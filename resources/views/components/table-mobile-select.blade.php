@props(['model' => null, 'total' => null])
<div class="flex items-center gap-2 px-3 py-2 border-b border-base-200">
    <button class="btn btn-xs btn-soft" id="mToggleAll" onclick="mToggleAll()">Select All</button>
    @can('delete', $model ?? null)
    @endcan
    <span class="flex-1"></span>
    <span id="mSelCount" class="text-xs text-primary font-medium">Total {{ formatAngka($total->total()) }}</span>
</div>
