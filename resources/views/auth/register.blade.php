<x-layouts.auth :title="__('Sign up')">
<div class="space-y-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    @if ($invitation)
        <p class="rounded-lg bg-blue-50 px-4 py-3 text-center text-sm font-medium text-blue-800 dark:bg-blue-950/50 dark:text-blue-200">{{ __('Sign up to join :team.', ['team' => $invitation->team->name]) }}</p>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <x-form method="post" :action="route('register')" class="space-y-6">
        <input type="hidden" name="code" value="{{ $invitation?->code }}" />

        <x-input :label="__('First name')" name="first_name" required autofocus autocomplete="given-name" />

        <x-input :label="__('Last name')" name="last_name" required autocomplete="family-name" />

        <x-input type="email" :label="__('Email address')" name="email" required autocomplete="email" />

        <x-input type="password" :label="__('Password')" :description="\App\Field::passwordRequirementsText()" name="password" required autocomplete="new-password" :passwordrules="\App\Field::passwordRules()" />

        <x-input type="password" :label="__('Confirm password')" name="password_confirmation" required autocomplete="new-password" :passwordrules="\App\Field::passwordRules()" />

        <x-button variant="primary" class="w-full">{{ __('Create account') }}</x-button>
    </x-form>

    <div class="space-x-1 text-center text-sm text-gray-600 dark:text-gray-400">
        {{ __('Already have an account?') }}
        <x-link href="{{ route('login', array_filter(['code' => $invitation?->code])) }}">{{ __('Log in') }}</x-link>
    </div>
</div>
</x-layouts.auth>
