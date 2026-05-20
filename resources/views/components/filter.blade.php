@props(['perPage' => 25, 'perPageOptions' => [5,10,25,50,100], 'fields' => [], 'searchPlaceholder' => 'Search...'])
<div class="card bg-base-100 shadow-sm mb-3">
    <div class="card-body p-3 space-y-2 default-filter">
        <div class="grid grid-cols-12 gap-y-3 gap-x-4">
            <select id="perPage" class="col-span-4 md:col-span-2 select select-sm" onchange="buildUrl()">
                @foreach($perPageOptions as $pp)
                <option value="{{ $pp }}" {{ request('per_page', $perPage) == $pp ? 'selected' : '' }}>{{ $pp }} / page</option>
                @endforeach
            </select>

            @if(isset($advanced))
            <button type="button" class="col-span-4 md:col-span-2 btn btn-sm btn-outline gap-1" onclick="document.getElementById('advFilter').classList.remove('hidden')">
                <span class="icon-[tabler--filter] size-3.5"></span> Advanced
            </button>
            @endif

            @if(count($fields))
            <select id="filterField" class="col-span-4 md:col-span-2 select select-sm">
                @foreach($fields as $key => $label)
                <option value="{{ $key }}" {{ request('_field', array_key_first($fields)) === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @endif

            <div class="join col-span-12 md:col-span-{{ isset($advanced) || count($fields) ? '6' : '10' }}">
                <span class="join-item btn btn-sm no-animation text-xs">Pencarian</span>
                <input type="text" id="searchInput" class="input input-sm join-item flex-1"
                    value="{{ request('_q') }}"
                    placeholder="{{ $searchPlaceholder }}" onkeydown="if(event.key==='Enter')buildUrl()">
                <button type="button" class="btn btn-sm btn-primary join-item" onclick="buildUrl()"><span class="icon-[tabler--search] size-3.5"></span></button>
            </div>
        </div>
    </div>
</div>

@if(isset($advanced))
<div id="advFilter" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('advFilter').classList.add('hidden')"></div>
    <div class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-base-100 shadow-xl p-4 space-y-3 overflow-y-auto">
        <div class="flex justify-between items-center">
            <h3 class="text-sm font-bold">Advanced Filter</h3>
            <button class="btn btn-xs btn-soft btn-circle" onclick="document.getElementById('advFilter').classList.add('hidden')"><span class="icon-[tabler--x] size-4"></span></button>
        </div>
        {{ $advanced }}
    </div>
</div>
@endif
