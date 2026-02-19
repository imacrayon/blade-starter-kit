<x-layouts.app :title="__('Teams')">
    <x-headbar :title="__('Teams')">
        <x-button variant="primary" href="{{ route('teams.create') }}" before="phosphor-plus">{{ __('New') }}</x-button>
    </x-headbar>
    <x-section class="mt-6">
        <form x-target.replace="teams" x-on:input.debounce="$el.requestSubmit()" class="flex gap-3">
            <x-label for="q" :value="__('Search')" class="sr-only" />
            <x-input name="q" placeholder="Search by name" autocomplete="off" />
            <x-button x-show="false">Submit</x-button>
        </form>
        <div id="teams">
            @if($teams->isNotEmpty())
                <x-table>
                    <x-slot:head>
                        <x-table.row>
                            <x-table.col>{{ __('Name') }}</x-table.col>
                            <x-table.col class="text-right">{{ __('Users') }}</x-table.col>
                            <x-table.col>{{ __('Created') }}</x-table.col>
                            <x-table.col class="text-right">{{ __('Actions') }}</x-table.col>
                        </x-table.row>
                    </x-slot:head>
                    <x-slot:body>
                        @foreach($teams as $team)
                            <x-table.row>
                                <x-table.cell>
                                    <x-link id="team_{{ $team->id }}_name" href="{{ route('teams.show', $team) }}" class="font-semibold">{{ $team->name }}</x-link>
                                </x-table.cell>
                                <x-table.cell class="text-right">
                                    {{ $team->users_count }}
                                </x-table.cell>
                                <x-table.cell>
                                    <x-time :datetime="$team->created_at" />
                                </x-table.cell>
                                <x-table.cell>
                                    <div class="flex justify-end gap-3">
                                        <x-button icon size="xs" type="button" commandfor="team_{{ $team->id }}_actions" command="toggle-popover">
                                            <span class="sr-only">Open options</span>
                                            <x-phosphor-dots-three-vertical width="20" height="20" class="text-gray-500" />
                                        </x-button>
                                        <x-popover id="team_{{ $team->id }}_actions">
                                            <x-popover.item href="{{ route('teams.edit', $team) }}" before="phosphor-pencil" aria-describedby="team_{{ $team->id }}_name">
                                                {{ __('Edit') }}
                                            </x-popover.item>
                                            <x-form x-target="teams" onsubmit="return confirm('This team will be deleted.')" method="delete" action="{{ route('admin.teams.destroy', $team) }}" class="contents">
                                                <x-popover.item before="phosphor-trash" aria-describedby="team_{{ $team->id }}_name">
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
            <x-pagination class="mt-1" :paginator="$teams" />
        </div>
    </x-section>
</x-layouts.app>
