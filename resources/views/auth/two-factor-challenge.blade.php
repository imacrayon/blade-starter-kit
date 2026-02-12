<x-layouts.auth :title="__('Authentication Code')">
<div class="space-y-6" x-data="{ showRecoveryInput: @js($errors->has('recovery_code')) }">
    <x-auth-header
        x-show="!showRecoveryInput"
        :title="__('Authentication Code')"
        :description="__('Enter the authentication code provided by your authenticator application.')"
    />

    <x-auth-header
        x-show="showRecoveryInput"
        :title="__('Recovery Code')"
        :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')"
    />

    <x-form method="post" action="{{ route('two-factor.login.store') }}">
        <div class="space-y-5 text-center">
            <x-field x-show="!showRecoveryInput">
                <x-label for="code" class="sr-only" :value="__('OTP Code')" />
                <x-error for="code" />
                <x-input inputmode="numeric" name="code" autofocus x-ref="code" x-bind:required="!showRecoveryInput" autocomplete="one-time-code" class="mx-auto max-w-[16ch]" placeholder="• • • • • •" />
            </x-field>

            <x-field x-show="showRecoveryInput">
                <x-label for="recovery_code" class="sr-only" :value="__('Recovery Code')" />
                <x-error for="recovery_code" />
                <x-input name="recovery_code" x-ref="recovery_code" x-bind:required="showRecoveryInput" autocomplete="one-time-code" />
            </x-field>

            <x-button variant="primary" class="mt-5 w-full">{{ __('Continue') }}</x-button>
        </div>

        <div class="mt-5 space-x-0.5 text-sm text-center">
            <span class="text-gray-500 dark:text-gray-400">{{ __('or you can') }}</span>
            <div class="inline font-medium underline cursor-pointer">
                <button class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 underline cursor-pointer" x-show="!showRecoveryInput" x-on:click="showRecoveryInput = true; $nextTick(() => { $refs.recovery_code.focus() })">{{ __('login using a recovery code') }}</button>
                <button class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 underline cursor-pointer" x-show="showRecoveryInput" x-on:click="showRecoveryInput = false; $nextTick(() => { $refs.code.focus() })">{{ __('login using an authentication code') }}</button>
            </div>
        </div>
    </x-form>
</div>
</x-layouts.auth>
