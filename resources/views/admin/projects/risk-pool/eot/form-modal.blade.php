@php
    $isEdit = isset($eot);
    $canCreateEot = $canCreateEot ?? false;
    $canEditEot = $canEditEot ?? $isEdit;
    $existingEntries = $existingEntries ?? collect();
    $action = $isEdit ? route('admin.projects.risk-pool.eot.update', $eot) : route('admin.projects.risk-pool.eot.store');
    $canManageCategories = auth()->user()->canManageRiskPool('create')
        || auth()->user()->canManageRiskPool('edit')
        || auth()->user()->canManageRiskPool('delete');
@endphp

<form id="eotForm" action="{{ $action }}" method="POST" data-mode="{{ $isEdit ? 'edit' : 'create' }}" data-can-create="{{ $canCreateEot ? '1' : '0' }}" data-can-edit="{{ $canEditEot ? '1' : '0' }}" class="space-y-4">
    @csrf
    @if($isEdit)<input type="hidden" name="_method" value="PUT">@endif
    <div data-eot-errors class="hidden rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700" role="alert"></div>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-50 text-[#00ADC5]"><i data-lucide="clipboard-list" class="h-4 w-4"></i></div>
            <div><h4 class="text-sm font-black text-slate-900">EOT Information</h4><p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Existing EOTs are shown below; add records with Add New Row</p></div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(!$isEdit && $canCreateEot)
                <button type="button" data-eot-add-row class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-[9px] font-black uppercase tracking-widest text-white transition hover:bg-[#00515f]"><i data-lucide="plus" class="h-3.5 w-3.5"></i>Add New Row</button>
            @endif
            @if($canManageCategories)
                <button type="button" onclick="openEotCategoryModal()" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-[9px] font-black uppercase tracking-widest text-slate-600 transition hover:border-cyan-300 hover:text-cyan-800"><i data-lucide="list-tree" class="h-3.5 w-3.5"></i>Manage Categories</button>
            @endif
            <span data-eot-row-count class="text-[9px] font-black uppercase tracking-widest text-slate-400"></span>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="w-full min-w-[900px] border-collapse text-left text-[10px] font-bold text-slate-800">
            <thead><tr class="bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black uppercase tracking-widest text-white">
                <th class="w-48 border-r border-white/10 px-3 py-3">EOT ID</th>
                <th class="w-64 border-r border-white/10 px-3 py-3">Base of Risk</th>
                <th class="px-3 py-3">Detail Reason of EOT</th>
                @if(!$isEdit)<th class="w-16 px-3 py-3 text-center">Action</th>@endif
            </tr></thead>
            <tbody data-eot-rows class="divide-y divide-slate-100">
                @if($isEdit)
                    @include('admin.projects.risk-pool.eot.form-row', ['entry' => $eot, 'categories' => $categories, 'editable' => true, 'isEdit' => true, 'canCreateEot' => $canCreateEot])
                @else
                    @foreach($existingEntries as $entry)
                        @include('admin.projects.risk-pool.eot.form-row', ['entry' => $entry, 'categories' => $categories, 'editable' => $canEditEot, 'isEdit' => false, 'canCreateEot' => $canCreateEot])
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    @if(!$isEdit && $canCreateEot)
        <template data-eot-row-template>
            <tr data-eot-row data-new-row="1" class="align-top odd:bg-slate-50/60">
                <td class="border-r border-slate-100 p-2"><div class="flex gap-1.5"><input type="text" data-field="eot_id" required maxlength="50" placeholder="Choose a base of risk" class="min-w-0 w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"><button type="button" data-eot-suggest class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white px-2 text-slate-500 transition hover:border-cyan-300 hover:text-cyan-800" title="Suggest EOT ID"><i data-lucide="sparkles" class="h-3.5 w-3.5"></i></button></div></td>
                <td class="border-r border-slate-100 p-2"><select data-field="eot_category_id" required class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-bold text-slate-700 focus:border-cyan-300 focus:ring-cyan-100"><option value="">Select base of risk</option>@foreach($categories->where('is_active', true) as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></td>
                <td class="p-2"><textarea data-field="detail_reason" rows="2" required maxlength="20000" placeholder="Describe the event and its effect on the works..." class="min-h-[42px] w-full resize-y rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-[10px] font-medium leading-5 text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:ring-cyan-100"></textarea></td>
                <td class="p-2 text-center"><button type="button" data-eot-remove-row class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-100 bg-rose-50 text-rose-500 transition hover:bg-rose-500 hover:text-white" title="Remove row"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i></button></td>
            </tr>
        </template>
    @endif

    <div class="flex flex-wrap items-center justify-end gap-2 border-t border-slate-100 pt-3">
        <button type="button" onclick="closeEotFormModal()" class="rounded-lg border border-slate-200 px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50">Cancel</button>
        <button type="submit" data-eot-submit class="inline-flex items-center gap-1.5 rounded-lg bg-[#00515f] px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-[#00515f]/20 transition hover:bg-[#006572]"><i data-lucide="save" class="h-3.5 w-3.5"></i>{{ $isEdit ? 'Save EOT' : 'Save All Changes' }}</button>
    </div>
</form>
