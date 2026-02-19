<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Testimonial;

class TestimonialController extends Controller
{
   
    public function index()
    {
        
    $data['testimonials'] = Testimonial::all();

    return view('pages.testimonials',$data);

    }

}
