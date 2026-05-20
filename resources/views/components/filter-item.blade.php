@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'operator' => '$eq',
    'operators' => [],
    'options' => [],
    'placeholder' => '...'
])
<div>
    <x-label text="{{ $label }}" />
    @if(count($options))
    <select class="select select-sm w-full" data-field="{{ $name }}" data-op="{{ $operator }}">
        <option value="">-- All --</option>
        @foreach($options as $value => $text)
        <option value="{{ $value }}" {{ request('filters.' . $name . '.$eq') == (string)$value ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>
    @else
    <div class="grid" style="grid-template-columns: 30% 70%; gap: 0.25rem;">
        @if(count($operators))
        <select class="select select-sm" data-op="{{ $name }}">
            @foreach($operators as $op => $symbol)
            <option value="{{ $op }}" {{ $operator === $op ? 'selected' : '' }}>{{ $symbol }}</option>
            @endforeach
        </select>
        @else
        <select class="select select-sm" data-op="{{ $name }}" onchange="updateFilterOp('{{ $name }}')">
            @php
            $defaultOps = [
                '$eq' => '=',
                '$ne' => '!=',
                '$contains' => '~',
                '$notContains' => '!~',
                '$gt' => '>',
                '$gte' => '>=',
                '$lt' => '<',
                '$lte' => '<=',
            ];
            $filterOperator = request('filter_op.' . $name, $operator);
            @endphp
            @foreach($defaultOps as $op => $symbol)
            <option value="{{ $op }}" {{ $filterOperator === $op ? 'selected' : '' }}>{{ $symbol }}</option>
            @endforeach
        </select>
        @endif
        <input type="{{ $type }}" class="input input-sm" data-field="{{ $name }}" value="{{ request('filters.' . $name . '.' . $operator) }}" placeholder="{{ $placeholder }}">
    </div>
    @endif
</div>
