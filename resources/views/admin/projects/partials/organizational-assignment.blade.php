@php
    $canChooseBusinessUnit = auth()->user()->isSuperadmin();
    $organizationBusinessUnitId = old('business_unit_id', $selectedBusinessUnitId ?? null);
    $organizationSectorId = old('sector_id', $project?->sector_id ?? null);
    $organizationBusinessUnit = $businessUnits->firstWhere('id', $organizationBusinessUnitId);
@endphp

<div class="col-span-2 grid grid-cols-2 gap-3 rounded-xl border border-teal-100 bg-teal-50/40 p-3">
    <div class="space-y-1">
        <label class="text-[8px] font-black uppercase tracking-widest text-slate-400">Business Unit</label>
        @if(!$canChooseBusinessUnit)
            <div class="inline-flex min-h-[38px] w-full items-center gap-2 rounded-lg border border-teal-200 bg-white px-3 py-2 text-[10px] font-black text-teal-800">
                <i data-lucide="building-2" class="h-3.5 w-3.5 text-teal-600"></i>
                {{ $organizationBusinessUnit?->name ?? auth()->user()->businessUnit?->name ?? 'Not assigned' }}
            </div>
        @else
            <select name="business_unit_id" onchange="updateProjectSectorOptions(this)"
                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[10px] font-bold text-slate-700 focus:border-[#00ADC5]/30 focus:ring-2 focus:ring-teal-100">
                <option value="">Select a Business Unit</option>
                @foreach($businessUnits as $businessUnit)
                    <option value="{{ $businessUnit->id }}"
                        data-sectors='@json($businessUnit->activeSectors->map(fn ($sector) => ["id" => $sector->id, "name" => $sector->name])->values())'
                        @selected($organizationBusinessUnitId == $businessUnit->id)>
                        {{ $businessUnit->name }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>
    <div class="space-y-1">
        <label class="text-[8px] font-black uppercase tracking-widest text-slate-400">Organizational Sector</label>
        <select name="sector_id"
            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[10px] font-bold text-slate-700 focus:border-[#00ADC5]/30 focus:ring-2 focus:ring-teal-100">
            <option value="">Select a Sector</option>
            @foreach($sectors as $sector)
                <option value="{{ $sector->id }}" @selected($organizationSectorId == $sector->id)>{{ $sector->name }}</option>
            @endforeach
        </select>
    </div>
</div>
