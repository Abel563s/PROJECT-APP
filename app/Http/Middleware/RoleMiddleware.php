<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $requiredRoles = explode('|', $role);

        // Superadmin-only routes: require explicit superadmin role (Admin does NOT bypass)
        if (in_array('superadmin', $requiredRoles, true)) {
            if (!$user->isSuperadmin()) {
                abort(403, 'Unauthorized. Only Superadmin can access this resource.');
            }
            return $next($request);
        }

        // Superadmin bypasses all other role checks
        if ($user->isSuperadmin()) {
            return $next($request);
        }

        // High priority: Admins always have access to everything (except superadmin-only routes)
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has the required role
        if (!$this->hasRole($user, $role)) {
            \Log::warning("Role Access Denied: User ID {$user->id}, Role: '{$user->role}', Required: '{$role}', Path: " . $request->path());
            abort(403, 'Unauthorized. You do not have the required permissions to access this resource.');
        }

        return $next($request);
    }

    /**
     * Check if user has the required role.
     */
    private function hasRole($user, string $role): bool
    {
        // Superadmin satisfies any role requirement
        if ($user->isSuperadmin()) {
            return true;
        }

        // Allow multiple roles separated by pipe
        $roles = explode('|', $role);

        foreach ($roles as $requiredRole) {
            switch ($requiredRole) {
                case 'admin':
                    if ($user->isAdmin())
                        return true;
                    break;
                case 'manager':
                    if ($user->isManager())
                        return true;
                    break;
                case 'user':
                    if ($user->isUser())
                        return true;
                    break;
                case 'superadmin':
                    if ($user->isSuperadmin())
                        return true;
                    break;
                case 'admin_manager':
                    if ($user->isAdmin() || $user->isManager())
                        return true;
                    break;
                case 'manager_user':
                    if ($user->isManager() || $user->isUser())
                        return true;
                    break;
                case 'admin_manager_user':
                    if ($user->isAdmin() || $user->isManager() || $user->isUser())
                        return true;
                    break;
                case 'department_attendance_user':
                    if ($user->isDepartmentAttendanceUser())
                        return true;
                    break;
            }
        }

        return false;
    }
}
