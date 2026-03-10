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
        ->with('institute')
        ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Invalid Student Details!');
        }


        $pdf = Pdf::loadView('pages.certificate-pdf', compact('student'));

        return $pdf->download('certificate-'.$student->id.'.pdf');

    }


}
