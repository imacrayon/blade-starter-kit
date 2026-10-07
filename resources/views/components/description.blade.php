@blaze

@props(['for', 'value' => null])

@php $field = \App\Field::for($for); @endphp

<div {{ $attributes->class('text-sm text-gray-500 dark:text-white/60')->merge([
    'id' => $field->descriptionId(),
  ]) }}>
    {{ $value ?? $slot }}
</div>
