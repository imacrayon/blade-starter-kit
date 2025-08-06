<x-layouts.auth :title="__('Team Invitation')">
<div class="space-y-6">
    <x-auth-header :title="__('Team Invitation')" :description="__('You have been invited to join :team', ['team' => $invitation->team->name])" />
    <x-section>
        <x-card class="p-1">
            <x-navlist class="divide-y">
                <x-navlist.item href="{{ route('register', ['code' => $invitation->code]) }}" after="phosphor-caret-right">{{ __('Sign up for a new account') }}</x-navlist.item>
                <x-separator class="my-1" />
                <x-navlist.item href="{{ route('teams.memberships.create', $invitation) }}" after="phosphor-caret-right">{{ __('Log in to an existing account') }}</x-navlist.item>
            </x-navlist>
        </x-card>
    </x-section>
</div>
</x-layouts.auth>
