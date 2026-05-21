<?php /** @var App\Models\Pos $model */ ?>

<x-layouts::app :title="__('POS Form')">
    <x-breadcrumb :items="[['url' => moduleRoute('getTable'), 'label' => 'POS'], ['url' => '', 'label' => isset($model) && $model->exists ? 'Update' : 'Create']]" />

    <x-form :model="$model">
        <x-card :label="ucfirst(module())">
            @bind($model ?? null)
                <x-input col="3" name="pos_no" />
                <x-input col="3" name="pos_total" />
                <x-input col="3" name="pos_payment" />
                <x-input col="3" name="pos_change" />
                <x-select col="3" name="pos_payment_method" :options="['cash' => 'Cash', 'qris' => 'QRIS', 'card' => 'Card']" />
                <x-textarea col="6" name="pos_keterangan" />
            @endbind
        </x-card>

        <x-action :model="$model" :action="['save']"/>
    </x-form>
</x-layouts::app>
