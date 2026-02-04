<x-layouts.app title="{{ $team->name }} {{ __('Members') }}">
<x-headbar title="{{ $team->name }} {{ __('Members') }}">
    @can('update', $team)
        <x-button variant="primary" before="phosphor-user-plus" href="{{ route('teams.invitations.index', $team) }}">
            Invite
        </x-button>
    @endif
</x-headbar>
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
                        <x-table.col>{{ __('Role') }}</x-table.col>
                        @can('update', $team)
                            <x-table.col class="text-right">{{ __('Actions') }}</x-table.col>
                        @endcan
                    </x-table.row>
                </x-slot:head>
                <x-slot:body>
                    @foreach($users as $user)
                        <x-table.row>
                            <x-table.cell>
                                <div class="flex gap-2 items-center">
                                    <img width="28" height="28" role="presentation" class="flex-none rounded-full bg-gray-50" src="{{ $user->avatar }}" alt="">
                                    @can('update', $team)
                                        <x-link id="user_{{ $user->id }}_name" href="{{ route('teams.memberships.edit', [$team, $user]) }}" class="font-semibold">{{ $user->name }}</x-link>
                                    @else
                                        <span class="font-medium">{{ $user->name }}</span>
                                    @endcan
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                {{ App\UserRole::tryFrom($user->membership_role)?->label() }}
                            </x-table.cell>
                            @can('update', $team)
                                <x-table.cell>
                                    <div class="flex justify-end gap-3">
                                        <x-link href="{{ route('teams.memberships.edit', [$team, $user]) }}">{{ __('Edit') }}</x-link>
                                    </div>
                                </x-table.cell>
                            @endcan
                        </x-table.row>
                    @endforeach
                </x-slot:body>
            </x-table>
        @endif
        <x-pagination class="mt-1" :paginator="$users" />
    </div>
</x-section>
</x-layouts.app>
