<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        $role = $user->role;
        $module = request()->route()->getAction('name');
        $restrict = config('permision');

        if(isset($restrict[$role]) && !isset($restrict[$role][$module]))
        {
            abort(403, ERROR_PERMISION);
        }

        return $next($request);
    }
}
