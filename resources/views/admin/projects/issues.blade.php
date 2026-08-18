<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-3 border-b border-slate-100 items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Issue <span class="font-black text-slate-900">Log</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Project Issue Tracking</p>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-white/90"></i>
                    <span class="text-base font-black text-white tracking-tight">{{ $issues->total() }}</span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Issues Logged</span>
                </div>
            </div>

            <div class="flex justify-end">
                <button onclick="openCreateModal()"
                    class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        New Issue
                    </span>
                </button>
            </div>
        </div>

        <!-- Search & Filter -->
        <form action="{{ route('admin.projects.issues.index') }}" method="GET" class="space-y-4">
            <div class="bg-white p-4 rounded-2xl shadow-lg shadow-slate-200/30 border border-slate-50 flex flex-wrap items-center justify-center gap-3 w-fit mx-auto">
                <div class="min-w-[220px] max-w-[320px] relative group shrink-0">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#00ADC5] transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search issues by type, issue, or raised by..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-xs font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="relative group/select">
                        <select name="project_id" onchange="this.form.submit()"
                            class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[180px]">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_code }}
                                </option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                    </div>

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

                    <div class="relative group/select">
                        <select name="issue_type" onchange="this.form.submit()"
                            class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[180px]">
                            <option value="all">All Issue Types</option>
                            @foreach($issueTypes as $type)
                                <option value="{{ $type }}" {{ request('issue_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                    </div>

                    @if(request()->anyFilled(['search', 'project_id', 'status', 'impact', 'issue_type']))
                            <a href="{{ route('admin.projects.issues.index') }}"
                            class="flex items-center justify-center w-10 h-10 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all active:scale-90 shadow-sm"
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
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Issue Owner</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Issue</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Impact</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Status</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Raised Date</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Data Date</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Raised By</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Last Updated</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($issues as $issue)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-slate-900 leading-tight break-words">{{ $issue->project_code ?? '---' }}</p>
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
                                        {{ $issue->date ? $issue->date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-slate-900">{{ $issue->raised_by ?? '---' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $issue->last_updated_date ? $issue->last_updated_date->format('M d, Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openViewModal({{ $issue->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#00ADC5] hover:text-white hover:border-[#00ADC5] transition-all duration-200"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button onclick="openEditModal({{ $issue->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <form action="{{ route('admin.projects.issues.destroy', $issue->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this issue?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-200"
                                                title="Delete">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-16 text-center">
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
            <div class="p-6 border-t border-slate-50 flex items-center justify-between bg-slate-50/30">
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 px-3 h-10 bg-white border border-slate-100 rounded-xl">
                        <span class="text-[9px] font-black text-slate-900 tracking-widest">{{ sprintf('%02d', $issues->count() > 0 ? 1 : 0) }}</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">of</span>
                        <span class="text-[9px] font-black text-slate-400 tracking-widest">{{ sprintf('%02d', $issues->total()) }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{ $issues->links() }}
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
                    <h3 class="text-base font-black text-white">New Issue</h3>
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

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col">
                <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
                    <h3 class="text-base font-black text-white">Issue Details</h3>
                    <button onclick="closeViewModal()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
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
                    <h3 class="text-base font-black text-white">Edit Issue</h3>
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
            fetch(`/admin/projects/issues/${id}?partial=1&action=view`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('viewModalContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openCreateModal() {
            fetch(`{{ route('admin.projects.issues.create-modal') }}?partial=1&action=create`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                    initIssueForm();
                });
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(id) {
            fetch(`/admin/projects/issues/${id}/edit?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    document.getElementById('editModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                    initIssueForm();
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

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
