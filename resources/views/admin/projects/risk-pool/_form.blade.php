@php
    $fieldClass = 'w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100 focus:bg-white focus:border-[#00ADC5]/30';
    $labelClass = 'mb-1 block text-[9px] font-black uppercase tracking-widest text-slate-500';
    $areaClass = $fieldClass . ' min-h-28 resize-y';
    $impactLevels = ['High', 'Medium', 'Low'];
    $isEdit = $mode === 'edit';
    $action = $isEdit
        ? route('admin.projects.risk-pool.update', $risk)
        : route('admin.projects.risk-pool.store');
@endphp

<form id="{{ $isEdit ? 'riskPoolEditForm' : 'riskPoolCreateForm' }}"
      action="{{ $action }}" method="POST" data-mode="{{ $mode }}" class="space-y-4">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

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

    <div id="riskPoolFormError" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs font-bold text-rose-700" role="alert"></div>

    <section class="rounded-xl border border-slate-100 p-4">
        <h4 class="mb-3 text-sm font-black text-slate-900">Risk Details</h4>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="risk_category">Risk Category</label>
                <select id="risk_category" name="risk_category" required class="{{ $fieldClass }}"
                        onchange="onRiskCategoryChange(this.value)">
                    <option value="">Select category</option>
                    @foreach(\App\Models\ProjectRiskPool::CATEGORIES as $category)
                        <option value="{{ $category }}" @selected(old('risk_category', $risk->risk_category ?? '') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="risk_id">Risk ID</label>
                <div class="flex items-center gap-2">
                    <input id="risk_id" name="risk_id" type="text" required value="{{ old('risk_id', $risk->risk_id ?? '') }}" placeholder="e.g. 1.01" class="{{ $fieldClass }}">
                    <button type="button" onclick="suggestRiskId()" aria-label="Suggest next ID"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 hover:border-[#00ADC5] hover:text-[#00ADC5] hover:bg-white transition-all">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="mt-1 text-[8px] font-black uppercase tracking-widest text-slate-400">Use the sparkle to suggest the next available ID.</p>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="risk_type">Risk Type</label>
                <select id="risk_type" name="risk_type" required class="{{ $fieldClass }}">
                    <option value="">Select type</option>
                    @foreach(\App\Models\ProjectRiskPool::RISK_TYPES as $type)
                        <option value="{{ $type }}" @selected(old('risk_type', $risk->risk_type ?? '') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="specific_risk">Specific Risk</label>
                <input id="specific_risk" name="specific_risk" type="text" required value="{{ old('specific_risk', $risk->specific_risk ?? '') }}" placeholder="Short risk title" class="{{ $fieldClass }}">
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="detailed_risk_explanation">Detailed Risk Explanation</label>
                <textarea id="detailed_risk_explanation" name="detailed_risk_explanation" rows="4"
                    placeholder="Describe what the risk is, how and why it may occur, the potential consequences, trigger conditions, and any other relevant context."
                    class="{{ $areaClass }}">{{ old('detailed_risk_explanation', $risk->detailed_risk_explanation ?? '') }}</textarea>
            </div>
            <div>
                <label class="{{ $labelClass }}" for="impact_on_timeline">Impact on Time</label>
                <select id="impact_on_timeline" name="impact_on_timeline" class="{{ $fieldClass }}">
                    <option value="">Select impact</option>
                    @foreach($impactLevels as $level)
                        <option value="{{ $level }}" @selected(old('impact_on_timeline', $risk->impact_on_timeline ?? '') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
                <input id="impact_on_timeline_description" name="impact_on_timeline_description" type="text"
                    value="{{ old('impact_on_timeline_description', $risk->impact_on_timeline_description ?? '') }}"
                    placeholder="Timeline impact details..." class="mt-1.5 {{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="impact_on_cost">Impact on Cost</label>
                <select id="impact_on_cost" name="impact_on_cost" class="{{ $fieldClass }}">
                    <option value="">Select impact</option>
                    @foreach($impactLevels as $level)
                        <option value="{{ $level }}" @selected(old('impact_on_cost', $risk->impact_on_cost ?? '') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
                <input id="impact_on_cost_description" name="impact_on_cost_description" type="text"
                    value="{{ old('impact_on_cost_description', $risk->impact_on_cost_description ?? '') }}"
                    placeholder="Cost impact details..." class="mt-1.5 {{ $fieldClass }}">
            </div>
            <div>
                <label class="{{ $labelClass }}" for="overall_impact_level">Impact on Quality</label>
                <select id="overall_impact_level" name="overall_impact_level" class="{{ $fieldClass }}">
                    <option value="">Select level</option>
                    @foreach($impactLevels as $level)
                        <option value="{{ $level }}" @selected(old('overall_impact_level', $risk->overall_impact_level ?? '') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
                <input id="impact_on_quality_description" name="impact_on_quality_description" type="text"
                    value="{{ old('impact_on_quality_description', $risk->impact_on_quality_description ?? '') }}"
                    placeholder="Quality impact details..." class="mt-1.5 {{ $fieldClass }}">
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="stakeholder">Stakeholder</label>
                <textarea id="stakeholder" name="stakeholder" rows="3"
                    placeholder="e.g. Client, Project Manager, Senior Management"
                    class="{{ $areaClass }}">{{ old('stakeholder', $risk->stakeholder ?? '') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}" for="recommended_mitigation_measures">Recommended Mitigation Measures (Optional)</label>
                <textarea id="recommended_mitigation_measures" name="recommended_mitigation_measures" rows="3"
                    placeholder="Recommended measures to prevent, reduce, or manage the risk."
                    class="{{ $areaClass }}">{{ old('recommended_mitigation_measures', $risk->recommended_mitigation_measures ?? '') }}</textarea>
            </div>
        </div>
    </section>

    <div class="flex justify-end gap-2">
        <button type="button" onclick="{{ $isEdit ? 'closeEditModal()' : 'closeCreateModal()' }}"
            class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-black text-slate-600 hover:bg-slate-50">Cancel</button>
        <button type="submit"
            class="rounded-lg bg-slate-900 px-5 py-2 text-xs font-black text-white hover:bg-cyan-700">
            {{ $isEdit ? 'Update Risk' : 'Save Risk' }}
        </button>
    </div>
</form>
