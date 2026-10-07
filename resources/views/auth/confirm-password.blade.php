<x-layouts.auth :title="__('Confirm password')">
<div class="space-y-6">
    <x-auth-header
        :title="__('Confirm password')"
        :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" action="{{ route('password.confirm') }}" class="space-y-6">
        <!-- Password -->
        <x-input
            type="password"
            :label="__('Password')"
            name="password"
            required
            autocomplete="current-password"
        />

        <x-button variant="primary" class="w-full">{{ __('Confirm') }}</x-button>
    </x-form>

    @if (auth()->user()->passkeys()->exists())
        <div class="space-y-6" x-data="passkeys(@js(['options' => route('passkey.confirm-options'), 'submit' => route('passkey.confirm')]))" x-show="supported">
            <x-separator />

            <div class="space-y-2">
                <x-button type="button" before="phosphor-fingerprint" class="w-full" x-on:click="verify" x-bind:disabled="busy">{{ __('Confirm with a passkey') }}</x-button>
                <p role="alert" class="text-sm font-medium text-red-600 dark:text-red-400" x-show="error" x-text="error" style="display: none"></p>
            </div>
        </div>
    @endif
</div>
</x-layouts.auth>
