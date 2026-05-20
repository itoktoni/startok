<x-layouts::app>
    <x-breadcrumb :items="[['url' => route('dashboard'), 'label' => 'Dashboard'], ['url' => '', 'label' => 'Profile']]" />

    <x-form :action="route('profile.update')" method="POST">
        <x-card :label="__('Profile Information')">
            @bind($user ?? null)
                <x-input col="6" name="name" :label="__('Name')" />
                <x-input col="6" name="email" type="email" :label="__('Email')" />
            @endbind
        </x-card>

        <x-action>
            <x-button type="submit">{{ __('Save') }}</x-button>
        </x-action>
    </x-form>

    @if ($user && !$user->hasVerifiedEmail())
        <div class="col-span-12 md:col-span-12">
            <div class="alert alert-outline">
                <span>
                    {{ __('Your email address is unverified.') }}
                    <form action="{{ route('verification.send') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="link link-primary text-sm">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </form>
                </span>
            </div>
        </div>
    @endif

</x-layouts::app>
