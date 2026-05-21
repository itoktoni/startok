@props(['name', 'label' => null, 'col' => '12', 'options' => [], 'default' => null, 'multiple' => false, 'placeholder' => '', 'model' => null, 'helper' => null])
@php
    global $activeBladeModel;
    $label = $label ?? formatLabel($name);
    $m = $model ?? $activeBladeModel ?? null;
    $selected = $default ?? ($m ? old($name, data_get($m, $name, '')) : old($name, ''));
    $hasError = $errors->has($name);
    $isTomSelect = $attributes->get('class') && str_contains($attributes->get('class'), 'search');
    $extraClass = $attributes->get('class') ? $attributes->get('class') : '';
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="label-text text-xs">{{ $label }}</label>
    <select name="{{ $name }}" {{ $multiple ? 'multiple' : '' }} id="select-{{ $name }}" @if(!$isTomSelect) class="select select-sm w-full{{ $hasError ? ' is-invalid' : '' }}{{ $extraClass ? ' ' . $extraClass : '' }}" @else class="{{ $extraClass }}{{ $hasError ? ' is-invalid' : '' }}" @endif {{ $attributes->except('class') }}>
        @if(!$multiple && $placeholder !== false)
        <option value="">{{ $placeholder ?: '-- '.$label.' --' }}</option>
        @endif
        @foreach($options as $key => $text)
        <option value="{{ $key }}" @if($multiple) {{ is_array($selected) && in_array($key, $selected) ? 'selected' : '' }} @else {{ (string)$selected === (string)$key ? 'selected' : '' }} @endif>{{ $text }}</option>
        @endforeach
    </select>
    @if($helper && !$hasError)<span class="helper-text ps-3">{{ $helper }}</span>@endif
    @if($hasError)<span class="helper-text text-xs ps-3 text-error">{{ $errors->first($name) }}</span>@endif
</div>
@if($isTomSelect)
@once
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
@endonce
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ts = new TomSelect('#select-{{ $name }}', {
        {!! $multiple ? 'create: true,' : 'create: false,' !!}
        plugins: {!! $multiple ? json_encode(['remove_button']) : json_encode([]) !!}
    });
    @if($hasError)
    ts.wrapper.classList.add('has-error');
    @endif
});
</script>
@endif
