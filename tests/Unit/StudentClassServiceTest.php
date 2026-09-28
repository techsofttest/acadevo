<?php

namespace Tests\Unit;

use App\Models\Student;
use App\Models\StudentClassHistory;
use App\Services\Students\StudentClassService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StudentClassServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StudentClassService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StudentClassService();
    }

    public function test_can_initialize_history_for_new_student(): void
    {
        $student = Student::create([
            'full_name' => 'Alice Smith',
            'phone' => '1234567890',
            'class' => '10th',
            'division' => 'A',
        ]);

        $history = $this->service->initializeHistory(
            student: $student,
            class: '10th',
            division: 'A',
            academicYear: '2025-26',
            fromDate: '2025-06-01'
        );

        $this->assertNotNull($history);
        $this->assertEquals('10th', $history->class);
        $this->assertEquals('A', $history->division);
        $this->assertEquals('2025-26', $history->academic_year);
        $this->assertEquals('2025-06-01', $history->from_date->format('Y-m-d'));
        $this->assertNull($history->to_date);
    }

    public function test_can_change_student_class_and_close_previous_history(): void
    {
        $student = Student::create([
            'full_name' => 'Bob Builder',
            'phone' => '9876543210',
            'class' => '5th',
            'division' => 'B',
        ]);

        $this->service->initializeHistory(
            student: $student,
            class: '5th',
            division: 'B',
            academicYear: '2025-26',
            fromDate: '2025-06-01'
        );

        $newHistory = $this->service->changeClass(
            student: $student,
            class: '6th',
            division: 'A',
            academicYear: '2026-27',
            fromDate: Carbon::parse('2026-06-01')
        );

        $student->refresh();
        $this->assertEquals('6th', $student->class);
        $this->assertEquals('A', $student->division);

        $this->assertCount(2, $student->classHistories);

        $oldHistory = $student->classHistories()->whereNotNull('to_date')->first();
        $this->assertNotNull($oldHistory);
        $this->assertEquals('5th', $oldHistory->class);
        $this->assertEquals('B', $oldHistory->division);
        $this->assertEquals('2025-06-01', $oldHistory->from_date->format('Y-m-d'));
        $this->assertEquals('2026-05-31', $oldHistory->to_date->format('Y-m-d'));

        $this->assertEquals('6th', $newHistory->class);
        $this->assertEquals('A', $newHistory->division);
        $this->assertEquals('2026-06-01', $newHistory->from_date->format('Y-m-d'));
        $this->assertNull($newHistory->to_date);
    }

    public function test_changing_division_only_creates_new_history(): void
    {
        $student = Student::create([
            'full_name' => 'Charlie Brown',
            'phone' => '5555555555',
            'class' => '6th',
            'division' => 'A',
        ]);

        $this->service->initializeHistory(
            student: $student,
            class: '6th',
            division: 'A',
            academicYear: '2026-27',
            fromDate: '2026-06-01'
        );

        $newHistory = $this->service->changeClass(
            student: $student,
            class: '6th',
            division: 'B',
            academicYear: '2026-27',
            fromDate: Carbon::parse('2026-09-01')
        );

        $student->refresh();
        $this->assertEquals('6th', $student->class);
        $this->assertEquals('B', $student->division);

        $this->assertCount(2, $student->classHistories);

        $oldHistory = $student->classHistories()->whereNotNull('to_date')->first();
        $this->assertEquals('2026-08-31', $oldHistory->to_date->format('Y-m-d'));
    }

    public function test_rejects_duplicate_class_and_division_assignment(): void
    {
        $this->expectException(ValidationException::class);

        $student = Student::create([
            'full_name' => 'David Hassel',
            'phone' => '4444444444',
            'class' => '6th',
            'division' => 'A',
        ]);

        $this->service->changeClass(
            student: $student,
            class: '6th',
            division: 'A',
            academicYear: '2026-27',
            fromDate: Carbon::parse('2026-06-01')
        );
    }

    public function test_rejects_invalid_date_range(): void
    {
        $this->expectException(ValidationException::class);

        $student = Student::create([
            'full_name' => 'Eve Adams',
            'phone' => '3333333333',
            'class' => '5th',
            'division' => 'A',
        ]);

        $this->service->initializeHistory(
            student: $student,
            class: '5th',
            division: 'A',
            academicYear: '2025-26',
            fromDate: '2025-06-01'
        );

        // Attempt from_date earlier than or equal to previous active history
        $this->service->changeClass(
            student: $student,
            class: '6th',
            division: 'A',
            academicYear: '2026-27',
            fromDate: Carbon::parse('2025-05-01')
        );
    }
}
