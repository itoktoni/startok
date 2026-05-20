@props(['id' => null])
<div class="mx-1 mb-3 shadow-sm shadow-gray-400 dark:shadow-gray-400 rounded-lg p-3 cursor-pointer active:bg-base-200" data-id="{{ $id }}" onclick="mToggle(this)">
    {{ $slot }}
</div>
