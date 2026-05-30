<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PosController extends Controller
{
    /**
     * Display POS page with products and categories.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::with('has_category')->get();

        // Transform products for POS view
        $posProducts = $products->map(function ($product) {
            return [
                'n' => $product->product_nama,
                'p' => (int) $product->product_harga,
                'c' => $product->has_category ? $product->has_category->{Category::field_name()} : 'Lainnya',
            ];
        });

        // Transform categories
        $posCategories = $categories->map(function ($category) {
            return $category->{Category::field_name()};
        })->toArray();

        return view('pages.pos', [
            'products' => $posProducts,
            'categories' => $posCategories,
            'store_lat' => config('shipping.store_lat'),
            'store_lng' => config('shipping.store_lng'),
            'price_per_km' => config('shipping.price_per_km'),
        ]);
    }

    /**
     * Get products for POS (API endpoint).
     */
    public function products(Request $request)
    {
        $products = Product::with('has_category')
            ->when($request->category && $request->category !== 'All', function ($query) use ($request) {
                return $query->whereHas('has_category', function ($q) use ($request) {
                    $q->where(Category::field_name(), $request->category);
                });
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where('product_nama', 'like', '%' . $request->search . '%');
            })
            ->get();

        return response()->json($products->map(function ($product) {
            return [
                'n' => $product->product_nama,
                'p' => (int) $product->product_harga,
                'c' => $product->has_category ? $product->has_category->{Category::field_name()} : 'Lainnya',
            ];
        }));
    }

    /**
     * Process checkout (API endpoint).
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant' => 'nullable|string',
            'items.*.note' => 'nullable|string',
            'items.*.extra' => 'nullable|numeric',
            'payment_method' => 'required|string',
            'subtotal' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'shipping_cost' => 'nullable|numeric',
            'total' => 'required|numeric',
        ]);

        // Here you would typically:
        // 1. Create an order record
        // 2. Create order items
        // 3. Process payment
        // 4. Update stock

        // For now, return success response
        return response()->json([
            'success' => true,
            'message' => 'Order processed successfully',
            'order_id' => 'ORD-' . time(),
        ]);
    }
}
