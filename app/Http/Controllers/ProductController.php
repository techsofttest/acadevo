<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
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
            ->get();

        $data['products'] = Product::with(['variants', 'defaultVariant'])
            ->where('is_active', 1)
            ->get();

        return view('pages.products', $data);
    }

    public function keysearch(Request $request)
    {
        $keyword = $request->keyword;
        $categorySlug = $request->category;

        $products = Product::query()
            ->with(['variants', 'defaultVariant'])
            ->where('is_active', 1);

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
        $category = Category::where('slug', $slug)->firstOrFail();

        if ($slug2) {
            // Subcategory
            $subcategory = Category::where('slug', $slug2)
                ->where('parent_id', $category->id)
                ->firstOrFail();

            $products = Product::with(['variants', 'defaultVariant'])
                ->where('is_active', 1)
                ->where('category_id', $subcategory->id)
                ->get();

            $title = $subcategory->name;
        } else {
            // ✅ Get all subcategory IDs
            $subcategoryIds = Category::where('parent_id', $category->id)
                ->pluck('id')
                ->toArray();

            // Include parent ID also
            $allCategoryIds = array_merge([$category->id], $subcategoryIds);

            $products = Product::with(['variants', 'defaultVariant'])
                ->where('is_active', 1)
                ->whereIn('category_id', $allCategoryIds)
                ->get();

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
            'variants',
            'defaultVariant',
            'approvedReviews.customer'
        ])->where('slug', $slug)
          ->where('is_active', true)
          ->firstOrFail();

        $relatedProducts = Product::with(['variants', 'defaultVariant'])
            ->where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(8)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    public function ajaxList(Request $request)
    {
        $query = Product::query()
            ->with(['variants', 'defaultVariant'])
            ->where('is_active', 1);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->categories) {
            $query->whereIn('category_id', $request->categories);
        }

        // Filter by variants selling_price
        if ($request->min_price > 0 || $request->max_price) {
            $query->whereHas('variants', function ($q) use ($request) {
                if ($request->min_price > 0) {
                    $q->where('selling_price', '>=', $request->min_price);
                }
                if ($request->max_price) {
                    $q->where('selling_price', '<=', $request->max_price);
                }
            });
        }

        // Sort by price or default order
        if ($request->sort) {
            match ($request->sort) {
                'price_asc' => $query->select('products.*')
                    ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->where('product_variants.is_default', true)
                    ->orderBy('product_variants.selling_price', 'asc'),

                'price_desc' => $query->select('products.*')
                    ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->where('product_variants.is_default', true)
                    ->orderBy('product_variants.selling_price', 'desc'),

                'new' => $query->latest(),
                'featured' => $query->orderBy('order', 'asc'),
                default => null,
            };
        } else {
            $query->orderBy('order')->latest();
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            return response()->json([
                'html' => '<div class="col-12 text-center">No products found</div>'
            ]);
        }

        $html = view('components.product-grid', compact('products'))->render();

        return response()->json(['html' => $html]);
    }

    public function storeReview(Request $request, Product $product)
    {
        $customer = auth('customer')->user();
        abort_if(!$customer, 403);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10',
        ]);

        abort_if(
            !ProductReview::customerHasPurchasedProduct($customer->id, $product->id),
            403,
            'You must purchase this product before reviewing.'
        );

        abort_if(
            ProductReview::where('product_id', $product->id)
                ->where('customer_id', $customer->id)
                ->exists(),
            403,
            'You already reviewed this product.'
        );

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
