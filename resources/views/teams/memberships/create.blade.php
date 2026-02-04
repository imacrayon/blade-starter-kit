<x-layouts.auth :title="__('Team Invitation')">
<div class="space-y-6">
    <x-auth-header :title="__('Team Invitation')" :description="__('You have been invited to join :team', ['team' => $invitation->team->name])" />
    <x-section>
        <x-card>
            @if ($invitation->sender)
                <x-text class="text-center">{{ __('You were invited by :user', ['user' => $invitation->sender->name]) }}</x-text>
            @endif
            <x-form method="post" action="{{ route('teams.memberships.store', $invitation) }}" class="mt-4 flex justify-center gap-3">
                <x-button variant="primary">{{ __('Join') }}</x-button>
                <x-button href="{{ route('app') }}">{{ __('Nevermind') }}</x-button>
            </x-form>
        </x-card>
    </x-section>
</div>
</x-layouts.auth>
