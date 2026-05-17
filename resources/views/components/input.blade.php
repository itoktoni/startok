@props(['name', 'label' => null, 'type' => 'text', 'col' => '12', 'value' => null, 'placeholder' => '', 'model' => null])
@php
    global $activeBladeModel;
    $label = $label ?? ucwords(str_replace('_', ' ', $name));
    $m = $model ?? $activeBladeModel ?? null;
    $bound = $value ?? ($m ? old($name, data_get($m, $name, '')) : old($name, ''));
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="label-text text-xs">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ $bound }}" class="input input-sm w-full" placeholder="{{ $placeholder ?: $label }}" {{ $attributes }}>
</div>
