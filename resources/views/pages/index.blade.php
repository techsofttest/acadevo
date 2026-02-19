@extends('layouts.app')


@section('content')


<div class="th-hero-wrapper hero-2 slider-area" id="hero" data-bg-src="assets/img/hero/hero_bg_2.png">
        <div class="swiper th-slider" id="heroSlide2" data-slider-options='{"effect":"fade","autoHeight":true}'>
            <div class="swiper-wrapper">


                <div class="swiper-slide">
                    <div class="hero-inner">
                        <div class="container th-container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="hero-style2"> 
                                        <h1 class="hero-title" data-ani="slideinup" data-ani-delay="0.4s">Best Boards, Robotics & Electronics Products</h1>
                                        <p class="price" data-ani="slideinup" data-ani-delay="0.6s"> Free Shipping On Order ₹1000+</p>
                                        <div class="btn-group" data-ani="slideinup" data-ani-delay="0.7s"><a href="{{url('products')}}" class="th-btn btn-white style3">Buy Now</a></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="hero-img" data-ani="slideinrighthero" data-ani-delay="0.8s"><img src="{{asset('img/banner1.png')}}" alt="Image"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
            <div class="slider-pagination"></div>
        </div>
    </div>
	
	
	
	 <section class="category-area" id="category-sec">
        <div class="container th-container">
      
            <div class="slider-area">
                <div class="swiper th-slider has-shadow  " data-slider-options='{"spaceBetween":16,"breakpoints":{"0":{"slidesPerView":2},"380":{"slidesPerView":"3"},"576":{"slidesPerView":"4"},"768":{"slidesPerView":"5"},"992":{"slidesPerView":"8"},"1300":{"slidesPerView":"8"},"1600":{"slidesPerView":"8"}}}'>
                    <div class="swiper-wrapper">


                  @foreach($categories as $category)
                  <div class="swiper-slide">
                  <div class="head_rounded">
                  <div class="cat-img"><a href="product-category.html"  ><img src="{{asset('storage')}}/{{$category->image}}" alt=""></a></div>
                  <h3 class="cat-name"><a class="text-inherit" href="product-category.html"  >{{$category->name}}</a></h3>
                  </div>
                  </div>
                  @endforeach
          
                       
                       
                    
                    </div>
                </div>
            </div>
        </div>
    </section>
	
	
	
	
	<div class="product-innersec home-three home-threettt">
 
		<div class="container">
 
		<div class="row justify-content-center">


        @foreach($offers as $offer)

	 	<div class="col-lg-3 col-md-6 col-sm-6 d-flex aos-init aos-animate" data-aos="zoom-in" data-aos-duration="800">
		
	<a href="{{route('category.show',$offer->category->slug)}}" class="single-offer-box">
						<div class="single-offer-thumb">
							<img src="{{ asset('storage/'.$offer->category->image) }}" alt="{{ $offer->category->name }}">
						</div>
						
					
						<div class="offer-bottom-title">
						
						<div class="per-oo">
						<span>upto</span>
						
						<h3>{{ $offer->percentage }}% <b>Off</b></h3>
						</div>
							<h4>{{ $offer->category->name }}</h4>
						</div>
					</a>
			</div>

            @endforeach
		
	
				 
		</div>
		
		
		</div>
		
		
		</div>
	
	
      <section class="popular-productsec overflow-hidden overflow-hidden">
        <div class="container th-container">
            <div class="row justify-content-lg-between justify-content-center align-items-end mb-45">
                <div class="col-lg-auto col-md-auto col-auto">
                    <div class="title-area    mb-20 mb-xl-0"> 
                        <h2 class="sec-title style1">Popular Products</h2>
                    </div>
                </div>
                <div class="col-lg-8 col-md-auto">
                    <div class="nav tab-menu style2 indicator-active justify-content-xl-end justify-content-center mb-0" id="tab-menu1" role="tablist"><button class="tab-btn th-btn active" id="nav-one-tab" data-bs-toggle="tab" data-bs-target="#nav-one" type="button" role="tab" aria-controls="nav-one" aria-selected="true">Best Sellers</button> <button class="tab-btn th-btn" id="nav-two-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-two" type="button" role="tab" aria-controls="nav-two" aria-selected="false">New Arrivals</button> <button class="tab-btn th-btn" id="nav-three-tab" data-bs-toggle="tab" data-bs-target="#nav-three"
                            type="button" role="tab" aria-controls="nav-three" aria-selected="false">Featured Products</button>  </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="nav-one" role="tabpanel" aria-labelledby="nav-one-tab">
                    <div class="row gy-4">



                  @include('components.product-grid',['products' => $popular])


                      
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-two" role="tabpanel" aria-labelledby="nav-two-tab">

                    <div class="row gy-4">
					
                     @include('components.product-grid',['products' => $new_arrivals])

                    </div>

                </div>
                <div class="tab-pane fade" id="nav-three" role="tabpanel" aria-labelledby="nav-three-tab">
                   <div class="row gy-4">
                        
				
                     @include('components.product-grid',['products' => $featured])

                      
                    </div>
                </div>

            </div>
        </div>
    </section>
 
    
 
        <section class="collection-area overflow-hidden sermm-sec">
        <div class="container th-container5">
		
		
		<div class="title-area text-center   mb-30 "> 
                        <h2 class="sec-title style1">Our Sevices</h2>
                    </div>
            <div class="row gy-4 justify-content-center">



            @foreach($services as $service)

                
            <div class="col-md-6 col-xl-4 col-lg-4 callection-card_wrapp">
                    <div class="callection-card">
                        <div class="box-img {{ $loop->even ? 'order-1' : '' }}"  ><img src="{{asset('storage')}}/{{$service->image}}" alt="img"></div>
                        <div class="box-content">
                            <h3 class="box-title">{{$service->name}}</h3>
            <p class="box-text">
             {{ \Illuminate\Support\Str::words(strip_tags($service->description), 50, '…') }}
            </p>
            
            <a href="{{url('services')}}" class="th-btn black-btn th-icon">Read More</a></div>
                    </div>
                </div>

            @endforeach

               
            </div>
        </div>
    </section>
   
    
	
	
	@if($featuredCategory && $featuredCategory->products->count())

