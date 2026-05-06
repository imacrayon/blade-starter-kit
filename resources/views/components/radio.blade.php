<x-has-inline-field>
    <input type="radio" {{ $controlAttributes }} value="{{ $value }}" {{ $attributes->class([
      'relative size-5 appearance-none rounded-full',
      'border border-gray-300 bg-white dark:bg-gray-950 outline-offset-1',
      'before:absolute before:inset-0 before:rounded-full before:border-2 before:border-white dark:before:border-black not-checked:before:hidden checked:border-(--color-accent) checked:before:bg-(--color-accent)',
      'disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400',
      'forced-colors:appearance-auto forced-colors:before:hidden',
      'aria-invalid:border-red-500'
    ]) }}>
</x-has-inline-field>
