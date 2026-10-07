@blaze

@props(['team'])

<x-form
    method="delete"
    :action="route('settings.teams.destroy', $team)"
    :confirm="$team->users_count === 1
        ? __(':team will be deleted because you are its last member.', ['team' => $team->name])
        : __('You will lose access to :team.', ['team' => $team->name])"
    {{ $attributes }}
>{{ $slot }}</x-form>
