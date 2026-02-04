<x-layouts.auth :title="__('Log in')">
<div class="space-y-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" :action="route('login')" class="space-y-6">
        <x-input
            type="email"
            :label="__('Email address')"
            name="email"
            required
            autofocus
            autocomplete="email"
        />

        <div class="relative">
            <x-input
                type="password"
                :label="__('Password')"
                name="password"
                required
                autocomplete="current-password"
            />

            @if (Route::has('password.request'))
                <x-link class="absolute right-0 top-0 text-sm" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </x-link>
            @endif
        </div>

        <x-checkbox name="remember" :label="__('Remember me')" />

        <x-button variant="primary" class="w-full">{{ __('Log in') }}</x-button>
    </x-form>

    @if (Route::has('register'))
      <p class="text-center text-sm text-gray-600 dark:text-gray-400">
          <span>{{ __('Don\'t have an account?') }}</span>
          <x-link href="{{ route('register') }}">{{ __('Sign up') }}</x-link>
      </p>
    @endif
</div>
</x-layouts.auth>
