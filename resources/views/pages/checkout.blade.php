@extends('layouts.app')


@section('head_extras')


@endsection


@section('content')

<style>

    #sameAsShipping
    {
    width: 30px !important;
    display: inline-block !important;
    height: 100% !important;
    }

</style>


<div class="ibm-bcrms-main">
	<div class="ibm-bcrms">
 <div class="container">

   <h3>Checkout</h3>
   <ul class="ibm-breadcrumb ">

      <li><a href="{{url('/')}}">Home</a></li>

	 

      <li class="active">Checkout</li>

    </ul>
  </div>
</div>
</div>
	
 
 
	  <div class="vs-checkout-wrapper Checkout-sec finaa-ccf">
  <div class="container">
  <div class="row">
  
  <div class="col-lg-8">
  <div class="woocommerce-form-login-toggle">
      <div class="woocommerce-info">Returning customer? <a href="javascript:void(0);" class="showlogin" data-bs-toggle="modal" data-bs-target="#youmyModal">Click here to login</a></div>
    </div>
   
	
<form action="{{ route('checkout.confirm') }}" method="POST" class="woocommerce-checkout mt-40">
    @csrf

    {{-- ================= SHIPPING ADDRESS ================= --}}
    <div class="Delvery-address">
        <h4>Shipping Address</h4>

        <div class="row">

            <div class="col-md-6 form-group">
                <input type="text"
                       name="shipping_address[name]"
                       class="form-control"
                       placeholder="Full Name"
                       required>
            </div>

            <div class="col-md-6 form-group">
                <input type="email"
                       name="shipping_address[email]"
                       class="form-control"
                       placeholder="Email"
                       required>
            </div>

            <div class="col-md-6 form-group">
                <input type="text"
                       name="shipping_address[phone]"
                       class="form-control"
                       placeholder="Phone"
                       required>
            </div>

            <div class="col-md-6 form-group">
                <input type="text"
                       name="shipping_address[pincode]"
                       class="form-control"
                       placeholder="Pincode"
                       required>
            </div>

            <div class="col-md-12 form-group">
                <input type="text"
                       name="shipping_address[address]"
                       class="form-control"
                       placeholder="Full Address"
                       required>
            </div>

            <div class="col-md-6 form-group">
                <input type="text"
                       name="shipping_address[city]"
                       class="form-control"
                       placeholder="City"
                       required>
            </div>

            <div class="col-md-6 form-group">
                <input type="text"
                       name="shipping_address[state]"
                       class="form-control"
                       placeholder="State"
                       required>
            </div>

        </div>
    </div>

    {{-- ================= BILLING ADDRESS ================= --}}
    <div class="Delvery-address-new mt-4">
        <label>
            <input type="checkbox" id="sameAsShipping" checked>
            Billing same as shipping
        </label>
    </div>

    <div id="billingSection" style="display:none;">

        <h4 class="mt-3">Billing Address</h4>

        <div class="row">

            <div class="col-md-6 form-group">
                <input type="text"
                       name="billing_address[name]"
                       class="form-control"
                       placeholder="Full Name">
            </div>

            <div class="col-md-6 form-group">
                <input type="email"
                       name="billing_address[email]"
                       class="form-control"
                       placeholder="Email">
            </div>

            <div class="col-md-6 form-group">
                <input type="text"
                       name="billing_address[phone]"
                       class="form-control"
                       placeholder="Phone">
            </div>

            <div class="col-md-6 form-group">
                <input type="text"
                       name="billing_address[pincode]"
                       class="form-control"
                       placeholder="Pincode">
            </div>

            <div class="col-md-12 form-group">
                <input type="text"
                       name="billing_address[address]"
                       class="form-control"
                       placeholder="Full Address">
            </div>

            <div class="col-md-6 form-group">
                <input type="text"
                       name="billing_address[city]"
                       class="form-control"
                       placeholder="City">
            </div>

            <div class="col-md-6 form-group">
                <input type="text"
                       name="billing_address[state]"
                       class="form-control"
                       placeholder="State">
            </div>

        </div>
    </div>

    {{-- ================= PAYMENT ================= --}}
    <div class="Delvery-address-new ccert mt-4">
        <h4>Payment Method</h4>

        <div class="form-check">
            <input type="radio"
                   name="payment_method"
                   value="cod"
                   class="form-check-input"
                   checked id="codpay">
            <label class="form-check-label" for="codpay">Cash on Delivery</label>
        </div>

        <div class="form-check mt-2">
            <input type="radio"
                   name="payment_method"
                   value="online"
                   class="form-check-input" id="onlinepay">
            <label class="form-check-label" for="onlinepay">Online Payment (Card / UPI)</label>
        </div>

        <button type="submit" class="th-btn style3 mt-3">
            Place Order
        </button>
    </div>

</form>





  
  </div>
  
 


<div class="col-lg-4">
    <div class="checkoutlast-sticky">
        <div class="checkoutlast">

            <h4>Your Order</h4>

            <table class="cart_table mb-20">
                <tbody class="m-tt-check">

                    @forelse($cart as $id => $item)
                        @php
                            $lineTotal = $item['price'] * $item['qty'];
                        @endphp

                        <tr class="cart_item">
                            <td class="product-td-full">
                              

                                    <img width="71"
                                         height="71"
                                         src="{{ asset('storage') }}/{{$item['image']}}"
                                         alt="{{ $item['name'] }}">

                                    <div class="checoo-count">
                                        {{ $item['qty'] }}
                                    </div>
                                
                            </td>

                            <td class="product-td-full">
                                
                                    {{ $item['name'] }}
                               
                            </td>

                            <td class="product-td-full cat-min-tdr">
                                <span class="amount">
                                    <bdi>₹{{ number_format($lineTotal, 2) }}</bdi>
                                </span>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                Your cart is empty.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

                @if(count($cart))
                <tfoot class="checkout-ordertable mob-chec-table">

                    <tr class="cart-subtotal">
                        <th>Subtotal</th>
                        <td colspan="3">
                            ₹{{ number_format($subtotal, 2) }}
                        </td>
                    </tr>

                    @if($discount > 0)
                    <tr>
                        <th>Discount</th>
                        <td colspan="3" class="text-success">
                            - ₹{{ number_format($discount, 2) }}
                        </td>
                    </tr>
                    @endif

                    <tr class="order-total">
                        <th>Total</th>
                        <td colspan="3">
                            <strong>
                                ₹{{ number_format($total, 2) }}
                            </strong>
                        </td>
                    </tr>

                </tfoot>
                @endif

            </table>

        </div>
    </div>
</div>







  
  
  </div>
  
    
  
  </div>
</div>


@endsection


@section('footer_extras')


<script>
document.getElementById('sameAsShipping').addEventListener('change', function() {
    document.getElementById('billingSection').style.display =
        this.checked ? 'none' : 'block';
});
</script>



@endsection