@props(['model' => null, 'total' => null])
<div class="flex items-center gap-2 px-3 mb-2 py-2 shadow-sm shadow-gray-200 dark:shadow-gray-300">
    <button class="btn btn-xs btn-soft" id="mToggleAll" onclick="mToggleAll()">Select All</button>
    @can('delete', $model ?? null)
    @endcan
    <span class="flex-1"></span>
    <span id="mSelCount" class="text-xs text-primary font-medium"></span>
</div>
