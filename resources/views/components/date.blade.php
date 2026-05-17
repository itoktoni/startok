@props(['name', 'label' => null, 'col' => '12', 'value' => null, 'placeholder' => 'Select date'])
@php
    $label = $label ?? ucwords(str_replace('_', ' ', $name));
    $val = $value ?? (isset($model) ? old($name, $model->{$name} ?? '') : old($name, ''));
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="label-text text-xs">{{ $label }}</label>
    <input type="date" name="{{ $name }}" value="{{ $val }}" class="input input-sm w-full" placeholder="{{ $placeholder }}" {{ $attributes }}>
</div>
