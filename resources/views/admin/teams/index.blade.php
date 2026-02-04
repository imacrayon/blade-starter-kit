<x-layouts.app :title="__('Teams')">
    <x-headbar :title="__('Teams')">
        <x-button href="{{ route('teams.create') }}" before="phosphor-plus">{{ __('New') }}</x-button>
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
                                        <a href="{{ route('teams.show', $team) }}" aria-describedby="team_{{ $team->id }}_name" title="View">
                                            <x-phosphor-eye width="20" height="20" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" aria-hidden="true" />
                                            <span class="sr-only">View</span>
                                        </a>
                                        <a href="{{ route('teams.edit', $team) }}" aria-describedby="team_{{ $team->id }}_name" title="Edit">
                                            <x-phosphor-pencil width="20" height="20" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" aria-hidden="true" />
                                            <span class="sr-only">Edit</span>
                                        </a>
                                        <x-form x-target="teams" onsubmit="return confirm('This team will be deleted.')" method="delete" action="{{ route('admin.teams.destroy', $team) }}" class="contents">
                                            <button title="Delete" aria-describedby="team_{{ $team->id }}_name">
                                                <x-phosphor-trash width="20" height="20" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400" aria-hidden="true" />
                                                <span class="sr-only">Delete</span>
                                            </button>
                                        </x-form>
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
