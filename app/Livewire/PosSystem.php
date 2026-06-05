<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\Product;
use Livewire\Component;

class PosSystem extends Component
{
    public $cat = 'All';

    public $search = '';

    public $cart = [];

    public $payMethod = 'cash';

    public $shipCost = 0;

    public $shipCostFromMap = 0;

    public $noteProd = null;

    public $noteVariant = 'Regular';
    public $noteInput = '';

    public $shipAddress = '';
    public $showMap = false;

    public $view = 'grid';

    public $variants = [
        'Regular' => 0,
        'Large' => 5000,
        'Extra' => 10000,
    ];

    public $discAmt = 0;
    public $discIsPct = false;

    public $voucherCode = '';
    public $voucherType = '';
    public $voucherVal = 0;

    public function mount()
    {
        $this->cart = session()->get('pos_cart', []);
    }

    public function updatedCart()
    {
        session()->put('pos_cart', $this->cart);
    }

    public function getProductsProperty()
    {
        return Product::with('has_category')->get();
    }

    public function getCategoriesProperty()
    {
        return Category::pluck('category_nama')->toArray();
    }

    public function getFilteredProductsProperty()
    {
        $query = $this->products;

        if ($this->cat !== 'All') {
            $query = $query->filter(fn ($p) => $p->has_category?->category_nama === $this->cat);
        }

        if ($this->search) {
            $search = strtolower($this->search);
            $query = $query->filter(fn ($p) => str_contains(strtolower($p->product_nama), $search));
        }

        return $query->values();
    }

    public function addToCart($productId)
    {
        $product = Product::with('has_category')->find($productId);
        if (! $product) return;
        $key = $productId.'|Regular|';

        $existing = collect($this->cart)->firstWhere('key', $key);

        if ($existing) {
            foreach ($this->cart as &$item) {
                if ($item['key'] === $key) {
                    $item['qty']++;
                    break;
                }
            }
        } else {
            $this->cart[] = [
                'key' => $key,
                'product_id' => $productId,
                'product_nama' => $product->product_nama,
                'product_harga' => $product->product_harga,
                'qty' => 1,
                'variant' => 'Regular',
                'note' => '',
                'extra' => 0,
            ];
        }
    }

    public function openNote($productId)
    {
        $this->noteProd = $productId;
        $this->noteVariant = 'Regular';
        $this->noteInput = '';
    }

    public function submitNote()
    {
        if (! $this->noteProd) {
            $this->noteProd = null;
            return;
        }

        $product = Product::find($this->noteProd);
        if (! $product) {
            $this->noteProd = null;
            return;
        }

        $extra = $this->variants[$this->noteVariant] ?? 0;
        $key = $this->noteProd.'|'.$this->noteVariant.'|'.$this->noteInput;

        $existing = collect($this->cart)->firstWhere('key', $key);

        if ($existing) {
            foreach ($this->cart as &$item) {
                if ($item['key'] === $key) {
                    $item['qty']++;
                    break;
                }
            }
        } else {
            $this->cart[] = [
                'key' => $key,
                'product_id' => $this->noteProd,
                'product_nama' => $product->product_nama,
                'product_harga' => $product->product_harga,
                'qty' => 1,
                'variant' => $this->noteVariant,
                'note' => $this->noteInput,
                'extra' => $extra,
            ];
        }

        $this->noteProd = null;
        $this->noteVariant = 'Regular';
        $this->noteInput = '';
    }

    public function updateQty($key, $delta)
    {
        foreach ($this->cart as &$item) {
            if ($item['key'] === $key) {
                $item['qty'] += $delta;
                if ($item['qty'] <= 0) {
                    $this->cart = array_values(array_filter($this->cart, fn ($i) => $i['key'] !== $key));
                }
                break;
            }
        }
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->discAmt = 0;
        $this->discIsPct = false;
        $this->voucherCode = '';
        $this->voucherType = '';
        $this->voucherVal = 0;
    }

    public function calcTotal()
    {
        $subtotal = collect($this->cart)->sum(fn ($i) => ($i['product_harga'] + $i['extra']) * $i['qty']);

        // Discount
        $discAmt = (float) $this->discAmt;
        $discount = $this->discIsPct ? $subtotal * $discAmt / 100 : $discAmt;
        $afterDisc = max(0, $subtotal - $discount);

        // Voucher
        if ($this->voucherType === 'pct') {
            $afterDisc = max(0, $afterDisc - $afterDisc * $this->voucherVal / 100);
        } elseif ($this->voucherType === 'fix') {
            $afterDisc = max(0, $afterDisc - $this->voucherVal);
        }

        // Tax 11%
        $withTax = $afterDisc + ($afterDisc * 0.11);

        // Shipping
        $total = $withTax + $this->shipCost;

        return [
            'subtotal' => $subtotal,
            'discount' => round($discount),
            'afterDisc' => round($afterDisc),
            'total' => round($total),
        ];
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Keranjang kosong!');

            return;
        }

        $totals = $this->calcTotal();

        $order = PosOrder::create([
            'pos_order_code' => PosOrder::generateCode(),
            'pos_payment_method' => $this->payMethod,
            'pos_subtotal' => $totals['subtotal'],
            'pos_discount' => $totals['discount'],
            'pos_tax' => round($totals['afterDisc'] * 0.11),
            'pos_shipping_cost' => $this->shipCost,
            'pos_total' => $totals['total'],
            'pos_shipping_type' => $this->shipCost > 0 ? 'delivery' : 'cod_berbah',
            'pos_shipping_address' => $this->shipAddress ?: null,
            'pos_status' => 'completed',
        ]);

        foreach ($this->cart as $item) {
            PosOrderItem::create([
                'pos_order_id' => $order->pos_id,
                'pos_detail_product_id' => $item['product_id'],
                'pos_detail_unit_price' => $item['product_harga'],
                'pos_detail_quantity' => $item['qty'],
                'pos_detail_extra_price' => $item['extra'],
                'pos_detail_note' => $item['note'] ?: null,
                'pos_detail_line_total' => ($item['product_harga'] + $item['extra']) * $item['qty'],
            ]);
        }

        $this->cart = [];
        $this->payMethod = 'cash';
        session()->forget('pos_cart');
        $this->dispatch('notify', message: 'Transaksi berhasil! Order: ' . $order->pos_order_code);
    }

    public function openMap()
    {
        $this->showMap = true;
        $this->dispatch('mapOpened');
    }

    public function confirmMap()
    {
        $this->shipCost = $this->shipCostFromMap;
        $this->shipCostFromMap = 0;
        $this->showMap = false;
    }

    public function setShipCost($cost)
    {
        $this->shipCostFromMap = $cost;
    }

    public function applyVoucher()
    {
        $code = strtoupper(trim($this->voucherCode));
        $map = [
            'HEMAT10' => ['pct', 10],
            'DISKON5K' => ['fix', 5000],
            'GRATIS20' => ['pct', 20],
        ];
        if (! isset($map[$code])) {
            $this->voucherType = '';
            $this->voucherVal = 0;
            return;
        }
        [$this->voucherType, $this->voucherVal] = $map[$code];
    }

    public function toggleDiscType()
    {
        $this->discIsPct = ! $this->discIsPct;
    }

    public function render()
    {
        $totals = $this->calcTotal();

        return view('livewire.pos-system', [
            'products' => $this->filteredProducts,
            'categories' => $this->categories,
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'afterDisc' => $totals['afterDisc'],
            'total' => $totals['total'],
            'variants' => $this->variants,
        ]);
    }
}
