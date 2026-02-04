@props(['for'])

<div {{ $attributes->class('text-sm text-gray-500 dark:text-white/60')->merge([
    'id' => \App\View\Components\Control::sessionPath($for).'_description',
  ]) }}>
    {{ $value ?? $slot }}
</div>
