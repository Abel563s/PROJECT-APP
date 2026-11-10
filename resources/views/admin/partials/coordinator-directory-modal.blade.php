<div class="p-6 sm:p-8 max-h-[85vh] overflow-y-auto custom-scrollbar">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-[#00ADC5]/10 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Project Coordinators Directory</h3>
                <p class="text-[9px] font-black text-[#00ADC5] uppercase tracking-[0.2em] mt-0.5">Assigned Projects &amp; Update History</p>
            </div>
        </div>
        <p class="text-[9px] font-medium text-slate-500 mt-1">
            View all Project Coordinators, their assigned projects, and recent progress update activity across the system.
        </p>
    </div>

    <!-- Search & Filter -->
    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="relative min-w-0">
                <label class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search Coordinators</label>
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="search" id="coordinatorSearch" placeholder="Name or email..."
                        class="w-full rounded-lg border border-slate-200 bg-white py-1.5 pl-9 pr-3 text-xs text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                </div>
            </div>
            <div class="min-w-0">
                <label class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Filter by Project</label>
                <select id="projectFilter" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-700 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                    <option value="">All projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_code ?: $project->project_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="clearCoordinatorFilters()" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                    Clear
                </button>
            </div>
        </div>
        <p class="text-[8px] font-medium text-slate-500 mt-2" id="coordinatorFilterCount">
            Showing <span id="coordinatorVisibleCount">{{ $coordinators->count() }}</span> of {{ $coordinators->count() }} coordinators
        </p>
    </div>

    @if($coordinators->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mb-3">
                <i data-lucide="user-x" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base font-black text-slate-900">No Project Coordinators Found</h3>
            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400 mt-1">
                Initialize a Project Coordinator identity to begin tracking assignments.
            </p>
        </div>
    @else
        @php
            $totalAssigned = $coordinators->sum(fn($c) => $c->assignedProjects->count());
            $totalUpdates = collect($coordinatorUpdates)->sum(fn($updates) => $updates->count());
        @endphp
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <div class="rounded-2xl bg-white border border-slate-100 p-4 shadow-sm">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Total Coordinators</p>
                <p class="text-2xl font-black text-slate-900 tabular-nums mt-1">{{ $coordinators->count() }}</p>
            </div>
            <div class="rounded-2xl bg-white border border-slate-100 p-4 shadow-sm">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Projects Assigned</p>
                <p class="text-2xl font-black text-slate-900 tabular-nums mt-1">{{ $totalAssigned }}</p>
            </div>
            <div class="rounded-2xl bg-white border border-slate-100 p-4 shadow-sm">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Recent Updates</p>
                <p class="text-2xl font-black text-slate-900 tabular-nums mt-1">{{ $totalUpdates }}</p>
            </div>
        </div>

        <!-- Coordinator List -->
        <div class="space-y-3" id="coordinatorList">
            @foreach($coordinators as $coordinator)
                @php
                    $assignedProjects = $coordinator->assignedProjects;
                    $updates = $coordinatorUpdates[$coordinator->id] ?? collect();
                    $latestUpdate = $updates->first();
                @endphp
                <div class="coordinator-card rounded-2xl border border-slate-100 bg-white p-4 shadow-sm hover:shadow-md hover:shadow-cyan-50/50 transition-all duration-300 group"
                     data-coordinator-id="{{ $coordinator->id }}"
                     data-coordinator-name="{{ $coordinator->name }}"
                     data-coordinator-email="{{ $coordinator->email }}"
                     data-project-ids="{{ $assignedProjects->pluck('id')->implode(',') }}">
                    <!-- Coordinator Header -->
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20 shrink-0">
                                <span class="font-black text-[10px]">{{ substr($coordinator->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900 leading-tight">{{ $coordinator->name }}</p>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $coordinator->email }}</p>
                                @if($coordinator->businessUnit)
                                    <span class="inline-flex items-center gap-1 mt-0.5">
                                        <i data-lucide="building-2" class="w-2.5 h-2.5 text-[#00ADC5]"></i>
                                        <span class="text-[8px] font-black text-slate-500">{{ $coordinator->businessUnit->name }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider {{ $assignedProjects->isNotEmpty() ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $assignedProjects->isNotEmpty() ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ $assignedProjects->isNotEmpty() ? 'Active' : 'No Projects' }}
                            </span>
                        </div>
                    </div>

                    <!-- Assigned Projects -->
                    <div class="mb-3">
                        <p class="text-[8px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Assigned Projects</p>
                        @if($assignedProjects->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($assignedProjects as $project)
                                    <span class="rounded-lg px-2.5 py-1 bg-[#00ADC5]/10 border border-[#00ADC5]/20 text-[#00ADC5] text-[8px] font-black uppercase tracking-wider">
                                        {{ $project->project_code ?: $project->project_name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-[8px] font-medium text-slate-400 italic">No projects assigned</span>
                        @endif
                    </div>

                    <!-- Update History -->
                    <div>
                        <p class="text-[8px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Recent Update History</p>
                        @if($updates->isNotEmpty())
                            <div class="space-y-2">
                                @foreach($updates as $update)
                                    @php
                                        $updateDate = \Carbon\Carbon::parse($update->data_date ?? $update->updated_at)->format('M d, Y');
                                    @endphp
                                    <div class="flex items-start gap-3 px-3 py-2 rounded-xl bg-slate-50/50 border border-slate-100/50">
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-wider">{{ $updateDate }}</span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-black text-slate-900 leading-tight truncate">
                                                {{ $update->project->project_code ?: $update->project->project_name }}
                                            </p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-lg text-[7px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border-emerald-100">
                                                    <i data-lucide="trending-up" class="w-2 h-2"></i>
                                                    Planned: {{ number_format($update->progress_planned ?? 0, 0) }}%
                                                </span>
                                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-lg text-[7px] font-bold uppercase tracking-wider bg-sky-50 text-sky-700 border-sky-100">
                                                    <i data-lucide="trending-down" class="w-2 h-2"></i>
                                                    Actual: {{ number_format($update->progress_actual ?? 0, 0) }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-100/50 text-center">
                                <span class="text-[8px] font-medium text-slate-400 italic">No update history</span>
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
