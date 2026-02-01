<?php

namespace App\Policies;

use App\Models\ShortUrl;
use App\Models\User;

class ShortUrlPolicy
{
    // Who can view any short URLs
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'member']); // SuperAdmin cannot see URLs
    }

    // Who can view a specific short URL
    public function view(User $user, ShortUrl $shortUrl): bool
    {
        return match($user->role) {
            'admin'  => $shortUrl->company_id === $user->company_id,
            'member' => $shortUrl->user_id === $user->id,
            default  => false,
        };
    }

    // Who can create short URLs
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'member']); // SuperAdmin cannot create
    }
}
