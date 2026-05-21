<?php /** @var App\Models\Product $model */ ?>

<x-layouts::app>
    <x-breadcrumb :items="[['url' => moduleRoute('getTable'), 'label' => ucfirst(module())], ['url' => '', 'label' => isset($model) && $model ? 'Update' : 'Create']]" />

    <x-form :model="$model">
        <x-card :label="ucfirst(module())">
            @bind($model ?? null)

                <x-select col="6" name="product_id_category" class="search" :options="$category"/>
                <x-input col="6" name="product_nama" />
                <x-input col="6" name="product_harga" />
                <x-input col="6" name="product_keterangan" />

            @endbind
        </x-card>

        <x-action :model="$model" :action="['save']"/>
    </x-form>
</x-layouts::app>
