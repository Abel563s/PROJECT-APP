<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleWriteMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeName = $request->route()?->getName() ?? '';

        if (!$user || $user->isSuperadmin() || $user->isAdmin()) {
            return $next($request);
        }

        if ($user->isManager() && (
            str_contains($routeName, '.create')
            || str_contains($routeName, '.edit')
            || str_contains($routeName, '.password.modal')
        )) {
            abort(403, 'Management access is view-only.');
        }

        if ($user->isUser() && (
            (str_contains($routeName, '.create') && !in_array($routeName, [
                'admin.progress-updates.create',
                'admin.projects.issues.create-modal',
                'admin.projects.payments.create-modal',
                'admin.projects.risk.create-modal',
            ], true))
            || (str_contains($routeName, '.edit') && !in_array($routeName, [
                'admin.projects.edit',
                'admin.progress-updates.edit',
                'admin.projects.issues.edit',
                'admin.projects.payments.edit-modal',
                'admin.projects.risk.edit-modal',
            ], true))
        )) {
            abort(403, 'Project Coordinators cannot access administrative edit forms.');
        }

        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

        $coordinatorRoutes = [
            'admin.projects.update',
            'admin.projects.toggle-status',
            'admin.progress-updates.store',
            'admin.progress-updates.update',
            'admin.projects.issues.store',
            'admin.projects.issues.update',
            'admin.projects.issues.destroy',
            'admin.projects.payments.store',
            'admin.projects.payments.update',
            'admin.projects.payments.destroy',
            'admin.projects.risk.store',
            'admin.projects.risk.update',
        ];

        if ($user->isUser() && in_array($routeName, $coordinatorRoutes, true)) {
            return $next($request);
        }

        abort(403, 'This role does not have permission to modify records.');
    }
}
