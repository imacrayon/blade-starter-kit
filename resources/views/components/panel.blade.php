@blaze(fold: true)

<div {{ $attributes->merge(['class' => 'relative [:where(&)]:bg-gray-50 dark:[:where(&)]:bg-white/2 dark:ring dark:ring-gray-900 [:where(&)]:space-y-1 [:where(&)]:rounded-[0.75rem] [:where(&)]:p-1 [&>header]:px-3 [&>header]:py-2 [&>[data-heading]]:px-3 [&>[data-heading]]:py-2']) }}>
    {{ $slot }}
</div>
