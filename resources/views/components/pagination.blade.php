@props(['paginator'])
<div class="flex items-center gap-2 justify-center py-2">
    <span class="text-[10px] text-base-content/50">{{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}</span>
    <div class="join">
        @if($paginator->onFirstPage())
        <span class="btn btn-xs join-item btn-disabled"><span class="icon-[tabler--chevron-left] size-3.5"></span></span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-xs join-item"><span class="icon-[tabler--chevron-left] size-3.5"></span></a>
        @endif
        <span class="btn btn-xs join-item no-animation font-medium">{{ $paginator->currentPage() }}/{{ $paginator->lastPage() }}</span>
        @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-xs join-item"><span class="icon-[tabler--chevron-right] size-3.5"></span></a>
        @else
        <span class="btn btn-xs join-item btn-disabled"><span class="icon-[tabler--chevron-right] size-3.5"></span></span>
        @endif
    </div>
</div>
