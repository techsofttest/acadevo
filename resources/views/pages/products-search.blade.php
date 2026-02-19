@extends('layouts.app')


@section('content')



<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Products</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="{{url('/')}}">Home</a></li>

      <li class="active">Search Products</li>

    </ul>
  </div>
</div>
</div>
	
 
 <div class="product-inner-ss">
  <div class="container">
  
  <div class="row">


    <div class="col-12 text-center">
     <h2>Search Results For 

     @if(!empty($search_keyword))
        For "<span class="text-primary">{{ $search_keyword }}</span>"
    @endif

    </h2>
    </div>

    <div class="col-lg-12 col-md-8">
  
  <div class="ff-right-mainsec">
  
  <div class="ff-right-result">
  
  <div class="row align-items-center justify-content-between">
  <div class="col-auto">
  <p class="ff-right-result-p">{{ $products->count() }} products</p>
  </div>

  </div>
  </div>
  <!--  start   -->
  
  <div class="row">


  @include('components.product-grid',['products' => $products])

                      
  </div>
	  
     
     
    </div>
  <!-------- end --->
  </div>
  </div>
  
  </div>
</div>
</div>






@endsection


@section('footer_extras')



@endsection