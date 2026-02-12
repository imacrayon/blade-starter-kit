<x-layouts.auth :title="__('Verification required')">
<div class="mt-4 space-y-6">
    <x-text class="text-center">
        {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
    </x-text>

    @if (session('status') == 'verification-link-sent')
        <x-text class="text-center font-medium !dark:text-green-400 !text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </x-text>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <x-form method="post" action="{{ route('verification.send') }}">
            <x-button variant="primary" class="w-full">
                {{ __('Resend verification email') }}
            </x-button>
        </x-form>
        <x-form method="post" action="{{ route('logout') }}">
            <x-button variant="link">{{ __('Log out') }}</x-button>
        </x-form>
    </div>
</div>
</x-layouts.auth>
