@props(['text' => null])
<label {{ $attributes->merge(['class' => 'label-text text-xs']) }}>{{ $text ?? $slot }}</label>
