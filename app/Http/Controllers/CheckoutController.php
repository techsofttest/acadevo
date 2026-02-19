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
use App\Models\Coupon;

class CheckoutController extends Controller
{
   

    public function index()
    {

        $data['cart'] = session('cart', []);
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

        foreach ($cart as $item)

            {

            $product = Product::findOrFail($item['product_id']);

            if ($product->stock < $item['qty']) {
                //throw new \Exception("Insufficient stock for {$product->name}");
            }

            $subtotal += $product->offer_price * $item['qty'];
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

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'title'      => $product->name,
                'sku'        => null,
                'quantity'   => $item['qty'],
                'price'      => $product->offer_price,
                'subtotal'   => $product->offer_price * $item['qty'],
                'tax'        => 0,
                'total'      => $product->offer_price * $item['qty'],
            ]);
        }

        // COD FLOW
        if ($request->payment_method === 'cod') {

            DB::commit();

            session()->forget(['cart', 'coupon']);

            return redirect()
                ->route('order.success', $order->id)
                ->with('success', 'Order placed successfully.');
        }

        // ONLINE PAYMENT (STRIPE)
        Stripe::setApiKey(config('services.stripe.secret'));

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

        echo $e->getMessage(); exit;

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
