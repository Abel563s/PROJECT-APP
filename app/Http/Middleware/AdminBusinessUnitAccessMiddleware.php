<?php

namespace App\Http\Middleware;

use App\Models\BusinessUnit;
use App\Models\Issue;
use App\Models\ManageMaterial;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\ProjectProgressUpdate;
use App\Models\ProjectRisk;
use App\Models\ProjectWeeklyUpdate;
use App\Models\ProjectSite;
use App\Models\RiskCategory;
use App\Models\ProjectRiskPool;
use App\Models\Sector;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminBusinessUnitAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user?->isAdmin() || $user->isSuperadmin()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName() ?? '';
        if (in_array($routeName, [
            'admin.settings.account.update',
            'admin.settings.profile.update',
            'admin.settings.password.update',
        ], true)) {
            return $next($request);
        }

        $method = strtoupper($request->method());
        $isWrite = !in_array($method, ['GET', 'HEAD', 'OPTIONS'], true);
        $isFormPage = preg_match('/\.(create|create-modal|edit|edit-modal)$/', $routeName) === 1
            || str_ends_with($routeName, '.password.modal');
        if (!$isWrite && !$isFormPage) {
            return $next($request);
        }

        // Project creation is scoped server-side to the signed-in Admin's unit.
        if (in_array($routeName, ['admin.projects.create', 'admin.projects.store'], true)) {
            abort_unless($user->business_unit_id !== null, 403, 'Assign a Business Unit before creating projects.');
            return $next($request);
        }

        // User creation is allowed; the controller assigns the Admin's own unit.
        if (in_array($routeName, ['admin.users.create', 'admin.users.store'], true)) {
            abort_unless($user->business_unit_id !== null, 403, 'Assign a Business Unit before creating users.');
            return $next($request);
        }

        if (str_starts_with($routeName, 'admin.projects.risk-pool.')) {
            // The shared risk pool uses explicit Superadmin-granted permissions,
            // which are enforced by the controller for each operation and bulk row.
            return $next($request);
        }

        if (str_starts_with($routeName, 'admin.project-sites.')) {
            abort(403, 'Admins have view-only access to shared records.');
        }

        $project = null;
        foreach ($request->route()?->parameters() ?? [] as $parameter) {
            if ($parameter instanceof Project) {
                $project = $parameter;
                break;
            }

            if ($parameter instanceof User) {
                abort_unless($user->business_unit_id !== null
                    && $parameter->business_unit_id !== null
                    && (int) $parameter->business_unit_id === (int) $user->business_unit_id, 403);
                continue;
            }

            if ($parameter instanceof ProjectRisk || $parameter instanceof Issue || $parameter instanceof ProjectPayment
                || $parameter instanceof ProjectProgressUpdate || $parameter instanceof ProjectWeeklyUpdate
                || $parameter instanceof ManageMaterial) {
                $project = $parameter->project;
                break;
            }

            if ($parameter instanceof ProjectSite || $parameter instanceof RiskCategory || $parameter instanceof ProjectRiskPool
                || $parameter instanceof BusinessUnit || $parameter instanceof Sector) {
                abort(403, 'Admins have view-only access to shared records.');
            }
        }

        if ($project) {
            abort_unless($this->belongsToAdmin($project, $user), 403);
        }

        if ($isWrite && in_array($routeName, ['admin.material.store', 'admin.material.update'], true)) {
            abort_unless($request->filled('project_id'), 403, 'Material records must belong to a project in your Business Unit.');
        }

        if (!$isWrite && $isFormPage && str_contains($routeName, '.edit') && !$project
            && !str_starts_with($routeName, 'admin.users.')) {
            abort(403, 'Admins have view-only access to records outside their Business Unit.');
        }

        // Also validate destination projects on child-record edits and creates.
        if ($request->filled('project_id')) {
            $destinationProject = Project::find($request->input('project_id'));
            abort_unless($destinationProject && $this->belongsToAdmin($destinationProject, $user), 403);
            $project = $destinationProject;
        }

        if ($isWrite && !$project && !str_starts_with($routeName, 'admin.users.')) {
            abort(403, 'Admins can modify only records assigned to their Business Unit.');
        }

        return $next($request);
    }

    private function belongsToAdmin(Project $project, User $user): bool
    {
        return $user->business_unit_id !== null
            && (int) $project->business_unit_id === (int) $user->business_unit_id;
    }
}
