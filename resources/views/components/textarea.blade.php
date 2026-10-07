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
$value = $field->old($value);
@endphp

<x-has-field :name="$name" :id="$field->id" :label="$label" :description="$description" :bag="$bag">
    <textarea {{ $field->attributes($attributes, $description)->class([
      'appearance-none block w-full',
      'bg-white dark:bg-gray-900 dark:disabled:bg-white/2',
      'text-gray-700 disabled:text-gray-500',
      'rounded-lg border border-gray-200 border-b-gray-300/80 disabled:border-b-gray-200 dark:border-gray-800 dark:disabled:border-white/5',
      'shadow-xs disabled:shadow-none dark:shadow-none',
      'aria-invalid:border-red-500',
      match ($size) {
          'base' => 'text-base sm:text-sm px-3 py-2 leading-[1.375rem]',
          'sm' => 'text-sm px-2 py-1.5 leading-[1.125rem]',
          'xs' => 'text-xs px-1 py-1.5 leading-[1.125rem]',
      },
    ]) }}>{{ $value === '' || $value === null ? $slot : $value }}</textarea>
</x-has-field>
