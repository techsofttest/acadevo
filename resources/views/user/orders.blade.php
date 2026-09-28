@extends('layouts.app')


@section('content')



 <section class="Myaccountsec">
  <div class="container-fluid">
    <div class="row">
      

      @include('user.user-sidebar')
  
    
      <div class="col-lg-9 col-md-8">

      <div id="General" class="tabcontent profile">
	  
	
      <div class="Address">
      <h5>Order History</h5>

      <div class="woocommerce-cart-form">


      @forelse($orders as $order)

<table class="cart_table mb-30">
    <thead class="dash-tr">
        <tr>
            <th class="cart-col-image">
                {{ $order->created_at->format('M d, Y') }}
            </th>

            <th class="cart-col-productname">
                #{{ $order->order_number }}
            </th>

            <th class="cart-col-productname">
                <span class="oo-sorder">
                    {{ ucfirst($order->payment_method) }} Order
                </span>
            </th>

            <th class="cart-col-productname">
                <span class="oo-sstatus-{{ $order->payment_status === 'paid' ? 'success' : 'fail' }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </th>
        </tr>
    </thead>

    <tbody>

        {{-- Order Items --}}
        @foreach($order->items as $item)
        <tr class="cart_item">
            <td data-title="Order Id">
                <img width="80" height="80"
                     src="{{ !empty($item->product?->image) ? asset('storage/'.$item->product->image) : asset('img/products/p1-1.jpg') }}"
                     alt="{{ $item->product?->name ?? $item->title }}">
            </td>

            <td class="product-td-full">
                <a class="cart-productname"
                   href="{{ $item->product?->slug ? url('products/'.$item->product->slug) : '#' }}">
                    {{ $item->product?->name ?? $item->title }}
                </a>

                @if($item->variant_label)
                    <p class="text-muted small mb-1" style="font-size: 13px; color: #6c757d;">
                        <strong>Variant:</strong> {{ $item->variant_label }}
                    </p>
                @endif

                <p class="color-carttablee">
                    Quantity: {{ $item->quantity }}
                </p>
            </td>

            <td class="product-td-full">
                <p class="price-carttablee">
                    ₹ {{ number_format($item->total, 2) }}
                </p>
            </td>

            <td>
                @if($item->product?->slug)
                    <a href="{{ url('products/'.$item->product->slug) }}"
                       class="reorder-swc">
                        Re Order
                    </a>
                @endif
            </td>
        </tr>
        @endforeach

        {{-- Order Footer --}}
        <tr class="cart_item final-tt">
            <td colspan="2">
                <p class="color-carttablee">
                    {{ $order->items->sum('quantity') }} Items
                </p>
            </td>

            <td>
                <p class="price-carttablee">
                    ₹ {{ number_format($order->total, 2) }}
                </p>
            </td>

            <td>
                <div class="Order-buttons">
                    <a href="{{ route('customer.orderdetail', $order->id) }}"
                       class="oview">
                        View Order
                    </a>
                </div>
            </td>
        </tr>

    </tbody>
</table>

@empty
    <div class="text-center py-5">
        <p class="text-muted">You have no orders yet.</p>
        <a href="{{ url('products') }}" class="btn btn-primary mt-2" style="display: inline-block; padding: 8px 20px; background: #000; color: #fff; text-decoration: none; border-radius: 4px;">Start Shopping</a>
    </div>
@endforelse
    </div>
          </div>
          
          
         
        </div>

        
      </div>
    </div>
  </div>
</section>

@endsection