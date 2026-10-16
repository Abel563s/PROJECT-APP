<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (
            $request->user()?->must_change_password
            && !in_array($routeName, ['password.first-login.edit', 'password.first-login.update'], true)
        ) {
            return redirect()->route('password.first-login.edit');
        }

        return $next($request);
    }
}
