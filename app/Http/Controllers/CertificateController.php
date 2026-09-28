<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Institute;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;

class CertificateController extends Controller
{
    public function index()
    {
        return view('pages.certificate');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'class' => 'required',
            'division' => 'required',
        ], [
            'code.required' => 'Lab code is required.',
            'name.required' => 'Student name is required.',
            'class.required' => 'Class is required.',
            'division.required' => 'Division is required.',
        ]);

        $code = trim($request->code);
        $name = trim($request->name);
        $class = trim($request->class);
        $division = trim($request->division);

        // 1. Verify Institute / Lab Code
        $institute = Institute::where('lab_code', $code)->first();
        if (!$institute) {
            throw ValidationException::withMessages([
                'code' => 'Invalid Lab Code! No institute found with this lab code.'
            ]);
        }

        // 2. Verify Student Name within this institute
        $studentByName = Student::where('institute_id', $institute->id)
            ->whereRaw('LOWER(full_name) = ?', [strtolower($name)])
            ->first();

        if (!$studentByName) {
            throw ValidationException::withMessages([
                'name' => 'Student name not found for the provided Lab Code.'
            ]);
        }

        // 3. Verify Class & Division specifically
        $classMatches = strtolower(trim($studentByName->class ?? '')) === strtolower($class);
        $divisionMatches = strtolower(trim($studentByName->division ?? '')) === strtolower($division);

        if (!$classMatches && !$divisionMatches) {
            throw ValidationException::withMessages([
                'class' => 'Invalid Class specified for this student.',
                'division' => 'Invalid Division specified for this student.'
            ]);
        }

        if (!$classMatches) {
            throw ValidationException::withMessages([
                'class' => 'Invalid Class specified for this student.'
            ]);
        }

        if (!$divisionMatches) {
            throw ValidationException::withMessages([
                'division' => 'Invalid Division specified for this student.'
            ]);
        }

        // Format masked phone number (e.g., 9876543210 -> 9******210)
        $phone = trim($studentByName->phone ?? '');
        $maskedPhone = $this->maskPhoneNumber($phone);

        // Store student ID in session to allow OTP verification
        session([
            'otp_student_id' => $studentByName->id,
            'otp_masked_phone' => $maskedPhone,
        ]);

        return redirect()->back()->with([
            'show_otp' => true,
            'masked_phone' => $maskedPhone,
            'code' => $code,
            'name' => $name,
            'class' => $class,
            'division' => $division,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ], [
            'otp.required' => 'OTP is required.',
        ]);

        $otp = trim($request->otp);
        $studentId = session('otp_student_id');

        if (!$studentId) {
            return redirect()->route('certificate')->with('error', 'Session expired. Please verify student details again.');
        }

        if ($otp !== '0000') {
            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP! Please enter dummy OTP 0000.'
            ]);
        }

        $student = Student::with(['institute', 'studentCourses.course'])->find($studentId);

        if (!$student) {
            return redirect()->route('certificate')->with('error', 'Student record not found.');
        }

        $pdf = Pdf::loadView('pages.certificate-pdf', compact('student'));
        return $pdf->stream('certificate-'.$student->id.'.pdf');
    }

    public function generate(Student $student)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized access.');
        }

        $student->load(['institute', 'studentCourses.course']);
        $pdf = Pdf::loadView('pages.certificate-pdf', compact('student'));
        return $pdf->stream('certificate-'.$student->id.'.pdf');
    }

    private function maskPhoneNumber($phone)
    {
        if (empty($phone)) {
            return '******';
        }

        $length = strlen($phone);
        if ($length <= 4) {
            return substr($phone, 0, 1) . str_repeat('*', max(1, $length - 1));
        }

        // Show 1st digit, mask middle, show last 3 digits
        $first = substr($phone, 0, 1);
        $last = substr($phone, -3);
        $maskedLength = max(1, $length - 4);
        return $first . str_repeat('*', $maskedLength) . $last;
    }
}
