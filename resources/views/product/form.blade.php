@php $isEdit = isset($product); $model = $product ?? null; @endphp
<x-layouts::app :title="$title">
    <nav class="hidden lg:block text-xs" aria-label="Breadcrumb">
        <ol class="flex items-center gap-1 text-base-content/60">
            <li><a href="/product/table" class="hover:text-primary">Products</a></li>
            <li><span class="icon-[tabler--chevron-right] size-3"></span></li>
            <li class="text-base-content font-medium">{{ $isEdit ? 'Update' : 'Create' }}</li>
        </ol>
    </nav>

    @if($errors->any())
        <div class="alert alert-error text-xs py-2">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ $isEdit ? '/product/update/'.$product->id : '/product/create' }}" method="POST">
        @csrf
        <x-card :label="($isEdit ? 'Update' : 'Create') . ' Product'">
            <x-input col="6" name="name" required />
            <x-input col="6" name="price" type="number" required />
            <x-textarea col="12" name="description" />
        </x-card>

        <x-action cancel="/product/table">
            <button type="submit" class="btn btn-sm {{ $isEdit ? 'btn-soft btn-info' : 'btn-primary' }}">{{ $isEdit ? 'Update' : 'Create' }}</button>
        </x-action>
    </form>
</x-layouts::app>
