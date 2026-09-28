<?php

namespace App\Services\Students;

use App\Models\Student;
use App\Models\StudentClassHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentClassService
{
    /**
     * Change a student's class and division, updating the history.
     *
     * @param Student $student
     * @param string $class
     * @param string|null $division
     * @param string|null $academicYear
     * @param Carbon|string $fromDate
     * @return StudentClassHistory
     * @throws ValidationException
     */
    public function changeClass(
        Student $student,
        string $class,
        ?string $division = null,
        ?string $academicYear = null,
        Carbon|string $fromDate = null
    ): StudentClassHistory {
        $class = trim($class);
        $division = $division !== null ? trim($division) : null;
        if ($division === '') {
            $division = null;
        }

        $academicYear = $academicYear !== null ? trim($academicYear) : null;
        if ($academicYear === '') {
            $academicYear = null;
        }

        if (empty($class)) {
            throw ValidationException::withMessages([
                'class' => ['The class field is required.'],
            ]);
        }

        $fromDate = $fromDate ? Carbon::parse($fromDate) : Carbon::today();

        // Check if student already has this exact class and division
        $currentClassNormalized = trim((string) $student->class);
        $currentDivisionNormalized = trim((string) $student->division);
        $newDivisionNormalized = (string) ($division ?? '');

        if (
            strcasecmp($currentClassNormalized, $class) === 0 &&
            strcasecmp($currentDivisionNormalized, $newDivisionNormalized) === 0
        ) {
            $divisionLabel = $division ? " - Division {$division}" : '';
            throw ValidationException::withMessages([
                'class' => ["The student is already assigned to Class {$class}{$divisionLabel}."],
            ]);
        }

        return DB::transaction(function () use ($student, $class, $division, $academicYear, $fromDate) {
            // Find current active history record
            /** @var StudentClassHistory|null $currentHistory */
            $currentHistory = $student->classHistories()
                ->whereNull('to_date')
                ->latest('from_date')
                ->first();

            if ($currentHistory) {
                // Ensure the new from_date is not prior to the existing history's from_date
                if ($fromDate->lessThanOrEqualTo($currentHistory->from_date)) {
                    throw ValidationException::withMessages([
                        'from_date' => ['The effective from date must be after the current active class start date (' . $currentHistory->from_date->format('Y-m-d') . ').'],
                    ]);
                }

                $currentHistory->update([
                    'to_date' => $fromDate->copy()->subDay()->format('Y-m-d'),
                ]);
            }

            // Update student current class and division
            $student->update([
                'class' => $class,
                'division' => $division,
            ]);

            // Create new active history record
            return $student->classHistories()->create([
                'class' => $class,
                'division' => $division,
                'academic_year' => $academicYear,
                'from_date' => $fromDate->format('Y-m-d'),
                'to_date' => null,
            ]);
        });
    }

    /**
     * Create the initial class history for a newly created student.
     *
     * @param Student $student
     * @param string|null $class
     * @param string|null $division
     * @param string|null $academicYear
     * @param Carbon|string|null $fromDate
     * @return StudentClassHistory|null
     */
    public function initializeHistory(
        Student $student,
        ?string $class = null,
        ?string $division = null,
        ?string $academicYear = null,
        Carbon|string|null $fromDate = null
    ): ?StudentClassHistory {
        $class = $class !== null ? trim($class) : trim((string) $student->class);
        $division = $division !== null ? trim($division) : ($student->division ? trim((string) $student->division) : null);

        if (empty($class)) {
            return null;
        }

        $fromDate = $fromDate ? Carbon::parse($fromDate) : Carbon::today();

        return $student->classHistories()->create([
            'class' => $class,
            'division' => $division ?: null,
            'academic_year' => $academicYear ?: null,
            'from_date' => $fromDate->format('Y-m-d'),
            'to_date' => null,
        ]);
    }
}