<section class="Otherpp-sec overflow-hidden">
    <div class="container th-container">
        <div class="row justify-content-lg-between justify-content-center align-items-end">
            <div class="col-lg-5">
                <div class="title-area mb-35">
                    <h2 class="sec-title style1">
                        {{ $featuredCategory->name }} Products
                    </h2>
                </div>
            </div>
        </div>

        <div class="slider-area">
            <div class="swiper th-slider has-shadow productSlide8"
                data-slider-options='{"spaceBetween":16,"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":2},"992":{"slidesPerView":3},"1200":{"slidesPerView":4}}}'>

                <div class="swiper-wrapper">

                    @foreach($featuredCategory->products as $product)
                        <div class="swiper-slide">
                            <div class="product-grid style2 style8">

                                <div class="box-img">
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                                    </a>

                                    @if($product->discount_percent)
                                        <span class="product-tag">
                                            {{ $product->discount_percent }}% off
                                        </span>
                                    @endif
                                </div>

                                <div class="product-grid-content">

                                    <h3 class="box-title">
                                        <a href="{{ route('product.show', $product->slug) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <span class="box-price">
                                        @if($product->original_price)
                                            <del>₹{{ $product->original_price }}</del>
                                        @endif
                                        ₹{{ $product->price }}
                                    </span>

                                    <a href="javascript:void(0)"
                                       class="th-btn2 btn-fw add-to-cart"
                                       data-id="{{ $product->id }}">
                                        Add To Cart
                                    </a>

                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>

@endif

    
    
    <section class="overflow-hidden position-relative bg-white   overflow-hidden testii-sec" id="testi-sec">
        <div class="shape-mockup hero-shape2 jump d-none d-xl-block" data-top="0%" data-left="0%"><img src="{{asset('img/shape/shape-19.png')}}" alt="shape"></div>
     <div class="title-area text-center   mb-30 "> 
                        <h2 class="sec-title style1">What Our Customers Says</h2>
                    </div>
        <div class="container th-container">
          
            <div class="slider-wrap">
                <div class="swiper th-slider has-shadow testiSlider2" id="testiSlider2" data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"2"},"992":{"slidesPerView":"3"},"1200":{"slidesPerView":"4"},"1400":{"slidesPerView":"4"}}}'>
                    <div class="swiper-wrapper">


                        @foreach($testimonials as $testimonial)

                        <div class="swiper-slide">
                            <div class="testi-box">
								<div class="tt-test-img">
								
								<img src="{{asset('storage')}}/{{$testimonial->image}}" alt="">
								</div>
                                <div class="testi-wrapp">
								
							
                                    <div class="box-quote"><img src="{{asset('img/icon/quote2.svg')}}" alt=""></div>
                                    <div class="box-review"><i class="fa-sharp fa-solid fa-star"></i><i class="fa-sharp fa-solid fa-star"></i><i class="fa-sharp fa-solid fa-star"></i><i class="fa-sharp fa-solid fa-star"></i><i class="fa-sharp fa-solid fa-star"></i></div>
                                </div>
								
							 
                             <div class="box-text"><p>{{$testimonial->review}}</p></div>
                             
                                <div class="box-profile">
                                    <div class="box-avater"><img src="assets/img/testimonial/testi_2_1.jpg" alt=""></div>
                                    <div class="media-body">
                                        <h3 class="box-title">{{$testimonial->name}}</h3>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @endforeach


                    </div>
                    <div class="slider-pagination"></div>
                </div>
            </div>
        </div>
    </section>

  
 
	
	
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