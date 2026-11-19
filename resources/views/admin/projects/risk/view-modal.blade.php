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
    $poolTimelineDesc = $poolRisk?->impact_on_timeline_description ?? '';
    $poolCost = $poolRisk?->impact_on_cost ?? '';
    $poolCostDesc = $poolRisk?->impact_on_cost_description ?? '';
    $poolQuality = $poolRisk?->overall_impact_level ?? '';
    $poolQualityDesc = $poolRisk?->impact_on_quality_description ?? '';
    $poolOtherImpact = $poolRisk?->other_potential_impact ?? '';
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
        @if($risk->pool_risk_id)
            <div id="poolImpactSummary" class="mt-3">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400 mb-2">From Risk Repository</p>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Time</p>
                        <span class="mt-1 inline-flex rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$poolTimeline] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                            {{ $poolTimeline ?: '—' }}
                        </span>
                        @if($poolTimelineDesc)
                            <p class="mt-1 text-[10px] font-semibold text-slate-700 whitespace-pre-wrap break-words">{{ $poolTimelineDesc }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Cost</p>
                        <span class="mt-1 inline-flex rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$poolCost] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                            {{ $poolCost ?: '—' }}
                        </span>
                        @if($poolCostDesc)
                            <p class="mt-1 text-[10px] font-semibold text-slate-700 whitespace-pre-wrap break-words">{{ $poolCostDesc }}</p>
                        @endif
                    </div>
<div>
    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Quality</p>
    <span class="mt-1 inline-flex rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$poolQuality] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
        {{ $poolQuality ?: '—' }}
    </span>
    @if($poolQualityDesc)
        <p class="mt-1 text-[10px] font-semibold text-slate-700 whitespace-pre-wrap break-words">{{ $poolQualityDesc }}</p>
    @endif
</div>
<div>
    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Other Impact</p>
    <span class="mt-1 inline-flex rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$poolOtherImpact] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
        {{ $poolOtherImpact ?: '—' }}
    </span>
    @if($poolOtherImpact)
        <p class="mt-1 text-[10px] font-semibold text-slate-700 whitespace-pre-wrap break-words">{{ $poolOtherImpact }}</p>
    @endif
</div>
                </div>
            </div>
        @endif
    </div>
    <!-- Lead summary -->
    <div class="bg-slate-50 rounded-xl p-4">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Risk ID &amp; Sub Category</p>
                <p class="text-lg font-black text-slate-900 leading-snug">{{ $risk->pool_risk_id ? $risk->pool_risk_id.' — ' : '' }}{{ $risk->risk_name }}</p>
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
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Project-Specific Risk Statement</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->risk_description ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Affected Project Objective/ Activity</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->activity ?: '---' }}</p>
            </div>
        </div>
    </div>

    <!-- Step 2 - Risk Assessment -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <h4 class="text-sm font-black text-slate-900">2. Risk Assessment</h4>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="{{ $labelClass }}">Likelihood of occurrence =Probability (P)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->likelihood }}/5</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Severity of Impact (I)= Impact (I)</p>
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
                <p class="{{ $labelClass }}">EOT applicability</p>
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $risk->intent_to_claim ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                    {{ $risk->intent_to_claim ? 'Yes' : 'No' }}
                </span>
            </div>
            @if($risk->eotEntry)
                <div>
                    <p class="{{ $labelClass }}">Baseline for EOT</p>
                    <p class="text-xs font-black text-slate-900">{{ $risk->eotEntry->baseOfRisk?->name ?: '---' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="{{ $labelClass }}">EOT ID</p>
                    <p class="whitespace-pre-wrap break-words [overflow-wrap:anywhere] rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[10px] font-bold leading-5 text-slate-700">{{ $risk->eotEntry->eot_id }} — {{ $risk->eotEntry->detail_reason }}</p>
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
                <p class="{{ $labelClass }}">Most Likely Quality Impact (Description)</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->most_likely_quality_impact_description ?: '---' }}</p>
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
                <p class="{{ $labelClass }}">Risk Response Strategy</p>
                <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">
                    {{ $risk->response_strategy ?: '---' }}
                </span>
            </div>
            <div class="col-span-2">
                <p class="{{ $labelClass }}">Existing controls/Preventive and detective controls</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->existing_control ?: '---' }}</p>
            </div>
<div class="col-span-2">
    <p class="{{ $labelClass }}">Residual risk after treatment</p>
    <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->residual_risk ?: '---' }}</p>
