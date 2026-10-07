<?php

namespace App\Concerns;

use App\Models\Membership;
use App\Models\Team;
use App\UserRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Validation\ValidationException;

trait HasTeams
{
    public static function bootHasTeams(): void
    {
        static::deleting(function (self $user) {
            $user->ensureDeletable();
            $user->teams()->has('users', '=', 1)->get()->each->delete();
        });
    }

    /** @return BelongsTo<Team, $this> */
    public function team(): BelongsTo
    {
        if (is_null($this->team_id) && $this->id) {
            $this->healTeams();
        }

        return $this->belongsTo(Team::class);
    }

    /** @return BelongsToMany<Team, $this, Membership, 'membership'> */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'memberships')
            ->withPivot('role')
            ->using(Membership::class)
            ->as('membership')
            ->withTimestamps()
            ->orderBy('name');
    }

    public function belongsToTeam(Team $team): bool
    {
        if (is_null($team->getKey())) {
            return false;
        }

        return $this->teams->contains($team);
    }

    /** @param  UserRole|array<UserRole>  $roles */
    public function hasTeamRole(Team $team, UserRole|array $roles): bool
    {
        if (! is_array($roles)) {
            $roles = [$roles];
        }

        $team = $this->teams->find($team);

        return $team && in_array($team->membership->role, $roles);
    }

    public function ensureDeletable(): void
    {
        $team = $this->teams->loadCount(Team::memberCounts())->first(fn (Team $team) => $team->needsAnotherAdmin($this));

        throw_if($team, ValidationException::withMessages([
            'team' => __('Promote another admin of :team before deleting this account.', ['team' => $team?->name]),
        ]));
    }

    public function joinTeam(Team $team, UserRole $role = UserRole::MEMBER): static
    {
        if ($this->belongsToTeam($team)) {
            return $this;
        }

        $this->teams()->syncWithPivotValues(
            $team,
            ['role' => $role],
            detaching: false
        );

        $this->unsetRelation('teams');

        return $this->switchTeam($team);
    }

    public function switchTeam(Team $team): static
    {
        $this->update(['team_id' => $team->id]);
        $this->setRelation('team', $team);

        return $this;
    }

    public function leaveTeam(Team $team): static
    {
        $this->teams()->detach($team);

        // Nobody can reach a team without members, so drop it along with its invitations.
        if ($team->users()->doesntExist()) {
            $team->delete();
        }

        if ($this->team_id === $team->id) {
            $this->healTeams();
        }

        return $this;
    }

    public function healTeams(): void
    {
        if ($team = $this->teams()->first()) {
            $this->switchTeam($team);
        } else {
            $this->joinTeam(Team::create([
                'name' => 'Untitled Team',
            ]), UserRole::ADMIN);
        }
    }
}
