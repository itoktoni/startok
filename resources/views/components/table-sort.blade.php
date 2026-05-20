@props(['field', 'label', 'sortField' => '', 'sortDir' => 'asc'])
<th class="cursor-pointer select-none" onclick="doSort('{{ $field }}')">
    {{ $label }}
    <span class="icon-[tabler--{{ $sortField===$field ? ($sortDir==='asc'?'sort-ascending':'sort-descending') : 'arrows-sort' }}] size-3 align-middle text-base-content/40"></span>
</th>
