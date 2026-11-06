<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRiskPool;
use App\Models\RiskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RiskCategoryController extends Controller
{
    private function authorizeRiskCategoryAction(?string $action = null): void
    {
        $user = auth()->user();
        if ($user?->isSuperadmin()) {
            return;
        }

        $hasRiskPoolAccess = $user && collect(['create', 'edit', 'delete'])
            ->contains(fn (string $permission) => $user->canManageRiskPool($permission));
        abort_unless($hasRiskPoolAccess, 403);

        if ($action !== null) {
            abort_unless($user->canManageRiskPool($action), 403, 'You do not have permission to '.$action.' risk categories.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeRiskCategoryAction();

        if ($request->query('format') === 'options') {
            return response()->json([
                'categories' => RiskCategory::active()
                    ->ordered()
                    ->get(['name', 'risk_type', 'code'])
                    ->map(fn (RiskCategory $category) => [
                        'name' => $category->name,
                        'risk_type' => $category->risk_type,
                        'code' => $category->code,
                    ])
                    ->values(),
            ]);
        }

        $active = RiskCategory::ordered()->get();
        $defaults = ProjectRiskPool::CATEGORIES;
        $canCreateRiskCategory = auth()->user()->canManageRiskPool('create');
        $canEditRiskCategory = auth()->user()->canManageRiskPool('edit');
        $canDeleteRiskCategory = auth()->user()->canManageRiskPool('delete');

        return view('admin.projects.risk-pool.category-manage', compact('active', 'defaults', 'canCreateRiskCategory', 'canEditRiskCategory', 'canDeleteRiskCategory'));
    }

    public function store(Request $request)
    {
        $this->authorizeRiskCategoryAction('create');

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255', 'unique:risk_categories,name'],
            'code'           => ['required', 'string', 'max:255', 'regex:/^\d+$/', 'unique:risk_categories,code'],
            'risk_type'      => ['required', Rule::in(ProjectRiskPool::RISK_TYPES)],
            'display_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $validated['is_active'] : true;

        DB::transaction(function () use ($validated) {
            $category = RiskCategory::create($validated);
        });

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json(['message' => 'Category added.', 'refresh' => true], 201);
        }

        return back()->with('success', 'Category added.');
    }

    public function update(Request $request, RiskCategory $risk_category)
    {
        $this->authorizeRiskCategoryAction('edit');

        $validated = $request->validate([
            'name'           => ['sometimes', 'required', 'string', 'max:255', Rule::unique('risk_categories', 'name')->ignore($risk_category->id)],
            'code'           => ['sometimes', 'required', 'string', 'max:255', 'regex:/^\d+$/', Rule::unique('risk_categories', 'code')->ignore($risk_category->id)],
            'risk_type'      => ['sometimes', 'required', Rule::in(ProjectRiskPool::RISK_TYPES)],
            'display_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        if (array_key_exists('is_active', $validated)) {
            $validated['is_active'] = (bool) $validated['is_active'];
        }

        // Renaming a category cascades to all pooled risks that reference it.
        $originalName = $risk_category->getOriginal('name');

        DB::transaction(function () use ($risk_category, $validated, $originalName) {
            if (array_key_exists('name', $validated) && $validated['name'] !== $originalName) {
                ProjectRiskPool::where('risk_category', $originalName)->update(['risk_category' => $validated['name']]);
            }

            $risk_category->update($validated);

            if (array_key_exists('risk_type', $validated)) {
                ProjectRiskPool::where('risk_category', $risk_category->name)
                    ->update(['risk_type' => $validated['risk_type']]);
            }
        });

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json(['message' => 'Category updated.', 'refresh' => true], 200);
        }

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Request $request, RiskCategory $risk_category)
    {
        $this->authorizeRiskCategoryAction('delete');

        $references = ProjectRiskPool::where('risk_category', $risk_category->name)->count();

        if ($references > 0) {
            $message = 'This category is used by ' . $references . ' risk record(s). Deactivate it instead of deleting.';

            if ($request->expectsJson() || $request->isXmlHttpRequest()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $risk_category->delete();

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json(['message' => 'Category removed.', 'refresh' => true], 200);
        }

        return back()->with('success', 'Category removed.');
    }
}
