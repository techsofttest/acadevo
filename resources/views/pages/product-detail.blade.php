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

.product-variants-sec {
    margin-top: 20px;
    margin-bottom: 24px;
}
.product-variants-sec label {
    font-family: var(--title-font, "Outfit", sans-serif);
    font-size: 15px;
    font-weight: 700;
    color: var(--title-color, #101018);
    margin-bottom: 12px;
}
.product-variants-sec .variant-btn-group {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 14px !important;
    padding-top: 8px !important;
}
.product-variants-sec .material-variant-btn {
    position: relative !important;
    display: inline-flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 115px !important;
    padding: 12px 18px !important;
    border-radius: 14px !important;
    border: 2px solid #e2e8f0 !important;
    background: #ffffff !important;
    color: var(--title-color, #101018) !important;
    font-family: var(--title-font, "Outfit", sans-serif) !important;
    cursor: pointer !important;
    transition: all 0.2s ease-in-out !important;
    user-select: none !important;
    outline: none !important;
    text-align: center !important;
    height: auto !important;
    line-height: normal !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.03) !important;
}
.product-variants-sec .material-variant-btn .btn-variant-label {
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #101018 !important;
    line-height: 1.2 !important;
    display: block !important;
}
.product-variants-sec .material-variant-btn .btn-variant-stock {
    font-size: 12px !important;
    font-weight: 600 !important;
    margin-top: 4px !important;
    color: #27ae60 !important;
    display: block !important;
}
.product-variants-sec .material-variant-btn .btn-variant-stock.out-of-stock {
    color: #eb5757 !important;
}
.product-variants-sec .material-variant-btn .variant-badge-tick {
    display: none !important;
    position: absolute !important;
    top: -10px !important;
    right: -10px !important;
    width: 24px !important;
    height: 24px !important;
    border-radius: 50% !important;
    background: var(--theme-color, #FD5B44) !important;
    color: #ffffff !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 11px !important;
    box-shadow: 0 3px 8px rgba(253, 91, 68, 0.4) !important;
    z-index: 2 !important;
}
.product-variants-sec .material-variant-btn:hover {
    border-color: var(--theme-color, #FD5B44) !important;
    background: #ffffff !important;
}
.product-variants-sec .material-variant-btn.active {
    border-color: var(--theme-color, #FD5B44) !important;
    background: #ffffff !important;
    box-shadow: 0 4px 12px rgba(253, 91, 68, 0.18) !important;
}
.product-variants-sec .material-variant-btn.active .variant-badge-tick {
    display: flex !important;
}
.product-variants-sec .material-variant-btn:disabled,
.product-variants-sec .material-variant-btn.disabled,
.product-variants-sec .material-variant-btn.out-of-stock-btn {
    opacity: 0.5 !important;
    background: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #94a3b8 !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
    box-shadow: none !important;
}
.product-variants-sec .material-variant-btn:disabled .btn-variant-label,
.product-variants-sec .material-variant-btn.disabled .btn-variant-label,
.product-variants-sec .material-variant-btn.out-of-stock-btn .btn-variant-label {
    color: #94a3b8 !important;
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


                    @php
                        $sortedVariants = $product->variants->sortBy('selling_price')->values();
                        $inStockVariants = $sortedVariants->filter(fn($v) => $v->stock > 0);
                        $defaultVariant = $inStockVariants->where('is_default', 1)->first() 
                                          ?? $inStockVariants->first() 
                                          ?? null;
                        $initialSellingPrice = $defaultVariant ? $defaultVariant->selling_price : ($sortedVariants->first()?->selling_price ?? $product->offer_price);
                        $initialStrikePrice = $defaultVariant ? $defaultVariant->strike_price : ($sortedVariants->first()?->strike_price ?? $product->original_price);
                        $initialStock = $defaultVariant ? $defaultVariant->stock : 0;
                    @endphp

                    @php
                        $initialSku = $defaultVariant?->sku ?? $product->sku;
                    @endphp

                    {{-- stock and sku --}}
                    <div class="product-info-list">
                        <ul>
                            <li>
                                Availability:
                                <span id="product_stock_status">
                                    {{ ($initialStock > 0 || ($product->is_active && !$sortedVariants->count())) ? 'In Stock' : 'Out of stock' }}
                                </span>
                            </li>
                            <li id="product_sku_row" style="{{ $initialSku ? '' : 'display:none;' }}">
                                SKU:
                                <span id="product_sku_display">{{ $initialSku }}</span>
                            </li>
                        </ul>
                    </div>

                    {{-- price --}}
                    <p class="price" id="product_price_display">
                        <span id="product_selling_price">₹ {{ number_format($initialSellingPrice, 2) }}</span>
                        <del id="product_strike_price" style="{{ ($initialStrikePrice > $initialSellingPrice) ? '' : 'display:none;' }}">₹ {{ number_format($initialStrikePrice, 2) }}</del>
                    </p>

                    {{-- variants --}}
                    @if($sortedVariants && $sortedVariants->count() > 0)
                    <div class="product-variants-sec">
                        <label class="d-block">Choose a package</label>
                        <div class="variant-btn-group">
                            @foreach($sortedVariants as $variant)
                                @php
                                    $inStock = $variant->stock > 0;
                                    $isSelected = ($defaultVariant && $defaultVariant->id === $variant->id && $inStock);
                                    $variantLabel = trim($variant->value . ' ' . $variant->unit);
                                @endphp
                                <button type="button"
                                        class="material-variant-btn variant-btn {{ $isSelected ? 'active' : '' }} {{ !$inStock ? 'disabled out-of-stock-btn' : '' }}"
                                        data-variant-id="{{ $variant->id }}"
                                        data-selling-price="{{ number_format($variant->selling_price, 2, '.', '') }}"
                                        data-strike-price="{{ number_format($variant->strike_price, 2, '.', '') }}"
                                        data-stock="{{ $variant->stock }}"
                                        data-sku="{{ $variant->sku ?? $product->sku ?? '' }}"
                                        {{ !$inStock ? 'disabled="disabled"' : '' }}>
                                    
                                    <span class="variant-badge-tick">
                                        <i class="fas fa-check"></i>
                                    </span>

                                    <span class="btn-variant-label">{{ $variantLabel }}</span>
                                    <span class="btn-variant-stock {{ $inStock ? '' : 'out-of-stock' }}">
                                        {{ $inStock ? 'In Stock' : 'Out of stock' }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" id="selected_variant_id" name="variant_id" value="{{ $defaultVariant?->id }}">
                    </div>
                    @endif

                    @php
                        $defaultStock = $defaultVariant ? $defaultVariant->stock : $product->stock;
                    @endphp
                    {{-- quantity --}}
                    <div class="quantity">
                        <div class="pro-qty">
                            <input 
                                id="quantity_input"
                                class="quantity_input"
                                type="number"
                                value="{{ $defaultStock > 0 ? 1 : 0 }}"
                                min="1"
                                max="{{ $defaultStock }}"
                                data-stock="{{ $defaultStock }}"
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
                        <a href="{{ route('buy.now', ['id' => $product->id, 'variant_id' => $defaultVariant?->id]) }}" 
                        id="buyNowBtn"
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

        <div class="swiper th-slider productSlide8" data-slider-options='{"spaceBetween":16,"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":2},"992":{"slidesPerView":3},"1200":{"slidesPerView":4}}}'>
            <div class="swiper-wrapper">

                @foreach($relatedProducts as $related)
                    <div class="swiper-slide">
                        <div class="product-grid col-12 col-sm-12 col-md-12 col-lg-12 style2 style8">

                            <div class="box-img">
                                <a href="{{ route('product.show', $related->slug) }}">
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}">
                                </a>

                                @if($related->original_price > $related->offer_price)
                                    <span class="product-tag">
                                        {{ round((($related->original_price - $related->offer_price) / $related->original_price) * 100) }}% off
                                    </span>
                                @endif
                            </div>

                            <div class="product-grid-content">
                                <h3 class="box-title">
                                    <a href="{{ route('product.show', $related->slug) }}">
                                        {{ $related->name }}
                                    </a>
                                </h3>

                                <span class="box-price">
                                    @if($related->original_price > $related->offer_price)
                                        <del>₹{{ number_format($related->original_price, 2) }}</del>
                                    @endif
                                    ₹{{ number_format($related->offer_price, 2) }}
                                </span>

                                @php
                                    $relatedVariants = [];
                                    if($related->relationLoaded('variants') && $related->variants->count() > 0) {
                                        $relatedVariants = $related->variants->map(function($v) {
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

                                <a href="javascript:void(0)"
                                   class="th-btn2 btn-fw addToCartBtn"
                                   data-id="{{ $related->id }}"
                                   data-name="{{ $related->name }}"
                                   data-image="{{ asset('storage/' . $related->image) }}"
                                   data-variants='@json($relatedVariants)'>
                                    Add To Cart
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>

    </div>
</section>

@endsection

@section('footer_extras')

<script>
$(document).ready(function(){
    var proQty = $('.pro-qty');
    if (proQty.length && !proQty.find('.qtybtn').length) {
        proQty.prepend('<span class="dec qtybtn">-</span>');
        proQty.append('<span class="inc qtybtn">+</span>');
        proQty.on('click', '.qtybtn', function () {
            var $button = $(this);
            var $input = $button.parent().find('input');
            var oldValue = parseFloat($input.val()) || 1;
            var maxStock = parseInt($input.attr('max')) || parseInt($input.data('stock')) || 9999;
            var newVal = 1;
            if ($button.hasClass('inc')) {
                if (oldValue < maxStock) {
                    newVal = oldValue + 1;
                } else {
                    newVal = maxStock;
                    if (typeof alertify !== 'undefined') {
                        alertify.error('Maximum stocks selected').delay(2).dismissOthers();
                    }
                }
            } else {
                if (oldValue > 1) {
                    newVal = oldValue - 1;
                } else {
                    newVal = 1;
                }
            }
            $input.val(newVal);
        });
    }

    $(document).on('click', '.variant-btn', function(e) {
        e.preventDefault();
        if ($(this).is(':disabled') || $(this).hasClass('disabled') || $(this).hasClass('out-of-stock-btn')) {
            return false;
        }
        let stock = parseInt($(this).data('stock'));
        if (isNaN(stock) || stock <= 0) {
            return false;
        }

        $('.variant-btn').removeClass('active');
        $(this).addClass('active');

        let variantId = $(this).data('variant-id');
        let sellingPrice = parseFloat($(this).data('selling-price'));
        let strikePrice = parseFloat($(this).data('strike-price'));

        $('#selected_variant_id').val(variantId);

        let $qtyInput = $('#quantity_input');
        $qtyInput.attr('max', stock).data('stock', stock);
        let currentQty = parseInt($qtyInput.val()) || 1;
        if (currentQty > stock) {
            $qtyInput.val(stock);
        } else if (currentQty < 1 && stock > 0) {
            $qtyInput.val(1);
        }

        $('#product_selling_price').text('₹ ' + sellingPrice.toFixed(2));
        if (strikePrice > sellingPrice) {
            $('#product_strike_price').text('₹ ' + strikePrice.toFixed(2)).show();
        } else {
            $('#product_strike_price').hide();
        }

        let variantSku = $(this).data('sku');
        if (variantSku && variantSku.toString().trim() !== '') {
            $('#product_sku_display').text(variantSku);
            $('#product_sku_row').show();
        } else {
            $('#product_sku_row').hide();
        }

        if (stock > 0) {
            $('#product_stock_status').text('In Stock');
            let buyNowUrl = "{{ route('buy.now', $product->id) }}?variant_id=" + variantId;
            $('#buyNowBtn').removeClass('disabled').attr('href', buyNowUrl);
        } else {
            $('#product_stock_status').text('Out of stock');
            $('#buyNowBtn').addClass('disabled').attr('href', 'javascript:void(0);');
        }
    });
});
</script>

@endsection
