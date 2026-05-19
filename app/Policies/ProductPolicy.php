<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductPolicy
{
    protected $module;
    protected $restrict;

    public function __construct()
    {
        $this->module = request()->route()->getAction('name');
        $this->restrict = config('permision');
    }

    private function checkPermision($user, $permision)
    {
        $role = $user->role ?? 'guest';

        if (isset($this->restrict[$role][$this->module])) {

            if(is_array(($this->restrict[$role][$this->module])) && in_array($permision, $this->restrict[$role][$this->module]))
            {
                return false;
            }
        }

        return true;
    }

    public function create(User $user): bool
    {
        return $this->checkPermision($user, __FUNCTION__);
    }

    public function update(User $user): bool
    {
        return $this->checkPermision($user, __FUNCTION__);
    }

    public function save(User $user): bool
    {
        return $this->checkPermision($user, __FUNCTION__);
    }

    public function delete(User $user): bool
    {
        return $this->checkPermision($user, __FUNCTION__);
    }
}
