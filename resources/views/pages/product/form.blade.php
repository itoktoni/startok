@php $isEdit = isset($model) && $model; @endphp
<x-layouts::app>
    <x-breadcrumb :items="[['url' => moduleRoute('getTable'), 'label' => ucfirst(module())], ['url' => '', 'label' => $isEdit ? 'Update' : 'Create']]" />

    @if($errors->any())
        <x-alert type="error">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </x-alert>
    @endif

    <x-form :model="$model">
        <x-card :label="ucfirst(module())">
            @bind($model ?? null)
                <x-input col="6" name="name" />
                <x-input col="6" name="price" type="number" />
                <x-textarea col="12" name="description" />
            @endbind
        </x-card>

        <x-action>
            <x-button type="submit" :variant="$isEdit ? 'btn-info' : 'primary'">{{ $isEdit ? 'Update' : 'Create' }}</x-button>
        </x-action>
    </x-form>
</x-layouts::app>
