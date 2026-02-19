<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

use App\Models\Product;

use App\Models\Testimonial;

use App\Models\Service;

class ServiceController extends Controller
{
   

    public function index()
    {

    $data['services'] = Service::orderBy('order')->get();

    return view('pages.services',$data);

    }


}

