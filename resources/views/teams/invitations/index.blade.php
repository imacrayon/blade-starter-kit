<x-layouts.app :title="__('Invitations')">
    <x-headbar :title="__('Invitations')" />
    <x-section class="mt-6">
        <x-heading level="2">{{ __('New Invitation') }}</x-heading>
        <x-card>
            <x-form method="post" action="{{ route('teams.invitations.store', $team) }}" autocomplete="off" class="max-w-lg space-y-6">
                <x-input :label="__('Email')" name="email" type="email" required />

                <x-select :label="__('Role')" name="role" :options="App\UserRole::class" :value="$invitation->role" required />

                <div class="flex items-center gap-3">
                    <x-button variant="primary">{{ __('Save') }}</x-button>
                    <x-button href="{{ route('teams.show', $team) }}">{{ __('Cancel') }}</x-button>
                </div>
            </x-form>
        </x-card>
    </x-section>
    <x-section id="invitations" class="mt-6">
        <x-heading level="2">{{ __('Pending Invitations') }}</x-heading>
        @if($invitations->isNotEmpty())
            <x-table>
                <x-slot:head>
                    <x-table.row>
                        <x-table.col>{{ __('Name') }}</x-table.col>
                        <x-table.col>{{ __('Role') }}</x-table.col>
                        <x-table.col>{{ __('Sent') }}</x-table.col>
                        <x-table.col class="text-right">{{ __('Actions') }}</x-table.col>
                    </x-table.row>
                </x-slot:head>
                <x-slot:body>
                    @foreach($invitations as $invitation)
                        <x-table.row>
                            <x-table.cell>
                                <div class="flex gap-2 items-center whitespace-nowrap">
                                    <img width="28" height="28" role="presentation" class="flex-none rounded-full bg-gray-50" src="{{ $invitation->avatar }}" alt="">
                                    <x-link href="mailto:{{ $invitation->email }}" class="font-semibold">{{ $invitation->email }}</x-link>
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                {{ $invitation->role->label() }}
                            </x-table.cell>
                            <x-table.cell>
                                <x-time :datetime="$invitation->created_at" />
                            </x-table.cell>
                            <x-table.cell>
                                <div class="flex justify-end gap-3">
                                    <x-button icon size="xs" type="button" commandfor="invitation_{{ $invitation->id }}_actions" command="toggle-popover">
                                        <span class="sr-only">Open options</span>
                                        <x-phosphor-dots-three-vertical width="20" height="20" class="text-gray-500" />
                                    </x-button>
                                    <x-popover id="invitation_{{ $invitation->id }}_actions">
                                        <x-form x-target="invitations" method="post" action="{{ route('invitations.resend', $invitation) }}">
                                            <x-popover.item before="phosphor-arrows-clockwise">Resend</x-popover.item>
                                        </x-form>
                                        <x-popover.separator />
                                        <x-popover.group>
                                            <x-field>
                                                <x-label for="invite_{{ $invitation->id }}_link" class="font-medium text-xs leading-none" :value="__('Invite link')" />
                                                <div class="flex gap-1">
                                                    <x-input size="sm" name="url" id="invite_{{ $invitation->id }}_link" readonly value="{{ $invitation->url() }}" />
                                                    <x-button size="sm" type="button" class="shrink-0 !w-auto"
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
                                                    ></x-button>
                                                </div>
                                            </x-field>
                                        </x-popover.group>
                                        <x-popover.separator />
                                        <x-form x-target="invitations" onsubmit="return confirm('This invitation will be deleted.')" method="delete" action="{{ route('invitations.destroy', $invitation) }}">
                                            <x-popover.item before="phosphor-trash">Delete</x-popover.item>
                                        </x-form>
                                    </x-popover>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-slot:body>
            </x-table>
        @else
            <x-card>
                <x-text>{{ __('No pending invitations found.') }}</x-text>
            </x-card>
        @endif
    </x-section>
</x-layouts.app>
