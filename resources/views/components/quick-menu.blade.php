@props(['items' => [], 'title' => 'Quick Menu'])

<div class="card bg-base-100 shadow-sm">
    <div class="card-body p-4">
        <h3 class="text-sm font-bold mb-3">{{ $title }}</h3>
        <div class="grid grid-cols-4 lg:grid-cols-8 gap-2">
            @foreach($items as $item)
            <a href="{{ $item['url'] ?? '#' }}"
                class="group flex flex-col items-center gap-2 p-3 rounded-2xl {{ $item['bg_class'] ?? 'bg-primary/5 hover:bg-primary/10 border border-primary/10' }} transition">
                <span class="{{ $item['icon_class'] ?? 'size-7' }} {{ $item['icon_color'] ?? 'text-primary' }} group-hover:scale-110 transition-transform">{!! $item['icon'] ?? '' !!}</span>
                <span class="text-[11px] font-medium {{ $item['text_color'] ?? 'text-primary' }}">{{ $item['label'] ?? '' }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>
