<x-has-inline-field>
    <input type="radio" {{ $controlAttributes }} value="{{ $value }}" {{ $attributes->class([
      'relative size-5 appearance-none rounded-full',
      'border border-gray-300 bg-white',
      'before:absolute before:inset-1 before:rounded-full before:bg-white not-checked:before:hidden checked:border-(--color-accent) checked:bg-(--color-accent)',
      'disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400',
      'forced-colors:appearance-auto forced-colors:before:hidden',
    ]) }}>
</x-has-inline-field>
