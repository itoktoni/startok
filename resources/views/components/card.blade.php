@props(['label' => null])
<div class="card bg-base-100 shadow-sm">
    <div class="card-body p-4 gap-3">
        @if($label)
        <h3 class="card-title text-sm">{{ $label }}</h3>
        @endif
        <div class="grid grid-cols-12 gap-3">
            {{ $slot }}
        </div>
    </div>
</div>
