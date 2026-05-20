<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class BasePolicy
{
    public function allow(User $user): ?bool
    {
        if ($user->isDeveloper()) return true;

        // Default allow untuk admin dan user
        return null;
    }

    protected $module;
    protected $restrict;

    public function __construct()
    {
        $this->module = request()->route()->getAction('name');
        $this->restrict = config('permision');
    }

    private function accessProtected($user, $permision)
    {
        $role = $user->role ?? 'guest';

        if (isset($this->restrict[$role][$this->module])) {

            if(in_array($permision, $this->restrict[$role][$this->module]))
            {
                return true;
            }
        }

        return false;
    }

    public function create(User $user) : Response
    {
        return $this->accessProtected($user, __FUNCTION__) ? Response::deny() : Response::allow();
    }

    public function update(User $user): Response
    {
        return $this->accessProtected($user, __FUNCTION__) ? Response::deny() : Response::allow();
    }

    public function table(User $user): Response
    {
        return $this->accessProtected($user, __FUNCTION__) ? Response::deny() : Response::allow();
    }

    public function delete(User $user): Response
    {
         return $this->accessProtected($user, __FUNCTION__) ? Response::deny() : Response::allow();
    }

    public function show(User $user): Response
    {
        return $this->accessProtected($user, __FUNCTION__) ? Response::deny() : Response::allow();
    }
}
