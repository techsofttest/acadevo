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

            <a href="#" class="th-btn2 btn-fw add-to-cart" data-id="{{ $product->id }}">
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
