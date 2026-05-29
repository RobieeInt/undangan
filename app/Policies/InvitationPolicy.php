<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    public function view(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id || $user->isAdmin();
    }

    public function update(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id;
    }

    public function delete(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id || $user->isAdmin();
    }

    public function publish(User $user, Invitation $invitation): bool
    {
        return $user->id === $invitation->user_id && $invitation->is_active;
    }
}
