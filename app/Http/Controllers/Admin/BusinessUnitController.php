<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BusinessUnitController extends Controller
{
    private function authorizeSuperadmin(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->isSuperadmin(),
            403,
            'Only Superadmin can manage Business Units and Sectors.'
        );
    }

    public function index(Request $request)
    {
        $this->authorizeSuperadmin();

        $businessUnits = BusinessUnit::with('activeSectors.users', 'users')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
                $q->where('is_active', $request->status === 'active');
            })
            ->latest()
            ->get();

        return response()->json([
            'business_units' => $businessUnits->map(function ($bu) {
                return [
                    'id' => $bu->id,
                    'name' => $bu->name,
                    'code' => $bu->code,
                    'description' => $bu->description,
                    'is_active' => $bu->is_active,
                    'sectors_count' => $bu->activeSectors->count(),
                    'users_count' => $bu->users->count(),
                    'sectors' => $bu->activeSectors->map(fn ($s) => [
                        'id' => $s->id,
                        'name' => $s->name,
                        'description' => $s->description,
                        'is_active' => $s->is_active,
                        'users_count' => $s->users->count(),
                    ])->values(),
                ];
            }),
        ]);
    }

    public function manageHtml(Request $request)
    {
        $this->authorizeSuperadmin();

        $businessUnits = BusinessUnit::with(['activeSectors' => fn ($q) => $q->withCount('users')])
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'description', 'is_active']);

        return view('admin.business-units.manage', compact('businessUnits'));
    }

    public function show(BusinessUnit $businessUnit)
    {
        $this->authorizeSuperadmin();

        return response()->json([
            'id' => $businessUnit->id,
            'name' => $businessUnit->name,
            'code' => $businessUnit->code,
            'description' => $businessUnit->description,
            'is_active' => $businessUnit->is_active,
        ]);
    }

    public function showSector(Sector $sector)
    {
        $this->authorizeSuperadmin();

        return response()->json([
            'id' => $sector->id,
            'business_unit_id' => $sector->business_unit_id,
            'name' => $sector->name,
            'description' => $sector->description,
            'is_active' => $sector->is_active,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeSuperadmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:business_units,name'],
            'code' => ['nullable', 'string', 'max:50', 'unique:business_units,code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $bu = BusinessUnit::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Business Unit created successfully.',
                'business_unit' => $bu,
            ], 201);
        }

        return back()->with('success', 'Business Unit created successfully.');
    }

    public function update(Request $request, BusinessUnit $businessUnit)
    {
        $this->authorizeSuperadmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('business_units', 'name')->ignore($businessUnit->id)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('business_units', 'code')->ignore($businessUnit->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $businessUnit->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Business Unit updated successfully.',
                'business_unit' => $businessUnit->fresh(),
            ]);
        }

        return back()->with('success', 'Business Unit updated successfully.');
    }

    public function destroy(Request $request, BusinessUnit $businessUnit)
    {
        $this->authorizeSuperadmin();

        $usersCount = $businessUnit->users()->count();
        $sectorsCount = $businessUnit->sectors()->count();

        if ($usersCount > 0 || $sectorsCount > 0) {
            $businessUnit->update(['is_active' => false]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Business Unit deactivated. ' . ($usersCount > 0 ? "{$usersCount} user(s) affected. " : '') . ($sectorsCount > 0 ? "{$sectorsCount} sector(s) affected." : ''),
                    'deactivated' => true,
                ]);
            }

            return back()->with('success', 'Business Unit deactivated.');
        }

        $businessUnit->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Business Unit deleted.']);
        }

        return back()->with('success', 'Business Unit deleted.');
    }

    public function toggleStatus(Request $request, BusinessUnit $businessUnit)
    {
        $this->authorizeSuperadmin();

        $businessUnit->update(['is_active' => !$businessUnit->is_active]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $businessUnit->is_active ? 'Business Unit activated.' : 'Business Unit deactivated.',
                'is_active' => $businessUnit->is_active,
            ]);
        }

        return back()->with('success', $businessUnit->is_active ? 'Business Unit activated.' : 'Business Unit deactivated.');
    }

    public function sectors(Request $request, BusinessUnit $businessUnit)
    {
        $this->authorizeSuperadmin();

        $sectors = $businessUnit->sectors()
            ->withCount('users')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
                $q->where('is_active', $request->status === 'active');
            })
            ->latest()
            ->get();

        return response()->json([
            'sectors' => $sectors->map(fn ($s) => [
                'id' => $s->id,
                'business_unit_id' => $s->business_unit_id,
                'name' => $s->name,
                'description' => $s->description,
                'is_active' => $s->is_active,
                'users_count' => $s->users_count,
            ]),
        ]);
    }

    public function storeSector(Request $request)
    {
        $this->authorizeSuperadmin();

        $validated = $request->validate([
            'business_unit_id' => ['required', Rule::exists('business_units', 'id')],
            'name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($request) {
                $exists = Sector::where('business_unit_id', $request->input('business_unit_id'))
                    ->where('name', $value)
                    ->exists();
                if ($exists) {
                    $fail('A Sector with this name already exists in the selected Business Unit.');
                }
            }],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ], [
            'business_unit_id.required' => 'A Business Unit must be selected.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $bu = BusinessUnit::findOrFail($validated['business_unit_id']);
        abort_if(!$bu->is_active, 422, 'Cannot create a Sector under an inactive Business Unit.');

        $validated['name'] = trim($validated['name']);

        $sector = DB::transaction(function () use ($validated) {
            return Sector::create($validated);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sector created successfully.',
                'sector' => $sector->load('businessUnit'),
            ], 201);
        }

        return back()->with('success', 'Sector created successfully.');
    }

    public function updateSector(Request $request, Sector $sector)
    {
        $this->authorizeSuperadmin();

        $validated = $request->validate([
            'business_unit_id' => ['required', Rule::exists('business_units', 'id')],
            'name' => ['required', 'string', 'max:255', function ($attr, $val, $fail) use ($sector, $request) {
                $exists = Sector::where('business_unit_id', $request->input('business_unit_id'))
                    ->where('name', $val)
                    ->where('id', '!=', $sector->id)
                    ->exists();
                if ($exists) {
                    $fail('A Sector with this name already exists in the selected Business Unit.');
                }
            }],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', $sector->is_active);

        $sector->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sector updated successfully.',
                'sector' => $sector->fresh()->load('businessUnit'),
            ]);
        }

        return back()->with('success', 'Sector updated successfully.');
    }

    public function destroySector(Request $request, Sector $sector)
    {
        $this->authorizeSuperadmin();

        $usersCount = $sector->users()->count();

        if ($usersCount > 0) {
            $sector->update(['is_active' => false]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => "Sector deactivated. {$usersCount} user(s) affected.",
                    'deactivated' => true,
                ]);
            }

            return back()->with('success', 'Sector deactivated.');
        }

        $sector->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Sector deleted.']);
        }

        return back()->with('success', 'Sector deleted.');
    }

    public function toggleSectorStatus(Request $request, Sector $sector)
    {
        $this->authorizeSuperadmin();

        $sector->update(['is_active' => !$sector->is_active]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $sector->is_active ? 'Sector activated.' : 'Sector deactivated.',
                'is_active' => $sector->is_active,
            ]);
        }

        return back()->with('success', $sector->is_active ? 'Sector activated.' : 'Sector deactivated.');
    }

    public function dropdownData(Request $request)
    {
        $this->authorizeSuperadmin();

        $businessUnits = BusinessUnit::with('activeSectors:id,business_unit_id,name,is_active')
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'is_active']);

        $sectors = Sector::with('businessUnit')
            ->active()
            ->orderBy('name')
            ->get(['id', 'business_unit_id', 'name', 'is_active']);

        return response()->json([
            'business_units' => $businessUnits,
            'sectors' => $sectors,
        ]);
    }

    public function adminDropdownData(Request $request)
    {
        $user = $request->user();
        abort_unless($user->isSuperadmin() || $user->isAdmin(), 403);

        if ($user->isSuperadmin()) {
            $businessUnits = BusinessUnit::with('activeSectors:id,business_unit_id,name,is_active')
                ->active()
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'is_active']);
        } else {
            $businessUnits = BusinessUnit::with('activeSectors:id,business_unit_id,name,is_active')
                ->where('id', $user->business_unit_id)
                ->active()
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'is_active']);
        }

        return response()->json([
            'business_units' => $businessUnits,
        ]);
    }
}
