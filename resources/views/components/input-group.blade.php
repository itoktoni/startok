@props(['name', 'label' => null, 'col' => '12', 'type' => 'text', 'value' => null, 'placeholder' => '', 'prefix' => null, 'suffix' => null])
@php
    $label = $label ?? ($name ? ucwords(str_replace('_', ' ', $name)) : null);
    $val = $value ?? (isset($model) ? old($name, $model->{$name} ?? '') : old($name, ''));
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    @if($label)<label class="label-text text-xs">{{ $label }}</label>@endif
    <div class="join w-full">
        @if($prefix)<span class="join-item btn btn-sm no-animation">{{ $prefix }}</span>@endif
        <input type="{{ $type }}" name="{{ $name }}" value="{{ $val }}" class="input input-sm join-item flex-1" placeholder="{{ $placeholder ?: $label }}" {{ $attributes }}>
        @if($suffix)<span class="join-item btn btn-sm no-animation">{{ $suffix }}</span>@endif
    </div>
</div>
