@extends('layouts.app')


@section('content')






	
 <div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>About Us</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="index.html">Home</a></li>

	 

      <li class="active">Sign Up </li>

    </ul>
  </div>
</div>
</div>
	
 
 <div class="Sigbupmainn">
  <div class="container ">
 <div class="Sign-in-sec  ">
 

 
 <div class="row  align-items-center">
  <div class=" col-lg-6 col-md-6 ">
  <img src="assets/img/sign-inn.jpg" alt="" width="100%">
  </div>
 <div class=" col-lg-6 col-md-6 ">
	 
	<div class="register-inner">
			

    <form action="{{ route('customer.register') }}" method="POST">
    @csrf

    <h3><span>Stop Waiting,</span> Free Register Now</h3>

    <div class="row">

        {{-- First Name --}}
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label>First Name</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}"
                       required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Last Name --}}
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label>Last Name</label>
                <input type="text"
                       name="lname"
                       class="form-control"
                       value="{{ old('lname') }}">
                @error('lname')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div class="col-lg-12">
            <div class="form-group">
                <label>Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email') }}"
                       required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Password --}}
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label>Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       required>
            </div>
        </div>

        <div class="col-lg-12">
            <button type="submit" class="th-btn style3">
                Register Now
            </button>
        </div>

    </div>
    </form>



	</div>


	 
	 </div>
 </div>
 </div>
 </div>
 </div>





@endsection