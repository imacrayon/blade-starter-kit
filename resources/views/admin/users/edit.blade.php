<x-layouts.app :title="__('Edit user')">
    <x-headbar :title="__('Edit user')" />
    <x-form method="put" action="{{ route('admin.users.update', $user) }}" autocomplete="off" class="mt-6 max-w-xl space-y-6">
        <x-input :label="__('Name')" name="name" type="text" :value="$user->name" required autofocus autocomplete="name" />

        <x-input type="email" :label="__('Email')" name="email" :value="$user->email" required autocomplete="email" />

        <x-select :label="__('Role')" name="role" :options="App\UserRole::class"  :value="$user->role" required />

        <div class="flex gap-3">
            <x-button variant="primary">{{ __('Update') }}</x-button>
            <x-button type="button" onclick="history.back()">{{ __('Cancel') }}</x-button>
            <x-spacer />
            <x-button variant="danger" form="delete_user">{{ __('Delete user') }}</x-button>
        </div>
    </x-form>

    <x-form id="delete_user" method="delete" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('This user will be deleted.')" />
</x-layouts.app>
