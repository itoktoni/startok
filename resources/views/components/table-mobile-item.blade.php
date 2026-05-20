@props(['id' => null])
<div class="border rounded-lg p-3 cursor-pointer active:bg-base-200" data-id="{{ $id }}" onclick="mToggle(this)">
    {{ $slot }}
</div>
