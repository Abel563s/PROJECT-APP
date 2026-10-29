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
@endphp

<div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
    @forelse($risks as $risk)
        @php
            $overall = $risk->overall_impact_level;
            $hasExplanation = !empty($risk->detailed_risk_explanation);
        @endphp
        <div class="border-b border-slate-100/60 p-4 transition-colors hover:bg-slate-50/80">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1 min-w-0 space-y-2.5">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 tabular-nums shadow-sm">
                            {{ $risk->risk_id }}
                        </span>
                        <span class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-600 shadow-sm">
                            {{ $risk->risk_category ?: '—' }}
                        </span>
                        @if($overall)
                            <span class="inline-flex items-center gap-1 rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$overall] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                                <i data-lucide="{{ $impactIcons[$overall] ?? 'circle' }}" class="w-3 h-3"></i>
                                <span>{{ $overall }}</span>
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-1 text-[9px] font-black uppercase tracking-wider text-slate-400">
                                Unassigned
                            </span>
                        @endif
                    </div>

                    <h3 class="text-sm font-black leading-tight text-slate-900 break-words">
                        {{ $risk->specific_risk ?: '—' }}
                    </h3>

                    <p class="text-xs font-bold text-slate-600 break-words">
                        @if($hasExplanation)
                            {{ Str::limit($risk->detailed_risk_explanation, 160) }}
                        @else
                            <span class="italic text-slate-400">No detailed explanation provided.</span>
                        @endif
                    </p>

                    <div class="grid grid-cols-1 gap-2.5 pt-1 sm:grid-cols-3">
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Timeline</p>
                            <span class="mt-1 inline-flex rounded-lg border border-slate-200 bg-white px-2 py-0.5 text-[9px] font-black text-slate-700">
                                {{ $risk->impact_on_timeline ?: '—' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Cost</p>
                            <span class="mt-1 inline-flex rounded-lg border border-slate-200 bg-white px-2 py-0.5 text-[9px] font-black text-slate-700">
                                {{ $risk->impact_on_cost ?: '—' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Quality</p>
                            <span class="mt-1 inline-flex items-center gap-1 rounded-lg border px-2 py-0.5 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$overall] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                                @if($overall)<i data-lucide="{{ $impactIcons[$overall] ?? 'circle' }}" class="w-3 h-3"></i>@endif
                                {{ $overall ?: '—' }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Stakeholder</p>
                        <p class="mt-1 text-xs font-bold text-slate-600 break-words">
                            {{ $risk->stakeholder ? Str::limit($risk->stakeholder, 140) : '—' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-shrink-0 items-start gap-1.5 sm:items-center sm:gap-2">
                    <button onclick="openRiskDetails({{ $risk->id }})" type="button"
                        class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-[#00ADC5] hover:border-[#00ADC5]/30 hover:shadow-sm transition-all"
                        title="View Details">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        <span class="sr-only">View Details</span>
                    </button>
                    @if($isAdmin)
                        <button onclick="openSpreadsheetEditor({{ $risk->id }})" type="button"
                            class="p-2 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-amber-500 hover:border-amber-500 hover:shadow-sm transition-all"
                            title="Edit Risk">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            <span class="sr-only">Edit Risk</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="px-6 py-16">
            <div class="flex flex-col items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-200">
                    <i data-lucide="triangle-alert" class="h-8 w-8"></i>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-black text-slate-900">No Risks in Pool</h3>
                    <p class="mt-1 text-[9px] font-black uppercase tracking-widest text-slate-400">
                        @if(request()->filled('search') || request()->filled('risk_category') || request()->filled('overall_impact_level'))
                            No records match your current filters.
                        @else
                            Add risk items to start building the risk pool.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($risks->hasPages())
    <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/30 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">
            Showing {{ $risks->firstItem() ?? 0 }} to {{ $risks->lastItem() ?? 0 }} of {{ $risks->total() }} risks
        </span>
        <div class="flex items-center gap-2">
            {{ $risks->appends(\Illuminate\Support\Arr::except(request()->query(), ['partial','page']))->links() }}
        </div>
    </div>
@endif
