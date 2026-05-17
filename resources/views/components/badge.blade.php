@props(['type' => 'default'])
<span {{ $attributes->merge(['class' => "badge badge-sm badge-{$type}"]) }}>{{ $slot }}</span>
