<x-layouts.app :title="__('Profile | Settings')">
    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <x-form method="put" action="{{ route('settings.profile.update') }}" class="my-6 w-full space-y-6">
            <x-input type="text" :label="__('Name')" :value="$user->name" name="name" required autofocus autocomplete="name" />

            <div>
                <x-input type="email" :label="__('Email')" :value="$user->email" name="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <x-text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <x-button variant="link" :formaction="route('verification.store')" name="_method" value="post">
                                {{ __('Click here to re-send the verification email.') }}
                            </x-button>
                        </x-text>

                        @if (session('status') === 'verification-link-sent')
                            <x-text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </x-text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <x-button variant="primary" class="w-full">{{ __('Save') }}</x-button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </x-form>

        <section class="mt-10 space-y-6">
            <div class="relative mb-5">
                <x-heading size="lg">{{ __('Delete account') }}</x-heading>
                <x-subheading size="lg">{{ __('Delete your account and all of its resources') }}</x-subheading>
            </div>

            <x-button type="button" variant="danger" x-init="" x-on:click="$dispatch('modal:open', 'confirm_user_deletion')">
                {{ __('Delete account') }}
            </x-button>

            <x-modal id="confirm_user_deletion" :open="$errors->has('password')">
                <x-form method="delete" action="{{ route('settings.profile.destroy') }}" class="space-y-6">
                    <div>
                        <x-heading size="lg">{{ __('Are you sure you want to delete your account?') }}</x-heading>
                        <x-subheading>
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </x-subheading>
                    </div>

                    <x-input type="password" :label="__('Password')" name="password" />

                    <div class="flex justify-end space-x-2">
                        <x-button form="confirm_user_deletion_close">{{ __('Cancel') }}</x-button>
                        <x-button variant="danger">{{ __('Delete account') }}</x-button>
                    </div>
                </x-form>
            </x-modal>
        </section>
    </x-settings.layout>
</x-layouts.app>
