<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Portfolio <span class="font-black text-slate-900">Intelligence</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Macro-level reconnaissance & financial equilibrium</p>
                </div>
            </div>
            <button onclick="window.print()" 
                    class="px-4 py-2 bg-white border border-slate-100 text-slate-600 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-slate-50 active:scale-95 shadow-sm">
                <span class="flex items-center gap-1.5">
                    <i data-lucide="printer" class="w-3.5 h-3.5 text-[#00ADC5]"></i>
                    Generate Report
                </span>
            </button>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Portfolio Value</p>
                <h3 class="text-sm font-black text-slate-900 tracking-tight">
                    <span class="text-[9px] font-bold text-slate-400 mr-1">ETB</span>{{ number_format($totalPortfolioValue / 1000000, 1) }}M
                </h3>
                <div class="mt-2 pt-2 border-t border-slate-50 flex items-center gap-1">
                    <i data-lucide="trending-up" class="w-2.5 h-2.5 text-emerald-500"></i>
                    <span class="text-[8px] font-black text-emerald-500 uppercase">+{{ number_format($totalVariations / 1000000, 1) }}M Var.</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Allowable Cost</p>
                <h3 class="text-sm font-black text-slate-900 tracking-tight">
                    <span class="text-[9px] font-bold text-slate-400 mr-1">ETB</span>{{ number_format($totalAllowableCost / 1000000, 1) }}M
                </h3>
                <div class="mt-2 pt-2 border-t border-slate-50">
                    <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden border border-slate-100">
                        <div class="bg-indigo-500 h-full rounded-full" style="width: {{ ($totalAllowableCost / $totalPortfolioValue) * 100 }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Cost at Completion</p>
                <h3 class="text-sm font-black text-slate-900 tracking-tight">
                    <span class="text-[9px] font-bold text-slate-400 mr-1">ETB</span>{{ number_format($totalCostAtCompletion / 1000000, 1) }}M
                </h3>
                <div class="mt-2 pt-2 border-t border-slate-50 flex items-center gap-1">
                    <i data-lucide="eye" class="w-3 h-3 text-slate-300"></i>
                    <span class="text-[8px] font-black text-slate-400 uppercase">Projection</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#00ADC5] to-cyan-600 p-4 rounded-2xl border border-cyan-400/20 shadow-lg relative overflow-hidden group">
                <p class="text-[8px] font-black text-white/70 uppercase tracking-widest mb-2">Financial Equilibrium</p>
                <h3 class="text-sm font-black text-white tracking-tight">
                    <span class="text-[9px] font-bold text-white/50 mr-1">ETB</span>{{ number_format($costVariance / 1000000, 1) }}M
                </h3>
                <p class="text-[8px] font-black text-white uppercase mt-2 tracking-wider bg-white/10 w-fit px-1.5 py-0.5 rounded">
                    {{ $costVariance >= 0 ? 'Surplus' : 'Deficit' }}
                </p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Temporal Variance</p>
                <h3 class="text-sm font-black text-slate-900 tracking-tight">
                    {{ number_format($avgScheduleVariance, 1) }} <span class="text-[9px] font-bold text-slate-400 ml-1">Days</span>
                </h3>
                <div class="mt-2 pt-2 border-t border-slate-50 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-2.5 h-2.5 text-rose-400"></i>
                    <span class="text-[8px] font-black text-rose-400 uppercase">Avg Drift</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Portfolio Value Chart -->
            <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Portfolio Asset Value</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Value distribution by project</p>
                    </div>
                </div>
                <div class="h-[300px] w-full">
                    <canvas id="portfolioValueChart"></canvas>
                </div>
            </div>

            <!-- Sector Distribution -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
                <div class="px-1">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Sector Distribution</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Portfolio allocation by sector</p>
                </div>
                <div class="h-[220px] w-full flex items-center justify-center">
                    <canvas id="sectorDistributionChart"></canvas>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                    @foreach($sectorValues as $sector => $value)
                        <div class="flex items-center justify-between text-[9px] font-bold">
                            <span class="text-slate-500 uppercase tracking-wider">{{ $sector }}</span>
                            <span class="text-slate-900">{{ number_format(($value / $totalPortfolioValue) * 100, 1) }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Top Stakeholders -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3 px-1">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Top Stakeholders</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">By asset value</p>
                    </div>
                </div>
                <div class="space-y-2">
                    @foreach($clientValues->take(5) as $client => $value)
                        <div class="group flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-all border border-transparent hover:border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-md bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                                    <span class="text-[9px] font-black">{{ substr($client, 0, 1) }}</span>
                                </div>
                                <span class="text-[10px] font-black text-slate-700">{{ $client }}</span>
                            </div>
                            <span class="text-[10px] font-mono font-black text-slate-900">ETB {{ number_format($value / 1000000, 1) }}M</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Project Type Performance -->
            <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3 px-1">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Module Performance</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Financial sync by project type</p>
                    </div>
                </div>
                <div class="h-[260px] w-full">
                    <canvas id="typePerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Portfolio Value Chart
                new Chart(document.getElementById('portfolioValueChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($projects->pluck('project_code')->take(10)) !!},
                        datasets: [{
                            label: 'Total Project Value (ETB)',
                            data: {!! json_encode($projects->map(fn($p) => (float) $p->total_project_value)->take(10)) !!},
                            backgroundColor: '#00ADC5',
                            borderRadius: 8,
                            borderSkipped: false,
                            barThickness: 24
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { display: true, color: '#f1f5f9', drawBorder: false },
                                ticks: {
                                    font: { weight: 'bold', size: 9 },
                                    callback: function (value) { return 'ETB ' + (value / 1000000).toFixed(1) + 'M'; }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { weight: 'bold', size: 8 }, color: '#94a3b8', maxRotation: 45, minRotation: 0 }
                            }
                        }
                    }
                });

                // Sector Distribution Chart
                new Chart(document.getElementById('sectorDistributionChart'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($sectorValues->keys()) !!},
                        datasets: [{
                            data: {!! json_encode($sectorValues->values()) !!},
                            backgroundColor: ['#00ADC5', '#6366f1', '#f59e0b', '#10b981', '#ef4444'],
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { weight: 'bold', size: 9 }, usePointStyle: true, padding: 12 } }
                        }
                    }
                });

                // Type Performance Chart
                new Chart(document.getElementById('typePerformanceChart'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($projectTypes->keys()) !!},
                        datasets: [{
                            label: 'Project Count',
                            data: {!! json_encode($projectTypes->values()) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 5,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { weight: 'bold', size: 9 } } },
                            x: { grid: { display: false }, ticks: { font: { weight: 'bold', size: 8 } } }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
