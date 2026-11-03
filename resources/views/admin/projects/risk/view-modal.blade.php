@php
    $ratingClasses = [
        'Low'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'Medium'  => 'bg-amber-50 text-amber-700 border-amber-200',
        'High'    => 'bg-orange-50 text-orange-700 border-orange-200',
        'Extreme' => 'bg-rose-50 text-rose-700 border-rose-200',
    ];
    $statusClasses = [
        'Risk'     => 'bg-sky-50 text-sky-700 border-sky-200',
        'Issue'    => 'bg-amber-50 text-amber-700 border-amber-200',
        'Resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
    $typeClasses = [
        'Threat'      => 'bg-rose-50 text-rose-700 border-rose-200',
        'Opportunity' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
    $impactClasses = [
        'High'   => 'bg-rose-50 text-rose-700 border-rose-200',
        'Medium' => 'bg-amber-50 text-amber-700 border-amber-200',
        'Low'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
    $labelClass = 'block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-1';
    $poolTimeline = $poolRisk?->impact_on_timeline ?? '';
    $poolCost = $poolRisk?->impact_on_cost ?? '';
    $poolQuality = $poolRisk?->overall_impact_level ?? '';
@endphp

<div class="space-y-4">
    <div class="risk-project-summary rounded-xl border border-cyan-100 bg-gradient-to-r from-cyan-50/80 to-slate-50 p-4">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm font-black tracking-wide text-slate-900">{{ $risk->project?->project_code ?? '---' }}</p>
                <p class="mt-1 text-[10px] font-semibold text-slate-500">{{ $risk->project?->project_name ?? '---' }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-cyan-200 bg-white px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-cyan-800 shadow-sm">
                <i data-lucide="clock-3" class="h-3 w-3"></i>
                Last updated {{ $risk->updated_at?->format('M d, Y') ?? '---' }}
            </span>
        </div>
    </div>
    <!-- Lead summary -->
    <div class="bg-slate-50 rounded-xl p-4">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Risk Name</p>
                <p class="text-lg font-black text-slate-900 leading-snug">{{ $risk->risk_name }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $typeClasses[$risk->risk_type] ?? 'bg-slate-100 text-slate-500 border-slate-200' }}">
                    {{ $risk->risk_type }}
                </span>
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $statusClasses[$risk->action_status] ?? 'bg-slate-100 text-slate-500 border-slate-200' }}">
                    {{ $risk->action_status }}
                </span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            <div>
                <p class="{{ $labelClass }}">Rating (P × I)</p>
                <span class="inline-flex flex-col items-center rounded-lg border px-2.5 py-1 {{ $ratingClasses[$risk->risk_level] ?? 'bg-slate-100 text-slate-500 border-slate-200' }}">
                    <span class="text-[10px] font-black">{{ $risk->risk_score }}</span>
                    <span class="text-[8px] font-black uppercase tracking-wider">{{ $risk->risk_level }}</span>
                </span>
            </div>
            <div>
                <p class="{{ $labelClass }}">Risk Urgency</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->risk_urgency ?: '---' }}</p>
            </div>
        </div>
        @if($risk->pool_risk_id && ($poolTimeline || $poolCost || $poolQuality))
            <div class="mt-3 grid grid-cols-3 gap-2">
                <div>
                    <p class="{{ $labelClass }}">Impact on Time</p>
                    <span class="mt-1 inline-flex rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$poolTimeline] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                        {{ $poolTimeline ?: '—' }}
                    </span>
                </div>
                <div>
                    <p class="{{ $labelClass }}">Impact on Cost</p>
                    <span class="mt-1 inline-flex rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$poolCost] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                        {{ $poolCost ?: '—' }}
                    </span>
                </div>
                <div>
                    <p class="{{ $labelClass }}">Impact on Quality</p>
                    <span class="mt-1 inline-flex rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$poolQuality] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                        {{ $poolQuality ?: '—' }}
                    </span>
                </div>
            </div>
        @endif
    </div>

    <!-- Step 1 - Risk Identification -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <h4 class="text-sm font-black text-slate-900">1. Risk Identification</h4>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="{{ $labelClass }}">Risk Type</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->risk_type ?: '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Pool Risk Category</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->pool_risk_category ?: '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Pool Risk ID</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->pool_risk_id ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Activity Related to the Risk</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->activity ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Risk Description</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->risk_description ?: '---' }}</p>
            </div>
        </div>
    </div>

    <!-- Step 2 - Risk Assessment -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <h4 class="text-sm font-black text-slate-900">2. Risk Assessment</h4>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="{{ $labelClass }}">Likelihood (P)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->likelihood }}/5</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Severity (I)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->severity }}/5</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Risk Frequency</p>
                <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">
                    {{ $risk->risk_frequency ?: '---' }}
                </span>
            </div>
            <div>
                <p class="{{ $labelClass }}">Risk Urgency</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->risk_urgency ?: '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Intent to Claim</p>
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $risk->intent_to_claim ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                    {{ $risk->intent_to_claim ? 'Yes' : 'No' }}
                </span>
            </div>
            @if($risk->eotEntry)
                <div>
                    <p class="{{ $labelClass }}">Base of Risk</p>
                    <p class="text-xs font-black text-slate-900">{{ $risk->eotEntry->baseOfRisk?->name ?: '---' }}</p>
                </div>
                <div>
                    <p class="{{ $labelClass }}">EOT ID</p>
                    <p class="text-xs font-black text-slate-900">{{ $risk->eotEntry->eot_id }}</p>
                </div>
                <div class="col-span-2">
                    <p class="{{ $labelClass }}">Detail Reason of EOT</p>
                    <p class="truncate rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[10px] font-bold text-slate-700" title="{{ $risk->eotEntry->detail_reason }}">{{ $risk->eotEntry->detail_reason }}</p>
                </div>
            @endif
            <div>
                <p class="{{ $labelClass }}">Most Likely Cost Impact (METB)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->most_likely_cost_impact ? number_format($risk->most_likely_cost_impact, 2) : '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Most Likely Time Impact (Days)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->most_likely_time_impact ?? '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Most Likely Quality Impact (Days)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->most_likely_quality_impact ?? '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Resolution by</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->resolution_by ?: '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Assumed Impact Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->impact_date ? $risk->impact_date->format('M d, Y') : '---' }}</p>
            </div>
        </div>
    </div>

    <!-- Step 3 - Risk Response -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <h4 class="text-sm font-black text-slate-900">3. Risk Response</h4>
        <div class="grid grid-cols-2 gap-3">
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Existing Control</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->existing_control ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Residual Risk</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->residual_risk ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Response Strategy</p>
                <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">
                    {{ $risk->response_strategy ?: '---' }}
                </span>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Mitigation Plan</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->mitigation_plan ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Contingency Plan</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->contingency_plan ?: '---' }}</p>
            </div>
        </div>
    </div>

    <!-- Step 4 - Risk Evaluation -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <h4 class="text-sm font-black text-slate-900">4. Risk Evaluation</h4>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="{{ $labelClass }}">Risk Action Status</p>
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $statusClasses[$risk->action_status] ?? 'border-slate-200 bg-slate-100 text-slate-500' }}">
                    {{ $risk->action_status ?: '---' }}
                </span>
            </div>
            <div>
                <p class="{{ $labelClass }}">Materialized Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->materialized_date?->format('M d, Y') ?: '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Name of Owner</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->owner_name ?: '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Planned Action Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->planned_action_date ? $risk->planned_action_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Actual Action Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->actual_action_date ? $risk->actual_action_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Exposure (Estimated Delay in Days)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->exposure ?? '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Tracking Comments</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->tracking_comments ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Recommendation / Lesson Learned</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->recommendation ?: '---' }}</p>
            </div>
        </div>
    </div>
</div>
