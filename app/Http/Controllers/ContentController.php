<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Content;

class ContentController extends Controller
{
    


    public function privacy()
    {
        
    $data['content'] = Content::where('id',1)->first();

    return view('pages.cms',$data);

    }


    public function terms()
    {
        
    $data['content'] = Content::where('id',2)->first();

    return view('pages.cms',$data);

    }


    public function return()
    {
        
    $data['content'] = Content::where('id',3)->first();

    return view('pages.cms',$data);

    }


    public function shipping()
    {
        
    $data['content'] = Content::where('id',4)->first();

    return view('pages.cms',$data);

    }




}
