<x-layouts::app :title="__('Dashboard')">

    {{-- Stat Widgets --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 mt-4 lg:mt-0">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="bg-primary/10 rounded-xl p-2.5">
                    <span
                        class="icon-[tabler--currency-dollar] size-6 text-primary">
                    </span>
                </div>
                <div>
                    <p class="text-xs text-base-content/50">Revenue</p>
                    <p class="text-lg font-bold leading-tight">Rp 45.2M</p>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="bg-success/10 rounded-xl p-2.5"><span
                        class="icon-[tabler--shopping-cart] size-6 text-success"></span></div>
                <div>
                    <p class="text-xs text-base-content/50">Orders</p>
                    <p class="text-lg font-bold leading-tight">1,248</p>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="bg-info/10 rounded-xl p-2.5"><span class="icon-[tabler--users] size-6 text-info"></span>
                </div>
                <div>
                    <p class="text-xs text-base-content/50">Customers</p>
                    <p class="text-lg font-bold leading-tight">856</p>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body p-4 flex-row items-center gap-3">
                <div class="bg-warning/10 rounded-xl p-2.5"><span
                        class="icon-[tabler--package] size-6 text-warning"></span></div>
                <div>
                    <p class="text-xs text-base-content/50">Products</p>
                    <p class="text-lg font-bold leading-tight">324</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <h3 class="text-sm font-bold mb-2">Sales Overview</h3>
            {!! $chart->container() !!}
        </div>
    </div>

    {{-- Quick Menu --}}
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <h3 class="text-sm font-bold mb-3">Quick Menu</h3>
            <div class="grid grid-cols-4 lg:grid-cols-8 gap-2">
                <a href="/product/table"
                    class="group flex flex-col items-center gap-2 p-3 rounded-2xl bg-primary/5 hover:bg-primary/10 border border-primary/10 transition">
                    <span
                        class="icon-[tabler--file-invoice] size-7 text-primary group-hover:scale-110 transition-transform"></span>
                    <span class="text-[11px] font-medium text-primary">Invoice</span>
                </a>
                <a href="#"
                    class="group flex flex-col items-center gap-2 p-3 rounded-2xl bg-success/5 hover:bg-success/10 border border-success/10 transition">
                    <span
                        class="icon-[tabler--shopping-cart] size-7 text-success group-hover:scale-110 transition-transform"></span>
                    <span class="text-[11px] font-medium text-success">POS</span>
                </a>
                <a href="#"
                    class="group flex flex-col items-center gap-2 p-3 rounded-2xl bg-info/5 hover:bg-info/10 border border-info/10 transition">
                    <span
                        class="icon-[tabler--forms] size-7 text-info group-hover:scale-110 transition-transform"></span>
                    <span class="text-[11px] font-medium text-info">Forms</span>
                </a>
                <a href="#"
                    class="group flex flex-col items-center gap-2 p-3 rounded-2xl bg-warning/5 hover:bg-warning/10 border border-warning/10 transition">
                    <span
                        class="icon-[tabler--report-analytics] size-7 text-warning group-hover:scale-110 transition-transform"></span>
                    <span class="text-[11px] font-medium text-warning">Reports</span>
                </a>
                <a href="#"
                    class="group flex flex-col items-center gap-2 p-3 rounded-2xl bg-secondary/5 hover:bg-secondary/10 border border-secondary/10 transition">
                    <span
                        class="icon-[tabler--users] size-7 text-secondary group-hover:scale-110 transition-transform"></span>
                    <span class="text-[11px] font-medium text-secondary">Customers</span>
                </a>
                <a href="/product/table"
                    class="group flex flex-col items-center gap-2 p-3 rounded-2xl bg-accent/5 hover:bg-accent/10 border border-accent/10 transition">
                    <span
                        class="icon-[tabler--package] size-7 text-accent group-hover:scale-110 transition-transform"></span>
                    <span class="text-[11px] font-medium text-accent">Products</span>
                </a>
                <a href="#"
                    class="group flex flex-col items-center gap-2 p-3 rounded-2xl bg-neutral/5 hover:bg-neutral/15 border border-neutral/10 transition">
                    <span
                        class="icon-[tabler--settings] size-7 text-neutral group-hover:scale-110 transition-transform"></span>
                    <span class="text-[11px] font-medium text-neutral">Settings</span>
                </a>
                <a href="#"
                    class="group flex flex-col items-center gap-2 p-3 rounded-2xl bg-neutral/5 hover:bg-neutral/15 border border-neutral/10 transition">
                    <span
                        class="icon-[tabler--help-circle] size-7 text-neutral group-hover:scale-110 transition-transform"></span>
                    <span class="text-[11px] font-medium text-neutral">Help</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Menu List --}}
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <h3 class="text-sm font-bold mb-1">Menu</h3>
            <div class="divide-y divide-base-200">
                <a href="/product/table" class="flex items-center gap-3 py-3 px-1 hover:bg-base-200 rounded"><span
                        class="icon-[tabler--file-invoice] size-5 text-primary"></span><span
                        class="flex-1 text-xs font-medium">Invoice Management</span><span
                        class="icon-[tabler--chevron-right] size-4 text-base-content/30"></span></a>
                <a href="#" class="flex items-center gap-3 py-3 px-1 hover:bg-base-200 rounded"><span
                        class="icon-[tabler--shopping-cart] size-5 text-success"></span><span
                        class="flex-1 text-xs font-medium">Point of Sale</span><span
                        class="icon-[tabler--chevron-right] size-4 text-base-content/30"></span></a>
                <a href="#" class="flex items-center gap-3 py-3 px-1 hover:bg-base-200 rounded"><span
                        class="icon-[tabler--forms] size-5 text-info"></span><span
                        class="flex-1 text-xs font-medium">Form Components</span><span
                        class="icon-[tabler--chevron-right] size-4 text-base-content/30"></span></a>
                <a href="#" class="flex items-center gap-3 py-3 px-1 hover:bg-base-200 rounded"><span
                        class="icon-[tabler--report-analytics] size-5 text-warning"></span><span
                        class="flex-1 text-xs font-medium">Reports & Analytics</span><span
                        class="icon-[tabler--chevron-right] size-4 text-base-content/30"></span></a>
                <a href="#" class="flex items-center gap-3 py-3 px-1 hover:bg-base-200 rounded"><span
                        class="icon-[tabler--settings] size-5 text-base-content/60"></span><span
                        class="flex-1 text-xs font-medium">Settings</span><span
                        class="icon-[tabler--chevron-right] size-4 text-base-content/30"></span></a>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <div class="flex gap-1 border-b border-base-300 mb-3">
                <button class="tab-btn px-3 py-1.5 text-xs font-medium border-b-2 border-primary text-primary"
                    onclick="showTab('trx')">Transaksi</button>
                <button
                    class="tab-btn px-3 py-1.5 text-xs font-medium border-b-2 border-transparent text-base-content/50"
                    onclick="showTab('act')">Aktivitas</button>
                <button
                    class="tab-btn px-3 py-1.5 text-xs font-medium border-b-2 border-transparent text-base-content/50"
                    onclick="showTab('prod')">Products</button>
            </div>

            <div id="tab-trx">
                <div class="space-y-2">
                    <div class="flex items-center gap-3 p-2.5 bg-base-200 rounded-lg">
                        <div class="bg-success/10 rounded-full p-1.5"><span
                                class="icon-[tabler--arrow-down-left] size-4 text-success"></span></div>
                        <div class="flex-1">
                            <p class="text-xs font-medium">Payment from PT. Maju Jaya</p>
                            <p class="text-[10px] text-base-content/50">17 May 2026, 08:30</p>
                        </div><span class="text-xs font-bold text-success">+Rp 15.5M</span>
                    </div>
                    <div class="flex items-center gap-3 p-2.5 bg-base-200 rounded-lg">
                        <div class="bg-error/10 rounded-full p-1.5"><span
                                class="icon-[tabler--arrow-up-right] size-4 text-error"></span></div>
                        <div class="flex-1">
                            <p class="text-xs font-medium">Purchase SSD 1TB x10</p>
                            <p class="text-[10px] text-base-content/50">16 May 2026, 14:20</p>
                        </div><span class="text-xs font-bold text-error">-Rp 15M</span>
                    </div>
                    <div class="flex items-center gap-3 p-2.5 bg-base-200 rounded-lg">
                        <div class="bg-success/10 rounded-full p-1.5"><span
                                class="icon-[tabler--arrow-down-left] size-4 text-success"></span></div>
                        <div class="flex-1">
                            <p class="text-xs font-medium">Payment from CV. Berkah</p>
                            <p class="text-[10px] text-base-content/50">15 May 2026, 10:15</p>
                        </div><span class="text-xs font-bold text-success">+Rp 4.5M</span>
                    </div>
                    <div class="flex items-center gap-3 p-2.5 bg-base-200 rounded-lg">
                        <div class="bg-success/10 rounded-full p-1.5"><span
                                class="icon-[tabler--arrow-down-left] size-4 text-success"></span></div>
                        <div class="flex-1">
                            <p class="text-xs font-medium">POS Sale #1247</p>
                            <p class="text-[10px] text-base-content/50">15 May 2026, 09:00</p>
                        </div><span class="text-xs font-bold text-success">+Rp 285K</span>
                    </div>
                </div>
            </div>

            <div id="tab-act" class="hidden">
                <ul class="timeline timeline-vertical timeline-compact timeline-sm">
                    <li>
                        <div class="timeline-start text-[10px] text-base-content/50">10:30</div>
                        <div class="timeline-middle"><span
                                class="icon-[tabler--circle-filled] size-3 text-primary"></span></div>
                        <div class="timeline-end timeline-box text-xs">Invoice INV-2026-0001 created</div>
                        <hr>
                    </li>
                    <li>
                        <hr>
                        <div class="timeline-start text-[10px] text-base-content/50">09:15</div>
                        <div class="timeline-middle"><span
                                class="icon-[tabler--circle-filled] size-3 text-success"></span></div>
                        <div class="timeline-end timeline-box text-xs">Payment received Rp 4.5M</div>
                        <hr>
                    </li>
                    <li>
                        <hr>
                        <div class="timeline-start text-[10px] text-base-content/50">08:45</div>
                        <div class="timeline-middle"><span
                                class="icon-[tabler--circle-filled] size-3 text-info"></span></div>
                        <div class="timeline-end timeline-box text-xs">New customer registered</div>
                        <hr>
                    </li>
                    <li>
                        <hr>
                        <div class="timeline-start text-[10px] text-base-content/50">08:00</div>
                        <div class="timeline-middle"><span
                                class="icon-[tabler--circle-filled] size-3 text-warning"></span></div>
                        <div class="timeline-end timeline-box text-xs">Stock alert: Mouse Wireless low</div>
                    </li>
                </ul>
            </div>

            <div id="tab-prod" class="hidden">
                <div class="hidden md:block overflow-x-auto">
                    <table class="table table-xs w-full">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-medium">Laptop ASUS ROG</td>
                                <td class="font-mono text-base-content/60">SKU-001</td>
                                <td>12</td>
                                <td>Rp 15.0M</td>
                                <td><span class="badge badge-xs badge-success">Active</span></td>
                            </tr>
                            <tr>
                                <td class="font-medium">Monitor LG 27"</td>
                                <td class="font-mono text-base-content/60">SKU-002</td>
                                <td>28</td>
                                <td>Rp 4.5M</td>
                                <td><span class="badge badge-xs badge-success">Active</span></td>
                            </tr>
                            <tr>
                                <td class="font-medium">Mouse Wireless</td>
                                <td class="font-mono text-base-content/60">SKU-004</td>
                                <td>3</td>
                                <td>Rp 350K</td>
                                <td><span class="badge badge-xs badge-warning">Low Stock</span></td>
                            </tr>
                            <tr>
                                <td class="font-medium">Keyboard Mechanical</td>
                                <td class="font-mono text-base-content/60">SKU-003</td>
                                <td>45</td>
                                <td>Rp 1.2M</td>
                                <td><span class="badge badge-xs badge-success">Active</span></td>
                            </tr>
                            <tr>
                                <td class="font-medium">Printer Laser</td>
                                <td class="font-mono text-base-content/60">SKU-010</td>
                                <td>0</td>
                                <td>Rp 3.2M</td>
                                <td><span class="badge badge-xs badge-error">Out</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden space-y-2">
                    <div class="bg-base-200 rounded-lg p-2.5 flex items-center gap-3">
                        <div class="flex-1">
                            <p class="text-xs font-medium">Laptop ASUS ROG</p>
                            <p class="text-[10px] text-base-content/50">SKU-001 · Stock: 12</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold">Rp 15.0M</p><span
                                class="badge badge-xs badge-success">Active</span>
                        </div>
                    </div>
                    <div class="bg-base-200 rounded-lg p-2.5 flex items-center gap-3">
                        <div class="flex-1">
                            <p class="text-xs font-medium">Monitor LG 27"</p>
                            <p class="text-[10px] text-base-content/50">SKU-002 · Stock: 28</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold">Rp 4.5M</p><span
                                class="badge badge-xs badge-success">Active</span>
                        </div>
                    </div>
                    <div class="bg-base-200 rounded-lg p-2.5 flex items-center gap-3">
                        <div class="flex-1">
                            <p class="text-xs font-medium">Mouse Wireless</p>
                            <p class="text-[10px] text-base-content/50">SKU-004 · Stock: 3</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold">Rp 350K</p><span
                                class="badge badge-xs badge-warning">Low</span>
                        </div>
                    </div>
                    <div class="bg-base-200 rounded-lg p-2.5 flex items-center gap-3">
                        <div class="flex-1">
                            <p class="text-xs font-medium">Keyboard Mechanical</p>
                            <p class="text-[10px] text-base-content/50">SKU-003 · Stock: 45</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold">Rp 1.2M</p><span
                                class="badge badge-xs badge-success">Active</span>
                        </div>
                    </div>
                    <div class="bg-base-200 rounded-lg p-2.5 flex items-center gap-3">
                        <div class="flex-1">
                            <p class="text-xs font-medium">Printer Laser</p>
                            <p class="text-[10px] text-base-content/50">SKU-010 · Stock: 0</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold">Rp 3.2M</p><span
                                class="badge badge-xs badge-error">Out</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
    <script>
        function showTab(id) {
            document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + id).classList.remove('hidden');
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.className =
                    'tab-btn px-3 py-1.5 text-xs font-medium border-b-2 border-transparent text-base-content/50';
            });
            event.target.className = 'tab-btn px-3 py-1.5 text-xs font-medium border-b-2 border-primary text-primary';
        }
    </script>
</x-layouts::app>
