@props(['model' => null, 'id' => null])
<td class="w-1">
    <div class="flex gap-0.5">
        @can('save', $model ?? null)
        <a href="{{ moduleRoute('getUpdate', ['id' => $id]) }}" class="btn btn-primary btn-circle">
            <span class="icon-[tabler--edit] size-4"></span>
        </a>
        @endcan
        @can('delete', $model ?? null)
        <a onclick="return confirm('Apakah anda yakin ingin menghapus ?')" href="{{ moduleRoute('getDelete', ['id' => $id]) }}" class="btn btn-error btn-circle">
            <span class="icon-[tabler--trash] size-4"></span>
        </a>
        @endcan
        {{ $slot }}
    </div>
</td>
