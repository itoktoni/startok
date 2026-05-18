<?php

namespace App\Http\Controllers;

use App\Http\Function\ControllerHelper;

abstract class Controller
{
    use ControllerHelper;

    public $model;

    protected function isApi(): bool
    {
        if (request()->hasHeader('authorization')) {
            return true;
        }

        if (request()->wantsJson()) {
            return true;
        }

        return request()->expectsJson() || request()->is('api/*');
    }

    protected function getData()
    {
        return $this->model->filter()->sort();
    }

    protected function response(string $message = null, $redirect = null, $data = null, int $status = 200)
    {
        if ($this->isApi()) {
            $payload = ['message' => $message];
            if ($data) $payload['data'] = $data;
            return response()->json($payload, $status);
        }

        flash()->success($message);
        return redirect()->back();
    }

    protected function respondView(string $view, array $data = [])
    {
        if ($this->isApi()) {
            return response()->json($data['model'] ?? $data[array_key_first($data)] ?? $data);
        }

        return view($view, $data);
    }
}
