<x-layouts::app>
    <x-breadcrumb :items="[['url' => '/dashboard', 'label' => 'Home'], ['url' => '', 'label' => ucfirst(module())]]" />
    <div class="content mt-4 lg:mt-0">
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
                <th class="w-1">@can('delete', $model)<input type="checkbox" class="checkbox checkbox-xs" onchange="toggleAll(this)">@endcan</th>
                <th>Actions</th>
                <th class="cursor-pointer select-none" onclick="doSort('name')">Name
                    <span class="icon-[tabler--{{ $sortField==='name' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
                <th class="cursor-pointer select-none" onclick="doSort('price')">Price
                    <span class="icon-[tabler--{{ $sortField==='price' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
                <th>Description</th>
                <th class="cursor-pointer select-none" onclick="doSort('created_at')">Created
                    <span class="icon-[tabler--{{ $sortField==='created_at' ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span></th>
            </x-slot:head>

            <x-slot:body>
                @forelse($data as $table)
                <tr>
                    <td>@can('delete', $model)<input type="checkbox" class="checkbox checkbox-xs" value="{{ $table->id }}">@endcan</td>
                    <td class="w-1">
                        <div class="flex gap-0.5">
                            @can('save', $model)
                            <a href="{{ moduleRoute('getUpdate', ['id' => $table->id]) }}" class="btn btn-primary btn-circle">
                                <span class="icon-[tabler--edit] size-4"></span>
                            </a>
                            @endcan
                            @can('delete', $model)
                            <a onclick="return confirm('Apakah anda yakin ingin menghapus ?')" href="{{ moduleRoute('getDelete', ['id' => $table->field_primary]) }}" class="btn btn-error btn-circle">
                                <span class="icon-[tabler--trash] size-4"></span>
                            </a>
                            @endcan
                        </div>
                    </td>
                    <td>{{ $table->name }}</td>
                    <td>{{ formatRupiah($table->price) }}</td>
                    <td>{{ Str::limit($table->description, 40) }}</td>
                    <td class="w-1">{{ formatDate($table->created_at) }}</td>

                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-base-content/50">No data found.</td></tr>
                @endforelse
            </x-slot:body>

            <x-slot:mobile>
                <div class="flex items-center gap-2 px-3 py-2 border-b border-base-200">
                    <button class="btn btn-xs btn-soft" id="mToggleAll" onclick="mToggleAll()">Select All</button>
                    <span class="flex-1"></span>
                    <span id="mSelCount" class="text-xs text-primary font-medium"></span>
                </div>
                <div class="p-2 space-y-2" id="mBody">
                    @forelse($data as $table)
                    <div class="border rounded-lg p-3 cursor-pointer active:bg-base-200" data-id="{{ $table->id }}" onclick="mToggle(this)">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-bold text-lg truncate flex-1">{{ $table->name }}</p>
                            <span data-check class="icon-[tabler--circle] size-5 text-base-content/20 shrink-0"></span>
                        </div>
                        <p class="text-sm font-bold font-mono text-primary mt-1">Rp {{ number_format($table->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-base-content/50 mt-1 line-clamp-1">{{ $table->description }}</p>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-base-200">
                            <span class="text-xs text-base-content/40">{{ formatDate($table->created_at) }}</span>
                            <div class="flex gap-1">
                                @can('save', $model)
                                <a href="{{ moduleRoute('getUpdate', ['id' => $table->id]) }}" class="btn btn-circle" onclick="event.stopPropagation()"><span class="icon-[tabler--edit] size-4"></span></a>
                                @endcan
                                @can('delete', $model)
                                <a onclick="event.stopPropagation();return confirm('Apakah anda yakin ingin menghapus ?')" href="{{ moduleRoute('getDelete', ['id' => $table->field_primary]) }}" class="btn btn-error btn-circle"><span class="icon-[tabler--trash] size-4"></span></a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-3 py-6 text-center text-base-content/50 text-xs">No data found.</div>
                    @endforelse
                </div>
            </x-slot:mobile>

        </x-table>
        {{-- Pagination --}}
        <x-pagination :paginator="$data" />

        {{-- Fixed action bar --}}
        <x-action>
            @can('save', $model)
            <a href="{{ moduleRoute('getCreate') }}" class="btn btn-sm btn-primary gap-1">Create</a>
            @endcan
            @can('delete', $model)
            <button class="btn btn-sm btn-error" onclick="deleteSelected()">Delete</button>
            @endcan
        </x-action>
    </div>

    <script src="/js/table.js"></script>
    <script>initTable('{{ $sortField }}', '{{ $sortDir }}');</script>
</x-layouts::app>
