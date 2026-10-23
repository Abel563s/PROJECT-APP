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
                    <p class="mt-1 text-[8px] font-black uppercase tracking-[0.3em] text-[#00ADC5]">{{ $project->project_name }} - {{ $project->project_code }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
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
            <div class="grid grid-cols-7 gap-3 min-w-[992px]">
                @php
                    $kpis = [
                        ['label' => 'Total Risks', 'key' => 'total', 'icon' => 'alert-triangle', 'tone' => 'slate'],
                        ['label' => 'Open', 'key' => 'open', 'icon' => 'circle', 'tone' => 'sky'],
                        ['label' => 'Closed', 'key' => 'closed', 'icon' => 'check-circle', 'tone' => 'emerald'],
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
                        <p class="mt-1 text-[8px] font-bold uppercase tracking-wider text-slate-400">{{ in_array($kpi['key'], ['open', 'in_progress', 'closed']) ? 'By status' : (in_array($kpi['key'], ['extreme', 'high', 'medium', 'low']) ? 'By rating' : 'All records') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Search & Filter -->
        <form action="{{ route('admin.projects.risk.manage', $project) }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="mb-3 flex justify-end">
                @if(request()->anyFilled(['search', 'risk_category', 'risk_type', 'risk_level', 'risk_urgency', 'action_status']))
                    <a href="{{ route('admin.projects.risk.manage', $project) }}" class="inline-flex items-center gap-1.5 self-start rounded-lg border border-rose-100 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition-colors hover:bg-rose-500 hover:text-white sm:self-auto">
                        <i data-lucide="x" class="h-3 w-3"></i>Clear Filters
                    </a>
                @endif
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-7">
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
                    <select name="risk_category" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Categories</option>
                        @foreach($riskCategories as $category)
                            <option value="{{ $category }}" @selected(request('risk_category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Type</span>
                    <select name="risk_type" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Types</option>
                        @foreach(['Opportunity', 'Threat'] as $type)
                            <option value="{{ $type }}" @selected(request('risk_type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Rating</span>
                    <select name="risk_level" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Ratings</option>
                        @foreach(['Low', 'Medium', 'High', 'Extreme'] as $level)
                            <option value="{{ $level }}" @selected(request('risk_level') === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Urgency</span>
                    <select name="risk_urgency" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Urgencies</option>
                        @foreach(['Very Urgent', 'Urgent', 'Moderate', 'Low'] as $urgency)
                            <option value="{{ $urgency }}" @selected(request('risk_urgency') === $urgency)>{{ $urgency }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Status</span>
                    <select name="action_status" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Statuses</option>
                        @foreach(['Open', 'Closed'] as $status)
                            <option value="{{ $status }}" @selected(request('action_status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex items-end gap-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-white transition-colors hover:bg-cyan-700">
                        <i data-lucide="filter" class="h-3.5 w-3.5"></i>Apply Filters
                    </button>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#00515f]/30 bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black uppercase tracking-widest text-white">
                            <th class="px-4 py-3 text-center">#</th>
                            <th class="px-4 py-3">Risk Name</th>
                            <th class="px-4 py-3">Category</th>
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
                                    'Open' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'Inprogress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Closed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                ];
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-center"><span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-[10px] font-black tabular-nums text-slate-900">{{ $risks->firstItem() + $loop->index }}</span></td>
                                <td class="max-w-xs px-4 py-3">
                                    <p class="text-xs font-black leading-tight text-slate-900">{{ $risk->risk_name }}</p>
                                    <p class="mt-1 line-clamp-2 text-[10px] text-slate-500">{{ $risk->risk_description }}</p>
                                </td>
                                <td class="px-4 py-3"><span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700">{{ $risk->risk_category }}</span></td>
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
                                        {{ $risk->action_status === 'Inprogress' ? 'In Progress' : $risk->action_status }}
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
                                        @if(auth()->user()->isAdmin() || ($canManageProject && auth()->user()->isUser() && (int) $risk->created_by === (int) auth()->id()))
                                            <button onclick="openEditModal({{ $risk->id }})"
                                                class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-amber-500 hover:border-amber-500 hover:shadow-sm transition-all"
                                                title="Edit">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endif
                                        @if(auth()->user()->isAdmin())
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
                                <td colspan="9" class="px-4 py-16 text-center">
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

    <div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm">
        <div class="flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
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
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
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
                <button onclick="closeViewModal()"
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
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
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
                <button onclick="closeEditModal()"
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
            document.body.style.overflow = 'hidden';

            fetch(@json(route('admin.projects.risk.create-modal', $project)))
                .then(response => {
                    if (!response.ok) throw new Error(`Unable to load the risk form (${response.status}).`);
                    return response.text();
                })
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    if (window.lucide) lucide.createIcons();
                    updateRiskRating();
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
            document.body.style.overflow = '';
        }

        function updateRiskRating() {
            const likelihoodInput = document.getElementById('likelihood');
            const severityInput = document.getElementById('severity');
            if (!likelihoodInput || !severityInput) return;

            const likelihood = Number(likelihoodInput.value);
            const severity = Number(severityInput.value);
            const score = likelihood * severity;
            const level = score >= 17 ? 'Extreme' : score >= 10 ? 'High' : score >= 5 ? 'Medium' : 'Low';
            const colors = { Low: 'text-emerald-600', Medium: 'text-amber-600', High: 'text-orange-600', Extreme: 'text-rose-600' };

            document.getElementById('likelihoodValue').textContent = likelihood;
            document.getElementById('severityValue').textContent = severity;
            document.getElementById('riskRatingScore').textContent = score;
            document.getElementById('riskRatingLevel').textContent = level;
            document.getElementById('riskRatingLevel').className = `text-[9px] font-black uppercase tracking-widest ${colors[level]}`;
        }

        document.getElementById('createModal').addEventListener('click', function (event) {
            if (event.target === this) closeCreateModal();
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
                    document.getElementById('viewModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Unable to load the risk.');
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
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
                    document.getElementById('editModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    if (window.lucide) lucide.createIcons();
                    updateRiskRating();
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Unable to load the risk form.');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.getElementById('viewModal').addEventListener('click', function (event) {
            if (event.target === this) closeViewModal();
        });

        document.getElementById('editModal').addEventListener('click', function (event) {
            if (event.target === this) closeEditModal();
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
