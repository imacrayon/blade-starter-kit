<x-layouts.app :title="__('Teams | Settings')">
    <x-settings.layout>
        <x-panel>
            <div data-heading>
                <x-heading level="2" size="lg">{{ __('Teams') }}</x-heading>
                <x-subheading level="2">{{ __('Teams you belong to. You must belong to at least one team.') }}</x-subheading>
                <x-error for="team" class="mt-2" />
            </div>
            <x-card class="space-y-3 divide-y divide-gray-200 dark:divide-white/10">
                <ul class="space-y-3 divide-y divide-gray-200 dark:divide-white/10">
                    @foreach($teams as $team)
                        <li class="pb-3 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 font-medium text-gray-900 dark:text-white">
                                    <span class="truncate">{{ $team->name }}</span>
                                    @if($team->is($user->team))
                                        <x-badge size="sm" color="blue">{{ __('Current') }}</x-badge>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">{{ $team->membership->role->label() }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                @can('update', $team)
                                    <x-link href="{{ route('teams.edit', $team) }}" class="text-sm">{{ __('Manage') }}</x-link>
                                @endcan
                                @can('leave', $team)
                                    <x-leave-team-form :team="$team"><x-button variant="link" class="text-sm text-gray-600 hover:text-red-600">{{ __('Leave') }}</x-button></x-leave-team-form>
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>
                <x-button href="{{ route('teams.create') }}" before="phosphor-plus" class="w-full rounded-xl border-2 border-dashed border-gray-200 p-6 text-center">{{ __('New team') }}</x-button>
            </x-card>
        </x-panel>
    </x-settings.layout>
</x-layouts.app>
