@props(['type' => 'button', 'variant' => 'primary', 'size' => 'sm', 'icon' => null])
<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-{$size} btn-{$variant}"]) }}>
    @if($icon)<span class="icon-[tabler--{{ $icon }}] size-3.5"></span>@endif
    {{ $slot }}
</button>
