@props(['model' => null, 'onchange' => null])
<th class="w-1">
    @can('delete', $model ?? null)
    <input type="checkbox" class="checkbox checkbox-xs" {{ $onchange ? 'onchange=' . $onchange : '' }}>
    @endcan
</th>
