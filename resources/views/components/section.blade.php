<div {{ $attributes->merge(['class' => 'relative bg-gray-50 dark:bg-gray-900/50 space-y-1 [:where(&)]:rounded-[0.75rem] [:where(&)]:p-1 [&>[data-heading]]:px-3 [&>[data-heading]]:py-1']) }}>
    {{ $slot }}
</div>
