@props([
    'title',
    'subtitle' => null,
])

<div class="relative w-full">
    <div class="flex justify-between items-center">
        <div class="min-w-0 flex-1">
            <?php if (!isset($head)) : ?>
                <x-heading size="xl" level="1">{{ $title }}</x-heading>
                <?php if ($subtitle) : ?>
                    <x-subheading size="lg">{{ $subtitle }}</x-subheading>
                <?php endif; ?>
            <?php else: ?>
                {{ $head }}
            <?php endif; ?>
        </div>
        <?php if ($slot->isNotEmpty()) : ?>
        <div class="flex -my-2 gap-3">
            {{ $slot }}
        </div>
        <?php endif; ?>
    </div>
    <x-separator class="mt-6" />
</div>
