<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    /**
     * Admin bypasses all policy checks automatically.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null; // defer to the specific policy method
    }

    public function view(User $user, Invitation $invitation): bool
    {
        return (int) $user->id === (int) $invitation->user_id;
    }

    public function update(User $user, Invitation $invitation): bool
    {
        return (int) $user->id === (int) $invitation->user_id;
    }

    public function delete(User $user, Invitation $invitation): bool
    {
        return (int) $user->id === (int) $invitation->user_id;
    }

    public function publish(User $user, Invitation $invitation): bool
    {
        return (int) $user->id === (int) $invitation->user_id && $invitation->is_active;
    }
}
