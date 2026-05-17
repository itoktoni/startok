@props(['name', 'label' => '', 'col' => '12', 'value' => '', 'checked' => null])
@php
    $isChecked = $checked ?? (isset($model) ? old($name, $model->{$name} ?? '') == $value : old($name) == $value);
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="{{ $name }}" value="{{ $value }}" class="radio radio-sm radio-primary" {{ $isChecked ? 'checked' : '' }} {{ $attributes }}>
        <span class="text-xs">{{ $label }}</span>
    </label>
</div>
