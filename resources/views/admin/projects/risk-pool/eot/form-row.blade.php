<tr data-eot-row data-existing-row="1" data-record-id="{{ $editable ? $entry->id : '' }}" data-readonly="{{ $editable ? '0' : '1' }}" class="align-top odd:bg-slate-50/60">
    <td class="border-r border-slate-100 p-2">
        @if($editable && !$isEdit)<input type="hidden" data-record-id-field value="{{ $entry->id }}">@endif
        <div class="flex gap-1.5">
            <input type="text" data-field="eot_id" value="{{ $entry->eot_id }}" {{ $editable ? 'required' : 'disabled' }} maxlength="50" class="min-w-0 w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-black uppercase tracking-wider text-slate-700 disabled:bg-slate-100 disabled:text-slate-500 focus:border-cyan-300 focus:ring-cyan-100">
            @if($isEdit && $canCreateEot)<button type="button" data-eot-suggest class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white px-2 text-slate-500 transition hover:border-cyan-300 hover:text-cyan-800" title="Suggest EOT ID"><i data-lucide="sparkles" class="h-3.5 w-3.5"></i></button>@endif
        </div>
    </td>
    <td class="border-r border-slate-100 p-2">
        <select data-field="eot_category_id" {{ $editable ? 'required' : 'disabled' }} class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-bold text-slate-700 disabled:bg-slate-100 disabled:text-slate-500 focus:border-cyan-300 focus:ring-cyan-100">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected($entry->eot_category_id == $category->id)>{{ $category->name }}{{ $category->is_active ? '' : ' (Inactive)' }}</option>
            @endforeach
        </select>
    </td>
    <td class="border-r border-slate-100 p-2"><input type="text" data-field="contract_reference_id" value="{{ $entry->contract_reference_id }}" {{ $editable ? '' : 'disabled' }} maxlength="255" placeholder="Contract reference ID (optional)" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2.5 text-[10px] font-bold text-slate-700 disabled:bg-slate-100 disabled:text-slate-500 focus:border-cyan-300 focus:ring-cyan-100"></td>
    <td class="p-2"><textarea data-field="detail_reason" rows="2" {{ $editable ? 'required' : 'disabled' }} maxlength="20000" class="min-h-[42px] w-full resize-y rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-[10px] font-medium leading-5 text-slate-700 disabled:bg-slate-100 disabled:text-slate-500 focus:border-cyan-300 focus:ring-cyan-100">{{ $entry->detail_reason }}</textarea></td>
    @if(!$isEdit)
        <td class="p-2 text-center"><span class="text-[8px] font-black uppercase tracking-wider text-slate-400">Saved</span></td>
    @endif
</tr>
