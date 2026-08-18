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
                <button onclick="openCreateModal()"
                    class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        New Project
                    </span>
                </button>
            </div>
        </div>

        <!-- Search & Filter -->
        <form action="{{ route('admin.projects.index') }}" method="GET" class="space-y-4">
            <div class="bg-white p-4 rounded-2xl shadow-lg shadow-slate-200/30 border border-slate-50 flex flex-wrap items-center justify-center gap-3 w-fit mx-auto">
                <div class="min-w-[220px] max-w-[320px] relative group shrink-0">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#00ADC5] transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, code or protocol..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-xs font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                </div>

                <div class="flex flex-nowrap items-center gap-3 shrink-0 overflow-x-auto pb-1">
                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Project Sector</span>
                        <div class="relative group/select">
                            <select name="project_sector" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="">All Sectors</option>
                                @foreach(['Building', 'Fit-Out', 'Infrastructure', 'Water & Energy'] as $sector)
                                    <option value="{{ $sector }}" {{ request('project_sector') == $sector ? 'selected' : '' }}>{{ $sector }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Delivery Method</span>
                        <div class="relative group/select">
                            <select name="delivery_method" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="">All Methods</option>
                                @foreach(['DB', 'DBB'] as $method)
                                    <option value="{{ $method }}" {{ request('delivery_method') == $method ? 'selected' : '' }}>{{ $method }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Consultant Type</span>
                        <div class="relative group/select">
                            <select name="consultant_type" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="">All Types</option>
                                @foreach(['Government', 'Private', 'Client / Engineering Team'] as $type)
                                    <option value="{{ $type }}" {{ request('consultant_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Budget Performance</span>
                        <div class="relative group/select">
                            <select name="budget_performance" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="">All</option>
                                <option value="Above Budget" {{ request('budget_performance') == 'Above Budget' ? 'selected' : '' }}>Above Budget</option>
                                <option value="Under Budget" {{ request('budget_performance') == 'Under Budget' ? 'selected' : '' }}>Under Budget</option>
                                <option value="On Budget" {{ request('budget_performance') == 'On Budget' ? 'selected' : '' }}>On Budget</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Cost Performance</span>
                        <div class="relative group/select">
                            <select name="cost_performance" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="">All</option>
                                <option value="Above Allowable Cost" {{ request('cost_performance') == 'Above Allowable Cost' ? 'selected' : '' }}>Above Allowable Cost</option>
                                <option value="Below Allowable Cost" {{ request('cost_performance') == 'Below Allowable Cost' ? 'selected' : '' }}>Below Allowable Cost</option>
                                <option value="Withn Allowable Cost" {{ request('cost_performance') == 'Withn Allowable Cost' ? 'selected' : '' }}>Withn Allowable Cost</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Schedule Performance</span>
                        <div class="relative group/select">
                            <select name="schedule_performance" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="">All</option>
                                <option value="Behind Schedule" {{ request('schedule_performance') == 'Behind Schedule' ? 'selected' : '' }}>Behind Schedule</option>
                                <option value="Ahead of Schedule" {{ request('schedule_performance') == 'Ahead of Schedule' ? 'selected' : '' }}>Ahead of Schedule</option>
                                <option value="On Schedule" {{ request('schedule_performance') == 'On Schedule' ? 'selected' : '' }}>On Schedule</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Project Status</span>
                        <div class="relative group/select">
                            <select name="project_status" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="">All</option>
                                <option value="in progress" {{ request('project_status') == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('project_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="suspended" {{ request('project_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    @if(request()->anyFilled(['search', 'project_sector', 'delivery_method', 'consultant_type', 'budget_performance', 'cost_performance', 'schedule_performance', 'project_status']))
                        <a href="{{ route('admin.projects.index') }}"
                            class="flex items-center justify-center w-10 h-10 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all active:scale-90 shadow-sm self-end"
                            title="Clear Filters">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
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
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center cursor-pointer" title="Click to toggle status">Project Status</th>
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
                                            'Building' => 'bg-cyan-100/60 text-cyan-700 border-cyan-200',
                                            'Fit-Out' => 'bg-violet-100/60 text-violet-700 border-violet-200',
                                            'Infrastructure' => 'bg-amber-100/60 text-amber-700 border-amber-200',
                                            'Water & Energy' => 'bg-emerald-100/60 text-emerald-700 border-emerald-200',
                                        ];
                                        $typeColor = $typeColors[$project->project_type] ?? 'bg-slate-100 text-slate-600 border-slate-200';
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
                                        {{ $project->baseline_start_date ? $project->baseline_start_date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $project->baseline_finish_date ? $project->baseline_finish_date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $duration = '';
                                        if ($project->baseline_start_date && $project->baseline_finish_date) {
                                            $duration = abs($project->baseline_finish_date->diffInDays($project->baseline_start_date));
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
                                        $costPerformance = $costVariance < 0 ? 'Above Allowable Cost' : ($costVariance > 0 ? 'Below Allowable Cost' : 'Withn Allowable Cost');

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
                                    <button type="button" onclick="toggleStatus({{ $project->id }}, this)" class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border hover:shadow-md transition-all {{ $project->status === 'in progress' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : ($project->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200') }}">
                                        {{ ucfirst($project->status) }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openViewModal({{ $project->id }}, '{{ $project->status }}')"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#00ADC5] hover:text-white hover:border-[#00ADC5] transition-all duration-200"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button onclick="openEditModal({{ $project->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @if(auth()->user() && auth()->user()->isAdmin())
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
                                        @endif
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
                                        <a href="{{ route('admin.projects.create') }}"
                                            class="px-5 py-2 bg-slate-900 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                                            Initialize First Protocol
                                        </a>
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

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
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

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function toggleStatus(id, btn) {
            const originalText = btn.textContent;
            const originalClass = btn.className;
            btn.disabled = true;
            btn.style.opacity = '0.6';
            
            console.log('Toggling status for project ID:', id);
            
            fetch(`/admin/projects/${id}/toggle-status?_t=${Date.now()}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                cache: 'no-store',
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success && data.status) {
                    const newStatus = data.status;
                    btn.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    btn.className = 'inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border hover:shadow-md transition-all ' + (
                        newStatus === 'in progress' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' :
                        newStatus === 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' :
                        'bg-rose-50 text-rose-600 border-rose-200'
                    );
                } else {
                    throw new Error('Unexpected response format');
                }
            })
            .catch(error => {
                console.error('Toggle status failed:', error);
                btn.textContent = originalText;
                btn.className = originalClass;
                alert('Failed to update status: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.style.opacity = '1';
            });
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
