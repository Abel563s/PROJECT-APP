<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function roles(Request $request)
    {
        $roles = [
            'admin' => User::where('role', 'admin')->count(),
            'manager' => User::where('role', 'manager')->count(),
            'user' => User::where('role', 'user')->count(),
        ];
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(['admin', 'manager', 'user'])],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        $userQuery = User::with('assignedProjects');

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

        $users = $userQuery->latest()->paginate(15)->withQueryString();
        $projects = $this->assignmentProjects();

        return view('admin.roles.index', compact('roles', 'users', 'projects'));
    }

    public function edit(User $user)
    {
        $projects = $this->assignmentProjects();

        if (request()->has('partial')) {
            return view('admin.users.edit-modal', compact('user', 'projects'));
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

            return view('admin.users.create-modal', compact('projects'));
        }
        return redirect()->route('admin.roles.index');
    }

    private function assignmentProjects()
    {
        return Project::query()
            ->get(['id', 'project_code', 'project_name'])
            ->sortBy(
                fn (Project $project) => $project->project_code ?: $project->project_name,
                SORT_NATURAL | SORT_FLAG_CASE
            )
            ->values();
    }

    public function store(Request $request)
    {
        $isAdmin = $request->input('role') === 'admin';
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'role' => ['required', Rule::in(['admin', 'manager', 'user'])],
            'password' => $isAdmin
                ? ['required', 'string', 'min:8', 'confirmed']
                : ['prohibited'],
            'password_confirmation' => $isAdmin ? ['required'] : ['prohibited'],
            'is_active' => ['nullable', 'boolean'],
            'assigned_project_ids' => ['sometimes', 'array'],
            'assigned_project_ids.*' => ['integer', Rule::exists('projects', 'id')],
        ]);

        $prefix = \App\Models\SystemSetting::where('key', 'employee_id_prefix')->first()?->value ?? 'EMP';
        $nextId = User::count() + 1;
        $employeeId = $prefix . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        $mustChangePassword = in_array($validated['role'], ['manager', 'user'], true);

        DB::transaction(function () use ($validated, $employeeId, $mustChangePassword) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password'] ?? 'pass1234'),
                'must_change_password' => $mustChangePassword,
                'is_active' => $validated['is_active'] ?? true,
                'employee_id' => $employeeId,
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

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'manager', 'user'])],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'assigned_project_ids' => ['sometimes', 'array'],
            'assigned_project_ids.*' => ['integer', Rule::exists('projects', 'id')],
        ]);

        DB::transaction(function () use ($validated, $user, $request) {
            $previousRole = $user->role;
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
            ]);

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

        return redirect()->route('admin.roles.index')->with('success', 'User profile updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.roles.index')->with('success', 'User deleted successfully.');
    }
}
