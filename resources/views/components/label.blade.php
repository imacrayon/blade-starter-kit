@props(['value'])

<label {{ $attributes->class(['block text-sm font-medium text-gray-800 dark:text-white']) }}>{{ $value ?? $slot }}</label>
