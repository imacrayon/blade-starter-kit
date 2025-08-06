<?php

namespace App\Models;

use App\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory, Searchable;

    protected static $unguarded = true;

    public static function forUser(User $user, $attributes = [])
    {
        $team = self::create($attributes + [
            'name' => 'Untitled Team',
        ]);

        $user->joinTeam($team, UserRole::ADMIN);

        return $team;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->withPivot('role')
            ->using(Membership::class)
            ->as('membership')
            ->withTimestamps();
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
