@props(['value'])

<legend {{ $attributes->merge(['class' => 'block text-sm font-medium leading-tight text-gray-800 dark:text-white']) }}>{{ $value ?? $slot }}</legend>
