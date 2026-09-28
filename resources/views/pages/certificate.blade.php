@extends('layouts.app')


@section('content')


<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Certificate Download</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="{{url('/')}}">Home</a></li>

      <li class="active">Certificate Download</li>

    </ul>
  </div>
</div>
</div>
	
 
 <div class="Certificate-sec">
 <div class="container ">

<div class="col-12 col-lg-8 mx-auto justify-content-center">

<div class="ccerti-inner">
			
    @if(session('show_otp'))
        {{-- Step 2: OTP Verification Form --}}
        <div class="alert alert-success text-center mb-4">
            Details verified! OTP sent to <strong>{{ session('masked_phone') }}</strong>
        </div>

        <form method="POST" action="{{ route('certificate.verifyOtp') }}">
            @csrf

            <div class="row justify-content-center">
                <div class="col-lg-8 my-2">
                    <label class="form-label font-weight-bold">Enter OTP sent to registered mobile:</label>
                    <input type="text" name="otp" required autofocus 
                           class="form-control text-center font-weight-bold style-otp @error('otp') is-invalid @enderror" 
                           placeholder="Enter OTP (Use dummy: 0000)">
                    @error('otp')
                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6 my-3 d-flex gap-2">
                    <a href="{{ route('certificate') }}" class="btn btn-secondary w-50 py-2" style="border-radius:30px; display:inline-flex; align-items:center; justify-content:center;">
                        Back
                    </a>
                    <button type="submit" class="th-btn style3 w-50">
                        Download Certificate
                    </button>
                </div>
            </div>
        </form>

    @else
        {{-- Step 1: Student Verification Form --}}
        <form method="POST" action="{{ route('certificate.verify') }}">
            @csrf

            <div class="row justify-content-center">

                <div class="col-lg-6 my-2">
                    <input type="text" name="code" value="{{ old('code', session('code')) }}" required 
                           class="form-control @error('code') is-invalid @enderror" 
                           placeholder="Enter Lab Code">
                    @error('code')
                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 my-2">
                    <input type="text" name="name" value="{{ old('name', session('name')) }}" required 
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Enter Student Name">
                    @error('name')
                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="row justify-content-center">

                <div class="col-lg-6 my-2">
                    <input type="text" name="class" value="{{ old('class', session('class')) }}" required 
                           class="form-control @error('class') is-invalid @enderror" 
                           placeholder="Enter Class">
                    @error('class')
                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 my-2">
                    <input type="text" name="division" value="{{ old('division', session('division')) }}" required 
                           class="form-control @error('division') is-invalid @enderror" 
                           placeholder="Enter Division">
                    @error('division')
                        <div class="invalid-feedback d-block text-start">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6 my-3">
                    <button type="submit" class="th-btn style3 w-100">
                        Verify Student Details
                    </button>
                </div>
            </div>

        </form>
    @endif

@if(session('error'))
    <div class="alert alert-danger text-center mt-3">
        {{ session('error') }}
    </div>
@endif

			</div>
			
			<div id="myDiv" style="display:none;">
				<img src="assets/img/certificate.webp" alt="" width="100%">
			</div>

</div>

 </div>
 
 </div>
 
@endsection