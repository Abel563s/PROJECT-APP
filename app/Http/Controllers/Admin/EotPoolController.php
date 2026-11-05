<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EotCategory;
use App\Models\EotEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EotPoolController extends Controller
{
    private function authorizeEot(?string $action = null): void
    {
        $user = auth()->user();
        if ($user?->isSuperadmin()) {
            return;
        }

        $hasAccess = $user && collect(['create', 'edit', 'delete'])
            ->contains(fn (string $permission) => $user->canManageRiskPool($permission));
        abort_unless($hasAccess, 403, 'You do not have access to the EOT pool.');

        if ($action !== null) {
            abort_unless($user->canManageRiskPool($action), 403, 'You do not have permission to '.$action.' EOT records.');
        }
    }

    public function index(Request $request)
    {
        $query = EotEntry::with(['baseOfRisk', 'creator'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());
            $query->where(function ($builder) use ($search) {
                $builder->where('eot_id', 'like', '%'.$search.'%')
                    ->orWhere('contract_reference_id', 'like', '%'.$search.'%')
                    ->orWhere('detail_reason', 'like', '%'.$search.'%')
                    ->orWhereHas('baseOfRisk', fn ($category) => $category->where('name', 'like', '%'.$search.'%'));
            });
        }

        if ($request->filled('category_id')) {
            $query->where('eot_category_id', $request->integer('category_id'));
        }

        $entries = $query->paginate(15)->withQueryString();
        $categories = EotCategory::active()->ordered()->get();
        $total = EotEntry::count();

        return view('admin.projects.risk-pool.eot', [
            'entries' => $entries,
            'categories' => $categories,
            'total' => $total,
            'canCreateEot' => auth()->user()->canManageRiskPool('create'),
            'canEditEot' => auth()->user()->canManageRiskPool('edit'),
            'canDeleteEot' => auth()->user()->canManageRiskPool('delete'),
            'canManageEotCategories' => collect(['create', 'edit', 'delete'])->contains(fn ($action) => auth()->user()->canManageRiskPool($action)),
        ]);
    }

    public function create()
    {
        $this->authorizeEot();
        abort_unless(auth()->user()->canManageRiskPool('create') || auth()->user()->canManageRiskPool('edit'), 403);

        $categories = EotCategory::ordered()->get();
        $existingEntries = EotEntry::with('baseOfRisk')->orderBy('eot_category_id')->orderBy('eot_id')->get();
        $canCreateEot = auth()->user()->canManageRiskPool('create');
        $canEditEot = auth()->user()->canManageRiskPool('edit');

        return view('admin.projects.risk-pool.eot.form-modal', compact('categories', 'existingEntries', 'canCreateEot', 'canEditEot'))->render();
    }

    public function suggestId(Request $request)
    {
        $this->authorizeEot('create');

        $validated = $request->validate(['category_id' => ['required', 'integer', Rule::exists('eot_categories', 'id')->where('is_active', true)]]);
        $category = EotCategory::findOrFail($validated['category_id']);

        return response()->json(['eot_id' => EotEntry::suggestNextId($category)]);
    }

    public function store(Request $request)
    {
        $this->authorizeEot();

        $validated = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.id' => ['nullable', 'integer', 'exists:eot_entries,id'],
            'rows.*.eot_id' => ['required', 'string', 'max:50'],
            'rows.*.eot_category_id' => ['required', 'integer', Rule::exists('eot_categories', 'id')],
            'rows.*.contract_reference_id' => ['nullable', 'string', 'max:255'],
            'rows.*.detail_reason' => ['required', 'string', 'max:20000'],
        ]);

        $rows = $validated['rows'];
        $seenIds = [];
        $seenRecordIds = [];
        $errors = [];
        foreach ($rows as $index => $row) {
            $recordId = $row['id'] ?? null;
            $action = $recordId ? 'edit' : 'create';
            abort_unless($request->user()->canManageRiskPool($action), 403, 'You do not have permission to '.$action.' EOT records.');

            $key = mb_strtolower($row['eot_id']);
            if (isset($seenIds[$key])) {
                $errors["rows.{$index}.eot_id"][] = 'This EOT ID is duplicated in the form.';
            }
            $seenIds[$key] = true;

            if ($recordId && isset($seenRecordIds[$recordId])) {
                $errors["rows.{$index}.id"][] = 'This EOT record appears more than once.';
            }
            if ($recordId) $seenRecordIds[$recordId] = true;

            $conflict = EotEntry::where('eot_id', $row['eot_id'])->first();
            if ($conflict && (int) $conflict->id !== (int) $recordId) {
                $errors["rows.{$index}.eot_id"][] = 'This EOT ID is already in use.';
            }

            $category = EotCategory::find($row['eot_category_id']);
            if (!$recordId && !$category?->is_active) {
                $errors["rows.{$index}.eot_category_id"][] = 'Select an active base of risk for new EOT records.';
            }
        }
        if ($errors) throw ValidationException::withMessages($errors);

        $created = 0;
        $updated = 0;
        DB::transaction(function () use ($rows, $request, &$created, &$updated) {
            foreach ($rows as $row) {
                $recordId = $row['id'] ?? null;
                $payload = [
                    'eot_id' => $row['eot_id'],
                    'eot_category_id' => $row['eot_category_id'],
                    'contract_reference_id' => $row['contract_reference_id'] ?? null,
                    'detail_reason' => $row['detail_reason'],
                ];
                if ($recordId) {
                    $entry = EotEntry::findOrFail($recordId);
                    if ($entry->fill($payload)->isDirty()) {
                        $entry->save();
                        $updated++;
                    }
                } else {
                    $payload['created_by'] = $request->user()->id;
                    EotEntry::create($payload);
                    $created++;
                }
            }
        });

        return response()->json([
            'message' => $created.' new and '.$updated.' existing EOT record(s) saved.',
            'created' => $created,
            'updated' => $updated,
            'refresh' => true,
        ]);
    }

    public function show(EotEntry $eot)
    {
        $eot->load(['baseOfRisk', 'creator']);
        $canEditEot = auth()->user()->canManageRiskPool('edit');

        return view('admin.projects.risk-pool.eot.view-modal', compact('eot', 'canEditEot'))->render();
    }

    public function edit(EotEntry $eot)
    {
        $this->authorizeEot('edit');

        $eot->load('baseOfRisk');
        $categories = EotCategory::active()->ordered()->get();
        if ($eot->baseOfRisk && !$categories->contains('id', $eot->eot_category_id)) {
            $categories->prepend($eot->baseOfRisk);
        }
        $canCreateEot = auth()->user()->canManageRiskPool('create');
        $canEditEot = true;

        return view('admin.projects.risk-pool.eot.form-modal', compact('eot', 'categories', 'canCreateEot', 'canEditEot'))->render();
    }

    public function update(Request $request, EotEntry $eot)
    {
        $this->authorizeEot('edit');

        $validated = $request->validate([
            'eot_id' => ['required', 'string', 'max:50', Rule::unique('eot_entries', 'eot_id')->ignore($eot->id)],
            'eot_category_id' => ['required', 'integer', Rule::exists('eot_categories', 'id')],
            'contract_reference_id' => ['nullable', 'string', 'max:255'],
            'detail_reason' => ['required', 'string', 'max:20000'],
        ]);
        $eot->update($validated);

        return response()->json(['message' => 'EOT updated successfully.', 'refresh' => true]);
    }

    public function destroy(EotEntry $eot)
    {
        $this->authorizeEot('delete');
        $eot->delete();

        return response()->json(['message' => 'EOT removed successfully.', 'refresh' => true]);
    }

    public function categories()
    {
        $this->authorizeEot();

        $categories = EotCategory::ordered()->get();
        $canCreateCategory = auth()->user()->canManageRiskPool('create');
        $canEditCategory = auth()->user()->canManageRiskPool('edit');
        $canDeleteCategory = auth()->user()->canManageRiskPool('delete');

        return view('admin.projects.risk-pool.eot.category-manage', compact(
            'categories', 'canCreateCategory', 'canEditCategory', 'canDeleteCategory'
        ))->render();
    }

    public function storeCategory(Request $request)
    {
        $this->authorizeEot('create');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:eot_categories,name'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $validated['is_active'] = true;
        $validated['display_order'] = $validated['display_order'] ?? ((int) EotCategory::max('display_order') + 1);
        EotCategory::create($validated);

        return response()->json(['message' => 'EOT category added.']);
    }

    public function updateCategory(Request $request, EotCategory $category)
    {
        $this->authorizeEot('edit');
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('eot_categories', 'name')->ignore($category->id)],
            'display_order' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        if (array_key_exists('display_order', $validated) && $validated['display_order'] === null) {
            unset($validated['display_order']);
        }
        if (array_key_exists('is_active', $validated)) {
            $validated['is_active'] = (bool) $validated['is_active'];
        }

        DB::transaction(fn () => $category->update($validated));

        return response()->json(['message' => 'EOT category updated.']);
    }

    public function destroyCategory(EotCategory $category)
    {
        $this->authorizeEot('delete');
        if (EotEntry::where('eot_category_id', $category->id)->exists()) {
            return response()->json(['message' => 'This category is in use. Deactivate it instead of deleting.'], 422);
        }
        $category->delete();

        return response()->json(['message' => 'EOT category removed.']);
    }
}
