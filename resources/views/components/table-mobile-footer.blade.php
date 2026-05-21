@props(['label' => null])
<div class="flex items-center justify-between mt-2 pt-2">
    <span class="badge badge-secondary rounded-xs text-xs text-white">{{ $label }}</span>
    <div class="flex gap-1" onclick="event.stopPropagation()">
        {{ $slot }}
    </div>
</div>
