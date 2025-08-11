<x-has-field>
    <textarea {{ $controlAttributes }} {{ $attributes->class([
      'appearance-none block w-full',
      'bg-white dark:bg-white/5 dark:disabled:bg-white/2',
      'text-gray-700 disabled:text-gray-500',
      'rounded-lg border border-gray-200 border-b-gray-300/80 disabled:border-b-gray-200 dark:border-white/10 dark:disabled:border-white/5',
      'shadow-xs disabled:shadow-none dark:shadow-none',
      'aria-invalid:border-red-500',
    ]) }}>{{ $value ?: $slot }}</textarea>
</x-has-field>
