@props(['type' => 'info'])
<div class="alert alert-{{ $type }} text-xs py-2">
    <span>{{ $slot }}</span>
</div>
