@blaze

@props([
    'name' => null,
    'id' => null,
    'value' => '',
    'label' => '',
    'description' => '',
    'bag' => 'default',
    'size' => 'base',
])

@php
$field = \App\Field::for($name, $id, $bag);
$isPassword = $attributes['type'] === 'password';
$classes = match($attributes['type']) {
    'file' => [
        'appearance-none block w-full',
        'p-0.5',
        'bg-gray-50 dark:bg-white/10 dark:disabled:bg-white/[7%]',
        'text-gray-700 disabled:text-gray-500 placeholder-gray-400 disabled:placeholder-gray-400/70 dark:text-gray-300 dark:disabled:text-gray-400 dark:placeholder-gray-400 dark:disabled:placeholder-gray-500',
        'rounded-lg shadow-xs border border-gray-200 dark:border-white/10',
        'aria-invalid:border-red-500',
        'file:font-medium file:mr-[1em]',
        'file:text-gray-800 dark:file:text-white',
        'file:border file:border-gray-200 hover:file:border-gray-200 file:border-b-gray-300/80 dark:file:border-gray-600 dark:hover:file:border-gray-600',
        'file:bg-white hover:file:bg-gray-50 dark:file:bg-gray-700 dark:hover:file:bg-gray-600/75',
        match ($size) {
            'base' => 'text-base sm:text-sm h-10 file:h-8.5 file:px-4 file:rounded-md file:shadow-xs leading-[1.375rem]',
            'sm' => 'text-sm h-8 file:h-6.5 file:px-3 file:rounded-sm file:shadow-xs leading-[1.125rem]',
            'xs' => 'text-xs h-6 file:h-4.5 file:px-2 file:rounded-sm file:shadow-none leading-[1.125rem]',
        },
    ],
    default => [
        'appearance-none block w-full',
        'bg-white dark:bg-gray-900 dark:disabled:bg-white/2',
        'text-gray-700 disabled:text-gray-500 placeholder-gray-400 disabled:placeholder-gray-400/70 dark:text-gray-300 dark:disabled:text-gray-400 dark:placeholder-gray-400 dark:disabled:placeholder-gray-500',
        'rounded-lg border border-gray-200 border-b-gray-300/80 disabled:border-b-gray-200 dark:border-b-gray-600/80 dark:border-white/15 dark:disabled:border-white/5',
        'shadow-xs disabled:shadow-none',
        'aria-invalid:border-red-500',
        match ($size) {
            'base' => 'text-base sm:text-sm px-3 py-2 h-10 leading-[1.375rem]',
            'sm' => 'text-sm px-2 py-1.5 h-8 leading-[1.125rem]',
            'xs' => 'text-xs px-1 py-1.5 h-6 leading-[1.125rem]',
        },
    ]
};
@endphp

<x-has-field :name="$name" :id="$field->id" :label="$label" :description="$description" :bag="$bag">
    @if ($isPassword)
    <div class="relative" x-data="{ visible: false }">
    @endif
    <input
        value="{{ $field->old($value) }}"
        @if ($isPassword) x-bind:type="visible ? 'text' : 'password'" @endif
        {{ $field->attributes($attributes->merge(['type' => 'text']), $description)->class([...$classes, 'pe-10' => $isPassword]) }}>
    @if ($isPassword)
        <button type="button" x-on:click="visible = ! visible" x-bind:aria-pressed="visible" aria-pressed="false" class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <span class="sr-only">{{ __('Show password') }}</span>
            <x-phosphor-eye x-show="! visible" aria-hidden="true" width="16" height="16" />
            <x-phosphor-eye-closed x-show="visible" aria-hidden="true" width="16" height="16" style="display: none" />
        </button>
    </div>
    @endif
</x-has-field>
