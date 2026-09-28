<div class="col-lg-4 col-md-6 col-sm-6 d-flex">
    <div class="product-grid style2 style8">
        <div class="box-img">
            <a href="{{ route('product.show', $product->slug) }}">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            </a>

            @if($product->original_price > $product->offer_price)
                <span class="product-tag">
                    {{ round((($product->original_price - $product->offer_price) / $product->original_price) * 100) }}% off
                </span>
            @endif

            <a href="#" class="tt-btn-wishlist" data-id="{{ $product->id }}">
                <i class="fa fa-heart"></i>
            </a>
        </div>

        <div class="product-grid-content">
            <h3 class="box-title">
                <a href="{{ route('product.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <span class="box-price">
                @if($product->original_price > $product->offer_price)
                    <del>₹{{ number_format($product->original_price) }}</del>
                @endif
                ₹{{ number_format($product->offer_price) }}
            </span>

@php
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

            <a href="javascript:void(0);"
               class="th-btn2 btn-fw addToCartBtn"
               data-id="{{ $product->id }}"
               data-name="{{ $product->name }}"
               data-image="{{ asset('storage/' . $product->image) }}"
               data-variants='@json($variantsData)'>
                <span class="link-effect">
                    <span class="effect-1">
                         Add To Cart
                    </span>
                    <span class="effect-1 style2">
                         Add To Cart
                    </span>
                </span>
            </a>

        </div>
    </div>
</div>
