<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="layout sidebar min-h-screen bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
        <x-sidebar sticky stashable class="border-r border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
            <x-sidebar.toggle class="lg:hidden w-10 p-0">
                <x-phosphor-x aria-hidden="true" width="20" height="20" />
            </x-sidebar.toggle>

            <a href="{{ route('app') }}" class="mr-5 flex items-center space-x-2">
                <x-app-logo />
            </a>

            <x-navlist>
                <x-navlist.item before="phosphor-house-line" href="{{ route('app') }}" :current="request()->routeIs('app')">
                    {{ __('Dashboard') }}
                </x-navlist.item>
                <x-navlist.item before="phosphor-git-pull-request" href="https://github.com/imacrayon/blade-starter-kit" target="_blank">
                        {{ __('Repository') }}
                </x-navlist.item>
            </x-navlist>

            <x-separator />

            <x-navlist>
                <x-navlist.group>
                    <x-popover justify="left">
                        <button type="button" class="flex pl-3 h-10 w-full items-center rounded-lg text-gray-500 cursor-default hover:bg-gray-800/5 hover:text-gray-800 lg:h-8 dark:text-white/80 dark:hover:bg-white/7 dark:hover:text-white">
                            <span class="text-sm font-medium leading-none">
                                {{ auth()->user()->team->name }}
                            </span>
                            <span class="shrink-0 ml-auto size-8 flex justify-center items-center">
                                <x-phosphor-caret-up-down aria-hidden="true" width="12" height="12" class="text-gray-400 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white" />
                            </span>
                        </button>
                        <x-slot:menu class="w-max">
                            <x-form method="put" action="{{ route('settings.team.update') }}" class="grid grid-cols-[auto_1fr]">
                                @foreach(auth()->user()->teams as $team)
                                    <x-popover.item class="col-span-2 grid grid-cols-subgrid" :before="$team->id === auth()->user()->team_id ? 'phosphor-check' : ''" name="team_id" value="{{ $team->id }}">{{ $team->name }}</x-popover.item>
                                @endforeach
                            </x-form>
                            <x-popover.separator />
                            <x-popover.item before="phosphor-plus" href="{{ route('teams.create') }}" :current="request()->routeIs('teams.create')">
                                {{ __('New Team') }}
                            </x-popover.item>
                        </x-slot:menu>
                    </x-popover>
                    <x-navlist.item before="phosphor-user-list" href="{{ route('teams.show', auth()->user()->team) }}" :current="request()->routeIs('teams.show') && request()->route('team')->is(auth()->user()->team)">
                        {{ __('Members') }}
                    </x-navlist.item>
                    <x-navlist.item before="phosphor-gear-fine" href="{{ route('teams.edit', auth()->user()->team) }}" :current="request()->routeIs('teams.edit') && request()->route('team')->is(auth()->user()->team)">
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

            <x-popover align="bottom" justify="left">
                <button type="button" class="w-full group flex items-center rounded-lg p-1 hover:bg-gray-800/5 dark:hover:bg-white/10">
                    <img class="size-8 flex-none rounded-full bg-gray-50" src="{{ auth()->user()->avatar }}" alt="">
                    <span class="ml-2 text-sm text-gray-500 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white font-medium truncate">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="shrink-0 ml-auto size-8 flex justify-center items-center">
                        <x-phosphor-caret-up-down aria-hidden="true" width="16" height="16" class="text-gray-400 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white" />
                    </span>
                </button>
                <x-slot:menu class="w-max">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                        <img class="size-8 flex-none rounded-full bg-gray-50" src="{{ auth()->user()->avatar }}" alt="">
                        <div class="grid flex-1 text-left text-sm leading-tight">
                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <x-popover.separator />
                    <x-popover.item before="phosphor-gear-fine" href="{{ route('settings.profile.edit') }}">{{ __('Settings') }}</x-popover.item>
                    <x-popover.separator />
                    <x-form method="post" action="{{ route('logout') }}" class="w-full flex">
                        <x-popover.item before="phosphor-sign-out">{{ __('Log Out') }}</x-popover.item>
                    </x-form>
                </x-slot:menu>
            </x-popover>
        </x-sidebar>

        <!-- Mobile User Menu -->
        <x-header class="lg:hidden">
            <x-container class="min-h-14 flex items-center">
                <x-sidebar.toggle class="lg:hidden w-10 p-0">
                    <x-phosphor-list aria-hidden="true" width="20" height="20" />
                </x-sidebar.toggle>

                <x-spacer />

                <x-popover align="top" justify="right">
                    <button type="button" class="w-full group flex items-center rounded-lg p-1 hover:bg-gray-800/5 dark:hover:bg-white/10">
                        <img class="size-8 flex-none rounded-full bg-gray-50" src="{{ auth()->user()->avatar }}" alt="">
                        <span class="shrink-0 ml-auto size-8 flex justify-center items-center">
                            <x-phosphor-caret-down width="16" height="16" class="text-gray-400 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white" />
                        </span>
                    </button>
                    <x-slot:menu>
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <img class="size-8 flex-none rounded-full bg-gray-50" src="{{ auth()->user()->avatar }}" alt="">
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                        <x-popover.separator />
                        <x-popover.item before="phosphor-gear-fine" href="{{ route('settings.profile.edit') }}">{{ __('Settings') }}</x-popover.item>
                        <x-popover.separator />
                        <x-form method="post" action="{{ route('logout') }}" class="w-full flex">
                            <x-popover.item before="phosphor-sign-out">{{ __('Log Out') }}</x-popover.item>
                        </x-form>
                    </x-slot:menu>
                </x-popover>
            </x-container>
        </x-header>

        {{ $slot }}

    </body>
</html>
