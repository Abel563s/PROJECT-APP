<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.risk.index') }}"
                   class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-[#00ADC5]/20 hover:text-[#00ADC5]">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold leading-none tracking-tight text-slate-900">
                        Risk <span class="font-black">Register</span>
                    </h2>
                    <p class="mt-1 text-[8px] font-black uppercase tracking-[0.3em] text-[#00ADC5]">{{ $project->project_code }}</p>
                </div>
            </div>
            <div class="hidden lg:flex justify-center">
                @php
                    $coordinators = $project->coordinators->map(fn ($c) => $c->name ?? $c->email ?? 'Coordinator');
                @endphp
                @if($coordinators->count())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#00ADC5]/10 border border-[#00ADC5]/20 text-[#00515f] text-[9px] font-black uppercase tracking-widest shadow-sm">
                        <i data-lucide="user" class="w-3 h-3"></i>
                        {{ $coordinators->implode(', ') }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-500 text-[9px] font-black uppercase tracking-widest shadow-sm">
                        <i data-lucide="user" class="w-3 h-3"></i>
                        No Coordinator Assigned
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-2 justify-end lg:justify-self-end">
                @if($canManageProject)
                    <button onclick="openCreateModal()"
                        class="group/btn relative overflow-hidden rounded-xl bg-slate-900 px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-slate-900/20 transition-all hover:scale-105 active:scale-95">
                        <span class="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-[#00ADC5] to-blue-500 transition-transform duration-500 group-hover/btn:translate-x-0"></span>
                        <span class="relative flex items-center gap-1.5"><i data-lucide="plus" class="h-3 w-3"></i>New Risk</span>
                    </button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <!-- Summary Cards -->
        <div class="-mx-4 overflow-x-auto px-4 pb-1 lg:mx-0 lg:px-0">
            <div class="grid grid-cols-8 gap-3 min-w-[1152px]" style="grid-template-columns: repeat(8, minmax(0, 1fr)); min-width: 1152px;">
                @php
                    $kpis = [
                        ['label' => 'Total Risks', 'key' => 'total', 'icon' => 'alert-triangle', 'tone' => 'slate'],
                        ['label' => 'Risk', 'key' => 'risk', 'icon' => 'circle', 'tone' => 'sky'],
                        ['label' => 'Issue', 'key' => 'issue', 'icon' => 'triangle-alert', 'tone' => 'amber'],
                        ['label' => 'Resolved', 'key' => 'resolved', 'icon' => 'check-circle', 'tone' => 'emerald'],
                        ['label' => 'Extreme', 'key' => 'extreme', 'icon' => 'zap', 'tone' => 'rose'],
                        ['label' => 'High', 'key' => 'high', 'icon' => 'triangle-alert', 'tone' => 'orange'],
                        ['label' => 'Medium', 'key' => 'medium', 'icon' => 'minus', 'tone' => 'amber'],
                        ['label' => 'Low', 'key' => 'low', 'icon' => 'arrow-down', 'tone' => 'cyan'],
                    ];
                    $kpiTones = [
                        'slate' => ['icon' => 'bg-slate-100 text-slate-600', 'value' => 'text-slate-900', 'border' => 'hover:border-slate-300'],
                        'sky' => ['icon' => 'bg-sky-100 text-sky-700', 'value' => 'text-sky-700', 'border' => 'hover:border-sky-200'],
                        'amber' => ['icon' => 'bg-amber-100 text-amber-700', 'value' => 'text-amber-700', 'border' => 'hover:border-amber-200'],
                        'emerald' => ['icon' => 'bg-emerald-100 text-emerald-700', 'value' => 'text-emerald-700', 'border' => 'hover:border-emerald-200'],
                        'rose' => ['icon' => 'bg-rose-100 text-rose-700', 'value' => 'text-rose-700', 'border' => 'hover:border-rose-200'],
                        'orange' => ['icon' => 'bg-orange-100 text-orange-700', 'value' => 'text-orange-700', 'border' => 'hover:border-orange-200'],
                        'cyan' => ['icon' => 'bg-cyan-100 text-cyan-700', 'value' => 'text-cyan-700', 'border' => 'hover:border-cyan-200'],
                    ];
                @endphp
                @foreach($kpis as $kpi)
                    <div class="group rounded-2xl border border-slate-100 bg-gradient-to-br from-white to-slate-50/80 p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md {{ $kpiTones[$kpi['tone']]['border'] }}">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $kpiTones[$kpi['tone']]['icon'] }} transition-transform group-hover:scale-105">
                                <i data-lucide="{{ $kpi['icon'] }}" class="h-4 w-4"></i>
                            </div>
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $kpi['label'] }}</span>
                        </div>
                        <p class="mt-3 text-2xl font-black leading-none tabular-nums {{ $kpiTones[$kpi['tone']]['value'] }}">{{ $stats[$kpi['key']] }}</p>
                        <p class="mt-1 text-[8px] font-bold uppercase tracking-wider text-slate-400">{{ in_array($kpi['key'], ['risk', 'issue', 'resolved']) ? 'By status' : (in_array($kpi['key'], ['extreme', 'high', 'medium', 'low']) ? 'By rating' : 'All records') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

         <!-- Search & Filter -->
         @php
            $riskManageAdvancedFiltersActive = request()->anyFilled(['risk_level', 'risk_urgency', 'action_status']);
            $riskManageFiltersActive = request()->anyFilled(['search', 'risk_category', 'risk_type', 'risk_level', 'risk_urgency', 'action_status']);
        @endphp
        <form action="{{ route('admin.projects.risk.manage', $project) }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="admin-filter-grid gap-3">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Risks</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search risks..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Category</span>
                    <select name="risk_category" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Categories</option>
                        @foreach($riskCategories as $category)
                            <option value="{{ $category }}" @selected(request('risk_category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Type</span>
                    <select name="risk_type" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Types</option>
                        @foreach(['Opportunity', 'Threat'] as $type)
                            <option value="{{ $type }}" @selected(request('risk_type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" aria-controls="riskManageAdvancedFilters" aria-expanded="{{ $riskManageAdvancedFiltersActive ? 'true' : 'false' }}" onclick="toggleFilterPanel(this, 'riskManageAdvancedFilters')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[9px] font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-[#00515f]">
                        <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i>More filters
                        <i data-lucide="chevron-down" class="filter-panel-chevron h-3.5 w-3.5 transition-transform {{ $riskManageAdvancedFiltersActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    @if($riskManageFiltersActive)
                        <a href="{{ route('admin.projects.risk.manage', $project) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition hover:bg-rose-500 hover:text-white">
                            <i data-lucide="x" class="h-3.5 w-3.5"></i>Clear filters
                        </a>
                    @endif
                </div>
                <div id="riskManageAdvancedFilters" class="admin-filter-advanced-panel {{ $riskManageAdvancedFiltersActive ? '' : 'hidden' }} rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                    <div class="admin-filter-advanced-grid">
                        <label class="space-y-1">
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Rating</span>
                            <select name="risk_level" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                                <option value="">All Ratings</option>
                                @foreach(['Low', 'Medium', 'High', 'Extreme'] as $level)
                                    <option value="{{ $level }}" @selected(request('risk_level') === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="space-y-1">
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Urgency</span>
                            <select name="risk_urgency" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                                <option value="">All Urgencies</option>
                                @foreach(['Very Urgent', 'Urgent', 'Moderate', 'Low'] as $urgency)
                                    <option value="{{ $urgency }}" @selected(request('risk_urgency') === $urgency)>{{ $urgency }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="space-y-1">
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Status</span>
                            <select name="action_status" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                                <option value="">All Statuses</option>
                                @foreach(\App\Models\ProjectRisk::ACTION_STATUSES as $status)
                                    <option value="{{ $status }}" @selected(request('action_status') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
            </div>
        </form>
        @include('admin.partials.filter-panel-toggle-script')

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#00515f]/30 bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black uppercase tracking-widest text-white">
                            <th class="px-4 py-3 text-center w-[32px]">#</th>
                            <th class="px-4 py-3 text-center w-[100px]">Risk ID</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Risk Sub Category</th>
                            <th class="px-4 py-3 text-center">Type</th>
                            <th class="px-4 py-3 text-center">Rating (P × I)</th>
                            <th class="px-4 py-3 text-center">Urgency</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3">Risk Owner</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($risks as $risk)
                            @php
                                $ratingClasses = [
                                    'Low' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'High' => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'Extreme' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $statusClasses = [
                                    'Risk' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'Issue' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                ];
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-center w-[32px]"><span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-[10px] font-black tabular-nums text-slate-900">{{ $risks->firstItem() + $loop->index }}</span></td>
                                <td class="px-4 py-3 text-center w-[100px]"><span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-[10px] font-bold text-slate-600 tabular-nums">{{ $risk->pool_risk_id ?: '---' }}</span></td>
                                <td class="px-4 py-3"><span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700">{{ $risk->risk_category }}</span></td>
                                <td class="max-w-xs px-4 py-3">
                                    <p class="text-xs font-black leading-tight text-slate-900">{{ $risk->risk_name }}</p>
                                    <p class="mt-1 line-clamp-2 text-[10px] text-slate-500">{{ $risk->risk_description }}</p>
                                </td>
                                <td class="px-4 py-3 text-center"><span class="text-[10px] font-bold text-slate-700">{{ $risk->risk_type }}</span></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex flex-col items-center rounded-lg border px-2.5 py-1 {{ $ratingClasses[$risk->risk_level] }}">
                                        <span class="text-[10px] font-black">{{ $risk->risk_score }}</span>
                                        <span class="text-[8px] font-black uppercase tracking-wider">{{ $risk->risk_level }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-[10px] font-bold text-slate-600">{{ $risk->risk_urgency ?: '---' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $statusClasses[$risk->action_status] ?? 'border-slate-200 bg-slate-100 text-slate-500' }}">
                                        {{ $risk->action_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-[10px] font-bold text-slate-700">{{ $risk->owner_name ?: $risk->risk_owner ?: '---' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openViewModal({{ $risk->id }})"
                                            class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-sky-500 hover:border-sky-500 hover:shadow-sm transition-all"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @if($canManageProject && (auth()->user()->isAdmin() || auth()->user()->isSuperadmin() || auth()->user()->isUser()))
                                            <button onclick="openEditModal({{ $risk->id }})"
                                                class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-amber-500 hover:border-amber-500 hover:shadow-sm transition-all"
                                                title="Edit">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endif
                                        @if($canManageProject && (auth()->user()->isAdmin() || auth()->user()->isSuperadmin()))
                                            <form action="{{ route('admin.projects.risk.destroy', $risk->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this risk?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-rose-500 hover:border-rose-500 hover:shadow-sm transition-all"
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
                                <td colspan="10" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-200"><i data-lucide="alert-triangle" class="h-8 w-8"></i></div>
                                        <div><h3 class="text-lg font-black text-slate-900">No Risks Registered</h3><p class="mt-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Create a risk to start tracking this project.</p></div>
                                        @if($canManageProject)
                                            <button onclick="openCreateModal()" class="mt-1 rounded-lg bg-slate-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white hover:bg-cyan-700">Add First Risk</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/30 p-4 sm:flex-row sm:items-center sm:justify-between">
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Showing {{ $risks->firstItem() ?? 0 }} to {{ $risks->lastItem() ?? 0 }} of {{ $risks->total() }} risks</span>
                <div>{{ $risks->links() }}</div>
            </div>
        </div>
    </div>

    <div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" style="position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:1rem; background-color:rgba(15,23,42,0.4);">
        <div class="risk-modal-dialog flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl" style="display:flex; flex-direction:column; max-height:92vh; width:100%; max-width:56rem; overflow:hidden; border-radius:1rem; background-color:white;">
            <div class="flex flex-shrink-0 items-center justify-between border-b border-[#00515f] bg-gradient-to-b from-[#00515f] to-black px-6 py-3">
                <div>
                    <h3 class="text-base font-black text-white">New Risk</h3>
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-300">Risk assessment for {{ $project->project_code }}</p>
                </div>
                <button type="button" onclick="closeCreateModal()" aria-label="Close"
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white/80 transition-colors hover:bg-white/20">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <div id="createModalContent" class="flex-1 overflow-y-auto p-5">
                <div class="flex items-center justify-center py-12"><div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div></div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden" style="position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:1rem; background-color:rgba(15,23,42,0.4);">
        <div class="risk-modal-dialog bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" style="width:100%; max-width:48rem; overflow:hidden; display:flex; flex-direction:column; max-height:90vh; background-color:white; border-radius:1rem;">
            <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white">Risk Details</h3>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">View risk information</p>
                    </div>
                </div>
                <button type="button" onclick="closeViewModal()" aria-label="Close"
                    class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <div id="viewModalContent" class="p-5 overflow-y-auto flex-1">
                <div class="flex items-center justify-center py-12">
                    <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden" style="position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:1rem; background-color:rgba(15,23,42,0.4);">
        <div class="risk-modal-dialog bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]" style="width:100%; max-width:56rem; overflow:hidden; display:flex; flex-direction:column; max-height:90vh; background-color:white; border-radius:1rem;">
            <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white">Edit Risk</h3>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Update risk details</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" aria-label="Close"
                    class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <div id="editModalContent" class="p-6 overflow-y-auto flex-1">
                <div class="flex items-center justify-center py-12">
                    <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            const modal = document.getElementById('createModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.style.display = 'flex';
            syncRiskModalBodyLock();

            fetch(@json(route('admin.projects.risk.create-modal', $project)))
                .then(response => {
                    if (!response.ok) throw new Error(`Unable to load the risk form (${response.status}).`);
                    return response.text();
                })
                 .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    if (window.lucide) lucide.createIcons();
                    updateRiskRating(document.getElementById('createModalContent'));
                    initRiskResponseStrategies(document.getElementById('createModalContent'));
                    initPoolRiskPicker(document.getElementById('createModalContent'));
                    initEotPicker(document.getElementById('createModalContent'));
                    initRiskExposure(document.getElementById('createModalContent'));
                    initRiskUrgency(document.getElementById('createModalContent'));
                    initRiskStepper(document.getElementById('createModalContent'));
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('createModalContent').innerHTML = '<p class="rounded-xl bg-rose-50 p-4 text-xs font-bold text-rose-700">Unable to load the risk form. Please try again.</p>';
                });
        }

        function closeCreateModal() {
            const modal = document.getElementById('createModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.style.display = 'none';
            syncRiskModalBodyLock();
        }

        function syncRiskModalBodyLock() {
            const isModalOpen = ['createModal', 'viewModal', 'editModal'].some(id => {
                const modal = document.getElementById(id);
                return modal && modal.style.display === 'flex';
            });
            document.body.style.overflow = isModalOpen ? 'hidden' : '';
        }

        function updateRiskRating(container = document) {
            const likelihoodInput = container.querySelector('#likelihood');
            const severityInput = container.querySelector('#severity');
            if (!likelihoodInput || !severityInput) return;

            const likelihood = Number(likelihoodInput.value);
            const severity = Number(severityInput.value);
            const sliderColors = {
                1: '#16a34a',
                2: '#84cc16',
                3: '#f59e0b',
                4: '#f97316',
                5: '#e11d48',
            };
            likelihoodInput.style.accentColor = sliderColors[likelihood] || sliderColors[3];
            severityInput.style.accentColor = sliderColors[severity] || sliderColors[3];
            const score = likelihood * severity;
            const level = score >= 17 ? 'Extreme' : score >= 10 ? 'High' : score >= 5 ? 'Medium' : 'Low';
            const colors = { Low: 'text-emerald-600', Medium: 'text-amber-600', High: 'text-orange-600', Extreme: 'text-rose-600' };

            container.querySelector('#likelihoodValue').textContent = likelihood;
            container.querySelector('#severityValue').textContent = severity;
            container.querySelector('#riskRatingScore').textContent = score;
            container.querySelector('#riskRatingLevel').textContent = level;
            container.querySelector('#riskRatingLevel').className = `text-[9px] font-black uppercase tracking-widest ${colors[level]}`;
        }

        function initPoolRiskPicker(container) {
            const url = @json(route('admin.projects.risk.pool-risks', $project));
            const type = container.querySelector('#risk_type');
            const cat = container.querySelector('#pool_risk_category');
            const idSelect = container.querySelector('#pool_risk_id');
            const riskName = container.querySelector('#risk_name');
            const riskDesc = container.querySelector('#risk_description');
            const poolCategoryHidden = container.querySelector('#pool_risk_category_hidden');
            const poolRiskIdHidden = container.querySelector('#pool_risk_id_hidden');
            const impactSummary = container.querySelector('#poolImpactSummary');
            const impactTimeline = container.querySelector('#poolImpactTimeline');
            const impactTimelineDesc = container.querySelector('#poolImpactTimelineDesc');
            const impactCost = container.querySelector('#poolImpactCost');
            const impactCostDesc = container.querySelector('#poolImpactCostDesc');
            const impactQuality = container.querySelector('#poolImpactQuality');
            const impactQualityDesc = container.querySelector('#poolImpactQualityDesc');
            if (!type || !cat || !idSelect) return;

            const impactClasses = {
                'High': 'bg-rose-50 text-rose-700 border-rose-200',
                'Medium': 'bg-amber-50 text-amber-700 border-amber-200',
                'Low': 'bg-emerald-50 text-emerald-700 border-emerald-200',
            };

            function applyImpact(el, level) {
                if (level) {
                    el.textContent = level;
                    el.className = 'mt-1 inline-flex rounded-lg border px-2 py-1 text-[9px] font-black uppercase tracking-wider ' + (impactClasses[level] || 'border-slate-200 bg-slate-100 text-slate-400');
                } else {
                    el.textContent = '—';
                    el.className = 'mt-1 inline-flex rounded-lg border border-slate-200 bg-slate-100 px-2 py-1 text-[9px] font-black uppercase tracking-wider text-slate-400';
                }
            }

            function showImpact(timeline, cost, quality, timelineDesc = '', costDesc = '', qualityDesc = '') {
                if (impactTimeline) applyImpact(impactTimeline, timeline);
                if (impactCost) applyImpact(impactCost, cost);
                if (impactQuality) applyImpact(impactQuality, quality);
                if (impactTimelineDesc) {
                    impactTimelineDesc.textContent = timelineDesc || '';
                    impactTimelineDesc.classList.toggle('hidden', !timelineDesc);
                }
                if (impactCostDesc) {
                    impactCostDesc.textContent = costDesc || '';
                    impactCostDesc.classList.toggle('hidden', !costDesc);
                }
                if (impactQualityDesc) {
                    impactQualityDesc.textContent = qualityDesc || '';
                    impactQualityDesc.classList.toggle('hidden', !qualityDesc);
                }
                if (impactSummary) {
                    const hasSelected = Boolean(idSelect.value);
                    impactSummary.classList.toggle('hidden', !hasSelected);
                }
            }

            function clearImpact() {
                if (impactSummary) impactSummary.classList.add('hidden');
                if (poolRiskIdHidden) poolRiskIdHidden.value = '';
                if (riskName) riskName.value = '';
                if (impactTimelineDesc) { impactTimelineDesc.textContent = ''; impactTimelineDesc.classList.add('hidden'); }
                if (impactCostDesc) { impactCostDesc.textContent = ''; impactCostDesc.classList.add('hidden'); }
                if (impactQualityDesc) { impactQualityDesc.textContent = ''; impactQualityDesc.classList.add('hidden'); }
            }

            async function loadRisks(category, riskType, selectedId = '') {
                idSelect.innerHTML = '<option value="">Select risk</option>';
                idSelect.disabled = !category;
                clearImpact();
                if (!category) return;
                try {
                    const res = await fetch(url + '?risk_type=' + encodeURIComponent(riskType) + '&category=' + encodeURIComponent(category));
                    if (!res.ok) throw new Error('Unable to load repository risks (' + res.status + ').');
                    const json = await res.json();
                    json.forEach(r => {
                        const o = document.createElement('option');
                        o.value = r.risk_id;
                        o.textContent = (r.risk_id || '') + ' — ' + (r.specific_risk || '');
                        o.dataset.specific = r.specific_risk || '';
                        o.dataset.detail = r.detailed_risk_explanation || '';
                        o.dataset.timeline = r.impact_on_timeline || '';
                        o.dataset.timelineDesc = r.impact_on_timeline_description || '';
                        o.dataset.cost = r.impact_on_cost || '';
                        o.dataset.costDesc = r.impact_on_cost_description || '';
                        o.dataset.quality = r.overall_impact_level || '';
                        o.dataset.qualityDesc = r.impact_on_quality_description || '';
                        idSelect.appendChild(o);
                    });
                    if (selectedId && [...idSelect.options].some(option => option.value === selectedId)) {
                        idSelect.value = selectedId;
                        idSelect.disabled = false;
                        idSelect.dispatchEvent(new Event('change'));
                    }
                } catch (e) {
                    console.error(e);
                    alert('Unable to load risks for this category.');
                }
            }

            async function loadCategories(riskType, selectedCategory = '', selectedId = '') {
                cat.innerHTML = '<option value="">Select pool category</option>';
                cat.disabled = !riskType;
                if (poolCategoryHidden) poolCategoryHidden.value = '';
                if (poolRiskIdHidden) poolRiskIdHidden.value = '';
                clearImpact();
                idSelect.innerHTML = '<option value="">Select category first</option>';
                idSelect.disabled = true;
                if (!riskType) return;
                try {
                    const res = await fetch(url + '?risk_type=' + encodeURIComponent(riskType));
                    if (!res.ok) throw new Error('Unable to load repository categories (' + res.status + ').');
                    const categories = await res.json();
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category;
                        option.textContent = category;
                        cat.appendChild(option);
                    });
                    if (selectedCategory && categories.includes(selectedCategory)) {
                        cat.value = selectedCategory;
                        if (poolCategoryHidden) poolCategoryHidden.value = selectedCategory;
                        await loadRisks(selectedCategory, riskType, selectedId);
                    }
                } catch (e) {
                    console.error(e);
                    alert('Unable to load repository categories for this risk type.');
                }
            }

            type.addEventListener('change', () => loadCategories(type.value));
            cat.addEventListener('change', () => {
                const category = cat.value;
                if (poolCategoryHidden) poolCategoryHidden.value = category;
                loadRisks(category, type.value);
            });

            idSelect.addEventListener('change', () => {
                const opt = idSelect.options[idSelect.selectedIndex];
                if (!opt || !opt.value) { clearImpact(); return; }
                if (riskName) riskName.value = opt.dataset.specific || '';
                if (riskDesc && !riskDesc.value) riskDesc.value = opt.dataset.detail || '';
                if (poolRiskIdHidden) poolRiskIdHidden.value = opt.value;
                showImpact(
                    opt.dataset.timeline,
                    opt.dataset.cost,
                    opt.dataset.quality,
                    opt.dataset.timelineDesc,
                    opt.dataset.costDesc,
                    opt.dataset.qualityDesc
                );
            });

            loadCategories(
                type.value,
                cat.dataset.selectedCategory || '',
                idSelect.dataset.selectedId || poolRiskIdHidden?.value || ''
            );
        }

        function initRiskResponseStrategies(container) {
            const type = container.querySelector('#risk_type');
            const strategy = container.querySelector('#response_strategy');
            if (!type || !strategy) return;

            const strategiesByType = @json(\App\Models\ProjectRisk::RESPONSE_STRATEGIES);
            const initialStrategy = strategy.dataset.selectedStrategy || strategy.value;

            function updateStrategies(selectedStrategy = '') {
                const options = strategiesByType[type.value] || [];
                strategy.innerHTML = '<option value="">Select strategy</option>';
                options.forEach(value => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = value;
                    strategy.appendChild(option);
                });
                if (options.includes(selectedStrategy)) {
                    strategy.value = selectedStrategy;
                }
            }

            updateStrategies(initialStrategy);
            type.addEventListener('change', () => updateStrategies());
        }

        function initEotPicker(container) {
            const state = container.querySelector('#eotPoolState');
            const intent = container.querySelector('#intent_to_claim');
            const fields = container.querySelector('#eotClaimFields');
            const base = container.querySelector('#eot_base_of_risk');
            const entry = container.querySelector('#eot_entry_id');
            if (!state || !intent || !fields || !base || !entry) return;

            const options = JSON.parse(state.textContent);
            const allEntries = options.entries || [];
            const selectedEntry = entry.dataset.selectedEntry || '';
            const selectedCategory = base.dataset.selectedCategory || '';
            const preview = container.querySelector('#eot_entry_preview');

            function updateClaimTracking(claiming = intent.value === '1') {
                const evaluationFields = container.querySelector('#eotClaimEvaluationFields');
                if (!evaluationFields) return;

                evaluationFields.classList.toggle('hidden', !claiming);
                evaluationFields.querySelectorAll('input, select, textarea').forEach(input => {
                    input.disabled = !claiming;
                });

                [
                    {
                        status: evaluationFields.querySelector('#itc_notification_status'),
                        date: evaluationFields.querySelector('#itc_notification_date'),
                        wrapper: evaluationFields.querySelector('#itcNotificationDateField'),
                    },
                    {
                        status: evaluationFields.querySelector('#eot_status'),
                        date: evaluationFields.querySelector('#eot_submitted_date'),
                        wrapper: evaluationFields.querySelector('#eotSubmittedDateField'),
                    },
                    {
                        status: evaluationFields.querySelector('#eot_status'),
                        wrapper: evaluationFields.querySelector('#eotResponseDateField'),
                    },
                    {
                        status: evaluationFields.querySelector('#eot_status'),
                        wrapper: evaluationFields.querySelector('#eotRequestedDaysField'),
                    },
                    {
                        status: evaluationFields.querySelector('#eot_status'),
                        wrapper: evaluationFields.querySelector('#eotApprovedDaysField'),
                    },
                ].forEach(({ status, date, wrapper }) => {
                    if (!status || !wrapper) return;
                    const submitted = claiming && status.value === 'Submitted';
                    wrapper.classList.toggle('hidden', !submitted);
                    if (date) {
                        date.disabled = !submitted;
                        date.required = submitted;
                    }
                });
            }

            function updateEntryPreview() {
                const selectedOption = entry.options[entry.selectedIndex];
                const hasSelection = Boolean(selectedOption?.value);
                if (!preview) return;
                preview.textContent = hasSelection ? selectedOption.textContent : '';
                preview.classList.toggle('hidden', !hasSelection);
            }

            function loadEntries(categoryId, selectedId = '') {
                entry.innerHTML = '<option value="">Select EOT ID</option>';
                entry.disabled = !categoryId;
                allEntries.filter(item => String(item.eot_category_id) === String(categoryId)).forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.eot_id + ' — ' + (item.detail_reason || '');
                    entry.appendChild(option);
                });
                if (selectedId) entry.value = selectedId;
                updateEntryPreview();
            }

            function updateIntentVisibility() {
                const claiming = intent.value === '1';
                fields.classList.toggle('hidden', !claiming);
                entry.required = claiming;
                base.disabled = !claiming;
                if (!claiming) {
                    base.value = '';
                    entry.value = '';
                    entry.disabled = true;
                }
                updateClaimTracking(claiming);
                updateEntryPreview();
            }

            base.addEventListener('change', () => loadEntries(base.value));
            entry.addEventListener('change', updateEntryPreview);
            intent.addEventListener('change', updateIntentVisibility);
            container.querySelector('#itc_notification_status')?.addEventListener('change', () => updateClaimTracking());
            container.querySelector('#eot_status')?.addEventListener('change', () => updateClaimTracking());
            if (selectedCategory) {
                base.value = selectedCategory;
                loadEntries(selectedCategory, selectedEntry);
            }
            updateIntentVisibility();
        }

        function initRiskExposure(container) {
            const materializedDate = container.querySelector('#materialized_date');
            const actualActionDate = container.querySelector('#actual_action_date');
            const display = container.querySelector('#exposure_display');
            if (!materializedDate || !actualActionDate || !display) return;

            function updateExposure() {
                if (!materializedDate.value) {
                    display.textContent = '—';
                    return;
                }

                const localToday = new Date();
                const browserToday = new Date(localToday.getTime() - localToday.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
                const today = display.dataset.today || browserToday;
                const endDate = actualActionDate.value || today;
                const startTime = Date.parse(materializedDate.value + 'T00:00:00Z');
                const endTime = Date.parse(endDate + 'T00:00:00Z');
                display.textContent = Number.isFinite(startTime) && Number.isFinite(endTime)
                    ? Math.trunc((endTime - startTime) / 86400000).toLocaleString()
                    : '—';
            }

            materializedDate.addEventListener('input', updateExposure);
            actualActionDate.addEventListener('input', updateExposure);
            materializedDate.addEventListener('change', updateExposure);
            actualActionDate.addEventListener('change', updateExposure);
            updateExposure();
        }

        function initRiskUrgency(container) {
            const materializedDate = container.querySelector('#materialized_date');
            const urgencyWrapper = container.querySelector('#risk_urgency_wrapper');
            if (!materializedDate || !urgencyWrapper) return;

            function toggleUrgency() {
                urgencyWrapper.classList.toggle('hidden', !materializedDate.value);
            }

            materializedDate.addEventListener('change', toggleUrgency);
            materializedDate.addEventListener('input', toggleUrgency);
            toggleUrgency();
        }

        function initRiskStepper(container) {
            const panels = container.querySelectorAll('.step-panel');
            if (!panels.length) return;
            const indicator = container.querySelectorAll('.step-indicator');
            const backBtn = container.querySelector('#riskStepBack');
            const nextBtn = container.querySelector('#riskStepNext');
            const form = container.querySelector('form');
            let step = 1;

            function render() {
                panels.forEach(p => {
                    const s = Number(p.dataset.step);
                    p.classList.toggle('hidden', s !== step);
                });
                indicator.forEach(button => {
                    const targetStep = Number(button.dataset.stepTarget);
                    const active = targetStep === step;
                    const number = button.querySelector('span');
                    button.classList.toggle('border-cyan-200', active);
                    button.classList.toggle('bg-cyan-50', active);
                    button.classList.toggle('text-cyan-800', active);
                    button.classList.toggle('border-slate-200', !active);
                    button.classList.toggle('bg-white', !active);
                    button.classList.toggle('text-slate-500', !active);
                    if (number) {
                        number.classList.toggle('bg-cyan-700', active);
                        number.classList.toggle('text-white', active);
                        number.classList.toggle('bg-slate-100', !active);
                        number.classList.toggle('text-slate-600', !active);
                    }
                    if (active) {
                        button.setAttribute('aria-current', 'step');
                    } else {
                        button.removeAttribute('aria-current');
                    }
                });
                if (backBtn) {
                    backBtn.disabled = step <= 1;
                    backBtn.classList.toggle('cursor-not-allowed', backBtn.disabled);
                    backBtn.classList.toggle('opacity-50', backBtn.disabled);
                }
                if (nextBtn) {
                    nextBtn.hidden = step >= panels.length;
                    nextBtn.textContent = 'Next step';
                }
                const scrollContainer = container.closest('.overflow-y-auto');
                if (scrollContainer) {
                    scrollContainer.scrollTo({ top: 0, behavior: 'smooth' });
                }
                if (step === 2) updateRiskRating(container);
            }

            indicator.forEach(button => {
                button.addEventListener('click', () => {
                    const targetStep = Number(button.dataset.stepTarget);
                    if (targetStep >= 1 && targetStep <= panels.length) {
                        step = targetStep;
                        render();
                    }
                });
            });
            if (backBtn) backBtn.addEventListener('click', () => { if (step > 1) { step--; render(); } });
            if (nextBtn) nextBtn.addEventListener('click', () => { if (step < panels.length) { step++; render(); } });
            if (form) {
                form.addEventListener('submit', () => {
                    if (nextBtn) nextBtn.disabled = true;
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) submitButton.disabled = true;
                });
            }
            render();
        }

        document.querySelectorAll('#createModal, #viewModal, #editModal').forEach(modal => {
            modal.querySelector('.risk-modal-dialog').addEventListener('click', event => event.stopPropagation());
        });

        function openViewModal(id) {
            const url = `{{ route('admin.projects.risk.view-modal', ['risk' => '__RISK__']) }}`.replace('__RISK__', encodeURIComponent(id));
            fetch(`${url}?partial=1`)
                .then(response => {
                    if (!response.ok) throw new Error(`Unable to load the risk (${response.status}).`);
                    return response.text();
                })
                .then(html => {
                    document.getElementById('viewModalContent').innerHTML = html;
                    const modal = document.getElementById('viewModal');
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                    syncRiskModalBodyLock();
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Unable to load the risk.');
                });
        }

        function closeViewModal() {
            const modal = document.getElementById('viewModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            syncRiskModalBodyLock();
        }

        function openEditModal(id) {
            const url = `{{ route('admin.projects.risk.edit-modal', ['risk' => '__RISK__']) }}`.replace('__RISK__', encodeURIComponent(id));
            fetch(`${url}?partial=1`)
                .then(response => {
                    if (!response.ok) throw new Error(`Unable to load the risk form (${response.status}).`);
                    return response.text();
                })
                 .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    const modal = document.getElementById('editModal');
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                    syncRiskModalBodyLock();
                    if (window.lucide) lucide.createIcons();
                    updateRiskRating(document.getElementById('editModalContent'));
                    initRiskResponseStrategies(document.getElementById('editModalContent'));
                    initPoolRiskPicker(document.getElementById('editModalContent'));
                    initEotPicker(document.getElementById('editModalContent'));
                    initRiskExposure(document.getElementById('editModalContent'));
                    initRiskUrgency(document.getElementById('editModalContent'));
                    initRiskStepper(document.getElementById('editModalContent'));
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Unable to load the risk form.');
                });
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            syncRiskModalBodyLock();
        }

        document.getElementById('viewModal').addEventListener('click', function (event) {
            if (event.target === this) closeViewModal();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeViewModal();
                closeCreateModal();
                closeEditModal();
            }
        });
        @if($errors->any())
            openCreateModal();
        @endif
    </script>
</x-app-layout>
