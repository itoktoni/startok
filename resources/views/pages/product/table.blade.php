<x-layouts::app>
    <x-breadcrumb :items="[['url' => '/dashboard', 'label' => 'Home'], ['url' => '', 'label' => ucfirst(module())]]" />
    <div class="content mt-4 lg:mt-0">
        {{-- Filters --}}
        <x-filter :per-page="25" :fields="$fields">
            <x-slot:advanced>
                @foreach ($fields as $key => $advance)
                <x-filter-item :label="$advance" :name="$key"/>
                @endforeach

                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-sm">Status Filter</h3>
                        <x-filter-item label="Status" name="status" :options="[1 => 'Active', 0 => 'Inactive']" />
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-sm">Price Filter</h3>
                        <x-filter-item label="Min Price" name="price" type="number" :operators="['$gte' => '>=']" />
                        <x-filter-item label="Max Price" name="price" type="number" :operators="['$lte' => '<=']" />
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-sm">Date Filter</h3>
                        <x-filter-item label="Date From" name="date_from" type="date" :operators="['$gte' => '>=']" />
                        <x-filter-item label="Date To" name="date_to" type="date" :operators="['$lte' => '<=']" />
                    </div>
                </div>

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
                <x-table-checkbox :model="$model" onchange="toggleAll(this)" />
                <th>Actions</th>
                <x-table-sort field="name" label="Name" :sortField="$sortField" :sortDir="$sortDir" />
                <x-table-sort field="price" label="Price" :sortField="$sortField" :sortDir="$sortDir" />
                <x-table-sort field="description" label="Description" :sortField="$sortField" :sortDir="$sortDir" />
                <x-table-sort field="created_at" label="Created" :sortField="$sortField" :sortDir="$sortDir" />
            </x-slot:head>

            <x-slot:body>
                @foreach($data as $table)
                <tr>
                    <x-table-row-checkbox :model="$model" :value="$table->field_primary" />
                    <x-table-action :model="$model" :id="$table->field_primary" />

                    <td>{{ $table->name }}</td>
                    <td>{{ $table->price }}</td>
                    <td>{{ $table->description }}</td>
                    <td>{{ $table->created_at }}</td>
                </tr>
                @endforeach
            </x-slot:body>

            <x-slot:mobile>
                <x-table-mobile-select :model="$model" />
                <div class="p-2 space-y-2" id="mBody">
                    @foreach($data as $table)
                    <x-table-mobile-item :id="$table->id">
                        <x-table-mobile-header title="{{ $table->name }}" />
                        <x-table-mobile-text :text="formatRupiah($table->price)" size="sm" color="primary" />
                        <x-table-mobile-text :text="$table->description" size="xs" color="muted" />
                        <x-table-mobile-footer :label="formatDate($table->created_at)">
                            <x-table-action :model="$model" :id="$table->field_primary" />
                        </x-table-mobile-footer>
                    </x-table-mobile-item>
                    @endforeach
                </div>
            </x-slot:mobile>

        </x-table>

        <x-pagination :paginator="$data" />
        <x-action :model="$model"/>
    </div>

    <input type="hidden" class="module" value="{{ module() }}">
    <script src="/js/table.js"></script>
    <script>initTable('{{ $sortField }}', '{{ $sortDir }}');</script>
</x-layouts::app>
