@blaze

@props([
    'name' => null,
    'id' => null,
    'value' => '',
    'label' => '',
    'description' => '',
    'bag' => 'default',
])

@php $field = \App\Field::for($name, $id, $bag); @endphp

<x-has-inline-field :id="$field->id" :label="$label" :description="$description">
    <input type="radio" value="{{ $value }}" {{ $field->attributes($attributes, $description)->class([
      'relative size-5 shrink-0 appearance-none rounded-full',
      'border border-gray-300 bg-white dark:bg-gray-950 outline-offset-1',
      'before:absolute before:inset-0 before:rounded-full before:border-2 before:border-white dark:before:border-black not-checked:before:hidden checked:border-(--color-accent) checked:before:bg-(--color-accent)',
      'disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400',
      'forced-colors:appearance-auto forced-colors:before:hidden',
      'aria-invalid:border-red-500'
    ]) }}>
</x-has-inline-field>
