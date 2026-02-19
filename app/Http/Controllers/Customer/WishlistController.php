<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{



    public function show()
    {

    $customer = Auth::guard('customer')->user();

    $wishlists = $customer->wishlist()
        ->with('product') // eager load product
        ->latest()
        ->get();

    return view('user.wishlist', compact('wishlists'));

    }






    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => 'removed',
                'message' => 'Removed from wishlist',
            ]);
        }

        Wishlist::create([
            'customer_id' => $customer->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Added to wishlist',
        ]);
    }
}
