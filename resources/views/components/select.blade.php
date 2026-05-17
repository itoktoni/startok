@props(['name', 'label' => null, 'col' => '12', 'options' => [], 'default' => null, 'multiple' => false, 'placeholder' => '', 'model' => null])
@php
    global $activeBladeModel;
    $label = $label ?? ucwords(str_replace('_', ' ', $name));
    $m = $model ?? $activeBladeModel ?? null;
    $selected = $default ?? ($m ? old($name, data_get($m, $name, '')) : old($name, ''));
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="label-text text-xs">{{ $label }}</label>
    <select name="{{ $name }}" {{ $multiple ? 'multiple' : '' }} class="select select-sm w-full {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
        @if(!$multiple && $placeholder !== false)
        <option value="">{{ $placeholder ?: '-- '.$label.' --' }}</option>
        @endif
        @foreach($options as $key => $text)
        @php $optVal = is_numeric($key) ? $text : $key; @endphp
        <option value="{{ $optVal }}" @if($multiple) {{ is_array($selected) && in_array($optVal, $selected) ? 'selected' : '' }} @else {{ (string)$selected === (string)$optVal ? 'selected' : '' }} @endif>{{ $text }}</option>
        @endforeach
    </select>
</div>
