<?php

namespace App\Http\Controllers;

use App\Concerns\ControllerTrait;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    use ControllerTrait;

    public function __construct(PosOrder $model)
    {
        $this->model = $model::getModel();
    }

    public function getCreate($request = null)
    {
        $products = Product::select('product_id', 'product_nama', 'product_harga')->get()->map(function ($p) {
            return [
                'value' => $p->product_id,
                'label' => $p->product_nama.' - Rp '.number_format($p->product_harga),
                'price' => $p->product_harga,
            ];
        })->values();

        $variants = Variant::where('variant_active', true)->get()->groupBy('product_id')->map(function ($group) {
            return $group->map(function ($v) {
                return [
                    'value' => $v->variant_id,
                    'label' => $v->variant_nama.' - Rp '.number_format($v->variant_harga),
                    'price' => $v->variant_harga,
                ];
            })->values();
        });

        $discounts = Discount::where('discount_active', true)->get()->map(function ($d) {
            return [
                'value' => $d->discount_id,
                'label' => $d->discount_code.' - '.$d->discount_nama,
                'type' => $d->discount_type,
                'value_amount' => $d->discount_value,
                'max_amount' => $d->discount_max_amount,
                'min_transaction' => $d->discount_min_transaction,
            ];
        })->values();

        $customers = Customer::all()->map(function ($c) {
            return [
                'value' => $c->customer_id,
                'label' => $c->customer_nama.($c->customer_phone ? ' - '.$c->customer_phone : ''),
                'address' => $c->customer_address ?? '',
            ];
        })->values();

        return $this->views($this->template(), [
            'products' => $products,
            'variants' => $variants,
            'discounts' => $discounts,
            'customers' => $customers,
        ]);
    }

    public function getUpdate($id = null)
    {
        $data = $this->model->findOrFail($id);
        $products = Product::select('product_id', 'product_nama', 'product_harga')->get()->map(function ($p) {
            return [
                'value' => $p->product_id,
                'label' => $p->product_nama.' - Rp '.number_format($p->product_harga),
                'price' => $p->product_harga,
            ];
        })->values();

        $variants = Variant::where('variant_active', true)->get()->groupBy('product_id')->map(function ($group) {
            return $group->map(function ($v) {
                return [
                    'value' => $v->variant_id,
                    'label' => $v->variant_nama.' - Rp '.number_format($v->variant_harga),
                    'price' => $v->variant_harga,
                ];
            })->values();
        });

        $discounts = Discount::where('discount_active', true)->get()->map(function ($d) {
            return [
                'value' => $d->discount_id,
                'label' => $d->discount_code.' - '.$d->discount_nama,
                'type' => $d->discount_type,
                'value_amount' => $d->discount_value,
                'max_amount' => $d->discount_max_amount,
                'min_transaction' => $d->discount_min_transaction,
            ];
        })->values();

        $customers = Customer::all()->map(function ($c) {
            return [
                'value' => $c->customer_id,
                'label' => $c->customer_nama.($c->customer_phone ? ' - '.$c->customer_phone : ''),
                'address' => $c->customer_address ?? '',
            ];
        })->values();

        return $this->views($this->template(), [
            'model' => $data,
            'products' => $products,
            'variants' => $variants,
            'discounts' => $discounts,
            'customers' => $customers,
        ]);
    }

    public function postCreate(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customer,customer_id',
            'pos_payment_method' => 'required|string|in:cash,qris,cod',
            'pos_subtotal' => 'required|numeric',
            'pos_discount' => 'nullable|numeric',
            'pos_tax' => 'nullable|numeric',
            'pos_total' => 'required|numeric',
            'pos_shipping_type' => 'nullable|string',
            'pos_shipping_address' => 'nullable|string',
            'pos_notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:product,product_id',
            'items.*.variant_id' => 'nullable|exists:variants,variant_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric',
            'items.*.line_total' => 'required|numeric',
        ]);

        try {
            $order = PosOrder::create([
                'pos_order_code' => PosOrder::generateCode(),
                'customer_id' => $validated['customer_id'] ?? null,
                'pos_payment_method' => $validated['pos_payment_method'],
                'pos_subtotal' => $validated['pos_subtotal'],
                'pos_discount' => $validated['pos_discount'] ?? 0,
                'pos_tax' => $validated['pos_tax'] ?? 0,
                'pos_total' => $validated['pos_total'],
                'pos_shipping_type' => $validated['pos_shipping_type'] ?? 'cod_berbah',
                'pos_shipping_address' => $validated['pos_shipping_address'] ?? null,
                'pos_notes' => $validated['pos_notes'] ?? null,
                'pos_status' => 'pending',
            ]);

            // Create order items
            foreach ($validated['items'] as $item) {
                PosOrderItem::create([
                    'pos_order_id' => $order->pos_id,
                    'pos_detail_product_id' => $item['product_id'],
                    'pos_detail_variant_id' => ! empty($item['variant_id']) ? $item['variant_id'] : null,
                    'pos_detail_unit_price' => $item['unit_price'],
                    'pos_detail_quantity' => $item['quantity'],
                    'pos_detail_line_total' => $item['line_total'],
                ]);
            }

            flash()->success('Order created successfully: '.$order->pos_order_code);

            return redirect()->action([self::class, 'getTable']);
        } catch (\Throwable $th) {
            flash()->error('Failed to create order: '.$th->getMessage());

            return redirect()->back()->withInput();
        }
    }

    public function postUpdate(Request $request, $id)
    {
        $order = PosOrder::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customer,customer_id',
            'pos_payment_method' => 'required|string|in:cash,qris,cod',
            'pos_subtotal' => 'required|numeric',
            'pos_discount' => 'nullable|numeric',
            'pos_tax' => 'nullable|numeric',
            'pos_total' => 'required|numeric',
            'pos_shipping_type' => 'nullable|string',
            'pos_shipping_address' => 'nullable|string',
            'pos_notes' => 'nullable|string',
            'pos_status' => 'required|string|in:pending,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:product,product_id',
            'items.*.variant_id' => 'nullable|exists:variants,variant_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric',
            'items.*.line_total' => 'required|numeric',
        ]);

        try {
            $order->update([
                'customer_id' => $validated['customer_id'] ?? null,
                'pos_payment_method' => $validated['pos_payment_method'],
                'pos_subtotal' => $validated['pos_subtotal'],
                'pos_discount' => $validated['pos_discount'] ?? 0,
                'pos_tax' => $validated['pos_tax'] ?? 0,
                'pos_total' => $validated['pos_total'],
                'pos_shipping_type' => $validated['pos_shipping_type'] ?? 'cod_berbah',
                'pos_shipping_address' => $validated['pos_shipping_address'] ?? null,
                'pos_notes' => $validated['pos_notes'] ?? null,
                'pos_status' => $validated['pos_status'],
            ]);

            // Delete existing items and recreate
            $order->items()->delete();

            foreach ($validated['items'] as $item) {
                PosOrderItem::create([
                    'pos_order_id' => $order->pos_id,
                    'pos_detail_product_id' => $item['product_id'],
                    'pos_detail_variant_id' => ! empty($item['variant_id']) ? $item['variant_id'] : null,
                    'pos_detail_unit_price' => $item['unit_price'],
                    'pos_detail_quantity' => $item['quantity'],
                    'pos_detail_line_total' => $item['line_total'],
                ]);
            }

            flash()->success('Order updated successfully: '.$order->pos_order_code);

            return redirect()->action([self::class, 'getTable']);
        } catch (\Throwable $th) {
            flash()->error('Failed to update order: '.$th->getMessage());

            return redirect()->back()->withInput();
        }
    }
}
