@props(['name', 'label' => null, 'col' => '12', 'value' => 1, 'checked' => null])
@php
    $label = $label ?? ucwords(str_replace('_', ' ', $name));
    $isChecked = $checked ?? (isset($model) ? old($name, $model->{$name} ?? false) : old($name, false));
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}" class="switch switch-sm switch-primary" {{ $isChecked ? 'checked' : '' }} {{ $attributes }}>
        <span class="text-xs">{{ $label }}</span>
    </label>
</div>
