<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Issue <span class="font-black text-slate-900">History</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">All logged issues across projects</p>
                </div>
            </div>
            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-white/90"></i>
                    <span class="text-base font-black text-white tracking-tight">{{ $issues->total() }}</span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Issues Logged</span>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <form action="{{ route('admin.projects.issues.history') }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="mb-3 flex justify-end">
                @if(request()->anyFilled(['search', 'project_id', 'status', 'impact', 'issue_type', 'date_from', 'date_to']))
                    <a href="{{ route('admin.projects.issues.history') }}" class="inline-flex items-center gap-1.5 self-start rounded-lg border border-rose-100 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition-colors hover:bg-rose-500 hover:text-white sm:self-auto">
                        <i data-lucide="x" class="h-3 w-3"></i>Clear Filters
                    </a>
                @endif
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-8">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Issues</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search issues..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project</span>
                    <select name="project_id" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>{{ $project->project_code }} - {{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Date From</span>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Date To</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Status</span>
                    <select name="status" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Statuses</option>
                        @foreach(['Open', 'In Progress', 'Closed', 'Cancelled'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact</span>
                    <select name="impact" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Impacts</option>
                        @foreach(['Low', 'Medium', 'High'] as $impact)
                            <option value="{{ $impact }}" @selected(request('impact') === $impact)>{{ $impact }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Issue Type</span>
                    <select name="issue_type" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Issue Types</option>
                        @foreach(['Constraint', 'Risk', 'Dependency'] as $type)
                            <option value="{{ $type }}" @selected(request('issue_type') === $type)>{{ $type }}</option>
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

        <!-- Issues Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">
                            <th class="px-4 py-3 text-center">#</th>
                            <th class="px-4 py-3">Project</th>
                            <th class="px-4 py-3">Issue Owner</th>
                            <th class="px-4 py-3">Issue</th>
                            <th class="px-4 py-3 text-center">Impact</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3">Raised Date</th>
                            <th class="px-4 py-3">Data Date</th>
                            <th class="px-4 py-3">Raised By</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($issues as $issue)
                            <tr class="hover:bg-slate-50/80 transition-all group">
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $issue->project->project_code ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $issue->issue_type ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-black text-slate-900 leading-tight break-words">{{ $issue->issue }}</p>
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
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $issue->raised_date ? $issue->raised_date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums tracking-tight shadow-sm justify-center">
                                        {{ $issue->date ? $issue->date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $issue->raised_by ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="openViewModal({{ $issue->id }})" class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-sky-500 hover:border-sky-500 hover:shadow-sm transition-all" title="View">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="alert-circle" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">No Issues Found</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Try adjusting the filters to see more results.</p>
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

    <!-- View Modal -->
    <div id="viewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
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
                    <div id="viewModalDataDate" class="hidden ml-4 px-3 py-1 rounded-lg bg-white/10 border border-white/20">
                        <span class="text-[9px] font-black text-white uppercase tracking-widest">Data Date: </span>
                        <span class="text-[10px] font-black text-white" id="viewModalDataDateValue">---</span>
                    </div>
                </div>
                <button onclick="closeViewModal()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
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

    <script>
        function openViewModal(id) {
            fetch(`/admin/projects/issues/${id}?partial=1&action=view`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const dataDateEl = doc.querySelector('.issue-data-date');
                    const dataDateValue = dataDateEl ? dataDateEl.textContent.trim() : '---';
                    const dataDateContainer = document.getElementById('viewModalDataDate');
                    const dataDateValueEl = document.getElementById('viewModalDataDateValue');
                    if (dataDateContainer && dataDateValueEl) {
                        dataDateValueEl.textContent = dataDateValue;
                        dataDateContainer.classList.remove('hidden');
                    }
                    document.getElementById('viewModalContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        document.getElementById('viewModal').addEventListener('click', function (e) {
            if (e.target === this) closeViewModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeViewModal();
        });

        if (window.lucide) lucide.createIcons();
    </script>
</x-app-layout>
