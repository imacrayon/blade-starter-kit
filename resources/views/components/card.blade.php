@blaze(fold: true)

<div {{ $attributes->class('bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xs [:where(&)]:p-3 [:where(&)]:rounded-[0.625rem]') }} data-card>
    {{ $slot }}
</div>
