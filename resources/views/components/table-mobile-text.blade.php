@props(['text' => null, 'size' => 'sm', 'color' => null])
@php
$sizeClass = match($size) {
    'xs' => 'text-xs',
    'sm' => 'text-sm',
    'lg' => 'text-lg',
    'xl' => 'text-xl',
    default => 'text-sm'
};
$colorClass = match($color) {
    'primary' => 'text-primary',
    'secondary' => 'text-secondary',
    'accent' => 'text-accent',
    'neutral' => 'text-neutral',
    'base-content' => 'text-base-content',
    'success' => 'text-success',
    'warning' => 'text-warning',
    'error' => 'text-error',
    'muted' => 'text-base-content/50',
    default => ''
};
$weightClass = $size === 'lg' || $size === 'xl' ? 'font-bold' : '';
@endphp
<p class="{{ $sizeClass }} {{ $colorClass }} {{ $weightClass }} {{ $attributes->get('class') }}">{{ $text }}</p>
