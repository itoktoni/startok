@php
$isActive = request()->routeIs($route . '*') || request()->is($route . '*') || request()->url() == $route || request()->url() == rtrim($route, '/');
@endphp
<a href="{{ $route }}" class="flex flex-col items-center gap-0.5 {{ $isActive ? '' : 'text-base-content/50' }}" wire:navigate>
    @if ($isActive)
        <span class="icon-[tabler--{{ $icon }}] size-6"></span>
    @else
        <span class="icon-[tabler--{{ $icon }}] size-6"></span>
    @endif
    <span class="text-[10px] {{ $isActive ? 'mt-0.5' : '' }}">{{ $label }}</span>
</a>
