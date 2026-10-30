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
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isSuperadmin()), 403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        $active = RiskCategory::ordered()->get();
        $defaults = ProjectRiskPool::CATEGORIES;

        return view('admin.projects.risk-pool.category-manage', compact('active', 'defaults'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255', 'unique:risk_categories,name'],
            'code'           => ['nullable', 'string', 'max:255', function ($attr, $val, $fail) {
                if ($val !== null && $val !== '' && !ctype_digit((string) $val)) {
                    $fail('The category code must be numeric (used as the Risk ID prefix).');
                }
            }],
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
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name'           => ['sometimes', 'required', 'string', 'max:255', Rule::unique('risk_categories', 'name')->ignore($risk_category->id)],
            'code'           => ['nullable', 'string', 'max:255', function ($attr, $val, $fail) {
                if ($val !== null && $val !== '' && !ctype_digit((string) $val)) {
                    $fail('The category code must be numeric (used as the Risk ID prefix).');
                }
            }],
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
        });

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json(['message' => 'Category updated.', 'refresh' => true], 200);
        }

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Request $request, RiskCategory $risk_category)
    {
        $this->authorizeAdmin();

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
