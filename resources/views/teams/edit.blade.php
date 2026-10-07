<x-layouts.app :title="$team->name.' '.__('Settings')">
    <x-headbar :title="$team->name.' '.__('Settings')" />
    @can('update', $team)
        <x-form method="put" :action="route('teams.update')" class="mt-6 max-w-lg space-y-6">
            @include('teams._fields')
            <div class="flex gap-3">
                <x-button variant="primary">Save</x-button>
                <x-button href="{{ route('teams.members.index', $team) }}">Cancel</x-button>
            </div>
        </x-form>
    @endcan
    @can('leave', $team)
        <section class="mt-10 space-y-6">
            <div class="relative mb-5">
                <x-heading size="lg">{{ __('Leave Team') }}</x-heading>
                <x-subheading>{{ __('You will no longer have access to this team') }}</x-subheading>
            </div>
            <x-leave-team-form :team="$team" class="contents">
                <x-button variant="danger">{{ __('Leave Team') }}</x-button>
            </x-leave-team-form>
        </section>
    @endcan
    @can('destroy', $team)
        <section class="mt-10 space-y-6">
            <div class="relative mb-5">
                <x-heading size="lg">{{ __('Delete Team') }}</x-heading>
                <x-subheading>{{ __('Members will lose access and pending invitations will be cancelled') }}</x-subheading>
            </div>
            <x-form method="delete" action="{{ route('teams.destroy', $team) }}" class="max-w-lg space-y-6">
                <x-input name="team_name" :label="__('Type “:team” to confirm', ['team' => $team->name])" required autocomplete="off" />
                <x-button variant="danger">{{ __('Delete Team') }}</x-button>
            </x-form>
        </section>
    @endcan
</x-layouts.app>
