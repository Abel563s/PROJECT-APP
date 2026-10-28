@php
    $impactClasses = [
        'High'   => 'bg-rose-50 text-rose-700 border-rose-200',
        'Medium' => 'bg-amber-50 text-amber-700 border-amber-200',
        'Low'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];
@endphp

<div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-[#00515f]/30 bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black uppercase tracking-widest text-white">
                    <th class="px-4 py-3">Risk ID</th>
                    <th class="px-4 py-3">Risk Category</th>
                    <th class="px-4 py-3">Specific Risk</th>
                    <th class="px-4 py-3 text-center">Impact on Timeline</th>
                    <th class="px-4 py-3 text-center">Impact on Cost</th>
                    <th class="px-4 py-3 text-center">Overall Impact Level</th>
                    <th class="px-4 py-3">Stakeholder</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($risks as $risk)
                    <tr class="transition-colors hover:bg-slate-50/80">
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm tabular-nums">{{ $risk->risk_id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm">{{ $risk->risk_category }}</span>
                        </td>
                        <td class="max-w-xs px-4 py-3">
                            <p class="text-xs font-black text-slate-900 leading-tight break-words">{{ $risk->specific_risk }}</p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$risk->impact_on_timeline] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                                {{ $risk->impact_on_timeline ?: '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$risk->impact_on_cost] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                                {{ $risk->impact_on_cost ?: '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $impactClasses[$risk->overall_impact_level] ?? 'border-slate-200 bg-slate-100 text-slate-400' }}">
                                {{ $risk->overall_impact_level ?: '—' }}
                            </span>
                        </td>
                        <td class="max-w-xs px-4 py-3">
                            <p class="text-xs font-bold text-slate-600 leading-tight break-words">{{ $risk->stakeholder ?: '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="openRiskDetails({{ $risk->id }})"
                                    class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-sky-500 hover:border-sky-500 hover:shadow-sm transition-all"
                                    title="View">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </button>
                                @if($isAdmin)
                                    <button onclick="openSpreadsheetEditor({{ $risk->id }})"
                                        class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-amber-500 hover:border-amber-500 hover:shadow-sm transition-all"
                                        title="Edit">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button onclick="deleteRiskPoolItem({{ $risk->id }})"
                                        class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-rose-500 hover:border-rose-500 hover:shadow-sm transition-all"
                                        title="Delete">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-200">
                                    <i data-lucide="triangle-alert" class="h-8 w-8"></i>
                                </div>
                                <div>
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
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
</div>

@if (count(optional($risks)->getCollection() ?? []) === 0 && !$risks->hasPages())
    <div class="mt-4 rounded-2xl border border-slate-100 bg-white p-6 text-center shadow-sm">
        <div class="flex flex-col items-center gap-3">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-200">
                <i data-lucide="plus" class="h-7 w-7"></i>
            </div>
            <div>
                <p class="text-sm font-black text-slate-500">The risk pool is empty.</p>
                <p class="mt-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Use the filters above or add a new risk.</p>
            </div>
        </div>
    </div>
@endif
