@php($selectedBusinessUnitId = \App\Support\ProjectOrganizationFilters::effectiveBusinessUnitId(request()))
@php($effectiveSectorId = \App\Support\ProjectOrganizationFilters::effectiveSectorId(request()))

<label class="min-w-[170px] flex-1 space-y-1">
    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Business Unit</span>
    <select name="business_unit_id" onchange="this.form.querySelector('[name=sector_id]').value=''; const projectSelect=this.form.querySelector('[name=project_id]'); if(projectSelect) projectSelect.value=''; this.form.submit()"
        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
        <option value="">All Business Units</option>
        @foreach($businessUnits as $businessUnit)
            <option value="{{ $businessUnit->id }}" @selected($selectedBusinessUnitId == $businessUnit->id)>{{ $businessUnit->name }}</option>
        @endforeach
    </select>
</label>

<label class="min-w-[170px] flex-1 space-y-1">
    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Organizational Sector</span>
    <select name="sector_id" onchange="this.form.submit()"
        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
        <option value="">All Organizational Sectors</option>
        @foreach($organizationSectors as $sector)
            <option value="{{ $sector->id }}" @selected(request('sector_id') == $sector->id || ($effectiveSectorId && !$request->filled('sector_id') && $effectiveSectorId == $sector->id))>{{ $sector->name }}</option>
        @endforeach
    </select>
</label>
