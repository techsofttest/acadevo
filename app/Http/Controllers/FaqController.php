<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Faq;

class FaqController extends Controller
{

    public function index()
    {
        
    $data['faqs'] = Product::order_by('order','asc')->get();

    return view('pages.faq',$data);

    }

}
