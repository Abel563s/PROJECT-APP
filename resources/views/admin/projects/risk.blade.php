<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Risk <span class="font-black text-slate-900">Registry</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Project Risk Assessment</p>
                </div>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                <i data-lucide="folder-kanban" class="w-3.5 h-3.5 text-white/90"></i>
                <span class="text-base font-black text-white tracking-tight">{{ count($risks) }}</span>
                <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Projects</span>
            </div>
        </div>

        <!-- Search & Filter -->
        @php
            $riskFiltersActive = request()->anyFilled(['search', 'project_id', 'impact', 'risk_type', 'risk_status']);
        @endphp
        <form action="{{ route('admin.projects.risk.index') }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-{{ $riskFiltersActive ? '6' : '5' }}">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Projects</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, code, or client"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project</span>
                    <select name="project_id" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="all">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project['id'] }}" @selected(request('project_id') == $project['id'])>{{ $project['project_code'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact</span>
                    <select name="impact" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="all">All Impacts</option>
                        @foreach(['High', 'Medium', 'Low', 'Extreme'] as $impact)
                            <option value="{{ $impact }}" @selected(request('impact') === $impact)>{{ $impact }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Risk Type</span>
                    <select name="risk_type" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="all">All Risk Types</option>
                        @foreach($riskTypes as $type)
                            <option value="{{ $type }}" @selected(request('risk_type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Risk Status</span>
                    <select name="risk_status" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="all">All Statuses</option>
                        @foreach(['Open', 'Inprogress', 'Closed'] as $status)
                            <option value="{{ $status }}" @selected(request('risk_status') === $status)>{{ $status === 'Inprogress' ? 'In Progress' : $status }}</option>
                        @endforeach
                    </select>
                </label>
                @if($riskFiltersActive)
                    <div class="flex items-end justify-end">
                        <a href="{{ route('admin.projects.risk.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-600 rounded-lg border border-rose-200 hover:bg-rose-500 hover:text-white transition-all active:scale-95 shadow-sm text-[9px] font-black uppercase tracking-widest">
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
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Total Risk</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Open</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">In Progress</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Closed</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($risks as $risk)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-slate-900 leading-tight break-words">{{ $risk['project_code'] ?? '---' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white/70 backdrop-blur-sm border border-slate-200 text-slate-700 shadow-sm">
                                        {{ $risk['total_risk'] ?? '0' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $openRiskClass = 'bg-sky-50 text-sky-600 border-sky-200';
                                        $openRiskCount = $risk['open_risk'] ?? 0;
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $openRiskClass }} border shadow-sm">
                                        {{ $openRiskCount }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $inProgressRiskClass = 'bg-amber-50 text-amber-600 border-amber-200';
                                        $inProgressRiskCount = $risk['in_progress_risk'] ?? 0;
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $inProgressRiskClass }} border shadow-sm">
                                        {{ $inProgressRiskCount }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $closedRiskClass = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                                        $closedRiskCount = $risk['closed_risk'] ?? 0;
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $closedRiskClass }} border shadow-sm">
                                        {{ $closedRiskCount }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.projects.risk.manage', $risk['id']) }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 text-slate-700 font-black text-[9px] uppercase tracking-widest rounded-lg hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-300 shadow-sm">
                                            <span>Manage</span>
                                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h3 class="text-lg font-black text-slate-900">No Risk Registered</h3>
                                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Add risk items to start tracking.</p>
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
                        <span class="text-[9px] font-black text-slate-900 tracking-widest">{{ sprintf('%02d', count($risks) > 0 ? 1 : 0) }}</span>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">of</span>
                        <span class="text-[9px] font-black text-slate-400 tracking-widest">{{ sprintf('%02d', count($risks)) }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{ count($risks) > 0 ? '' : '' }}
                </div>
            </div>
        </div>
    </div>

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
