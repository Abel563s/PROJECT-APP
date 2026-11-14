<div class="space-y-4">
    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
        <div><p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Contract Reference ID</p><p class="mt-1 text-lg font-black text-slate-900">{{ $clause->row_number }}</p></div>
        <span class="rounded-lg border border-cyan-100 bg-cyan-50 px-3 py-1.5 text-[9px] font-black uppercase tracking-widest text-cyan-800">Contract Clause</span>
    </div>
    <div class="overflow-hidden rounded-xl border border-slate-100">
        <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0">
            @foreach(['Item' => $clause->item, 'PPA 2011' => $clause->ppa, 'FIDIC 1999' => $clause->fidic_red, 'FIDIC 2017' => $clause->fidic_yellow] as $label => $value)
                <div class="p-4"><p class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $label }}</p><p class="mt-1 text-xs font-semibold text-slate-800">{{ filled($value) ? $value : '—' }}</p></div>
            @endforeach
        </div>
        <div class="border-t border-slate-100 bg-slate-50/60 p-4"><p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Explanation for Planners</p><p class="mt-2 whitespace-pre-wrap text-sm font-medium leading-6 text-slate-700">{{ $clause->planner_explanation }}</p></div>
    </div>
</div>
