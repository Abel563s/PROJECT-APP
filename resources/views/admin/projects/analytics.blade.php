<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        <style>
            .analytics-kpi-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .analytics-kpi-card:hover {
                transform: translateY(-4px);
            }
            .analytics-kpi-card .progress-bar {
                transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            }
            @keyframes countUp {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .analytics-kpi-card h3 {
                animation: countUp 0.6s ease-out;
            }
        </style>

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Active Projects <span class="font-black text-slate-900">Dashboard</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Macro-level reconnaissance & financial equilibrium</p>
                </div>
            </div>
            <form action="{{ route('admin.projects.analytics') }}" method="GET" class="flex items-center gap-2">
                <div class="flex items-center gap-2 bg-white border border-slate-100 rounded-xl px-3 py-2 shadow-sm">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        placeholder="From"
                        class="bg-transparent border-none text-[9px] font-black text-slate-600 uppercase tracking-widest focus:outline-none focus:ring-0 w-[130px]">
                    <span class="text-[8px] font-black text-slate-300">TO</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        placeholder="To"
                        class="bg-transparent border-none text-[9px] font-black text-slate-600 uppercase tracking-widest focus:outline-none focus:ring-0 w-[130px]">
                    <button type="submit"
                        class="px-3 py-1.5 bg-slate-900 text-white rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm">
                        Apply
                    </button>
                    @if(request()->anyFilled(['date_from', 'date_to']))
                        <a href="{{ route('admin.projects.analytics') }}"
                            class="flex items-center justify-center w-7 h-7 bg-rose-50 text-rose-500 rounded-md hover:bg-rose-500 hover:text-white transition-all active:scale-90"
                            title="Clear Date Filter">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Enhanced KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <!-- Total Projects -->
            <div class="analytics-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-cyan-200 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-50/0 to-cyan-50/0 group-hover:from-cyan-50/50 group-hover:to-cyan-50/30 transition-all duration-300"></div>
                <div class="relative z-10 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-[#00ADC5] flex items-center justify-center text-white shadow-lg shadow-cyan-500/30">
                            <i data-lucide="folder-open" class="w-5 h-5"></i>
                        </div>
                        <div class="flex items-center gap-1 bg-cyan-50 px-2 py-1 rounded-lg border border-cyan-100">
                            <i data-lucide="trending-up" class="w-3 h-3 text-cyan-600"></i>
                            <span class="text-[9px] font-black text-cyan-700 uppercase">Active</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Projects</p>
                        <p class="text-4xl font-black text-slate-900 tracking-tighter leading-none">{{ $totalProjectsFromUpdates }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">By Sector</p>
                        <div class="space-y-1.5">
                            @foreach($sectorBreakdown->take(3) as $sector => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-slate-600 truncate max-w-[80px]">{{ $sector ?: 'Unspecified' }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-12 bg-slate-50 rounded-full h-1.5 overflow-hidden border border-slate-100">
                                            <div class="bg-gradient-to-r from-cyan-500 to-[#00ADC5] h-full rounded-full" style="width: {{ $totalProjectsFromUpdates > 0 ? ($count / $totalProjectsFromUpdates) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-[9px] font-black text-slate-900 w-5 text-right">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Planned & Actual Combined -->
            <div class="analytics-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-200 transition-all duration-300 overflow-hidden lg:col-span-2">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/0 to-cyan-50/0 group-hover:from-emerald-50/40 group-hover:to-cyan-50/40 transition-all duration-300"></div>
                <div class="relative z-10 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-[#00ADC5] flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                            <i data-lucide="banknote" class="w-5 h-5"></i>
                        </div>
                        <div class="flex items-center gap-1 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                            <span class="text-[9px] font-black text-emerald-700 uppercase">Revenue</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Planned</p>
                            <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">
                                <span class="text-[9px] font-black text-slate-400 mr-0.5">ETB</span>{{ number_format($totalRevenuePlanned / 1000000, 1) }}<span class="text-[9px] font-black text-slate-400 ml-0.5">M</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual</p>
                            <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">
                                <span class="text-[9px] font-black text-slate-400 mr-0.5">ETB</span>{{ number_format($totalRevenueActual / 1000000, 1) }}<span class="text-[9px] font-black text-slate-400 ml-0.5">M</span>
                            </p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Collection Rate</span>
                            @php
                                $revenueVariance = $totalRevenuePlanned > 0 ? (($totalRevenueActual - $totalRevenuePlanned) / $totalRevenuePlanned) * 100 : 0;
                                $revenueVarianceColor = $revenueVariance >= 0 ? 'text-emerald-600' : 'text-rose-600';
                                $revenueVarianceIcon = $revenueVariance >= 0 ? 'trending-up' : 'trending-down';
                            @endphp
                            <span class="text-[9px] font-black {{ $revenueVarianceColor }} flex items-center gap-1">
                                <i data-lucide="{{ $revenueVarianceIcon }}" class="w-3 h-3"></i>
                                {{ number_format(abs($revenueVariance), 1) }}%
                            </span>
                        </div>
                        <div class="w-full bg-slate-50 rounded-full h-2 overflow-hidden border border-slate-100">
                            <div class="bg-gradient-to-r from-emerald-500 to-[#00ADC5] h-full rounded-full transition-all duration-500" style="width: {{ $totalRevenuePlanned > 0 ? min(100, ($totalRevenueActual / $totalRevenuePlanned) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paid to Date -->
            <div class="analytics-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-slate-200 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-50/0 to-slate-50/0 group-hover:from-slate-50/50 group-hover:to-slate-50/30 transition-all duration-300"></div>
                <div class="relative z-10 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-400 to-slate-500 flex items-center justify-center text-white shadow-lg shadow-slate-400/30">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                        </div>
                        <div class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                            <span class="text-[9px] font-black text-slate-500 uppercase">Pending</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Paid to Date</p>
                        <p class="text-4xl font-black text-slate-300 tracking-tighter leading-none">---</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Coming Soon</p>
                    </div>
                </div>
            </div>

            <!-- Average SPI -->
            <div class="analytics-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-200 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/0 to-emerald-50/0 group-hover:from-emerald-50/40 group-hover:to-emerald-50/20 transition-all duration-300"></div>
                <div class="relative z-10 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-[#00ADC5] flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                            <i data-lucide="gauge" class="w-5 h-5"></i>
                        </div>
                        <div class="flex items-center gap-1
                            @if($sectorWeightedSpi->isEmpty()) bg-slate-50 border-slate-100
                            @elseif($avgWeightedSpi >= 100) bg-emerald-50 border-emerald-100
                            @elseif($avgWeightedSpi >= 90) bg-amber-50 border-amber-100
                            @else bg-rose-50 border-rose-100 @endif
                            px-2 py-1 rounded-lg border">
                            <span class="text-[9px] font-black
                                @if($sectorWeightedSpi->isEmpty()) text-slate-500
                                @elseif($avgWeightedSpi >= 100) text-emerald-700
                                @elseif($avgWeightedSpi >= 90) text-amber-700
                                @else text-rose-700 @endif
                                uppercase">
                                @if($avgWeightedSpi > 0)
                                    @if($avgWeightedSpi >= 100) Ahead
                                    @elseif($avgWeightedSpi >= 90) On Track
                                    @else Behind @endif
                                @else No Data @endif
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Average SPI</p>
                        <p class="text-4xl font-black
                            @if($sectorWeightedSpi->isEmpty()) text-slate-300
                            @elseif($avgWeightedSpi >= 100) text-emerald-600
                            @elseif($avgWeightedSpi >= 90) text-amber-600
                            @else text-rose-600 @endif
                            tracking-tighter leading-none">
                            {{ $avgWeightedSpi > 0 ? number_format($avgWeightedSpi, 2) . '%' : '---' }}
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">By Sector</p>
                        <div class="space-y-1.5">
                            @foreach($sectorWeightedSpi->take(3) as $sector => $spi)
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-slate-600 truncate max-w-[80px]">{{ $sector ?: 'Unspecified' }}</span>
                                    <span class="text-[9px] font-black text-slate-900">{{ number_format($spi, 1) }}</span>
                                </div>
                            @endforeach
                            @if($sectorWeightedSpi->isEmpty())
                                <p class="text-[9px] font-black text-slate-400">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average CPI -->
            <div class="analytics-kpi-card group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-50/0 group-hover:from-blue-50/40 group-hover:to-blue-50/20 transition-all duration-300"></div>
                <div class="relative z-10 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                            <i data-lucide="trending-down" class="w-5 h-5"></i>
                        </div>
                        <div class="flex items-center gap-1
                            @if($sectorWeightedCpi->isEmpty()) bg-slate-50 border-slate-100
                            @elseif($avgWeightedCpi >= 1) bg-emerald-50 border-emerald-100
                            @elseif($avgWeightedCpi >= 0.9) bg-amber-50 border-amber-100
                            @else bg-rose-50 border-rose-100 @endif
                            px-2 py-1 rounded-lg border">
                            <span class="text-[9px] font-black
                                @if($sectorWeightedCpi->isEmpty()) text-slate-500
                                @elseif($avgWeightedCpi >= 1) text-emerald-700
                                @elseif($avgWeightedCpi >= 0.9) text-amber-700
                                @else text-rose-700 @endif
                                uppercase">
                                @if($avgWeightedCpi > 0)
                                    @if($avgWeightedCpi >= 1) Under Budget
                                    @elseif($avgWeightedCpi >= 0.9) On Budget
                                    @else Over Budget @endif
                                @else No Data @endif
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Average CPI</p>
                        <p class="text-4xl font-black
                            @if($sectorWeightedCpi->isEmpty()) text-slate-300
                            @elseif($avgWeightedCpi >= 1) text-emerald-600
                            @elseif($avgWeightedCpi >= 0.9) text-amber-600
                            @else text-rose-600 @endif
                            tracking-tighter leading-none">
                            {{ $avgWeightedCpi > 0 ? number_format($avgWeightedCpi, 2) : '---' }}
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">By Sector</p>
                        <div class="space-y-1.5">
                            @foreach($sectorWeightedCpi->take(3) as $sector => $cpi)
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold text-slate-600 truncate max-w-[80px]">{{ $sector ?: 'Unspecified' }}</span>
                                    <span class="text-[9px] font-black text-slate-900">{{ number_format($cpi, 2) }}</span>
                                </div>
                            @endforeach
                            @if($sectorWeightedCpi->isEmpty())
                                <p class="text-[9px] font-black text-slate-400">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 gap-4">
            <!-- EVM Chart -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-black text-slate-900 tracking-tight">Earned Value Management (EVM)</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Revenue Planned vs Revenue Actual vs Actual Cost by Project</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-600 to-blue-400"></span>
                            <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">Revenue Planned (PV)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gradient-to-r from-emerald-600 to-emerald-400"></span>
                            <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">Revenue Actual (EV)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gradient-to-r from-amber-500 to-amber-400"></span>
                            <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">Cost Actual (AC)</span>
                        </div>
                    </div>
                </div>

                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.25em]">Showing the top 12 projects; the rest are summarized for readability.</p>

                <!-- Summary KPI Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="group relative bg-gradient-to-br from-blue-50 to-blue-50/50 rounded-xl border border-blue-100 p-3 overflow-hidden hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10 flex items-start gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
                                <i data-lucide="target" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[7px] font-black text-slate-500 uppercase tracking-widest">Total Planned Revenue</p>
                                <p class="text-lg font-black text-slate-900 tracking-tighter leading-none mt-1">
                                    <span class="text-[8px] font-black text-slate-400 mr-0.5">ETB</span>{{ number_format($totalRevenuePlanned / 1000000, 1) }}<span class="text-[8px] font-black text-slate-400 ml-0.5">M</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="group relative bg-gradient-to-br from-emerald-50 to-emerald-50/50 rounded-xl border border-emerald-100 p-3 overflow-hidden hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10 flex items-start gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 shrink-0">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[7px] font-black text-slate-500 uppercase tracking-widest">Total Earned Revenue</p>
                                <p class="text-lg font-black text-slate-900 tracking-tighter leading-none mt-1">
                                    <span class="text-[8px] font-black text-slate-400 mr-0.5">ETB</span>{{ number_format($totalRevenueActual / 1000000, 1) }}<span class="text-[8px] font-black text-slate-400 ml-0.5">M</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="group relative bg-gradient-to-br from-amber-50 to-amber-50/50 rounded-xl border border-amber-100 p-3 overflow-hidden hover:shadow-lg hover:border-amber-200 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-amber-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10 flex items-start gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-500 to-amber-400 flex items-center justify-center text-white shadow-md shadow-amber-500/20 shrink-0">
                                <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[7px] font-black text-slate-500 uppercase tracking-widest">Total Actual Cost</p>
                                <p class="text-lg font-black text-slate-900 tracking-tighter leading-none mt-1">
                                    <span class="text-[8px] font-black text-slate-400 mr-0.5">ETB</span>{{ number_format($totalCostActual / 1000000, 1) }}<span class="text-[8px] font-black text-slate-400 ml-0.5">M</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="relative h-[400px] w-full">
                    @if($evmData->count() > 0)
                        <canvas id="evmChart"></canvas>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-slate-400">
                            <i data-lucide="bar-chart-3" class="w-12 h-12 mb-3 text-slate-300"></i>
                            <p class="text-sm font-black text-slate-600 mb-1">No Earned Value Management data available.</p>
                            <p class="text-[9px] font-black text-slate-400">Progress updates will appear here once project revenue and cost information has been submitted.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- S-Curve Performance Analytics -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 tracking-tight">Performance S-Curve Analytics</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Schedule Performance Index (SPI) and Cost Performance Index (CPI) Trend Over Time</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400"></span>
                        <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">SPI</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-600 to-blue-400"></span>
                        <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">CPI</span>
                    </div>
                </div>
            </div>

            @php
                $latestSpi = null;
                $latestCpi = null;
                $spiTrend = 'stable';
                $cpiTrend = 'stable';
                if ($sCurveData->count() > 0) {
                    $allUpdates = $sCurveData->flatten(1)->sortBy('date');
                    $latest = $allUpdates->last();
                    $previous = $allUpdates->count() > 1 ? $allUpdates->slice(-2, 1)->first() : null;
                    
                    if ($latest) {
                        $latestSpi = $latest['spi'];
                        $latestCpi = $latest['cpi'];
                        
                        if ($previous) {
                            if ($latestSpi > $previous['spi']) $spiTrend = 'improving';
                            elseif ($latestSpi < $previous['spi']) $spiTrend = 'declining';
                            
                            if ($latestCpi > $previous['cpi']) $cpiTrend = 'improving';
                            elseif ($latestCpi < $previous['cpi']) $cpiTrend = 'declining';
                        }
                    }
                }
            @endphp

            <!-- Charts -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-slate-500">CPI Trend</p>
                            <h4 class="text-sm font-black text-slate-900">Cost Performance Index</h4>
                        </div>
                        <div class="rounded-2xl border border-blue-100 bg-blue-50 px-3 py-2 min-w-[150px]">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                                    <i data-lucide="trending-down" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Current CPI</p>
                                    <p class="text-lg font-black text-slate-900 tracking-tighter">
                                        {{ $latestCpi !== null ? number_format($latestCpi, 2) : '---' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-[320px] w-full">
                        @if($sCurveData->count() > 0)
                            <canvas id="cpiChart"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-slate-400">
                                <i data-lucide="trending-up" class="w-10 h-10 mb-3 text-slate-300"></i>
                                <p class="text-sm font-black text-slate-600 mb-1">No CPI data available.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-slate-500">SPI Trend</p>
                            <h4 class="text-sm font-black text-slate-900">Schedule Performance Index</h4>
                        </div>
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-3 py-2 min-w-[150px]">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Current SPI</p>
                                    <p class="text-lg font-black text-slate-900 tracking-tighter">
                                        {{ $latestSpi !== null ? number_format($latestSpi, 2) : '---' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-[320px] w-full">
                        @if($sCurveData->count() > 0)
                            <canvas id="spiChart"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-slate-400">
                                <i data-lucide="trending-up" class="w-10 h-10 mb-3 text-slate-300"></i>
                                <p class="text-sm font-black text-slate-600 mb-1">No SPI data available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            </div>
        </div>

        <!-- Planned vs Actual Schedule Performance -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 space-y-4">
            <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 tracking-tight">Planned vs Actual Schedule Performance</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Compare planned progress against actual progress for all active projects</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-600 to-blue-400"></span>
                        <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">Planned Progress</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-r from-emerald-600 to-emerald-400"></span>
                        <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">Actual Progress</span>
                    </div>
                </div>
            </div>

            @php
                $scheduleData = $schedulePerformanceData ?? collect();
                $avgPlanned = $scheduleData->avg('planned') ?? 0;
                $avgActual = $scheduleData->avg('actual') ?? 0;
                $aheadCount = $scheduleData->filter(fn($item) => $item['actual'] >= $item['planned'])->count();
                $behindCount = $scheduleData->filter(fn($item) => $item['actual'] < $item['planned'])->count();
            @endphp

            <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.25em]">Showing the top 12 projects; the rest are summarized for readability.</p>

            <!-- KPI Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2.5">
                <!-- Average Planned Progress -->
                <div class="group relative bg-gradient-to-br from-blue-50 to-blue-50/30 rounded-xl border border-blue-100 p-3 overflow-hidden hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-14 h-14 bg-blue-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10 flex items-start gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
                            <i data-lucide="target" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[7px] font-black text-slate-500 uppercase tracking-widest">Avg Planned Progress</p>
                            <p class="text-xl font-black text-slate-900 tracking-tighter leading-none mt-1">{{ number_format($avgPlanned, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Average Actual Progress -->
                <div class="group relative bg-gradient-to-br from-emerald-50 to-emerald-50/30 rounded-xl border border-emerald-100 p-3 overflow-hidden hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-14 h-14 bg-emerald-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10 flex items-start gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 shrink-0">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[7px] font-black text-slate-500 uppercase tracking-widest">Avg Actual Progress</p>
                            <p class="text-xl font-black text-slate-900 tracking-tighter leading-none mt-1">{{ number_format($avgActual, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Projects Ahead of Schedule -->
                <div class="group relative bg-gradient-to-br from-emerald-50 to-emerald-50/30 rounded-xl border border-emerald-100 p-3 overflow-hidden hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-14 h-14 bg-emerald-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10 flex items-start gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 shrink-0">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[7px] font-black text-slate-500 uppercase tracking-widest">Ahead of Schedule</p>
                            <p class="text-xl font-black text-slate-900 tracking-tighter leading-none mt-1">{{ $aheadCount }}</p>
                            <p class="text-[8px] font-black text-slate-500 mt-1">Projects</p>
                        </div>
                    </div>
                </div>

                <!-- Projects Behind Schedule -->
                <div class="group relative bg-gradient-to-br from-rose-50 to-rose-50/30 rounded-xl border border-rose-100 p-3 overflow-hidden hover:shadow-lg hover:border-rose-200 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-14 h-14 bg-rose-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10 flex items-start gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-rose-500 to-rose-400 flex items-center justify-center text-white shadow-md shadow-rose-500/20 shrink-0">
                            <i data-lucide="trending-down" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[7px] font-black text-slate-500 uppercase tracking-widest">Behind Schedule</p>
                            <p class="text-xl font-black text-slate-900 tracking-tighter leading-none mt-1">{{ $behindCount }}</p>
                            <p class="text-[8px] font-black text-slate-500 mt-1">Projects</p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.25em]">Showing the top 12 projects; the rest are summarized for readability.</p>

            <!-- Chart -->
            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-3">
                <div class="relative h-[360px] w-full">
                    @if($scheduleData->count() > 0)
                        <canvas id="schedulePerformanceChart"></canvas>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-slate-400">
                            <i data-lucide="bar-chart-2" class="w-12 h-12 mb-3 text-slate-300"></i>
                            <p class="text-sm font-black text-slate-600 mb-1">No schedule performance data available.</p>
                            <p class="text-[9px] font-black text-slate-400">Active projects with submitted progress updates will appear here automatically.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
            <!-- Sector Distribution -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 space-y-4">
                <div class="px-1">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Construction Sector Distribution</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Projects by construction sector</p>
                </div>
                <div class="relative h-[280px] w-full flex items-center justify-center">
                    <canvas id="sectorDistributionChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Total</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $sectorValues->sum() }}</p>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Projects</p>
                    </div>
                </div>
                <div id="sectorLegend" class="space-y-2"></div>
            </div>

            <!-- Project Type Performance -->
            <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 space-y-4">
                <div class="flex items-center gap-3 px-1">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Project Type Distribution</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Active projects by delivery type</p>
                    </div>
                </div>
                <div class="h-[300px] w-full">
                    <canvas id="typePerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const maxVisibleChartItems = 12;

            function summarizeChartSeries(items, formatter) {
                const visibleItems = items.slice(0, maxVisibleChartItems);
                const remainingItems = items.slice(maxVisibleChartItems);
                const summarized = visibleItems.map((item, index) => ({
                    ...item,
                    label: formatter(item, index)
                }));

                if (remainingItems.length > 0) {
                    summarized.push({
                        label: `Others (${remainingItems.length})`,
                        isSummary: true,
                        values: remainingItems,
                    });
                }

                return summarized;
            }

            function shortCodeLabel(code, fallback, index) {
                const value = String(code || fallback || `P${index + 1}`).trim();
                return value.length > 14 ? `${value.slice(0, 12)}…` : value;
            }

            document.addEventListener('DOMContentLoaded', function () {
                @if($evmData->count() > 0)
                const evmCtx = document.getElementById('evmChart');
                if (evmCtx) {
                    const evmRows = {!! json_encode($evmData->map(function ($item) {
                        return [
                            'project_code' => $item->project_code,
                            'project_name' => $item->project_name,
                            'pv' => (float) $item->total_pv,
                            'ev' => (float) $item->total_ev,
                            'ac' => (float) $item->total_ac,
                        ];
                    })) !!};
                    const evmSeries = summarizeChartSeries(evmRows, (item, index) => shortCodeLabel(item.project_code, item.project_name, index));
                    const labels = evmSeries.map(item => item.label);
                    const pvData = evmSeries.map(item => item.isSummary ? item.values.reduce((sum, entry) => sum + (Number(entry.pv) || 0), 0) : (Number(item.pv) || 0));
                    const evData = evmSeries.map(item => item.isSummary ? item.values.reduce((sum, entry) => sum + (Number(entry.ev) || 0), 0) : (Number(item.ev) || 0));
                    const acData = evmSeries.map(item => item.isSummary ? item.values.reduce((sum, entry) => sum + (Number(entry.ac) || 0), 0) : (Number(item.ac) || 0));
                    const evmTooltipNames = evmSeries.map(item => item.isSummary ? 'Multiple projects' : (item.project_name || item.project_code || 'Unnamed project'));

                    const formatCurrency = (value) => {
                        if (value >= 1_000_000_000) return 'ETB ' + (value / 1_000_000_000).toFixed(1) + 'B';
                        if (value >= 1_000_000) return 'ETB ' + (value / 1_000_000).toFixed(1) + 'M';
                        if (value >= 1_000) return 'ETB ' + (value / 1_000).toFixed(0) + 'K';
                        return 'ETB ' + value.toFixed(0);
                    };

                    const valueLabelPlugin = {
                        id: 'valueLabels',
                        afterDatasetsDraw(chart) {
                            const progress = chart.$valueLabelProgress !== undefined ? chart.$valueLabelProgress : 1;
                            const {ctx} = chart;
                            ctx.save();
                            ctx.font = 'bold 9px Inter, sans-serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillStyle = '#64748b';

                            chart.data.datasets.forEach((dataset, datasetIndex) => {
                                const meta = chart.getDatasetMeta(datasetIndex);
                                if (!meta || !meta.data) return;
                                meta.data.forEach((bar, index) => {
                                    if (!bar) return;
                                    const finalValue = dataset.data[index];
                                    const animatedValue = finalValue * progress;
                                    const label = formatCurrency(animatedValue);
                                    ctx.fillText(label, bar.x, bar.y - 6);
                                });
                            });
                            ctx.restore();
                        }
                    };

                    const groupSeparatorPlugin = {
                        id: 'groupSeparator',
                        afterDatasetsDraw(chart) {
                            const meta = chart.getDatasetMeta(0);
                            if (!meta || !meta.data || meta.data.length <= 1) return;

                            const groupCenters = meta.data.map(bar => bar.x);
                            const {ctx, chartArea: {top, bottom}} = chart;

                            ctx.save();
                            ctx.strokeStyle = 'rgba(226, 232, 240, 0.5)';
                            ctx.lineWidth = 1;
                            ctx.setLineDash([4, 4]);

                            for (let i = 1; i < groupCenters.length; i++) {
                                const xPos = (groupCenters[i - 1] + groupCenters[i]) / 2;
                                ctx.beginPath();
                                ctx.moveTo(xPos, top);
                                ctx.lineTo(xPos, bottom);
                                ctx.stroke();
                            }
                            ctx.restore();
                        }
                    };

                    const hoverShadowPlugin = {
                        id: 'hoverShadow',
                        beforeDatasetsDraw(chart) {
                            const {ctx, tooltip} = chart;
                            const activeElements = tooltip && tooltip.getActiveElements();
                            if (!activeElements || activeElements.length === 0) return;

                            ctx.save();
                            activeElements.forEach(active => {
                                const meta = chart.getDatasetMeta(active.datasetIndex);
                                if (!meta || !meta.data[active.index]) return;

                                const bar = meta.data[active.index];
                                ctx.shadowColor = 'rgba(0, 0, 0, 0.18)';
                                ctx.shadowBlur = 16;
                                ctx.shadowOffsetX = 0;
                                ctx.shadowOffsetY = 6;
                                ctx.fillStyle = 'rgba(0,0,0,0)';
                                ctx.fillRect(bar.x - bar.width/2, bar.y, bar.width, bar.height);
                            });
                            ctx.restore();
                        }
                    };

                    const evmChart = new Chart(evmCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Revenue Planned (PV)',
                                    data: pvData,
                                    backgroundColor: (context) => {
                                        const chart = context.chart;
                                        const {ctx, chartArea} = chart;
                                        if (!chartArea) return '#2563eb';
                                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.85)');
                                        gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.92)');
                                        gradient.addColorStop(1, 'rgba(96, 165, 250, 0.98)');
                                        return gradient;
                                    },
                                    hoverBackgroundColor: (context) => {
                                        const chart = context.chart;
                                        const {ctx, chartArea} = chart;
                                        if (!chartArea) return '#3b82f6';
                                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.95)');
                                        gradient.addColorStop(0.5, 'rgba(96, 165, 250, 0.95)');
                                        gradient.addColorStop(1, 'rgba(147, 197, 253, 1)');
                                        return gradient;
                                    },
                                    borderRadius: [8, 8, 0, 0],
                                    borderSkipped: false,
                                    borderWidth: 1,
                                    borderColor: 'rgba(255, 255, 255, 0.25)',
                                    barPercentage: 0.55,
                                    categoryPercentage: 0.75,
                                },
                                {
                                    label: 'Revenue Actual (EV)',
                                    data: evData,
                                    backgroundColor: (context) => {
                                        const chart = context.chart;
                                        const {ctx, chartArea} = chart;
                                        if (!chartArea) return '#059669';
                                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                        gradient.addColorStop(0, 'rgba(5, 150, 105, 0.85)');
                                        gradient.addColorStop(0.5, 'rgba(16, 185, 129, 0.92)');
                                        gradient.addColorStop(1, 'rgba(52, 211, 153, 0.98)');
                                        return gradient;
                                    },
                                    hoverBackgroundColor: (context) => {
                                        const chart = context.chart;
                                        const {ctx, chartArea} = chart;
                                        if (!chartArea) return '#10b981';
                                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.95)');
                                        gradient.addColorStop(0.5, 'rgba(52, 211, 153, 0.95)');
                                        gradient.addColorStop(1, 'rgba(110, 231, 183, 1)');
                                        return gradient;
                                    },
                                    borderRadius: [8, 8, 0, 0],
                                    borderSkipped: false,
                                    borderWidth: 1,
                                    borderColor: 'rgba(255, 255, 255, 0.25)',
                                    barPercentage: 0.55,
                                    categoryPercentage: 0.75,
                                },
                                {
                                    label: 'Cost Actual (AC)',
                                    data: acData,
                                    backgroundColor: (context) => {
                                        const chart = context.chart;
                                        const {ctx, chartArea} = chart;
                                        if (!chartArea) return '#d97706';
                                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                        gradient.addColorStop(0, 'rgba(217, 119, 6, 0.85)');
                                        gradient.addColorStop(0.5, 'rgba(245, 158, 11, 0.92)');
                                        gradient.addColorStop(1, 'rgba(252, 211, 77, 0.98)');
                                        return gradient;
                                    },
                                    hoverBackgroundColor: (context) => {
                                        const chart = context.chart;
                                        const {ctx, chartArea} = chart;
                                        if (!chartArea) return '#f59e0b';
                                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                        gradient.addColorStop(0, 'rgba(245, 158, 11, 0.95)');
                                        gradient.addColorStop(0.5, 'rgba(252, 211, 77, 0.95)');
                                        gradient.addColorStop(1, 'rgba(253, 230, 138, 1)');
                                        return gradient;
                                    },
                                    borderRadius: [8, 8, 0, 0],
                                    borderSkipped: false,
                                    borderWidth: 1,
                                    borderColor: 'rgba(255, 255, 255, 0.25)',
                                    barPercentage: 0.55,
                                    categoryPercentage: 0.75,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                    titleColor: '#0f172a',
                                    bodyColor: '#475569',
                                    borderColor: '#e2e8f0',
                                    borderWidth: 1,
                                    cornerRadius: 12,
                                    padding: 16,
                                    boxPadding: 4,
                                    usePointStyle: true,
                                    callbacks: {
                                        title: function(context) {
                                            const idx = context[0].dataIndex;
                                            return evmTooltipNames[idx] + ' (' + context[0].label + ')';
                                        },
                                        label: function(context) {
                                            const value = context.raw;
                                            return context.dataset.label + ': ' + formatCurrency(value);
                                        },
                                        afterBody: function(context) {
                                            const idx = context[0].dataIndex;
                                            const pv = pvData[idx];
                                            const ev = evData[idx];
                                            const ac = acData[idx];
                                            const sv = ev - pv;
                                            const cv = ev - ac;
                                            const spi = pv > 0 ? (ev / pv) : 0;
                                            const cpi = ac > 0 ? (ev / ac) : 0;
                                            return [
                                                '',
                                                'Schedule Variance (SV): ' + formatCurrency(sv),
                                                'Cost Variance (CV): ' + formatCurrency(cv),
                                                'SPI: ' + spi.toFixed(2),
                                                'CPI: ' + cpi.toFixed(2)
                                            ];
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false, drawBorder: false },
                                    ticks: {
                                        font: { weight: 'bold', size: 10 },
                                        color: '#64748b',
                                        maxRotation: 35,
                                        minRotation: 0,
                                        maxTicksLimit: 12
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { color: '#f1f5f9', drawBorder: false },
                                    ticks: {
                                        font: { weight: 'bold', size: 9 },
                                        color: '#94a3b8',
                                        callback: function(value) {
                                            if (value >= 1_000_000_000) return (value / 1_000_000_000).toFixed(1) + 'B';
                                            if (value >= 1_000_000) return (value / 1_000_000).toFixed(1) + 'M';
                                            if (value >= 1_000) return (value / 1_000).toFixed(0) + 'K';
                                            return value;
                                        }
                                    }
                                }
                            },
                            animation: {
                                duration: 1000,
                                easing: 'easeInOutQuart',
                                onProgress: function(animation) {
                                    evmChart.$valueLabelProgress = animation.progress;
                                }
                            },
                            layout: { padding: { top: 24 } }
                        },
                        plugins: [valueLabelPlugin, groupSeparatorPlugin, hoverShadowPlugin]
                    });
                }
                @endif

                const sCurveData = {!! json_encode($sCurveData) !!};
                const allDates = [];
                const spiValues = [];
                const cpiValues = [];
                const projectCodes = [];

                if (sCurveData && Object.keys(sCurveData).length > 0) {
                    Object.values(sCurveData).forEach(updates => {
                        updates.forEach(update => {
                            allDates.push(update.date);
                            spiValues.push(update.spi);
                            cpiValues.push(update.cpi);
                            projectCodes.push(update.project_code);
                        });
                    });
                }

                function roundToNice(value, roundUp = false) {
                    if (value <= 0) return 0;
                    const exp = Math.floor(Math.log10(value));
                    const base = Math.pow(10, exp);
                    const normalized = value / base;
                    let nice;
                    if (roundUp) {
                        if (normalized <= 1) nice = 1;
                        else if (normalized <= 2) nice = 2;
                        else if (normalized <= 2.5) nice = 2.5;
                        else if (normalized <= 5) nice = 5;
                        else nice = 10;
                    } else {
                        if (normalized < 1.5) nice = 1;
                        else if (normalized < 2.5) nice = 2;
                        else if (normalized < 3.5) nice = 3;
                        else if (normalized < 5.5) nice = 5;
                        else nice = 10;
                    }
                    return nice * base;
                }

                function buildLineChart(ctx, labels, values, color, label, target, ySuffix = '') {
                    if (!ctx || values.length === 0) return;

                    const dataMin = Math.min(...values);
                    const dataMax = Math.max(...values);
                    const padding = (dataMax - dataMin) * 0.15;
                    const rawMin = dataMin - padding;
                    const rawMax = dataMax + padding;
                    const yMin = target === 100 ? 0 : Math.max(0, roundToNice(rawMin, false));
                    const yMax = target === 100 ? Math.max(100, roundToNice(rawMax, true)) : roundToNice(rawMax, true);
                    const finalMax = Math.max(yMax, yMin + (target === 100 ? 20 : 1.0));

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                label,
                                data: values,
                                borderColor: color,
                                backgroundColor: (context) => {
                                    const chart = context.chart;
                                    const {ctx, chartArea} = chart;
                                    if (!chartArea) return `${color}1A`;
                                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                    gradient.addColorStop(0, `${color}00`);
                                    gradient.addColorStop(1, `${color}26`);
                                    return gradient;
                                },
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 8,
                                pointBackgroundColor: color,
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                    titleColor: '#0f172a',
                                    bodyColor: '#475569',
                                    borderColor: '#e2e8f0',
                                    borderWidth: 1,
                                    cornerRadius: 12,
                                    padding: 16,
                                    boxPadding: 4,
                                    usePointStyle: true,
                                    callbacks: {
                                        title: function(context) {
                                            const idx = context[0].dataIndex;
                                            return 'Project: ' + (projectCodes[idx] || 'N/A');
                                        },
                                        label: function(context) {
                                            const value = context.raw;
                                            return context.dataset.label + ': ' + value.toFixed(2) + ySuffix;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false, drawBorder: false },
                                    ticks: {
                                        font: { weight: 'bold', size: 10 },
                                        color: '#64748b',
                                        maxRotation: 45,
                                        minRotation: 0
                                    }
                                },
                                y: {
                                    min: yMin,
                                    max: finalMax,
                                    grid: { color: '#f1f5f9', drawBorder: false },
                                    ticks: {
                                        font: { weight: 'bold', size: 9 },
                                        color: '#94a3b8',
                                        callback: function(value) {
                                            return value + ySuffix;
                                        }
                                    }
                                }
                            },
                            animation: { duration: 1500, easing: 'easeInOutQuart' }
                        },
                        plugins: [{
                            id: 'targetLine',
                            beforeDraw: (chart) => {
                                const {ctx, chartArea: {left, right}, scales: {y}} = chart;
                                const yPos = y.getPixelForValue(target);
                                ctx.save();
                                ctx.strokeStyle = '#94a3b8';
                                ctx.lineWidth = 2;
                                ctx.setLineDash([8, 4]);
                                ctx.beginPath();
                                ctx.moveTo(left, yPos);
                                ctx.lineTo(right, yPos);
                                ctx.stroke();
                                ctx.restore();
                            }
                        }]
                    });
                }

                const cpiChartCtx = document.getElementById('cpiChart');
                if (cpiChartCtx) {
                    buildLineChart(cpiChartCtx, allDates, cpiValues, '#2563eb', 'CPI', 1.0);
                }

                const spiChartCtx = document.getElementById('spiChart');
                if (spiChartCtx) {
                    buildLineChart(spiChartCtx, allDates, spiValues, '#10b981', 'SPI', 100, '%');
                }

                const sectorCtx = document.getElementById('sectorDistributionChart');
                if (sectorCtx) {
                    const sectorLabels = {!! json_encode($sectorValues->keys()) !!};
                    const sectorData = {!! json_encode($sectorValues->values()) !!};
                    const sectorColors = ['#00ADC5', '#6366f1', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];
                    const sectorTotal = sectorData.reduce((a, b) => a + b, 0);

                    new Chart(sectorCtx, {
                        type: 'doughnut',
                        data: {
                            labels: sectorLabels,
                            datasets: [{
                                data: sectorData,
                                backgroundColor: sectorColors.slice(0, sectorLabels.length),
                                borderColor: '#ffffff',
                                borderWidth: 3,
                                hoverBorderColor: '#ffffff',
                                hoverBorderWidth: 4,
                                hoverOffset: 12,
                                borderRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            circumference: 360,
                            rotation: -90,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                    titleColor: '#0f172a',
                                    bodyColor: '#475569',
                                    borderColor: '#e2e8f0',
                                    borderWidth: 1,
                                    cornerRadius: 12,
                                    padding: 16,
                                    callbacks: {
                                        label: function(context) {
                                            const value = context.raw;
                                            const percentage = sectorTotal > 0 ? ((value / sectorTotal) * 100).toFixed(1) : 0;
                                            return ' ' + context.label + ': ' + value + ' (' + percentage + '%)';
                                        }
                                    }
                                }
                            },
                            animation: {
                                animateRotate: true,
                                animateScale: true,
                                duration: 1200,
                                easing: 'easeInOutQuart'
                            }
                        }
                    });

                    // Planned vs Actual Schedule Performance - Modern Stacked Bar Chart
                    const scheduleCtx = document.getElementById('schedulePerformanceChart');
                    if (scheduleCtx) {
                        const scheduleData = {!! json_encode($schedulePerformanceData ?? collect()) !!};
                        const scheduleSeries = summarizeChartSeries(scheduleData, (item, index) => shortCodeLabel(item.project_code, item.project_name, index));
                        const projectLabels = scheduleSeries.map(item => item.label);
                        const plannedValues = scheduleSeries.map(item => item.isSummary ? item.values.reduce((sum, entry) => sum + (parseFloat(entry.planned) || 0), 0) : (parseFloat(item.planned) || 0));
                        const actualValues = scheduleSeries.map(item => item.isSummary ? item.values.reduce((sum, entry) => sum + (parseFloat(entry.actual) || 0), 0) : (parseFloat(item.actual) || 0));
                        const variances = scheduleSeries.map(item => item.isSummary ? item.values.reduce((sum, entry) => sum + (parseFloat(entry.variance) || 0), 0) / Math.max(1, item.values.length) : (parseFloat(item.variance) || 0));

                        new Chart(scheduleCtx, {
                            type: 'bar',
                            data: {
                                labels: projectLabels,
                                datasets: [
                                    {
                                        label: 'Actual Progress',
                                        data: actualValues,
                                        stack: 'schedule',
                                        barPercentage: 0.85,
                                        categoryPercentage: 0.8,
                                        maxBarThickness: 26,
                                        backgroundColor: (context) => {
                                            const chart = context.chart;
                                            const {ctx, chartArea} = chart;
                                            if (!chartArea) return '#10b981';
                                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.82)');
                                            gradient.addColorStop(1, 'rgba(52, 211, 153, 0.95)');
                                            return gradient;
                                        },
                                        borderColor: '#0f9d6f',
                                        borderWidth: 1,
                                        borderRadius: 8,
                                        borderSkipped: false,
                                    },
                                    {
                                        label: 'Remaining Planned',
                                        data: plannedValues.map((p, i) => Math.max(0, p - actualValues[i])),
                                        stack: 'schedule',
                                        barPercentage: 0.85,
                                        categoryPercentage: 0.8,
                                        maxBarThickness: 26,
                                        backgroundColor: (context) => {
                                            const chart = context.chart;
                                            const {ctx, chartArea} = chart;
                                            if (!chartArea) return '#ef4444';
                                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                            gradient.addColorStop(0, 'rgba(239, 68, 68, 0.68)');
                                            gradient.addColorStop(1, 'rgba(248, 113, 113, 0.8)');
                                            return gradient;
                                        },
                                        borderColor: '#dc2626',
                                        borderWidth: 1,
                                        borderRadius: 8,
                                        borderSkipped: false,
                                    }
                                ]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                layout: {
                                    padding: { top: 8, right: 16, left: 8, bottom: 8 }
                                },
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        align: 'end',
                                        labels: {
                                            boxWidth: 12,
                                            boxHeight: 12,
                                            color: '#64748b',
                                            font: { family: 'Inter, sans-serif', size: 10, weight: '700' },
                                            padding: 14
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(255, 255, 255, 0.97)',
                                        titleColor: '#0f172a',
                                        bodyColor: '#475569',
                                        borderColor: '#e2e8f0',
                                        borderWidth: 1,
                                        cornerRadius: 12,
                                        padding: 14,
                                        boxPadding: 4,
                                        usePointStyle: true,
                                        callbacks: {
                                            title: function(context) {
                                                const idx = context[0].dataIndex;
                                                return 'Project: ' + (projectLabels[idx] || 'N/A');
                                            },
                                            label: function(context) {
                                                const value = context.raw;
                                                return context.dataset.label + ': ' + value.toFixed(1) + '%';
                                            },
                                            afterBody: function(context) {
                                                const idx = context[0].dataIndex;
                                                const variance = variances[idx];
                                                const varianceLabel = variance >= 0 ? '+' + variance.toFixed(1) + '%' : variance.toFixed(1) + '%';
                                                const spi = plannedValues[idx] > 0 ? (actualValues[idx] / plannedValues[idx]) : 0;
                                                const scheduleStatus = spi >= 1.0 ? 'Ahead of Schedule' : (spi >= 0.9 ? 'On Schedule' : 'Behind Schedule');
                                                return [
                                                    '',
                                                    'Variance: ' + varianceLabel,
                                                    'SPI: ' + spi.toFixed(2),
                                                    'Status: ' + scheduleStatus
                                                ];
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                        beginAtZero: true,
                                        max: 100,
                                        grid: { color: '#f1f5f9', drawBorder: false },
                                        ticks: {
                                            font: { weight: 'bold', size: 9 },
                                            color: '#94a3b8',
                                            maxTicksLimit: 12,
                                            callback: function(value) { return value + '%'; }
                                        }
                                    },
                                    y: {
                                        stacked: true,
                                        grid: { display: false, drawBorder: false },
                                        ticks: {
                                            font: { weight: 'bold', size: 9 },
                                            color: '#64748b',
                                            maxTicksLimit: 12
                                        }
                                    }
                                },
                                animation: {
                                    duration: 1500,
                                    easing: 'easeInOutQuart'
                                }
                            }
                        });
                    }

                    // Custom Legend
                    const legendContainer = document.getElementById('sectorLegend');
                    if (legendContainer && sectorTotal > 0) {
                        legendContainer.innerHTML = sectorLabels.map((label, i) => {
                            const value = sectorData[i];
                            const percentage = ((value / sectorTotal) * 100).toFixed(1);
                            const color = sectorColors[i % sectorColors.length];
                            return `
                                <div class="group flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition-all cursor-pointer border border-transparent hover:border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-3 h-3 rounded-sm flex-shrink-0 shadow-sm" style="background-color: ${color};"></span>
                                        <span class="text-[9px] font-black text-slate-700 uppercase tracking-wider">${label}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-black text-slate-900">${value}</span>
                                        <span class="text-[9px] font-black text-slate-400 w-10 text-right">${percentage}%</span>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    }
                }

                // Module Performance Chart - Modern Radar Chart
                const typeCtx = document.getElementById('typePerformanceChart');
                if (typeCtx) {
                    const typeLabels = {!! json_encode($projectTypes->keys()) !!};
                    const typeData = {!! json_encode($projectTypes->values()) !!};
                    const typeColors = ['#00ADC5', '#6366f1', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];

                    new Chart(typeCtx, {
                        type: 'radar',
                        data: {
                            labels: typeLabels,
                            datasets: [{
                                label: 'Projects',
                                data: typeData,
                                backgroundColor: 'rgba(0, 173, 197, 0.15)',
                                borderColor: '#00ADC5',
                                borderWidth: 3,
                                pointBackgroundColor: typeLabels.map((_, i) => typeColors[i % typeColors.length]),
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                fill: true,
                                tension: 0.4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                    titleColor: '#0f172a',
                                    bodyColor: '#475569',
                                    borderColor: '#e2e8f0',
                                    borderWidth: 1,
                                    cornerRadius: 12,
                                    padding: 16,
                                    callbacks: {
                                        label: function(context) {
                                            return 'Projects: ' + context.raw;
                                        }
                                    }
                                }
                            },
                            scales: {
                                r: {
                                    beginAtZero: true,
                                    max: Math.max(...typeData, 5),
                                    grid: { color: '#f1f5f9' },
                                    angleLines: { color: '#f1f5f9' },
                                    pointLabels: {
                                        font: { weight: 'bold', size: 10 },
                                        color: '#64748b'
                                    },
                                    ticks: {
                                        font: { weight: 'bold', size: 9 },
                                        color: '#94a3b8',
                                        stepSize: 1,
                                        backdropColor: 'transparent'
                                    }
                                }
                            },
                            animation: {
                                duration: 1500,
                                easing: 'easeInOutQuart'
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
