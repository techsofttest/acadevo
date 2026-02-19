<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        
    $data = array();

    return view('pages.about',$data);

    }
}
