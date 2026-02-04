<div {{ $attributes->class('bg-white dark:bg-gray-800 border border-gray-200 dark:border-white/10 shadow-xs [:where(&)]:p-3 [:where(&)]:rounded-[0.625rem]') }} data-card>
    {{ $slot }}
</div>
