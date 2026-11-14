<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        @php
            $analyticsAdvancedFiltersActive = request()->anyFilled(['project_id', 'sector']);
            $revenueVariance = $totalRevenuePlanned > 0
                ? (($totalRevenueActual - $totalRevenuePlanned) / $totalRevenuePlanned) * 100
                : 0;
            $revenueVarianceColor = $revenueVariance >= 0 ? 'text-emerald-600' : 'text-rose-600';
            $revenueVarianceIcon = $revenueVariance >= 0 ? 'trending-up' : 'trending-down';
        @endphp
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
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Project Overview & Financial Balance</p>
                </div>
            </div>
            <form action="{{ route('admin.projects.analytics') }}" method="GET" class="admin-filter-grid gap-2">
                <div class="flex flex-col gap-1">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Data Date</label>
                    <div class="flex items-center gap-2 bg-white border border-slate-100 rounded-xl px-3 py-2 shadow-sm">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                        <input type="date" name="data_date" value="{{ request('data_date') ?: ($latestDataDate ? \Carbon\Carbon::parse($latestDataDate)->format('Y-m-d') : '') }}" onchange="this.form.submit()"
                            class="bg-transparent border-none text-[9px] font-black text-slate-600 uppercase tracking-widest focus:outline-none focus:ring-0 w-[160px]">
                    </div>
                </div>

                @include('admin.partials.project-organization-filters')

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" aria-controls="analyticsAdvancedFilters" aria-expanded="{{ $analyticsAdvancedFiltersActive ? 'true' : 'false' }}" onclick="toggleFilterPanel(this, 'analyticsAdvancedFilters')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[9px] font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-[#00515f]">
                        <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i>More filters
                        <i data-lucide="chevron-down" class="filter-panel-chevron h-3.5 w-3.5 transition-transform {{ $analyticsAdvancedFiltersActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div class="flex items-center gap-2">
                        @if(request()->anyFilled(['data_date', 'project_id', 'sector', 'business_unit_id', 'sector_id']))
                            <a href="{{ route('admin.projects.analytics') }}"
                                class="flex items-center justify-center w-10 h-10 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all active:scale-90 shadow-sm"
                                title="Clear Filters">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div id="analyticsAdvancedFilters" class="admin-filter-advanced-panel {{ $analyticsAdvancedFiltersActive ? '' : 'hidden' }} rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                    <div class="admin-filter-advanced-grid gap-2">
                        <div class="flex flex-col gap-1">
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Project</label>
                            <div class="relative">
                                <select name="project_id" onchange="this.form.submit()"
                                    class="w-full appearance-none rounded-xl border-2 border-transparent bg-white px-4 py-2.5 pr-10 text-[9px] font-black uppercase tracking-widest text-slate-500 transition-all hover:bg-slate-100 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                    <option value="">All Projects</option>
                                    @foreach($allProjectsWithUpdates as $project)
                                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->project_code }}</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Project Sector</label>
                            <div class="relative">
                                <select name="sector" onchange="this.form.submit()"
                                    class="w-full appearance-none rounded-xl border-2 border-transparent bg-white px-4 py-2.5 pr-10 text-[9px] font-black uppercase tracking-widest text-slate-500 transition-all hover:bg-slate-100 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                    <option value="">All Sectors</option>
                                    <option value="Building" {{ request('sector') == 'Building' ? 'selected' : '' }}>Building</option>
                                    <option value="Fit-Out" {{ request('sector') == 'Fit-Out' ? 'selected' : '' }}>Fit-Out</option>
                                    <option value="Infrastructure" {{ request('sector') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                    <option value="Water & Energy" {{ request('sector') == 'Water & Energy' ? 'selected' : '' }}>Water & Energy</option>
                                </select>
                                <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @include('admin.partials.filter-panel-toggle-script')

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
                    </div>
                    <div class="mb-3">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Average SPI</p>
                        <p class="text-4xl font-black
                            @if($sectorWeightedSpi->isEmpty()) text-slate-300
                            @elseif($avgWeightedSpi >= 80) text-emerald-600
                            @elseif($avgWeightedSpi >= 70) text-[#fcba03]
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
                        @if($avgWeightedCpi > 1)
                            <div class="flex items-center gap-1 bg-emerald-50 border-emerald-100 px-2 py-1 rounded-lg border">
                                <span class="text-[9px] font-black text-emerald-700 uppercase">Under Budget</span>
                            </div>
                        @elseif($avgWeightedCpi > 0 && $avgWeightedCpi < 1)
                            
                        @elseif($avgWeightedCpi > 0)
                            <div class="flex items-center gap-1 bg-[#fcba03]/10 border-[#fcba03]/20 px-2 py-1 rounded-lg border">
                                <span class="text-[9px] font-black text-[#fcba03] uppercase">On Budget</span>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Average CPI</p>
                        <p class="text-4xl font-black
                            @if($sectorWeightedCpi->isEmpty()) text-slate-300
                            @elseif($avgWeightedCpi > 1) text-emerald-600
                            @elseif($avgWeightedCpi < 1) text-rose-600
                            @else text-[#fcba03] @endif
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

        <!-- Sector Distribution & Project Type Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
            <!-- Sector Distribution -->
            <div class="relative bg-white rounded-3xl border border-slate-100 shadow-sm shadow-slate-200/50 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-2xl bg-cyan-50 flex items-center justify-center text-[#00ADC5] shadow-md shadow-cyan-200/40">
                            <i data-lucide="pie-chart" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 tracking-tight">Project Sector Distribution</h3>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Projects by sector</p>
                        </div>
                    </div>
                    <div class="relative h-[280px] w-full flex items-center justify-center">
                        <canvas id="sectorDistributionChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Total</p>
                            <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $sectorValues->sum() }}</p>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Projects</p>
                        </div>
                    </div>
                    <div id="sectorLegend" class="mt-5 space-y-2"></div>
                </div>
            </div>

            <!-- Project Type Distribution -->
            <div class="lg:col-span-2 relative bg-white rounded-3xl border border-slate-100 shadow-sm shadow-slate-200/50 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-md shadow-emerald-200/40">
                            <i data-lucide="zap" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 tracking-tight">Project Type Distribution</h3>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Active projects by delivery type</p>
                        </div>
                    </div>
                    <div class="h-[320px] w-full">
                        <canvas id="typePerformanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 gap-4">
            <!-- EVM Chart -->
            <div class="relative bg-white rounded-3xl border border-slate-100 shadow-sm shadow-slate-200/50 overflow-hidden">
                <div class="p-6 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-400/30">
                                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Earned Value Management (EVM)</h3>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Revenue Planned vs Revenue Actual vs Actual Cost by Project</p>
                            </div>
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

                    <div class="border-t border-slate-50 pt-4">
                        <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.25em] mb-4">All projects included.</p>

                        <!-- Summary KPI Cards -->
                        <div class="grid grid-cols-4 gap-3 mb-6">
                            <div class="relative p-4 rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-blue-50/50 dark:from-blue-950 dark:to-slate-900 dark:border-blue-800 overflow-hidden group hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-600 transition-all duration-300">
                                <div class="absolute top-0 right-0 w-14 h-14 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                                <div class="relative z-10 flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
                                        <i data-lucide="target" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[7px] font-black text-slate-500 dark:text-blue-200 uppercase tracking-widest">Total Planned Revenue</p>
                                        <p class="text-lg font-black text-slate-900 dark:text-white tracking-tighter leading-none mt-1">
                                            <span class="text-[8px] font-black text-slate-400 dark:text-blue-300 mr-0.5">ETB</span>{{ number_format($totalRevenuePlanned / 1000000, 1) }}<span class="text-[8px] font-black text-slate-400 dark:text-blue-300 ml-0.5">M</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative p-4 rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-emerald-50/50 dark:from-emerald-950 dark:to-slate-900 dark:border-emerald-800 overflow-hidden group hover:shadow-lg hover:border-emerald-200 dark:hover:border-emerald-600 transition-all duration-300">
                                <div class="absolute top-0 right-0 w-14 h-14 bg-emerald-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                                <div class="relative z-10 flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 shrink-0">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[7px] font-black text-slate-500 dark:text-emerald-200 uppercase tracking-widest">Total Earned Revenue</p>
                                        <p class="text-lg font-black text-slate-900 dark:text-white tracking-tighter leading-none mt-1">
                                            <span class="text-[8px] font-black text-slate-400 dark:text-emerald-300 mr-0.5">ETB</span>{{ number_format($totalRevenueActual / 1000000, 1) }}<span class="text-[8px] font-black text-slate-400 dark:text-emerald-300 ml-0.5">M</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative p-4 rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 to-amber-50/50 dark:from-amber-950 dark:to-slate-900 dark:border-amber-800 overflow-hidden group hover:shadow-lg hover:border-amber-200 dark:hover:border-amber-600 transition-all duration-300">
                                <div class="absolute top-0 right-0 w-14 h-14 bg-amber-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                                <div class="relative z-10 flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-amber-500 to-amber-400 flex items-center justify-center text-white shadow-md shadow-amber-500/20 shrink-0">
                                        <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[7px] font-black text-slate-500 dark:text-amber-200 uppercase tracking-widest">Total Actual Cost</p>
                                        <p class="text-lg font-black text-slate-900 dark:text-white tracking-tighter leading-none mt-1">
                                            <span class="text-[8px] font-black text-slate-400 dark:text-amber-300 mr-0.5">ETB</span>{{ number_format($totalCostActual / 1000000, 1) }}<span class="text-[8px] font-black text-slate-400 dark:text-amber-300 ml-0.5">M</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative p-4 rounded-2xl border border-rose-100 bg-gradient-to-br from-rose-50 to-rose-50/50 dark:from-rose-950 dark:to-slate-900 dark:border-rose-800 overflow-hidden group hover:shadow-lg hover:border-rose-200 dark:hover:border-rose-600 transition-all duration-300">
                                <div class="absolute top-0 right-0 w-14 h-14 bg-rose-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                                <div class="relative z-10 flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-rose-500 to-rose-400 flex items-center justify-center text-white shadow-md shadow-rose-500/20 shrink-0">
                                        <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[7px] font-black text-slate-500 dark:text-rose-200 uppercase tracking-widest">Total Planned Cost</p>
                                        <p class="text-lg font-black text-slate-900 dark:text-white tracking-tighter leading-none mt-1">
                                            <span class="text-[8px] font-black text-slate-400 dark:text-rose-300 mr-0.5">ETB</span>{{ number_format($totalCostPlanned / 1000000, 1) }}<span class="text-[8px] font-black text-slate-400 dark:text-rose-300 ml-0.5">M</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="relative h-[400px] w-full">
                        @if($evmData->count() > 0)
                            <canvas id="evmChart"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-slate-400 rounded-2xl border-2 border-dashed border-slate-100 bg-slate-50">
                                <i data-lucide="bar-chart-3" class="w-12 h-12 mb-3 text-slate-300"></i>
                                <p class="text-sm font-black text-slate-600 mb-1">No Earned Value Management data available.</p>
                                <p class="text-[9px] font-black text-slate-400">Progress updates will appear here once project revenue and cost information has been submitted.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Planned vs Actual Schedule Performance -->
        <div class="relative bg-white rounded-3xl border border-slate-100 shadow-sm shadow-slate-200/50 overflow-hidden">
            <div class="p-6 space-y-5">
                <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-400/30">
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

                <div class="border-t border-slate-50 pt-4">
                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.25em] mb-4">All projects included.</p>

                    <!-- Chart -->
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-3 relative h-[360px] w-full">
                        @php
                            $scheduleData = collect($schedulePerformanceData ?? []);
                        @endphp
                        @if($scheduleData->count() > 0)
                            <canvas id="schedulePerformanceChart"></canvas>
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-slate-400 rounded-xl border-2 border-dashed border-slate-100 bg-slate-50">
                                <i data-lucide="bar-chart-2" class="w-12 h-12 mb-3 text-slate-300"></i>
                                <p class="text-sm font-black text-slate-600 mb-1">No schedule performance data available.</p>
                                <p class="text-[9px] font-black text-slate-400">Active projects with submitted progress updates will appear here automatically.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- S-Curve Performance Analytics -->
        <div class="relative bg-white rounded-3xl border border-slate-100 shadow-sm shadow-slate-200/50 overflow-hidden">
            <div class="p-6 space-y-5">
                @php
                    $latestSpi = null;
                    $latestCpi = null;
                    $spiTrend = 'stable';
                    $cpiTrend = 'stable';
                    if ($sCurveData->count() > 0) {
                        $allUpdates = $sCurveData->sortBy('date');
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
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-emerald-400/30">
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

                <div class="border-t border-slate-50 pt-4">
                    <!-- KPI trend indicators -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                        <div class="p-3 rounded-2xl border border-blue-100 bg-blue-50 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                                <i data-lucide="trending-down" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Current CPI</p>
                                <p class="text-sm font-black text-slate-900 tracking-tighter">
                                    {{ $latestCpi !== null ? number_format($latestCpi, 2) : '---' }}
                                </p>
                            </div>
                        </div>
                        <div class="p-3 rounded-2xl border border-emerald-100 bg-emerald-50 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                                <i data-lucide="trending-up" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Current SPI</p>
                                <p class="text-sm font-black text-slate-900 tracking-tighter">
                                    {{ $latestSpi !== null ? number_format($latestSpi, 2) : '---' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
                        <div class="relative h-[320px] w-full flex flex-col">
                            <p class="text-[8px] font-black uppercase tracking-widest text-slate-500 mb-2">CPI Trend</p>
                            <div class="flex-1 relative h-[260px] w-full">
                                @if($sCurveData->count() > 0)
                                    <canvas id="cpiChart"></canvas>
                                @else
                                    <div class="flex flex-col items-center justify-center h-full text-slate-400 rounded-xl border-2 border-dashed border-slate-100 bg-slate-50">
                                        <i data-lucide="trending-up" class="w-10 h-10 mb-3 text-slate-300"></i>
                                        <p class="text-sm font-black text-slate-600 mb-1">No CPI data available.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="relative h-[320px] w-full flex flex-col">
                            <p class="text-[8px] font-black uppercase tracking-widest text-slate-500 mb-2">SPI Trend</p>
                            <div class="flex-1 relative h-[260px] w-full">
                                @if($sCurveData->count() > 0)
                                    <canvas id="spiChart"></canvas>
                                @else
                                    <div class="flex flex-col items-center justify-center h-full text-slate-400 rounded-xl border-2 border-dashed border-slate-100 bg-slate-50">
                                        <i data-lucide="trending-up" class="w-10 h-10 mb-3 text-slate-300"></i>
                                        <p class="text-sm font-black text-slate-600 mb-1">No SPI data available.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     @push('scripts')
         <script>
             document.addEventListener('DOMContentLoaded', function () {
                 const chartData = {
                     evmData: {!! json_encode($evmData->map(function ($item) {
                         return [
                             'project_code' => $item->project_code,
                             'project_name' => $item->project_name,
                             'pv' => (float) $item->total_pv,
                             'ev' => (float) $item->total_ev,
                             'ac' => (float) $item->total_ac,
                             'cost_planned' => (float) $item->total_cost_planned,
                         ];
                     })->values()) !!},
                     sCurveData: {!! json_encode($sCurveData->all()) !!},
                     sectorValues: {!! json_encode($sectorValues->toArray()) !!},
                     schedulePerformanceData: {!! json_encode($schedulePerformanceData ?? []) !!},
                     projectTypes: {!! json_encode($projectTypes->toArray()) !!}
                 };

                 function initCharts() {
                     if (window.initializeAnalyticsCharts) {
                         window.initializeAnalyticsCharts(chartData);
                     } else {
                         console.error('Analytics chart initializer was not loaded');
                     }
                 }

                 if (window.initializeAnalyticsCharts) {
                     initCharts();
                 } else {
                     let attempts = 0;
                     const maxAttempts = 30;
                     const interval = setInterval(function () {
                         attempts++;
                         if (window.initializeAnalyticsCharts) {
                             clearInterval(interval);
                             initCharts();
                         } else if (attempts >= maxAttempts) {
                             clearInterval(interval);
                             console.error('Analytics chart initializer was not loaded after waiting');
                         }
                     }, 100);
                 }
             });
         </script>
     @endpush
</x-app-layout>
