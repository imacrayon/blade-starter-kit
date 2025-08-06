<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    public function update(User $user, Invitation $invitation): bool
    {
        return $user->can('update', $invitation->team);
    }

    public function delete(User $user, Invitation $invitation): bool
    {
        return $this->update($user, $invitation);
    }
}
