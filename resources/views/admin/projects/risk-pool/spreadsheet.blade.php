@php
    use Illuminate\Support\Str;

    $mode = $mode ?? 'create';
    $risk = $risk ?? null;
    $categories = $categories ?? \App\Models\ProjectRiskPool::availableCategories();

    $emptyRow = [
        'id'                            => null,
        'risk_id'                       => '',
        'risk_category'                 => '',
        'specific_risk'                 => '',
        'impact_on_timeline'            => '',
        'impact_on_cost'                => '',
        'overall_impact_level'          => '',
        'stakeholder'                   => '',
    ];

    $existingRows = [];
    if ($mode === 'create') {
        foreach (($existingRisks ?? []) as $existing) {
            $existingRows[] = array_merge($emptyRow, $existing->only(array_keys($emptyRow)));
        }
    }

    $initialRows = $mode === 'edit' && $risk
        ? [array_merge($emptyRow, $risk->only(array_keys($emptyRow)))]
        : $existingRows;

    $state = [
        'mode'        => $mode,
        'riskId'      => $risk ? $risk->id : null,
        'categories'  => $categories,
        'impactLevels' => \App\Models\ProjectRiskPool::IMPACT_LEVELS,
        'rows'        => $initialRows,
        'routes'      => [
            'suggestId'   => route('admin.projects.risk-pool.suggest-id'),
            'bulkStore'   => route('admin.projects.risk-pool.bulk-store'),
            'categories'  => route('admin.projects.risk-pool.categories.index'),
        ],
        'csrf'        => csrf_token(),
    ];
@endphp

<div class="spreadsheet-editor space-y-3">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button" id="ssAddRow"
                class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-[#00515f]/90 active:scale-95">
                <i data-lucide="plus" class="h-3 w-3"></i> Add Row
            </button>
            <button type="button" id="ssManageCategories"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-600 shadow-sm transition-all hover:border-[#00ADC5] hover:text-[#00ADC5]">
                <i data-lucide="layers" class="h-3 w-3"></i> Manage Categories
            </button>
            <span id="ssRowCount" class="text-[9px] font-black uppercase tracking-widest text-slate-400">
                1 row
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="ssSaveAll"
                class="inline-flex items-center gap-1.5 rounded-xl bg-[#00515f] px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-[#00515f]/20 transition-all hover:bg-[#006572] active:scale-95">
                <i data-lucide="save" class="h-3 w-3"></i> Save All
            </button>
            <button type="button" id="ssCancel"
                class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-black text-slate-600 hover:bg-slate-50">
                Cancel
            </button>
        </div>
    </div>

    <div id="ssErrors"
        class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-[9px] font-bold text-rose-700"
        role="alert"></div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="ss-grid w-full border-collapse text-[10px] font-bold text-slate-800">
            <thead>
                <tr class="bg-slate-50/80">
                    <th class="ss-th ss-col-id border-b border-slate-200 px-2 py-2 text-left">Risk ID</th>
                    <th class="ss-th ss-col-category border-b border-slate-200 px-2 py-2 text-left">Risk Category</th>
                    <th class="ss-th ss-col-specific border-b border-slate-200 px-2 py-2 text-left">Specific Risk</th>
                    <th class="ss-th ss-col-timeline border-b border-slate-200 px-2 py-2 text-left">Timeline</th>
                    <th class="ss-th ss-col-cost border-b border-slate-200 px-2 py-2 text-left">Cost</th>
                    <th class="ss-th ss-col-overall border-b border-slate-200 px-2 py-2 text-left">Quality</th>
                    <th class="ss-th ss-col-stakeholder border-b border-slate-200 px-2 py-2 text-left">Stakeholder</th>
                    <th class="ss-th ss-col-actions border-b border-slate-200 px-2 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="ssGridBody">
            </tbody>
        </table>
    </div>
</div>

<script type="application/json" id="spreadsheetState">{!! json_encode($state, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) !!}</script>
