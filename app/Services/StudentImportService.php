<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Institute;
use App\Models\Student;
use App\Models\StudentCourse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentImportService
{
    /**
     * Import students from an Excel / CSV file.
     *
     * Expected Excel Headers:
     * Full Name | Institute | Roll Number | Division | Class | Email | Phone | Course Name
     *
     * @param string $filePath Absolute path or temporary path to file
     * @return array ['total' => int, 'created' => int, 'updated' => int, 'errors' => array]
     */
    public function import(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        if (empty($rows)) {
            return [
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'errors' => ['The uploaded spreadsheet is empty.'],
            ];
        }

        // Detect header row and mapping
        $headerRow = null;
        $columnMap = [];
        $headerRowIndex = null;

        foreach ($rows as $rowIndex => $row) {
            $normalizedRow = array_map(function ($val) {
                return strtolower(trim((string) $val));
            }, $row);

            // Check if this row contains 'full name' or 'name' or 'student name'
            if (
                in_array('full name', $normalizedRow) ||
                in_array('fullname', $normalizedRow) ||
                in_array('name', $normalizedRow) ||
                in_array('student name', $normalizedRow)
            ) {
                $headerRow = $normalizedRow;
                $headerRowIndex = $rowIndex;

                foreach ($headerRow as $colLetter => $headerName) {
                    if (empty($headerName)) {
                        continue;
                    }

                    if (in_array($headerName, ['full name', 'fullname', 'name', 'student name'])) {
                        $columnMap['full_name'] = $colLetter;
                    } elseif (in_array($headerName, ['institute', 'institute name', 'lab code', 'college', 'school'])) {
                        $columnMap['institute'] = $colLetter;
                    } elseif (in_array($headerName, ['roll number', 'roll no', 'roll_number', 'rollno'])) {
                        $columnMap['roll_number'] = $colLetter;
                    } elseif (in_array($headerName, ['division', 'div', 'sec', 'section'])) {
                        $columnMap['division'] = $colLetter;
                    } elseif (in_array($headerName, ['class', 'standard', 'std', 'grade', 'department', 'dept'])) {
                        $columnMap['class'] = $colLetter;
                    } elseif (in_array($headerName, ['email', 'email address', 'email_address'])) {
                        $columnMap['email'] = $colLetter;
                    } elseif (in_array($headerName, ['phone', 'mobile', 'mobile number', 'phone number', 'contact'])) {
                        $columnMap['phone'] = $colLetter;
                    } elseif (in_array($headerName, ['course name', 'course', 'courses', 'program'])) {
                        $columnMap['course'] = $colLetter;
                    }
                }
                break;
            }
        }

        if (!$headerRowIndex || !isset($columnMap['full_name'])) {
            // Default sequential fallback: A: Full Name, B: Institute, C: Roll Number, D: Division, E: Class, F: Email, G: Phone, H: Course Name
            $columnMap = [
                'full_name'   => 'A',
                'institute'   => 'B',
                'roll_number' => 'C',
                'division'    => 'D',
                'class'       => 'E',
                'email'       => 'F',
                'phone'       => 'G',
                'course'      => 'H',
            ];
            $headerRowIndex = 1;
        }

        $createdCount = 0;
        $updatedCount = 0;
        $totalProcessed = 0;
        $errors = [];

        // Preload caches for fast lookups
        $institutesByName = Institute::all()->keyBy(fn($i) => strtolower(trim($i->name)));
        $institutesByCode = Institute::all()->keyBy(fn($i) => strtolower(trim($i->lab_code ?? '')));
        $coursesByName = Course::all()->keyBy(fn($c) => strtolower(trim($c->name)));

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex <= $headerRowIndex) {
                continue;
            }

            $fullName = isset($columnMap['full_name']) && isset($row[$columnMap['full_name']]) ? trim((string)$row[$columnMap['full_name']]) : '';
            if (empty($fullName)) {
                // Skip completely empty rows
                $allEmpty = true;
                foreach ($row as $cellVal) {
                    if (!empty(trim((string)$cellVal))) {
                        $allEmpty = false;
                        break;
                    }
                }
                if ($allEmpty) {
                    continue;
                }
                $errors[] = "Row {$rowIndex}: Skipped because Full Name is empty.";
                continue;
            }

            $totalProcessed++; 

            $instituteVal = isset($columnMap['institute']) && isset($row[$columnMap['institute']]) ? trim((string)$row[$columnMap['institute']]) : '';
            $rollNumber = isset($columnMap['roll_number']) && isset($row[$columnMap['roll_number']]) ? trim((string)$row[$columnMap['roll_number']]) : null;
            $division = isset($columnMap['division']) && isset($row[$columnMap['division']]) ? trim((string)$row[$columnMap['division']]) : null;
            $classVal = isset($columnMap['class']) && isset($row[$columnMap['class']]) ? trim((string)$row[$columnMap['class']]) : null;
            $email = isset($columnMap['email']) && isset($row[$columnMap['email']]) ? trim((string)$row[$columnMap['email']]) : null;
            $phone = isset($columnMap['phone']) && isset($row[$columnMap['phone']]) ? trim((string)$row[$columnMap['phone']]) : null;
            $courseVal = isset($columnMap['course']) && isset($row[$columnMap['course']]) ? trim((string)$row[$columnMap['course']]) : '';

            // Clean phone string (keep digits)
            if (!empty($phone)) {
                // Handle scientific notation from excel or decimals
                if (is_numeric($phone) && strpos($phone, '.') !== false) {
                    $phone = (string) (int) floatval($phone);
                }
            }

            // Resolve Institute (lookup by name, or by lab_code, or create if missing)
            $instituteId = null;
            if (!empty($instituteVal)) {
                $lowerInst = strtolower($instituteVal);
                if (isset($institutesByName[$lowerInst])) {
                    $instituteId = $institutesByName[$lowerInst]->id;
                } elseif (isset($institutesByCode[$lowerInst])) {
                    $instituteId = $institutesByCode[$lowerInst]->id;
                } else {
                    $newInst = Institute::create([
                        'name'     => $instituteVal,
                        'lab_code' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $instituteVal), 0, 10)) ?: 'INST-' . rand(100, 999),
                    ]);
                    $institutesByName[$lowerInst] = $newInst;
                    $institutesByCode[strtolower($newInst->lab_code)] = $newInst;
                    $instituteId = $newInst->id;
                }
            }

            try {
                DB::beginTransaction();

                // Find existing student by (phone and full_name) or (email and full_name) or (roll_number and institute_id)
                $studentQuery = Student::query();
                $matchFound = false;

                if (!empty($phone)) {
                    $studentQuery->where('phone', $phone);
                    $matchFound = true;
                } elseif (!empty($email)) {
                    $studentQuery->where('email', $email);
                    $matchFound = true;
                } elseif (!empty($rollNumber) && $instituteId) {
                    $studentQuery->where('roll_number', $rollNumber)->where('institute_id', $instituteId);
                    $matchFound = true;
                }

                $student = $matchFound ? $studentQuery->first() : null;

                if ($student) {
                    $student->update([
                        'full_name'    => $fullName,
                        'institute_id' => $instituteId ?? $student->institute_id,
                        'roll_number'  => $rollNumber ?? $student->roll_number,
                        'division'     => $division ?? $student->division,
                        'class'        => $classVal ?? $student->class,
                        'email'        => $email ?? $student->email,
                        'phone'        => $phone ?? $student->phone,
                    ]);
                    $updatedCount++;
                } else {
                    $student = Student::create([
                        'full_name'    => $fullName,
                        'institute_id' => $instituteId,
                        'roll_number'  => $rollNumber,
                        'division'     => $division,
                        'class'        => $classVal,
                        'email'        => $email,
                        'phone'        => $phone ?? '',
                    ]);
                    $createdCount++;

                     if (!empty($classVal)) {
                        app(\App\Services\Students\StudentClassService::class)->initializeHistory(
                            student: $student,
                            class: $classVal,
                            division: $division,
                            academicYear: null,
                            fromDate: now()
                        );
                    }
                }

                // Resolve and attach courses (comma-separated or single)
                if (!empty($courseVal)) {
                    $courseNames = array_map('trim', explode(',', $courseVal));
                    foreach ($courseNames as $cName) {
                        if (empty($cName)) {
                            continue;
                        }
                        $lowerCName = strtolower($cName);
                        if (!isset($coursesByName[$lowerCName])) {
                            $newCourse = Course::create([
                                'name' => $cName,
                            ]);
                            $coursesByName[$lowerCName] = $newCourse;
                        }
                        $courseObj = $coursesByName[$lowerCName];

                        // Attach if not already attached
                        StudentCourse::firstOrCreate([
                            'student_id' => $student->id,
                            'course_id'  => $courseObj->id,
                        ]);
                    }
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $errors[] = "Row {$rowIndex} ({$fullName}): " . $e->getMessage();
            }
        }

        return [
            'total'   => $totalProcessed,
            'created' => $createdCount,
            'updated' => $updatedCount,
            'errors'  => $errors,
        ];
    }
}
