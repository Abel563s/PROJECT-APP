<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Issue <span class="font-black text-slate-900">Registry</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Project Issue Management</p>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-white/90"></i>
                    <span class="text-base font-black text-white tracking-tight">{{ $projects->count() }}</span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Projects Registered</span>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        @php
            $issuesFiltersActive = request()->anyFilled(['search', 'status', 'issue_status', 'priority', 'impact', 'business_unit_id', 'sector_id']);
            $issuesAdvancedFiltersActive = request()->anyFilled(['issue_status', 'status', 'priority', 'impact']);
        @endphp
        <form action="{{ route('admin.projects.issues.index') }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="admin-filter-grid gap-3">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Projects</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, code, or ID"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>
                @include('admin.partials.project-organization-filters')
                <div class="flex flex-wrap items-center justify-start gap-2 xl:justify-end">
                    <button type="button" aria-controls="issuesAdvancedFilters" aria-expanded="{{ $issuesAdvancedFiltersActive ? 'true' : 'false' }}" onclick="toggleFilterPanel(this, 'issuesAdvancedFilters')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[9px] font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-[#00515f]">
                        <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i>More filters
                        <i data-lucide="chevron-down" class="filter-panel-chevron h-3.5 w-3.5 transition-transform {{ $issuesAdvancedFiltersActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    @if($issuesFiltersActive)
                        <a href="{{ route('admin.projects.issues.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition hover:bg-rose-500 hover:text-white"><i data-lucide="x" class="h-3.5 w-3.5"></i>Clear filters</a>
                    @endif
                </div>
                <div id="issuesAdvancedFilters" class="admin-filter-advanced-panel {{ $issuesAdvancedFiltersActive ? '' : 'hidden' }} rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                    <div class="admin-filter-advanced-grid gap-3">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Issue Status</span>
                    <select name="issue_status" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Statuses</option>
                        <option value="Raised" @selected(request('issue_status') === 'Raised')>Raised</option>
                        <option value="Open" @selected(request('issue_status') === 'Open')>Open</option>
                        <option value="Closed" @selected(request('issue_status') === 'Closed')>Closed</option>
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project Status</span>
                    <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="all" @selected(request('status', 'in progress') === 'all')>All Projects</option>
                        <option value="in progress" @selected(request('status', 'in progress') === 'in progress')>In Progress</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Priority</span>
                    <select name="priority" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Priorities</option>
                        @foreach(['Overdue', 'High', 'Medium', 'Low'] as $priority)
                            <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact</span>
                    <select name="impact" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Impacts</option>
                        @foreach(['High', 'Medium', 'Low'] as $impact)
                            <option value="{{ $impact }}" @selected(request('impact') === $impact)>{{ $impact }}</option>
                        @endforeach
                    </select>
                </label>
                    </div>
                </div>
            </div>
        </form>
        @include('admin.partials.filter-panel-toggle-script')

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Project Code</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Total Issues</th>
                            <th colspan="3" class="px-6 py-2 text-[9px] font-black text-white uppercase tracking-widest border-b border-white/10 text-center">Status</th>
                            <th colspan="4" class="px-6 py-2 text-[9px] font-black text-white uppercase tracking-widest border-b border-white/10 text-center">Priority</th>
                            <th colspan="3" class="px-6 py-2 text-[9px] font-black text-white uppercase tracking-widest border-b border-white/10 text-center">Impact</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Actions</th>
                        </tr>
                        <tr class="bg-slate-900 text-[8px] font-black uppercase tracking-widest text-white/80">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="px-3 py-2 text-center">Raised</th>
                            <th class="px-3 py-2 text-center">Open</th>
                            <th class="px-3 py-2 text-center">Closed</th>
                            <th class="px-3 py-2 text-center">Overdue</th>
                            <th class="px-3 py-2 text-center">High</th>
                            <th class="px-3 py-2 text-center">Medium</th>
                            <th class="px-3 py-2 text-center">Low</th>
                            <th class="px-3 py-2 text-center">High</th>
                            <th class="px-3 py-2 text-center">Medium</th>
                            <th class="px-3 py-2 text-center">Low</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($projects as $project)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $project->project_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">{{ $project->project_code }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-900 border border-slate-200 shadow-sm">
                                        {{ $project->total_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                        {{ $project->raised_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-sky-50 text-sky-600 border-sky-200 border shadow-sm">
                                        {{ $project->open_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border-emerald-200 border shadow-sm">
                                        {{ $project->closed_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-rose-50 text-rose-600 border-rose-200 border shadow-sm">
                                        {{ $project->overdue_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-orange-50 text-orange-600 border-orange-200 border shadow-sm">
                                        {{ $project->high_priority_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-amber-50 text-amber-600 border-amber-200 border shadow-sm">
                                        {{ $project->medium_priority_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-cyan-50 text-cyan-600 border-cyan-200 border shadow-sm">
                                        {{ $project->low_priority_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-rose-50 text-rose-600 border-rose-200 border shadow-sm">
                                        {{ $project->high_impact_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-orange-50 text-orange-600 border-orange-200 border shadow-sm">
                                        {{ $project->medium_impact_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-sky-50 text-sky-600 border-sky-200 border shadow-sm">
                                        {{ $project->low_impact_issues }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.projects.issues.manage', $project) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 text-slate-700 font-black text-[9px] uppercase tracking-widest rounded-lg hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-300 shadow-sm">
                                        <span>Manage</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="database" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">No Projects Found</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Create a project first to initialize issue tracking.</p>
                                        </div>
                                        <a href="{{ route('admin.projects.create') }}"
                                            class="px-5 py-2 bg-slate-900 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                                            Initialize First Project
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
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
