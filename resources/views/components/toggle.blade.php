@props(['name', 'label' => null, 'col' => '12', 'value' => 1, 'checked' => null])
@php
    $label = $label ?? formatLabel($name);
    $isChecked = $checked ?? (isset($model) ? old($name, $model->{$name} ?? false) : old($name, false));
@endphp
<div class="col-span-12 md:col-span-{{ $col }}">
    <div class="flex items-center gap-3">
        <label class="font-body-sm text-body-sm text-on-surface-variant">{{ $label }}</label>
        <button type="button" x-data="{ on: {{ $isChecked ? 'true' : 'false' }} }" @click="on = !on; $refs.toggleInput.value = on ? '{{ $value }}' : ''" :class="on ? 'bg-primary' : 'bg-outline-variant'" class="relative w-11 h-6 rounded-full transition-colors">
            <span :class="on ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-5 h-5 bg-white rounded-full shadow-sm transition-transform"></span>
        </button>
        <input type="hidden" name="{{ $name }}" x-ref="toggleInput" value="{{ $isChecked ? $value : '' }}">
    </div>
</div>
