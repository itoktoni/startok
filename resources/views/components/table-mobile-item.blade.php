@props(['id' => null])
<div class="mx-1 mb-3 border border-base-300/20 shadow-xs shadow-gray-300 dark:shadow-gray-300 rounded-lg p-3 cursor-pointer active:bg-base-200" data-id="{{ $id }}" onclick="mToggle(this)">
    {{ $slot }}
</div>
