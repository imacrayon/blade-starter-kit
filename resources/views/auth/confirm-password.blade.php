<x-layouts.auth :title="__('Confirm password')">
<div class="space-y-6">
    <x-auth-header
        :title="__('Confirm password')"
        :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" action="{{ route('confirmation.store') }}" class="space-y-6">
        <!-- Password -->
        <x-input
            type="password"
            :label="__('Password')"
            name="password"
            required
            autocomplete="new-password"
        />

        <x-button variant="primary" class="w-full">{{ __('Confirm') }}</x-button>
    </x-form>
</div>
</x-layouts.auth>
