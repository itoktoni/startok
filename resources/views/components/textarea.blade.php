@props(['name', 'label' => null, 'col' => '12', 'value' => null, 'rows' => 3, 'placeholder' => '', 'model' => null, 'helper' => null])
@php
    global $activeBladeModel;
    $label = $label ?? ucwords(str_replace('_', ' ', $name));
    $m = $model ?? $activeBladeModel ?? null;
    $bound = $value ?? ($m ? old($name, data_get($m, $name, '')) : old($name, ''));
    $hasError = $errors->has($name);
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="label-text text-xs">{{ $label }}</label>
    <textarea name="{{ $name }}" class="textarea textarea-sm w-full {{ $hasError ? 'is-invalid' : '' }}" rows="{{ $rows }}" placeholder="{{ $placeholder ?: $label }}" {{ $attributes }}>{{ $bound }}</textarea>
    @if($helper && !$hasError)<span class="helper-text ps-3">{{ $helper }}</span>@endif
    @if($hasError)<span class="helper-text text-xs ps-3 text-error">{{ $errors->first($name) }}</span>@endif
</div>
