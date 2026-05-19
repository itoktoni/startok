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
            <div class="alert alert-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
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
