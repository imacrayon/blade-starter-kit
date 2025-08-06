<x-layouts.app :title="__('Invitations')">
    <x-headbar :title="__('Invitations')" />
    <x-section class="mt-6">
        <x-heading level="2">{{ __('New Invitation') }}</x-heading>
        <x-card>
            <x-form method="post" action="{{ route('teams.invitations.store', $team) }}" autocomplete="off" class="max-w-lg space-y-6">
                <x-input :label="__('Email')" name="email" type="email" required />

                <x-select :label="__('Role')" name="role" :options="App\UserRole::cases()" :value="$invitation->role" required />

                <div class="flex items-center gap-3">
                    <x-button>{{ __('Save') }}</x-button>
                    <x-button variant="secondary" href="{{ route('teams.invitations.index', $team) }}">{{ __('Cancel') }}</x-button>
                </div>
            </x-form>
        </x-card>
    </x-section>
    <x-section id="invitations" class="mt-6">
        <x-heading level="2">{{ __('Pending Invitations') }}</x-heading>
        @if($invitations->isNotEmpty())
            <x-card class="p-0">
                    <ul>
                        @foreach($invitations as $invitation)
                            <li class="flex justify-between gap-x-4 p-3">
                                <div class="flex items-center min-w-0 gap-x-2 pl-1">
                                    <img width="32" height="32" class="flex-none rounded-full bg-gray-50" src="{{ $invitation->avatar }}" alt="">
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $invitation->name }}
                                        </p>
                                        <p class="flex text-xs text-gray-600">
                                            <a href="mailto:{{ $invitation->email }}" class="truncate hover:underline">{{ $invitation->email }}</a>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-x-6">
                                    <div class="hidden sm:flex sm:flex-col sm:items-end">
                                        <p class="text-sm text-gray-900">
                                            {{ $invitation->role->label() }}
                                            @if($invitation->role !== \App\UserRole::ADMIN)
                                                &middot; {{ Str::plural("{$invitation->districts_count} ".__('district'), $invitation->districts_count) }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-600">Sent <x-time :datetime="$invitation->updated_at" /></p>
                                    </div>
                                    <x-popover>
                                        <x-button type="button" variant="secondary" class="px-0 size-10">
                                            <span class="sr-only">Open options</span>
                                            <x-phosphor-dots-three-vertical width="20" height="20" class="text-gray-500" />
                                        </x-button>
                                        <x-slot:menu>
                                            <x-form x-target="invitations" method="post" action="{{ route('invitations.resend', $invitation) }}">
                                                <x-popover.item>Resend</x-popover.item>
                                            </x-form>
                                            <x-popover.separator />
                                            <div class="px-2 pb-2">
                                                <label for="invite_{{ $invitation->id }}_link" class="font-medium text-xs leading-none">{{ __('Invite link') }}</label>
                                                <div class="flex gap-1">
                                                    <input id="invite_{{ $invitation->id }}_link" readonly value="{{ $invitation->url() }}" class="text-gray-600 text-xs px-1 text-gray-600 bg-gray-50 rounded-md border border-gray-200">
                                                    <x-popover.item type="button" class="shrink-0 !w-auto"
                                                        x-data="{
                                                            copied: false,
                                                            copy() {
                                                                window.navigator.clipboard.writeText('{{ $invitation->url() }}')
                                                                this.copied = true
                                                                setTimeout(() => this.copied = false, 6000)
                                                            }
                                                        }"
                                                        x-on:click="copy"
                                                        x-text="copied ? 'Copied' : 'Copy'"
                                                    ></x-popover.item>
                                                </div>
                                            </div>
                                            <x-popover.separator />
                                            <x-form x-target="invitations" onsubmit="return confirm('This invitation will be deleted.')" method="delete" action="{{ route('invitations.destroy', $invitation) }}">
                                                <x-popover.item>Delete</x-popover.item>
                                            </x-form>
                                        </x-slot:menu>
                                    </x-popover>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @else
                <x-card>
                    <x-text>{{ __('No pending invitations found.') }}</x-text>
                </x-card>
            @endif
    </x-section>
</x-layouts.app>
