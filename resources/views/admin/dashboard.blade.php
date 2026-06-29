<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Welcome Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Welcome back, <span class="font-black text-[#00ADC5]">{{ Auth::user()->name }}</span>
                    </h2>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 bg-[#00ADC5]/10 text-[#00ADC5] rounded-lg text-[9px] font-black uppercase tracking-widest">Admin Portal</span>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                    </div>
                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[8px] font-black uppercase tracking-widest">Active</span>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $stats['total_active'] }}</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Active Projects</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <i data-lucide="banknote" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Baseline</span>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">ETB {{ number_format($stats['total_contract_value'] / 1000000, 1) }}M</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Project Value</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="target" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Ceiling</span>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">ETB {{ number_format($stats['total_allowable_cost'] / 1000000, 1) }}M</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Allowable Cost</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#00ADC5] to-cyan-600 p-4 rounded-2xl border border-cyan-400/20 shadow-lg space-y-2">
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[8px] font-black text-white/50 uppercase tracking-widest">Forecast</span>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white tracking-tight">ETB {{ number_format($stats['cost_at_completion'] / 1000000, 1) }}M</h3>
                    <p class="text-[8px] font-black text-white/60 uppercase tracking-widest mt-0.5">Cost at Completion</p>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Near Deadline</p>
                </div>
                <h3 class="text-lg font-black text-slate-900">{{ $stats['near_deadline'] }}</h3>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Due within 30 days</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Completed</p>
                </div>
                <h3 class="text-lg font-black text-slate-900">{{ $stats['completed'] }}</h3>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Financial closure</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Total Projects</p>
                </div>
                <h3 class="text-lg font-black text-slate-900">{{ $projects->count() }}</h3>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">In registry</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Overdue</p>
                </div>
                <h3 class="text-lg font-black text-slate-900">
                    {{ $projects->where('baseline_finish_date', '<', now())->where('closing_status', 'Not Completed')->count() }}
                </h3>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Past baseline</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Project List Preview -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Project Inventory</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Recent created projects</p>
                    </div>
                    <a href="{{ route('admin.projects.index') }}" class="px-3 py-1.5 bg-slate-50 text-slate-900 rounded-lg font-black text-[9px] uppercase tracking-widest hover:bg-[#00ADC5] hover:text-white transition-all">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#00ADC5] to-[#00ADC5]">
                                <th class="px-4 py-2.5 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Identity</th>
                                <th class="px-4 py-2.5 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Metric</th>
                                <th class="px-4 py-2.5 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Timeframe</th>
                                <th class="px-4 py-2.5 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($projects->take(5) as $project)
                                <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-white shadow border border-slate-100 flex items-center justify-center transition-all duration-300 group-hover:rotate-[10deg] group-hover:scale-110 group-hover:border-[#00ADC5]">
                                                <span class="font-black text-[8px] text-slate-300 group-hover:text-[#00ADC5] tracking-tighter">{{ substr($project->project_name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">
                                                    {{ $project->project_code }}
                                                </p>
                                                <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $project->project_code }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-xs font-black text-slate-900">ETB {{ number_format($project->total_project_value / 1000, 0) }}K</p>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Value Basis</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-xs font-black text-slate-900">{{ $project->baseline_finish_date ? $project->baseline_finish_date->format('M Y') : 'N/A' }}</p>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Target Node</p>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $project->closing_status === 'Not Completed' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                                            {{ $project->closing_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Distribution Chart -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-3">
                <div>
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Sector Vitality</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Projects diversification</p>
                </div>
                <div class="h-[200px] w-full flex items-center justify-center">
                    <canvas id="sectorChart"></canvas>
                </div>
                <div class="space-y-2">
                    @foreach($projectTypes->take(3) as $type => $count)
                        <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 transition-all">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $loop->first ? 'bg-[#00ADC5]' : ($loop->index == 1 ? 'bg-indigo-500' : 'bg-emerald-500') }}"></div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $type }}</span>
                            </div>
                            <span class="text-[10px] font-black text-slate-900">{{ $count }} Units</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="text-sm font-black text-slate-900 tracking-tight mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('admin.projects.create') }}" class="flex items-center justify-center gap-2 p-3 bg-slate-50 rounded-xl hover:bg-[#00ADC5] hover:text-white transition-all group">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">New Project</span>
                </a>
                <a href="{{ route('admin.projects.payments.index') }}" class="flex items-center justify-center gap-2 p-3 bg-slate-50 rounded-xl hover:bg-[#00ADC5] hover:text-white transition-all group">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Payments</span>
                </a>
                <a href="{{ route('admin.progress-updates.index') }}" class="flex items-center justify-center gap-2 p-3 bg-slate-50 rounded-xl hover:bg-[#00ADC5] hover:text-white transition-all group">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Progress</span>
                </a>
                <a href="{{ route('admin.weekly-updates.index') }}" class="flex items-center justify-center gap-2 p-3 bg-slate-50 rounded-xl hover:bg-[#00ADC5] hover:text-white transition-all group">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Weekly</span>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('sectorChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($projectTypes->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($projectTypes->values()) !!},
                        backgroundColor: ['#00ADC5', '#6366F1', '#3B82F6', '#8B5CF6', '#EC4899'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
