@props(['items' => []])

<div class="grid grid-cols-2 lg:grid-cols-4 gap-2 mt-4 lg:mt-0">
    @foreach($items as $item)
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4 flex-row items-center gap-3">
            <div class="{{ $item['bg_color'] ?? 'bg-primary/10' }} rounded-xl p-2.5">
                {!! $item['icon'] ?? '' !!}
            </div>
            <div>
                <p class="text-xs text-base-content/50">{{ $item['label'] ?? '' }}</p>
                <p class="text-lg font-bold leading-tight">{{ $item['value'] ?? '' }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>
