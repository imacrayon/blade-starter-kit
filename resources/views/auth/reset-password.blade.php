<x-layouts.auth :title="__('Reset password')">
<div class="space-y-6">
    <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" action="{{ route('password.update') }}" class="space-y-6">
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <!-- Email Address -->
        <x-input
            type="email"
            :label="__('Email')"
            name="email"
            :value="request('email')"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <x-input
            type="password"
            :label="__('Password')"
            name="password"
            required
            autocomplete="new-password"
        />

        <!-- Confirm Password -->
        <x-input
            type="password"
            :label="__('Confirm password')"
            name="password_confirmation"
            required
            autocomplete="new-password"
        />

        <x-button variant="primary" class="w-full">{{ __('Reset password') }}</x-button>
    </x-form>
</div>
</x-layouts.auth>
