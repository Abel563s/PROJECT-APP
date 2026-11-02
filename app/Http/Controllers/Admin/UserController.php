<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Project;
use App\Models\Sector;
use App\Models\User;
use App\Support\ProjectOrganizationFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function roles(Request $request)
    {
        $roles = [
            'superadmin' => User::where('role', 'superadmin')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'manager' => User::where('role', 'manager')->count(),
            'user' => User::where('role', 'user')->count(),
        ];
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(['admin', 'manager', 'user', 'superadmin'])],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'business_unit_id' => ['nullable', 'integer', 'exists:business_units,id'],
            'sector_id' => ['nullable', 'integer', 'exists:sectors,id'],
        ]);

        $userQuery = User::with('assignedProjects', 'businessUnit', 'sector');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $userQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['role'])) {
            $userQuery->where('role', $filters['role']);
        }

        if (!empty($filters['status'])) {
            $userQuery->where('is_active', $filters['status'] === 'active');
        }

        $businessUnitFilterId = ProjectOrganizationFilters::effectiveBusinessUnitId($request);
        if ($businessUnitFilterId) {
            $userQuery->where('business_unit_id', $businessUnitFilterId);
        }

        if (!empty($filters['sector_id'])) {
            $userQuery->where('sector_id', $filters['sector_id']);
        }

        $users = $userQuery->latest()->paginate(15)->withQueryString();
        $projects = $this->assignmentProjects();
        $businessUnits = BusinessUnit::active()->orderBy('name')->get(['id', 'name']);
        $sectors = Sector::active()
            ->when($businessUnitFilterId, fn ($query) => $query->where('business_unit_id', $businessUnitFilterId))
            ->orderBy('name')->get(['id', 'business_unit_id', 'name']);

        return view('admin.roles.index', compact('roles', 'users', 'projects', 'businessUnits', 'sectors'));
    }

    public function edit(User $user)
    {
        $projects = $this->assignmentProjects();
        $businessUnits = $this->availableBusinessUnits();
        $selectedBusinessUnitId = auth()->user()->isAdmin()
            ? auth()->user()->business_unit_id
            : (old('business_unit_id', $user->business_unit_id) ?: ($businessUnits->isNotEmpty() ? $businessUnits->first()->id : null));
        $sectors = $this->sectorsForBusinessUnit($selectedBusinessUnitId);

        if (request()->has('partial')) {
            return view('admin.users.edit-modal', compact('user', 'projects', 'businessUnits', 'sectors'));
        }

        return redirect()->route('admin.roles.index');
    }

    public function passwordModal(User $user)
    {
        if (request()->has('partial')) {
            return view('admin.users.password-modal', compact('user'));
        }
        return redirect()->route('admin.roles.index');
    }

    public function create()
    {
        if (request()->has('partial')) {
            $projects = $this->assignmentProjects();
            $businessUnits = $this->availableBusinessUnits();
            $sectors = $this->sectorsForBusinessUnit(
                $businessUnits->isNotEmpty() ? $businessUnits->first()->id : null
            );

            return view('admin.users.create-modal', compact('projects', 'businessUnits', 'sectors'));
        }
        return redirect()->route('admin.roles.index');
    }

    private function availableBusinessUnits()
    {
        $user = auth()->user();

        if ($user->isSuperadmin()) {
            return BusinessUnit::active()->with('activeSectors')->get();
        }

        if ($user->isAdmin() && $user->business_unit_id) {
            return BusinessUnit::where('id', $user->business_unit_id)->with('activeSectors')->get();
        }

        return collect();
    }

    private function sectorsForBusinessUnit($businessUnitId = null)
    {
        if (!$businessUnitId) {
            return collect();
        }

        return Sector::where('business_unit_id', $businessUnitId)
            ->active()
            ->get();
    }

    private function assignmentProjects()
    {
        $query = Project::query();
        if (auth()->user()->isAdmin()) {
            if (auth()->user()->business_unit_id === null) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('business_unit_id', auth()->user()->business_unit_id);
            }
        }

        return $query
            ->get(['id', 'project_code', 'project_name'])
            ->sortBy(
                fn (Project $project) => $project->project_code ?: $project->project_name,
                SORT_NATURAL | SORT_FLAG_CASE
            )
            ->values();
    }

    public function store(Request $request)
    {
        $currentUser = $request->user();
        $availableRoles = ['admin', 'manager', 'user'];
        $isAdmin = $request->input('role') === 'admin';
        $canAssignRiskPoolPermissions = $currentUser->isSuperadmin() && $isAdmin;

        if ($currentUser->isSuperadmin()) {
            $availableRoles[] = 'superadmin';
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'role' => ['required', Rule::in($availableRoles)],
            'password' => $isAdmin
                ? ['required', 'string', 'min:8', 'confirmed']
                : ['prohibited'],
            'password_confirmation' => $isAdmin ? ['required'] : ['prohibited'],
            'is_active' => ['nullable', 'boolean'],
            'business_unit_id' => $currentUser->isSuperadmin() || $currentUser->isAdmin()
                ? ['nullable', Rule::exists('business_units', 'id')]
                : ['prohibited'],
            'sector_id' => $currentUser->isSuperadmin() || $currentUser->isAdmin()
                ? ['nullable', Rule::exists('sectors', 'id')]
                : ['prohibited'],
            'assigned_project_ids' => ['sometimes', 'array'],
            'assigned_project_ids.*' => ['integer', Rule::exists('projects', 'id')->where(function ($query) use ($currentUser) {
                if ($currentUser->isAdmin()) {
                    $query->where('business_unit_id', $currentUser->business_unit_id);
                }
            })],
            'risk_pool_permissions' => $canAssignRiskPoolPermissions ? ['nullable', 'array:create,edit,delete'] : ['prohibited'],
            'risk_pool_permissions.create' => [$canAssignRiskPoolPermissions ? 'nullable' : 'prohibited', 'boolean'],
            'risk_pool_permissions.edit' => [$canAssignRiskPoolPermissions ? 'nullable' : 'prohibited', 'boolean'],
            'risk_pool_permissions.delete' => [$canAssignRiskPoolPermissions ? 'nullable' : 'prohibited', 'boolean'],
        ]);

        $prefix = \App\Models\SystemSetting::where('key', 'employee_id_prefix')->first()?->value ?? 'EMP';
        $nextId = User::count() + 1;
        $employeeId = $prefix . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        $mustChangePassword = in_array($validated['role'], ['manager', 'user'], true);

        $businessUnitId = $this->resolveBusinessUnitId($request, $currentUser);
        $sectorId = $this->resolveSectorId($request, $currentUser, $businessUnitId);

        DB::transaction(function () use ($validated, $employeeId, $mustChangePassword, $businessUnitId, $sectorId, $canAssignRiskPoolPermissions) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password'] ?? 'pass1234'),
                'must_change_password' => $mustChangePassword,
                'is_active' => $validated['is_active'] ?? true,
                'employee_id' => $employeeId,
                'business_unit_id' => $businessUnitId,
                'sector_id' => $sectorId,
                'risk_pool_permissions' => $canAssignRiskPoolPermissions
                    ? $this->normalizeRiskPoolPermissions($validated['risk_pool_permissions'] ?? [])
                    : [],
            ]);

            $user->assignedProjects()->sync(
                $user->isUser() ? ($validated['assigned_project_ids'] ?? []) : []
            );
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'User node created successfully.'], 201);
        }

        return redirect()->route('admin.roles.index')->with('success', 'User node created successfully.');
    }

    private function resolveBusinessUnitId(Request $request, $currentUser)
    {
        if ($currentUser->isSuperadmin()) {
            return $request->input('business_unit_id');
        }

        if ($currentUser->isAdmin()) {
            return $currentUser->business_unit_id;
        }

        return null;
    }

    private function resolveSectorId(Request $request, $currentUser, $businessUnitId)
    {
        if ($currentUser->isSuperadmin()) {
            return $request->input('sector_id');
        }

        if ($currentUser->isAdmin() && $businessUnitId) {
            $sectorId = $request->input('sector_id');
            if ($sectorId && Sector::where('id', $sectorId)->where('business_unit_id', $businessUnitId)->exists()) {
                return $sectorId;
            }
        }

        return null;
    }

    public function update(Request $request, User $user)
    {
        $currentUser = $request->user();
        $availableRoles = ['admin', 'manager', 'user'];
        $businessUnitId = $currentUser->isSuperadmin()
            ? $request->input('business_unit_id')
            : ($currentUser->isAdmin() ? $currentUser->business_unit_id : null);
        $canAssignRiskPoolPermissions = $currentUser->isSuperadmin() && $request->input('role') === 'admin';

        if ($currentUser->isSuperadmin()) {
            $availableRoles[] = 'superadmin';
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in($availableRoles)],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8'],
            'password_confirmation' => ['required_with:password', 'same:password'],
            'business_unit_id' => $currentUser->isSuperadmin() || $currentUser->isAdmin()
                ? ($currentUser->isAdmin() ? 'prohibited' : ['nullable', Rule::exists('business_units', 'id')])
                : ['prohibited'],
            'sector_id' => $currentUser->isSuperadmin() || $currentUser->isAdmin()
                ? ['nullable', Rule::exists('sectors', 'id')->where(fn ($query) => $query->where('business_unit_id', $businessUnitId))]
                : ['prohibited'],
            'assigned_project_ids' => ['sometimes', 'array'],
            'assigned_project_ids.*' => ['integer', Rule::exists('projects', 'id')->where(function ($query) use ($currentUser) {
                if ($currentUser->isAdmin()) {
                    $query->where('business_unit_id', $currentUser->business_unit_id);
                }
            })],
            'risk_pool_permissions' => $canAssignRiskPoolPermissions ? ['nullable', 'array:create,edit,delete'] : ['prohibited'],
            'risk_pool_permissions.create' => [$canAssignRiskPoolPermissions ? 'nullable' : 'prohibited', 'boolean'],
            'risk_pool_permissions.edit' => [$canAssignRiskPoolPermissions ? 'nullable' : 'prohibited', 'boolean'],
            'risk_pool_permissions.delete' => [$canAssignRiskPoolPermissions ? 'nullable' : 'prohibited', 'boolean'],
        ]);

        if (!$currentUser->isSuperadmin() && $validated['role'] === 'superadmin') {
            abort(403, 'Only Superadmin can grant the Superadmin role.');
        }

        $sectorId = $this->resolveSectorId($request, $currentUser, $businessUnitId);

        DB::transaction(function () use ($validated, $user, $request, $businessUnitId, $sectorId, $currentUser, $canAssignRiskPoolPermissions) {
            $previousRole = $user->role;
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
                'business_unit_id' => $businessUnitId,
                'sector_id' => $sectorId,
            ]);

            if (!$user->isAdmin()) {
                $user->risk_pool_permissions = [];
            } elseif ($currentUser->isSuperadmin() && $canAssignRiskPoolPermissions) {
                $user->risk_pool_permissions = $this->normalizeRiskPoolPermissions($validated['risk_pool_permissions'] ?? []);
            }

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            if (!$user->isUser()) {
                $user->assignedProjects()->sync([]);
            } elseif ($request->exists('assigned_project_ids') || $previousRole !== 'user') {
                $user->assignedProjects()->sync($validated['assigned_project_ids'] ?? []);
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'User profile updated successfully.']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'User profile updated successfully.');
    }

    private function normalizeRiskPoolPermissions(array $permissions): array
    {
        return collect(['create', 'edit', 'delete'])
            ->mapWithKeys(fn (string $action) => [$action => (bool) ($permissions[$action] ?? false)])
            ->all();
    }

    public function destroy(User $user)
    {
        abort_unless(auth()->user()->isSuperadmin()
            || (auth()->user()->isAdmin() && auth()->user()->business_unit_id !== null
                && $user->business_unit_id !== null
                && (int) $user->business_unit_id === (int) auth()->user()->business_unit_id), 403);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.roles.index')->with('success', 'User deleted successfully.');
    }
}
