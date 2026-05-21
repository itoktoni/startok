@props(['model' => null, 'id' => null])
<td class="w-1">
    <div class="flex gap-2.5">
        @can('update', $model ?? null)
        <a href="{{ moduleRoute('getUpdate', ['id' => $id]) }}" class="btn btn-sm btn-primary">
            <span class="icon-[tabler--edit] size-4"></span>
        </a>
        @endcan
        @can('delete', $model ?? null)
        <a onclick="return confirm('Apakah anda yakin ingin menghapus ?')" href="{{ moduleRoute('getDelete', ['id' => $id]) }}" class="btn btn-sm btn-error">
            <span class="icon-[tabler--trash] size-4"></span>
        </a>
        @endcan
        {{ $slot }}
    </div>
</td>
