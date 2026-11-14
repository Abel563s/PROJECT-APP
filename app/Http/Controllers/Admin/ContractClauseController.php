<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContractClauseController extends Controller
{
    private function authorizeAccess(?string $action = null): void
    {
        $user = auth()->user();
        $hasAccess = $user && collect(['create', 'edit', 'delete'])
            ->contains(fn ($permission) => $user->canManageRiskPool($permission));
        abort_unless($hasAccess, 403, 'You do not have access to contract clauses.');
        if ($action) abort_unless($user->canManageRiskPool($action), 403, 'You do not have permission to '.$action.' contract clauses.');
    }

    public function index(Request $request)
    {
        $query = ContractClause::query()->orderBy('row_number', 'asc');
        if ($request->filled('search')) {
            $term = trim($request->string('search')->toString());
            $query->where(function ($builder) use ($term) {
                $builder->where('row_number', 'like', '%'.$term.'%')
                    ->orWhere('item', 'like', '%'.$term.'%')
                    ->orWhere('ppa', 'like', '%'.$term.'%')
                    ->orWhere('fidic_red', 'like', '%'.$term.'%')
                    ->orWhere('fidic_yellow', 'like', '%'.$term.'%')
                    ->orWhere('fidic_silver', 'like', '%'.$term.'%')
                    ->orWhere('planner_explanation', 'like', '%'.$term.'%');
            });
        }
        $clauses = $query->paginate(15)->withQueryString();

        return view('admin.projects.risk-pool.contract-clauses', [
            'clauses' => $clauses,
            'total' => ContractClause::count(),
            'canCreateClauses' => auth()->user()->canManageRiskPool('create'),
            'canEditClauses' => auth()->user()->canManageRiskPool('edit'),
            'canDeleteClauses' => auth()->user()->canManageRiskPool('delete'),
        ]);
    }

    public function create()
    {
        $this->authorizeAccess();
        abort_unless(auth()->user()->canManageRiskPool('create') || auth()->user()->canManageRiskPool('edit'), 403);
        $canCreateClauses = auth()->user()->canManageRiskPool('create');
        $canEditClauses = auth()->user()->canManageRiskPool('edit');
        $existingClauses = ContractClause::orderBy('row_number')->get();
        $nextRowNumber = ((int) ContractClause::max('row_number')) + 1;

        return view('admin.projects.risk-pool.contract-clauses.form-modal', compact('canCreateClauses', 'canEditClauses', 'existingClauses', 'nextRowNumber'))->render();
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();
        $validated = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.id' => ['nullable', 'integer', 'exists:contract_clauses,id'],
            'rows.*.row_number' => ['required', 'integer', 'min:1'],
            'rows.*.item' => ['required', 'string', 'max:1000'],
            'rows.*.ppa' => ['nullable', 'string', 'max:255'],
            'rows.*.fidic_red' => ['nullable', 'string', 'max:255'],
            'rows.*.fidic_yellow' => ['nullable', 'string', 'max:255'],
            'rows.*.fidic_silver' => ['nullable', 'string', 'max:255'],
            'rows.*.planner_explanation' => ['required', 'string', 'max:50000'],
        ]);

        $rows = $validated['rows'];
        $errors = [];
        $seenNumbers = [];
        $seenIds = [];
        foreach ($rows as $index => $row) {
            $id = $row['id'] ?? null;
            abort_unless($request->user()->canManageRiskPool($id ? 'edit' : 'create'), 403);
            if (isset($seenNumbers[$row['row_number']])) $errors["rows.{$index}.row_number"][] = 'This row number is duplicated in the form.';
            $seenNumbers[$row['row_number']] = true;
            if ($id && isset($seenIds[$id])) $errors["rows.{$index}.id"][] = 'This clause appears more than once.';
            if ($id) $seenIds[$id] = true;

            $conflict = ContractClause::where('row_number', $row['row_number'])->first();
            if ($conflict && (int) $conflict->id !== (int) $id) {
                $errors["rows.{$index}.row_number"][] = 'This row number is already in use.';
            }
        }
        if ($errors) throw ValidationException::withMessages($errors);

        $created = 0;
        $updated = 0;
        DB::transaction(function () use ($rows, $request, &$created, &$updated) {
            foreach ($rows as $row) {
                $id = $row['id'] ?? null;
                unset($row['id']);
                if ($id) {
                    $clause = ContractClause::findOrFail($id);
                    if ($clause->fill($row)->isDirty()) { $clause->save(); $updated++; }
                } else {
                    $row['created_by'] = $request->user()->id;
                    ContractClause::create($row);
                    $created++;
                }
            }
        });

        return response()->json(['message' => $created.' new and '.$updated.' existing clause(s) saved.', 'created' => $created, 'updated' => $updated]);
    }

    public function show(ContractClause $clause)
    {
        return view('admin.projects.risk-pool.contract-clauses.view-modal', compact('clause'))->render();
    }

    public function edit(ContractClause $clause)
    {
        $this->authorizeAccess('edit');
        $canCreateClauses = auth()->user()->canManageRiskPool('create');
        $canEditClauses = true;
        $existingClauses = collect();
        $nextRowNumber = $clause->row_number;

        return view('admin.projects.risk-pool.contract-clauses.form-modal', compact('clause', 'canCreateClauses', 'canEditClauses', 'existingClauses', 'nextRowNumber'))->render();
    }

    public function update(Request $request, ContractClause $clause)
    {
        $this->authorizeAccess('edit');
        $validated = $request->validate([
            'row_number' => ['required', 'integer', 'min:1', Rule::unique('contract_clauses', 'row_number')->ignore($clause->id)],
            'item' => ['required', 'string', 'max:1000'],
            'ppa' => ['nullable', 'string', 'max:255'],
            'fidic_red' => ['nullable', 'string', 'max:255'],
            'fidic_yellow' => ['nullable', 'string', 'max:255'],
            'fidic_silver' => ['nullable', 'string', 'max:255'],
            'planner_explanation' => ['required', 'string', 'max:50000'],
        ]);
        $clause->update($validated);

        return response()->json(['message' => 'Contract clause updated successfully.']);
    }

    public function destroy(ContractClause $clause)
    {
        $this->authorizeAccess('delete');
        $clause->delete();
        return response()->json(['message' => 'Contract clause deleted successfully.']);
    }
}
