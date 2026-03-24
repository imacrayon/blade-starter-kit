<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="flex flex-col min-h-screen bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
        <header class="z-10 border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
            <x-container class="flex items-center max-lg:py-3">
                <x-button type="button" command="show-modal" commandfor="mobile_nav" icon size="xs" before="phosphor-list" class="lg:hidden me-3">
                    <span class="sr-only">{{ __('Toggle navigation') }}</span>
                </x-button>

                <a href="{{ route('app') }}">
                    <x-app-logo width="24" height="24" />
                    <span class="sr-only">{{ config('app.name') }}</span>
                </a>

                <x-navbar class="max-lg:hidden">
                    <button type="button" commandfor="header_team_menu" command="toggle-popover" class="flex items-center ps-3 ms-3 h-10 w-full rounded-lg text-gray-500 cursor-default hover:bg-gray-800/5 hover:text-gray-800 lg:h-8 dark:text-white/80 dark:hover:bg-white/7 dark:hover:text-white">
                        <span class="text-sm font-medium leading-none">
                            {{ auth()->user()->team->name }}
                        </span>
                        <span class="shrink-0 ml-auto size-8 flex justify-center items-center">
                            <x-phosphor-caret-up-down aria-hidden="true" width="12" height="12" class="text-gray-400 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white" />
                        </span>
                    </button>
                    <x-popover id="header_team_menu" justify="left" class="w-max">
                        <x-form method="put" action="{{ route('settings.team.update') }}" class="grid grid-cols-[auto_1fr]">
                            @foreach(auth()->user()->teams as $team)
                                <x-popover.item class="col-span-2 grid grid-cols-subgrid" :before="$team->id === auth()->user()->team_id ? 'phosphor-check' : ''" name="team_id" value="{{ $team->id }}">{{ $team->name }}</x-popover.item>
                            @endforeach
                        </x-form>
                        <x-popover.separator />
                        <x-popover.item before="phosphor-plus" href="{{ route('teams.create') }}">
                            {{ __('New Team') }}
                        </x-popover.item>
                    </x-popover>
                </x-navbar>

                <x-spacer />

                <!-- Desktop User Menu -->
                <button type="button" commandfor="header_user_menu" command="toggle-popover" class="flex rounded-full ring-2 ring-transparent hover:ring-gray-300 dark:hover:ring-gray-700">
                    <img width="24" height="24" src="{{ auth()->user()->avatar }}" alt="" class="flex-none rounded-full bg-gray-50">
                </button>
                <x-popover id="header_user_menu" align="top" justify="right" class="w-max">
                    <x-popover.item before="phosphor-gear-fine" href="{{ route('settings.profile.edit') }}">{{ __('Settings') }}</x-popover.item>
                    @can('admin')
                        <x-popover.separator />
                        <x-popover.item href="{{ route('admin.users.index') }}" before="phosphor-user-list">{{ __('Users') }}</x-popover.item>
                        <x-popover.item href="{{ route('admin.teams.index') }}" before="phosphor-users-three">{{ __('Teams') }}</x-popover.item>
                    @endcan
                    <x-popover.separator />
                    <x-form method="post" action="{{ route('logout') }}" class="w-full flex">
                        <x-popover.item before="phosphor-sign-out">{{ __('Log Out') }}</x-popover.item>
                    </x-form>
                </x-popover>
            </x-container>
            <x-container class="flex items-center max-lg:hidden">
                <x-navbar>
                    <x-navbar.item href="{{ route('app') }}">
                        {{ __('Dashboard') }}
                    </x-navbar.item>
                    <x-navbar.item href="{{ route('teams.show', auth()->user()->team) }}">
                        {{ __('Members') }}
                    </x-navbar.item>
                    <x-navbar.item href="{{ route('teams.edit', auth()->user()->team) }}">
                        {{ __('Settings') }}
                    </x-navbar.item>
                </x-navbar>
            </x-container>
            <x-modal id="mobile_nav" class="mt-2 mx-2 h-full w-[calc(100%_-_var(--spacing-4))] max-w-md">
                <div class="h-full flex flex-col gap-4">
                    <x-app-logo width="24" height="24" class="-mt-2" />

                    <x-navlist>
                        <x-navlist.group>
                            <button type="button" commandfor="header_mobile_team_menu" command="toggle-popover" class="flex pl-3 h-10 w-full items-center rounded-lg text-gray-500 cursor-default hover:bg-gray-800/5 hover:text-gray-800 lg:h-8 dark:text-white/80 dark:hover:bg-white/7 dark:hover:text-white">
                                <span class="text-sm font-medium leading-none">
                                    {{ auth()->user()->team->name }}
                                </span>
                                <span class="shrink-0 ml-auto size-8 flex justify-center items-center">
                                    <x-phosphor-caret-up-down aria-hidden="true" width="12" height="12" class="text-gray-400 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white" />
                                </span>
                            </button>
                            <x-popover id="header_mobile_team_menu" justify="left" class="w-max">
                                <x-form method="put" action="{{ route('settings.team.update') }}" class="grid grid-cols-[auto_1fr]">
                                    @foreach(auth()->user()->teams as $team)
                                        <x-popover.item class="col-span-2 grid grid-cols-subgrid" :before="$team->id === auth()->user()->team_id ? 'phosphor-check' : ''" name="team_id" value="{{ $team->id }}">{{ $team->name }}</x-popover.item>
                                    @endforeach
                                </x-form>
                                <x-popover.separator />
                                <x-popover.item before="phosphor-plus" href="{{ route('teams.create') }}">
                                    {{ __('New Team') }}
                                </x-popover.item>
                            </x-popover>
                            <x-navlist.item before="phosphor-file-text" href="{{ route('app') }}">
                                {{ __('Dashboard') }}
                            </x-navlist.item>
                            <x-navlist.item before="phosphor-user-list" href="{{ route('teams.show', auth()->user()->team) }}">
                                {{ __('Members') }}
                            </x-navlist.item>
                            <x-navlist.item before="phosphor-gear-fine" href="{{ route('teams.edit', auth()->user()->team) }}">
                                {{ __('Settings') }}
                            </x-navlist.item>
                        </x-navlist.group>
                    </x-navlist>

                    <x-spacer />

                    @can('admin')
                        <x-navlist>
                            <x-navlist.group :heading="__('Admin')">
                                <x-navlist.item href="{{ route('admin.users.index') }}" before="phosphor-user">{{ __('Users') }}</x-navlist.item>
                                <x-navlist.item href="{{ route('admin.teams.index') }}" before="phosphor-users-three">{{ __('Teams') }}</x-navlist.item>
                            </x-navlist.group>
                        </x-navlist>
                    @endcan
                </div>
            </x-modal>
        </header>
        <main class="flex-1 flex flex-col">
            <x-container class="flex-1 flex flex-col py-6 lg:py-8">
                {{ $slot }}
            </x-container>
        </main>
    </body>
</html>
