@props(['cancel' => url()->previous(), 'model' => null])
<div class="fixed bottom-[58px] lg:bottom-0 left-0 lg:left-56 right-0 bg-base-100 border-t border-base-300 px-3 py-3 z-30">
    <div class="flex gap-x-3 justify-end">
        {{ $slot }}
        @can('save', $model)
        <a href="{{ moduleRoute('getCreate') }}" class="btn btn-sm btn-primary gap-1">Create</a>
        @endcan
        @can('delete', $model)
        <button class="btn btn-sm btn-error" onclick="deleteSelected()">Delete</button>
        @endcan
        <a href="{{ $cancel }}" class="btn btn-sm btn-outline">Cancel</a>
    </div>
</div>
