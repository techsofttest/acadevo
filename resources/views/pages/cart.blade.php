@extends('layouts.app')


@section('content')



	
 <div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Cart</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="{{url('/')}}">Home</a></li>

	 

      <li class="active">Cart</li>

    </ul>
  </div>
</div>
</div>
	
 
 
	   <div class="th-cart-wrapper  mycart-sec">
        <div class="container">
        
            <form action="#" class="woocommerce-cart-form">
                <table class="cart_table">
                    <thead>
                        <tr>
                                <th  class="cart-col-productname">Product Image</th>
                            <th   class="cart-col-productname">Product  </th>
                         
                            <th class="cart-col-quantity">Quantity</th>
                            <th class="cart-col-total">Total</th>
                            <th class="cart-col-remove">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    @if(count($cart))

                    @foreach($cart as $item)
                    <tr class="cart_item" data-key="{{ $item['key'] }}">

                        <td>
                            <a class="cart-productimage" href="#">
                                <img width="120" height="120"
                                    src="{{ asset('storage/'.$item['image']) }}"
                                    alt="{{ $item['name'] }}">
                            </a>
                        </td>

                        <td class="product-td-full">
                            <a class="cart-productname" href="#">
                                {{ $item['name'] }}
                            </a>

                            <p class="price-carttablee">
                                ₹{{ number_format($item['price'],2) }}
                            </p>
                        </td>

                        <td>
                            <div class="quantitynew">
                                <div class="pro-qty">
                                    <input type="number"
                                        class="cartQtyInput"
                                        data-key="{{ $item['key'] }}"
                                        value="{{ $item['qty'] }}"
                                        min="1" readonly>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="amount itemTotal">
                                ₹{{ number_format($item['price'] * $item['qty'],2) }}
                            </span>
                        </td>

                        <td>
                            <a href="javascript:void(0)"
                            class="remove removeCartItem"
                            data-key="{{ $item['key'] }}">
                                <i class="fal fa-trash-alt"></i>
                            </a>
                        </td>

                    </tr>
                    @endforeach

                    @else

                    <tr>
                        <td colspan="5" class="text-center">
                            Your cart is empty.
                        </td>
                    </tr>

                    @endif
                       
                    </tbody>

                </table>
            </form>
            <div class="row justify-content-end">



            

                <div class="col-md-8 col-lg-7 col-xl-6">

                    <div class="coupon-section mb-4">

                <h5>Apply Coupon</h5>

                <div class="d-flex gap-2">
                    <input type="text" 
                        class="form-control couponCodeInput" 
                        placeholder="Enter coupon code">

                    <button type="button" 
                            class="th-btn style3 applyCouponBtn">
                        Apply
                    </button>
                </div>

                <small class="text-success couponSuccess d-none"></small>
                <small class="text-danger couponError d-none"></small>

            </div>


                    <h2 class="h4 summary-title">Cart Totals</h2>


                    <table class="cart_totals">
    <tbody>
        <tr>
            <td>Cart Subtotal</td>
            <td>
                <span class="amount cartSubtotal">
                    ₹{{ number_format($subtotal,2) }}
                </span>
            </td>
        </tr>

        @if(session()->has('coupon'))

        <tr class="couponRow">
            <td>Coupon Discount ({{ session('coupon.code') }})

            <form action="{{ route('coupon.remove') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-sm btn-link text-danger p-0 ms-2">
                Remove
            </button>
            </form>

            </td>
            <td>
                - ₹{{ number_format(session('coupon.discount'),2) }}
            </td>
        </tr>

        @endif

        <tr class="shipping">
            <th>Shipping and Handling</th>
            <td>
                ₹{{ number_format($shipping,2) }}
            </td>
        </tr>
    </tbody>

    <tfoot>
        <tr class="order-total">
            <td class="oo-total-p">Order Total</td>
            <td>
                <strong>
                    <span class="amount cartTotal">
                        ₹{{ number_format($total,2) }}
                    </span>
                </strong>
            </td>
        </tr>
    </tfoot>
</table>


                    <div class="wc-proceed-to-checkout mb-30">
                        <a href="{{route('checkout.view')}}" class="th-btn   style3">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection


@section('footer_extras')

<script src="{{asset('js/jquery.slicknav.min.js')}}"></script>

<script src="{{asset('js/jquery.nicescroll.min.js')}}"></script>

<script src="{{asset('js/jquery.zoom.min.js')}}"></script> 

<script src="{{asset('js/jquery-ui.min.js')}}"></script> 

<script src="{{asset('js/mains.js')}}"></script>

<script src="{{asset('js/functions.js')}}"></script> 

<script src="{{asset('js/plugins.min.js')}}"></script>



<script>
$(document).ready(function(){

    // Update quantity
    $(document).on('click', '.qtybtn', function(){

        let key = $('.cartQtyInput').data('key');
        let qty = $('.cartQtyInput').val();

        $.post("{{ route('cart.update') }}", {
            key: key,
            qty: qty,
            _token: "{{ csrf_token() }}"
        }, function(response){
            location.reload(); // simple version
        });

    });

    // Remove item
    $(document).on('click', '.removeCartItem', function(){

        let key = $(this).data('key');

        $.post("{{ route('cart.remove') }}", {
            key: key,
            _token: "{{ csrf_token() }}"
        }, function(response){
            location.reload(); // simple version
        });

    });

});
</script>

<script>
// Apply Coupon
$(document).on('click', '.applyCouponBtn', function(){

    let code = $('.couponCodeInput').val();

    $.post("{{ route('cart.applyCoupon') }}", {
        code: code,
        _token: "{{ csrf_token() }}"
    }, function(response){

        $('.couponSuccess').addClass('d-none');
        $('.couponError').addClass('d-none');

        if(response.status){
            $('.couponSuccess').removeClass('d-none')
                .text(response.message);
            location.reload();
        } else {
            $('.couponError').removeClass('d-none')
                .text(response.message);
        }
    });

});
</script>



@endsection