<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        <style>
            .issue-kpi-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .issue-kpi-card:hover {
                transform: translateY(-4px);
            }
            .issue-kpi-card .progress-bar {
                transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            }
            @keyframes countUp {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .issue-kpi-card h3 {
                animation: countUp 0.6s ease-out;
            }
            .chart-fallback {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
                color: #94a3b8;
                font-weight: 800;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
        </style>

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="file-bar-chart" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Issue <span class="font-black text-slate-900">Report</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Issue Analytics & Reporting</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form action="{{ route('admin.projects.issues.report') }}" method="GET" class="space-y-4">
            <div class="bg-white p-4 rounded-2xl shadow-lg shadow-slate-200/30 border border-slate-50 flex flex-wrap items-center justify-center gap-3 w-fit mx-auto">
                <div class="min-w-[220px] max-w-[320px] relative group shrink-0">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#00ADC5] transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search issues..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-xs font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                </div>

                <div class="flex flex-nowrap items-center gap-3 shrink-0 overflow-x-auto pb-1">
                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Project</span>
                        <div class="relative group/select">
                            <select name="project_id" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[180px]">
                                <option value="all">All Projects</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_code }} - {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Status</span>
                        <div class="relative group/select">
                            <select name="status" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="all">All Statuses</option>
                                <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Impact</span>
                        <div class="relative group/select">
                            <select name="impact" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                                <option value="all">All Impacts</option>
                                <option value="High" {{ request('impact') == 'High' ? 'selected' : '' }}>High</option>
                                <option value="Medium" {{ request('impact') == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Low" {{ request('impact') == 'Low' ? 'selected' : '' }}>Low</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Issue Type</span>
                        <div class="relative group/select">
                            <select name="issue_type" onchange="this.form.submit()"
                                class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[180px]">
                                <option value="all">All Issue Types</option>
                                @foreach($issueTypeCounts as $type => $count)
                                    <option value="{{ $type }}" {{ request('issue_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex flex-col gap-1">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">From</span>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" onchange="this.form.submit()"
                                class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[9px] font-black text-slate-600 uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-cyan-100/50 w-[140px]">
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">To</span>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" onchange="this.form.submit()"
                                class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[9px] font-black text-slate-600 uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-cyan-100/50 w-[140px]">
                        </div>
                    </div>

                    @if(request()->anyFilled(['search', 'project_id', 'status', 'impact', 'issue_type', 'from_date', 'to_date']))
                        <a href="{{ route('admin.projects.issues.report') }}"
                            class="flex items-center justify-center w-10 h-10 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all active:scale-90 shadow-sm shrink-0 self-end"
                            title="Clear Filters">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <div class="issue-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-cyan-200 transition-all duration-300 overflow-hidden">
                <div class="relative z-10 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center text-white shadow-md">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Issues</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">{{ $total }}</p>
                </div>
            </div>

            <div class="issue-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-sky-200 transition-all duration-300 overflow-hidden">
                <div class="relative z-10 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center text-white shadow-md">
                            <i data-lucide="circle" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Open</p>
                    <p class="text-2xl font-black text-sky-600 tracking-tighter leading-none">{{ $open }}</p>
                </div>
            </div>

            <div class="issue-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-amber-200 transition-all duration-300 overflow-hidden">
                <div class="relative z-10 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white shadow-md">
                            <i data-lucide="loader" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">In Progress</p>
                    <p class="text-2xl font-black text-amber-600 tracking-tighter leading-none">{{ $inProgress }}</p>
                </div>
            </div>

            <div class="issue-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-200 transition-all duration-300 overflow-hidden">
                <div class="relative z-10 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-md">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Closed</p>
                    <p class="text-2xl font-black text-emerald-600 tracking-tighter leading-none">{{ $closed }}</p>
                </div>
            </div>

            <div class="issue-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-red-200 transition-all duration-300 overflow-hidden">
                <div class="relative z-10 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white shadow-md">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Overdue</p>
                    <p class="text-2xl font-black text-red-600 tracking-tighter leading-none">{{ $overdue }}</p>
                </div>
            </div>

            <div class="issue-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-rose-200 transition-all duration-300 overflow-hidden">
                <div class="relative z-10 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center text-white shadow-md">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cancelled</p>
                    <p class="text-2xl font-black text-rose-600 tracking-tighter leading-none">{{ $cancelled }}</p>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Status Distribution -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Status Distribution</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issues by current status</p>
                </div>
                <div id="statusChart" style="height: 280px;"></div>
            </div>

            <!-- Impact Distribution -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Impact Distribution</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issues by severity level</p>
                </div>
                <div id="impactChart" style="height: 280px;"></div>
            </div>

            <!-- Monthly Trend -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">6-Month Trend</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issues raised per month</p>
                </div>
                <div id="trendChart" style="height: 280px;"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Issue Type -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Top Issue Types</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Most frequent issue categories</p>
                </div>
                <div id="typeChart" style="height: 300px;"></div>
            </div>

            <!-- Sector Distribution -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Sector Distribution</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issues by project sector</p>
                </div>
                <div id="sectorChart" style="height: 300px;"></div>
            </div>
        </div>

        <!-- Top Projects & Resolution Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Top Projects by Issue Count</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Projects with the most logged issues</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-4 py-2 text-[9px] font-black text-slate-500 uppercase tracking-widest">#</th>
                                <th class="px-4 py-2 text-[9px] font-black text-slate-500 uppercase tracking-widest">Project</th>
                                <th class="px-4 py-2 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Total</th>
                                <th class="px-4 py-2 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Open</th>
                                <th class="px-4 py-2 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Closed</th>
                                <th class="px-4 py-2 text-[9px] font-black text-slate-500 uppercase tracking-widest text-right">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($topProjects as $index => $project)
                                <tr class="hover:bg-slate-50/80 transition-all">
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-xs font-black text-slate-900 leading-tight">{{ $project['project_name'] }}</p>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $project['project_code'] }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs font-black text-slate-900">{{ $project['count'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs font-black text-sky-600">{{ $project['open'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs font-black text-emerald-600">{{ $project['closed'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @php
                                            $progress = $project['count'] > 0 ? round(($project['closed'] / $project['count']) * 100) : 0;
                                        @endphp
                                        <div class="flex items-center justify-end gap-2">
                                            <div class="w-16 bg-slate-50 rounded-full h-1.5 overflow-hidden border border-slate-100">
                                                <div class="bg-gradient-to-r from-emerald-500 to-emerald-400 h-full rounded-full" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-[9px] font-black text-slate-900 w-8 text-right">{{ $progress }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-[9px] font-black uppercase tracking-widest">No project data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Resolution Stats -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Resolution Performance</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issue closure metrics</p>
                </div>
                <div class="space-y-4">
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Avg Resolution Time</p>
                        <p class="text-lg font-black text-slate-900">{{ $avgResolutionDays ? number_format($avgResolutionDays, 1) . ' days' : 'No data' }}</p>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Median (P50)</p>
                        <p class="text-lg font-black text-slate-900">{{ $p50 ? $p50 . ' days' : 'No data' }}</p>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">90th Percentile (P90)</p>
                        <p class="text-lg font-black text-slate-900">{{ $p90 ? $p90 . ' days' : 'No data' }}</p>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Closure Rate</p>
                        @php
                            $closureRate = $total > 0 ? round(($closed / $total) * 100, 1) : 0;
                        @endphp
                        <p class="text-lg font-black {{ $closureRate >= 50 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $closureRate }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Issues Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Detailed Issue Log</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $total }} issues found</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">#</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Issue Owner</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Issue</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Impact</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Status</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Raised Date</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Planned Resolution</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Closed Date</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Raised By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($issues as $issue)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="min-w-0">
                                        <p class="text-xs font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors break-words">
                                            {{ $issue->project?->project_name ?? 'N/A' }}
                                        </p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $issue->project_code ?? '---' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $issue->issue_type ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-slate-900 leading-tight break-words">{{ $issue->issue }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $impactColors = [
                                            'Low' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'Medium' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            'High' => 'bg-rose-50 text-rose-600 border-rose-200',
                                        ];
                                        $impactColor = $impactColors[$issue->impact] ?? 'bg-slate-100 text-slate-400 border-slate-200';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $impactColor }} border shadow-sm">
                                        {{ $issue->impact ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'Open' => 'bg-sky-50 text-sky-600 border-sky-200',
                                            'In Progress' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            'Closed' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'Cancelled' => 'bg-rose-50 text-rose-600 border-rose-200',
                                        ];
                                        $statusColor = $statusColors[$issue->status] ?? 'bg-slate-100 text-slate-400 border-slate-200';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $statusColor }} border shadow-sm">
                                        {{ $issue->status ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $issue->raised_date ? $issue->raised_date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $issue->planned_resolution_date ? $issue->planned_resolution_date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $issue->issue_closed_date ? $issue->issue_closed_date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $issue->raised_by ?? '---' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="alert-circle" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">No Issues Found</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Try adjusting your filters.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($issues->hasPages())
                <div class="p-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                            Showing {{ $issues->firstItem() ?? 0 }} to {{ $issues->lastItem() ?? 0 }} of {{ $issues->total() }} issues
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        {{ $issues->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts" onerror="window.__apexchartsFailed=true"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.__apexchartsFailed || typeof ApexCharts === 'undefined') {
                document.querySelectorAll('[id$="Chart"]').forEach(function (el) {
                    el.innerHTML = '<div class=\"chart-fallback\">Chart unavailable</div>';
                });
                if (window.lucide) lucide.createIcons();
                return;
            }

            const statusData = @json($statusCounts ?? []);
            const impactData = @json($impactCounts ?? []);
            const trendData = @json($monthlyTrend ?? []);
            const typeData = @json($issueTypeCounts ?? []);
            const sectorData = @json($sectorCounts ?? []);

            const statusSeries = Object.values(statusData);
            const statusLabels = Object.keys(statusData);
            const statusColors = ['#3b82f6', '#f59e0b', '#10b981', '#ef4444'];

            const impactSeries = [impactData.High || 0, impactData.Medium || 0, impactData.Low || 0];
            const impactColors = ['#ef4444', '#f59e0b', '#10b981'];

            const typeLabels = Object.keys(typeData);
            const typeValues = Object.values(typeData);
            const typeColors = ['#00ADC5', '#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f97316', '#eab308', '#84cc16', '#22c55e', '#3b82f6'];

            const sectorLabels = Object.keys(sectorData);
            const sectorValues = Object.values(sectorData);
            const sectorColors = ['#00ADC5', '#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f97316', '#eab308', '#84cc16'];

            function createChartSafe(selector, config) {
                const el = document.querySelector(selector);
                if (!el) return;
                try {
                    new ApexCharts(el, config).render();
                } catch (e) {
                    el.innerHTML = '<div class=\"chart-fallback\">Chart unavailable</div>';
                }
            }

            createChartSafe('#statusChart', {
                chart: { type: 'donut', height: 280, animations: { enabled: true, easing: 'easeinout', speed: 500 } },
                series: statusSeries,
                labels: statusLabels,
                colors: statusColors,
                legend: { position: 'bottom', fontSize: '10px', fontWeight: 700, labels: { colors: '#475569' } },
                plotOptions: { pie: { donut: { size: '65%', labels: { show: true, value: { fontSize: '14px', fontWeight: 800, color: '#0f172a' }, total: { show: true, label: 'Total', fontSize: '11px', fontWeight: 700, color: '#64748b' } } } } },
                dataLabels: { enabled: true, style: { fontSize: '10px', fontWeight: 800 }, formatter: (val) => val > 0 ? val : '' },
                stroke: { show: true, width: 2, colors: ['#fff'] },
                tooltip: { theme: 'light' }
            });

            createChartSafe('#impactChart', {
                chart: { type: 'donut', height: 280, animations: { enabled: true, easing: 'easeinout', speed: 500 } },
                series: impactSeries,
                labels: ['High', 'Medium', 'Low'],
                colors: impactColors,
                legend: { position: 'bottom', fontSize: '10px', fontWeight: 700, labels: { colors: '#475569' } },
                plotOptions: { pie: { donut: { size: '65%', labels: { show: true, value: { fontSize: '14px', fontWeight: 800, color: '#0f172a' }, total: { show: true, label: 'Total', fontSize: '11px', fontWeight: 700, color: '#64748b' } } } } },
                dataLabels: { enabled: true, style: { fontSize: '10px', fontWeight: 800 }, formatter: (val) => val > 0 ? val : '' },
                stroke: { show: true, width: 2, colors: ['#fff'] },
                tooltip: { theme: 'light' }
            });

            createChartSafe('#trendChart', {
                chart: { type: 'bar', height: 280, animations: { enabled: true, easing: 'easeinout', speed: 500 }, toolbar: { show: false } },
                series: [{ name: 'Issues', data: Object.values(trendData) }],
                xaxis: { categories: Object.keys(trendData), labels: { style: { fontSize: '9px', fontWeight: 700, colors: '#475569' } }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' }, formatter: (val) => Math.round(val) } },
                colors: ['#00ADC5'],
                dataLabels: { enabled: true, style: { fontSize: '9px', fontWeight: 800, colors: ['#000'] }, formatter: (val) => val > 0 ? val : '' },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
                grid: { borderColor: '#EEF2F7', strokeDashArray: 4, padding: { left: 8, right: 16, top: 8, bottom: 8 } },
                tooltip: { theme: 'light' }
            });

            createChartSafe('#typeChart', {
                chart: { type: 'bar', height: 300, animations: { enabled: true, easing: 'easeinout', speed: 500 }, toolbar: { show: false } },
                series: [{ name: 'Issues', data: typeValues }],
                xaxis: { categories: typeLabels.map(l => l.length > 20 ? l.substring(0, 18) + '…' : l), labels: { style: { fontSize: '9px', fontWeight: 700, colors: '#475569' }, rotate: -30, rotateAlways: true }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' }, formatter: (val) => Math.round(val) } },
                colors: typeColors,
                dataLabels: { enabled: true, style: { fontSize: '9px', fontWeight: 800, colors: ['#000'] }, formatter: (val) => val > 0 ? val : '' },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
                grid: { borderColor: '#EEF2F7', strokeDashArray: 4, padding: { left: 8, right: 16, top: 8, bottom: 8 } },
                tooltip: { theme: 'light' }
            });

            createChartSafe('#sectorChart', {
                chart: { type: 'bar', height: 300, animations: { enabled: true, easing: 'easeinout', speed: 500 }, toolbar: { show: false } },
                series: [{ name: 'Issues', data: sectorValues }],
                xaxis: { categories: sectorLabels, labels: { style: { fontSize: '9px', fontWeight: 700, colors: '#475569' } }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' }, formatter: (val) => Math.round(val) } },
                colors: sectorColors,
                dataLabels: { enabled: true, style: { fontSize: '9px', fontWeight: 800, colors: ['#000'] }, formatter: (val) => val > 0 ? val : '' },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
                grid: { borderColor: '#EEF2F7', strokeDashArray: 4, padding: { left: 8, right: 16, top: 8, bottom: 8 } },
                tooltip: { theme: 'light' }
            });

            if (window.lucide) lucide.createIcons();
        });

        function exportCSV() {
            const table = document.querySelector('.overflow-x-auto table');
            if (!table) return;
            const rows = Array.from(table.querySelectorAll('tr'));
            const csv = rows.map(row => Array.from(row.querySelectorAll('th, td')).map(cell => `"${cell.innerText.replace(/"/g, '""')}"`).join(',')).join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `issues-report-${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
        }
    </script>
</x-app-layout>
