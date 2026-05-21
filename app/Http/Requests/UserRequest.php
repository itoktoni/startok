<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends GeneralRequest
{
    public function prepareForValidation()
    {
        $merge = [
            'password' => '',
        ];

        $this->merge($merge);
    }
}
