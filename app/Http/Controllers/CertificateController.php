<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

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
        ]);

        $student = Student::whereRaw('LOWER(full_name) = ?', [strtolower(trim($request->name))])
        ->whereRaw('LOWER(division) = ?', [strtolower(trim($request->class))])
        ->whereHas('institute', function ($q) use ($request) {
            $q->where('lab_code', trim($request->code));
        })
        ->with(['institute', 'studentCourses.course'])
        ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Invalid Student Details!');
        }


        $pdf = Pdf::loadView('pages.certificate-pdf', compact('student'));

        // Use stream() to preview in browser temporarily for designing
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


}
