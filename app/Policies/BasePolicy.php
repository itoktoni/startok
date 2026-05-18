<?php

namespace App\Policies;

use App\Models\User;

class BasePolicy
{
    public function allow(User $user): ?bool
    {
        if ($user->isDeveloper()) return true;

        // Default allow untuk admin dan user
        return null;
    }
}
