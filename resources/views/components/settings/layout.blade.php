<x-headbar :title="__('Settings')" :subtitle="__('Manage your profile and account settings')" />
<div class="w-full items-start md:flex md:mt-6">
    <div class="mr-10 w-full py-4 md:w-[220px]">
        <x-navlist variant="secondary">
            <x-navlist.item href="{{ route('settings.profile.edit') }}" :current="request()->routeIs('settings.profile.edit')">{{ __('Profile') }}</x-navlist.item>
            <x-navlist.item href="{{ route('settings.password.edit') }}" :current="request()->routeIs('settings.password.edit')">{{ __('Password') }}</x-navlist.item>
            <x-navlist.item href="{{ route('settings.appearance.edit') }}" :current="request()->routeIs('settings.appearance.edit')">{{ __('Appearance') }}</x-navlist.item>
        </x-navlist>
    </div>

    <x-separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <x-heading size="lg">{{ $heading ?? '' }}</x-heading>
        <x-subheading size="lg">{{ $subheading ?? '' }}</x-subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
