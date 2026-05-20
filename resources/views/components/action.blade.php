@props(['cancel' => url()->previous(), 'model' => null, 'action' => []])
@php
    $showAction = function($actionName) use ($action) {
        return empty($action) || in_array($actionName, $action);
    };
@endphp
<div class="fixed bottom-[58px] lg:bottom-0 left-0 lg:left-56 right-0 bg-base-100 shadow-sm shadow-gray-400 dark:shadow-gray-800 px-3 py-3 z-30">
    <div class="flex gap-x-3 justify-end">
        {{ $slot }}
        @if($showAction('create'))
            @can('create', $model)
            <a href="{{ moduleRoute('getCreate') }}" class="btn btn-sm btn-primary gap-1">Create</a>
            @endcan
        @endif
        @if($showAction('save'))
            @can('save', $model)
            <x-button type="submit">Save</x-button>
            @endcan
        @endif
        @if($showAction('update'))
            @can('update', $model)
            <x-button type="submit">Update</x-button>
            @endcan
        @endif
        @if($showAction('delete'))
            @can('delete', $model)
            <button class="btn btn-sm btn-error" onclick="deleteSelected()">Delete</button>
            @endcan
        @endif
        @if (empty($model))
        <x-button type="submit">Save</x-button>
        @endif
        <a href="{{ $cancel }}" class="btn btn-sm btn-outline">Cancel</a>
    </div>
</div>
