<div id="miniCartWrapper">

@if($cart && count($cart))

<div class="widget_shopping_cart_content">
<ul class="woocommerce-mini-cart cart_list product_list_widget">

@foreach($cart as $item)
<li class="woocommerce-mini-cart-item mini_cart_item">
<a href="javascript:void(0);" data-key="{{ $item['key'] }}" class="remove remove_from_cart_button removeFromCartBtn">
<i class="far fa-times"></i>
</a> 
<a href="product-detail.html"><img src="{{ asset('storage/' . $item['image']) }}" alt="Cart Image">{{ $item['name'] }}</a> 
<span class="quantity">{{ $item['qty'] }} x <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"> </span>₹ {{ number_format($item['price'],2) }}</span>
</span>
</li>
@endforeach

</ul>
 <p class="woocommerce-mini-cart__total total"><strong>Total:</strong> <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"> </span>₹ {{ number_format($total,2) }}</span></p>
<p class="woocommerce-mini-cart__buttons buttons"><a href="{{url('cart')}}" class="th-btn wc-forward">View cart</a> <a href="{{url('checkout')}}" class="th-btn checkout wc-forward">Checkout</a></p>
 </div>

@else
<p>Your cart is empty</p>
@endif

</div>
