<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Financial <span class="font-black text-slate-900">Protocols</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Project Payment Management</p>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                    <i data-lucide="banknote" class="w-3.5 h-3.5 text-white/90"></i>
                    <span class="text-base font-black text-white tracking-tight">{{ $projects->total() }}</span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Projects Registered</span>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <?php $paymentAdvancedFiltersActive = request()->filled('status'); ?>
        <form action="{{ route('admin.projects.payments.index') }}" method="GET" class="space-y-4">
            <div class="admin-filter-grid bg-white p-4 rounded-2xl shadow-lg shadow-slate-200/30 border border-slate-50 gap-3 w-full">
                <div class="min-w-[220px] max-w-[320px] relative group shrink-0">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#00ADC5] transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, code or ID..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-xs font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                </div>

                @include('admin.partials.project-organization-filters')

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" aria-controls="paymentAdvancedFilters" aria-expanded="{{ $paymentAdvancedFiltersActive ? 'true' : 'false' }}" onclick="toggleFilterPanel(this, 'paymentAdvancedFilters')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[9px] font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-[#00515f]">
                        <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i>More filters
                        <i data-lucide="chevron-down" class="filter-panel-chevron h-3.5 w-3.5 transition-transform {{ $paymentAdvancedFiltersActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'business_unit_id', 'sector_id']))
                        <a href="{{ route('admin.projects.payments.index') }}" class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 p-2 text-rose-500 transition hover:bg-rose-500 hover:text-white" title="Clear Filters">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </a>
                    @endif
                </div>
                <div id="paymentAdvancedFilters" class="admin-filter-advanced-panel {{ $paymentAdvancedFiltersActive ? '' : 'hidden' }} rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                    <div class="admin-filter-advanced-grid gap-3">
                        <div class="relative group/select">
                            <select name="status" onchange="this.form.submit()" class="w-full appearance-none rounded-xl border-2 border-transparent bg-white px-4 py-3 pr-10 text-[9px] font-black uppercase tracking-widest text-slate-500 transition-all hover:bg-slate-100 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                <option value="in progress" {{ request('status', 'in progress') == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400 transition-transform group-hover/select:translate-y-[-40%]"></i>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @include('admin.partials.filter-panel-toggle-script')

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">#</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Contract Value</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Total Certified</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Total Paid</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Remaining Balance</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Payment Progress</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($projects as $project)
                            @php
                                $contractValue = $project->total_project_value;
                                $totalCertified = $project->total_certified ?? 0;
                                $totalPaid = $project->total_paid ?? 0;
                                $remainingBalance = $contractValue - $totalCertified;
                                $paymentProgress = $contractValue > 0 ? ($totalPaid / $contractValue) * 100 : 0;
                            @endphp
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">{{ $project->project_code }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[11px] font-black tabular-nums tracking-tight shadow-sm">
                                        <span class="text-slate-500 font-bold mr-1">ETB</span>
                                        {{ number_format($contractValue) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[11px] font-black tabular-nums tracking-tight shadow-sm">
                                        <span class="text-slate-500 font-bold mr-1">ETB</span>
                                        {{ number_format($totalCertified) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[11px] font-black tabular-nums tracking-tight shadow-sm">
                                        <span class="text-slate-500 font-bold mr-1">ETB</span>
                                        {{ number_format($totalPaid) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white border border-slate-200 text-slate-900 text-[11px] font-black tabular-nums tracking-tight shadow-sm">
                                        <span class="text-slate-500 font-bold mr-1">ETB</span>
                                        {{ number_format($remainingBalance) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $paymentProgress >= 100 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : ($paymentProgress > 0 ? 'bg-[#00ADC5]/10 text-[#00ADC5] border-[#00ADC5]/20' : 'bg-slate-100 text-slate-900 border-slate-200') }} border shadow-sm group-hover:shadow-md transition-all">
                                        {{ number_format($paymentProgress, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.projects.payments.manage', $project) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 text-slate-700 font-black text-[9px] uppercase tracking-widest rounded-lg hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-300 shadow-sm">
                                        <span>Manage</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="database" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">No Projects Found</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Create a project first to initialize financial protocols.</p>
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
            <div class="p-6 border-t border-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/30">
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 px-3 h-10 bg-white border border-slate-100 rounded-xl">
                        <span class="text-[9px] font-black text-slate-900 tracking-widest">{{ $projects->firstItem() ?: 0 }}</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">to</span>
                        <span class="text-[9px] font-black text-slate-400 tracking-widest">{{ $projects->lastItem() ?: 0 }}</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">of</span>
                        <span class="text-[9px] font-black text-slate-400 tracking-widest">{{ $projects->total() }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
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
