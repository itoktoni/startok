<x-layouts::app>
    <x-breadcrumb :items="[['url' => moduleRoute('getTable'), 'label' => ucfirst(module())], ['url' => '', 'label' => isset($model) && $model ? 'Update' : 'Create']]" />

    <x-form :model="$model">
        <x-card :label="ucfirst(module())">
            @bind($model ?? null)

                <x-form-input col="6" name="category_id" />
                <x-form-input col="6" name="category_nama" />
                <x-form-input col="6" name="category_keterangan" />

            @endbind
        </x-card>

        <x-action>
            <x-button type="submit">Save</x-button>
        </x-action>
    </x-form>
</x-layouts::app>
