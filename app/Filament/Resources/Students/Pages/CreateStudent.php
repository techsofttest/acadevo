<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Models\Student;
use App\Models\StudentCourse;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    /**
     * Store created student IDs to handle actions like generating certificates.
     *
     * @var array<int>
     */
    protected array $createdStudentIds = [];

    protected function handleRecordCreation(array $data): Model
    {
        $studentsList = $data['students_list'] ?? [];
        $courses = $data['studentCourses'] ?? [];

        $instituteId = $data['institute_id'] ?? null;
        $class = $data['class'] ?? null;
        $division = $data['division'] ?? null;

        $createdRecords = [];

        DB::transaction(function () use ($studentsList, $courses, $instituteId, $class, $division, &$createdRecords) {
            // Fallback if repeater is empty
            if (empty($studentsList)) {
                $studentsList = [[
                    'full_name'   => $data['full_name'] ?? '',
                    'roll_number' => $data['roll_number'] ?? null,
                    'phone'       => $data['phone'] ?? '',
                    'email'       => $data['email'] ?? null,
                ]];
            }

            foreach ($studentsList as $item) {
                if (empty($item['full_name']) || empty($item['phone'])) {
                    continue;
                }

                $student = Student::create([
                    'full_name'    => $item['full_name'],
                    'phone'        => $item['phone'],
                    'roll_number'  => $item['roll_number'] ?? null,
                    'email'        => $item['email'] ?? null,
                    'institute_id' => $instituteId,
                    'class'        => $class,
                    'division'     => $division,
                ]);

                if (!empty($class)) {
                    app(\App\Services\Students\StudentClassService::class)->initializeHistory(
                        student: $student,
                        class: $class,
                        division: $division,
                        academicYear: null,
                        fromDate: now()
                    );
                }

                // Attach courses
                if (!empty($courses)) {
                    foreach ($courses as $courseItem) {
                        if (!empty($courseItem['course_id'])) {
                            StudentCourse::create([
                                'student_id' => $student->id,
                                'course_id'  => $courseItem['course_id'],
                            ]);
                        }
                    }
                }

                $createdRecords[] = $student;
                $this->createdStudentIds[] = $student->id;
            }
        });

        // Return the first created record (or new Student model) to satisfy Filament's return type
        return $createdRecords[0] ?? new Student();
    }

    protected function afterCreate(): void
    {
        // If single student, open their certificate automatically
        if (count($this->createdStudentIds) === 1) {
            $studentId = $this->createdStudentIds[0];
            $url = route('certificate.generate', ['student' => $studentId]);
            $this->js("window.open('{$url}', '_blank');");
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        $count = count($this->createdStudentIds);
        return $count > 1 
            ? "{$count} students created successfully." 
            : "Student created successfully.";
    }
}


