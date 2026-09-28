<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;

class CheckoutController extends Controller
{
   

    public function index()
    {

        $cart = session('cart', []);
        $updated = false;

        foreach ($cart as $key => $item) {
            $availableStock = 0;
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::where('product_id', $item['product_id'])->find($item['variant_id']);
                $availableStock = $variant ? (int)$variant->stock : 0;
            } else {
                $product = Product::find($item['product_id']);
                $availableStock = $product ? (int)$product->stock : 0;
            }

            if ($availableStock <= 0) {
                unset($cart[$key]);
                $updated = true;
            } elseif ($item['qty'] > $availableStock) {
                $cart[$key]['qty'] = $availableStock;
                $updated = true;
            }
        }

        if ($updated) {
            session()->put('cart', $cart);
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Item(s) in your cart are no longer in stock.');
            }
            session()->flash('warning', 'Cart quantities were updated based on real-time stock availability.');
        }

        $data['cart'] = $cart;
        $data['subtotal'] = collect($data['cart'])->sum(fn ($i) => $i['price'] * $i['qty']);
        $data['discount'] = session('coupon.discount', 0);
        $data['total'] = max(0, $data['subtotal'] - $data['discount']);

        $customer = auth('customer')->user();

        $data['addresses'] = $customer
        ? $customer->addresses()->latest()->get()
        : collect();

        return view('pages.checkout',$data);

    }

    

public function placeOrder(Request $request)
{
    $customer = Auth::guard('customer')->user();

    $cart = session('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Your cart is empty.');
    }

    DB::beginTransaction();

    try {

        $subtotal = 0;

        foreach ($cart as $item) {
            $product = Product::findOrFail($item['product_id']);
            $availableStock = 0;
            $variant = null;

            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::where('product_id', $product->id)
                    ->where('id', $item['variant_id'])
                    ->lockForUpdate()
                    ->first();
                $availableStock = $variant ? (int)$variant->stock : 0;
            } else {
                $product = Product::where('id', $product->id)->lockForUpdate()->first();
                $availableStock = (int)$product->stock;
            }

            if ($availableStock < $item['qty']) {
                $itemName = $product->name . (!empty($item['variant_name']) ? " ({$item['variant_name']})" : '');
                if ($availableStock <= 0) {
                    throw new \Exception("Sorry, '{$itemName}' is out of stock.");
                } else {
                    throw new \Exception("Maximum stocks selected for '{$itemName}'.");
                }
            }

            $itemPrice = $item['price'] ?? ($variant ? $variant->selling_price : ($product->offer_price ?? $product->original_price));
            $subtotal += $itemPrice * $item['qty'];
        }

        $discount = 0;
        $coupon = null;

        if (session()->has('coupon')) {
            $coupon = Coupon::where('code', session('coupon.code'))
                ->where('is_active', 1)
                ->first();

            if ($coupon) {
                if ($coupon->type === 'percent') {
                    $discount = ($subtotal * $coupon->value) / 100;
                } else {
                    $discount = $coupon->value;
                }
            }
        }

        $tax = 0;
        $shipping = 0;
        $total = $subtotal - $discount + $tax + $shipping;

        $billingAddress = $request->billing_address ?? $request->shipping_address;

        $order = Order::create([
            'order_number'     => 'ACDVO-' . strtoupper(Str::random(8)),

            // Optional customer ID (null for guest)
            'customer_id'      => $customer?->id,

            'subtotal'         => $subtotal,
            'discount_total'   => $discount,
            'tax_total'        => $tax,
            'shipping_total'   => $shipping,
            'total'            => $total,
            'currency'         => 'INR',

            'coupon_id'        => $coupon?->id,
            'coupon_code'      => $coupon?->code,
            'coupon_discount'  => $discount,

            'payment_method'   => $request->payment_method,
            'payment_status'   => 'pending',
            'status'           => 'pending',

            // ✅ ALWAYS from form
            'billing_address'  => json_encode($billingAddress),
            'shipping_address' => json_encode($request->shipping_address),

            // ✅ ALWAYS from form
            'customer_name'    => $request->shipping_address['name'],
            'customer_email'   => $request->shipping_address['email'],
            'customer_phone'   => $request->shipping_address['phone'],

            'placed_at'        => now(),
        ]);

        foreach ($cart as $item) {
            $product = Product::findOrFail($item['product_id']);
            $itemPrice = $item['price'] ?? $product->offer_price;
            $title = $product->name . (!empty($item['variant_name']) ? ' (' . $item['variant_name'] . ')' : '');

            $itemVariant = null;
            if (!empty($item['variant_id'])) {
                $itemVariant = ProductVariant::where('product_id', $product->id)->find($item['variant_id']);
            }
            $itemSku = $itemVariant?->sku ?? $product->sku;

            OrderItem::create([
                'order_id'           => $order->id,
                'product_id'         => $product->id,
                'product_variant_id' => $item['variant_id'] ?? null,
                'variant_name'       => $item['variant_name'] ?? null,
                'title'              => $title,
                'sku'                => $itemSku,
                'quantity'           => $item['qty'],
                'price'              => $itemPrice,
                'subtotal'           => $itemPrice * $item['qty'],
                'tax'                => 0,
                'total'              => $itemPrice * $item['qty'],
            ]);

            // Decrement Stock
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::where('product_id', $product->id)->find($item['variant_id']);
                if ($variant) {
                    $variant->decrement('stock', $item['qty']);
                }
            }
            if ($product->stock >= $item['qty']) {
                $product->decrement('stock', $item['qty']);
            }
        }

        // COD FLOW
        if ($request->payment_method === 'cod' || empty($request->payment_method)) {

            DB::commit();

            session()->forget(['cart', 'coupon']);

            return redirect()
                ->route('order.success', $order->id)
                ->with('success', 'Order placed successfully.');
        }

        // ONLINE PAYMENT (STRIPE)
        $stripeSecret = config('services.stripe.secret');
        if (empty($stripeSecret)) {
            throw new \Exception("Online payment is currently unavailable. Please try again later or contact support.");
        }

        Stripe::setApiKey($stripeSecret);

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => 'Order #' . $order->order_number,
                    ],
                    'unit_amount' => $total * 100,
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('order.payment.success', $order->id),
            'cancel_url'  => route('order.payment.cancel', $order->id),
        ]);

        $order->update([
            'stripe_session_id' => $session->id
        ]);

        DB::commit();

        return redirect($session->url);

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
    }



    public function summary($orderId)
    {

        $order = Order::with([
                'items.product',
                'customer',
            ])
            ->where('id', $orderId)
            ->firstOrFail();

        return view('pages.order-summary', compact('order'));

    }


    public function stripeSuccess(Request $request)
    {
        \Stripe\Stripe::setApiKey('');

        $session = \Stripe\Checkout\Session::retrieve($request->session_id);

        $orderId = $session->metadata->order_id;

        $order = Order::findOrFail($orderId);

        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'status' => 'confirmed',
            ]);

            session()->forget(['cart', 'discount', 'coupon']);
        }

        return redirect()->route('order.success', $order->id);
    }



    public function stripeCancel(Order $order)
    {
        $order->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        return redirect()->route('checkout.view')
            ->with('error', 'Payment was cancelled.');
    }










}
