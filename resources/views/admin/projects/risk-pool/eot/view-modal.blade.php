<div class="space-y-4 text-slate-900">
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-[8px] font-black uppercase tracking-widest text-slate-400">EOT ID</p><p class="mt-1 text-xl font-black tracking-tight text-slate-900">{{ $eot->eot_id }}</p></div>
        <div class="rounded-xl bg-slate-50 p-4"><p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Base of Risk</p><p class="mt-2 inline-flex rounded-lg border border-cyan-100 bg-cyan-50 px-2.5 py-1 text-xs font-black text-cyan-800">{{ $eot->baseOfRisk?->name ?? 'Category removed' }}</p></div>
    </div>
    <section class="space-y-2 rounded-xl border border-slate-100 bg-white p-4">
        <h4 class="text-[9px] font-black uppercase tracking-widest text-slate-400">Detail Reason of EOT</h4>
        <p class="whitespace-pre-wrap break-words text-sm leading-6 text-slate-700">{{ $eot->detail_reason }}</p>
    </section>
    <div class="flex flex-wrap items-center justify-between gap-2 text-[10px] font-semibold text-slate-400">
        <span>Created {{ $eot->created_at?->format('M d, Y') }}{{ $eot->creator ? ' by '.$eot->creator->name : '' }}</span>
        @if($eot->updated_at && $eot->updated_at->ne($eot->created_at))<span>Updated {{ $eot->updated_at->format('M d, Y') }}</span>@endif
    </div>
    @if($canEditEot)
        <div class="flex justify-end border-t border-slate-100 pt-3"><button type="button" onclick="closeEotViewModal(); openEditEotModal({{ $eot->id }})" class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-white transition hover:bg-amber-600"><i data-lucide="pencil" class="h-3.5 w-3.5"></i>Edit EOT</button></div>
    @endif
</div>
