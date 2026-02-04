<x-layouts.auth :title="__('Forgot password')">
 <div class="space-y-6">
    <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" action="{{ route('password.email') }}" class="space-y-6">
        <!-- Email Address -->
        <x-input
            type="email"
            :label="__('Email address')"
            name="email"
            required
            autofocus
        />

        <x-button variant="primary" class="w-full">{{ __('Email password reset link') }}</x-button>
    </x-form>

    <div class="text-center text-sm text-gray-600 dark:text-gray-400">
        Or, return to
        <x-link href="{{ route('login') }}">log in</x-link>
    </div>
</div>
</x-layouts.auth>
