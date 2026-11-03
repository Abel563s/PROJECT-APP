<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        <style>
            @media print {
                .report-no-print { display: none !important; }
                .issue-kpi-card { break-inside: avoid; box-shadow: none !important; }
            }
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
        <div class="flex flex-col gap-4 border-b border-slate-100 pb-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="file-bar-chart-2" class="h-4 w-4"></i>
                </div>
                <div>
                    <h2 class="font-outfit text-xl font-bold leading-none tracking-tight text-slate-900">Issue <span class="font-black">Report</span></h2>
                    <p class="mt-1 text-[8px] font-black uppercase tracking-[0.3em] text-[#00ADC5]">Issue analytics and reporting</p>
                </div>
            </div>
            <div class="report-no-print flex flex-wrap items-center justify-between gap-2 sm:justify-end">
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-bold text-slate-600 shadow-sm transition hover:border-slate-300 hover:text-slate-900">
                    <i data-lucide="printer" class="h-4 w-4"></i><span>Print</span>
                </button>
                <button type="button" onclick="exportCSV()" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3.5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-cyan-800">
                    <i data-lucide="download" class="h-4 w-4"></i><span>Export CSV</span>
                </button>
            </div>
        </div>

        <form action="{{ route('admin.projects.issues.report') }}" method="GET" class="report-no-print rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            @php
                $reportAdvancedActive = request()->anyFilled(['project_id', 'status', 'impact', 'issue_type', 'from_date', 'to_date']);
                $reportFiltersActive = request()->anyFilled(['project_id', 'status', 'impact', 'issue_type', 'from_date', 'to_date', 'business_unit_id', 'sector_id', 'search']);
            @endphp
            <div class="admin-filter-grid gap-3">
                    <label class="space-y-1">
                        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search issues</span>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Issue, type, project, or owner" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 placeholder:font-medium placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100" onkeydown="if(event.key==='Enter') this.form.requestSubmit()">
                    </label>
                    @include('admin.partials.project-organization-filters')
                <div class="flex items-center gap-2">
                    <button type="button" onclick="toggleFilterPanel(this, 'issue-report-more-filters')" aria-expanded="{{ $reportAdvancedActive ? 'true' : 'false' }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-bold text-slate-600 transition hover:border-cyan-300 hover:text-cyan-800">
                        <i data-lucide="sliders-horizontal" class="h-4 w-4"></i><span>More filters</span><i data-lucide="chevron-down" class="h-3.5 w-3.5"></i>
                    </button>
                    @if($reportFiltersActive)
                        <a href="{{ route('admin.projects.issues.report') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2.5 text-xs font-bold text-rose-600 transition hover:bg-rose-500 hover:text-white" title="Clear filters"><i data-lucide="x" class="h-4 w-4"></i><span>Clear</span></a>
                    @endif
                </div>
            </div>
            <div id="issue-report-more-filters" class="admin-filter-advanced-panel {{ $reportAdvancedActive ? '' : 'hidden' }} mt-4 border-t border-slate-100 pt-4">
                <div class="admin-filter-advanced-grid gap-3">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project</span>
                    <select name="project_id" onchange="this.form.submit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="all">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>{{ $project->project_code }} - {{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex flex-col gap-1">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Status</label>
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                            <option value="all">All Statuses</option>
                            <option value="Raised" {{ request('status') == 'Raised' ? 'selected' : '' }}>Raised</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Impact</label>
                    <div class="relative">
                        <select name="impact" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                            <option value="all">All Impacts</option>
                            <option value="High" {{ request('impact') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Medium" {{ request('impact') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Low" {{ request('impact') == 'Low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Issue Type</label>
                    <div class="relative">
                        <select name="issue_type" onchange="this.form.submit()"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                            <option value="all">All Issue Types</option>
                            @foreach($issueTypeCounts as $type => $count)
                                <option value="{{ $type }}" {{ request('issue_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">From</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" onchange="this.form.submit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">To</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" onchange="this.form.submit()"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                </div>
                </div>
            </form>
        <section style="margin-top: 2rem" class="space-y-4 rounded-3xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div><h2 class="text-sm font-black text-slate-900">Issue analytics</h2><p class="mt-1 text-xs text-slate-500">Summary and breakdown for the selected issue records.</p></div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $total }} issues in current view</span>
            </div>
            <div class="-mx-4 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0">
                <div class="grid min-w-[992px] grid-cols-7 gap-3" style="grid-template-columns: repeat(7, minmax(0, 1fr)); min-width: 992px;">
                    @php
                        $kpis = [
                            ['label' => 'Total Issues', 'value' => $total, 'icon' => 'layers-3', 'tone' => 'slate', 'caption' => 'All records'],
                            ['label' => 'Open', 'value' => $open, 'icon' => 'circle-dot', 'tone' => 'sky', 'caption' => 'By status'],
                            ['label' => 'Closed', 'value' => $closed, 'icon' => 'check-check', 'tone' => 'emerald', 'caption' => 'By status'],
                            ['label' => 'Overdue', 'value' => $overdue, 'icon' => 'alarm-clock', 'tone' => 'rose', 'caption' => 'By priority'],
                            ['label' => 'High', 'value' => $highPriority, 'icon' => 'triangle-alert', 'tone' => 'orange', 'caption' => 'By priority'],
                            ['label' => 'Medium', 'value' => $mediumPriority, 'icon' => 'minus', 'tone' => 'amber', 'caption' => 'By priority'],
                            ['label' => 'Low', 'value' => $lowPriority, 'icon' => 'arrow-down', 'tone' => 'cyan', 'caption' => 'By priority'],
                        ];
                        $tones = [
                            'slate' => ['icon' => 'bg-slate-100 text-slate-600', 'value' => 'text-slate-900', 'border' => 'hover:border-slate-300'],
                            'sky' => ['icon' => 'bg-sky-100 text-sky-700', 'value' => 'text-sky-700', 'border' => 'hover:border-sky-200'],
                            'emerald' => ['icon' => 'bg-emerald-100 text-emerald-700', 'value' => 'text-emerald-700', 'border' => 'hover:border-emerald-200'],
                            'rose' => ['icon' => 'bg-rose-100 text-rose-700', 'value' => 'text-rose-700', 'border' => 'hover:border-rose-200'],
                            'orange' => ['icon' => 'bg-orange-100 text-orange-700', 'value' => 'text-orange-700', 'border' => 'hover:border-orange-200'],
                            'amber' => ['icon' => 'bg-amber-100 text-amber-700', 'value' => 'text-amber-700', 'border' => 'hover:border-amber-200'],
                            'cyan' => ['icon' => 'bg-cyan-100 text-cyan-700', 'value' => 'text-cyan-700', 'border' => 'hover:border-cyan-200'],
                        ];
                    @endphp
                    @foreach($kpis as $kpi)
                        <div class="group rounded-2xl border border-slate-100 bg-gradient-to-br from-white to-slate-50/80 p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md {{ $tones[$kpi['tone']]['border'] }}">
                            <div class="flex items-center justify-between gap-2"><div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $tones[$kpi['tone']]['icon'] }} transition-transform group-hover:scale-105"><i data-lucide="{{ $kpi['icon'] }}" class="h-4 w-4"></i></div><span class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $kpi['label'] }}</span></div>
                            <p class="mt-3 text-2xl font-black leading-none tabular-nums {{ $tones[$kpi['tone']]['value'] }}">{{ $kpi['value'] }}</p>
                            <p class="mt-1 text-[8px] font-bold uppercase tracking-wider text-slate-400">{{ $kpi['caption'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Status Distribution -->
            <div class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white via-white to-slate-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Status Distribution</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issues by current status</p>
                </div>
                <div id="statusChart" style="height: 280px;"></div>
            </div>

            <!-- Impact Distribution -->
            <div class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white via-white to-slate-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Impact Distribution</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issues by severity level</p>
                </div>
                <div id="impactChart" style="height: 280px;"></div>
            </div>

            <!-- Monthly Trend -->
            <div class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white via-white to-slate-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">6-Month Trend</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issues raised per month</p>
                </div>
                <div id="trendChart" style="height: 280px;"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Issue Type -->
            <div class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white via-white to-slate-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Top Issue Types</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Most frequent issue categories</p>
                </div>
                <div id="typeChart" style="height: 300px;"></div>
            </div>

            <!-- Sector Distribution -->
            <div class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white via-white to-slate-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="mb-3">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Sector Distribution</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Issues by project sector</p>
                </div>
                <div id="sectorChart" style="height: 300px;"></div>
            </div>
        </div>

        <!-- Top Projects & Resolution Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white via-white to-slate-50 p-5 shadow-sm transition hover:shadow-lg">
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
                                        <p class="text-xs font-black text-slate-900 leading-tight">{{ $project['project_code'] }}</p>
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
                                            <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
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
            <div class="group rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white via-white to-slate-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
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

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-100 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-black text-slate-900">Issue Register</h2>
                    <p class="mt-1 text-xs text-slate-500">{{ $issues->total() }} matching issues &middot; {{ $issues->firstItem() ?? 0 }}&ndash;{{ $issues->lastItem() ?? 0 }} shown</p>
                </div>
                <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-cyan-50 px-3 py-1.5 text-[10px] font-bold text-cyan-800"><i data-lucide="filter" class="h-3.5 w-3.5"></i>Filtered results</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] text-left">
                    <thead><tr class="bg-gradient-to-b from-[#00515f] to-slate-900 text-[9px] font-black uppercase tracking-widest text-white">
                        <th class="px-4 py-3">Project / Issue</th><th class="px-4 py-3">Issue Type & Owner</th><th class="px-4 py-3 text-center">Impact</th><th class="px-4 py-3 text-center">Status</th><th class="px-4 py-3 text-center">Priority</th><th class="px-4 py-3">Raised</th><th class="px-4 py-3">Planned Action</th><th class="px-4 py-3">Action By</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($issues as $issue)
                        @php
                            $issueStatusTone = ['Raised' => 'bg-slate-100 text-slate-600 border-slate-200', 'Open' => 'bg-sky-50 text-sky-700 border-sky-200', 'Closed' => 'bg-emerald-50 text-emerald-700 border-emerald-200'][$issue->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            $issueImpactTone = ['High' => 'bg-rose-50 text-rose-700 border-rose-200', 'Medium' => 'bg-amber-50 text-amber-700 border-amber-200', 'Low' => 'bg-emerald-50 text-emerald-700 border-emerald-200'][$issue->impact] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            $issuePriorityTone = ['Done' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'Overdue' => 'bg-rose-50 text-rose-700 border-rose-200', 'High' => 'bg-orange-50 text-orange-700 border-orange-200', 'Medium' => 'bg-amber-50 text-amber-700 border-amber-200', 'Low' => 'bg-sky-50 text-sky-700 border-sky-200', 'Unscheduled' => 'bg-slate-100 text-slate-600 border-slate-200'][$issue->priority];
                        @endphp
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="max-w-md px-4 py-3"><p class="text-[10px] font-black uppercase tracking-wide text-cyan-800">{{ $issue->project?->project_code ?? $issue->project_code ?? 'No project' }}</p><p class="mt-1 text-xs font-bold leading-5 text-slate-900">{{ $issue->issue }}</p></td>
                            <td class="px-4 py-3"><p class="text-xs font-bold text-slate-700">{{ $issue->issue_type ?: 'Uncategorized' }}</p><p class="mt-1 text-[10px] text-slate-500">Raised by {{ $issue->raised_by ?: '—' }}</p></td>
                            <td class="px-4 py-3 text-center"><span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-bold {{ $issueImpactTone }}">{{ $issue->impact ?: '—' }}</span></td>
                            <td class="px-4 py-3 text-center"><span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-bold {{ $issueStatusTone }}">{{ $issue->status ?: '—' }}</span></td>
                            <td class="px-4 py-3 text-center"><span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-bold {{ $issuePriorityTone }}">{{ $issue->priority }}</span>@if($issue->priority !== 'Done' && $issue->days_left !== null)<p class="mt-1 text-[9px] font-semibold {{ $issue->days_left < 0 ? 'text-rose-600' : 'text-slate-400' }}">{{ abs($issue->days_left) }}d {{ $issue->days_left < 0 ? 'overdue' : 'left' }}</p>@endif</td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs font-semibold text-slate-600">{{ $issue->raised_date?->format('d-M-y') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs font-semibold text-slate-600">{{ $issue->planned_resolution_date?->format('d-M-y') ?? 'Unscheduled' }}</td>
                            <td class="px-4 py-3 text-xs font-semibold text-slate-600">{{ $issue->action_by ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-12 text-center"><div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400"><i data-lucide="search-x" class="h-5 w-5"></i></div><p class="mt-3 text-sm font-bold text-slate-700">No matching issues</p><p class="mt-1 text-xs text-slate-500">Adjust the filters to see more results.</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($issues->hasPages())
                <div class="report-no-print border-t border-slate-100 px-4 py-3">{{ $issues->links() }}</div>
            @endif
        </section>
        </section>
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
            const statusColors = ['#0ea5e9', '#f59e0b', '#10b981', '#f43f5e'];

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
                chart: { type: 'donut', height: 300, foreColor: '#64748b', fontFamily: 'Inter, sans-serif', animations: { enabled: true, easing: 'easeinout', speed: 700 }, toolbar: { show: false } },
                series: statusSeries,
                labels: statusLabels,
                colors: statusColors,
                legend: { position: 'bottom', fontSize: '11px', fontWeight: 700, markers: { width: 9, height: 9, radius: 3 }, itemMargin: { horizontal: 10, vertical: 5 }, labels: { colors: '#475569' } },
                plotOptions: { pie: { expandOnClick: false, donut: { size: '72%', labels: { show: true, name: { show: true, fontSize: '11px', fontWeight: 700, color: '#64748b', offsetY: 18 }, value: { show: true, fontSize: '27px', fontWeight: 900, color: '#0f172a', offsetY: -16, formatter: (value) => Math.round(value) }, total: { show: true, label: 'Total issues', fontSize: '10px', fontWeight: 800, color: '#64748b', formatter: () => statusSeries.reduce((sum, value) => sum + value, 0) } } } } },
                dataLabels: { enabled: true, dropShadow: { enabled: false }, style: { fontSize: '10px', fontWeight: 800 }, formatter: (val) => val >= 5 ? `${Math.round(val)}%` : '' },
                stroke: { show: true, width: 4, colors: ['#fff'], lineCap: 'round' },
                states: { hover: { filter: { type: 'lighten', value: 0.06 } }, active: { filter: { type: 'none' } } },
                tooltip: { theme: 'light', y: { formatter: value => `${value} issues` } }
            });

            createChartSafe('#impactChart', {
                chart: { type: 'donut', height: 300, foreColor: '#64748b', fontFamily: 'Inter, sans-serif', animations: { enabled: true, easing: 'easeinout', speed: 700 }, toolbar: { show: false } },
                series: impactSeries,
                labels: ['High', 'Medium', 'Low'],
                colors: impactColors,
                legend: { position: 'bottom', fontSize: '11px', fontWeight: 700, markers: { width: 9, height: 9, radius: 3 }, itemMargin: { horizontal: 10, vertical: 5 }, labels: { colors: '#475569' } },
                plotOptions: { pie: { expandOnClick: false, donut: { size: '72%', labels: { show: true, value: { fontSize: '25px', fontWeight: 900, color: '#0f172a' }, total: { show: true, label: 'Total issues', fontSize: '10px', fontWeight: 800, color: '#64748b' } } } } },
                dataLabels: { enabled: true, dropShadow: { enabled: false }, style: { fontSize: '10px', fontWeight: 800 }, formatter: (val) => val >= 5 ? `${Math.round(val)}%` : '' },
                stroke: { show: true, width: 4, colors: ['#fff'], lineCap: 'round' },
                states: { hover: { filter: { type: 'lighten', value: 0.06 } }, active: { filter: { type: 'none' } } },
                tooltip: { theme: 'light', y: { formatter: value => `${value} issues` } }
            });

            createChartSafe('#trendChart', {
                chart: { type: 'area', height: 280, foreColor: '#64748b', fontFamily: 'Inter, sans-serif', animations: { enabled: true, easing: 'easeinout', speed: 700 }, toolbar: { show: false }, zoom: { enabled: false } },
                series: [{ name: 'Issues', data: Object.values(trendData) }],
                xaxis: { categories: Object.keys(trendData), labels: { style: { fontSize: '10px', fontWeight: 700, colors: '#64748b' } }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { min: 0, forceNiceScale: true, labels: { style: { fontSize: '10px', fontWeight: 600, colors: '#94a3b8' }, formatter: (val) => Math.round(val) } },
                colors: ['#00ADC5'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3, lineCap: 'round' },
                markers: { size: 4, strokeWidth: 2, strokeColors: '#fff', hover: { size: 6 } },
                fill: { type: 'gradient', gradient: { shadeIntensity: 0.5, opacityFrom: 0.35, opacityTo: 0.03, stops: [0, 90, 100] } },
                grid: { borderColor: '#e8eef5', strokeDashArray: 4, padding: { left: 8, right: 12, top: 8, bottom: 4 } },
                tooltip: { theme: 'light', y: { formatter: value => `${value} issues` } }
            });

            createChartSafe('#typeChart', {
                chart: { type: 'bar', height: 300, foreColor: '#64748b', fontFamily: 'Inter, sans-serif', animations: { enabled: true, easing: 'easeinout', speed: 700 }, toolbar: { show: false } },
                series: [{ name: 'Issues', data: typeValues }],
                xaxis: { categories: typeLabels.map(l => l.length > 20 ? l.substring(0, 18) + '…' : l), labels: { style: { fontSize: '9px', fontWeight: 700, colors: '#475569' }, rotate: -30, rotateAlways: true }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' }, formatter: (val) => Math.round(val) } },
                colors: typeColors,
                dataLabels: { enabled: true, offsetY: -16, style: { fontSize: '10px', fontWeight: 800, colors: ['#475569'] }, background: { enabled: false }, formatter: (val) => val > 0 ? val : '' },
                plotOptions: { bar: { borderRadius: 6, borderRadiusApplication: 'end', columnWidth: '48%', distributed: true, dataLabels: { position: 'top' } } },
                grid: { borderColor: '#e8eef5', strokeDashArray: 4, padding: { left: 8, right: 12, top: 8, bottom: 4 } },
                tooltip: { theme: 'light', y: { formatter: value => `${value} issues` } }
            });

            createChartSafe('#sectorChart', {
                chart: { type: 'bar', height: 300, foreColor: '#64748b', fontFamily: 'Inter, sans-serif', animations: { enabled: true, easing: 'easeinout', speed: 700 }, toolbar: { show: false } },
                series: [{ name: 'Issues', data: sectorValues }],
                xaxis: { categories: sectorLabels, labels: { style: { fontSize: '9px', fontWeight: 700, colors: '#475569' } }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' }, formatter: (val) => Math.round(val) } },
                colors: sectorColors,
                dataLabels: { enabled: true, offsetY: -16, style: { fontSize: '10px', fontWeight: 800, colors: ['#475569'] }, background: { enabled: false }, formatter: (val) => val > 0 ? val : '' },
                plotOptions: { bar: { borderRadius: 6, borderRadiusApplication: 'end', columnWidth: '48%', distributed: true, dataLabels: { position: 'top' } } },
                grid: { borderColor: '#e8eef5', strokeDashArray: 4, padding: { left: 8, right: 12, top: 8, bottom: 4 } },
                tooltip: { theme: 'light', y: { formatter: value => `${value} issues` } }
            });

            if (window.lucide) lucide.createIcons();
        });

        function toggleFilterPanel(button, panelId) {
            const panel = document.getElementById(panelId);
            if (!panel) return;
            const isHidden = panel.classList.toggle('hidden');
            button.setAttribute('aria-expanded', String(!isHidden));
        }

        function exportCSV() {
            const records = @json($exportIssues ?? []);
            const columns = [
                ['Project', 'project'], ['Issue', 'issue'], ['Issue Type', 'type'], ['Impact', 'impact'],
                ['Status', 'status'], ['Raised By', 'raised_by'], ['Raised Date', 'raised_date'],
                ['Planned Action Date', 'planned_resolution_date'], ['Action By', 'action_by']
            ];
            const escapeCell = value => `"${String(value ?? '').replace(/"/g, '""')}"`;
            const rows = [columns.map(([label]) => escapeCell(label)).join(',')];
            records.forEach(record => rows.push(columns.map(([, key]) => escapeCell(record[key])).join(',')));
            const csv = rows.join('\r\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `issues-report-${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
        }
    </script>
</x-app-layout>
