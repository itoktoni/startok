@props(['label' => null])
<div class="flex items-center justify-between mt-2 pt-2">
    <span class="text-xs text-base-content/40">{{ $label }}</span>
    <div class="flex gap-1" onclick="event.stopPropagation()">
        {{ $slot }}
    </div>
</div>
