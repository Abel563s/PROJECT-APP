<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Project <span class="font-black text-slate-900">Registry</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Operational Matrix Archive</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-100 rounded-xl shadow-sm">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#00ADC5] animate-pulse"></div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-900 tracking-widest uppercase leading-tight">{{ $projects->count() }} Projects</span>
                        <span class="text-[7px] font-bold text-slate-400 uppercase tracking-[0.2em] leading-tight">Registered</span>
                    </div>
                </div>

                <button onclick="openCreateModal()"
                    class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        New Project
                    </span>
                </button>
            </div>
        </div>

        <!-- Search & Filter -->
        <form action="{{ route('admin.projects.index') }}" method="GET" class="space-y-4">
            <div class="bg-white p-4 rounded-2xl shadow-lg shadow-slate-200/30 border border-slate-50 flex flex-wrap items-center gap-4">
                <div class="flex-1 relative min-w-[280px] group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#00ADC5] transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, code or protocol..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-xs font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative group/select">
                        <select name="type" onchange="this.form.submit()"
                            class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                            <option value="">All Modules</option>
                            @foreach(['Building', 'Fit-Out', 'Infrastructure', 'Mixed (Bui/Road)', 'Mixed (Bui/Fit-Out)'] as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                    </div>

                    <div class="relative group/select">
                        <select name="delivery_method" onchange="this.form.submit()"
                            class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                            <option value="">All Methods</option>
                            @foreach(['DBB', 'DB', 'CM', 'Negotiated'] as $method)
                                <option value="{{ $method }}" {{ request('delivery_method') == $method ? 'selected' : '' }}>{{ $method }}</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                    </div>

                    @if(request()->anyFilled(['search', 'type', 'delivery_method']))
                        <a href="{{ route('admin.projects.index') }}"
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
                        <tr class="bg-gradient-to-r from-[#00ADC5] to-[#00ADC5]">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Project Code</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Classification</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Stakeholder Hub</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30 text-right">Contract Budget</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30 text-center">Lifecycle Status</th>
                            <th class="px-6 py-3 text-right border-b border-[#00ADC5]/30"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($projects as $project)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <div class="w-11 h-11 rounded-xl bg-white shadow border border-slate-100 flex items-center justify-center transition-all duration-300 group-hover:rotate-[10deg] group-hover:scale-110 group-hover:border-[#00ADC5]">
                                                <span class="font-black text-[9px] text-slate-300 group-hover:text-[#00ADC5] tracking-tighter">{{ $project->project_code }}</span>
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-md bg-emerald-500 border-2 border-white flex items-center justify-center shadow-sm">
                                                <i data-lucide="check" class="w-2.5 h-2.5 text-white"></i>
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-sm font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors cursor-pointer" onclick="openViewModal({{ $project->id }})">
                                                {{ $project->project_code }}
                                            </p>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest bg-slate-100 px-1.5 py-0.5 rounded">{{ $project->custom_id }}</span>
                                                <span class="w-0.5 h-0.5 rounded-full bg-slate-300"></span>
                                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $project->revision_number ?? 'v1.0' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <p class="text-[9px] font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                            <span class="w-1 h-1 rounded-full bg-[#00ADC5]"></span>
                                            {{ $project->project_type }}
                                        </p>
                                        <div class="inline-flex px-2 py-0.5 bg-slate-100 rounded-md">
                                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                                                {{ $project->delivery_method }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <p class="text-xs font-black text-slate-900">{{ $project->project_client }}</p>
                                        <div class="flex items-center gap-1.5 text-slate-400 group-hover:text-amber-600 transition-colors">
                                            <i data-lucide="briefcase" class="w-2.5 h-2.5"></i>
                                            <p class="text-[8px] font-black uppercase tracking-widest">
                                                {{ $project->consultant }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="space-y-1">
                                        <p class="text-xs font-mono font-black text-slate-900 group-hover:scale-105 transition-transform origin-right">
                                            ETB {{ number_format($project->contract_budget) }}</p>
                                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-emerald-50 rounded-md border border-emerald-100/50">
                                            <span class="w-0.5 h-0.5 rounded-full bg-emerald-500"></span>
                                            <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest">
                                                Allowable: {{ number_format($project->total_allowable_cost) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $colors = [
                                            'Not Completed' => 'bg-amber-100/50 text-amber-600 border-amber-200/50',
                                            'PPA Received' => 'bg-cyan-100/50 text-[#00ADC5] border-cyan-200/50',
                                            'PA Received' => 'bg-indigo-100/50 text-indigo-600 border-indigo-200/50',
                                            'FA Received' => 'bg-emerald-100/50 text-emerald-600 border-emerald-200/50',
                                            'Snag / Di-Snag' => 'bg-rose-100/50 text-rose-600 border-rose-200/50',
                                            'Waiting for PA' => 'bg-slate-100/50 text-slate-600 border-slate-200/50',
                                        ];
                                        $color = $colors[$project->closing_status] ?? 'bg-slate-100 text-slate-400 border-slate-200';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $color }} border shadow-sm group-hover:shadow-md transition-all">
                                        {{ $project->closing_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openViewModal({{ $project->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#00ADC5] hover:text-white hover:border-[#00ADC5] transition-all duration-200"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button onclick="openEditModal({{ $project->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @if(auth()->user() && auth()->user()->isAdmin())
                                            <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-200"
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
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="database" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">Operational Vacancy</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">No project nodes discovered in the current registry subset.</p>
                                        </div>
                                        <a href="{{ route('admin.projects.create') }}"
                                            class="px-5 py-2 bg-slate-900 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                                            Initialize First Protocol
                                        </a>
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
                        <span class="text-[9px] font-black text-slate-900 tracking-widest">01</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">of</span>
                        <span class="text-[9px] font-black text-slate-400 tracking-widest">12</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button class="group/nav w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:border-[#00ADC5] hover:text-[#00ADC5] hover:shadow-md hover:shadow-cyan-100 transition-all duration-200">
                        <i data-lucide="chevron-left" class="w-4 h-4 group-hover/nav:-translate-x-0.5 transition-transform"></i>
                    </button>
                    <button class="group/nav w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:border-[#00ADC5] hover:text-[#00ADC5] hover:shadow-md hover:shadow-cyan-100 transition-all duration-200">
                        <i data-lucide="chevron-right" class="w-4 h-4 group-hover/nav:translate-x-0.5 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">Project Details</h3>
                    <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="viewModalContent" class="p-6">
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
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">Edit Project</h3>
                    <button onclick="closeEditModal()" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="editModalContent" class="p-6">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeCreateModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">New Project</h3>
                    <button onclick="closeCreateModal()" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="createModalContent" class="p-6">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openViewModal(id) {
            fetch(`/admin/projects/${id}?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('viewModalContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                    lucide.createIcons();
                });
        }

        function closeModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openEditModal(id) {
            fetch(`/admin/projects/${id}/edit?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    document.getElementById('editModal').classList.remove('hidden');
                    lucide.createIcons();
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openCreateModal() {
            fetch(`/admin/projects/create?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    lucide.createIcons();
                });
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

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
