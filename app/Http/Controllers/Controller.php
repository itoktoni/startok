<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function isApi(): bool
    {
        return request()->expectsJson() || request()->is('api/*');
    }

    protected function respond(string $message, $redirect, $data = null, int $status = 200)
    {
        if ($this->isApi()) {
            $payload = ['message' => $message];
            if ($data) $payload['data'] = $data;
            return response()->json($payload, $status);
        }

        flash()->success($message);
        return $redirect;
    }

    protected function respondView(string $view, array $data = [])
    {
        if ($this->isApi()) {
            return response()->json($data['model'] ?? $data[array_key_first($data)] ?? $data);
        }

        return view($view, $data);
    }
}
