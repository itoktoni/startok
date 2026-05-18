<?php

namespace App\Http\Requests;

use App\Policies\BasePolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class GeneralRequest extends FormRequest
{
    protected array $abilityMap = [
        'getCreate' => 'save',
        'postCreate' => 'save',
        'getUpdate' => 'save',
        'postUpdate' => 'save',
        'postDelete' => 'delete',
        'postDeleteBulk' => 'delete',
    ];

    public function authorize(): bool
    {
        $method = $this->route()->getActionMethod();
        $ability = $this->abilityMap[$method] ?? null;
        $routeName = $this->route()->getName();
        $user = $this->user();

        if (!$user) return false;

        // Cek permission.php
        $restrict = config('permision');
        $role = $user->role ?? 'guest';

        if (isset($restrict[$role]) && in_array($routeName, $restrict[$role])) {
            return false;
        }

        // Jika method tidak ada di abilityMap, default boleh akses
        if ($ability === null) {
            return true;
        }

        // Cek role global dari BasePolicy
        $base = app(BasePolicy::class);
        $allowed = $base->allow($user);

        if ($allowed !== null) {
            return $allowed;
        }

        // Lanjut ke policy method spesifik (admin, dll)
        $controller = $this->route()->getController();
        $policy = Gate::getPolicyFor($controller->model);

        if ($policy && method_exists($policy, $ability)) {
            return $policy->{$ability}($user);
        }

        return false;
    }

    public function rules(): array
    {
        return [];
    }
}
