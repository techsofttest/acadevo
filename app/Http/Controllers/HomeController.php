<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

use App\Models\Product;

use App\Models\Testimonial;

use App\Models\Service;

use App\Models\Offer;

class HomeController extends Controller
{
   

    public function index()
    {
    
    $data['categories'] = Category::all();

    $data['offers'] = Offer::with('category')->where('is_active', true)->get();

    $data['popular'] = Product::where('is_active', 1)->latest()->take(6)->get();

    $data['featured'] = Product::where('is_active', 1)->where('order', '>', 0)->take(6)->get();

    $data['new_arrivals'] = Product::where('is_active', 1)->latest()->take(6)->get();

    $data['featuredCategory'] = Category::with(['products' => function ($query) {
        $query->where('is_active', 1)
              ->latest()
              ->take(12);
    }])->where('is_featured', true)->first();

    $data['testimonials'] = Testimonial::all();

    $data['services'] = Service::orderBy('order')->get();

    return view('pages.index',$data);

    }


}

