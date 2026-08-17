<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-black text-[10px]">
                {{ $project->project_code }}
            </div>
            <div>
                <h4 class="text-base font-black text-slate-900 leading-tight">{{ $project->project_name }}</h4>
                <p class="text-[9px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $project->custom_id }}</p>
            </div>
        </div>
    </div>

    <!-- Section 1: Project Identity -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="fingerprint" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-black text-slate-900 font-mono">Project Identity</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core deployment</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project ID</p>
                <p class="text-xs font-black text-slate-900">{{ $project->custom_id }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Code</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_code }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Name</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_name }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Sector</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_type }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Delivery Method</p>
                <p class="text-xs font-black text-slate-900">{{ $project->delivery_method }}</p>
            </div>
        </div>
        <div class="col-span-2">
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Scope</p>
            <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $project->scope ?: 'No scope definition provided.' }}</p>
        </div>
    </div>

    <!-- Section 2: Stakeholders -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-black text-slate-900 font-mono">Stakeholders</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Partners</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Client</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_client ?: 'Not Specified' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Consultant</p>
                <p class="text-xs font-black text-slate-900">{{ $project->consultant ?: 'Not Specified' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Consultancy Sector</p>
                <p class="text-xs font-black text-slate-900">{{ $project->consultancy_sector ?: 'Not Specified' }}</p>
            </div>
        </div>
    </div>

    <!-- Section 3: Financial -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#00ADC5]/10 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-black text-slate-900 font-mono">Budget & Cost Management</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Capital (ETB)</p>
            </div>
        </div>
        @php
            $costVariance = ($project->total_allowable_cost ?? 0) - ($project->cost_at_completion ?? 0);
            $revisedBudgetAmount = ($project->contract_budget ?? 0) + ($project->variation ?? 0) + ($project->supplementary ?? 0);
            $revisedBudget = $revisedBudgetAmount;
            $contractBudget = $project->contract_budget ?? 0;
            $budgetAtCompletion = $project->budget_at_completion ?? 0;
            $signedBudgetVariance = $revisedBudget - $budgetAtCompletion;
            $budgetVariance = $revisedBudget - $budgetAtCompletion;
            $budgetPerformance = $budgetVariance > 0 ? 'Above Budget' : ($budgetVariance < 0 ? 'Under Budget' : 'On Budget');
            $budgetIndex = $revisedBudget > 0 ? ($budgetVariance / $revisedBudget) : 0;
            $allowable = $project->total_allowable_cost ?? 0;
            $cac = $project->cost_at_completion ?? 0;
            $costEacIndex = $allowable > 0 ? ($allowable - $cac) / $allowable : 0;
            $costPerformance = $costVariance < 0 ? 'Above Allowable Cost' : ($costVariance > 0 ? 'Below Allowable Cost' : 'With Allowable Cost');

            $scheduleStatus = 'On Schedule';
            if (!$project->actual_start_date) {
                $scheduleStatus = 'Not Started';
            } elseif ($project->baseline_finish_date) {
                if ($project->actual_finish_date) {
                    $svForStatus = $project->baseline_finish_date->diffInDays($project->actual_start_date);
                } else {
                    $svForStatus = $project->baseline_finish_date->diffInDays(\Carbon\Carbon::today());
                }
                if ($svForStatus < 0) {
                    $scheduleStatus = 'Behind Schedule';
                } elseif ($svForStatus > 0) {
                    $scheduleStatus = 'Ahead of Schedule';
                }
            }

            if ($scheduleStatus !== 'Behind Schedule' && $budgetPerformance !== 'Above Budget' && $scheduleStatus !== 'Not Started') {
                $overallPerformance = 'Good';
                $overallPerformanceColor = 'text-emerald-600';
            } elseif ($scheduleStatus === 'Behind Schedule' || $budgetPerformance === 'Above Budget' || $scheduleStatus === 'Not Started') {
                $overallPerformance = 'Attention!';
                $overallPerformanceColor = 'text-rose-600';
            } else {
                $overallPerformance = 'Check Data';
                $overallPerformanceColor = 'text-amber-600';
            }

            $elapsedDays = null;
            if ($project->baseline_start_date) {
                $elapsedDays = \Carbon\Carbon::today()->diffInDays($project->baseline_start_date) + 1;
            }
        @endphp

        <div class="space-y-3">
            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-500 mb-2">Contract Budget Management</p>
                <div class="grid grid-cols-2 xl:grid-cols-5 gap-3">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Contract Budget</p>
                        <p class="text-xs font-black text-slate-900">ETB {{ number_format($project->contract_budget) }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Variation</p>
                        <p class="text-xs font-black text-slate-900">ETB {{ number_format($project->variation) }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Supp.</p>
                        <p class="text-xs font-black text-slate-900">ETB {{ number_format($project->supplementary) }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revised Budget</p>
                        <p class="text-xs font-black text-slate-900">ETB {{ number_format($revisedBudgetAmount) }}</p>
                    </div>
                    @if($project->status === 'completed')
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Budget at Completion</p>
                        <p class="text-xs font-black text-slate-900">ETB {{ number_format($project->budget_at_completion) }}</p>
                    </div>
                    @endif
                </div>
                @if($project->status === 'completed')
                <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Budget Variance</p>
                        <p class="text-xs font-black {{ $budgetVariance > 0 ? 'text-emerald-600' : ($budgetVariance < 0 ? 'text-rose-600' : 'text-slate-900') }}">ETB {{ number_format($budgetVariance) }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Budget Index %</p>
                        <p class="text-xs font-black {{ $budgetIndex < 0 ? 'text-rose-600' : ($budgetIndex > 0 ? 'text-amber-600' : 'text-slate-900') }}">{{ number_format($budgetIndex * 100, 2) }}%</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Budget Performance</p>
                        <p class="text-xs font-black {{ $signedBudgetVariance > 0 ? 'text-rose-600' : ($signedBudgetVariance < 0 ? 'text-emerald-600' : 'text-slate-900') }}">{{ $budgetPerformance }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-500 mb-2">Cost Management</p>
                <div class="grid grid-cols-2 xl:grid-cols-3 gap-3">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Planned Cost</p>
                        <p class="text-xs font-black text-slate-900">ETB {{ number_format($project->total_allowable_cost) }}</p>
                    </div>
                        @if($project->status === 'completed')
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost at Completion</p>
                            <p class="text-xs font-black text-slate-900">ETB {{ number_format($project->cost_at_completion) }}</p>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost Variance at Completion</p>
                            <p class="text-xs font-black text-slate-900">ETB {{ number_format($costVariance) }}</p>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost EAC Index %</p>
                            <p class="text-xs font-black {{ $costEacIndex > 0 ? 'text-emerald-600' : ($costEacIndex < 0 ? 'text-rose-600' : 'text-slate-900') }}">{{ number_format($costEacIndex * 100, 2) }}%</p>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost Performance</p>
                            <p class="text-xs font-black {{ $costVariance < 0 ? 'text-rose-600' : ($costVariance > 0 ? 'text-emerald-600' : 'text-[#00ADC5]') }}">{{ $costPerformance }}</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>

    <!-- Section 4: Timeline -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-black text-slate-900 font-mono">Timeline</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Schedule dates</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Baseline Start</p>
                <p class="text-xs font-black text-slate-900">{{ $project->baseline_start_date ? $project->baseline_start_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Baseline Finish</p>
                <p class="text-xs font-black text-slate-900">{{ $project->baseline_finish_date ? $project->baseline_finish_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Duration</p>
                <p class="text-xs font-black text-slate-900">
                    @if($project->baseline_start_date && $project->baseline_finish_date)
                        {{ abs($project->baseline_finish_date->diffInDays($project->baseline_start_date)) }} days
                    @else
                        ---
                    @endif
                </p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Start</p>
                <p class="text-xs font-black text-slate-900">{{ $project->actual_start_date ? $project->actual_start_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Finish</p>
                <p class="text-xs font-black text-slate-900">{{ $project->actual_finish_date ? $project->actual_finish_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Duration</p>
                <p class="text-xs font-black text-slate-900">
                    @if($project->actual_start_date && $project->actual_finish_date)
                        {{ abs($project->actual_finish_date->diffInDays($project->actual_start_date)) }} days
                    @else
                        ---
                    @endif
                </p>
            </div>

            @if($project->baseline_start_date)
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Elapsed Days</p>
                <p class="text-xs font-black text-slate-900">
                    {{ \Carbon\Carbon::today()->diffInDays($project->baseline_start_date) + 1 }} days
                </p>
            </div>
            @endif
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Approved EOT</p>
                <p class="text-xs font-black text-slate-900">{{ $project->approved_eot ? $project->approved_eot->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Approved EOT Duration</p>
                <p class="text-xs font-black text-slate-900">
                    @if($project->approved_eot && $project->baseline_finish_date)
                        {{ $project->approved_eot->diffInDays($project->baseline_finish_date) + 1 }} days
                    @else
                        ---
                    @endif
                </p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revision</p>
                <p class="text-xs font-black text-slate-900">{{ $project->revision_number ?: '---' }}</p>
            </div>

            @if($project->status === 'completed')
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Schedule Variance</p>
                <p class="text-xs font-black text-slate-900">
                    @php
                        $sv2 = null;
                        $baselineDuration = null;
                        $actualDuration = null;
                        if ($project->baseline_start_date && $project->baseline_finish_date && $project->actual_start_date) {
                            $baselineDuration = abs($project->baseline_finish_date->diffInDays($project->baseline_start_date));
                            $actualFinish = $project->actual_finish_date ?: \Carbon\Carbon::today();
                            $actualDuration = abs($actualFinish->diffInDays($project->actual_start_date));
                            $sv2 = $actualDuration - $baselineDuration;
                        }
                    @endphp
                    @if($sv2 !== null)
                        @if($sv2 > 0)
                            <span class="text-rose-600">+{{ $sv2 }} days late</span>
                        @elseif($sv2 < 0)
                            <span class="text-emerald-600">{{ $sv2 }} days early</span>
                        @else
                            <span class="text-slate-900">On time</span>
                        @endif
                    @else
                        ---
                    @endif
                </p>
            </div>
            @endif

            @if($project->status === 'completed')
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Schedule Performance Index %</p>
                <p class="text-xs font-black text-slate-900">
                    @php
                        $spi = null;
                        if ($project->baseline_start_date && $project->baseline_finish_date) {
                            $baselineDuration = $project->baseline_finish_date->diffInDays($project->baseline_start_date);
                            if ($baselineDuration == 0) {
                                $spi = 0;
                            } else {
                                if ($project->actual_finish_date) {
                                    $remainingDays = $project->baseline_finish_date->diffInDays($project->actual_finish_date);
                                } else {
                                    $remainingDays = $project->baseline_finish_date->diffInDays(\Carbon\Carbon::today());
                                }
                                $spi = round(($remainingDays / $baselineDuration) * 100, 1);
                            }
                        }
                    @endphp
                    @if($spi !== null)
                        <span class="{{ $spi >= 100 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $spi }}%</span>
                    @else
                        ---
                    @endif
                </p>
            </div>
            @endif
            @if($project->status === 'completed')
            <div class="col-span-3 flex justify-center">
                <div class="text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Schedule Status</p>
                    <p class="text-sm font-black">
                        @php
                            $scheduleStatus = null;
                            $scheduleStatusColor = 'text-slate-900';
                            if (!$project->actual_start_date) {
                                $scheduleStatus = 'Not Started';
                                $scheduleStatusColor = 'text-slate-500';
                            } elseif ($project->baseline_finish_date) {
                                if ($project->actual_finish_date) {
                                    $svForStatus = $project->baseline_finish_date->diffInDays($project->actual_start_date);
                                } else {
                                    $svForStatus = $project->baseline_finish_date->diffInDays(\Carbon\Carbon::today());
                                }
                                if ($svForStatus < 0) {
                                    $scheduleStatus = 'Behind Schedule';
                                    $scheduleStatusColor = 'text-rose-600';
                                } elseif ($svForStatus > 0) {
                                    $scheduleStatus = 'Ahead of Schedule';
                                    $scheduleStatusColor = 'text-emerald-600';
                                } else {
                                    $scheduleStatus = 'On Schedule';
                                    $scheduleStatusColor = 'text-[#00ADC5]';
                                }
                            }
                        @endphp
                        @if($scheduleStatus)
                            <span class="{{ $scheduleStatusColor }}">{{ $scheduleStatus }}</span>
                        @else
                            <span class="text-slate-400">---</span>
                        @endif
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Section 5: Closeout -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-black text-slate-900 font-mono">Closing Status</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Operational state</p>
            </div>
        </div>
        @php
            $colors = [
                'Not Completed' => 'bg-amber-100/50 text-amber-600 border-amber-200/50',
                'PPA Received' => 'bg-cyan-100/50 text-[#00ADC5] border-cyan-200/50',
                'PA Received' => 'bg-indigo-100/50 text-indigo-600 border-indigo-200/50',
                'FA Received' => 'bg-emerald-100/50 text-emerald-600 border-emerald-200/50',
                'Snag / Di-Snag' => 'bg-rose-100/50 text-rose-600 border-rose-200/50',
                'Waiting for PA' => 'bg-slate-100/50 text-slate-600 border-slate-200/50',
            ];
            $color = $colors[$project->closing_status] ?? 'bg-slate-100 text-slate-400 border-slate-200';
        @endphp
        <div class="grid grid-cols-4 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $color }} border">
                    {{ $project->closing_status }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">PPA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->ppa_received_at ? $project->ppa_received_at->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">PA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->pa_received_at ? $project->pa_received_at->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">FA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->fa_received_at ? $project->fa_received_at->format('M d, Y') : '---' }}</p>
            </div>
        </div>
    </div>
</div>
