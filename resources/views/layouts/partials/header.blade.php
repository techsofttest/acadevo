<div class="th-menu-wrapper">
        <div class="th-menu-area text-center"><button class="th-menu-toggle"><i class="fal fa-times"></i></button>
            <div class="mobile-logo"><a href="{{url('/')}}"><h3>Acadevo </h3></a></div>
            <div class="th-mobile-menu">
                <ul>
                    <li ><a href="{{url('/')}}">Home</a>  </li>

 <li class="menu-item-has-children"><a href="{{url('products')}}">Products</a>
   <ul class="sub-menu">

@foreach($header_categories as $category)
<li class="menu-item-has-children"><a href="{{url('category')}}/{{$category->slug}}">{{ $category->name }}</a>
@if($category->children->isNotEmpty())
  <ul class="sub-menu">

  @foreach($category->children as $sub)
  <li><a href="{{url('category')}}/{{$category->slug}}/{{$sub->slug}}">{{ $sub->name }}</a></li>
  @endforeach

</ul>
 @endif

</li>
@endforeach



</ul>
 </li>
                  
<li><a href="{{url('about')}}"> About Us</a>  </li>
<li><a href="{{url('services')}}">Services</a>  </li>
<li><a href="{{url('testimonials')}}">Testimonials</a>  </li>

  <li><a href="{{url('contact')}}">Contact Us</a>  </li>
  
   <li><a href="{{url('certificate')}}" class="th-btn style3">Certificate Download</a>  </li>
                </ul>
            </div>
        </div>
    </div>


    <header class="th-header header-layout2">
        <div class="header-top" data-bg-src="{{asset('img/shape/header-pattern.png')}}">
            <div class="container th-container">
                <div class="row justify-content-center justify-content-md-between align-items-center gy-2">
                    <div class="col-auto">
                        <div class="header-links">
                            <ul>
                                <li><img src="{{asset('img/icon/phone2.svg')}}" alt=""><a href="tel:+00123456789">098951 20996</a></li>
                                <li><i class="fa-solid fa-location-dot"></i>Chinnakada, Kollam, Kerala</li>
                            </ul>
                        </div>
                    </div>

             <div class="col-auto">
			 <div class="row">
			                       <div class="col-auto cer-gen-auto">
					  <a class="cert-btn th-btn btn-white style3" href="{{url('certificate')}}">Certificate Download <svg aria-hidden="true" class="e-font-icon-svg e-fas-chevron-circle-right" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm113.9 231L234.4 103.5c-9.4-9.4-24.6-9.4-33.9 0l-17 17c-9.4 9.4-9.4 24.6 0 33.9L285.1 256 183.5 357.6c-9.4 9.4-9.4 24.6 0 33.9l17 17c9.4 9.4 24.6 9.4 33.9 0L369.9 273c9.4-9.4 9.4-24.6 0-34z"></path></svg></a>
					  </div>
					  
					  <div class="col-auto">
                      <ul class="hsocial-linksff">
			         <li><a href="https://api.whatsapp.com/send/?phone=+098951 20996&text=%2AHey     Acadevo+&app_absent=0"   target="_blank"><img src="assets/img/l1.png" alt=""></a></li>
                
                <li><a href="#" target="_blank"><img src="{{asset('img/l4.png')}}" alt=""></a></li>
			    <li><a href="#" target="_blank"><img src="{{asset('img/l5.png')}}" alt=""></a></li>
				<li><a href="#" target="_blank"><img src="{{asset('img/l6.png')}}" alt=""></a></li>
				<li><a href="#" target="_blank"><img src="{{asset('img/l7.png')}}" alt=""></a></li>
				<li><a href="#" target="_blank"><img src="{{asset('img/l8.png')}}" alt=""></a></li>

              </ul>
                    </div>
					</div>
					</div>
                </div>
            </div>
        </div>
        <div class="menu-top">
            <div class="container th-container">
                <div class="row justify-content-center justify-content-lg-between align-items-center gy-2">
                    <div class="col-auto logo-auuu" >
                        <div class="header-logo d-none d-lg-block"><a href="{{url('/')}}"><h3>Acadevo </h3></a></div>
                    </div>
                    <div class="col-auto   d-lg-block me-xl-auto me-lg-auto">
                        <div class="header-form">
                        
                        
                        <div class="row justify-content-between align-items-center">
                                <div class="col-auto ser-cat-cc">
                            <div class="currency-menu">

                                
                            <select  name="category" class="form-select nice-select">
                                <option selected="" value="">All Categories</option>

                                @foreach($header_categories as $search_category)
                                            <option value="{{ $search_category->slug }}">
                                                {{ $search_category->name }}
                                            </option>
                                @endforeach
                                
                                </select>
                                
                                </div>
                                </div>

                                <div class="col-auto me-lg-auto">


                                    <!-- Header Search Start -->
                                    <div class="top-search">

                                      <form action="{{ route('product.search') }}" method="GET" class="header-search">
                                      
                                            <div class="box-search">
                                                
                         <input type="text" 
                            name="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="Search for a product">
                        
                        <button type="submit" class="th-btn"><i class="far fa-search"></i></button>
                                             
                                            </div>

                                            </form>

                                        
                                    </div>

                                    <!-- Header Search End -->


                                </div>
                            </div>

                            


                        </div>
                    </div>
                    <div class="col-auto">
					   <div class="row align-items-center">
					      <div class="col-auto">
						     <div class="header2-wrapp">
                            <div class="header-info-wrap">

                                @php
                                    $customer = auth('customer')->user();
                                @endphp


                                @if($customer)

                                <div class="header-info">
                                  <a href="{{url('profile')}}" class="icon-btn"><img src="{{asset('img/icon/user.svg')}}" alt=""></a>
                                    <div class="media-body"> 
                                        <p class="header-info_link">
                                            <a href="{{url('profile')}}">{{ explode(' ',$customer->name)[0]}}</a></p>
                                    </div>
                                </div>

                                @else


                                <div class="header-info">
                                  <a href="#" data-bs-toggle="modal" data-bs-target="#youmyModal"  class="icon-btn"><img src="{{asset('img/icon/user.svg')}}" alt=""></a>
                                    <div class="media-body"> 
                                        <p class="header-info_link"><a href="#" data-bs-toggle="modal" data-bs-target="#youmyModal">Login / Register</a></p>
                                    </div>
                                </div>


                                @endif


                                @if($customer)
								   <div class="header-info">
                                    <a href="{{url('wishlist')}}" class="icon-btn"><i  style="top: 4px;
    position: relative;" class="fa fa-heart"></i></a>
                                    
                                </div>
                                @endif
                            
                            </div>
                        </div>
					      </div>
						  
 <div class="col-auto">
 <div class="header-info">
 <div class="dropdown">
