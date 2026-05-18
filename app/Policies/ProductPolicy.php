<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['developer', 'admin']);
    }

    public function save(User $user): bool
    {
        return in_array($user->role, ['developer', 'admin']);
    }

    public function delete(User $user): bool
    {
        return $user->role === 'developer';
    }
}
