<x-layouts::app>
    <x-breadcrumb :items="[['url' => '/dashboard', 'label' => 'Home'], ['url' => '', 'label' => 'Products']]" />

    <div class="content">
        {{-- Filters --}}
        <x-filter :per-page="25" :fields="['name' => 'Name', 'price' => 'Price', 'description' => 'Description']">
            <x-slot:advanced>
                <div><x-label text="Min Price" /><input type="number" id="afMin" class="input input-sm w-full" value="{{ request('filters.price.$gte') }}" placeholder="0"></div>
                <div><x-label text="Max Price" /><input type="number" id="afMax" class="input input-sm w-full" value="{{ request('filters.price.$lte') }}" placeholder="∞"></div>
                <div><x-label text="From Date" /><input type="date" id="afFrom" class="input input-sm w-full" value="{{ request('filters.created_at.$gte') }}"></div>
                <div><x-label text="To Date" /><input type="date" id="afTo" class="input input-sm w-full" value="{{ request('filters.created_at.$lte') }}"></div>
                <x-button variant="primary" class="btn-block" onclick="applyAdvanced()">Apply</x-button>
                <x-button variant="soft" class="btn-block" onclick="resetAdvanced()">Reset</x-button>
            </x-slot:advanced>
        </x-filter>

        {{-- Table --}}
        @php
            $currentSort = request('sort.0', '');
            $sortField = str_replace(':desc','',str_replace(':asc','',$currentSort));
            $sortDir = str_contains($currentSort, ':desc') ? 'desc' : 'asc';
        @endphp

        <x-table>
            <x-slot:head>
                <th><input type="checkbox" class="checkbox checkbox-xs" onchange="toggleAll(this)"></th>
                <th class="cursor-pointer select-none" onclick="doSort('name')">Name
                    <span class="icon-[tabler--{{ $sortField==='name' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
                <th class="cursor-pointer select-none" onclick="doSort('price')">Price
                    <span class="icon-[tabler--{{ $sortField==='price' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
                <th>Description</th>
                <th class="cursor-pointer select-none" onclick="doSort('created_at')">Created
                    <span class="icon-[tabler--{{ $sortField==='created_at' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
                <th>Actions</th>
            </x-slot:head>

            <x-slot:body>
                @forelse($tables as $table)
                <tr>
                    <td><input type="checkbox" class="checkbox checkbox-xs" value="{{ $table->id }}"></td>
                    <td class="font-medium">{{ $table->name }}</td>
                    <td class="font-mono">Rp {{ number_format($table->price, 0, ',', '.') }}</td>
                    <td class="text-base-content/60">{{ Str::limit($table->description, 40) }}</td>
                    <td class="text-base-content/60">{{ $table->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="flex gap-0.5">
                            <a href="/product/update/{{ $table->id }}" class="btn btn-xs btn-soft btn-circle"><span class="icon-[tabler--edit] size-3"></span></a>
                            <button onclick="confirmDelete({{ $table->id }})" class="btn btn-xs btn-soft btn-error btn-circle"><span class="icon-[tabler--trash] size-3"></span></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-base-content/50">No products found.</td></tr>
                @endforelse
            </x-slot:body>

            <x-slot:mobile>
                <div class="flex items-center gap-2 px-3 py-2 border-b border-base-200">
                    <button class="btn btn-xs btn-soft" id="mToggleAll" onclick="mToggleAll()">Select All</button>
                    <span class="flex-1"></span>
                    <span id="mSelCount" class="text-xs text-primary font-medium"></span>
                </div>
                <div class="p-2 space-y-2" id="mBody">
                    @forelse($tables as $table)
                    <div class="border rounded-lg p-3 cursor-pointer active:bg-base-200" style="border-color:#ddd" data-id="{{ $table->id }}" onclick="mToggle(this)">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-bold truncate flex-1">{{ $table->name }}</p>
                            <span data-check class="icon-[tabler--circle] size-5 text-base-content/20 shrink-0"></span>
                        </div>
                        <p class="text-sm font-bold font-mono text-primary mt-1">Rp {{ number_format($table->price, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-base-content/50 mt-1 line-clamp-1">{{ $table->description }}</p>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-base-200">
                            <span class="text-[10px] text-base-content/40">{{ $table->created_at->format('d M Y') }}</span>
                            <div class="flex gap-1">
                                <a href="/product/update/{{ $table->id }}" class="btn btn-xs btn-soft btn-circle" onclick="event.stopPropagation()"><span class="icon-[tabler--edit] size-3"></span></a>
                                <button onclick="event.stopPropagation();confirmDelete({{ $table->id }})" class="btn btn-xs btn-soft btn-error btn-circle"><span class="icon-[tabler--trash] size-3"></span></button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-3 py-6 text-center text-base-content/50 text-xs">No products found.</div>
                    @endforelse
                </div>
            </x-slot:mobile>
        </x-table>

        {{-- Pagination --}}
        <x-pagination :paginator="$tables" />

        {{-- Fixed action bar --}}
        <x-action cancel="/product/table">
            <a href="/product/create" class="btn btn-sm btn-primary gap-1">Create</a>
            <x-button variant="soft btn-error" onclick="deleteSelected()">Delete</x-button>
        </x-action>
    </div>

    <script src="/js/product-table.js"></script>
    <script>initTable('{{ $sortField }}', '{{ $sortDir }}');</script>
</x-layouts::app>
