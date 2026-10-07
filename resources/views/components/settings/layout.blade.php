@blaze

<x-headbar :title="__('Settings')" :subtitle="__('Manage your profile and account settings')" />
<div class="w-full items-start md:flex md:mt-8">
    <div class="mr-10 w-full md:w-[220px]">
        <x-navlist variant="secondary">
            <x-navlist.item href="{{ route('settings.profile.edit') }}">{{ __('Profile') }}</x-navlist.item>
            <x-navlist.item href="{{ route('settings.security.edit') }}">{{ __('Security') }}</x-navlist.item>
            <x-navlist.item href="{{ route('settings.teams.index') }}">{{ __('Teams') }}</x-navlist.item>
            <x-navlist.item href="{{ route('settings.appearance.edit') }}">{{ __('Appearance') }}</x-navlist.item>
        </x-navlist>
    </div>

    <x-separator class="md:hidden" />

    <div {{ $attributes->class('flex-1 self-stretch max-md:pt-6 max-w-2xl') }}>
        {{ $slot }}
    </div>
</div>
