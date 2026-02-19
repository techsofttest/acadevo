<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    

    public function index()
    {
        
    $data = array();

    return view('pages.contact',$data);

    }
    

}
