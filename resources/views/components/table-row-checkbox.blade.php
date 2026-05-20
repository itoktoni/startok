@props(['model' => null, 'value' => null])
<td>
    @can('delete', $model ?? null)
    <input type="checkbox" class="checkbox checkbox-xs" value="{{ $value }}">
    @endcan
</td>
