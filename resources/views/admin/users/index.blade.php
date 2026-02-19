<x-layouts.app :title="__('Users')">
    <x-headbar :title="__('Users')" />
    <x-section class="mt-6">
    <form x-target.replace="users" x-on:input.debounce="$el.requestSubmit()" class="flex gap-3">
        <x-label for="q" :value="__('Search')" class="sr-only" />
        <x-input name="q" placeholder="Search by name or email" autocomplete="off" />
        <x-button x-show="false">Submit</x-button>
    </form>
    <div id="users">
        @if($users->isNotEmpty())
            <x-table>
                <x-slot:head>
                    <x-table.row>
                        <x-table.col>{{ __('Name') }}</x-table.col>
                        <x-table.col>{{ __('Email') }}</x-table.col>
                        <x-table.col>{{ __('Role') }}</x-table.col>
                        <x-table.col class="text-right">{{ __('Teams') }}</x-table.col>
                        <x-table.col>{{ __('Joined') }}</x-table.col>
                        <x-table.col class="text-right">{{ __('Actions') }}</x-table.col>
                    </x-table.row>
                </x-slot:head>
                <x-slot:body>
                    @foreach($users as $user)
                        <x-table.row>
                            <x-table.cell>
                                <div class="flex gap-2 items-center whitespace-nowrap">
                                    <img width="28" height="28" role="presentation" class="flex-none rounded-full bg-gray-50" src="{{ $user->avatar }}" alt="">
                                    <x-link id="user_{{ $user->id }}_name" href="{{ route('admin.users.edit', $user) }}" class="font-semibold">{{ $user->name }}</x-link>
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                <x-link href="mailto:{{ $user->email }}" class="truncate">{{ $user->email }}</x-link>
                            </x-table.cell>
                            <x-table.cell>
                                {{ $user->role->label() }}
                            </x-table.cell>
                            <x-table.cell class="text-right">
                                {{ $user->teams_count }}
                            </x-table.cell>
                            <x-table.cell>
                                <x-time :datetime="$user->created_at" />
                            </x-table.cell>
                            <x-table.cell>
                                <div class="flex justify-end gap-3">
                                    <x-button icon size="xs" type="button" commandfor="user_{{ $user->id }}_actions" command="toggle-popover">
                                        <span class="sr-only">Open options</span>
                                        <x-phosphor-dots-three-vertical width="20" height="20" class="text-gray-500" />
                                    </x-button>
                                    <x-popover id="user_{{ $user->id }}_actions">
                                        <x-popover.item href="{{ route('admin.users.edit', $user) }}" before="phosphor-pencil" aria-describedby="user_{{ $user->id }}_name">
                                            {{ __('Edit') }}
                                        </x-popover.item>
                                        @can('impersonate', $user)
                                            <x-form method="post" action="{{ route('admin.impersonation.store') }}" class="contents">
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <x-popover.item aria-describedby="user_{{ $user->id }}_name" before="phosphor-user-switch">
                                                    {{ __('Impersonate') }}
                                                </x-popover.item>
                                            </x-form>
                                        @endif
                                        <x-form class="contents" x-target="users" onsubmit="return confirm('This user will be deleted.')" method="delete" action="{{ route('admin.users.destroy', $user) }}">
                                            <x-popover.item before="phosphor-trash">
                                                {{ __('Delete') }}
                                            </x-popover.item>
                                        </x-form>
                                    </x-popover>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-slot:body>
            </x-table>
        @endif
        <x-pagination class="mt-1" :paginator="$users" />
    </div>
</x-section>
</x-layouts.app>
