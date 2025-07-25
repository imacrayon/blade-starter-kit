<x-layouts.auth :title="__('Sign up')">
<div class="space-y-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" :action="route('register')" class="space-y-6">
        <!-- Name -->
        <x-input
            type="text"
            :label="__('Full name')"
            name="name"
            required
            autofocus
            autocomplete="name"
        />

        <!-- Email Address -->
        <x-input
            type="email"
            :label="__('Email address')"
            name="email"
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

        <x-button class="w-full">{{ __('Create account') }}</x-button>
    </x-form>

    <div class="space-x-1 text-center text-sm text-gray-600 dark:text-gray-400">
        {{ __('Already have an account?') }}
        <x-link href="{{ route('login') }}">Log in</x-link>
    </div>
</div>
</x-layouts.auth>
