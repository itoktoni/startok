@props(['items' => [], 'title' => 'Menu'])

<div class="card bg-base-100 shadow-sm">
    <div class="card-body p-4">
        <h3 class="text-sm font-bold mb-1">{{ $title }}</h3>
        <div class="divide-y divide-base-200">
            @foreach($items as $item)
            <a href="{{ $item['url'] ?? '#' }}" class="flex items-center gap-3 py-3 px-1 hover:bg-base-200 rounded">
                <span class="{{ $item['icon_class'] ?? 'size-5' }} {{ $item['icon_color'] ?? 'text-primary' }}">{!! $item['icon'] ?? '' !!}</span>
                <span class="flex-1 text-xs font-medium">{{ $item['label'] ?? '' }}</span>
                <span class="icon-[tabler--chevron-right] size-4 text-base-content/30"></span>
            </a>
            @endforeach
        </div>
    </div>
</div>
