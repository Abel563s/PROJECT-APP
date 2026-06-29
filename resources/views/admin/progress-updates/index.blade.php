<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Progress <span class="font-black text-slate-900">Updates</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Operational Performance Matrix</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-100 rounded-xl shadow-sm">
                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></div>
                    <span class="text-[9px] font-black text-slate-900 tracking-widest uppercase leading-tight">{{ $updates->count() }} Metrics Filed</span>
                </div>

                <button onclick="openCreateModal()"
                   class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                        New Update
                    </span>
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#00ADC5] to-[#00ADC5]">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Project</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Planned vs Actual</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">SPI Index</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Revenue Status</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Last Edit</th>
                            <th class="px-6 py-3 text-right border-b border-[#00ADC5]/30"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($updates as $update)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-white shadow border border-slate-100 flex items-center justify-center transition-all duration-300 group-hover:rotate-[10deg] group-hover:scale-110 group-hover:border-[#00ADC5]">
                                            <span class="font-black text-[9px] text-slate-300 group-hover:text-[#00ADC5] tracking-tighter">{{ substr($update->project->project_name, 0, 2) }}</span>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-sm font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">
                                                {{ $update->project->project_name }}
                                            </p>
                                            <span class="text-[8px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $update->project->custom_id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-[9px] font-black uppercase tracking-widest mb-1">
                                            <span class="text-slate-400">Progress</span>
                                            <span class="text-[#00ADC5]">{{ $update->progress_actual }}% / {{ $update->progress_planned }}%</span>
                                        </div>
                                        <div class="w-40 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#00ADC5] rounded-full" style="width: {{ min(100, $update->progress_actual) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $spi = $update->progress_planned > 0 ? ($update->progress_actual / $update->progress_planned) : 0;
                                        $spiColor = $spi >= 1 ? 'text-emerald-500' : ($spi >= 0.8 ? 'text-amber-500' : 'text-rose-500');
                                    @endphp
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm font-black {{ $spiColor }}">{{ number_format($spi * 100, 1) }}%</span>
                                        <i data-lucide="{{ $spi >= 1 ? 'trending-up' : 'trending-down' }}" class="w-3.5 h-3.5 {{ $spiColor }}"></i>
                                    </div>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">SPI</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-mono font-black text-slate-900">ETB {{ number_format($update->revenue_actual) }}</p>
                                    <p class="text-[9px] font-black text-slate-400 mt-0.5 uppercase tracking-widest">Planned: ETB {{ number_format($update->revenue_planned) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $update->updated_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openViewModal({{ $update->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#00ADC5] hover:text-white hover:border-[#00ADC5] transition-all duration-200"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button onclick="openEditModal({{ $update->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @if(auth()->user() && auth()->user()->isAdmin())
                                            <form action="{{ route('admin.progress-updates.destroy', $update->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this progress update?');">
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
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="trending-up" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="text-base font-black text-slate-900">No Record Found</h3>
                                        <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Please initiate a progress protocol update.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeCreateModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">New Progress Update</h3>
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

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">Progress Update Details</h3>
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
                    <h3 class="text-base font-black text-slate-900">Edit Progress Update</h3>
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

    <script>
        function openCreateModal() {
            fetch(`/admin/progress-updates/create?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    lucide.createIcons();

                    const plannedInput = document.querySelector('input[name="progress_planned"]');
                    const actualInput = document.querySelector('input[name="progress_actual"]');
                    const spiDisplay = document.getElementById('spiDisplay');

                    if (plannedInput && actualInput && spiDisplay) {
                        function updateSPI() {
                            const planned = parseFloat(plannedInput.value) || 0;
                            const actual = parseFloat(actualInput.value) || 0;
                            const spi = planned > 0 ? ((actual / planned) * 100).toFixed(2) : '0.00';
                            spiDisplay.textContent = spi + '%';
                        }
                        plannedInput.addEventListener('input', updateSPI);
                        actualInput.addEventListener('input', updateSPI);
                    }
                });
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openViewModal(id) {
            fetch(`/admin/progress-updates/${id}?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('viewModalContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                    lucide.createIcons();
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openEditModal(id) {
            fetch(`/admin/progress-updates/${id}/edit?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    document.getElementById('editModal').classList.remove('hidden');
                    lucide.createIcons();

                    const plannedInput = document.getElementById('editPlanned');
                    const actualInput = document.getElementById('editActual');
                    const spiDisplay = document.getElementById('editSpiDisplay');

                    if (plannedInput && actualInput && spiDisplay) {
                        function updateEditSPI() {
                            const planned = parseFloat(plannedInput.value) || 0;
                            const actual = parseFloat(actualInput.value) || 0;
                            const spi = planned > 0 ? ((actual / planned) * 100).toFixed(2) : '0.00';
                            spiDisplay.textContent = spi + '%';
                        }
                        plannedInput.addEventListener('input', updateEditSPI);
                        actualInput.addEventListener('input', updateEditSPI);
                    }
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeViewModal();
                closeEditModal();
            }
        });
    </script>
</x-app-layout>
