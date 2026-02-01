<?php

namespace App\Policies;

use App\Models\User;

class InvitationPolicy
{
    public function create(User $user, string $role): bool
    {
        // SuperAdmin can invite ONLY admin
        if ($user->role === 'super_admin') {
            return $role === 'admin';
        }

        // Admin can invite admin or member (inside own company)
        if ($user->role === 'admin') {
            return in_array($role, ['admin', 'member']);
        }

        return false;
    }
}

