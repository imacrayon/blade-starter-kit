<x-layouts.app title="{{ $user->name }} {{ __('Team Settings') }}">
    <x-headbar title="{{ $user->name }} {{ __('Team Settings') }}" />
    <x-form method="put" action="{{ route('teams.memberships.update', [$team, $user]) }}" class="mt-6 max-w-lg space-y-6">
        <div class="grid grid-cols-1 gap-y-4">
        <fieldset>
            <x-field>
            <x-legend :value="__('Team role')" />
            <x-error for="role" />
            </x-field>
            <x-field class="mt-3">
                @foreach(App\UserRole::cases() as $role)
                    <x-radio :label="$role->label()" :description="$role->description()" id="role_{{ $user->id }}_{{ $loop->index }}" name="role" value="{{ $role->value }}" :checked="$role === $user->membership->role" autofocus />
                @endforeach
            </x-field>
        </fieldset>
        </div>
        <div class="flex gap-3">
            <x-button variant="primary">Update</x-button>
            <x-button href="{{ route('teams.show', $team) }}">Cancel</x-button>
            <x-spacer />
            <x-button form="delete_user" variant="danger">{{ __('Remove User') }}</x-button>
        </div>
    </x-form>
    <x-form id="delete_user" method="delete" action="{{ route('teams.memberships.destroy', [$team, $user]) }}" onsubmit="return confirm('{{ __(':user will be removed from this team', ['user' => $user->name]) }}')" />
</x-layouts.app>
