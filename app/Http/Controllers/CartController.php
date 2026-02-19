<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Coupon;

class CartController extends Controller
{



    public function index()
    {

    $cart = session()->get('cart', []);

    $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
    $shipping = 0; // you can make dynamic later
    $total = $subtotal + $shipping;

    return view('pages.cart', compact('cart', 'subtotal', 'shipping', 'total'));

    }





    /**
     * Add to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'qty'        => 'nullable|integer|min:1',
        ]);

        $qty = $request->qty ?? 1;

        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);

        // Prevent ID collision between tables
        $key = 'product_' . $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'key'   =>        $key,
                'product_id'   => $product->id,
                'name'         => $product->name,
                'price'        => $product->offer_price ?? $product->original_price,
                'qty'          => $qty,
                'image'        => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return $this->miniCartHtml();
    }

    /**
     * Remove from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|string'
        ]);

        $cart = session()->get('cart', []);

        unset($cart[$request->key]);

        session()->put('cart', $cart);

        return $this->miniCartHtml();
    }

    /**
     * Update quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'qty' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->key])) {
            $cart[$request->key]['qty'] = $request->qty;
            session()->put('cart', $cart);
        }

        return $this->miniCartHtml();
    }

    /**
     * Mini cart HTML
     */
    protected function miniCartHtml()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn ($i) => $i['price'] * $i['qty']);

        $count = collect($cart)->sum('qty');

        return response()->json([
        'html' => view('layouts.partials.mini_cart', compact('cart', 'total'))->render(),
        'count' => $count
        ]);

    }



   public function applyCoupon(Request $request)
{
    $code = strtoupper(trim($request->code));

    $coupon = Coupon::where('code', $code)
        ->where('is_active', 1)
        ->first();

    if (!$coupon) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid coupon code'
        ]);
    }

    // Check start date
    if ($coupon->starts_at && Carbon::now()->lt($coupon->starts_at)) {
        return response()->json([
            'status' => false,
            'message' => 'Coupon not started yet'
        ]);
    }

    // Check expiry date
    if ($coupon->expires_at && Carbon::now()->gt($coupon->expires_at)) {
        return response()->json([
            'status' => false,
            'message' => 'Coupon expired'
        ]);
    }

    // Calculate fresh subtotal
    $cart = session('cart', []);
    $subtotal = 0;

    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['qty'];
    }

    if ($subtotal <= 0) {
        return response()->json([
            'status' => false,
            'message' => 'Cart is empty'
        ]);
    }

    // Calculate discount
    if ($coupon->type === 'percent') {
        $discount = ($subtotal * $coupon->value) / 100;
    } else {
        $discount = $coupon->value;
    }

    // Prevent over-discount
    $discount = min($discount, $subtotal);

    session()->put('coupon', [
        'id' => $coupon->id,
        'code' => $coupon->code,
        'discount' => $discount
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Coupon applied successfully'
    ]);
}


    public function removeCoupon()
    {
        session()->forget('coupon');

        return redirect()->back()->with('success', 'Coupon removed successfully.');
    }



    public function buyNow($id)
{
    $product = Product::findOrFail($id);

    // Clear previous cart (optional — recommended for direct buy)
    session()->forget('cart');

     $key = 'product_' . $product->id;

    // Add only this product
    $cart = [
        $product->id => [
            'key'   =>  $key,
            'product_id' => $product->id,
            "name" => $product->name,
            "qty" => 1,
            "price" => $product->offer_price ?? $product->original_price,
            "image" => $product->image
        ]
    ];

    session()->put('cart', $cart);

    // Redirect directly to checkout
    return redirect()->route('checkout.view');
    }





    public function clear(Request $request)
    {
        // Remove only cart session
        session()->forget('cart');

        return $this->miniCartHtml();
    }





}
