<div class="p-5 sm:p-6 max-h-[88vh] overflow-y-auto custom-scrollbar">
    <!-- Header -->
    <div class="mb-5">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-xl bg-[#00ADC5]/10 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="users" class="w-4.5 h-4.5"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-slate-900 tracking-tight">Project Coordinators Directory</h3>
                <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.2em] mt-0.5">Assigned Projects &amp; Activity History</p>
            </div>
        </div>
        <p class="text-[8px] font-medium text-slate-500 mt-1">
            View all Project Coordinators, their assigned projects, and recent progress &amp; issue activity.
        </p>
    </div>

    <!-- Search & Filter -->
    <div class="rounded-xl border border-slate-100 bg-white p-3 shadow-sm mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            <div class="relative min-w-0">
                <label class="block text-[7px] font-black uppercase tracking-widest text-slate-400 mb-1">Search Coordinators</label>
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
                    <input type="search" id="coordinatorSearch" placeholder="Name or email..."
                        class="w-full rounded-lg border border-slate-200 bg-white py-1 pl-8 pr-2 text-[9px] text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>
            <div class="min-w-0">
                <label class="block text-[7px] font-black uppercase tracking-widest text-slate-400 mb-1">Filter by Project</label>
                <select id="projectFilter" class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1 text-[9px] text-slate-700 focus:border-cyan-600 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                    <option value="">All projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_code ?: $project->project_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="clearCoordinatorFilters()" class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-[8px] font-black text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                    Clear
                </button>
            </div>
        </div>
        <p class="text-[7px] font-medium text-slate-500 mt-2" id="coordinatorFilterCount">
            Showing <span id="coordinatorVisibleCount">{{ $coordinators->count() }}</span> of {{ $coordinators->count() }} coordinators
        </p>
    </div>

    @if($coordinators->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mb-2">
                <i data-lucide="user-x" class="w-5 h-5"></i>
            </div>
            <h3 class="text-sm font-black text-slate-900">No Project Coordinators Found</h3>
            <p class="text-[8px] uppercase font-black tracking-widest text-slate-400 mt-1">
                Initialize a Project Coordinator identity to begin tracking assignments.
            </p>
        </div>
    @else
        @php
            $totalAssigned = $coordinators->sum(fn($c) => $c->assignedProjects->count());
            $totalUpdates = collect($coordinatorUpdates)->sum(fn($updates) => $updates->count());
            $totalIssues = collect($coordinatorIssues)->sum(fn($issues) => $issues->count());
        @endphp
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mb-5">
            <div class="rounded-xl bg-white border border-slate-100 p-3 shadow-sm">
                <p class="text-[7px] font-black uppercase tracking-widest text-slate-400">Total Coordinators</p>
                <p class="text-lg font-black text-slate-900 tabular-nums mt-0.5">{{ $coordinators->count() }}</p>
            </div>
            <div class="rounded-xl bg-white border border-slate-100 p-3 shadow-sm">
                <p class="text-[7px] font-black uppercase tracking-widest text-slate-400">Projects Assigned</p>
                <p class="text-lg font-black text-slate-900 tabular-nums mt-0.5">{{ $totalAssigned }}</p>
            </div>
            <div class="rounded-xl bg-white border border-slate-100 p-3 shadow-sm">
                <p class="text-[7px] font-black uppercase tracking-widest text-slate-400">Activity Records</p>
                <p class="text-lg font-black text-slate-900 tabular-nums mt-0.5">{{ $totalUpdates + $totalIssues }}</p>
            </div>
        </div>

        <!-- Coordinator List -->
        <div class="space-y-2" id="coordinatorList">
            @foreach($coordinators as $coordinator)
                @php
                    $assignedProjects = $coordinator->assignedProjects;
                    $updates = $coordinatorUpdates[$coordinator->id] ?? collect();
                    $issues = $coordinatorIssues[$coordinator->id] ?? collect();
                    $latestUpdate = $updates->first();
                    $latestIssue = $issues->first();
                @endphp
                <div class="coordinator-card rounded-xl border border-slate-100 bg-white p-3 shadow-sm hover:shadow-md hover:shadow-cyan-50/30 transition-all duration-200 group"
                     data-coordinator-id="{{ $coordinator->id }}"
                     data-coordinator-name="{{ $coordinator->name }}"
                     data-coordinator-email="{{ $coordinator->email }}"
                     data-project-ids="{{ $assignedProjects->pluck('id')->implode(',') }}">
                    <!-- Coordinator Header -->
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20 shrink-0">
                                <span class="font-black text-[8px]">{{ substr($coordinator->name, 0, 1) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-slate-900 leading-tight truncate">{{ $coordinator->name }}</p>
                                <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest truncate">{{ $coordinator->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[7px] font-black uppercase tracking-wider {{ $assignedProjects->isNotEmpty() ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                                <span class="w-1 h-1 h-1 w-1 rounded-full {{ $assignedProjects->isNotEmpty() ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ $assignedProjects->isNotEmpty() ? 'Active' : 'None' }}
                            </span>
                        </div>
                    </div>

                    <!-- Assigned Projects -->
                    @if($assignedProjects->isNotEmpty())
                    <div class="mb-2">
                        <p class="text-[7px] font-black uppercase tracking-widest text-slate-400 mb-1">Assigned Projects</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($assignedProjects as $project)
                                <span class="rounded px-2 py-0.5 bg-[#00ADC5]/10 border border-[#00ADC5]/20 text-[#00ADC5] text-[7px] font-black uppercase tracking-wider">
                                    {{ $project->project_code ?: $project->project_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Activity History -->
                    <div class="space-y-1">
                        @if($updates->isNotEmpty() || $issues->isNotEmpty())
                            <p class="text-[7px] font-black uppercase tracking-widest text-slate-400 mb-1">Recent Activity</p>
                            <div class="space-y-1.5">
                                @foreach($updates->take(4) as $update)
                                    @php
                                        $updateDate = \Carbon\Carbon::parse($update->data_date ?? $update->updated_at)->format('M d');
                                    @endphp
                                    <div class="flex items-start gap-2 px-2 py-1 rounded-lg bg-emerald-50 border border-emerald-100/50">
                                        <span class="text-[7px] font-black text-emerald-600 uppercase tracking-wider shrink-0">{{ $updateDate }}</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[8px] font-black text-slate-900 leading-tight truncate">
                                                {{ $update->project->project_code ?: $update->project->project_name }} — Progress Update
                                            </p>
                                            <div class="flex items-center gap-1 mt-0.5">
                                                <span class="text-[7px] font-bold text-emerald-700">
                                                    Planned {{ number_format($update->progress_planned ?? 0, 0) }}%
                                                </span>
                                                <span class="text-[7px] font-bold text-sky-700">
                                                    Actual {{ number_format($update->progress_actual ?? 0, 0) }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach($issues->take(4) as $issue)
                                    @php
                                        $issueDate = \Carbon\Carbon::parse($issue->raised_date ?? $issue->date ?? $issue->created_at)->format('M d');
                                    @endphp
                                    <div class="flex items-start gap-2 px-2 py-1 rounded-lg bg-rose-50 border border-rose-100/50">
                                        <span class="text-[7px] font-black text-rose-600 uppercase tracking-wider shrink-0">{{ $issueDate }}</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[8px] font-black text-slate-900 leading-tight truncate">
                                                {{ $issue->project->project_code ?: $issue->project->project_name }} — Issue {{ $issue->issue_type }}
                                            </p>
                                            <div class="flex items-center gap-1 mt-0.5">
                                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[7px] font-bold uppercase tracking-wider
                                                    {{ $issue->status === 'Closed' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($issue->status === 'Open' ? 'bg-sky-50 text-sky-700 border-sky-100' : 'bg-slate-100 text-slate-600 border-slate-200') }}">
                                                    {{ $issue->status }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-2 py-1 rounded-lg bg-slate-50 border border-slate-100/50 text-center">
                                <span class="text-[7px] font-medium text-slate-400 italic">No activity</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    if (window.lucide) lucide.createIcons();

    function filterCoordinators() {
        const search = document.getElementById('coordinatorSearch').value.toLowerCase().trim();
        const projectId = document.getElementById('projectFilter').value;
        const cards = document.querySelectorAll('.coordinator-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-coordinator-name').toLowerCase();
            const email = card.getAttribute('data-coordinator-email').toLowerCase();
            const projectIds = card.getAttribute('data-project-ids').split(',').filter(Boolean);

            const matchesSearch = !search || name.includes(search) || email.includes(search);
            const matchesProject = !projectId || projectIds.includes(projectId);

            if (matchesSearch && matchesProject) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        const countEl = document.getElementById('coordinatorVisibleCount');
        if (countEl) {
            countEl.textContent = visibleCount;
        }
    }

    function clearCoordinatorFilters() {
        document.getElementById('coordinatorSearch').value = '';
        document.getElementById('projectFilter').value = '';
        filterCoordinators();
    }

    document.getElementById('coordinatorSearch')?.addEventListener('input', filterCoordinators);
    document.getElementById('projectFilter')?.addEventListener('change', filterCoordinators);
</script>

<style>
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
