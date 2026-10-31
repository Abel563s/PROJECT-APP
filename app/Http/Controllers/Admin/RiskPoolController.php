<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRiskPool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RiskPoolController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isSuperadmin()), 403);
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'risk_category' => 'nullable|string',
            'overall_impact_level' => ['nullable', Rule::in(ProjectRiskPool::IMPACT_LEVELS)],
            'sort' => ['nullable', Rule::in(['risk_id', 'risk_category', 'specific_risk', 'overall_impact_level', 'impact_on_timeline', 'impact_on_cost', 'updated_at', 'created_at'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'view' => ['nullable', Rule::in(['cards', 'table'])],
            'partial' => 'nullable|string',
        ]);

        $filters = [
            'search' => $request->input('search'),
            'risk_category' => $request->input('risk_category'),
            'overall_impact_level' => $request->input('overall_impact_level'),
        ];

        $query = ProjectRiskPool::query();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $query->applyFilters($filters);

        $sort = $request->input('sort', 'risk_id');
        $direction = $request->input('direction', 'asc');
        $query->applySort($sort, $direction);

        $perPage = $request->filled('per_page') ? max(5, min(100, (int) $request->per_page)) : 15;
        $risks = $query->paginate($perPage)->withQueryString();

        $stats = ProjectRiskPool::stats();
        $isAdmin = auth()->user()->isAdmin() || auth()->user()->isSuperadmin();
        $categories = ProjectRiskPool::availableCategories();
        $viewMode = 'table';

        if ($request->filled('partial')) {
            $view = match ($request->input('partial')) {
                'summary' => view('admin.projects.risk-pool.partials._summary-cards', compact('stats', 'isAdmin', 'filters'))->render(),
                'cards' => view('admin.projects.risk-pool.partials._cards', compact('risks', 'isAdmin'))->render(),
                'table' => view('admin.projects.risk-pool.partials._table', compact('risks', 'isAdmin'))->render(),
                default => null,
            };

            return response()->json(['html' => $view]);
        }

        return view('admin.projects.risk-pool.index', compact('risks', 'stats', 'isAdmin', 'categories', 'viewMode', 'sort', 'direction', 'filters'));
    }

    public function eot(Request $request)
    {
        $isAdmin = auth()->user()->isAdmin() || auth()->user()->isSuperadmin();
        $stats = ProjectRiskPool::stats();

        return view('admin.projects.risk-pool.eot', compact('isAdmin', 'stats'));
    }

    public function contractClauses(Request $request)
    {
        $isAdmin = auth()->user()->isAdmin() || auth()->user()->isSuperadmin();
        $stats = ProjectRiskPool::stats();

        return view('admin.projects.risk-pool.contract-clauses', compact('isAdmin', 'stats'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $existingRisks = ProjectRiskPool::ordered()->get();

        return view('admin.projects.risk-pool.create-modal', compact('existingRisks'))->render();
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $this->validateRiskData($request);

        $validated['created_by'] = $request->user()->id;

        DB::transaction(function () use ($validated) {
            ProjectRiskPool::create($validated);
        });

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'message' => 'Risk registered successfully.',
                'refresh' => true,
            ], 201);
        }

        return redirect()->route('admin.projects.risk-pool.index')->with('success', 'Risk registered successfully.');
    }

    /**
     * Persist multiple risk rows (new + edited) in a single transaction.
     */
    public function bulkStore(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.id' => ['nullable', 'integer', 'exists:project_risk_pools,id'],
            'rows.*.risk_id' => ['required', 'string', 'max:255'],
            'rows.*.risk_category' => ['required', 'string', 'max:255', function ($attr, $val, $fail) {
                $allowed = ProjectRiskPool::availableCategories();
                if (! in_array($val, $allowed, true)) {
                    $fail('The selected risk category is invalid.');
                }
            }],
            'rows.*.specific_risk' => ['required', 'string', 'max:255'],
            'rows.*.detailed_risk_explanation' => ['nullable', 'string'],
            'rows.*.impact_on_timeline' => ['nullable', Rule::in(ProjectRiskPool::IMPACT_LEVELS)],
            'rows.*.impact_on_cost' => ['nullable', Rule::in(ProjectRiskPool::IMPACT_LEVELS)],
            'rows.*.overall_impact_level' => ['nullable', Rule::in(ProjectRiskPool::IMPACT_LEVELS)],
            'rows.*.stakeholder' => ['nullable', 'string'],
            'rows.*.recommended_mitigation_measures' => ['nullable', 'string'],
        ]);

        $rows = $data['rows'];
        $userId = $request->user()->id;

        $seenIds = [];
        $conflicts = [];
        $rowErrors = [];

        // Detect duplicate Risk IDs within the batch and against existing records.
        foreach ($rows as $index => $row) {
            $riskId = $row['risk_id'];
            $rowId = $row['id'] ?? null;

            if (array_key_exists($riskId, $seenIds)) {
                $conflicts[] = ['row' => $index, 'risk_id' => $riskId];
                $rowErrors["rows.{$index}.risk_id"][] = "Duplicate Risk ID '{$riskId}' within this batch.";

                continue;
            }

            $existing = ProjectRiskPool::where('risk_id', $riskId)->first();
            if ($existing && $existing->id != $rowId) {
                $conflicts[] = ['row' => $index, 'risk_id' => $riskId];
                $rowErrors["rows.{$index}.risk_id"][] = "Risk ID '{$riskId}' is already in use by another record.";

                continue;
            }

            $seenIds[$riskId] = $rowId;
        }

        if (! empty($rowErrors)) {
            throw ValidationException::withMessages($rowErrors)
                ->errorBag('default');
        }

        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($rows, $userId, &$created, &$updated) {
            foreach ($rows as $row) {
                $id = $row['id'] ?? null;
                unset($row['id']);

                $payload = [
                    'risk_id' => $row['risk_id'] ?? null,
                    'risk_category' => $row['risk_category'] ?? null,
                    'specific_risk' => $row['specific_risk'] ?? null,
                    'detailed_risk_explanation' => $row['detailed_risk_explanation'] ?? null,
                    'impact_on_timeline' => $row['impact_on_timeline'] ?? null,
                    'impact_on_cost' => $row['impact_on_cost'] ?? null,
                    'overall_impact_level' => $row['overall_impact_level'] ?? null,
                    'stakeholder' => $row['stakeholder'] ?? null,
                    'recommended_mitigation_measures' => $row['recommended_mitigation_measures'] ?? null,
                ];

                if ($id) {
                    $existing = ProjectRiskPool::findOrFail($id);
                    $existing->fill($payload);
                    if ($existing->isDirty()) {
                        $existing->save();
                        $updated++;
                    }
                } else {
                    $payload['created_by'] = $userId;
                    ProjectRiskPool::create($payload);
                    $created++;
                }
            }
        });

        return response()->json([
            'message' => __(':created new and :updated existing risk(s) saved.', ['created' => $created, 'updated' => $updated]),
            'created' => $created,
            'updated' => $updated,
            'refresh' => true,
        ], 200);
    }

    public function show(Request $request, ProjectRiskPool $risk)
    {
        if ($request->has('partial')) {
            return view('admin.projects.risk-pool.view-modal', compact('risk'))->render();
        }

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json($risk);
        }

        return view('admin.projects.risk-pool.view-modal', compact('risk'));
    }

    public function edit(ProjectRiskPool $risk)
    {
        $this->authorizeAdmin();

        return view('admin.projects.risk-pool.edit-modal', compact('risk'))->render();
    }

    public function update(Request $request, ProjectRiskPool $risk)
    {
        $this->authorizeAdmin();

        $validated = $this->validateRiskData($request, $risk->id);

        DB::transaction(function () use ($risk, $validated) {
            $risk->update($validated);
        });

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'message' => 'Risk updated successfully.',
                'refresh' => true,
            ], 200);
        }

        return redirect()->route('admin.projects.risk-pool.index')->with('success', 'Risk updated successfully.');
    }

    public function destroy(Request $request, ProjectRiskPool $risk)
    {
        $this->authorizeAdmin();

        $risk->delete();

        if ($request->expectsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'message' => 'Risk removed from the risk pool.',
                'refresh' => true,
            ], 200);
        }

        return redirect()->route('admin.projects.risk-pool.index')->with('success', 'Risk removed from the risk pool.');
    }

    public function suggestId(Request $request)
    {
        $this->authorizeAdmin();

        $category = $request->query('category');

        return response()->json([
            'risk_id' => ProjectRiskPool::suggestNextRiskId($category ?? ''),
        ]);
    }

    /**
     * Shared validation ruleset for a single risk record.
     */
    private function validateRiskData(Request $request, $ignoreId = null): array
    {
        $uniqueRule = $ignoreId
            ? Rule::unique('project_risk_pools', 'risk_id')->ignore($ignoreId)
            : Rule::unique('project_risk_pools', 'risk_id');

        return $request->validate([
            'risk_id' => ['required', 'string', 'max:255', $uniqueRule],
            'risk_category' => ['required', 'string', Rule::in(ProjectRiskPool::availableCategories())],
            'specific_risk' => 'required|string|max:255',
            'detailed_risk_explanation' => 'nullable|string',
            'impact_on_timeline' => ['nullable', Rule::in(ProjectRiskPool::IMPACT_LEVELS)],
            'impact_on_cost' => ['nullable', Rule::in(ProjectRiskPool::IMPACT_LEVELS)],
            'overall_impact_level' => ['nullable', Rule::in(ProjectRiskPool::IMPACT_LEVELS)],
            'stakeholder' => 'nullable|string',
            'recommended_mitigation_measures' => 'nullable|string',
        ]);
    }
}
