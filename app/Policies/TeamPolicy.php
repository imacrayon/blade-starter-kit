<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Team $team): bool
    {
        return $user->belongsToTeam($team) || $user->can('admin');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasTeamRole($team, UserRole::ADMIN) || $user->can('admin');
    }

    public function destroy(User $user, Team $team): bool
    {
        return $this->update($user, $team);
    }
}
