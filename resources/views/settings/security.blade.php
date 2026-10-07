<x-layouts.app :title="__('Security | Settings')">
    <x-settings.layout>
      <x-panel>
          <div data-heading>
            <x-heading level="2">{{ __('Update password') }}</x-heading>
            <x-subheading>{{ __('Ensure your account is using a long, random password to stay secure') }}</x-subheading>
          </div>
          <x-card>
            <x-form method="put" action="{{ route('settings.security.update') }}" class="space-y-4">
                <x-input
                    type="password"
                    name="current_password"
                    :label="__('Current password')"
                    required
                    autocomplete="current-password"
                />
                <x-input
                    type="password"
                    name="password"
                    :label="__('New password')"
                    :description="\App\Field::passwordRequirementsText()"
                    required
                    autocomplete="new-password"
                    :passwordrules="\App\Field::passwordRules()"
                />
                <x-input
                    type="password"
                    name="password_confirmation"
                    :label="__('Confirm Password')"
                    required
                    autocomplete="new-password"
                    :passwordrules="\App\Field::passwordRules()"
                />
                <div class="mt-6 flex items-center gap-3">
                  <x-button variant="primary">{{ __('Save') }}</x-button>
                  <x-action-message on="password-updated">{{ __('Password updated.') }}</x-action-message>
                </div>
            </x-form>
          </x-card>
        </x-panel>
        @if (Laravel\Fortify\Features::canManagePasskeys())
        <x-panel class="mt-8">
          <div data-heading>
            <x-heading level="2">{{ __('Passkeys') }}</x-heading>
            <x-subheading>{{ __('Sign in without a password using Face ID, Touch ID, Windows Hello, or a security key') }}</x-subheading>
          </div>
          <x-card id="passkeys" class="space-y-4">
            <x-action-message on="passkey-deleted">{{ __('Passkey removed.') }}</x-action-message>
            @if ($user->passkeys->isNotEmpty())
              <x-card class="p-0 divide-y divide-gray-200 dark:divide-gray-800">
                @foreach ($user->passkeys as $passkey)
                  <div class="flex items-center justify-between gap-3 px-4 py-3">
                    <div class="min-w-0">
                      <div class="font-medium truncate text-gray-800 dark:text-white">{{ $passkey->name }}</div>
                      <x-text size="sm">
                        {{ $passkey->authenticator ?? __('Passkey') }} &middot; {{ __('Added :date', ['date' => $passkey->created_at->diffForHumans()]) }}
                        @if ($passkey->last_used_at)
                          &middot; {{ __('Last used :date', ['date' => $passkey->last_used_at->diffForHumans()]) }}
                        @endif
                      </x-text>
                    </div>
                    <x-form x-target="passkeys" method="delete" action="{{ route('passkey.destroy', $passkey) }}" :confirm="__('This passkey will be removed.')">
                      <x-button variant="danger" size="sm" before="phosphor-trash">{{ __('Remove') }}</x-button>
                    </x-form>
                  </div>
                @endforeach
              </x-card>
            @else
              <x-text>{{ __("You haven't added any passkeys yet.") }}</x-text>
            @endif
            <div x-data="passkeys">
              <form class="space-y-4" x-show="supported" x-on:submit.prevent="register(new FormData($el).get('passkey_name'))">
                <x-input
                    name="passkey_name"
                    :label="__('Passkey name')"
                    :description="__('A label to help you recognize this device later')"
                    placeholder="e.g. MacBook Pro"
                    required
                    maxlength="255"
                    autocomplete="off"
                    x-init="$el.value = deviceName()"
                />
                <p role="alert" class="text-sm font-medium text-red-600 dark:text-red-400" x-show="error" x-text="error" style="display: none"></p>
                <x-button variant="primary" before="phosphor-fingerprint" x-bind:disabled="busy">{{ __('Add a passkey') }}</x-button>
              </form>
              <x-text x-show="! supported" style="display: none">{{ __('This browser does not support passkeys.') }}</x-text>
            </div>
          </x-card>
        </x-panel>
        @endif
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        <x-panel class="mt-8">
          <div data-heading>
            <x-heading level="2">{{ __('Two-Factor Authentication') }}</x-heading>
            <x-subheading>{{ __('Add additional security to your account using two-factor authentication') }}</x-subheading>
          </div>
          <x-card class="space-y-4">
            @if ($confirmingTwoFactor)
              <x-heading level="3">{{ __('Enable Two-Factor Authentication') }}</x-heading>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.') }}</p>
              <div class="relative overflow-hidden border rounded-lg max-w-64 flex min-w-0 items-center justify-center aspect-square border-gray-200 bg-white">
                <div class="p-2 [&>svg]:max-w-full">{!! $user->twoFactorQrCodeSvg() !!}</div>
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-400"><span class="font-medium">{{ __('Setup key') }}</span>: <span class="font-mono">{{ decrypt($user->two_factor_secret) }}</span></p>
              @if (Laravel\Fortify\Fortify::confirmsTwoFactorAuthentication())
                <x-form method="post" action="{{ route('two-factor.confirm') }}" class="space-y-4">
                  <x-input name="code" :label="__('Enter the 6-digit code from your authenticator app')" bag="confirmTwoFactorAuthentication" class="max-w-[16ch]" inputmode="numeric" autofocus autocomplete="one-time-code" />
                  <x-button variant="primary">{{ __('Confirm') }}</x-button>
                </x-form>
              @else
                <x-button variant="primary" href="{{ route('settings.security.edit') }}">{{ __('Done') }}</x-button>
              @endif
            @elseif ($user->hasEnabledTwoFactorAuthentication())
              <x-badge color="green">{{ __('Enabled') }}</x-badge>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}</p>
              <x-heading level="3">{{ __('Recovery Codes') }}</x-heading>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Recovery codes let you regain access if you lose your 2FA device. Each code can be used once. Store them in a secure password manager.') }}</p>
              <details {{ session('status') === Laravel\Fortify\Fortify::RECOVERY_CODES_GENERATED ? 'open' : '' }} class="group">
                <x-button variant="primary" as="summary" class="w-full">
                    <x-slot:before>
                        <x-phosphor-eye aria-hidden="true" width="20" height="20" class="block group-open:hidden shrink-0 opacity-80 group-hover:opacity-90 -mr-0.5" />
                        <x-phosphor-eye-closed aria-hidden="true" width="20" height="20" class="hidden group-open:block shrink-0 opacity-80 group-hover:opacity-90 -mr-0.5" />
                    </x-slot:before>
                    <span class="block group-open:hidden">{{ __('View Recovery codes') }}</span>
                    <span class="hidden group-open:block">{{ __('Hide Recovery codes') }}</span>
                </x-button>
                <div class="space-y-4 mt-4">
                  <div class="grid gap-1 max-w-xl px-4 py-4 font-mono text-sm bg-gray-100 dark:bg-white/5 dark:text-gray-200 rounded-lg">
                    @foreach ($user->recoveryCodes() as $code)
                      <div>{{ $code }}</div>
                    @endforeach
                  </div>
                  <x-form method="post" action="{{ route('two-factor.regenerate-recovery-codes') }}">
                    <x-button>{{ __('Regenerate Codes') }}</x-button>
                  </x-form>
                </div>
              </details>
              <x-form method="delete" action="{{ route('two-factor.disable') }}" :confirm="__('Two-factor authentication will be disabled for this account.')">
                <x-button variant="danger">{{ __('Disable 2FA') }}</x-button>
              </x-form>
            @else
              <x-badge color="red">{{ __('Disabled') }}</x-badge>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}</p>
              <x-form method="post" action="{{ route('two-factor.enable') }}">
                <input type="hidden" name="force" value="1">
                <x-button variant="primary">{{ __('Enable 2FA') }}</x-button>
              </x-form>
            @endif
          </x-card>
        </x-panel>
        @endif
    </x-settings.layout>
</x-layouts.app>
