<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductVarient;

use App\Models\Category;

use App\Models\ProductReview;
use App\Models\OrderItem;

use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
   

    public function index()
    {
        
    $data['pp_title'] = "All Products";

    $data['categories'] = Category::with('children')
    ->whereNull('parent_id')
    ->where('is_active', true)
    ->get();

    $data['products'] = Product::where('is_active',1)->get();

    return view('pages.products',$data);

    }


   public function keysearch(Request $request)
{
    $keyword = $request->keyword;
    $categorySlug = $request->category;

    $products = Product::query()->where('is_active', 1);

    // 🔎 Keyword search
    if ($keyword) {
        $products->where(function ($q) use ($keyword) {
            $q->where('name', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%");
        });
    }

    // 📂 Category filter
    if ($categorySlug) {

        $category = Category::where('slug', $categorySlug)->first();

        if ($category) {

            // Get child categories also
            $categoryIds = [$category->id];

            $children = Category::where('parent_id', $category->id)->pluck('id')->toArray();

            $categoryIds = array_merge($categoryIds, $children);

            $products->whereIn('category_id', $categoryIds);
        }
    }

    $data['pp_title'] = "Search Results";
    $data['products'] = $products->get();

    $data['search_keyword'] = $keyword;

    $data['categories'] = Category::with('children')
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->get();

    return view('pages.products-search', $data);
}



    public function category($slug, $slug2 = null)
{
    // Parent Category
    $category = Category::where('slug', $slug)->firstOrFail();

    if ($slug2) {
        // Subcategory case
        $subcategory = Category::where('slug', $slug2)
            ->where('parent_id', $category->id)
            ->firstOrFail();

        $products = Product::where('category_id', $subcategory->id)->get();

        $title = $subcategory->name;
    } else {
        // Main category case
        $products = Product::where('category_id', $category->id)->get();

        $title = $category->name;
    }

    return view('pages.products-category', [
        'pp_title' => $title,
        'products' => $products,
    ]);
}




    public function detail($slug)
    {
        
    $product = Product::with([
        'category',
        'approvedReviews.customer'
    ])->where('slug', $slug)
      ->where('is_active', true)
      ->firstOrFail();

    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->limit(8)
        ->get();

    return view('pages.product-detail', compact('product', 'relatedProducts'));

    // ⭐ Review stats
    $data['reviewStats'] = [
        'avg'   => $data['product']->average_rating ?? 0,
        'count' => $data['product']->reviews_count ?? 0,
        'stars' => $data['product']->approvedReviews()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray(),
    ];

    // ✔ Can customer review?
    $data['canReview'] = auth('customer')->check()
        && \App\Models\ProductReview::customerHasPurchasedProduct(
            auth('customer')->id(),
            $data['product']->id
        )
        && ! $data['product']->reviews()
            ->where('customer_id', auth('customer')->id())
            ->exists();


    $data['related'] = Product::where('is_active', 1)
    ->where('id', '!=', $data['product']->id)
    ->where('category_id', $data['product']->category_id)
    ->limit(6)
    ->get(); //get that bis
    //custom business solutions

    return view('pages.product-detail',$data);

    }


  

    public function ajaxList(Request $request)
    {
    $products = Product::query()
        ->where('is_active', true)

        ->when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        })

        ->when($request->categories, function ($q) use ($request) {
        $q->whereIn('category_id', $request->categories);
        })

        ->when($request->min_price>0, function ($q) use ($request) {
            $q->where('offer_price', '>=', $request->min_price);
        })

        ->when($request->max_price, function ($q) use ($request) {
            $q->where('offer_price', '<=', $request->max_price);
        })

        ->when($request->sort, function ($q) use ($request) {
        match ($request->sort) {
        'price_asc'  => $q->orderBy('offer_price', 'asc'),
        'price_desc' => $q->orderBy('offer_price', 'desc'),
        'new'        => $q->latest(),
        'featured'   => $q->orderBy('order', 'asc'),
        'best_seller'=> $q->orderBy('sold_count', 'desc'), // if exists
        default      => null,
        };
        })

        ->orderBy('order')
        ->latest()
        ->get();

        if ($products->isEmpty()) {
        return response()->json([
            'html' => '<div class="col-12 text-center">No products found</div>'
        ]);
        }

    $html = view('products._list', compact('products'))->render();

    return response()->json(['html' => $html]);
    }






    public function storeReview(Request $request, Product $product)
    {
    $customer = auth('customer')->user();
    abort_if(!$customer, 403);

    // ✔ Validate
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'required|string|min:10',
    ]);

    // ✔ Must have purchased
    abort_if(
        !ProductReview::customerHasPurchasedProduct($customer->id, $product->id),
        403,
        'You must purchase this product before reviewing.'
    );

    // ✔ One review per customer
    abort_if(
        ProductReview::where('product_id', $product->id)
            ->where('customer_id', $customer->id)
            ->exists(),
        403,
        'You already reviewed this product.'
    );

    // ✔ Get order ID (first paid order containing this product)
    $orderId = OrderItem::where('product_id', $product->id)
        ->whereHas('order', function ($q) use ($customer) {
            $q->where('customer_id', $customer->id)
              ->where('payment_status', 'paid');
        })
        ->value('order_id');

    ProductReview::create([
        'product_id'  => $product->id,
        'customer_id' => $customer->id,
        'order_id'    => $orderId,
        'rating'      => $request->rating,
        'review'      => $request->review,
    ]);

    return back()->with('success', 'Review submitted for approval.');
}





}
