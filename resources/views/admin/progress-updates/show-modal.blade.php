<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="target" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-base font-black text-slate-900 leading-tight">{{ $update->project->project_name }}</h4>
                <p class="text-[9px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $update->project->custom_id }}</p>
            </div>
        </div>
    </div>

    <!-- Performance Matrix -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="target" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Performance Matrix</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core performance data</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Progress Planned</p>
                <p class="text-xs font-black text-slate-900">{{ $update->progress_planned }}%</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Progress Actual</p>
                <p class="text-xs font-black text-slate-900">{{ $update->progress_actual }}%</p>
            </div>
            <div class="col-span-2 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SPI Index</p>
                        <h4 class="text-lg font-black text-slate-900 mt-0.5">
                            @php
                                $spi = $update->progress_planned > 0 ? ($update->progress_actual / $update->progress_planned) * 100 : 0;
                                echo number_format($spi, 2) . '%';
                            @endphp
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-[#00ADC5] shadow-sm">
                        <i data-lucide="gauge" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financials & Timeline -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Commercial & Temporal</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Revenue and completion</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revenue Planned</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->revenue_planned) }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revenue Actual</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->revenue_actual) }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Target Completion</p>
                <p class="text-xs font-black text-slate-900">{{ $update->completion_date ? \Carbon\Carbon::parse($update->completion_date)->format('M d, Y') : '---' }}</p>
            </div>
        </div>
    </div>

    <!-- Operational Constraints -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Operational Log</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Constraints and issues</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Top Constraints</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->top_constraints ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Client Issues</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->client_issue ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Design Completion</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->design_completion_approval ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Material Submittal</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->material_submittal_approval ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Material Delivery</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->material_delivery ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Labor</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->labor ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Machinery</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->machinery_equipment ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Subcontractor</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->subcontractor ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Finance</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->finance ?: '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Constraints</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->operation_constraint ?: '---' }}</p>
            </div>
        </div>
    </div>
</div>
