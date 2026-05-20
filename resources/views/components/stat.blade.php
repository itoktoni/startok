<div class="card bg-base-100 shadow-sm">
    <div class="card-body p-4 flex-row items-center gap-3">
        <div class="{{ $bg_color }} rounded-xl p-2.5">
            {{ $icon }}
        </div>
        <div>
            <p class="text-xs text-base-content/50">{{ $label }}</p>
            <p class="text-lg font-bold leading-tight">{{ $value }}</p>
        </div>
    </div>
</div>
