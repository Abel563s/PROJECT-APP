<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-3 border-b border-slate-100 items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="package" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Material <span class="font-black text-slate-900">Registry</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Procurement Archive</p>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                    <i data-lucide="package" class="w-3.5 h-3.5 text-white/90"></i>
                    <span class="text-base font-black text-white tracking-tight">{{ $records->total() ?? 0 }}</span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Materials Registered</span>
                </div>
            </div>

            <div class="flex justify-end">
                <button onclick="openCreateModal()"
                    class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        Add Material
                    </span>
                </button>
            </div>
        </div>

        <!-- Search & Filter -->
        @php
            $materialFiltersActive = request()->anyFilled(['search', 'project_id', 'project_client', 'project_type', 'consultancy_sector']);
        @endphp
        <form action="{{ route('admin.material.index') }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-{{ $materialFiltersActive ? '6' : '5' }}">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Materials</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by name, code or supplier..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project</span>
                    <select name="project_id" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->project_code }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Supplier</span>
                    <select name="project_client" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Suppliers</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('project_client') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Discipline</span>
                    <select name="project_type" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Disciplines</option>
                        @foreach(['Civil','MEP'] as $type)
                            <option value="{{ $type }}" {{ request('project_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Procurement Type</span>
                    <select name="consultancy_sector" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Procurement Types</option>
                        @foreach(['LVA','RFQ-Local','RFQ-Import-Birr','RFQ-Import-USD','IFB-ICB','IFB-NCB','RFP','LTA'] as $t)
                            <option value="{{ $t }}" {{ request('consultancy_sector') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </label>
                @if($materialFiltersActive)
                    <div class="flex items-end justify-end">
                        <a href="{{ route('admin.material.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-600 rounded-lg border border-rose-200 hover:bg-rose-500 hover:text-white transition-all active:scale-95 shadow-sm text-[9px] font-black uppercase tracking-widest">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </form>

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">#</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Material Name</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Supplier</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Discipline</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Product</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Route</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Type</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Approval</th>
                            <th class="px-6 py-3 text-right border-b border-[#00515f]/30"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($records as $record)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <p class="text-sm font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">{{ $record->material_name }}</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $record->material_code }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-slate-900">{{ $record->supplier_name ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tight">{{ $record->discipline ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tight">{{ $record->procurement_product ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-0.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-tight rounded-md">{{ $record->supply_route ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-bold text-slate-700 uppercase tracking-tight">{{ $record->procurement_type ?? '—' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $approval = $record->approval ?? 'No';
                                        $approvalColors = [
                                            'Yes' => 'bg-emerald-100/50 text-emerald-600 border-emerald-200/50',
                                            'No' => 'bg-rose-100/50 text-rose-600 border-rose-200/50',
                                        ];
                                        $approvalColor = $approvalColors[$approval] ?? 'bg-slate-100 text-slate-400 border-slate-200';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $approvalColor }} border shadow-sm">{{ $approval }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openViewModal({{ $record->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#00ADC5] hover:text-white hover:border-[#00ADC5] transition-all duration-200"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button onclick="openEditModal({{ $record->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @if(auth()->user()->isAdmin())
                                            <form action="{{ route('admin.material.destroy', $record) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this material? This action cannot be undone.');">
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
                                <td colspan="10" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="package" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">No Materials Found</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">No material records discovered in the current registry.</p>
                                        </div>
                                        <button type="button" onclick="openCreateModal()"
                                            class="px-5 py-2 bg-slate-900 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                                            Add First Material
                                        </button>
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
                        <span class="text-[9px] font-black text-slate-900 tracking-widest">{{ $records->currentPage() }}</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">of</span>
                        <span class="text-[9px] font-black text-slate-400 tracking-widest">{{ $records->lastPage() }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{ $records->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">Material Details</h3>
                    <button onclick="closeViewModal()" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
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
                    <h3 class="text-base font-black text-slate-900">Edit Material</h3>
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
                    <h3 class="text-base font-black text-slate-900">New Material</h3>
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
            fetch(`/admin/material/${id}?partial=1`)
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

        function openEditModal(id) {
            fetch(`/admin/material/${id}/edit?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    document.getElementById('editModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openCreateModal() {
            fetch(`/admin/material/create?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                    initProjectSearch();
                });
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeViewModal();
                closeEditModal();
                closeCreateModal();
            }
        });

        function initProjectSearch() {
            document.querySelectorAll('select[id="projectSearch"]').forEach(function(select) {
                if (select.dataset.initialized) return;
                select.dataset.initialized = 'true';

                const wrapper = document.createElement('div');
                wrapper.className = 'relative';
                select.parentNode.insertBefore(wrapper, select);

                const searchInput = document.createElement('input');
                searchInput.type = 'text';
                searchInput.placeholder = 'Search project...';
                searchInput.className = 'w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all mb-1';
                wrapper.appendChild(searchInput);

                const originalSelect = select;
                originalSelect.classList.add('hidden');
                wrapper.appendChild(originalSelect);

                const dropdown = document.createElement('div');
                dropdown.className = 'absolute z-50 w-full bg-white border border-slate-200 rounded-lg shadow-xl max-h-40 overflow-y-auto hidden';
                dropdown.style.top = searchInput.offsetHeight + 4 + 'px';
                wrapper.appendChild(dropdown);

                function renderOptions(filter = '') {
                    dropdown.innerHTML = '';
                    const options = Array.from(originalSelect.options);
                    const filterLower = filter.toLowerCase();

                    options.forEach(function(option) {
                        if (!option.value) return;
                        const text = option.text.toLowerCase();
                        if (text.includes(filterLower)) {
                            const item = document.createElement('div');
                            item.className = 'px-3 py-2 text-[10px] font-bold text-slate-700 hover:bg-slate-50 cursor-pointer';
                            item.textContent = option.text;
                            item.addEventListener('click', function() {
                                originalSelect.value = option.value;
                                searchInput.value = option.text;
                                dropdown.classList.add('hidden');
                            });
                            dropdown.appendChild(item);
                        }
                    });

                    if (dropdown.children.length === 0 && filter) {
                        const empty = document.createElement('div');
                        empty.className = 'px-3 py-2 text-[10px] font-bold text-slate-400';
                        empty.textContent = 'No projects found';
                        dropdown.appendChild(empty);
                    }
                }

                searchInput.addEventListener('input', function() {
                    renderOptions(this.value);
                    dropdown.classList.remove('hidden');
                });

                searchInput.addEventListener('focus', function() {
                    renderOptions(this.value);
                    dropdown.classList.remove('hidden');
                });

                searchInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                document.addEventListener('click', function(e) {
                    if (!wrapper.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });

                if (originalSelect.value) {
                    const selectedOption = originalSelect.options[originalSelect.selectedIndex];
                    if (selectedOption) {
                        searchInput.value = selectedOption.text;
                    }
                }
            });
        }

        initProjectSearch();
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
