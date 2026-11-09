@php
    $isEdit = isset($clause);
    $canCreateClauses = $canCreateClauses ?? false;
    $canEditClauses = $canEditClauses ?? $isEdit;
    $existingClauses = $existingClauses ?? collect();
    $action = $isEdit ? route('admin.projects.risk-pool.contract-clauses.update', $clause) : route('admin.projects.risk-pool.contract-clauses.store');
@endphp
<form id="clauseForm" action="{{ $action }}" method="POST" data-mode="{{ $isEdit ? 'edit' : 'create' }}" class="space-y-4">
    @csrf
    @if($isEdit)<input type="hidden" name="_method" value="PUT">@endif
    <div data-clause-errors class="hidden rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700" role="alert"></div>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-50 text-[#00ADC5]"><i data-lucide="file-text" class="h-4 w-4"></i></div>
            <div><h4 class="text-sm font-black text-slate-900">Contract Clause Register</h4><p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Saved clauses are listed below; add records with Add New Row</p></div>
        </div>
        <div class="flex items-center gap-3">
            @if(!$isEdit && $canCreateClauses)
                <button type="button" data-clause-add-row class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-[9px] font-black uppercase tracking-widest text-white transition hover:bg-[#00515f]"><i data-lucide="plus" class="h-3.5 w-3.5"></i>Add New Row</button>
            @endif
            <span data-clause-row-count class="text-[9px] font-black uppercase tracking-widest text-slate-400"></span>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="w-full min-w-[1250px] border-collapse text-left text-[10px] font-bold text-slate-800">
            <thead><tr class="bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black uppercase tracking-widest text-white">
                <th class="w-36 border-r border-white/10 px-2 py-3">Contract Reference ID</th>
                <th class="w-48 border-r border-white/10 px-2 py-3">Item</th>
                <th class="w-28 border-r border-white/10 px-2 py-3">PPA</th>
                <th class="w-28 border-r border-white/10 px-2 py-3">FIDIC Red</th>
                <th class="w-28 border-r border-white/10 px-2 py-3">FIDIC Yellow</th>
                <th class="w-28 border-r border-white/10 px-2 py-3">FIDIC Silver</th>
                <th class="min-w-[300px] px-2 py-3">Explanation for Planners</th>
                @if(!$isEdit)<th class="w-14 px-2 py-3 text-center">Action</th>@endif
            </tr></thead>
            <tbody data-clause-rows class="divide-y divide-slate-100">
                @if($isEdit)
                    @include('admin.projects.risk-pool.contract-clauses.form-row', ['entry' => $clause, 'editable' => true, 'isEdit' => true])
                @else
                    @foreach($existingClauses as $entry)
                        @include('admin.projects.risk-pool.contract-clauses.form-row', ['entry' => $entry, 'editable' => $canEditClauses, 'isEdit' => false])
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    @if(!$isEdit && $canCreateClauses)
        <template data-clause-row-template>
            <tr data-clause-row data-new-row="1" class="align-top odd:bg-slate-50/60">
                <td class="border-r border-slate-100 p-1.5"><input data-field="row_number" type="number" min="1" required class="w-full rounded-lg border border-slate-200 bg-white px-2 py-2.5 text-center text-[10px] font-black text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"></td>
                <td class="border-r border-slate-100 p-1.5"><input data-field="item" type="text" maxlength="1000" required placeholder="Item" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-semibold text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"></td>
                <td class="border-r border-slate-100 p-1.5"><input data-field="ppa" type="text" maxlength="255" placeholder="PPA" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-semibold text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"></td>
                <td class="border-r border-slate-100 p-1.5"><input data-field="fidic_red" type="text" maxlength="255" placeholder="FIDIC Red" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-semibold text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"></td>
                <td class="border-r border-slate-100 p-1.5"><input data-field="fidic_yellow" type="text" maxlength="255" placeholder="FIDIC Yellow" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-semibold text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"></td>
                <td class="border-r border-slate-100 p-1.5"><input data-field="fidic_silver" type="text" maxlength="255" placeholder="FIDIC Silver" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-semibold text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"></td>
                <td class="p-1.5"><textarea data-field="planner_explanation" rows="1" required maxlength="50000" placeholder="Explain the clause and planning implications..." class="min-h-[34px] w-full resize-y rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[10px] font-medium leading-4 text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"></textarea></td>
                <td class="p-1.5 text-center"><button type="button" data-clause-remove-row class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-100 bg-rose-50 text-rose-500 transition hover:bg-rose-500 hover:text-white" title="Remove row"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i></button></td>
            </tr>
        </template>
    @endif

    <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
        <button type="button" onclick="closeClauseFormModal()" class="rounded-lg border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Cancel</button>
        <button type="submit" data-clause-submit class="inline-flex items-center gap-1.5 rounded-lg bg-[#00515f] px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-[#00515f]/20 transition hover:bg-[#006572]"><i data-lucide="save" class="h-3.5 w-3.5"></i>{{ $isEdit ? 'Save Clause' : 'Save All Changes' }}</button>
    </div>
</form>