</div>
<div class="col-span-2">
    <p class="{{ $labelClass }}">Risk treatment/Mitigation actions</p>
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
                <p class="{{ $labelClass }}">Materialized Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->materialized_date?->format('M d, Y') ?: '---' }}</p>
            </div>
            @if($risk->materialized_date)
            <div>
                <p class="{{ $labelClass }}">Risk Urgency</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->risk_urgency ?: '---' }}</p>
            </div>
            @endif
            <div>
                <p class="{{ $labelClass }}">Risk Action Status</p>
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $statusClasses[$risk->action_status] ?? 'border-slate-200 bg-slate-100 text-slate-500' }}">
                    {{ $risk->action_status ?: '---' }}
                </span>
            </div>
            <div>
                <p class="{{ $labelClass }}">Last Review Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->last_review_date?->format('M d, Y') ?: '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Risk Owner</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->owner_name ?: '---' }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Action Owner</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->action_owner ?: '---' }}</p>
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
                <p class="{{ $labelClass }}">Exposure (Days)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->calculated_exposure_days ?? '---' }}</p>
            </div>
            @if($risk->intent_to_claim)
                <div class="col-span-2 rounded-xl border border-cyan-100 bg-cyan-50/40 p-3">
                    <h5 class="mb-3 text-[9px] font-black uppercase tracking-widest text-cyan-900">EOT Claim Details</h5>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <p class="{{ $labelClass }}">Detail Reason for EOT</p>
                            <p class="whitespace-pre-wrap break-words text-xs font-bold text-slate-800">{{ $risk->detail_reason_for_eot ?: '---' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="{{ $labelClass }}">Project Case Event Description</p>
                            <p class="whitespace-pre-wrap break-words text-xs font-bold text-slate-800">{{ $risk->project_case_event_description ?: '---' }}</p>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="w-full">
                                <p class="{{ $labelClass }}">Contract Document Type</p>
                                <p class="break-words text-xs font-bold text-slate-800 w-full">{{ $risk->contract_document_type ?: '---' }}</p>
                            </div>
                            <div class="w-full">
                                <p class="{{ $labelClass }}">Project Contract Clause</p>
                                <p class="break-words text-xs font-bold text-slate-800 w-full">{{ $risk->project_contract_clause ?: '---' }}</p>
                            </div>
                            <div class="w-full">
                                <p class="{{ $labelClass }}">Reference Contract Clause No.</p>
                                <p class="break-words text-xs font-bold text-slate-800 w-full">{{ $risk->reference_contract_clause_no ?: '---' }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 flex-1 mb-4">
                            <div class="flex-1">
                                <p class="{{ $labelClass }}">Affected Activities</p>
                                <p class="whitespace-pre-wrap break-words text-xs font-bold text-slate-800 w-full">{{ $risk->affected_activities ?: '---' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="{{ $labelClass }}">Notice of delay / Intent to claim</p>
                            <p class="text-xs font-bold text-slate-800">{{ $risk->itc_notification_status ?: '---' }}</p>
                        </div>
                        <div>
                            <p class="{{ $labelClass }}">Notice of delay / Intent to claim Date</p>
                            <p class="text-xs font-bold text-slate-800">{{ $risk->itc_notification_date?->format('d-M-y') ?: '---' }}</p>
                        </div>
                        <div>
                            <p class="{{ $labelClass }}">EOT Status</p>
                            <p class="text-xs font-bold text-slate-800">{{ $risk->eot_status ?: '---' }}</p>
                        </div>
                        <div>
                            <p class="{{ $labelClass }}">EOT Submitted Date</p>
                            <p class="text-xs font-bold text-slate-800">{{ $risk->eot_submitted_date?->format('d-M-y') ?: '---' }}</p>
                        </div>
                        <div>
                            <p class="{{ $labelClass }}">EOT Response Date</p>
                            <p class="text-xs font-bold text-slate-800">{{ $risk->eot_response_date?->format('d-M-y') ?: '---' }}</p>
                        </div>
                        <div>
                            <p class="{{ $labelClass }}">EOT Requested (Days)</p>
                            <p class="text-xs font-bold text-slate-800">{{ $risk->eot_requested_days ?? '---' }}</p>
                        </div>
                        <div>
                            <p class="{{ $labelClass }}">EOT Approved (Days)</p>
                            <p class="text-xs font-bold text-slate-800">{{ $risk->eot_approved_days ?? '---' }}</p>
                        </div>
                    </div>
                </div>
            @endif
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
