<?php /** @var App\Models\PosOrder $model */ ?>

<x-layouts::app>
    <x-breadcrumb :items="[
        ['url' => moduleRoute('getTable'), 'label' => 'Sales Order'],
        ['url' => '', 'label' => isset($model) && $model->exists ? 'Update' : 'Create']
    ]" />

    <form method="POST" action="{{ isset($model) && $model->exists ? route('sales-order.postUpdate', ['id' => $model->pos_id]) : moduleRoute('postCreate') }}">
        @csrf
        @if(isset($model) && $model->exists)
            @method('PUT')
        @endif

        {{-- Order Header --}}
        <div class="grid grid-cols-12 gap-3 mb-4">
            <div class="col-span-12 md:col-span-6">
                <x-card label="Order Information">
                    <x-input col="12" name="pos_order_code" label="Order Code" :value="$model->pos_order_code ?? ''" disabled />
                    <x-select col="6" name="pos_payment_method" label="Payment Method" :options="['cash' => 'Cash', 'qris' => 'QRIS', 'cod' => 'COD']" :value="$model->pos_payment_method ?? ''" />
                    <x-select col="6" name="pos_status" label="Status" :options="['pending' => 'Pending', 'completed' => 'Completed', 'cancelled' => 'Cancelled']" :value="$model->pos_status ?? 'pending'" />
                    <x-input col="12" name="pos_shipping_type" label="Shipping Type" :value="$model->pos_shipping_type ?? ''" />
                </x-card>
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-card label="Shipping Address">
                    <x-textarea col="12" name="pos_shipping_address" label="Address" rows="4" :value="$model->pos_shipping_address ?? ''" />
                </x-card>
            </div>
        </div>

        {{-- Items Section --}}
        <div class="card bg-base-100 shadow-sm mb-4">
            <div class="card-body p-4 gap-3">
                <div class="flex items-center justify-between">
                    <h3 class="card-title text-sm">Items</h3>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addRow()">+ Add Item</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-sm w-full" id="itemsTable">
                        <thead>
                            <tr class="bg-base-200">
                                <th class="w-10 text-center">#</th>
                                <th class="min-w-[250px]">Product</th>
                                <th class="w-24">Qty</th>
                                <th class="w-36">Price</th>
                                <th class="w-36 text-right">Line Total</th>
                                <th class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="tB">
                            @if(isset($model) && $model->exists && $model->items->count() > 0)
                                @foreach($model->items as $index => $item)
                                    <tr class="item-row">
                                        <td class="text-center row-num">{{ $index + 1 }}</td>
                                        <td>
                                            <select name="items[{{ $index }}][product_id]" class="product-select select select-sm select-bordered w-full" onchange="updatePrice(this)">
                                                <option value="">Select Product</option>
                                                @foreach($products ?? [] as $product)
                                                    <option value="{{ $product['value'] }}" data-price="{{ $product['price'] }}" {{ $item->pos_detail_product_name == $product['label'] ? 'selected' : '' }}>
                                                        {{ $product['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->pos_detail_quantity }}" min="1" class="input input-sm input-bordered w-full qty-input" onchange="calcRow(this)">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->pos_detail_unit_price }}" class="input input-sm input-bordered w-full price-input" onchange="calcRow(this)">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][line_total]" value="{{ $item->pos_detail_line_total }}" class="input input-sm input-bordered w-full text-right line-total" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-ghost btn-error btn-square" onclick="removeRow(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="item-row">
                                    <td class="text-center row-num">1</td>
                                    <td>
                                        <select name="items[0][product_id]" class="product-select select select-sm select-bordered w-full" onchange="updatePrice(this)">
                                            <option value="">Select Product</option>
                                            @foreach($products ?? [] as $product)
                                                <option value="{{ $product['value'] }}" data-price="{{ $product['price'] }}">{{ $product['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" value="1" min="1" class="input input-sm input-bordered w-full qty-input" onchange="calcRow(this)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" value="0" class="input input-sm input-bordered w-full price-input" onchange="calcRow(this)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][line_total]" value="0" class="input input-sm input-bordered w-full text-right line-total" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-ghost btn-error btn-square" onclick="removeRow(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="bg-base-200 font-semibold">
                                <td colspan="3" class="text-right">Subtotal</td>
                                <td colspan="2" class="text-right" id="tfoot_subtotal">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Summary & Notes --}}
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 md:col-span-6">
                <x-card label="Notes">
                    <x-textarea col="12" name="pos_notes" rows="4" placeholder="Order notes..." :value="$model->pos_notes ?? ''" />
                </x-card>
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-card label="Summary">
                    <div class="col-span-12">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-base-content/60">Subtotal</span>
                            <input type="number" name="pos_subtotal" id="pos_subtotal" value="{{ $model->pos_subtotal ?? 0 }}" class="input input-sm input-bordered w-32 text-right" readonly>
                        </div>
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-base-content/60">Discount</span>
                            <input type="number" name="pos_discount" id="pos_discount" value="{{ $model->pos_discount ?? 0 }}" step="0.01" class="input input-sm input-bordered w-32 text-right" onchange="calcTotal()">
                        </div>
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-base-content/60">Tax (11%)</span>
                            <input type="number" name="pos_tax" id="pos_tax" value="{{ $model->pos_tax ?? 0 }}" step="0.01" class="input input-sm input-bordered w-32 text-right" readonly>
                        </div>
                        <div class="divider my-2"></div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <input type="number" name="pos_total" id="pos_total" value="{{ $model->pos_total ?? 0 }}" step="0.01" class="input input-sm input-bordered w-32 text-right font-bold" readonly>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-4 flex gap-2 justify-end">
            <a href="{{ moduleRoute('getTable') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ isset($model) && $model->exists ? 'Update Order' : 'Create Order' }}
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        let rowCount = {{ isset($model) && $model->exists ? $model->items->count() : 1 }};
        const products = @json($products ?? []);

        // Use event delegation for dynamically added rows
        document.getElementById('tB').addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                updatePrice(e.target);
            } else if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                calcRow(e.target);
            }
        });

        // Also bind to existing selects on page load
        document.querySelectorAll('.product-select').forEach(function(select) {
            select.addEventListener('change', function() {
                updatePrice(this);
            });
        });

        function formatCurrency(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        function updatePrice(select) {
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption ? (selectedOption.getAttribute('data-price') || 0) : 0;
            const row = select.closest('tr');
            const priceInput = row.querySelector('.price-input');
            if (priceInput) {
                priceInput.value = parseFloat(price) || 0;
            }
            calcRow(select);
        }

        function calcRow(element) {
            const row = element.closest('tr');
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const lineTotal = qty * price;

            row.querySelector('.line-total').value = lineTotal.toFixed(2);
            calcTotal();
        }

        function calcTotal() {
            let subtotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const lineTotal = parseFloat(row.querySelector('.line-total').value) || 0;
                subtotal += lineTotal;
            });

            const discount = parseFloat(document.getElementById('pos_discount').value) || 0;
            let afterDiscount = subtotal - discount;
            let tax = afterDiscount * 0.11;
            let total = afterDiscount + tax;

            document.getElementById('pos_subtotal').value = subtotal.toFixed(2);
            document.getElementById('pos_tax').value = tax.toFixed(2);
            document.getElementById('pos_total').value = total.toFixed(2);

            const tfootSubtotal = document.getElementById('tfoot_subtotal');
            if (tfootSubtotal) {
                tfootSubtotal.textContent = formatCurrency(subtotal);
            }
        }

        function addRow() {
            rowCount++;
            const tbody = document.getElementById('tB');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            let optionsHtml = '<option value="">Select Product</option>';
            products.forEach(function(p) {
                optionsHtml += '<option value="' + p.value + '" data-price="' + p.price + '">' + p.label + '</option>';
            });
            newRow.innerHTML = `
                <td class="text-center row-num">${rowCount}</td>
                <td>
                    <select name="items[${rowCount}][product_id]" class="product-select select select-sm select-bordered w-full">
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][quantity]" value="1" min="1" class="input input-sm input-bordered w-full qty-input">
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][unit_price]" value="0" class="input input-sm input-bordered w-full price-input">
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][line_total]" value="0" class="input input-sm input-bordered w-full text-right line-total" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-ghost btn-error btn-square" onclick="removeRow(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
            renumberRows();
        }

        function removeRow(btn) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                btn.closest('tr').remove();
                renumberRows();
                calcTotal();
            }
        }

        function renumberRows() {
            document.querySelectorAll('.item-row').forEach((row, index) => {
                row.querySelector('.row-num').textContent = index + 1;
            });
        }

        // Initialize calculation on page load
        document.addEventListener('DOMContentLoaded', function() {
            calcTotal();
        });
    </script>
    @endpush
</x-layouts::app>
