@props(['cancel' => '/product/table'])
<div class="fixed bottom-14 lg:bottom-0 left-0 lg:left-56 right-0 bg-base-100 border-t border-base-300 px-3 py-2 z-30">
    <div class="flex gap-1.5 justify-end">
        {{ $slot }}
        <a href="{{ $cancel }}" class="btn btn-sm btn-outline">Cancel</a>
    </div>
</div>
