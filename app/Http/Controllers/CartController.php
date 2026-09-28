<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use App\Models\Coupon;

class CartController extends Controller
{



    public function index()
    {
        $cart = session()->get('cart', []);
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
            session()->flash('warning', 'Some quantities in your cart were adjusted based on available stock.');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $shipping = 0;
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
            'variant_id' => 'nullable|integer',
            'qty'        => 'nullable|integer|min:1',
        ]);

        $qty = (int) ($request->qty ?? 1);
        if ($qty < 1) {
            $qty = 1;
        }

        $product = Product::findOrFail($request->product_id);

        $variant = null;
        if ($request->filled('variant_id')) {
            $variant = ProductVariant::where('product_id', $product->id)->find($request->variant_id);
        }

        if (!$variant && $product->variants()->exists()) {
            $variant = $product->variants()->where('stock', '>', 0)->where('is_default', true)->first() 
                ?? $product->variants()->where('stock', '>', 0)->first() 
                ?? $product->variants()->where('is_default', true)->first() 
                ?? $product->variants()->first();
        }

        $availableStock = $variant ? (int)$variant->stock : (int)$product->stock;

        if ($availableStock <= 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Sorry, this item is currently out of stock.'
            ], 422);
        }

        $cart = session()->get('cart', []);
        $key = $variant ? 'product_' . $product->id . '_v_' . $variant->id : 'product_' . $product->id;

        $currentCartQty = isset($cart[$key]) ? (int)$cart[$key]['qty'] : 0;
        $totalRequestedQty = $currentCartQty + $qty;

        if ($totalRequestedQty > $availableStock) {
            return response()->json([
                'status'  => false,
                'message' => 'Maximum stocks selected'
            ], 422);
        }

        $price = $variant ? $variant->selling_price : ($product->offer_price ?? $product->original_price);
        $variantName = $variant ? trim(($variant->value ?? '') . ' ' . ($variant->unit ?? '')) : null;

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'key'          => $key,
                'product_id'   => $product->id,
                'variant_id'   => $variant?->id,
                'variant_name' => $variantName,
                'name'         => $product->name,
                'slug'         => $product->slug,
                'price'        => $price,
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
            $item = $cart[$request->key];
            $availableStock = 0;

            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::where('product_id', $item['product_id'])->find($item['variant_id']);
                $availableStock = $variant ? (int)$variant->stock : 0;
            } else {
                $product = Product::find($item['product_id']);
                $availableStock = $product ? (int)$product->stock : 0;
            }

            if ((int)$request->qty > $availableStock) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Maximum stocks selected'
                ], 422);
            }

            $cart[$request->key]['qty'] = (int)$request->qty;
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



    public function buyNow(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $variant = null;
        if ($request->filled('variant_id')) {
            $variant = ProductVariant::where('product_id', $product->id)->find($request->variant_id);
        }

        if (!$variant && $product->variants()->exists()) {
            $variant = $product->variants()->where('stock', '>', 0)->where('is_default', true)->first() 
                ?? $product->variants()->where('stock', '>', 0)->first() 
                ?? $product->variants()->where('is_default', true)->first() 
                ?? $product->variants()->first();
        }

        $availableStock = $variant ? (int)$variant->stock : (int)$product->stock;

        if ($availableStock < 1) {
            return redirect()->back()->with('error', 'Sorry, this item is currently out of stock.');
        }

        $price = $variant ? $variant->selling_price : ($product->offer_price ?? $product->original_price);
        $variantName = $variant ? trim(($variant->value ?? '') . ' ' . ($variant->unit ?? '')) : null;

        session()->forget('cart');

        $key = $variant ? 'product_' . $product->id . '_v_' . $variant->id : 'product_' . $product->id;

        $cart = [
            $key => [
                'key'          => $key,
                'product_id'   => $product->id,
                'variant_id'   => $variant?->id,
                'variant_name' => $variantName,
                'name'         => $product->name,
                'slug'         => $product->slug,
                'qty'          => 1,
                'price'        => $price,
                'image'        => $product->image
            ]
        ];

        session()->put('cart', $cart);

        return redirect()->route('checkout.view');
    }





    public function clear(Request $request)
    {
        // Remove only cart session
        session()->forget('cart');

        return $this->miniCartHtml();
    }





}
