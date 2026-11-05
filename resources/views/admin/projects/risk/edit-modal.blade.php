<form action="{{ route('admin.projects.risk.update', $risk->id) }}" method="POST" id="editRiskForm" class="space-y-4" novalidate>
    @csrf
    @method('PUT')
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
        @php
            $poolTimeline = $poolRisk?->impact_on_timeline ?? '';
            $poolCost = $poolRisk?->impact_on_cost ?? '';
            $poolQuality = $poolRisk?->overall_impact_level ?? '';
            $showPoolImpact = $risk->pool_risk_id && ($poolTimeline || $poolCost || $poolQuality);
        @endphp
        <div id="poolImpactSummary" class="{{ $showPoolImpact ? '' : 'hidden' }} mt-3">
            <p class="text-[8px] font-black uppercase tracking-widest text-slate-400 mb-2">From Risk Repository</p>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Time</p>
                    <span id="poolImpactTimeline" class="mt-1 inline-flex rounded-lg border border-slate-200 bg-white px-2 py-1 text-[9px] font-black text-slate-700">{{ $poolTimeline ?: '---' }}</span>
                </div>
                <div>
                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Cost</p>
                    <span id="poolImpactCost" class="mt-1 inline-flex rounded-lg border border-slate-200 bg-white px-2 py-1 text-[9px] font-black text-slate-700">{{ $poolCost ?: '---' }}</span>
                </div>
                <div>
                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Quality</p>
                    <span id="poolImpactQuality" class="mt-1 inline-flex rounded-lg border border-slate-200 bg-white px-2 py-1 text-[9px] font-black text-slate-700">{{ $poolQuality ?: '---' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs font-bold text-rose-700" role="alert">
            <p class="mb-2">Please correct the following before saving:</p>
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $fieldClass = 'w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-black text-slate-700 focus:ring-2 focus:ring-cyan-100 focus:bg-white focus:border-[#00ADC5]/30';
        $labelClass = 'mb-1 block text-[9px] font-black uppercase tracking-widest text-slate-500';
        $areaClass = $fieldClass . ' min-h-24 resize-y';
    @endphp

    <!-- Step indicator -->
    <div class="mb-5 grid grid-cols-2 gap-2 sm:grid-cols-4" role="navigation" aria-label="Risk form steps">
        <button type="button" class="step-indicator group flex min-w-0 items-center gap-2 rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2 text-left text-cyan-800 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-300 hover:shadow" data-step-target="1" aria-current="step">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-cyan-700 text-[10px] font-black text-white">1</span><span class="text-[8px] font-black uppercase tracking-wider sm:text-[9px]">Identification</span>
        </button>
        <button type="button" class="step-indicator group flex min-w-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-left text-slate-500 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-200 hover:text-cyan-800 hover:shadow" data-step-target="2">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-[10px] font-black text-slate-600">2</span><span class="text-[8px] font-black uppercase tracking-wider sm:text-[9px]">Assessment</span>
        </button>
        <button type="button" class="step-indicator group flex min-w-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-left text-slate-500 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-200 hover:text-cyan-800 hover:shadow" data-step-target="3">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-[10px] font-black text-slate-600">3</span><span class="text-[8px] font-black uppercase tracking-wider sm:text-[9px]">Response</span>
        </button>
        <button type="button" class="step-indicator group flex min-w-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-left text-slate-500 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-200 hover:text-cyan-800 hover:shadow" data-step-target="4">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-[10px] font-black text-slate-600">4</span><span class="text-[8px] font-black uppercase tracking-wider sm:text-[9px]">Evaluation</span>
        </button>
    </div>

    <!-- Step 1 - Risk Identification -->
    <section class="step-panel hidden" data-step="1">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="risk_type">Risk Type</label>
                <select id="risk_type" name="risk_type" required class="{{ $fieldClass }}">
                    <option value="">Select opportunity or threat</option>
                    @foreach($poolTypes as $type)
                        <option value="{{ $type }}" @selected(old('risk_type', $risk->risk_type) === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="pool_risk_category">Pool Risk Category</label>
                <select id="pool_risk_category" name="risk_category" class="{{ $fieldClass }}" disabled data-selected-category="{{ old('pool_risk_category', $risk->pool_risk_category ?? $risk->risk_category) }}">
                    <option value="">Select risk type first</option>
                </select>
                <input type="hidden" name="pool_risk_category" id="pool_risk_category_hidden" value="{{ old('pool_risk_category', $risk->pool_risk_category ?? '') }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="pool_risk_id">Risk ID &amp; Name</label>
                <select id="pool_risk_id" class="{{ $fieldClass }}" disabled data-selected-id="{{ $risk->pool_risk_id ?? '' }}">
                    @if($risk->pool_risk_id)
                        <option value="{{ $risk->pool_risk_id }}" selected>{{ $risk->pool_risk_id }}</option>
                    @else
                        <option value="">Select category first</option>
                    @endif
                </select>
                <input type="hidden" name="pool_risk_id" id="pool_risk_id_hidden" value="{{ $risk->pool_risk_id ?? '' }}">
                <input type="hidden" name="risk_name" id="risk_name" value="{{ old('risk_name', $risk->risk_name) }}">
            </div>
            <div class="md:col-span-2">
                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">From Risk Repository</p>
                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Select a category and Risk ID &amp; Name to auto-fill the risk details.</p>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="activity">Activity Related to the Risk</label>
                <textarea id="activity" name="activity" rows="3" required class="{{ $areaClass }}">{{ old('activity', $risk->activity) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="risk_description">Risk Description</label>
                <textarea id="risk_description" name="risk_description" rows="3" required class="{{ $areaClass }}">{{ old('risk_description', $risk->risk_description) }}</textarea>
            </div>
        </div>
    </section>

    <!-- Step 2 - Risk Assessment -->
    <section class="step-panel hidden" data-step="2">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="likelihood">Likelihood of Occurrence (P)</label>
                <div class="flex items-center gap-3">
                    <span class="text-[9px] font-bold text-slate-400">Low</span>
                    <input id="likelihood" name="likelihood" type="range" min="1" max="5" step="1" value="{{ old('likelihood', $risk->likelihood) }}" class="w-full accent-cyan-600" oninput="updateRiskRating(this.closest('#editModalContent'))">
                    <span class="text-[9px] font-bold text-slate-400">High</span>
                    <output id="likelihoodValue" class="w-7 text-center text-sm font-black text-slate-900">{{ old('likelihood', $risk->likelihood) }}</output>
                </div>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="severity">Severity of the Impact (I)</label>
                <div class="flex items-center gap-3">
                    <span class="text-[9px] font-bold text-slate-400">Low</span>
                    <input id="severity" name="severity" type="range" min="1" max="5" step="1" value="{{ old('severity', $risk->severity) }}" class="w-full accent-cyan-600" oninput="updateRiskRating(this.closest('#editModalContent'))">
                    <span class="text-[9px] font-bold text-slate-400">High</span>
                    <output id="severityValue" class="w-7 text-center text-sm font-black text-slate-900">{{ old('severity', $risk->severity) }}</output>
                </div>
            </div>
            <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 md:col-span-2">
                <div>
                    <p class="{{ $labelClass }}">Risk Rating (P × I)</p>
                    <p class="text-[10px] text-slate-500">Low: 1–4 · Medium: 5–9 · High: 10–16 · Extreme: 17–25</p>
                </div>
                <div class="text-right">
                    <p id="riskRatingScore" class="text-xl font-black text-slate-900">{{ old('likelihood', $risk->likelihood) * old('severity', $risk->severity) }}</p>
                    <p id="riskRatingLevel" class="text-[9px] font-black uppercase tracking-widest text-amber-600">Medium</p>
                </div>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="risk_frequency">Risk Frequency</label>
                <select id="risk_frequency" name="risk_frequency" class="{{ $fieldClass }}">
                    <option value="">Select frequency</option>
                    @foreach(['One Time', 'Fixed Duration', 'Recurring'] as $frequency)
                        <option value="{{ $frequency }}" @selected(old('risk_frequency', $risk->risk_frequency) === $frequency)>{{ $frequency }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="risk_urgency">Risk Urgency</label>
                <select id="risk_urgency" name="risk_urgency" class="{{ $fieldClass }}">
                    <option value="">Select urgency</option>
                    @foreach(['Very Urgent', 'Urgent', 'Moderate', 'Low'] as $urgency)
                        <option value="{{ $urgency }}" @selected(old('risk_urgency', $risk->risk_urgency) === $urgency)>{{ $urgency }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="intent_to_claim">Intent to Claim</label>
                <select id="intent_to_claim" name="intent_to_claim" required class="{{ $fieldClass }}">
                    <option value="">Select intent to claim</option>
                    <option value="1" @selected(old('intent_to_claim', $risk->intent_to_claim) == '1')>Yes</option>
                    <option value="0" @selected(old('intent_to_claim', $risk->intent_to_claim) == '0')>No</option>
                </select>
            </div>
            <div id="eotClaimFields" class="hidden md:col-span-2 grid grid-cols-1 gap-3 md:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}" for="eot_base_of_risk">Base of EOT</label>
                    <select id="eot_base_of_risk" name="eot_category_id" class="{{ $fieldClass }}" data-selected-category="{{ old('eot_category_id', $risk->eotEntry?->eot_category_id) }}">
                        <option value="">Select base of risk</option>
                        @foreach($eotOptions['categories'] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $labelClass }}" for="eot_entry_id">EOT ID</label>
                    <select id="eot_entry_id" name="eot_entry_id" class="{{ $fieldClass }}" disabled data-selected-entry="{{ old('eot_entry_id', $risk->eot_entry_id) }}">
                        <option value="">Select base of risk first</option>
                    </select>
                    <p id="eot_entry_preview" class="mt-1 hidden whitespace-pre-wrap [overflow-wrap:anywhere] rounded-lg border border-cyan-100 bg-cyan-50/60 px-3 py-2 text-[10px] font-medium leading-5 text-slate-700" aria-live="polite"></p>
                </div>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="most_likely_cost_impact">Most Likely Cost Impact (METB)</label>
                <input id="most_likely_cost_impact" name="most_likely_cost_impact" type="number" min="0" step="0.01" value="{{ old('most_likely_cost_impact', $risk->most_likely_cost_impact) }}" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="most_likely_time_impact">Most Likely Time Impact (Days)</label>
                <input id="most_likely_time_impact" name="most_likely_time_impact" type="number" min="0" step="1" value="{{ old('most_likely_time_impact', $risk->most_likely_time_impact) }}" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="most_likely_quality_impact_description">Most Likely Quality Impact (Description)</label>
                <textarea id="most_likely_quality_impact_description" name="most_likely_quality_impact_description" rows="2" class="{{ $areaClass }}">{{ old('most_likely_quality_impact_description', $risk->most_likely_quality_impact_description) }}</textarea>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="resolution_by">Resolution by</label>
                <input id="resolution_by" name="resolution_by" type="text" value="{{ old('resolution_by', $risk->resolution_by) }}" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="impact_date">Assumed Impact Date</label>
                <input id="impact_date" name="impact_date" type="date" value="{{ old('impact_date', $risk->impact_date?->format('Y-m-d')) }}" class="{{ $fieldClass }}">
            </div>
        </div>
    </section>

    <!-- Step 3 - Risk Response -->
    <section class="step-panel hidden" data-step="3">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="existing_control">Existing Control, If the Risk is Materialized</label>
                <textarea id="existing_control" name="existing_control" rows="3" class="{{ $areaClass }}">{{ old('existing_control', $risk->existing_control) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="residual_risk">Residual Risk, If Risk is Materialized</label>
                <textarea id="residual_risk" name="residual_risk" rows="3" class="{{ $areaClass }}">{{ old('residual_risk', $risk->residual_risk) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="response_strategy">Risk Response Strategy</label>
                <select id="response_strategy" name="response_strategy" class="{{ $fieldClass }}" data-selected-strategy="{{ old('response_strategy', $risk->response_strategy) }}">
                    <option value="">Select strategy</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="mitigation_plan">Response / Mitigation Plan / Risk Treatment (Additional if Residual Risk Remains)</label>
                <textarea id="mitigation_plan" name="mitigation_plan" rows="3" class="{{ $areaClass }}">{{ old('mitigation_plan', $risk->mitigation_plan) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="contingency_plan">Risk Contingency Plan</label>
                <textarea id="contingency_plan" name="contingency_plan" rows="3" class="{{ $areaClass }}">{{ old('contingency_plan', $risk->contingency_plan) }}</textarea>
            </div>
        </div>
    </section>

    <!-- Step 4 - Risk Evaluation -->
    <section class="step-panel hidden" data-step="4">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}" for="action_status">Risk Action Status</label>
                <select id="action_status" name="action_status" required class="{{ $fieldClass }}">
                    @foreach(\App\Models\ProjectRisk::ACTION_STATUSES as $status)
                        <option value="{{ $status }}" @selected(old('action_status', $risk->action_status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="materialized_date">Materialized Date</label>
                <input id="materialized_date" name="materialized_date" type="date" value="{{ old('materialized_date', $risk->materialized_date?->format('Y-m-d')) }}" class="{{ $fieldClass }}">
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="tracking_comments">Tracking Comments</label>
                <textarea id="tracking_comments" name="tracking_comments" rows="3" class="{{ $areaClass }}">{{ old('tracking_comments', $risk->tracking_comments) }}</textarea>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="owner_name">Name of Owner</label>
                <input id="owner_name" name="owner_name" type="text" value="{{ old('owner_name', $risk->owner_name) }}" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="planned_action_date">Planned Action Date</label>
                <input id="planned_action_date" name="planned_action_date" type="date" value="{{ old('planned_action_date', $risk->planned_action_date?->format('Y-m-d')) }}" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="actual_action_date">Actual Action Date</label>
                <input id="actual_action_date" name="actual_action_date" type="date" value="{{ old('actual_action_date', $risk->actual_action_date?->format('Y-m-d')) }}" class="{{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="exposure_display">Exposure (Days)</label>
                <output id="exposure_display" class="{{ $fieldClass }} block min-h-9" data-today="{{ now()->toDateString() }}" data-materialized-date="{{ old('materialized_date', $risk->materialized_date?->format('Y-m-d')) }}" data-actual-action-date="{{ old('actual_action_date', $risk->actual_action_date?->format('Y-m-d')) }}">—</output>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="recommendation">Recommendation / Lesson Learned</label>
                <textarea id="recommendation" name="recommendation" rows="3" class="{{ $areaClass }}">{{ old('recommendation', $risk->recommendation) }}</textarea>
            </div>
        </div>
    </section>

    <div class="sticky bottom-0 z-10 flex items-center justify-between border-t border-slate-100 bg-white/95 py-3 pt-4 backdrop-blur">
        <button type="button" id="riskStepBack" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-black text-slate-600 hover:bg-slate-50">Back</button>
        <div class="flex items-center gap-2">
            <button type="button" onclick="closeEditModal()" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-black text-slate-600 hover:bg-slate-50">Cancel</button>
            <button type="button" id="riskStepNext" class="rounded-lg border border-cyan-200 bg-cyan-50 px-4 py-2 text-xs font-black text-cyan-800 hover:bg-cyan-100">Next step</button>
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-5 py-2 text-xs font-black text-white shadow-sm transition hover:bg-cyan-700"><i data-lucide="save" class="h-3.5 w-3.5"></i>Update Risk</button>
        </div>
    </div>
</form>
<script type="application/json" id="eotPoolState">{!! json_encode($eotOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
