<?php

namespace App\Concerns;

use Illuminate\Support\Str;

trait GeneratesUniqueSlug
{
    public static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($name) ?: Str::slug(class_basename(static::class));
        $slug = $baseSlug;

        // Unique indexes include soft-deleted rows, so the check must too.
        while (static::withoutGlobalScopes()
            ->where('slug', $slug)
            ->when($excludeId, fn ($query) => $query->whereKeyNot($excludeId))
            ->exists()) {
            $slug = $baseSlug.'-'.Str::lower(Str::random(6));
        }

        return $slug;
    }
}
