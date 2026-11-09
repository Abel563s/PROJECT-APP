<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-black text-[10px]">
                {{ $update->project->project_code }}
            </div>
            <div>
                <h4 class="text-base font-black text-slate-900 leading-tight">{{ $update->project->project_name }}</h4>
                <p class="text-[9px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $update->project->custom_id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.projects.issues.manage', $update->project_id) }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-[#00ADC5] px-3 py-2 text-[9px] font-black uppercase tracking-widest text-white transition hover:bg-[#0097ad]">
                <i data-lucide="list-checks" class="h-3.5 w-3.5"></i>
                Project Issues
            </a>
            <div class="text-right">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Data Date</p>
                <p class="text-xs font-black text-slate-900">{{ $update->data_date ? \Carbon\Carbon::parse($update->data_date)->format('M d, Y') : '---' }}</p>
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
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">PSPI</p>
                        <h4 class="text-lg font-black text-slate-900 mt-0.5">
                            @php
                                $spi = $update->progress_planned > 0 ? ($update->progress_actual / $update->progress_planned) * 100 : 0;
                                $spiColor = $spi >= 80 ? 'text-emerald-600' : ($spi >= 70 ? 'text-[#fcba03]' : 'text-rose-600');
                            @endphp
                            <span class="{{ $spiColor }}">{{ number_format($spi, 2) }}%</span>
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-[#00ADC5] shadow-sm">
                        <i data-lucide="gauge" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Revenue</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Revenue planned and actual</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revenue Planned (PV)</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->revenue_planned) }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revenue Actual (EV)</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->revenue_actual) }}</p>
            </div>
            <div class="col-span-2 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">FSPI</p>
                        <h4 class="text-lg font-black text-slate-900 mt-0.5">
                            @php
                                $fspi = $update->revenue_planned > 0 ? ($update->revenue_actual / $update->revenue_planned) * 100 : 0;
                                $fspiColor = $fspi >= 80 ? 'text-emerald-600' : ($fspi >= 70 ? 'text-[#fcba03]' : 'text-rose-600');
                            @endphp
                            <span class="{{ $fspiColor }}">{{ number_format($fspi, 2) }}%</span>
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                        <i data-lucide="banknote" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revenue Planned Weekly</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->revenue_planned_weekly) }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revenue Actual Weekly</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->revenue_actual_weekly) }}</p>
            </div>
        </div>

    <!-- Cost -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                <i data-lucide="trending-down" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Cost</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Cost planned and actual</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost Planned</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->cost_planned) }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost Actual</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->cost_actual) }}</p>
            </div>
            <div class="col-span-2 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">CPI</p>
                        <h4 class="text-lg font-black text-slate-900 mt-0.5">
                            @php
                                $costPlanned = $update->cost_planned ?? 0;
                                $costActual = $update->cost_actual ?? 0;
                                $cpi = $costActual > 0 ? $costPlanned / $costActual : 0;
                                $cpiColor = $cpi > 1.0 ? 'text-emerald-600' : ($cpi === 1.0 ? 'text-[#fcba03]' : 'text-rose-600');
                            @endphp
                            <span class="{{ $cpiColor }}">{{ number_format($cpi, 2) }}</span>
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-[#00ADC5] shadow-sm">
                        <i data-lucide="gauge" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost Planned Weekly</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->cost_planned_weekly) }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost Actual Weekly</p>
                <p class="text-xs font-black text-slate-900">ETB {{ number_format($update->cost_actual_weekly) }}</p>
            </div>
            <div class="col-span-2 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">CPI Weekly</p>
                        <h4 class="text-lg font-black text-slate-900 mt-0.5">
                            @php
                                $costPlannedWeekly = $update->cost_planned_weekly ?? 0;
                                $costActualWeekly = $update->cost_actual_weekly ?? 0;
                                $cpiWeekly = $costActualWeekly > 0 ? $costPlannedWeekly / $costActualWeekly : 0;
                                $cpiWeeklyColor = $cpiWeekly > 1.0 ? 'text-emerald-600' : ($cpiWeekly === 1.0 ? 'text-[#fcba03]' : 'text-rose-600');
                            @endphp
                            <span class="{{ $cpiWeeklyColor }}">{{ number_format($cpiWeekly, 2) }}</span>
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-[#00ADC5] shadow-sm">
                        <i data-lucide="gauge" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dates -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Dates</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Completion and data date</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revised Finish Date</p>
                <p class="text-xs font-black text-slate-900">{{ $update->completion_date ? \Carbon\Carbon::parse($update->completion_date)->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                @php
                    $baselineStart = $update->project->baseline_start_date ? \Carbon\Carbon::parse($update->project->baseline_start_date) : null;
                    $baselineFinish = $update->project->baseline_finish_date ? \Carbon\Carbon::parse($update->project->baseline_finish_date) : null;
                    $baselineDuration = $baselineStart && $baselineFinish ? $baselineStart->diffInDays($baselineFinish) : 0;
                    $elapsedDays = $baselineStart ? (int) \Carbon\Carbon::now()->diffInDays($baselineStart) : 0;
                    $elapsedDaysColor = $elapsedDays < 0 ? 'text-rose-600' : 'text-emerald-600';
                    $elapsedPercent = $baselineDuration > 0 ? ($elapsedDays / $baselineDuration) * 100 : 0;
                    $elapsedPercentColor = $elapsedPercent < 0 ? 'text-rose-600' : 'text-emerald-600';
                @endphp
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Elapsed Days</p>
                <p class="text-xs font-black {{ $elapsedDaysColor }}">
                    {{ $baselineStart ? $elapsedDays . ' Days' : '---' }}
                </p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Elapsed Days %</p>
                <p class="text-xs font-black {{ $elapsedPercentColor }}">
                    {{ $baselineStart && $baselineDuration > 0 ? round($elapsedPercent, 2) . '%' : '---' }}
                </p>
            </div>
        </div>
        </div>
    </div>

    <!-- Operational Log -->
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
