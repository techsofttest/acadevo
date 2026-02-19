@extends('layouts.app')


@section('content')




<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Testimonials</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="index.html">Home</a></li>

	 

      <li class="active">Testimonials</li>

    </ul>
  </div>
</div>
</div>
	
	
 
 <div class="Testimonial-insec">
 
 <div class="container">
 <div class="row justify-content-center">


   
                        @foreach($testimonials as $testimonial)
<div class="col-lg-3 col-md-6 col-sm-6 d-flex">
    <div class="testi-box">

        <div class="tt-test-img">
            <img src="{{ asset('storage/'.$testimonial->image) }}" alt="">
        </div>

        <div class="testi-wrapp">
            <div class="box-quote">
                <img src="{{ asset('img/icon/quote2.svg') }}" alt="">
            </div>

            <div class="box-review">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa-sharp fa-solid fa-star"></i>
                @endfor
            </div>
        </div>

        @if(!empty($testimonial->title))
            <h4>{{ $testimonial->title }}</h4>
        @endif

        <div class="box-text">
            <p>{{ $testimonial->review }}</p>
        </div>

        <div class="box-profile">
            <div class="box-avater">
                <img src="{{ asset('storage/'.$testimonial->avatar) }}" alt="">
            </div>

            <div class="media-body">
                <h3 class="box-title">{{ $testimonial->name }}</h3>
            </div>
        </div>

    </div>
</div>
@endforeach

                         
                   
 
 
 </div>
 
 
 </div>
 
 </div>







@endsection