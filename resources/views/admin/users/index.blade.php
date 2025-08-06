<x-layouts.app :title="__('Users')">
    <x-headbar :title="__('Users')" />
    <x-section class="mt-6">
    <form x-target.replace="users" x-on:input.debounce="$el.requestSubmit()" class="flex gap-3">
        <x-label for="q" :value="__('Search')" class="sr-only" />
        <x-input name="q" placeholder="Search by name or email" autocomplete="off" />
        <x-button x-show="false" variant="secondary">Submit</x-button>
    </form>
    <div id="users">
        @if($users->isNotEmpty())
            <x-table>
                <x-slot:head>
                    <x-table.row>
                        <x-table.col>{{ __('Name') }}</x-table.col>
                        <x-table.col>{{ __('Email') }}</x-table.col>
                        <x-table.col>{{ __('Role') }}</x-table.col>
                        <x-table.col class="text-right">{{ __('Teams') }}</x-table.col>
                        <x-table.col>{{ __('Joined') }}</x-table.col>
                        <x-table.col class="text-right">{{ __('Actions') }}</x-table.col>
                    </x-table.row>
                </x-slot:head>
                <x-slot:body>
                    @foreach($users as $user)
                        <x-table.row>
                            <x-table.cell>
                                <div class="flex gap-2 items-center">
                                    <img width="28" height="28" role="presentation" class="flex-none rounded-full bg-gray-50" src="{{ $user->avatar }}" alt="">
                                    <x-link id="user_{{ $user->id }}_name" href="{{ route('admin.users.edit', $user) }}" class="font-semibold">{{ $user->name }}</x-link>
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                <x-link href="mailto:{{ $user->email }}" class="truncate">{{ $user->email }}</x-link>
                            </x-table.cell>
                            <x-table.cell>
                                {{ $user->role->label() }}
                            </x-table.cell>
                            <x-table.cell class="text-right">
                                {{ $user->teams_count }}
                            </x-table.cell>
                            <x-table.cell>
                                <x-time :datetime="$user->created_at" />
                            </x-table.cell>
                            <x-table.cell>
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" aria-describedby="user_{{ $user->id }}_name" title="Edit">
                                        <x-phosphor-pencil width="20" height="20" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" aria-hidden="true" />
                                        <span class="sr-only">Edit</span>
                                    </a>
                                    @if($user->isNot(Auth::user()))
                                        <x-form method="post" action="{{ route('admin.impersonation.store') }}" class="contents">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button aria-describedby="user_{{ $user->id }}_name" title="Impersonate">
                                                <x-phosphor-user-switch width="20" height="20" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" aria-hidden="true" />
                                                <span class="sr-only">Impersonate</span>
                                            </button>
                                        </x-form>
                                    @else
                                        <x-phosphor-user-switch width="20" height="20" class="text-gray-200 dark:text-gray-600" aria-hidden="true" />
                                    @endif
                                    <x-form class="contents" x-target="users" onsubmit="return confirm('This user will be deleted.')" method="delete" action="{{ route('admin.users.destroy', $user) }}">
                                        <button title="Delete">
                                            <x-phosphor-trash width="20" height="20" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400" aria-hidden="true" />
                                            <span class="sr-only">Delete</span>
                                        </button>
                                    </x-form>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-slot:body>
            </x-table>
        @endif
        <x-pagination class="mt-1" :paginator="$users" />
    </div>
</x-section>
</x-layouts.app>
