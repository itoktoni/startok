@props(['name', 'label' => null, 'col' => '12', 'value' => null, 'rows' => 3, 'placeholder' => ''])
@php
    $label = $label ?? ucwords(str_replace('_', ' ', $name));
    $val = $value ?? (isset($model) ? old($name, $model->{$name} ?? '') : old($name, ''));
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="label-text text-xs">{{ $label }}</label>
    <textarea name="{{ $name }}" class="textarea textarea-sm w-full" rows="{{ $rows }}" placeholder="{{ $placeholder ?: $label }}" {{ $attributes }}>{{ $val }}</textarea>
</div>
