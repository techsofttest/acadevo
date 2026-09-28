
@foreach($products as $product)

@php
    $discount = 0;
    if ($product->original_price > 0 && $product->offer_price < $product->original_price) {
        $discount = round((($product->original_price - $product->offer_price) / $product->original_price) * 100);
    }

    $variantsData = [];
    if($product->relationLoaded('variants') && $product->variants->count() > 0) {
        $variantsData = $product->variants->sortBy('selling_price')->map(function($v) {
            return [
                'id' => $v->id,
                'label' => trim($v->value . ' ' . $v->unit),
                'selling_price' => number_format($v->selling_price, 2, '.', ''),
                'strike_price' => number_format($v->strike_price, 2, '.', ''),
                'stock' => $v->stock,
                'is_default' => (bool)$v->is_default
            ];
        })->values()->toArray();
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
                <h3 class="box-title"><a href="{{ route('product.show', $product->slug) }}">{{$product->name}}</a></h3>
        <span class="box-price"><del>₹ {{$product->original_price}}</del> ₹ {{$product->offer_price}}</span>

                @if($product->is_active)
                <a href="javascript:void(0);" 
                data-id="{{ $product->id }}" 
                data-name="{{ $product->name }}"
                data-image="{{ asset('storage/'.$product->image) }}"
                data-variants='@json($variantsData)'
                class="th-btn2 btn-fw addToCartBtn">
                        <span>Add To Cart</span>
                </a>
                @else
                <button class="th-btn2 btn-fw" disabled>
                        <span>Out of Stock</span>
                </button>
                @endif
        
        </div>
        </div>
        </div>

@endforeach