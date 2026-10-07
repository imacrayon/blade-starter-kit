<?php

namespace App\Models;

use App\Concerns\GeneratesUniqueSlug;
use App\UserRole;
use Carbon\CarbonImmutable;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $users_count
 * @property int|null $admins_count
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, User> $users
 * @property-read Collection<int, Invitation> $invitations
 * @property-read Membership $membership
 */
class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use GeneratesUniqueSlug, HasFactory, Searchable;

    protected static function booted(): void
    {
        static::creating(function (Team $team) {
            if (empty($team->slug)) {
                $team->slug = static::generateUniqueSlug($team->name);
            }
        });

        static::updating(function (Team $team) {
            if ($team->isDirty('name')) {
                $team->slug = static::generateUniqueSlug($team->name, $team->id);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @param  array<string, mixed>  $attributes */
    public static function forUser(User $user, array $attributes = []): self
    {
        $team = self::create($attributes + [
            'name' => 'Untitled Team',
        ]);

        $user->joinTeam($team, UserRole::ADMIN);

        return $team;
    }

    /** @return BelongsToMany<User, $this, Membership, 'membership'> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->withPivot('role')
            ->using(Membership::class)
            ->as('membership')
            ->withTimestamps();
    }

    public function isSoleAdmin(User $user): bool
    {
        $admins = $this->users()->wherePivot('role', UserRole::ADMIN)->pluck('users.id');

        return $admins->count() === 1 && $admins->contains($user->id);
    }

    /** @return array<int|string, mixed> */
    public static function memberCounts(): array
    {
        return ['users', 'users as admins_count' => fn ($query) => $query->where('memberships.role', UserRole::ADMIN)];
    }

    public function needsAnotherAdmin(User $user): bool
    {
        if (! isset($this->users_count, $this->admins_count)) {
            $this->loadCount(self::memberCounts());
        }

        return $user->hasTeamRole($this, UserRole::ADMIN) && $this->admins_count === 1 && $this->users_count > 1;
    }

    /** @return HasMany<Invitation, $this> */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /** @return array<string, string> */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
