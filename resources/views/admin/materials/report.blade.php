<x-app-layout>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">
        @php
            $orderedKeys = ['design','spec','ro','supplier','submittal','approval','payment','lc','production','shipment','logistics','clearance','delivery'];
            $orderedLabels = ['Design','Specification','RO','Supplier','Submittal','Approval','Finance','LC','Production','Shipment','Logistics','Clearance','Delivery to Site'];

            $statsByKey = [];
            foreach ($stage_stats as $key => $stage) {
                $statsByKey[$key] = $stage;
            }

            $dedupedStageStats = [];
            foreach ($orderedKeys as $index => $key) {
                if (isset($statsByKey[$key])) {
                    $stage = $statsByKey[$key];
                    if ($key === 'delivery') {
                        $stage['label'] = 'Delivery to Site';
                    }
                    $dedupedStageStats[$key] = $stage;
                } else {
                    $dedupedStageStats[$key] = [
                        'label' => $orderedLabels[$index],
                        'planned' => 0,
                        'completed' => 0,
                        'outstanding' => 0,
                        'delayed' => 0,
                        'total_variance' => 0,
                        'variance_count' => 0,
                        'materials' => [],
                        'completion_pct' => 0,
                        'avg_delay' => 0,
                        'status' => 'No Data',
                        'status_color' => 'bg-slate-50 text-slate-500 border-slate-200',
                    ];
                }
            }
        @endphp

        <!-- Dashboard Header -->
        <div class="bg-gradient-to-r from-[#00515F] to-[#00ADC5] rounded-3xl shadow-xl shadow-slate-900/20 overflow-hidden">
            <div class="px-6 py-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-white tracking-tight">Material Reports</h2>
                        <p class="text-[9px] font-black text-white/70 uppercase tracking-widest">Procurement Performance Dashboard</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-widest px-3 py-1.5 bg-white/10 rounded-lg">
                        {{ now()->format('d M Y') }}
                    </span>
                    <select onchange="window.location.href='?filter='+this.value" class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-[9px] font-black text-white uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-white/30 cursor-pointer">
                        <option value="all" {{ $current_filter === 'all' ? 'selected' : '' }}>All Materials</option>
                        <option value="local" {{ $current_filter === 'local' ? 'selected' : '' }}>Local Materials</option>
                        <option value="import" {{ $current_filter === 'import' ? 'selected' : '' }}>Import Materials</option>
                    </select>
                    <button onclick="window.print()" class="px-4 py-2 bg-white/10 border border-white/20 text-white rounded-lg font-black text-[9px] uppercase tracking-widest hover:bg-white/20 transition-all active:scale-95">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                            Export PDF
                        </span>
                    </button>
                    <button onclick="exportExcel()" class="px-4 py-2 bg-emerald-500 border border-emerald-400 text-white rounded-lg font-black text-[9px] uppercase tracking-widest hover:bg-emerald-600 transition-all active:scale-95 shadow-lg shadow-emerald-900/20">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            Export Excel
                        </span>
                    </button>
                    <a href="{{ route('admin.material-progress.index') }}" class="px-4 py-2 bg-white/10 border border-white/20 text-white rounded-lg font-black text-[9px] uppercase tracking-widest hover:bg-white/20 transition-all active:scale-95">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                            Refresh
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Executive KPI Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Materials</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($total_materials) }}</h3>
                <p class="text-[9px] font-bold text-slate-400 mt-1">{{ $projects_count }} Projects • {{ $suppliers_count }} Suppliers</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Completed</p>
                <h3 class="text-2xl font-black text-emerald-600">{{ number_format($completed_materials) }}</h3>
                <p class="text-[9px] font-bold text-slate-400 mt-1">{{ $total_materials > 0 ? round(($completed_materials / $total_materials) * 100, 1) : 0 }}% of total</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Outstanding</p>
                <h3 class="text-2xl font-black text-amber-600">{{ number_format($outstanding_materials) }}</h3>
                <p class="text-[9px] font-bold text-slate-400 mt-1">{{ $total_materials > 0 ? round(($outstanding_materials / $total_materials) * 100, 1) : 0 }}% of total</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Delayed</p>
                <h3 class="text-2xl font-black text-rose-600">{{ number_format($delayed_materials) }}</h3>
                <p class="text-[9px] font-bold text-slate-400 mt-1">{{ $total_materials > 0 ? round(($delayed_materials / $total_materials) * 100, 1) : 0 }}% of total</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Avg Completion</p>
                <h3 class="text-2xl font-black text-sky-600">{{ $avg_completion }}%</h3>
                <div class="mt-2 w-full bg-slate-50 h-1.5 rounded-full overflow-hidden border border-slate-100">
                    <div class="bg-sky-500 h-full rounded-full transition-all duration-500" style="width: {{ $avg_completion }}%"></div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-all">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Critical Delays</p>
                <h3 class="text-2xl font-black text-rose-600">{{ number_format($critical_delays) }}</h3>
                <p class="text-[9px] font-bold text-slate-400 mt-1">Balanced days0.
                    
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                &lt; -15d</p>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Completion by Stage -->
            <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between px-1 mb-4">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Completion by Stage</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Progress across procurement pipeline</p>
                    </div>
                </div>
                <div class="h-[300px] w-full">
                    <canvas id="completionChart"></canvas>
                </div>
            </div>

            <!-- Delayed Materials -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-1 mb-4">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Delayed Materials</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">By procurement stage</p>
                </div>
                <div class="h-[300px] w-full">
                    <canvas id="delayedChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Procurement Pipeline -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="text-sm font-black text-slate-900 tracking-tight">Procurement Pipeline</h3>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Stage-by-stage performance overview</p>
            </div>
            <div class="p-5 overflow-x-auto">
                <div class="flex items-center gap-2 min-w-max">
                    @foreach($dedupedStageStats as $key => $stage)
                        @if($stage['planned'] > 0 || $key === 'delivery')
                            <div class="flex items-center gap-2">
                                <div class="flex flex-col items-center gap-1 p-3 rounded-xl border border-slate-100 bg-slate-50/50 min-w-[120px]">
                                    <span class="text-[9px] font-black text-slate-700 uppercase tracking-wider">{{ $stage['label'] }}</span>
                                    <div class="flex items-center gap-2 text-[9px] font-black">
                                        <span class="text-emerald-600">{{ $stage['completed'] }}</span>
                                        <span class="text-slate-400">Done</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[9px] font-black">
                                        <span class="text-amber-600">{{ $stage['outstanding'] }}</span>
                                        <span class="text-slate-400">Left</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[9px] font-black">
                                        <span class="text-rose-600">{{ $stage['delayed'] }}</span>
                                        <span class="text-slate-400">Late</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $stage['completion_pct'] >= 90 ? 'bg-emerald-500' : ($stage['completion_pct'] >= 70 ? 'bg-blue-500' : ($stage['completion_pct'] >= 50 ? 'bg-orange-400' : 'bg-red-500')) }}" style="width: {{ $stage['completion_pct'] }}%"></div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 shrink-0"></i>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Report Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden sticky top-0 z-10">
            <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Stage Performance Report</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Detailed breakdown by procurement stage</p>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ count($dedupedStageStats) }} Stages</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest">Stage</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest">Progress</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Completion</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Planned</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Completed</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Outstanding</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Delayed</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Status</th>
                            <th class="px-5 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                    @foreach($dedupedStageStats as $key => $stage)
                            @if($stage['planned'] > 0 || $key === 'delivery')
                                <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                                    <td class="px-5 py-4">
                                        <span class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $stage['label'] }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="w-40">
                                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500 {{ $stage['completion_pct'] >= 90 ? 'bg-emerald-500' : ($stage['completion_pct'] >= 70 ? 'bg-blue-500' : ($stage['completion_pct'] >= 50 ? 'bg-orange-400' : 'bg-red-500')) }}" style="width: {{ $stage['completion_pct'] }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="text-sm font-black {{ $stage['completion_pct'] >= 90 ? 'text-emerald-600' : ($stage['completion_pct'] >= 70 ? 'text-blue-600' : ($stage['completion_pct'] >= 50 ? 'text-orange-600' : 'text-red-600')) }}">{{ $stage['completion_pct'] }}%</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="text-sm font-black text-slate-700">{{ number_format($stage['planned']) }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="text-sm font-black text-emerald-600">{{ number_format($stage['completed']) }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ number_format($stage['outstanding']) }} Left
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if($stage['delayed'] > 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                                ⚠ {{ number_format($stage['delayed']) }} Delayed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ✔ None
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $stage['status_color'] }}">
                                            {{ $stage['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <button onclick="toggleExpand('{{ $key }}')" class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-200">
                                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" id="chevron-{{ $key }}"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr id="expand-{{ $key }}" class="hidden">
                                    <td colspan="9" class="px-5 py-0">
                                        <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-4 mb-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ $stage['label'] }} Materials</h4>
                                                <span class="text-[9px] font-black text-slate-400">{{ number_format(count($stage['materials'])) }} Total</span>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                                @foreach(array_slice($stage['materials'], 0, 12) as $mat)
                                                    <div class="bg-white border border-slate-100 rounded-lg p-3 hover:shadow-sm transition-all">
                                                        <div class="flex items-center justify-between gap-2 mb-1">
                                                            <span class="text-[10px] font-black text-slate-900 truncate">{{ $mat['material_name'] }}</span>
                                                            <span class="text-[9px] font-black px-2 py-0.5 rounded-md {{ $mat['status'] === 'Completed' ? 'bg-emerald-50 text-emerald-700' : ($mat['status'] === 'Delayed' ? 'bg-rose-50 text-rose-700' : 'bg-sky-50 text-sky-700') }}">{{ $mat['status'] }}</span>
                                                        </div>
                                                        <p class="text-[9px] font-bold text-slate-400 mb-2">{{ $mat['supplier_name'] }} • {{ $mat['project_code'] }}</p>
                                                        <div class="flex items-center gap-2 text-[9px] font-black text-slate-500">
                                                            <span class="text-slate-400">Planned:</span>
                                                            <span class="text-slate-700">{{ $mat['planned'] ?? '—' }}</span>
                                                            <span class="text-slate-300">|</span>
                                                            <span class="text-slate-400">Actual:</span>
                                                            <span class="text-slate-700">{{ $mat['actual'] ?? '—' }}</span>
                                                            @if($mat['variance'] > 0)
                                                                <span class="text-rose-600 ml-auto">+{{ $mat['variance'] }}d</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if(count($stage['materials']) > 12)
                                                <p class="text-[9px] font-black text-slate-400 text-center mt-3 uppercase tracking-widest">+{{ number_format(count($stage['materials']) - 12) }} more materials</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            function toggleExpand(key) {
                const row = document.getElementById('expand-' + key);
                const chevron = document.getElementById('chevron-' + key);
                if (row.classList.contains('hidden')) {
                    row.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                } else {
                    row.classList.add('hidden');
                    chevron.style.transform = 'rotate(0deg)';
                }
            }

            function exportExcel() {
                alert('Excel export would generate a CSV file from the current report data.');
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Completion Chart
                const completionLabels = @json(collect($dedupedStageStats)->pluck('label'));
                const completionData = @json(collect($dedupedStageStats)->pluck('completion_pct'));
                const completionColors = completionData.map(pct => {
                    if (pct >= 90) return '#10b981';
                    if (pct >= 70) return '#3b82f6';
                    if (pct >= 50) return '#f97316';
                    return '#ef4444';
                });

                new Chart(document.getElementById('completionChart'), {
                    type: 'bar',
                    data: {
                        labels: completionLabels,
                        datasets: [{
                            label: 'Completion %',
                            data: completionData,
                            backgroundColor: completionColors,
                            borderRadius: 8,
                            borderSkipped: false,
                            barThickness: 24
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 100,
                                grid: { display: true, color: '#f1f5f9', drawBorder: false },
                                ticks: {
                                    font: { weight: 'bold', size: 9 },
                                    callback: function (value) { return value + '%'; }
                                }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { weight: 'bold', size: 9 }, color: '#64748b' }
                            }
                        }
                    }
                });

                // Delayed Chart
                const delayedLabels = @json(collect($dedupedStageStats)->filter(fn($s) => $s['delayed'] > 0)->pluck('label'));
                const delayedData = @json(collect($dedupedStageStats)->filter(fn($s) => $s['delayed'] > 0)->pluck('delayed'));

                new Chart(document.getElementById('delayedChart'), {
                    type: 'bar',
                    data: {
                        labels: delayedLabels,
                        datasets: [{
                            label: 'Delayed',
                            data: delayedData,
                            backgroundColor: '#f43f5e',
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
                                ticks: { font: { weight: 'bold', size: 9 } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { weight: 'bold', size: 8 }, color: '#64748b', maxRotation: 45, minRotation: 0 }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
