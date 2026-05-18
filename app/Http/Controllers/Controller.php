<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $model;

    protected function share($data = [])
    {
        $default = [
            'model' => $this->model,
        ];

        return array_merge($default, $data);
    }

    protected function views(string $view, array $data = [], int $status = 200)
    {
        if (request()->expectsJson()) {
            return response()->json($data, $status);
        }

        return view($view, $this->share($data));
    }

    public function template($file = null, $folder = null, $core = false)
    {
        // Get the class name (e.g., UserController)
        $className = class_basename(get_class($this));

        // Remove 'Controller' suffix and convert to lowercase
        $module = strtolower(str_replace('Controller', '', $className));

        // Get the method name (e.g., getCreate)
        $method = debug_backtrace()[1]['function'];

        // Remove 'get' or 'post' prefix and convert to lowercase
        $action = strtolower(preg_replace('/^(get|post)/', '', $method));

        if(in_array($action, ['update', 'create']))
        {
            $action = 'form';
        }

        if ($file)
        {
            $action = $file;
        }

        if($file === true)
        {
            return $module;
        }

        if($folder)
        {
            $module = $folder;
        }

        $path = 'pages.';

        if($core)
        {
            $path = 'core.';
        }

        return $path.$module.'.'.$action;
    }

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
        if(empty($message))
        {
            $message = "Berhasil !";
        }

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
