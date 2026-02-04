<x-layouts.app title="{{ $team->name }} {{ __('Settings') }}">
    <x-headbar title="{{ $team->name }} {{ __('Settings') }}" />
    @can('update', $team)
        <x-form method="put" :action="route('teams.update', $team)" class="mt-6 max-w-lg space-y-6">
            @include('teams._fields')
            <div class="flex gap-3">
                <x-button variant="primary">Save</x-button>
                <x-button type="button" onclick="history.back()">Cancel</x-button>
            </div>
        </x-form>
    @endcan
    <section class="mt-10 space-y-6">
        <div class="relative mb-5">
            <x-heading size="lg">{{ __('Leave Team') }}</x-heading>
            <x-subheading size="lg">{{ __('You will no longer have access to this team') }}</x-subheading>
        </div>
        <x-form method="delete" action="{{ route('teams.memberships.destroy', [$team, auth()->user()]) }}" onsubmit="return confirm('You are about to be removed from this team.')" class="contents">
            <x-button variant="danger">{{ __('Leave Team') }}</x-button>
        </x-form>
    </section>
</x-layouts.app>
