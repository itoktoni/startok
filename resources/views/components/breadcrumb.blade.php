@props(['items' => []])
<nav class="hidden lg:block text-xs" aria-label="Breadcrumb">
    <ol class="flex items-center gap-1 text-base-content/60">
        @foreach($items as $item)
            @if(!$loop->last)
                <li><a href="{{ $item['url'] }}" class="hover:text-primary">{{ $item['label'] }}</a></li>
                <li><span class="icon-[tabler--chevron-right] size-3"></span></li>
            @else
                <li class="text-base-content font-medium">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
