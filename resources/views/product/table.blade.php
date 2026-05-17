<x-layouts::app :title="$title">
    <nav class="hidden lg:block text-xs" aria-label="Breadcrumb">
        <ol class="flex items-center gap-1 text-base-content/60">
            <li><a href="/dashboard" class="hover:text-primary">Home</a></li>
            <li><span class="icon-[tabler--chevron-right] size-3"></span></li>
            <li class="text-base-content font-medium">Products</li>
        </ol>
    </nav>

    <div class="content">
        {{-- Filters --}}
    <div class="card bg-base-100 shadow-sm mb-3">
        <div class="card-body p-3 space-y-2 default-filter">
            <div class="grid grid-cols-12 gap-2">
                <select id="perPage" class="col-span-4 md:col-span-2 select select-sm" onchange="buildUrl()">
                    @foreach([5,10,25,50] as $pp)
                    <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }} / page</option>
                    @endforeach
                </select>

                <button type="button" class="col-span-4 md:col-span-2 btn btn-sm btn-outline gap-1" onclick="document.getElementById('advFilter').classList.remove('hidden')">
                    <span class="icon-[tabler--filter] size-3.5"></span> Advanced
                </button>

                <select id="filterField" class="col-span-4 md:col-span-2 select select-sm">
                    <option value="name" {{ request('_field','name')==='name'?'selected':'' }}>Name</option>
                    <option value="price" {{ request('_field')==='price'?'selected':'' }}>Price</option>
                    <option value="description" {{ request('_field')==='description'?'selected':'' }}>Description</option>
                </select>

                <div class="join col-span-12 md:col-span-6">
                    <span class="join-item btn btn-sm no-animation text-xs">Pencarian</span>
                    <input type="text" id="searchInput" class="input input-sm join-item flex-1"
                        value="{{ request('_q') }}"
                        placeholder="Search..." onkeydown="if(event.key==='Enter')buildUrl()">
                    <button type="button" class="btn btn-sm btn-primary join-item" onclick="buildUrl()"><span class="icon-[tabler--search] size-3.5"></span></button>
                </div>

            </div>

        </div>
    </div>

    {{-- Table --}}
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-0">
            <div class="hidden lg:block overflow-x-auto">
                <table class="table table-sm w-full">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="checkbox checkbox-xs" onchange="toggleAll(this)"></th>
                            @php
                                $currentSort = request('sort.0', '');
                                $sortField = str_replace(':desc','',str_replace(':asc','',$currentSort));
                                $sortDir = str_contains($currentSort, ':desc') ? 'desc' : 'asc';
                            @endphp
                            <th class="cursor-pointer select-none" onclick="doSort('name')">Name
                                <span class="icon-[tabler--{{ $sortField==='name' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
                            <th class="cursor-pointer select-none" onclick="doSort('price')">Price
                                <span class="icon-[tabler--{{ $sortField==='price' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
                            <th>Description</th>
                            <th class="cursor-pointer select-none" onclick="doSort('created_at')">Created
                                <span class="icon-[tabler--{{ $sortField==='created_at' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                        <tr>
                            <td><input type="checkbox" class="checkbox checkbox-xs" value="{{ $p->id }}"></td>
                            <td class="font-medium">{{ $p->name }}</td>
                            <td class="font-mono">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="text-base-content/60">{{ Str::limit($p->description, 40) }}</td>
                            <td class="text-base-content/60">{{ $p->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="flex gap-0.5">
                                    <a href="/product/update/{{ $p->id }}" class="btn btn-xs btn-soft btn-circle"><span class="icon-[tabler--edit] size-3"></span></a>
                                    <button onclick="confirmDelete({{ $p->id }})" class="btn btn-xs btn-soft btn-error btn-circle"><span class="icon-[tabler--trash] size-3"></span></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-base-content/50">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile list --}}
            <div class="lg:hidden flex items-center gap-2 px-3 py-2 border-b border-base-200">
                <button class="btn btn-xs btn-soft" id="mToggleAll" onclick="mToggleAll()">Select All</button>
                <span class="flex-1"></span>
                <span id="mSelCount" class="text-xs text-primary font-medium"></span>
            </div>

            <div class="lg:hidden p-2 space-y-2" id="mBody">
                @forelse($products as $p)
                <div class="border rounded-lg p-3 cursor-pointer active:bg-base-200" style="border-color:#ddd" data-id="{{ $p->id }}" onclick="mToggle(this)">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-bold truncate flex-1">{{ $p->name }}</p>
                        <span data-check class="icon-[tabler--circle] size-5 text-base-content/20 shrink-0"></span>
                    </div>
                    <p class="text-sm font-bold font-mono text-primary mt-1">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-base-content/50 mt-1 line-clamp-1">{{ $p->description }}</p>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-base-200">
                        <span class="text-[10px] text-base-content/40">{{ $p->created_at->format('d M Y') }}</span>
                        <div class="flex gap-1">
                            <a href="/product/update/{{ $p->id }}" class="btn btn-xs btn-soft btn-circle" onclick="event.stopPropagation()"><span class="icon-[tabler--edit] size-3"></span></a>
                            <button onclick="event.stopPropagation();confirmDelete({{ $p->id }})" class="btn btn-xs btn-soft btn-error btn-circle"><span class="icon-[tabler--trash] size-3"></span></button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-3 py-6 text-center text-base-content/50 text-xs">No products found.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center gap-2 justify-center py-2">
        <span class="text-[10px] text-base-content/50">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}</span>
        <div class="join">
            @if($products->onFirstPage())
            <span class="btn btn-xs join-item btn-disabled"><span class="icon-[tabler--chevron-left] size-3.5"></span></span>
            @else
            <a href="{{ $products->previousPageUrl() }}" class="btn btn-xs join-item"><span class="icon-[tabler--chevron-left] size-3.5"></span></a>
            @endif
            <span class="btn btn-xs join-item no-animation font-medium">{{ $products->currentPage() }}/{{ $products->lastPage() }}</span>
            @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="btn btn-xs join-item"><span class="icon-[tabler--chevron-right] size-3.5"></span></a>
            @else
            <span class="btn btn-xs join-item btn-disabled"><span class="icon-[tabler--chevron-right] size-3.5"></span></span>
            @endif
        </div>
    </div>

    {{-- Fixed action bar --}}
    <div class="fixed bottom-14 lg:bottom-0 left-0 lg:left-56 right-0 bg-base-100 border-t border-base-300 px-3 py-2 z-30">
        <div class="flex gap-1.5 justify-end">
            <a href="/product/create" class="btn btn-sm btn-primary gap-1">Create</a>
            <button class="btn btn-sm btn-soft btn-error gap-1" onclick="deleteSelected()">Delete</button>
        </div>
    </div>

    {{-- Advanced Filter --}}
    <div id="advFilter" class="hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('advFilter').classList.add('hidden')"></div>
        <div class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-base-100 shadow-xl p-4 space-y-3 overflow-y-auto">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-bold">Advanced Filter</h3>
                <button class="btn btn-xs btn-soft btn-circle" onclick="document.getElementById('advFilter').classList.add('hidden')"><span class="icon-[tabler--x] size-4"></span></button>
            </div>
            <div><label class="label-text text-xs">Min Price</label><input type="number" id="afMin" class="input input-sm w-full" value="{{ request('filters.price.$gte') }}" placeholder="0"></div>
            <div><label class="label-text text-xs">Max Price</label><input type="number" id="afMax" class="input input-sm w-full" value="{{ request('filters.price.$lte') }}" placeholder="∞"></div>
            <div><label class="label-text text-xs">From Date</label><input type="date" id="afFrom" class="input input-sm w-full" value="{{ request('filters.created_at.$gte') }}"></div>
            <div><label class="label-text text-xs">To Date</label><input type="date" id="afTo" class="input input-sm w-full" value="{{ request('filters.created_at.$lte') }}"></div>
            <button class="btn btn-sm btn-primary btn-block" onclick="applyAdvanced()">Apply</button>
            <button class="btn btn-sm btn-soft btn-block" onclick="resetAdvanced()">Reset</button>
        </div>
    </div>
    </div>

    <script>
    // Current sort state
    let currentSortField = '{{ $sortField }}';
    let currentSortDir = '{{ $sortDir }}';
    let mSelected = new Set();

    function buildUrl() {
        const params = new URLSearchParams();
        const q = document.getElementById('searchInput').value.trim();
        const field = document.getElementById('filterField').value;
        const perPage = document.getElementById('perPage').value;
        const minP = document.getElementById('afMin').value;
        const maxP = document.getElementById('afMax').value;
        const from = document.getElementById('afFrom').value;
        const to = document.getElementById('afTo').value;

        // Purity filter based on selected field
        if (q) {
            if (field === 'price') {
                params.set('filters[price][$eq]', q);
            } else {
                params.set('filters[' + field + '][$contains]', q);
            }
            params.set('_field', field);
            params.set('_q', q);
        }

        // Advanced filters
        if (minP) params.set('filters[price][$gte]', minP);
        if (maxP) params.set('filters[price][$lte]', maxP);
        if (from) params.set('filters[created_at][$gte]', from);
        if (to) params.set('filters[created_at][$lte]', to);

        // Purity sort format
        if (currentSortField) params.set('sort[0]', currentSortField + ':' + currentSortDir);

        params.set('per_page', perPage);

        window.location.href = '/product/table?' + params.toString();
    }

    function doSort(col) {
        if (currentSortField === col) {
            currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortField = col;
            currentSortDir = 'asc';
        }
        buildUrl();
    }

    function applyAdvanced() {
        document.getElementById('advFilter').classList.add('hidden');
        buildUrl();
    }

    function resetAdvanced() {
        document.getElementById('afMin').value = '';
        document.getElementById('afMax').value = '';
        document.getElementById('afFrom').value = '';
        document.getElementById('afTo').value = '';
        applyAdvanced();
    }

    function toggleAll(el) {
        document.querySelectorAll('tbody .checkbox').forEach(c => c.checked = el.checked);
    }

    function mToggle(el) {
        const id = el.dataset.id;
        const icon = el.querySelector('[data-check]');
        if (mSelected.has(id)) {
            mSelected.delete(id);
            el.style.backgroundColor = '';
            icon.className = 'icon-[tabler--circle] size-5 text-base-content/20 shrink-0';
        } else {
            mSelected.add(id);
            el.style.backgroundColor = 'rgba(0,0,0,0.03)';
            icon.className = 'icon-[tabler--circle-check-filled] size-5 text-primary shrink-0';
        }
        updateMSel();
    }

    function mToggleAll() {
        const items = document.querySelectorAll('#mBody > div[data-id]');
        if (mSelected.size) {
            mSelected.clear();
            items.forEach(el => { el.style.backgroundColor=''; el.querySelector('[data-check]').className='icon-[tabler--circle] size-5 text-base-content/20 shrink-0'; });
        } else {
            items.forEach(el => { mSelected.add(el.dataset.id); el.style.backgroundColor='rgba(0,0,0,0.03)'; el.querySelector('[data-check]').className='icon-[tabler--circle-check-filled] size-5 text-primary shrink-0'; });
        }
        updateMSel();
    }

    function updateMSel() {
        document.getElementById('mSelCount').textContent = mSelected.size ? mSelected.size + ' selected' : '';
        document.getElementById('mToggleAll').textContent = mSelected.size ? 'Unselect' : 'Select All';
    }

    function deleteSelected() {
        const desktopIds = [...document.querySelectorAll('tbody .checkbox:checked')].map(c => c.value);
        const ids = desktopIds.length ? desktopIds : [...mSelected];
        if (!ids.length) return alert('No items selected');
        if (!confirm(`Delete ${ids.length} product(s)?`)) return;
        const form = document.createElement('form');
        form.method = 'POST'; form.action = '/product/delete-bulk';
        form.innerHTML = `@csrf` + ids.map(id => `<input type="hidden" name="ids[]" value="${id}">`).join('');
        document.body.appendChild(form); form.submit();
    }

    function confirmDelete(id) {
        if (!confirm('Are you sure want to delete?')) return;
        const form = document.createElement('form');
        form.method = 'POST'; form.action = '/product/delete/' + id;
        form.innerHTML = `@csrf`;
        document.body.appendChild(form); form.submit();
    }
    </script>
</x-layouts::app>
