<x-layout>
    <x-form :model="$model">
        <x-card>
            <x-action form="form" />

            <div class="row">
                @bind($model)

                <x-form-input col="6" name="name" />
                <x-form-input col="6" name="email" />
                <x-form-input col="6" name="role" />
                <x-form-input col="6" name="two_factor_secret" />
                <x-form-input col="6" name="two_factor_recovery_codes" />
                <x-form-input col="6" name="two_factor_confirmed_at" />

                @endbind
            </div>

        </x-card>
    </x-form>
</x-layout>
