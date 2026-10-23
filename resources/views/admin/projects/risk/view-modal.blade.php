@php
    $ratingClasses = [
        'Low'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'Medium'  => 'bg-amber-50 text-amber-700 border-amber-200',
        'High'    => 'bg-orange-50 text-orange-700 border-orange-200',
        'Extreme' => 'bg-rose-50 text-rose-700 border-rose-200',
    ];
    $statusClasses = [
        'Open'       => 'bg-sky-50 text-sky-700 border-sky-200',
        'Inprogress' => 'bg-amber-50 text-amber-700 border-amber-200',
        'Closed'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
    $typeClasses = [
        'Threat'      => 'bg-rose-50 text-rose-700 border-rose-200',
        'Opportunity' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
@endphp

<div class="space-y-4">
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
                    {{ $risk->action_status === 'Inprogress' ? 'In Progress' : $risk->action_status }}
                </span>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Risk Category</p>
                <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">
                    {{ $risk->risk_category ?: '---' }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Rating (P × I)</p>
                <span class="inline-flex flex-col items-center rounded-lg border px-2.5 py-1 {{ $ratingClasses[$risk->risk_level] }}">
                    <span class="text-[10px] font-black">{{ $risk->risk_score }}</span>
                    <span class="text-[8px] font-black uppercase tracking-wider">{{ $risk->risk_level }}</span>
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Urgency</p>
                <p class="text-xs font-bold text-slate-600">{{ $risk->risk_urgency ?: '---' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Risk Details</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core risk information</p>
            </div>
        </div>
        <div class="space-y-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Activity Related to the Risk</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->activity ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Risk Description</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->risk_description ?: '---' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Risk Assessment</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Likelihood, severity and impact</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Likelihood (P)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->likelihood }}/5</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Severity (I)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->severity }}/5</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Impact</p>
                <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">
                    {{ $risk->project_impact ?: '---' }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Intent to Claim</p>
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $risk->intent_to_claim ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                    {{ $risk->intent_to_claim ? 'Yes' : 'No' }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                <i data-lucide="trending-up" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Impacts &amp; Tracking</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Cost, time and stakeholder metrics</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost Impact (METB)</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->most_likely_cost_impact ? number_format($risk->most_likely_cost_impact, 2) : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Time Impact</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->most_likely_time_impact ? $risk->most_likely_time_impact . ' ' . ($risk->time_impact_unit ?? '') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Stakeholder</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->stakeholder ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Risk Frequency</p>
                <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">
                    {{ $risk->risk_frequency ?: '---' }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Impact Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->impact_date ? $risk->impact_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Exposure</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->exposure ? $risk->exposure . ' ' . ($risk->exposure_unit ?? '') : '---' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Controls &amp; Response</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Mitigation and contingency plans</p>
            </div>
        </div>
        <div class="space-y-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Response Strategy</p>
                <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">
                    {{ $risk->response_strategy ?: '---' }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Existing Control</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->existing_control ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Residual Risk</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->residual_risk ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Mitigation Plan</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->mitigation_plan ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Contingency Plan</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->contingency_plan ?: '---' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Ownership &amp; Actions</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tracking accountability</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Risk Owner</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->risk_owner ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Name of Owner</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->owner_name ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Planned Action Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->planned_action_date ? $risk->planned_action_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Action Date</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->actual_action_date ? $risk->actual_action_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Date Last Updated</p>
                <p class="text-xs font-black text-slate-900">{{ $risk->date_last_updated ? $risk->date_last_updated->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Action Status</p>
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $statusClasses[$risk->action_status] ?? 'border-slate-200 bg-slate-100 text-slate-500' }}">
                    {{ $risk->action_status === 'Inprogress' ? 'In Progress' : $risk->action_status }}
                </span>
            </div>
        </div>
        <div>
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Tracking Comments</p>
            <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->tracking_comments ?: '---' }}</p>
        </div>
        <div>
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Recommendation / Lesson Learned</p>
            <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $risk->recommendation ?: '---' }}</p>
        </div>
    </div>
</div>
