<x-layouts.app :title="__('Two-Factor Authentication | Settings')">
    <x-settings.layout :heading="__('Two-Factor Authentication')" :subheading="__('Manage your two-factor authentication settings')">
        @if($user->hasEnabledTwoFactorAuthentication())
            <div class="space-y-4">
                <x-badge color="green">{{ __('Enabled') }}</x-badge>
                <x-text>{{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}</x-text>
                <x-section>
                    <x-heading level="2" id="two_factor_confirmed" tabindex="-1">{{ __('2FA Recovery Codes') }}</x-heading>
                    <x-card class="space-y-4">
                        <x-text>{{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}</x-text>
                        <details {{ session('status') === Laravel\Fortify\Fortify::RECOVERY_CODES_GENERATED ? 'open' : '' }} class="group">
                            <x-button variant="primary" as="summary" class="w-full">
                                <x-slot:before>
                                    <x-phosphor-eye aria-hidden="true" width="20" height="20" class="block group-open:hidden shrink-0 opacity-80 group-hover:opacity-90 -mr-0.5" />
                                    <x-phosphor-eye-closed aria-hidden="true" width="20" height="20" class="hidden group-open:block shrink-0 opacity-80 group-hover:opacity-90 -mr-0.5" />
                                </x-slot:before>
                                <span class="block group-open:hidden">{{ __('View Recovery codes') }}</span>
                                <span class="hidden group-open:block">{{ __('Hide Recovery codes') }}</span>
                            </x-button>
                            <div class="space-y-4">
                                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                                    @foreach ($user->recoveryCodes() as $code)
                                        <div>{{ $code }}</div>
                                    @endforeach
                                </div>
                                <x-text>{{ __('Each recovery code can be used once to access your account and will be removed after use. If you need more, click Regenerate Codes below.') }}</x-text>
                                <x-form method="post" action="{{ route('two-factor.regenerate-recovery-codes') }}">
                                    <x-button class="w-full" before="phosphor-arrows-clockwise">{{ __('Regenerate Codes') }}</x-button>
                                </x-form>
                            </div>
                        </details>
                    </x-card>
                </x-section>
                <x-form method="delete" action="{{ route('two-factor.disable') }}" onsubmit="return confirm('{{ __('Two-factor authentication will be disabled for this account?') }}')">
                    <x-button variant="danger" before="phosphor-shield-warning-fill">{{ __('Disable 2FA') }}</x-button>
                </x-form>
            </div>
        @else
            @if (session('status') == 'two-factor-authentication-enabled' || $errors->has('code'))
                <x-heading level="3">{{ __('Enable Two-Factor Authentication') }}</x-heading>
                <x-subheading>{{ __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.') }}</x-subheading>
                <div class="mt-5 space-y-5">
                    <div class="relative overflow-hidden border rounded-lg max-w-64 flex min-w-0 items-center justify-center aspect-square border-gray-200 dark:border-gray-700">
                        <div class="dark:invert-100 dark:brightness-150 p-2 [&>svg]:max-w-full">{!! $user->twoFactorQrCodeSvg() !!}</div>
                    </div>
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p><span class="font-medium">{{ __('Setup key') }}</span>: <span class="font-mono">{{ decrypt($user->two_factor_secret) }}</span></p>
                    </div>
                    <div>
                        <x-form method="post" action="{{ route('two-factor.confirm') }}">
                            <x-input label="{{ __('Enter the 6-digit code from your authenticator app') }}" id="code" type="text" name="code" class="max-w-[16ch]" inputmode="numeric" autofocus autocomplete="one-time-code" />
                            <x-button variant="primary" class="mt-6">{{ __('Confirm') }}</x-button>
                        </x-form>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <x-badge color="red">{{ __('Disabled') }}</x-badge>
                    <x-text>{{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}</x-text>
                    <x-form method="post" action="{{ route('two-factor.enable') }}">
                        <x-button variant="primary" class="mt-6" before="phosphor-shield-check-fill">{{ __('Enable 2FA') }}</x-button>
                    </x-form>
                </div>
            @endif
        @endif
    </x-settings.layout>
</x-layouts.app>
