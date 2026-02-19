@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_desc ?? Str::limit(strip_tags($product->description), 160))

@section('header_extras')

<style>

    .pro-qty {
    display: flex;
    align-items: center;
    width: 140px;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}

.pro-qty input {
    width: 60px;
    text-align: center;
    border: none;
    outline: none;
    font-size: 16px;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: #f5f5f5;
    font-size: 20px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.qty-btn:hover {
    background: #e0e0e0;
}


</style>

@endsection

@section('content')

{{-- ================= BREADCRUMB ================= --}}
<div class="ibm-bcrms-main">
    <div class="ibm-bcrms">
        <div class="container">
            <h3>{{ $product->name }}</h3>

            <ul class="ibm-breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('products.index') }}">Products</a></li>

                @if($product->category)
                    <li>
                        <a href="{{ route('category.show', $product->category->slug) }}">
                            {{ $product->category->name }}
                        </a>
                    </li>
                @endif

                <li class="active">{{ $product->name }}</li>
            </ul>
        </div>
    </div>
</div>

{{-- ================= PRODUCT DETAILS ================= --}}
<section class="vs-product-wrapper product-details">
    <div class="container">
        <div class="row">

            {{-- ===== LEFT : IMAGES ===== --}}
            <div class="col-lg-5 col-md-12">
                <div class="pr-left-stikk">
                    <div class="pr-left-stikk-flex">

                        <div class="productdetail-order1">
                            <div class="product-imgsec">
                                <div class="product-pic-zoom">
                                    <img class="product-big-img"
                                         src="{{ asset('storage/'.$product->image) }}"
                                         alt="{{ $product->name }}"
                                         width="100%">
                                </div>
                            </div>
                        </div>

                        <div class="col-mythump productdetail-order2">
                            <div class="product-thumbs myuthumb">
                                <div class="product-thumbs-track">

                                    {{-- main image --}}
                                    <div class="pt active"
                                         data-imgbigurl="{{ asset('storage/'.$product->image) }}">
                                        <img src="{{ asset('storage/'.$product->image) }}">
                                    </div>

                                    {{-- additional images --}}
                                    @foreach($product->additional_images ?? [] as $img)
                                        <div class="pt"
                                             data-imgbigurl="{{ asset('storage/'.$img) }}">
                                            <img src="{{ asset('storage/'.$img) }}">
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ===== RIGHT : INFO ===== --}}
            <div class="col-lg-7">
                <div class="product-about">

                    <div class="title-area mb-20">
                        <h2 class="sec-title style1">{{ $product->name }}</h2>
                    </div>

                    {{-- rating --}}
                    @if(round($product->average_rating)>0)
                    
                    <h3 class="rating-ppr">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star {{ $i <= round($product->average_rating) ? '' : 'tt' }}"></i>
                        @endfor
                        {{ $product->reviews_count }}
                    </h3>
                    
                    @endif


                    {{-- stock --}}
                    <div class="product-info-list">
                        <ul>
                            <li>
                                Availability:
                                <span>
                                    {{ $product->stock > 0 ? $product->stock.' in stock' : 'Out of stock' }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    {{-- price --}}
                    <p class="price">
                        ₹ {{ number_format($product->offer_price, 2) }}
                        @if($product->original_price > $product->offer_price)
                            <del>₹ {{ number_format($product->original_price, 2) }}</del>
                        @endif
                    </p>

                    {{-- quantity --}}
                    <div class="quantity">
                        <div class="pro-qty">
                            <input 
                                id="quantity_input"
                                class="quantity_input"
                                type="number"
                                value="1"
                                min="1"
                                step="1"
                                readonly
                            >
                        </div>
                    </div>

                    {{-- actions --}}
                    <div class="actions-ss">
                        <a href="javascript:void(0);" class="cart1-link addToCartBtn" data-id="{{$product->id}}">
                            <i class="fal fa-shopping-bag"></i> Add to cart
                        </a>

                        <a href="#" class="cart2-link">
                            <i class="fal fa-heart"></i>
                            {{ $product->isWishlistedByCustomer() ? 'Wishlisted' : 'Add to Wishlist' }}
                        </a>
                    </div>

                    <div class="actions">
                        <a href="{{ route('buy.now', $product->id) }}" 
                        class="vs-btn style2 text-center">
                        Buy Now
                        </a>
                    </div>

                    {{-- description --}}
                    <div class="Prod-main-seccse">
                        <h3>Overview</h3>
                        {!! $product->description !!}
                    </div>

                    {{-- video --}}
                    @if($product->video)
                        <h3>Product Video</h3>
                        <div class="youtube" data-embed="{{ $product->video }}">
                            <div class="play-button"></div>
                            <img src="https://img.youtube.com/vi/{{ $product->video }}/hqdefault.jpg">
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= REVIEWS ================= --}}

@if(round($product->average_rating)>0)
<div class="Review-prodsec">
    <div class="container">

        <div class="title-area text-center mb-30">
            <h2 class="sec-title style1">Customer Reviews</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9 text-center">
                <h3>{{ $product->average_rating }} out of 5</h3>
                <h4>Based on {{ $product->reviews_count }} reviews</h4>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">

                @forelse($product->approvedReviews as $review)
                    <div class="Ceworkforce-box mb-4">
                        <h5>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= $review->rating ? '' : 'tt' }}"></i>
                            @endfor
                        </h5>

                        <div class="Cework-box-content">
                            <h3>
                                {{ $review->customer->name ?? 'Customer' }}
                                <span>Verified</span>
                            </h3>
                            <p>{{ $review->review }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center">No reviews yet.</p>
                @endforelse

            </div>
        </div>
    </div>
</div>
@endif



{{-- ================= RELATED PRODUCTS ================= --}}
<section class="Otherpp-sec relatedcc">
    <div class="container th-container">

        <div class="title-area mb-35">
            <h2 class="sec-title style1">Related Products</h2>
        </div>

        <div class="swiper th-slider productSlide8">
            <div class="swiper-wrapper">

                @foreach($relatedProducts as $related)
                    <div class="swiper-slide">
                        <div class="product-grid style2 style8">

                            <div class="box-img">
                                <a href="{{ route('products.show', $related->slug) }}">
                                    <img src="{{ asset('storage/products/'.$related->image) }}">
                                </a>
                            </div>

                            <div class="product-grid-content">
                                <h3 class="box-title">
                                    <a href="{{ route('products.show', $related->slug) }}">
                                        {{ $related->name }}
                                    </a>
                                </h3>

                                <span class="box-price">
                                    <del>₹ {{ number_format($related->original_price, 2) }}</del>
                                    ₹ {{ number_format($related->offer_price, 2) }}
                                </span>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </div>
</section>

@endsection

@section('footer_extars')



@endsection
