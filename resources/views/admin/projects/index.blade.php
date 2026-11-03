<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-3 border-b border-slate-100 items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Project <span class="font-black text-slate-900">Registry</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Operational Matrix Archive</p>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-white/90"></i>
                    <span class="text-base font-black text-white tracking-tight">{{ $projects->total() }}</span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Projects Registered</span>
                </div>
            </div>

            <div class="flex justify-end">
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperadmin())
                <button onclick="openCreateModal()"
                    class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        New Project
                    </span>
                </button>
                @endif
            </div>
        </div>

        <!-- Search & Filter -->
        <form id="projectsFilterForm" action="{{ route('admin.projects.index') }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            @php
                $hasActiveFilters = request()->anyFilled(['search', 'business_unit_id', 'sector_id', 'project_sector', 'delivery_method', 'consultant_type', 'budget_performance', 'cost_performance', 'schedule_performance', 'project_status']);
                $hasAdvancedFilters = request()->anyFilled(['delivery_method', 'consultant_type', 'budget_performance', 'cost_performance', 'schedule_performance', 'project_status']);
                $selectedBusinessUnitFilterId = \App\Support\ProjectOrganizationFilters::effectiveBusinessUnitId(request());
            @endphp
            <div class="admin-filter-grid gap-3">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Projects</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.requestSubmit()"
                            placeholder="Search by name, code or protocol..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Business Unit</span>
                    <select name="business_unit_id" onchange="this.form.querySelector('[name=sector_id]').value=''; this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Business Units</option>
                        @foreach($businessUnits as $businessUnit)
                            <option value="{{ $businessUnit->id }}" @selected($selectedBusinessUnitFilterId == $businessUnit->id)>{{ $businessUnit->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Organizational Sector</span>
                    <select name="sector_id" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Sectors</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector->id }}" @selected(request('sector_id') == $sector->id)>{{ $sector->name }}</option>
                        @endforeach
                    </select>
                </label>

                <div class="flex flex-wrap items-center justify-start gap-2 xl:justify-end">
                    <button type="button" aria-controls="projectAdvancedFilters" aria-expanded="{{ $hasAdvancedFilters ? 'true' : 'false' }}"
                        onclick="toggleProjectAdvancedFilters(this)"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[9px] font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-[#00515f]">
                        <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i>
                        More filters
                        <i data-lucide="chevron-down" class="project-filter-chevron h-3.5 w-3.5 transition-transform {{ $hasAdvancedFilters ? 'rotate-180' : '' }}"></i>
                    </button>
                    @if($hasActiveFilters)
                        <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition hover:bg-rose-500 hover:text-white">
                            <i data-lucide="x" class="h-3.5 w-3.5"></i>Clear filters
                        </a>
                    @endif
                </div>

                <div id="projectAdvancedFilters" class="admin-filter-advanced-panel {{ $hasAdvancedFilters ? '' : 'hidden' }} rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                    <div class="admin-filter-advanced-grid gap-3">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project Sector</span>
                    <select name="project_sector" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Sectors</option>
                        @foreach(['Building', 'Fit-Out', 'Infrastructure', 'Water & Energy'] as $sector)
                            <option value="{{ $sector }}" {{ request('project_sector') == $sector ? 'selected' : '' }}>{{ $sector }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Delivery Method</span>
                    <select name="delivery_method" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Methods</option>
                        @foreach(['DB', 'DBB'] as $method)
                            <option value="{{ $method }}" {{ request('delivery_method') == $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Consultant Type</span>
                    <select name="consultant_type" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Types</option>
                        @foreach(['Government', 'Private', 'Client / Engineering Team'] as $type)
                            <option value="{{ $type }}" {{ request('consultant_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Budget Performance</span>
                    <select name="budget_performance" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All</option>
                        <option value="Above Budget" {{ request('budget_performance') == 'Above Budget' ? 'selected' : '' }}>Above Budget</option>
                        <option value="Under Budget" {{ request('budget_performance') == 'Under Budget' ? 'selected' : '' }}>Under Budget</option>
                        <option value="On Budget" {{ request('budget_performance') == 'On Budget' ? 'selected' : '' }}>On Budget</option>
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Cost Performance</span>
                    <select name="cost_performance" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All</option>
                        <option value="Above Allowable Cost" {{ request('cost_performance') == 'Above Allowable Cost' ? 'selected' : '' }}>Above Allowable Cost</option>
                        <option value="Below Allowable Cost" {{ request('cost_performance') == 'Below Allowable Cost' ? 'selected' : '' }}>Below Allowable Cost</option>
                        <option value="Within Allowable Cost" {{ request('cost_performance') == 'Within Allowable Cost' ? 'selected' : '' }}>Within Allowable Cost</option>
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Schedule Performance</span>
                    <select name="schedule_performance" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All</option>
                        <option value="Behind Schedule" {{ request('schedule_performance') == 'Behind Schedule' ? 'selected' : '' }}>Behind Schedule</option>
                        <option value="Ahead of Schedule" {{ request('schedule_performance') == 'Ahead of Schedule' ? 'selected' : '' }}>Ahead of Schedule</option>
                        <option value="On Schedule" {{ request('schedule_performance') == 'On Schedule' ? 'selected' : '' }}>On Schedule</option>
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project Status</span>
                    <select name="project_status" onchange="this.form.requestSubmit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All</option>
                        <option value="in progress" {{ request('project_status') == 'in progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('project_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="suspended" {{ request('project_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </label>

                    </div>
                </div>
            </div>
        </form>

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">#</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project Type</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Contract Budget</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Baseline Start</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Baseline Finish</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project Duration</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Budget Performance</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Cost Performance</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Schedule Performance</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Project Status</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($projects as $project)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">{{ $project->project_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'Building' => 'bg-cyan-100/60 text-cyan-700 border-cyan-200 dark:bg-cyan-950 dark:text-cyan-300 dark:border-cyan-700',
                                            'Fit-Out' => 'bg-violet-100/60 text-violet-700 border-violet-200 dark:bg-violet-950 dark:text-violet-300 dark:border-violet-700',
                                            'Infrastructure' => 'bg-amber-100/60 text-amber-700 border-amber-200 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-700',
                                            'Water & Energy' => 'bg-emerald-100/60 text-emerald-700 border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-700',
                                        ];
                                        $typeColor = $typeColors[$project->project_type] ?? 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $typeColor }} border shadow-sm group-hover:shadow-md transition-all">
                                        {{ $project->project_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-900 text-[11px] font-black tabular-nums tracking-tight shadow-sm">
                                        <span class="text-slate-500 font-bold mr-1">ETB</span>
                                        {{ number_format($project->contract_budget) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $project->baseline_start_date ? $project->baseline_start_date->format('d-M-y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $project->baseline_finish_date ? $project->baseline_finish_date->format('d-M-y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $duration = '';
                                        if ($project->baseline_start_date && $project->baseline_finish_date) {
                                            $duration = abs($project->baseline_finish_date->diffInDays($project->baseline_start_date)) + 1;
                                        }
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $duration }}
                                    </span>
                                </td>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $revisedBudget = $project->revised_budget_amount ?? 0;
                                        $contractBudget = $project->contract_budget ?? 0;
                                        $signedBudgetVariance = $revisedBudget - $contractBudget;
                                        $budgetPerformance = $signedBudgetVariance > 0 ? 'Above Budget' : ($signedBudgetVariance < 0 ? 'Under Budget' : 'On Budget');

                                        $costVariance = ($project->total_allowable_cost ?? 0) - ($project->cost_at_completion ?? 0);
                                        $costPerformance = $costVariance < 0 ? 'Above Allowable Cost' : ($costVariance > 0 ? 'Below Allowable Cost' : 'Within Allowable Cost');

                                        if (!$project->actual_start_date) {
                                            $scheduleStatus = 'Not Started';
                                        } elseif ($project->baseline_start_date && $project->baseline_finish_date) {
                                            $baselineDuration = abs($project->baseline_finish_date->diffInDays($project->baseline_start_date));
                                            $actualFinish = $project->actual_finish_date ?: \Carbon\Carbon::today();
                                            $actualDuration = abs($actualFinish->diffInDays($project->actual_start_date));
                                            $sv2 = $actualDuration - $baselineDuration;
                                            if ($sv2 > 0) {
                                                $scheduleStatus = 'Behind Schedule';
                                            } elseif ($sv2 < 0) {
                                                $scheduleStatus = 'Ahead of Schedule';
                                            } else {
                                                $scheduleStatus = 'On Schedule';
                                            }
                                        } else {
                                            $scheduleStatus = 'On Schedule';
                                        }
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $signedBudgetVariance > 0 ? 'bg-rose-50 text-rose-600 border-rose-200' : ($signedBudgetVariance < 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-900 border-slate-200') }} border shadow-sm group-hover:shadow-md transition-all">
                                        {{ $budgetPerformance }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $costVariance < 0 ? 'bg-rose-50 text-rose-600 border-rose-200' : ($costVariance > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-900 border-slate-200') }} border shadow-sm group-hover:shadow-md transition-all">
                                        {{ $costPerformance }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $scheduleStatus === 'Behind Schedule' ? 'bg-rose-50 text-rose-600 border-rose-200' : ($scheduleStatus === 'Ahead of Schedule' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : ($scheduleStatus === 'Not Started' ? 'bg-slate-100 text-slate-500 border-slate-200' : 'bg-[#00ADC5]/10 text-[#00ADC5] border-[#00ADC5]/20')) }} border shadow-sm group-hover:shadow-md transition-all">
                                        {{ $scheduleStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border {{ $project->status === 'in progress' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : ($project->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200') }}">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openViewModal({{ $project->id }}, '{{ $project->status }}')"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#00ADC5] hover:text-white hover:border-[#00ADC5] transition-all duration-200"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @can('update', $project)
                                        <button onclick="openEditModal({{ $project->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @endcan
                                        @can('delete', $project)
                                            <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-200"
                                                    title="Delete">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="database" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">Operational Vacancy</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">No project nodes discovered in the current registry subset.</p>
                                        </div>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isSuperadmin())
                                        <a href="{{ route('admin.projects.create') }}"
                                            class="px-5 py-2 bg-slate-900 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                                            Initialize First Protocol
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/30">
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 px-3 h-10 bg-white border border-slate-100 rounded-xl">
                        <span class="text-[9px] font-black text-slate-900 tracking-widest">{{ $projects->firstItem() ?: 0 }}</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">to</span>
                        <span class="text-[9px] font-black text-slate-400 tracking-widest">{{ $projects->lastItem() ?: 0 }}</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">of</span>
                        <span class="text-[9px] font-black text-slate-400 tracking-widest">{{ $projects->total() }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            <div id="viewModalCard" class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col border border-slate-200">
                <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
                    <h3 class="text-base font-black text-white">Project Details</h3>
                    <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="viewModalContent" class="p-6 overflow-y-auto flex-1">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
                <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
                    <h3 class="text-base font-black text-white">Edit Project</h3>
                    <button onclick="closeEditModal()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="editModalContent" class="p-6 overflow-y-auto flex-1">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeCreateModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
                <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
                    <h3 class="text-base font-black text-white">New Project</h3>
                    <button onclick="closeCreateModal()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="createModalContent" class="p-6 overflow-y-auto flex-1">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openViewModal(id, status) {
            const modalCard = document.getElementById('viewModalCard');
            if (modalCard) {
                modalCard.classList.remove('bg-white', 'bg-gradient-to-br', 'from-yellow-50', 'to-amber-50', 'from-emerald-50', 'to-green-50', 'from-rose-50', 'to-red-50', 'border-yellow-200', 'border-emerald-200', 'border-rose-200', 'border-slate-200');
                if (status === 'in progress') {
                    modalCard.classList.add('bg-gradient-to-br', 'from-yellow-50', 'to-amber-50', 'border-yellow-200');
                } else if (status === 'completed') {
                    modalCard.classList.add('bg-gradient-to-br', 'from-emerald-50', 'to-green-50', 'border-emerald-200');
                } else if (status === 'suspended') {
                    modalCard.classList.add('bg-gradient-to-br', 'from-rose-50', 'to-red-50', 'border-rose-200');
                } else {
                    modalCard.classList.add('bg-white', 'border-slate-200');
                }
            }

            fetch(`/admin/projects/${id}?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('viewModalContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function closeModal() {
            const modalCard = document.getElementById('viewModalCard');
            if (modalCard) {
                modalCard.classList.remove('bg-gradient-to-br', 'from-yellow-50', 'to-amber-50', 'from-emerald-50', 'to-green-50', 'from-rose-50', 'to-red-50', 'border-yellow-200', 'border-emerald-200', 'border-rose-200');
                modalCard.classList.add('bg-white', 'border-slate-200');
            }
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openEditModal(id) {
            fetch(`/admin/projects/${id}/edit?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    document.getElementById('editModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        // Modal HTML is fetched and inserted dynamically, so its inline scripts do
        // not run. Delegate these changes from the persistent modal container.
        document.getElementById('editModalContent').addEventListener('change', function (event) {
            const statusInput = event.target.closest('#projectStatusInput');
            if (statusInput && statusInput.value === 'in progress') {
                const actualFinishInput = this.querySelector('input[name="actual_finish_date"]');
                if (actualFinishInput) actualFinishInput.value = '';
            }

            const actualFinishInput = event.target.closest('input[name="actual_finish_date"]');
            if (actualFinishInput && actualFinishInput.value) {
                const statusSelect = this.querySelector('#projectStatusInput');
                if (statusSelect) statusSelect.value = 'completed';
            }
        });

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function toggleProjectAdvancedFilters(button) {
            const panel = document.getElementById('projectAdvancedFilters');
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', String(!isExpanded));
            panel.classList.toggle('hidden', isExpanded);
            button.querySelector('.project-filter-chevron')?.classList.toggle('rotate-180', !isExpanded);
        }

        function openCreateModal() {
            fetch(`/admin/projects/create?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function updateProjectSectorOptions(businessUnitSelect) {
            const sectorSelect = businessUnitSelect.closest('.grid').querySelector('select[name="sector_id"]');
            const selectedOption = businessUnitSelect.options[businessUnitSelect.selectedIndex];
            const sectors = selectedOption?.dataset.sectors ? JSON.parse(selectedOption.dataset.sectors) : [];
            sectorSelect.innerHTML = '<option value="">Select a Sector</option>';
            sectors.forEach(sector => {
                const option = document.createElement('option');
                option.value = sector.id;
                option.textContent = sector.name;
                sectorSelect.appendChild(option);
            });
        }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeEditModal();
                closeCreateModal();
            }
        });
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>
