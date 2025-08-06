<x-layouts.app :title="__('New Team')">
    <x-headbar :title="__('New Team')" />
    <x-form method="post" :action="route('admin.teams.store')" class="mt-6 max-w-lg space-y-6">
        @include('teams._fields')
        <div class="flex gap-3">
            <x-button>Save</x-button>
            <x-button variant="secondary" onclick="history.back()">Cancel</x-button>
        </div>
    </x-form>
</x-layouts.app>
