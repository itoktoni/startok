@props(['name', 'label' => null, 'col' => '12', 'options' => [], 'default' => null, 'multiple' => false, 'placeholder' => '', 'model' => null, 'helper' => null])
@php
    global $activeBladeModel;
    $label = $label ?? ucwords(str_replace('_', ' ', $name));
    $m = $model ?? $activeBladeModel ?? null;
    $selected = $default ?? ($m ? old($name, data_get($m, $name, '')) : old($name, ''));
    $hasError = $errors->has($name);
@endphp
<div class="col-span-{{ $col }} md:col-span-{{ $col }}">
    <label class="label-text text-xs">{{ $label }}</label>
    <select name="{{ $name }}" {{ $multiple ? 'multiple' : '' }} class="select select-sm w-full {{ $hasError ? 'is-invalid' : '' }} {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
        @if(!$multiple && $placeholder !== false)
        <option value="">{{ $placeholder ?: '-- '.$label.' --' }}</option>
        @endif
        @foreach($options as $key => $text)
        @php $optVal = is_numeric($key) ? $text : $key; @endphp
        <option value="{{ $optVal }}" @if($multiple) {{ is_array($selected) && in_array($optVal, $selected) ? 'selected' : '' }} @else {{ (string)$selected === (string)$optVal ? 'selected' : '' }} @endif>{{ $text }}</option>
        @endforeach
    </select>
    @if($helper && !$hasError)<span class="helper-text ps-3">{{ $helper }}</span>@endif
    @if($hasError)<span class="helper-text text-xs ps-3 text-error">{{ $errors->first($name) }}</span>@endif
</div>
