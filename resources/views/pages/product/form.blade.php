@php $isEdit = isset($model) && $model; @endphp
<x-layouts::app>
    <x-breadcrumb :items="[['url' => '/product/table', 'label' => 'Products'], ['url' => '', 'label' => $isEdit ? 'Update' : 'Create']]" />

    @if($errors->any())
        <x-alert type="error">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </x-alert>
    @endif

    <x-form :action="$isEdit ? '/product/update/'.$model->id : '/product/create'">
        <x-card :label="($isEdit ? 'Update' : 'Create') . ' Product'">
            @bind($model ?? null)
                <x-input col="6" name="name" required />
                <x-input col="6" name="price" type="number" required />
                <x-textarea col="12" name="description" />
            @endbind
        </x-card>

        <x-action cancel="/product/table">
            <x-button type="submit" :variant="$isEdit ? 'soft btn-info' : 'primary'">{{ $isEdit ? 'Update' : 'Create' }}</x-button>
        </x-action>
    </x-form>
</x-layouts::app>
