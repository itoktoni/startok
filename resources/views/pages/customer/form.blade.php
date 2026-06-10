<?php /** @var App\Models\Customer $model */ ?>

<x-layouts::warehouse>
    <x-breadcrumb :items="[['url' => moduleRoute('getTable'), 'label' => ucfirst(module())], ['url' => '', 'label' => isset($model) && $model->exists ? 'Update' : 'Create']]" />

    <x-form :model="$model">
        <x-card :label="ucfirst(module())">
            @bind($model ?? null)

                <x-input col="6" name="customer_nama" />
                <x-input col="6" name="customer_phone" />
                <x-textarea col="12" name="customer_address" />

            @endbind
        </x-card>

        <x-action :model="$model" :action="['save']"/>
    </x-form>
</x-layouts::warehouse>
