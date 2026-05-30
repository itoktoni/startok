<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Variant;
use App\Models\Discount;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display POS page with products and categories.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::with(['has_category', 'variants'])->get();

        // Transform products for POS view
        $posProducts = $products->map(function ($product) {
            $variants = $product->variants->map(function ($v) {
                return [
                    'variant_nama' => $v->variant_nama,
                    'variant_harga' => (int) $v->variant_harga,
                ];
            })->toArray();
            return [
                'product_nama' => $product->product_nama,
                'product_harga' => (int) $product->product_harga,
                'product_category' => $product->has_category ? $product->has_category->{Category::field_name()} : 'Lainnya',
                'variants' => $variants,
            ];
        });

        // Transform categories
        $posCategories = $categories->map(function ($category) {
            return [
                'category_nama' => $category->{Category::field_name()},
            ];
        })->toArray();

        // Transform discounts
        $discounts = Discount::where('discount_active', true)->get()->map(function ($d) {
            return [
                'code' => $d->discount_code,
                'nama' => $d->discount_nama,
                'type' => $d->discount_type,
                'val' => (int) $d->discount_value,
                'min' => (int) $d->discount_min_transaction,
                'max' => $d->discount_max_amount ? (int) $d->discount_max_amount : null,
            ];
        })->toArray();

        return view('pages.pos', [
            'products' => $posProducts,
            'categories' => $posCategories,
            'discounts' => $discounts,
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
            'shipping_type' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'shipping_lat' => 'nullable|numeric',
            'shipping_lng' => 'nullable|numeric',
            'voucher_code' => 'nullable|string',
            'voucher_discount' => 'nullable|numeric',
            'total' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // Create order
            $order = PosOrder::create([
                'pos_order_code' => PosOrder::generateCode(),
                'pos_payment_method' => $request->payment_method,
                'pos_subtotal' => $request->subtotal,
                'pos_discount' => $request->discount ?? 0,
                'pos_tax' => $request->tax ?? 0,
                'pos_shipping_cost' => $request->shipping_cost ?? 0,
                'pos_total' => $request->total,
                'pos_shipping_type' => $request->shipping_type ?? 'cod_berbah',
                'pos_shipping_address' => $request->shipping_address,
                'pos_shipping_lat' => $request->shipping_lat,
                'pos_shipping_lng' => $request->shipping_lng,
                'pos_voucher_code' => $request->voucher_code,
                'pos_voucher_discount' => $request->voucher_discount ?? 0,
                'pos_status' => 'completed',
            ]);

            // Create order items
            foreach ($request->items as $item) {
                PosOrderItem::create([
                'pos_order_id' => $order->pos_id,
                    'pos_detail_product_name' => $item['name'],
                    'pos_detail_unit_price' => $item['price'],
                    'pos_detail_quantity' => $item['quantity'],
                    'pos_detail_extra_price' => $item['extra'] ?? 0,
                    'pos_detail_variant' => $item['variant'] ?? null,
                    'pos_detail_note' => $item['note'] ?? null,
                    'pos_detail_line_total' => ($item['price'] + ($item['extra'] ?? 0)) * $item['quantity'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order processed successfully',
                'order_id' => $order->pos_order_code,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List all POS orders.
     */
    public function orders(Request $request)
    {
        $orders = PosOrder::with('items')
            ->orderBy('created_at', 'desc')
            ->when($request->date, function ($query) use ($request) {
                return $query->whereDate('created_at', $request->date);
            })
            ->when($request->status, function ($query) use ($request) {
                return $query->where('pos_status', $request->status);
            })
            ->paginate(20);

        return response()->json($orders);
    }

    /**
     * Get single order details.
     */
    public function orderDetail($id)
    {
        $order = PosOrder::with('items')->findOrFail($id);
        return response()->json($order);
    }
}
