<?php /** @var App\Models\PosOrder $model */ ?>

<x-layouts::warehouse>
    <x-breadcrumb :items="[
        ['url' => moduleRoute('getTable'), 'label' => 'Sales Order'],
        ['url' => '', 'label' => isset($model) && $model->exists ? 'Update' : 'Create']
    ]" />

    <x-form :model="$model">

        {{-- Order Header --}}
        <div class="grid grid-cols-12 gap-3 mb-4">
            <div class="col-span-12 md:col-span-6">
                <x-card label="Order Information">
                    <x-input col="12" name="pos_order_code" label="Order Code" :value="$model->pos_order_code ?? ''" disabled />
                    <x-select col="12" name="customer_id" label="Customer" :options="$customers->pluck('label', 'value')->toArray()" :default="$model->customer_id ?? ''" placeholder="-- Select Customer --" onchange="onCustomerChange(this)" />
                    <x-select col="6" name="pos_payment_method" label="Payment Method" :options="['cash' => 'Cash', 'qris' => 'QRIS', 'cod' => 'COD']" :default="$model->pos_payment_method ?? ''" />
                    <x-select col="6" name="pos_status" label="Status" :options="['pending' => 'Pending', 'completed' => 'Completed', 'cancelled' => 'Cancelled']" :default="$model->pos_status ?? 'pending'" />
                    <x-select col="12" name="pos_shipping_type" label="Shipping Type" :options="['cod_berbah' => 'COD Berbah', 'cod_piyungan' => 'COD Piyungan', 'delivery' => 'Delivery']" :default="$model->pos_shipping_type ?? 'cod_berbah'" />
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
                                <th class="min-w-[200px]">Product</th>
                                <th class="min-w-[180px]">Variant</th>
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
                                            <select name="items[{{ $index }}][product_id]" class="product-select select select-sm select-bordered w-full" onchange="onProductChange(this)">
                                                <option value="">Select Product</option>
                                                @foreach($products ?? [] as $product)
                                                    <option value="{{ $product['value'] }}" data-price="{{ $product['price'] }}" {{ $item->pos_detail_product_id == $product['value'] ? 'selected' : '' }}>
                                                        {{ $product['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="items[{{ $index }}][variant_id]" class="variant-select select select-sm select-bordered w-full" onchange="onVariantChange(this)" data-selected="{{ $item->pos_detail_variant_id ?? '' }}">
                                                <option value="">No Variant</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->pos_detail_quantity }}" min="1" class="input input-sm input-bordered w-full qty-input" oninput="calcRow(this)">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->pos_detail_unit_price }}" class="input input-sm input-bordered w-full price-input" oninput="calcRow(this)">
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
                                        <select name="items[0][product_id]" class="product-select select select-sm select-bordered w-full" onchange="onProductChange(this)">
                                            <option value="">Select Product</option>
                                            @foreach($products ?? [] as $product)
                                                <option value="{{ $product['value'] }}" data-price="{{ $product['price'] }}">{{ $product['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="items[0][variant_id]" class="variant-select select select-sm select-bordered w-full" onchange="onVariantChange(this)">
                                            <option value="">No Variant</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" value="1" min="1" class="input input-sm input-bordered w-full qty-input" oninput="calcRow(this)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" value="0" class="input input-sm input-bordered w-full price-input" oninput="calcRow(this)">
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
                                <td colspan="4" class="text-right">Subtotal</td>
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
                            <div class="flex items-center gap-2">
                                <span class="text-base-content/60">Discount</span>
                                <select name="pos_discount_id" id="pos_discount_id" class="select select-sm select-bordered w-48" onchange="applyDiscount(this)">
                                    <option value="">No Discount</option>
                                    @foreach($discounts ?? [] as $discount)
                                        <option value="{{ $discount['value'] }}" data-type="{{ $discount['type'] }}" data-value="{{ $discount['value_amount'] }}" data-max="{{ $discount['max_amount'] }}" data-min="{{ $discount['min_transaction'] }}">
                                            {{ $discount['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="pos_discount" id="pos_discount" value="{{ $model->pos_discount ?? 0 }}">
                            </div>
                            <span id="pos_discount_display" class="text-sm font-medium w-32 text-right">-Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mb-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="pos_tax_enabled" class="checkbox checkbox-sm" onchange="calcTotal()" checked>
                                <span class="text-base-content/60">Tax (11%)</span>
                            </label>
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
    </x-form>

    <script>
        let rowCount = {{ isset($model) && $model->exists ? $model->items->count() : 1 }};
        const products = @json($products ?? []);
        const variants = @json($variants ?? []);
        const customers = @json($customers ?? []);

        function onCustomerChange(select) {
            const selectedOption = select.options[select.selectedIndex];
            const customerId = selectedOption ? selectedOption.value : '';
            const addressInput = document.querySelector('[name="pos_shipping_address"]');
            if (customerId && customers.length) {
                const customer = customers.find(c => c.value == customerId);
                if (customer && customer.address) {
                    addressInput.value = customer.address;
                }
            }
        }

        function formatCurrency(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        function populateVariants(row, productId) {
            const variantSelect = row.querySelector('.variant-select');
            variantSelect.innerHTML = '<option value="">No Variant</option>';
            if (productId && variants[productId]) {
                variants[productId].forEach(function(v) {
                    const opt = document.createElement('option');
                    opt.value = v.value;
                    opt.textContent = v.label;
                    opt.setAttribute('data-price', v.price);
                    variantSelect.appendChild(opt);
                });
            }
        }

        function onProductChange(select) {
            const selectedOption = select.options[select.selectedIndex];
            const productId = selectedOption ? selectedOption.value : '';
            const productPrice = selectedOption ? (parseFloat(selectedOption.getAttribute('data-price')) || 0) : 0;
            const row = select.closest('tr');

            populateVariants(row, productId);

            const priceInput = row.querySelector('.price-input');
            priceInput.value = productPrice;
            calcRow(select);
        }

        function onVariantChange(select) {
            const selectedOption = select.options[select.selectedIndex];
            const row = select.closest('tr');
            const priceInput = row.querySelector('.price-input');
            const productSelect = row.querySelector('.product-select');
            const productOption = productSelect.options[productSelect.selectedIndex];
            const productPrice = productOption ? (parseFloat(productOption.getAttribute('data-price')) || 0) : 0;

            if (selectedOption && selectedOption.value) {
                const variantPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                priceInput.value = productPrice + variantPrice;
            } else {
                priceInput.value = productPrice;
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
            const taxEnabled = document.getElementById('pos_tax_enabled').checked;
            let tax = taxEnabled ? afterDiscount * 0.11 : 0;
            let total = afterDiscount + tax;

            document.getElementById('pos_subtotal').value = subtotal.toFixed(2);
            document.getElementById('pos_tax').value = tax.toFixed(2);
            document.getElementById('pos_total').value = total.toFixed(2);

            const discountDisplay = document.getElementById('pos_discount_display');
            if (discountDisplay) {
                discountDisplay.textContent = discount > 0 ? '-' + formatCurrency(discount) : '-Rp 0';
            }

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
                    <select name="items[${rowCount}][product_id]" class="product-select select select-sm select-bordered w-full" onchange="onProductChange(this)">
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <select name="items[${rowCount}][variant_id]" class="variant-select select select-sm select-bordered w-full" onchange="onVariantChange(this)">
                        <option value="">No Variant</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][quantity]" value="1" min="1" class="input input-sm input-bordered w-full qty-input" oninput="calcRow(this)">
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][unit_price]" value="0" class="input input-sm input-bordered w-full price-input" oninput="calcRow(this)">
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

        function applyDiscount(select) {
            const opt = select.options[select.selectedIndex];
            if (!opt || !opt.value) {
                document.getElementById('pos_discount').value = 0;
                calcTotal();
                return;
            }

            const type = opt.getAttribute('data-type');
            const value = parseFloat(opt.getAttribute('data-value')) || 0;
            const maxAmount = parseFloat(opt.getAttribute('data-max')) || 0;
            const minTransaction = parseFloat(opt.getAttribute('data-min')) || 0;

            let subtotal = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                subtotal += parseFloat(row.querySelector('.line-total').value) || 0;
            });

            if (minTransaction > 0 && subtotal < minTransaction) {
                alert('Minimum transaction for this discount is Rp ' + minTransaction.toLocaleString('id-ID'));
                select.value = '';
                document.getElementById('pos_discount').value = 0;
                calcTotal();
                return;
            }

            let discountAmount = type === 'percentage' ? subtotal * value / 100 : value;

            if (maxAmount > 0 && discountAmount > maxAmount) {
                discountAmount = maxAmount;
            }

            document.getElementById('pos_discount').value = discountAmount.toFixed(2);
            calcTotal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.item-row').forEach(function(row) {
                const productSelect = row.querySelector('.product-select');
                if (productSelect && productSelect.value) {
                    populateVariants(row, productSelect.value);

                    const variantSelect = row.querySelector('.variant-select');
                    const selectedId = variantSelect.getAttribute('data-selected');
                    if (selectedId) {
                        variantSelect.value = selectedId;
                    }
                }
            });
            calcTotal();
        });
    </script>
</x-layouts::warehouse>
