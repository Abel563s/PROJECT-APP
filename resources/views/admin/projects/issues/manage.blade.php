<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.issues.index') }}"
                   class="w-9 h-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#00ADC5] hover:border-[#00ADC5]/20 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Issues <span class="font-black text-slate-900">Log</span>
                    </h2>
                    <p class="text-[10px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">{{ $project->project_code }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($canManageProject)
                    <button onclick="openCreateModal()"
                       class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                        <span class="relative flex items-center gap-1.5">
                            <i data-lucide="plus" class="w-3 h-3"></i>
                            New Issue
                        </span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="-mx-4 overflow-x-auto px-4 pb-1 lg:mx-0 lg:px-0">
            <div class="grid grid-cols-8 gap-3 min-w-[1120px]" style="grid-template-columns: repeat(8, minmax(0, 1fr)); min-width: 1120px;">
                @php
                $kpis = [
                        ['label' => 'Total Issues', 'key' => 'total', 'icon' => 'layers-3', 'tone' => 'slate'],
                        ['label' => 'Raised', 'key' => 'raised', 'icon' => 'circle-plus', 'tone' => 'slate'],
                        ['label' => 'Open', 'key' => 'open', 'icon' => 'circle-dot', 'tone' => 'sky'],
                        ['label' => 'Closed', 'key' => 'closed', 'icon' => 'check-check', 'tone' => 'emerald'],
                        ['label' => 'Overdue', 'key' => 'overdue', 'icon' => 'alarm-clock', 'tone' => 'rose'],
                        ['label' => 'High', 'key' => 'high', 'icon' => 'triangle-alert', 'tone' => 'orange'],
                        ['label' => 'Medium', 'key' => 'medium', 'icon' => 'minus', 'tone' => 'amber'],
                        ['label' => 'Low', 'key' => 'low', 'icon' => 'arrow-down', 'tone' => 'cyan'],
                    ];
                    $kpiTones = [
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
                    <div class="group rounded-2xl border border-slate-100 bg-gradient-to-br from-white to-slate-50/80 p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md {{ $kpiTones[$kpi['tone']]['border'] }}">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $kpiTones[$kpi['tone']]['icon'] }} transition-transform group-hover:scale-105">
                                <i data-lucide="{{ $kpi['icon'] }}" class="h-4 w-4"></i>
                            </div>
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $kpi['label'] }}</span>
                        </div>
                        <p class="mt-3 text-2xl font-black leading-none tabular-nums {{ $kpiTones[$kpi['tone']]['value'] }}">{{ $stats[$kpi['key']] }}</p>
                        <p class="mt-1 text-[8px] font-bold uppercase tracking-wider text-slate-400">{{ $kpi['key'] === 'total' ? 'All records' : (in_array($kpi['key'], ['raised', 'open', 'closed'], true) ? 'By status' : 'By priority') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <form action="{{ route('admin.projects.issues.manage', $project) }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            @php
                $projectIssueAdvancedFiltersActive = request()->anyFilled(['impact', 'status', 'priority']);
                $projectIssueFiltersActive = request()->anyFilled(['issue_owner', 'issue_search', 'impact', 'status', 'priority']);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Issue Owner</span>
                    <select name="issue_owner" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All owners</option>
                        @foreach($issueOwners as $owner)
                            <option value="{{ $owner }}" @selected(request('issue_owner') === $owner)>{{ $owner }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Issue</span>
                    <input type="search" name="issue_search" value="{{ request('issue_search') }}" placeholder="Search issues..." class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact</span>
                    <select name="impact" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All impacts</option>
                        @foreach(['Low', 'Medium', 'High'] as $impact)
                            <option value="{{ $impact }}" @selected(request('impact') === $impact)>{{ $impact }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Status</span>
                    <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All statuses</option>
                        @foreach(['Raised', 'Open', 'Closed'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Priority</span>
                    <select name="priority" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All priorities</option>
                        @foreach(['Done', 'Overdue', 'High', 'Medium', 'Low', 'Unscheduled'] as $priority)
                            <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            @if($projectIssueFiltersActive)
                <a href="{{ route('admin.projects.issues.manage', $project) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition hover:bg-rose-500 hover:text-white mt-3 lg:mt-0">
                    <i data-lucide="x" class="h-3.5 w-3.5"></i>Clear filters
                </a>
            @endif
        </form>
        @include('admin.partials.filter-panel-toggle-script')

        <!-- Issues Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">
                            <th class="px-4 py-3 text-center">#</th>
                            <th class="px-4 py-3">Issue Type</th>
                            <th class="px-4 py-3">Issue</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Impact</th>
                            <th class="px-4 py-3 text-center">Priority</th>
                            <th class="px-4 py-3">Raised Date</th>
                            <th class="px-4 py-3">Planned Action Date</th>
                            <th class="px-4 py-3">Action By</th>
                            <th class="px-4 py-3">Data Date</th>
                            <th class="px-4 py-3">Last Updated</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($issues as $issue)
                            <tr class="hover:bg-slate-50/80 transition-all group">
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $issues->firstItem() + $loop->index }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $issue->issue_type ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-1">
                                        <p class="text-xs font-black text-slate-900 leading-tight break-words">{{ $issue->issue }}</p>
                                        <p class="text-[9px] font-bold text-slate-500 leading-tight break-words">{{ $issue->description ?? 'No description' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $statusColors = [
                                            'Raised' => 'bg-slate-100 text-slate-600 border-slate-200',
                                            'Open' => 'bg-sky-50 text-sky-600 border-sky-200',
                                            'Closed' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        ];
                                        $statusColor = $statusColors[$issue->status] ?? 'bg-slate-100 text-slate-400 border-slate-200';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $statusColor }} border shadow-sm">
                                        {{ $issue->status ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
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
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $priorityColors = [
                                            'Done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Overdue' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'High' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'Medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Low' => 'bg-sky-50 text-sky-700 border-sky-200',
                                            'Unscheduled' => 'bg-slate-100 text-slate-500 border-slate-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $priorityColors[$issue->priority] }}">
                                        {{ $issue->priority }}
                                    </span>
                                    @if($issue->priority !== 'Done' && $issue->days_left !== null)
                                        <p class="mt-1 text-[9px] font-bold {{ $issue->days_left < 0 ? 'text-rose-600' : 'text-slate-400' }}">{{ abs($issue->days_left) }} {{ abs($issue->days_left) === 1 ? 'day' : 'days' }} {{ $issue->days_left < 0 ? 'overdue' : 'left' }}</p>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $issue->raised_date ? $issue->raised_date->format('d-M-y') : '---' }}
                                    </span>
                                </td>
                <td class="whitespace-nowrap px-4 py-3">
                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                        {{ $issue->planned_resolution_date ? $issue->planned_resolution_date->format('d-M-y') : '---' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                        {{ $issue->action_by ?? '---' }}
                    </span>
                </td>
                <td class="whitespace-nowrap px-4 py-3">
                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                        {{ $issue->date ? $issue->date->format('d-M-y') : '---' }}
                    </span>
                </td>
                <td class="whitespace-nowrap px-4 py-3">
                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                        {{ $issue->last_updated_date ? $issue->last_updated_date->format('d-M-y') : '---' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openViewModal({{ $issue->id }})"
                                            class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-sky-500 hover:border-sky-500 hover:shadow-sm transition-all"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @if($canManageProject && (auth()->user()->isAdmin() || auth()->user()->isSuperadmin() || auth()->user()->isUser()))
                                            <button onclick="openEditModal({{ $issue->id }})"
                                                class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-amber-500 hover:border-amber-500 hover:shadow-sm transition-all"
                                                title="Edit">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endif
                                        @if($canManageProject && (auth()->user()->isAdmin() || auth()->user()->isSuperadmin()))
                                            <form action="{{ route('admin.projects.issues.destroy', $issue->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this issue?');">
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
                                <td colspan="12" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="alert-circle" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">No Issues Logged</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Create your first issue to start tracking.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
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
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white">New Issue</h3>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Register a new issue for {{ $project->project_code }}</p>
                    </div>
                </div>
                <button onclick="closeCreateModal()"
                    class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
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
                        <h3 class="text-base font-black text-white">Issue Details</h3>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">View issue information</p>
                    </div>
                </div>
                <button onclick="closeViewModal()"
                    class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
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
                        <h3 class="text-base font-black text-white">Edit Issue</h3>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Update issue details</p>
                    </div>
                </div>
                <button onclick="closeEditModal()"
                    class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
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

    <script>
        function initIssueForm() {
            const projectSelect = document.getElementById('issueProjectSelect');
            const sectorInput = document.getElementById('issueSector');
            const codeInput = document.getElementById('issueProjectCode');
            const lastUpdatedInput = document.getElementById('lastUpdatedDate');
            const lastUpdatedDisplay = document.getElementById('lastUpdatedDisplay');

            function updateTimestamp() {
                const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
                if (lastUpdatedInput) lastUpdatedInput.value = now;
                if (lastUpdatedDisplay) lastUpdatedDisplay.value = now;
            }

            const editModal = document.getElementById('editModal');
            const createModal = document.getElementById('createModal');
            const activeModal = editModal && !editModal.classList.contains('hidden') ? editModal : createModal;
            const issueForm = activeModal ? activeModal.querySelector('#createIssueForm, #editIssueForm') : null;
            const plannedDate = issueForm ? issueForm.querySelector('[name="planned_resolution_date"]') : null;
            const closedDate = issueForm ? issueForm.querySelector('[name="issue_closed_date"]') : null;
            const statusDisplay = issueForm ? issueForm.querySelector('[data-issue-status]') : null;

            function updateIssueStatus() {
                if (!statusDisplay) return;

                const status = closedDate && closedDate.value
                    ? 'Closed'
                    : (plannedDate && plannedDate.value ? 'Open' : 'Raised');
                const statusStyles = {
                    Raised: 'border-slate-200 bg-slate-100 text-slate-600',
                    Open: 'border-sky-200 bg-sky-50 text-sky-600',
                    Closed: 'border-emerald-200 bg-emerald-50 text-emerald-600',
                };
                statusDisplay.textContent = status;
                statusDisplay.className = `inline-flex rounded-lg border px-3 py-2 text-[10px] font-bold ${statusStyles[status]}`;
            }

            [plannedDate, closedDate].forEach((dateInput) => {
                if (dateInput) {
                    dateInput.addEventListener('input', updateIssueStatus);
                    dateInput.addEventListener('change', updateIssueStatus);
                }
            });
            updateIssueStatus();

            if (projectSelect) {
                projectSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.dataset.sector) {
                        sectorInput.value = selectedOption.dataset.sector;
                        codeInput.value = selectedOption.dataset.code;
                    } else {
                        sectorInput.value = '';
                        codeInput.value = '';
                    }
                    updateTimestamp();
                });
            }

            updateTimestamp();
        }

        function openViewModal(id) {
            const url = `{{ route('admin.projects.issues.show', ['issue' => '__ISSUE__']) }}`.replace('__ISSUE__', encodeURIComponent(id));
            fetch(`${url}?partial=1&action=view`)
                .then(response => {
                    if (!response.ok) throw new Error(`Unable to load issue (${response.status}).`);
                    return response.text();
                })
                .then(html => {
                    document.getElementById('viewModalContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Unable to load the issue.');
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openCreateModal() {
            fetch(`{{ route('admin.projects.issues.create-modal', $project) }}?partial=1&action=create`)
                .then(response => {
                    if (!response.ok) throw new Error(`Unable to load the issue form (${response.status}).`);
                    return response.text();
                })
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    if (window.lucide) lucide.createIcons();
                    initIssueForm();
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Unable to load the issue form.');
                });
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('createModal').addEventListener('click', function (e) {
            if (e.target === this) closeCreateModal();
        });

        function openEditModal(id) {
            const url = `{{ route('admin.projects.issues.edit', ['issue' => '__ISSUE__']) }}`.replace('__ISSUE__', encodeURIComponent(id));
            fetch(`${url}?partial=1`)
                .then(response => {
                    if (!response.ok) throw new Error(`Unable to load the issue form (${response.status}).`);
                    return response.text();
                })
                .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    document.getElementById('editModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    if (window.lucide) lucide.createIcons();
                    initIssueForm();
                })
                .catch(error => {
                    console.error(error);
                    alert(error.message || 'Unable to load the issue form.');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('editModal').addEventListener('click', function (e) {
            if (e.target === this) closeEditModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeViewModal();
                closeCreateModal();
                closeEditModal();
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
