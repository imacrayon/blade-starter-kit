<x-layouts.auth :title="__('Log in')">
<div class="space-y-6" x-data="passkeys">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    @if ($invitation)
        <p class="rounded-lg bg-blue-50 px-4 py-3 text-center text-sm font-medium text-blue-800 dark:bg-blue-950/50 dark:text-blue-200">{{ __('Log in to join :team.', ['team' => $invitation->team->name]) }}</p>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" :action="route('login.store')" class="space-y-6">
        <x-input
            type="email"
            :label="__('Email address')"
            name="email"
            required
            autofocus
            autocomplete="email webauthn"
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

    <x-separator x-show="supported" />

    <div class="space-y-2" x-show="supported">
        <x-button type="button" before="phosphor-fingerprint" class="w-full" x-on:click="verify" x-bind:disabled="busy">{{ __('Log in with a passkey') }}</x-button>
        <p role="alert" class="text-sm font-medium text-red-600 dark:text-red-400" x-show="error" x-text="error" style="display: none"></p>
    </div>

    @if (Route::has('register'))
      <p class="text-center text-sm text-gray-600 dark:text-gray-400">
          <span>{{ __('Don\'t have an account?') }}</span>
          <x-link href="{{ route('register', array_filter(['code' => $invitation?->code])) }}">{{ __('Sign up') }}</x-link>
      </p>
    @endif
</div>
</x-layouts.auth>
