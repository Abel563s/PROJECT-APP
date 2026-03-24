<x-app-layout>
    <div class="py-10 px-6 space-y-12 max-w-[1700px] mx-auto font-inter">
        
        <!-- Premium Header Area -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 pb-4 border-b border-slate-100">
            <div class="space-y-3">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-xl shadow-slate-900/20">
                        <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-light text-slate-400 tracking-tight font-outfit leading-none">
                            Control <span class="font-black text-slate-900">Center</span>
                        </h2>
                        <p class="text-[10px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-2">Executive Performance Overview</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Status indicator removed -->
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-slate-200/40 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-cyan-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                            <i data-lucide="activity" class="w-6 h-6"></i>
                        </div>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Active</span>
                    </div>
                    <div>
                        <h3 class="text-4xl font-black text-slate-900 tracking-tight">{{ $stats['total_active'] }}</h3>
                        <p class="text-sm font-bold text-slate-400 mt-2 uppercase tracking-widest">Active Projects</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-slate-200/40 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <i data-lucide="banknote" class="w-6 h-6"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Baseline</span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">ETB {{ number_format($stats['total_contract_value'] / 1000000, 1) }}M</h3>
                        <p class="text-sm font-bold text-slate-400 mt-2 uppercase tracking-widest">Project Value</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-slate-200/40 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i data-lucide="target" class="w-6 h-6"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ceiling</span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">ETB {{ number_format($stats['total_allowable_cost'] / 1000000, 1) }}M</h3>
                        <p class="text-sm font-bold text-slate-400 mt-2 uppercase tracking-widest">Allowable Cost</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-8 rounded-[2.5rem] shadow-2xl shadow-indigo-100 group relative overflow-hidden border border-indigo-400/20">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all"></div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white">
                            <i data-lucide="trending-up" class="w-6 h-6"></i>
                        </div>
                        <span class="text-[10px] font-black text-white/50 uppercase tracking-widest">Forecast</span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-white tracking-tight">ETB {{ number_format($stats['cost_at_completion'] / 1000000, 1) }}M</h3>
                        <p class="text-sm font-bold text-white/60 mt-2 uppercase tracking-widest">Cost at Completion</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Project List Preview -->
            <div class="lg:col-span-2 bg-white rounded-[3rem] p-10 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight font-outfit">Project Inventory</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Recent created projects</p>
                    </div>
                    <a href="{{ route('admin.projects.index') }}" class="px-6 py-2.5 bg-slate-50 text-slate-900 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5] hover:text-white transition-all">View All Archives</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identity</th>
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Metric</th>
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Timeframe</th>
                                <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($projects->take(5) as $project)
                                <tr class="group cursor-pointer hover:bg-slate-50/50 transition-all transition-duration-300" onclick="window.location='{{ route('admin.projects.show', $project->id) }}'">
                                    <td class="py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-[10px] text-slate-400 group-hover:bg-[#00ADC5] group-hover:text-white transition-all">
                                                {{ substr($project->project_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900 leading-tight">{{ $project->project_name }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $project->project_code }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5">
                                        <p class="text-sm font-black text-slate-700">ETB {{ number_format($project->total_project_value / 1000, 0) }}K</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">Value Basis</p>
                                    </td>
                                    <td class="py-5">
                                        <p class="text-sm font-black text-slate-700">{{ $project->baseline_finish_date ? $project->baseline_finish_date->format('M Y') : 'N/A' }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">Target Node</p>
                                    </td>
                                    <td class="py-5 text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $project->closing_status === 'Not Completed' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
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
            <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-slate-100 relative overflow-hidden flex flex-col justify-between group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-cyan-50 rounded-full blur-3xl group-hover:bg-cyan-100 transition-all duration-700"></div>
                
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight font-outfit">Sector Vitality</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Projects diversification</p>
                </div>

                <div class="relative z-10 my-10 h-48">
                    <canvas id="sectorChart"></canvas>
                </div>

                <div class="relative z-10 space-y-4">
                    @foreach($projectTypes->take(3) as $type => $count)
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full {{ $loop->first ? 'bg-[#00ADC5]' : ($loop->index == 1 ? 'bg-indigo-500' : 'bg-emerald-500') }}"></div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $type }}</span>
                            </div>
                            <span class="text-xs font-black text-slate-900">{{ $count }} Units</span>
                        </div>
                    @endforeach
                </div>
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
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>