<div class="space-y-4">
    <div id="orgManagementErrors" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-[9px] font-bold text-rose-700" role="alert"></div>

    <div class="flex items-center justify-between">
        <h4 class="text-sm font-black text-slate-900">Business Units</h4>
        <button type="button" onclick="openBuForm()"
            class="inline-flex items-center gap-1.5 rounded-xl bg-[#00515f] px-3 py-1.5 text-[8px] font-black uppercase tracking-widest text-white shadow transition hover:bg-[#006572] active:scale-95">
            <i data-lucide="plus" class="h-3 w-3"></i> New BU
        </button>
    </div>

    <div id="buFormContainer"></div>

    <div class="grid gap-3">
        @forelse($businessUnits as $bu)
            <div class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-cyan-100 text-[#00ADC5]">
                                <i data-lucide="building-2" class="h-3 w-3"></i>
                            </div>
                            <p class="text-xs font-black text-slate-900">{{ $bu->name }}</p>
                        </div>
                        @if($bu->code)
                            <p class="mt-0.5 text-[8px] font-black uppercase tracking-wider text-slate-400">{{ $bu->code }}</p>
                        @endif
                        @if($bu->description)
                            <p class="mt-1 text-[9px] text-slate-500">{{ $bu->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100">
                        <button type="button" onclick="editBusinessUnit({{ $bu->id }})"
                            class="h-7 w-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm hover:bg-amber-500 hover:text-white transition">
                            <i data-lucide="pencil" class="h-3 w-3"></i>
                        </button>
                        <button type="button" onclick="deleteBusinessUnit({{ $bu->id }})"
                            class="h-7 w-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 shadow-sm hover:bg-rose-500 hover:text-white transition">
                            <i data-lucide="trash-2" class="h-3 w-3"></i>
                        </button>
                    </div>
                </div>

                @if($bu->sectors->isNotEmpty())
                    <div class="mt-3 space-y-1.5">
                        @foreach($bu->sectors as $sector)
                            <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50/30 px-3 py-1.5">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="layers" class="h-3 w-3 text-slate-400"></i>
                                    <span class="text-[9px] font-black text-slate-700">{{ $sector->name }}</span>
                                </div>
                                <div class="flex items-center gap-0.5">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-1.5 py-0.5 text-[7px] font-black text-slate-500">{{ $sector->users_count }} user{{ $sector->users_count !== 1 ? 's' : '' }}</span>
                                    <button type="button" onclick="editSector({{ $sector->id }})"
                                        class="h-5 w-5 rounded-md bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white transition">
                                        <i data-lucide="pencil" class="h-2.5 w-2.5"></i>
                                    </button>
                                    <button type="button" onclick="deleteSector({{ $sector->id }})"
                                        class="h-5 w-5 rounded-md bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white transition">
                                        <i data-lucide="trash-2" class="h-2.5 w-2.5"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-2 flex items-center justify-between">
                    <button type="button" onclick="openSectorForm({{ $bu->id }})"
                        class="inline-flex items-center gap-1 text-[8px] font-black text-[#00ADC5] hover:text-cyan-700">
                        <i data-lucide="plus" class="h-2.5 w-2.5"></i> Add sector
                    </button>
                    <span class="inline-flex items-center gap-1 text-[8px] font-black text-slate-400">
                        <i data-lucide="users" class="h-2.5 w-2.5"></i> {{ $bu->users_count ?? 0 }} user{{ ($bu->users_count ?? 0) !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/30 p-6 text-center">
                <i data-lucide="building-2" class="mx-auto h-6 w-6 text-slate-300"></i>
                <p class="mt-2 text-xs font-black text-slate-400">No business units found.</p>
            </div>
        @endforelse
    </div>

    <div id="sectorFormContainer"></div>
</div>
