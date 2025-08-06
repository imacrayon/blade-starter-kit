<?php

namespace App\Models;

use App\UserRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasTeams
{
    public static $hasActiveTeam = true;

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

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

    public function hasTeamRole(Team $team, $roles): bool
    {
        if (! is_array($roles)) {
            $roles = [$roles];
        }

        $team = $this->teams->find($team);

        return $team && in_array($team->membership->role, $roles);
    }

    public function joinTeam(Team $team, $role = UserRole::MEMBER)
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

        if (self::$hasActiveTeam) {
            $this->update(['team_id' => $team->id]);
            $this->unsetRelation('team');
        }

        return $this;
    }

    public function leaveTeam(Team $team)
    {
        $this->teams()->detach($team);

        if (self::$hasActiveTeam) {
            if ($this->team_id !== $team->id) {
                return $this;
            }

            if ($newTeam = $this->teams()->first()) {
                $this->update(['team_id' => $newTeam->getKey()]);
                $this->unsetRelation('team');
            } else {
                $this->joinTeam(Team::create([
                    'name' => 'Untitled Team',
                ]), UserRole::ADMIN);
            }
        }

        return $this;
    }
}
