@extends('layouts.app')


@section('content')


<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Services</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="{{url('/')}}">Home</a></li>


      <li class="active">Services</li>

    </ul>
  </div>
</div>
</div>
	
	
 <div class="in-content-insec-main">

	
@foreach($services as $service)
<div class="in-content-insec {{ $loop->even ? 'Ser-even' : 'Ser-odd' }}" id="{{ $service->slug }}">

    <div class="sicon-dots-14"></div>
    <div class="sicon-dots-15"></div>
    <div class="sicon-dots-16"></div>
    <div class="sicon-dots-17"></div>

    <div class="container">
        <div class="in-content-border">
            <div class="row justify-content-center align-items-center">

                {{-- TEXT COLUMN --}}
                <div class="col-lg-7 {{ $loop->odd ? 'order-lg-2' : 'order-lg-1' }}"
                     data-aos="zoom-in" data-aos-duration="800">
                    <div class="ipi-right">
                        <h2>{{ $service->name }}</h2>

                        {!!$service->description!!}
                      
                    </div>
                </div>

                {{-- IMAGE COLUMN --}}
                <div class="col-lg-5 {{ $loop->odd ? 'order-lg-1' : 'order-lg-2' }}"
                     data-aos="zoom-in" data-aos-duration="800">
                    <div class="ipi-left">
                        <img src="{{ asset('storage/'.$service->image) }}" alt="" width="100%">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endforeach
	
	
		
 
	
 
    </div>


 
@endsection