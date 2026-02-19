<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Certificate;
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
            'code' => 'required'
        ]);

        $certificate = Certificate::where('code', $request->code)->first();

        if (!$certificate) {
            return redirect()->back()->with('error', 'Invalid Certificate Code!');
        }

        $pdf = Pdf::loadView('pages.certificate-pdf', compact('certificate'));

        return $pdf->download('certificate-'.$certificate->code.'.pdf');
    }


}
