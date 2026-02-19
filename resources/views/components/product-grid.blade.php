
@foreach($products as $product)

@php
    $discount = 0;
    if ($product->original_price > 0 && $product->offer_price < $product->original_price) {
        $discount = round((($product->original_price - $product->offer_price) / $product->original_price) * 100);
    }
@endphp

        <div class="  col-lg-3 col-md-4 col-sm-6 filter-item  d-flex ">
                        <div class="product-grid style2 style8">
                        <div class="box-img">
                                    
                        <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{asset('storage')}}/{{$product->image}}" alt=""></a> 
                                
                                @if($discount > 0)
                                <span class="product-tag">{{ $discount }}% off</span>
                                @endif

								
				@if(auth('customer')->check())

                                <a href="javascript:void(0);" 
                                data-id="{{ $product->id }}" 
                                class="tt-btn-wishlist addToWishlistBtn">

                                        <i class="fa fa-heart {{ $product->isWishlistedByCustomer() ? 'text-danger' : '' }}"></i>

                                </a>

                                @else

                                <a href="javascript:void(0);" 
                                class="tt-btn-wishlist"
                                data-bs-toggle="modal" 
                                data-bs-target="#youmyModal">

                                <i class="fa fa-heart"></i>

                                </a>

                                @endif

						</div>
                        <div class="product-grid-content">
                <h3 class="box-title"><a href="">{{$product->name}}</a></h3>
        <span class="box-price"><del>₹ {{$product->original_price}}</del> ₹ {{$product->offer_price}}</span>

        <a href="javascript:void(0);" data-id="{{$product->id}}" class="th-btn2 btn-fw addToCartBtn">
                
        <span >Add To Cart</span> 
        
        </a>
        
        </div>
        </div>
        </div>

@endforeach