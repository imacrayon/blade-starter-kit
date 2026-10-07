<x-layouts.app :title="__('Appearance | Settings')">
    <x-settings.layout>
        <x-panel>
            <div data-heading>
                <x-heading level="2">Appearance</x-heading>
                <x-subheading>Update the appearance settings for your account</x-subheading>
            </div>
            <x-card>
                <fieldset>
                    <legend class="sr-only">Appearance</legend>
                    <x-button.group>
                        <x-button type="button" before="phosphor-sun-fill" value="light" onclick="setAppearance(this.value)">{{ __('Light') }}</x-button>
                        <x-button type="button" before="phosphor-moon-fill" value="dark" onclick="setAppearance(this.value)">{{ __('Dark') }}</x-button>
                        <x-button type="button" before="phosphor-monitor-fill" value="system" onclick="setAppearance(this.value)">{{ __('System') }}</x-button>
                    </x-button.group>
                </fieldset>
            </x-card>
        </x-panel>
    </x-settings.layout>
</x-layouts.app>
