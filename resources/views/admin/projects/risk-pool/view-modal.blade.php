@php
    use Illuminate\Support\Str;

    $impactClasses = [
        'High'   => 'bg-rose-50 text-rose-700 border-rose-200',
        'Medium' => 'bg-amber-50 text-amber-700 border-amber-200',
        'Low'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
    $impactIcons = [
        'High'   => 'triangle-alert',
        'Medium' => 'minus',
        'Low'    => 'arrow-down',
    ];
    $isAdmin = auth()->user() && auth()->user()->isAdmin();
@endphp

<div class="risk-details-modal space-y-4 text-slate-900">
    <!-- Header strip -->
    <div class="flex items-start justify-between gap-3 rounded-xl bg-slate-50 p-4">
        <div class="flex-1 min-w-0 space-y-1">
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Risk ID</p>
            <p class="text-xl font-black text-slate-900 leading-tight tabular-nums">{{ $risk->risk_id }}</p>
        </div>
        @php
            $overall = $risk->overall_impact_level;
        @endphp
        @if($overall)
            <span class="inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$overall] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                <i data-lucide="{{ $impactIcons[$overall] ?? 'circle' }}" class="w-3.5 h-3.5"></i>
                {{ $overall }}
            </span>
        @else
            <span class="inline-flex items-center rounded-lg border border-slate-200 bg-slate-100 px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-slate-400">
                Unassigned
            </span>
        @endif
    </div>

    <!-- Risk Identity -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                <i data-lucide="fingerprint-pattern" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Risk Identity</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Identifier, category and title</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Risk ID</p>
                <p class="mt-1 text-xs font-black text-slate-900">{{ $risk->risk_id ?: '—' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Risk Category</p>
                <span class="mt-1 inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">{{ $risk->risk_category ?: '—' }}</span>
            </div>
            <div class="sm:col-span-2">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Specific Risk</p>
                <p class="mt-1 text-sm font-black text-slate-900 break-words">{{ $risk->specific_risk ?: '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Detailed Risk Explanation -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-50 text-[#00ADC5]">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Detailed Risk Explanation</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">What the risk is and its context</p>
            </div>
        </div>
        <div class="bg-slate-50/50 rounded-lg border border-slate-100 p-3 max-h-64 overflow-y-auto custom-scrollbar">
            @if($risk->detailed_risk_explanation)
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap break-words">{{ $risk->detailed_risk_explanation }}</p>
            @else
                <p class="text-xs italic text-slate-400">No detailed explanation has been provided.</p>
            @endif
        </div>
    </div>

    <!-- Impact Assessments -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                <i data-lucide="chart-bar" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Impact Assessment</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Timeline, cost and overall impact</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Timeline</p>
                <span class="mt-1 inline-flex items-center gap-1 rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$risk->impact_on_timeline] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                    @if($risk->impact_on_timeline)<i data-lucide="{{ $impactIcons[$risk->impact_on_timeline] ?? 'circle' }}" class="w-3 h-3"></i>@endif
                    {{ $risk->impact_on_timeline ?: 'Unassigned' }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Cost</p>
                <span class="mt-1 inline-flex items-center gap-1 rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$risk->impact_on_cost] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                    @if($risk->impact_on_cost)<i data-lucide="{{ $impactIcons[$risk->impact_on_cost] ?? 'circle' }}" class="w-3 h-3"></i>@endif
                    {{ $risk->impact_on_cost ?: 'Unassigned' }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Overall Impact Level</p>
                <span class="mt-1 inline-flex items-center gap-1 rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$risk->overall_impact_level] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                    @if($risk->overall_impact_level)<i data-lucide="{{ $impactIcons[$risk->overall_impact_level] ?? 'circle' }}" class="w-3 h-3"></i>@endif
                    {{ $risk->overall_impact_level ?: 'Unassigned' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stakeholder -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Stakeholder</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Associated parties</p>
            </div>
        </div>
        <div class="bg-slate-50/50 rounded-lg border border-slate-100 p-3 max-h-48 overflow-y-auto custom-scrollbar">
            @if($risk->stakeholder)
                <p class="whitespace-pre-wrap break-words text-xs font-black text-slate-900">{{ $risk->stakeholder }}</p>
            @else
                <p class="text-xs italic text-slate-400">No stakeholder information has been provided.</p>
            @endif
        </div>
    </div>

    @if($risk->recommended_mitigation_measures)
        <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-900">Recommended Mitigation Measures</h4>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Preventive and responsive actions</p>
                </div>
            </div>
            <div class="bg-slate-50/50 rounded-lg border border-slate-100 p-3 max-h-48 overflow-y-auto custom-scrollbar">
                <p class="whitespace-pre-wrap break-words text-xs font-black text-slate-900">{{ $risk->recommended_mitigation_measures }}</p>
            </div>
        </div>
    @else
        <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-900">Recommended Mitigation Measures</h4>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Preventive and responsive actions</p>
                </div>
            </div>
            <p class="text-xs italic text-slate-400">No mitigation measures have been added.</p>
        </div>
    @endif

    <!-- Record Information -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                <i data-lucide="clock" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Record Information</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Creation and modification history</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Created</p>
                <p class="mt-1 text-xs font-black text-slate-900">{{ $risk->created_at ? $risk->created_at->format('M d, Y H:i') : '—' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Last Updated</p>
                <p class="mt-1 text-xs font-black text-slate-900">{{ $risk->updated_at ? $risk->updated_at->format('M d, Y H:i') : '—' }}</p>
            </div>
            @if($risk->creator)
                <div>
                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Created By</p>
                    <p class="mt-1 text-xs font-black text-slate-900">{{ $risk->creator->name }}</p>
                </div>
            @endif
        </div>
    </div>

    @if($isAdmin)
        <div class="flex justify-end gap-2 pt-2">
            <button type="button" onclick="editRiskFromDetails({{ $risk->id }})"
                class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-amber-600/20 transition-all hover:bg-amber-700 active:scale-95">
                <i data-lucide="pencil" class="h-3 w-3"></i> Edit Risk
            </button>
        </div>
    @endif
</div>