<div class="icon-btn">
<img src="{{asset('img/icon/bag2.svg')}}" alt=""><span class="badge cart-count" id="cart-count">{{ collect(session('cart'))->sum('qty') ?? 0 }}</span></div>
<div>  

<div class="cart-dropdown-menu">

<div class="widget woocommerce widget_shopping_cart style2" id="mini-cart-wrapper">

@include('layouts.partials.mini_cart', ['cart'=>session('cart'),'total'=>collect(session('cart'))->sum(fn($i)=>$i['price']*$i['qty'])])

 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
					
                     
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky-wrapper">
            <div class="menu-area">
                <div class="container th-container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto d-none d-xl-block">
 <div class="category-menu-wrap"><a class="menu-expand" href="#"><span class="icon"><i class="fa-light fa-bars"></i></span>All Categories<i class="fa-light fa-angle-down"></i></a>
 <nav class="category-menu">

 <ul>

 @foreach($header_categories as $category)

  <li class="menu-item-has-children"><a href="javascript:void(0);"> {{ $category->name }}</a>
         <div class="sub-menu">
    <div class="row gx-0">
    <div class="col-4">
    <div class="mega-menu">
  <h3 class="box-title"><a href="{{ url('category/' . $category->slug) }}"> {{ $category->name }}</a></h3>

 <ul class="">

    @if($category->children->count())
        <ul>
                @foreach($category->children as $sub)
                <li>
                <a href="{{ url('category/' . $category->slug . '/' . $sub->slug) }}">
                {{ $sub->name }}
                </a>
                </li>
                @endforeach
        </ul>
    @endif

 
  </ul>

  </div>
  </div>
   <div class="col-8">
  <div class="mega-menu">
  <div class="box-img mb-0"><img src="{{asset('storage')}}/{{$category->image}}" alt=""></div>
  </div>
  </div>
  </div>
  </div>
  </li>


  @endforeach

  <!--- end --->
     
        </ul>
    </nav>
    </div>
    </div>
  <div class="col-auto me-xl-auto">
 <nav class="main-menu d-none d-lg-inline-block">
 <ul>

<li  ><a href="{{url('/')}}">Home</a>  </li>

<li class="menu-item-has-children"><a href="{{url('products')}}">Categories</a>

                                        <ul class="mega-menu mega-menu-content">

                                            <li>

                                                <div class="container">

                                                    <div class="row gy-4">

                                                  

                                        @foreach($header_categories as $category)
                                        <div class="col-lg-3">
                                            <div class="mega-menu-box">

                                                <h3 class="mega-menu-title">
                                                    <a href="{{ url('category/' . $category->slug) }}">
                                                        {{ $category->name }}
                                                    </a>
                                                </h3>

                                                @if($category->children->count())
                                                    <ul>
                                                        @foreach($category->children as $sub)
                                                            <li>
                                                                <a href="{{ url('category/' . $category->slug . '/' . $sub->slug) }}">
                                                                    {{ $sub->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                            </div>
                                        </div>
                                    @endforeach

                                                    

                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>


  <li  ><a href="{{url('products')}}">Products</a>  </li>
                            
  <li  ><a href="{{url('about')}}">About</a>  </li>
    <li  ><a href="{{url('services')}}">Services</a>  </li>

	  <li  ><a href="{{url('testimonials')}}">Testimonials</a>  </li>
	    <li  ><a href="{{url('contact')}}">Contact</a>  </li>
                                </ul>
                            </nav>
                            <div class="header-logo d-block d-lg-none"><a href="{{url('/')}}"> <h3>Acadevo </h3></a></div>
                        </div>
                        <div class="col-auto">
                            <div class="call-btn d-none d-lg-inline-flex">
                                <div class="box-icon"><img src="{{asset('img/icon/truck2.svg')}}" alt=""></div>
                                <div class="media-body"><span class="box-label">Free Shipping</span>
                                    <h3 class="box-link mb-0">Over Order ₹1000</h3>
                                </div>
                            </div><button type="button" class="th-menu-toggle d-block d-lg-none"><i class="far fa-bars"></i></button></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

