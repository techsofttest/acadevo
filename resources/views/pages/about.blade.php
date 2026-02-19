@extends('layouts.app')


@section('content')


<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>About Us</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="{{url('/')}}">Home</a></li>

      <li class="active">About Us</li>

    </ul>
  </div>
</div>
</div>
	
 
<div class="Aboo-sec">

<div class="container">
<div class="row  align-items-center">
<div class="col-lg-6">
<div class="Aboo-sec-img">
<div class="row justify-content-center">
<div class="col-lg-6 col-md-6 col-sm-6">
<img src="{{asset('img/about1.jpg')}}" alt="">
</div>
<div class="col-lg-6 col-md-6 col-sm-6">
<img src="{{asset('img/about2.jpg')}}" alt="">
</div>
<div class="col-lg-12 col-sm-12">
<img src="{{asset('img/about3.jpg')}}" class="aert3" alt="">
</div>

</div>
 
</div>
</div>

<div class="col-lg-6">
<div class="Aboo-sec-text">
  <div class="title-area   mb-20"> 
                        <h2 class="sec-title style1">About Acadevo</h2>
                    </div>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p>			
		<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>	

<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>		
</div>
</div>

</div>

</div>

</div>




		<section class="faettsec">
  <div class="container">
    <div class="row ff-row justify-content-center">
      <div class="col-md-6 col-lg-3 col-sm-6 process-box-wrap d-flex">
        <div class="process-box">
          <div class="process-box_icon"><img src="{{asset('img/f1-f.png')}}" alt="icon">
        
          </div>
          <h2 class="process-box_title">Free Shipping</h2>
          <p>Get 100% Free Shipping</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 col-sm-6 process-box-wrap d-flex">
        <div class="process-box">
          <div class="process-box_icon"><img src="{{asset('img/f2-f.png')}}" alt="icon">
        
          </div>
          <h2 class="process-box_title">Fast Delivery</h2>
          <p>Fast Delivery You Can Count On</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 col-sm-6 process-box-wrap d-flex">
        <div class="process-box">
          <div class="process-box_icon"><img src="{{asset('img/f3-f.png')}}" alt="icon">
          
          </div>
          <h2 class="process-box_title">Geniune Products</h2>
          <p>Real Quality. Real Products.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 col-sm-6 process-box-wrap d-flex">
        <div class="process-box">
          <div class="process-box_icon"><img src="{{asset('img/f4-f.png')}}" alt="icon">
          
          </div>
          <h2 class="process-box_title ">Secure Payments</h2>
          <p>Your Payments, Fully Protected</p>
        </div>
      </div>
    </div>
  </div>
</section>

 
@endsection