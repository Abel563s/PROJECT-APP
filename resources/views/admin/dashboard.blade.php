<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        <style>
            .dashboard-kpi-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .dashboard-kpi-card:hover {
                transform: translateY(-2px);
            }
            .dashboard-kpi-card .progress-bar {
                transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            }
            @keyframes countUp {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .dashboard-kpi-card h3 {
                animation: countUp 0.6s ease-out;
            }
            .kpi-card-total { border: 1px solid #cbd5e1 !important; background-color: rgba(224, 242, 254, 0.4) !important; }
            .kpi-card-total > div:last-child { padding: 14px !important; }
            .kpi-card-completed { border: 1px solid #cbd5e1 !important; background-color: rgba(167, 243, 208, 0.35) !important; }
            .kpi-card-completed > div:last-child { padding: 14px !important; }
            .kpi-card-in-progress { border: 1px solid #cbd5e1 !important; background-color: rgba(253, 230, 138, 0.35) !important; }
            .kpi-card-in-progress > div:last-child { padding: 14px !important; }
            .kpi-card-suspended { border: 1px solid #cbd5e1 !important; background-color: rgba(254, 205, 211, 0.35) !important; }
            .kpi-card-suspended > div:last-child { padding: 14px !important; }
            .kpi-card-status { border: 1px solid #cbd5e1 !important; background-color: rgba(233, 213, 255, 0.35) !important; }
            .kpi-card-status > div:last-child { padding: 14px !important; }
            .kpi-badge-total { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #0e7490 !important; }
            .kpi-badge-completed { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #047857 !important; }
            .kpi-badge-in-progress { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #b45309 !important; }
            .kpi-badge-suspended { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #be123c !important; }
            .kpi-badge-status { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #6d28d9 !important; }
            .kpi-icon-total { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #06b6d4 !important; }
            .kpi-icon-completed { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #10b981 !important; }
            .kpi-icon-in-progress { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #f59e0b !important; }
            .kpi-icon-suspended { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #f43f5e !important; }
            .kpi-icon-status { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #6366f1 !important; }
            .performance-card { border: 1px solid #cbd5e1 !important; }
            .kpi-progress-track { background-color: rgba(255,255,255,0.6) !important; }
            .status-pie-legend-chip { background-color: rgba(255,255,255,0.75) !important; border: 1px solid #cbd5e1 !important; }
            .analytics-legend-inactive { background-color: rgba(255,255,255,0.55) !important; border: 1px solid #cbd5e1 !important; }
            .analytics-legend-active { background-color: rgba(255,255,255,0.85) !important; border: 1px solid #cbd5e1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.06) !important; }
            .analytics-card-hover-overlay { background: rgba(248,250,252,0.5) !important; }
        </style>

        <!-- Dashboard Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight font-outfit">Project Performance Overview</h1>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Real-time portfolio health metrics</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 w-fit mx-auto">
            <div class="flex flex-wrap items-end justify-center gap-3">
                <div class="relative min-w-[220px] max-w-[320px]">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300"></i>
                    <input type="text" id="searchInput" placeholder="Search project..."
                        oninput="handleSearch(this.value)"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-2 border-transparent rounded-xl text-xs font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                </div>

                <div class="flex flex-col gap-1 min-w-[160px]">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Project Sector</label>
                    <div class="relative">
                        <select id="filterSector" onchange="applyFilters()" class="appearance-none w-full pl-4 pr-10 py-2.5 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white">
                            <option value="all">All Sectors</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-1 min-w-[160px]">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Delivery Method</label>
                    <div class="relative">
                        <select id="filterDeliveryMethod" onchange="applyFilters()" class="appearance-none w-full pl-4 pr-10 py-2.5 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white">
                            <option value="all">All Delivery Methods</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-1 min-w-[160px]">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Consultant Type</label>
                    <div class="relative">
                        <select id="filterConsultantType" onchange="applyFilters()" class="appearance-none w-full pl-4 pr-10 py-2.5 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white">
                            <option value="all">All Consultant Types</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-1 min-w-[160px]">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest px-1">Project Status</label>
                    <div class="relative">
                        <select id="filterLifecycleStatus" onchange="applyFilters()" class="appearance-none w-full pl-4 pr-10 py-2.5 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white">
                            <option value="all">All Project Statuses</option>
                            <option value="completed">Completed</option>
                            <option value="in progress">In Progress</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <button onclick="clearFilters()" class="flex items-center justify-center w-10 h-10 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all active:scale-90 shadow-sm shrink-0 self-end" title="Clear Filters">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Executive KPI Summary Cards -->
        <div id="executiveKpiRow" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3">
            <!-- Total Projects -->
            <div class="dashboard-kpi-card group relative rounded-2xl border shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden cursor-pointer kpi-card-total" onclick="filterByStatus('all')">
                <div class="relative z-10 p-3.5">
                    <div class="flex items-center justify-between mb-1">
                        <div class="w-8 h-8 rounded-lg border flex items-center justify-center shadow-sm kpi-icon-total">
                            <i data-lucide="folder-open" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md border kpi-badge-total">Active</span>
                    </div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Projects</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none mb-1">
                        <span class="counter" data-target="0">0</span>
                    </p>
                    <div class="space-y-0.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[8px] font-semibold text-slate-500">Completed</span>
                            <div class="flex items-center gap-1">
                                <div class="w-5 kpi-progress-track rounded-full h-0.5 overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full" id="completedBar" style="width: 0%"></div>
                                </div>
                                <span class="text-[8px] font-bold text-slate-900 w-2 text-right kpi-breakdown-count" data-key="completed">0</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[8px] font-semibold text-slate-500">In Progress</span>
                            <div class="flex items-center gap-1">
                                <div class="w-5 kpi-progress-track rounded-full h-0.5 overflow-hidden">
                                    <div class="bg-blue-500 h-full rounded-full" id="inProgressBar" style="width: 0%"></div>
                                </div>
                                <span class="text-[8px] font-bold text-slate-900 w-2 text-right kpi-breakdown-count" data-key="in_progress">0</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[8px] font-semibold text-slate-500">Suspended</span>
                            <div class="flex items-center gap-1">
                                <div class="w-5 kpi-progress-track rounded-full h-0.5 overflow-hidden">
                                    <div class="bg-amber-500 h-full rounded-full" id="suspendedBar" style="width: 0%"></div>
                                </div>
                                <span class="text-[8px] font-bold text-slate-900 w-2 text-right kpi-breakdown-count" data-key="suspended">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Projects -->
            <div class="dashboard-kpi-card group relative rounded-2xl border shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden kpi-card-completed">
                <div class="relative z-10 p-3.5">
                    <div class="flex items-center justify-between mb-1">
                        <div class="w-8 h-8 rounded-lg border flex items-center justify-center shadow-sm kpi-icon-completed">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md border kpi-badge-completed">Revised Budget</span>
                    </div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Completed Projects</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none mb-1">
                        <span class="text-[10px] font-bold text-slate-400 mr-0.5">ETB</span>
                        <span class="counter-currency" data-target="0">0</span>
                    </p>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                        <span class="kpi-count-label">0</span> <span class="text-slate-500 normal-case tracking-normal">Projects</span>
                    </p>
                </div>
            </div>

            <!-- In Progress Projects -->
            <div class="dashboard-kpi-card group relative rounded-2xl border shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden kpi-card-in-progress">
                <div class="relative z-10 p-3.5">
                    <div class="flex items-center justify-between mb-1">
                        <div class="w-8 h-8 rounded-lg border flex items-center justify-center shadow-sm kpi-icon-in-progress">
                            <i data-lucide="banknote" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md border kpi-badge-in-progress">Revised Budget</span>
                    </div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">In Progress Projects</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none mb-1">
                        <span class="text-[10px] font-bold text-slate-400 mr-0.5">ETB</span>
                        <span class="counter-currency" data-target="0">0</span>
                    </p>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                        <span class="kpi-count-label">0</span> <span class="text-slate-500 normal-case tracking-normal">Active</span>
                    </p>
                </div>
            </div>

            <!-- Suspended Projects -->
            <div class="dashboard-kpi-card group relative rounded-2xl border shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden kpi-card-suspended">
                <div class="relative z-10 p-3.5">
                    <div class="flex items-center justify-between mb-1">
                        <div class="w-8 h-8 rounded-lg border flex items-center justify-center shadow-sm kpi-icon-suspended">
                            <i data-lucide="hard-hat" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md border kpi-badge-suspended">Revised Budget</span>
                    </div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Suspended Projects</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none mb-1">
                        <span class="text-[10px] font-bold text-slate-400 mr-0.5">ETB</span>
                        <span class="counter-currency" data-target="0">0</span>
                    </p>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                        <span class="kpi-count-label">0</span> <span class="text-slate-500 normal-case tracking-normal">Projects</span>
                    </p>
                </div>
            </div>

            <!-- Project Status Pie Chart -->
            <div class="dashboard-kpi-card group relative rounded-2xl border shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden kpi-card-status">
                <div class="relative z-10 p-3.5">
                    <div class="flex items-center justify-between mb-1">
                        <div class="w-8 h-8 rounded-lg border flex items-center justify-center shadow-sm kpi-icon-status">
                            <i data-lucide="pie-chart" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md border kpi-badge-status">Project Status</span>
                    </div>
                    <div class="flex flex-col items-center justify-center -mt-0.5">
                        <div id="statusPieChart" class="w-full" style="height: 120px;">
                            <div id="statusPieChartInner" class="w-full h-full"></div>
                        </div>
                        <div id="statusPieLegend" class="flex flex-wrap items-center justify-center gap-x-1 gap-y-0.5 mt-0.5"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="analyticsGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3 mt-4">
                <!-- Charts injected by JS -->
            </div>

        <!-- Performance Summary -->
        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden performance-card">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Performance Summary</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5" id="chartSubtitle">Loading...</p>
                </div>
                <div class="flex items-center gap-2">
                    <div id="chartLegend" class="hidden sm:flex items-center gap-4 mr-4"></div>
                    <button onclick="refreshChart()" class="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                        Refresh
                    </button>
                    <button onclick="toggleFullscreen()" class="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                        <i data-lucide="maximize" class="w-3 h-3"></i>
                        Fullscreen
                    </button>
                </div>
            </div>

            <!-- Mini KPI Row -->
            <div id="miniKpiRow" class="px-5 py-3 bg-white border-b border-slate-100">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Projects</span>
                        <span id="kpiProjects" class="text-xs font-black text-slate-900">0</span>
                    </div>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Average</span>
                        <span id="kpiAverage" class="text-xs font-black text-slate-900">0%</span>
                    </div>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Highest</span>
                        <span id="kpiHighest" class="text-xs font-black text-emerald-600">0%</span>
                    </div>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Lowest</span>
                        <span id="kpiLowest" class="text-xs font-black text-rose-600">0%</span>
                    </div>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="flex items-center gap-1.5">
                        <select id="sectionFilterCategory" onchange="updateSectionFilterValues()" class="appearance-none bg-white border border-slate-200 rounded-lg pl-2 pr-7 py-1.5 text-[8px] font-black text-slate-600 uppercase tracking-wider cursor-pointer hover:border-slate-300 transition-all focus:ring-2 focus:ring-slate-100 focus:border-slate-300">
                            <option value="">Filter By</option>
                            <option value="status">Project Status</option>
                            <option value="consultancy_sector">Consultancy Sector</option>
                            <option value="delivery_method">Delivery Method</option>
                            <option value="schedule_status">Schedule Status</option>
                            <option value="project_type">Project Sector</option>
                            <option value="budget_performance">Budget Performance</option>
                            <option value="cost_performance">Cost Performance</option>
                        </select>
                        <select id="sectionFilterValue" onchange="applySectionFilter()" disabled class="appearance-none bg-white border border-slate-200 rounded-lg pl-2 pr-7 py-1.5 text-[8px] font-black text-slate-600 uppercase tracking-wider cursor-pointer hover:border-slate-300 transition-all focus:ring-2 focus:ring-slate-100 focus:border-slate-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="">All</option>
                        </select>
                    </div>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl inline-flex">
                        <button onclick="switchStatusTab('in progress')" id="status-tab-in-progress" class="status-tab-btn px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all text-slate-500 hover:text-slate-900">In Progress</button>
                        <button onclick="switchStatusTab('completed')" id="status-tab-completed" class="status-tab-btn px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all bg-white text-slate-900 shadow-sm">Completed</button>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="px-5 pt-4">
                <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl inline-flex">
                    <button onclick="switchTab('schedule')" id="tab-schedule" class="tab-btn px-5 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all bg-white text-slate-900 shadow-sm">Schedule</button>
                    <button onclick="switchTab('budget')" id="tab-budget" class="tab-btn px-5 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all text-slate-500 hover:text-slate-900">Budget</button>
                    <button onclick="switchTab('cost')" id="tab-cost" class="tab-btn px-5 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all text-slate-500 hover:text-slate-900">Cost</button>
                </div>
            </div>

            <!-- Chart -->
            <div id="chartContainer" class="px-5 py-4 w-full">
                <div id="chart" class="h-full w-full"></div>
            </div>
        </div>

        <!-- Comparative Performance Analytics -->
        <div id="comparativeGrid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Charts injected by JS -->
        </div>

        <!-- Drill Down Drawer -->
    <div id="drillDownDrawer" class="hidden fixed inset-0 z-50 overflow-hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeDrillDown()"></div>
        <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-white shadow-2xl transform transition-transform duration-300 translate-x-full" id="drawerPanel">
            <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-4 flex items-center justify-between z-10">
                <div>
                    <h3 class="text-base font-black text-white" id="drawerProjectName">Project Details</h3>
                    <p class="text-[9px] font-black text-white/60 uppercase tracking-widest mt-0.5" id="drawerProjectCode"></p>
                </div>
                <button onclick="closeDrillDown()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="drawerContent" class="p-6 overflow-y-auto h-full pb-20">
                <div class="flex items-center justify-center py-12">
                    <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        let currentTab = 'schedule';
        let currentStatusTab = 'completed';
        let allProjects = [];
        let filteredProjects = [];
        let currentPage = 1;
        let perPage = 15;
        let totalPages = 1;
        let chartInstance = null;
        let searchTimeout = null;
        let currentChartMeta = { categories: [], values: [], colors: [], average: 0 };
        window.latestAnalytics = {};
        window.latestExecutiveKpis = {};
        window.latestComparativeAnalytics = {};
        let sectionFilter = { category: null, value: null };

        const tabTitles = {
            schedule: 'Project Schedule Performance',
            budget: 'Project Budget Performance',
            cost: 'Project Cost Performance'
        };

        const statusTabTitles = {
            'in progress': 'In Progress',
            completed: 'Completed'
        };

        document.addEventListener('DOMContentLoaded', function() {
            loadFilters();
            loadDashboardData();
        });

        function loadFilters() {
            fetch('{{ route('admin.dashboard.data') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Dashboard filters server error:', data.message);
                        const existing = document.getElementById('dashboardError');
                        if (existing) existing.remove();
                        const el = document.createElement('div');
                        el.id = 'dashboardError';
                        el.className = 'p-3 bg-rose-50 border border-rose-200 rounded-xl text-[9px] font-black text-rose-700';
                        el.textContent = data.message || 'Failed to load dashboard filters. Please refresh.';
                        document.querySelector('.space-y-4')?.prepend(el);
                        return;
                    }
                    populateFilter('filterSector', data.filters.sectors, 'All Sectors');
                    populateFilter('filterDeliveryMethod', data.filters.delivery_methods, 'All Delivery Methods');
                    populateFilter('filterConsultantType', data.filters.consultant_types, 'All Consultant Types');
                    populateFilter('filterLifecycleStatus', data.filters.lifecycle_statuses, 'All Project Statuses');
                })
                .catch(err => {
                    console.error('Dashboard filters load error:', err);
                    const existing = document.getElementById('dashboardError');
                    if (existing) existing.remove();
                    const el = document.createElement('div');
                    el.id = 'dashboardError';
                    el.className = 'p-3 bg-rose-50 border border-rose-200 rounded-xl text-[9px] font-black text-rose-700';
                    el.textContent = 'Network error loading filters. Please refresh.';
                    document.querySelector('.space-y-4')?.prepend(el);
                });
        }

        function populateFilter(elementId, values, defaultLabel) {
            const select = document.getElementById(elementId);
            if (!select) return;
            const currentValue = select.value;
            select.innerHTML = `<option value="all">${defaultLabel}</option>`;
            values.forEach(value => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                select.appendChild(option);
            });
            select.value = currentValue || 'all';
        }

        function loadDashboardData() {
            const params = new URLSearchParams();
            params.append('page', currentPage);
            params.append('per_page', perPage);

            const sectorDropdown = document.getElementById('filterSector');
            const sectorFilteredByChart = activeChartFilter && activeChartFilter.chart === 'consultancy_sector';
            if (!sectorFilteredByChart && sectorDropdown && sectorDropdown.value !== 'all') {
                params.append('sector', sectorDropdown.value);
            }

            const deliveryDropdown = document.getElementById('filterDeliveryMethod');
            if (deliveryDropdown && deliveryDropdown.value !== 'all') {
                params.append('delivery_method', deliveryDropdown.value);
            }

            const consultantDropdown = document.getElementById('filterConsultantType');
            if (consultantDropdown && consultantDropdown.value !== 'all') {
                params.append('consultant_type', consultantDropdown.value);
            }

            const lifecycleDropdown = document.getElementById('filterLifecycleStatus');
            if (lifecycleDropdown && lifecycleDropdown.value !== 'all') {
                params.append('lifecycle_status', lifecycleDropdown.value);
            }

            if (document.getElementById('searchInput').value) params.append('search', document.getElementById('searchInput').value);

            if (activeChartFilter) {
                params.append(activeChartFilter.chart, activeChartFilter.value);
            }

            fetch(`{{ route('admin.dashboard.data') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Dashboard server error:', data.message);
                        const existing = document.getElementById('dashboardError');
                        if (existing) existing.remove();
                        const el = document.createElement('div');
                        el.id = 'dashboardError';
                        el.className = 'p-3 bg-rose-50 border border-rose-200 rounded-xl text-[9px] font-black text-rose-700';
                        el.textContent = data.message || 'Failed to load dashboard data. Please refresh or contact support.';
                        document.querySelector('.space-y-4')?.prepend(el);
                        return;
                    }
                    allProjects = data.all_projects || [];
                    filteredProjects = allProjects;
                    currentPage = data.page || 1;
                    perPage = data.per_page || 15;
                    totalPages = data.total_pages || 1;
                    window.latestAnalytics = data.analytics || {};
                    window.latestExecutiveKpis = data.executive_kpis || {};
                    window.latestComparativeAnalytics = data.comparative_analytics || {};
                    renderExecutiveKpis();
                    renderMiniKpis();
                    renderLegend();
                    renderChart();
                    renderAnalyticsGrid();
                    renderComparativeAnalytics();
                    renderStatusPieChart();
                    updateSubtitle();
                })
                .catch(err => {
                    console.error('Dashboard data load error:', err);
                    const existing = document.getElementById('dashboardError');
                    if (existing) existing.remove();
                    const el = document.createElement('div');
                    el.id = 'dashboardError';
                    el.className = 'p-3 bg-rose-50 border border-rose-200 rounded-xl text-[9px] font-black text-rose-700';
                    el.textContent = 'Network error loading dashboard. Please refresh.';
                    document.querySelector('.space-y-4')?.prepend(el);
                });
        }

        function applyFilters() {
            loadDashboardData();
        }

        function handleSearch(value) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchInput').value = value;
                applyFilters();
            }, 300);
        }

        function clearFilters() {
            document.getElementById('filterSector').value = 'all';
            document.getElementById('filterDeliveryMethod').value = 'all';
            document.getElementById('filterConsultantType').value = 'all';
            document.getElementById('filterLifecycleStatus').value = 'all';
            document.getElementById('searchInput').value = '';
            activeChartFilter = null;
            updateClearFilterButton();
            applyFilters();
        }

        function filterByStatus(status) {
            const statusSelect = document.getElementById('filterLifecycleStatus');
            if (!statusSelect) return;
            statusSelect.value = status;
            applyFilters();
        }

        function updateSectionFilterValues() {
            const categorySelect = document.getElementById('sectionFilterCategory');
            const valueSelect = document.getElementById('sectionFilterValue');
            if (!categorySelect || !valueSelect) return;

            const category = categorySelect.value;
            valueSelect.innerHTML = '<option value="">All</option>';

            if (!category) {
                valueSelect.disabled = true;
                sectionFilter = { category: null, value: null };
                renderChart();
                return;
            }

            const values = new Set();
            (filteredProjects || []).forEach(p => {
                const val = p[category];
                if (val !== null && val !== undefined && val !== '') {
                    values.add(String(val));
                }
            });

            Array.from(values).sort().forEach(val => {
                const option = document.createElement('option');
                option.value = val;
                option.textContent = val;
                valueSelect.appendChild(option);
            });

            valueSelect.disabled = false;
            sectionFilter = { category, value: null };
            renderChart();
        }

        function applySectionFilter() {
            const categorySelect = document.getElementById('sectionFilterCategory');
            const valueSelect = document.getElementById('sectionFilterValue');
            if (!categorySelect || !valueSelect) return;

            const category = categorySelect.value;
            const value = valueSelect.value;
            sectionFilter = { category, value };
            renderChart();
        }

        function getSectionFilteredProjects(projects) {
            if (!sectionFilter.category || !sectionFilter.value) return projects;
            return projects.filter(p => String(p[sectionFilter.category]) === sectionFilter.value);
        }

        function renderExecutiveKpis() {
            const kpis = window.latestExecutiveKpis || {};
            const totalProjects = kpis.total_projects?.total ?? 0;
            const completed = kpis.total_projects?.completed ?? 0;
            const inProgress = kpis.total_projects?.in_progress ?? 0;
            const suspended = kpis.total_projects?.suspended ?? 0;

            const completedAmount = kpis.completed_revised_budget?.total ?? 0;
            const completedCount = kpis.completed_revised_budget?.count ?? 0;
            const inProgressAmount = kpis.in_progress_revised_budget?.total ?? 0;
            const inProgressCount = kpis.in_progress_revised_budget?.count ?? 0;
            const suspendedAmount = kpis.suspended_revised_budget?.total ?? 0;
            const suspendedCount = kpis.suspended_revised_budget?.count ?? 0;

            const totalEl = document.querySelector('#executiveKpiRow .dashboard-kpi-card:first-child .counter');
            if (totalEl) animateCounter(totalEl, totalProjects, false);

            const breakdownMap = { completed, in_progress: inProgress, suspended };
            const totalForBreakdown = totalProjects || 1;
            Object.keys(breakdownMap).forEach(key => {
                const countEl = document.querySelector(`#executiveKpiRow .dashboard-kpi-card:first-child .kpi-breakdown-count[data-key="${key}"]`);
                if (countEl) countEl.textContent = breakdownMap[key];
            });

            const completedBar = document.getElementById('completedBar');
            if (completedBar) completedBar.style.width = totalForBreakdown > 0 ? ((completed / totalForBreakdown) * 100) + '%' : '0%';

            const inProgressBar = document.getElementById('inProgressBar');
            if (inProgressBar) inProgressBar.style.width = totalForBreakdown > 0 ? ((inProgress / totalForBreakdown) * 100) + '%' : '0%';

            const suspendedBar = document.getElementById('suspendedBar');
            if (suspendedBar) suspendedBar.style.width = totalForBreakdown > 0 ? ((suspended / totalForBreakdown) * 100) + '%' : '0%';

            const completedAmountEl = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(2) .counter-currency');
            if (completedAmountEl) animateCounter(completedAmountEl, completedAmount, true);

            const completedCountEl = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(2) .kpi-count-label');
            if (completedCountEl) completedCountEl.textContent = completedCount;

            const inProgressAmountEl = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(3) .counter-currency');
            if (inProgressAmountEl) animateCounter(inProgressAmountEl, inProgressAmount, true);

            const inProgressCountEl = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(3) .kpi-count-label');
            if (inProgressCountEl) inProgressCountEl.textContent = inProgressCount;

            const suspendedAmountEl = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(4) .counter-currency');
            if (suspendedAmountEl) animateCounter(suspendedAmountEl, suspendedAmount, true);

            const suspendedCountEl = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(4) .kpi-count-label');
            if (suspendedCountEl) suspendedCountEl.textContent = suspendedCount;
        }

        function animateCounter(el, target, isCurrency) {
            const duration = 700;
            const start = parseFloat(el.textContent.replace(/[^0-9.-]/g, '')) || 0;
            const startTime = performance.now();

            function update(now) {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = start + (target - start) * eased;
                if (isCurrency) {
                    el.innerHTML = formatCompactCurrency(current);
                } else {
                    el.textContent = formatNumber(Math.round(current));
                }
                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    if (isCurrency) {
                        el.innerHTML = formatCompactCurrency(target);
                    } else {
                        el.textContent = formatNumber(Math.round(target));
                    }
                }
            }
            requestAnimationFrame(update);
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('en-US').format(Math.round(num));
        }

        function formatCompactCurrency(num) {
            const rounded = Math.round(num);
            const absNum = Math.abs(rounded);
            let suffix = '';
            if (absNum >= 1_000_000_000_000) suffix = 'T';
            else if (absNum >= 1_000_000_000) suffix = 'B';
            else if (absNum >= 1_000_000) suffix = 'M';
            else if (absNum >= 1_000) suffix = 'K';
            if (suffix) {
                return formatNumber(rounded) + `<span class="text-[10px] font-black text-slate-400 ml-0.5">${suffix}</span>`;
            }
            return formatNumber(rounded);
        }

        function switchStatusTab(status) {
            currentStatusTab = status;
            document.querySelectorAll('.status-tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                btn.classList.add('text-slate-500');
            });
            const activeBtn = document.getElementById(`status-tab-${status.replace(/\s+/g, '-')}`);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-500');
                activeBtn.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
            }
            currentPage = 1;
            renderMiniKpis();
            renderLegend();
            renderChart();
            renderStatusPieChart();
            updateSubtitle();
        }

        function switchTab(tab) {
            currentTab = tab;
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                btn.classList.add('text-slate-500');
            });
            const activeBtn = document.getElementById(`tab-${tab}`);
            activeBtn.classList.remove('text-slate-500');
            activeBtn.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
            renderMiniKpis();
            renderLegend();
            renderChart();
            updateSubtitle();
        }

        function updateSubtitle() {
            const subtitle = document.getElementById('chartSubtitle');
            if (subtitle) {
                const statusLabel = statusTabTitles[currentStatusTab] || currentStatusTab;
                let text = `${statusLabel} • ${tabTitles[currentTab]} • Last updated ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
                if (activeChartFilter) {
                    text = `${activeChartFilter.label} • ${text}`;
                }
                subtitle.textContent = text;
            }
        }

        function refreshChart() {
            loadDashboardData();
        }

        function toggleFullscreen() {
            const el = document.getElementById('chartContainer');
            if (!document.fullscreenElement) {
                el.requestFullscreen().catch(() => {});
            } else {
                document.exitFullscreen();
            }
        }

        function getStatusFilteredProjects() {
            return filteredProjects.filter(p => (p.status || '').toLowerCase() === currentStatusTab.toLowerCase());
        }

        function renderMiniKpis() {
            const projects = getSectionFilteredProjects(getStatusFilteredProjects());
            const values = projects.map(p => {
                if (currentTab === 'schedule') return p.schedule_performance_percent;
                if (currentTab === 'budget') return p.budget_index_percent;
                return p.cost_performance_percent;
            });

            if (values.length === 0) {
                document.getElementById('kpiProjects').textContent = '0';
                document.getElementById('kpiAverage').textContent = '0%';
                document.getElementById('kpiHighest').textContent = '0%';
                document.getElementById('kpiLowest').textContent = '0%';
                return;
            }

            const avg = values.reduce((a, b) => a + b, 0) / values.length;
            const max = Math.max(...values);
            const min = Math.min(...values);

            document.getElementById('kpiProjects').textContent = values.length;
            document.getElementById('kpiAverage').textContent = `${Math.round(avg)}%`;
            document.getElementById('kpiHighest').textContent = `${Math.round(max)}%`;
            document.getElementById('kpiLowest').textContent = `${Math.round(min)}%`;
        }

        function renderLegend() {
            const container = document.getElementById('chartLegend');
            const aheadLabel = currentTab === 'budget' || currentTab === 'cost' ? 'Below / Under' : 'Ahead';
            const onLabel = currentTab === 'budget' ? 'On Budget' : currentTab === 'cost' ? 'Within Allowable' : 'On Schedule';
            const behindLabel = currentTab === 'budget' || currentTab === 'cost' ? 'Above / Over' : 'Behind';

            container.innerHTML = `
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span>
                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">${aheadLabel}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-amber-500"></span>
                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">${onLabel}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-rose-500"></span>
                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">${behindLabel}</span>
                </div>
            `;
        }

        function formatChartLabel(project, index) {
            const rawLabel = project.project_code || project.project_name || `Project ${index + 1}`;
            const label = String(rawLabel).trim();
            return label.length > 24 ? `${label.substring(0, 21)}…` : label;
        }

        function getChartData() {
            const projects = getSectionFilteredProjects(getStatusFilteredProjects());
            const sorted = [...projects].sort((a, b) => {
                const statusOrder = { 'Ahead of Schedule': 1, 'On Schedule': 2, 'Behind Schedule': 3, 'Not Started': 4 };
                const statusA = statusOrder[a.schedule_status] ?? 5;
                const statusB = statusOrder[b.schedule_status] ?? 5;
                if (statusA !== statusB) return statusA - statusB;

                const valA = currentTab === 'schedule' ? a.schedule_performance_percent : currentTab === 'budget' ? a.budget_index_percent : a.cost_performance_percent;
                const valB = currentTab === 'schedule' ? b.schedule_performance_percent : currentTab === 'budget' ? b.budget_index_percent : b.cost_performance_percent;
                return valB - valA;
            });

            const visibleProjects = currentStatusTab === 'in progress' && currentTab === 'schedule'
                ? sorted.filter(p => p.has_valid_spi)
                : sorted;
            const categories = [];
            const values = [];
            const colors = [];
            const sortedProjects = [];

            visibleProjects.forEach((p, idx) => {
                categories.push(`${formatChartLabel(p, idx)}`);
                const val = currentTab === 'schedule' ? p.schedule_performance_percent : currentTab === 'budget' ? p.budget_index_percent : p.cost_performance_percent;
                values.push(val);
                if (currentTab === 'schedule') {
                    if (p.schedule_status === 'Ahead of Schedule') colors.push('#10b981');
                    else if (p.schedule_status === 'On Schedule') colors.push('#f59e0b');
                    else if (p.schedule_status === 'Behind Schedule') colors.push('#ef4444');
                    else colors.push('#94a3b8');
                } else if (currentTab === 'budget' || currentTab === 'cost') {
                    if (currentTab === 'budget') {
                        if (val > 0) colors.push('#10b981');
                        else if (val === 0) colors.push('#94a3b8');
                        else colors.push('#ef4444');
                    } else {
                        if (val > 0) colors.push('#10b981');
                        else if (val === 0) colors.push('#94a3b8');
                        else colors.push('#ef4444');
                    }
                } else {
                    colors.push('#00ADC5');
                }
                sortedProjects.push(p);
            });

            return { categories, values, colors, sortedProjects };
        }

        function renderChart() {
            if (chartInstance) {
                chartInstance.destroy();
                chartInstance = null;
            }

            const { categories, values, colors, sortedProjects } = getChartData();
            const chartEl = document.querySelector('#chart');

            if (categories.length === 0) {
                chartEl.innerHTML = '<div class="flex items-center justify-center h-full text-slate-400 text-sm font-black">No data available for the selected filters</div>';
                currentChartMeta = { categories: [], values: [], colors: [], average: 0 };
                return;
            }

            const chartHeight = 420;
            const chartContainer = document.getElementById('chartContainer');
            if (chartContainer) {
                chartContainer.style.height = `${chartHeight}px`;
                chartContainer.style.overflow = 'hidden';
            }

            const columnWidth = categories.length <= 5 ? '60%' : categories.length <= 15 ? '40%' : categories.length <= 30 ? '25%' : '15%';

            const allZero = values.every(v => v === 0);
            if (allZero) {
                chartEl.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300">
                            <i data-lucide="bar-chart-3" class="w-7 h-7"></i>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-black text-slate-900">No ${tabTitles[currentTab].toLowerCase()} variance detected</p>
                            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400 mt-1">All projects show 0% for this metric.</p>
                        </div>
                    </div>
                `;
                if (window.lucide) lucide.createIcons();
                currentChartMeta = { categories, values, colors, average: 0 };
                return;
            }

            const minVal = Math.min(...values);
            const maxVal = Math.max(...values);
            const rawRange = maxVal - minVal;
            const minRange = currentTab === 'budget' ? 6 : 4;
            const range = Math.max(rawRange, minRange);
            const padding = range * 0.15;
            const avg = values.reduce((a, b) => a + b, 0) / values.length;

            currentChartMeta = { categories, values, colors, average: avg };

            const tickStep = range > 40 ? 5 : range > 20 ? 2 : 1;
            const tickAmount = Math.ceil((maxVal + padding) / tickStep) + Math.ceil((Math.abs(minVal) + padding) / tickStep) + 1;
            const dataLabelFontSize = categories.length > 25 ? '7px' : categories.length > 15 ? '8px' : '9px';

            chartInstance = new ApexCharts(chartEl, {
                chart: {
                    type: 'bar',
                    height: chartHeight,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500,
                        dynamicAnimation: { enabled: true, speed: 400 }
                    },
                    toolbar: { show: false },
                    background: 'transparent',
                    dropShadow: {
                        enabled: true,
                        top: 3,
                        left: 0,
                        blur: 8,
                        opacity: 0.25
                    },
                    events: {
                        dataPointSelection: (event, chartContext, { dataPointIndex }) => {
                            openDrillDown(sortedProjects[dataPointIndex]);
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: columnWidth,
                        borderRadius: 4,
                        borderRadiusApplication: 'end',
                        distributed: true,
                    }
                },
                series: [{
                    name: tabTitles[currentTab],
                    data: values
                }],
                xaxis: {
                    categories: categories,
                    labels: {
                        style: { fontSize: '10px', fontWeight: 800, colors: '#334155' },
                        rotate: -45,
                        rotateAlways: true,
                        hideOverlappingLabels: true,
                        trim: true,
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    min: Math.floor(Math.min(minVal, 0) - Math.max(range * 0.15, 2)),
                    max: Math.ceil(Math.max(maxVal, 0) + Math.max(range * 0.15, 2)),
                    tickAmount: Math.min(8, Math.max(3, Math.round(range / 10) + 1)),
                    labels: {
                        style: { fontSize: '10px', fontWeight: 800, colors: '#00ADC5' },
                        formatter: (val) => `${Math.round(val)}%`
                    }
                },
                colors: colors,
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: 'horizontal',
                        shadeIntensity: 0.2,
                        gradientToColors: colors,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 0.85,
                        stops: [0, 100]
                    }
                },
                grid: {
                    borderColor: '#EEF2F7',
                    strokeDashArray: 4,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: true } },
                    padding: { left: 8, right: 16, top: 8, bottom: 8 }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: dataLabelFontSize,
                        fontWeight: 800,
                        colors: ['#000']
                    },
                    formatter: (val) => {
                        const icon = val > 0 ? '▲' : val < 0 ? '▼' : '●';
                        return `${icon} ${Math.round(val)}%`;
                    },
                    textAnchor: 'middle',
                    offsetX: 0,
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 3,
                        opacity: 0.35
                    }
                },
                annotations: {
                    xaxis: [
                        {
                            x: 0,
                            strokeColor: '#94a3b8',
                            strokeWidth: 2,
                            label: { show: false }
                        },
                        {
                            x: avg,
                            strokeColor: '#6366f1',
                            strokeDashArray: 4,
                            label: {
                                text: `Avg ${Math.round(avg)}%`,
                                style: {
                                    background: '#6366f1',
                                    color: '#fff',
                                    fontSize: '9px',
                                    fontWeight: 700,
                                    padding: { left: 6, right: 6, top: 2, bottom: 2 }
                                }
                            }
                        },
                        {
                            x: minVal - padding,
                            x2: 0,
                            fillColor: 'rgba(239,68,68,0.03)',
                            label: { show: false }
                        },
                        {
                            x: 0,
                            x2: maxVal + padding,
                            fillColor: 'rgba(34,197,94,0.03)',
                            label: { show: false }
                        }
                    ]
                },
                tooltip: {
                    enabled: true,
                    theme: 'light',
                    x: { show: false },
                    y: {
                        formatter: (val) => `${Math.round(val)}%`
                    },
                    custom: function({ series, seriesIndex, dataPointIndex, w }) {
                        const project = sortedProjects[dataPointIndex];
                        if (!project) return '';
                        let content = `<div style="padding: 10px 14px; font-family: 'Inter', sans-serif; min-width: 260px; border-radius: 10px;">`;
                        content += `<div style="font-weight: 800; font-size: 12px; color: #0f172a; margin-bottom: 6px;">${project.project_name}</div>`;
                        content += `<div style="font-weight: 700; font-size: 10px; color: #64748b; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.08em;">${project.project_code} • ${tabTitles[currentTab]}</div>`;

                        if (currentTab === 'schedule') {
                            const statusColor = project.schedule_status === 'Behind Schedule' ? '#ef4444' : project.schedule_status === 'Ahead of Schedule' ? '#10b981' : '#3b82f6';
                            const schedulePercent = Math.round(project.schedule_performance_percent);
                            let schedulePercentColor = '#94a3b8';
                            if (schedulePercent > 100) schedulePercentColor = '#10b981';
                            else if (schedulePercent === 100) schedulePercentColor = '#94a3b8';
                            else if (schedulePercent >= 80) schedulePercentColor = '#10b981';
                            else if (schedulePercent > 0) schedulePercentColor = '#f59e0b';
                            else schedulePercentColor = '#ef4444';
                            content += `<div style="font-weight: 800; font-size: 20px; color: ${schedulePercentColor}; margin-bottom: 8px;">${schedulePercent}%</div>`;
                            if (project.status === 'completed') {
                                content += `<div style="display: grid; gap: 3px; font-size: 10px; color: #475569; margin-bottom: 8px;">`;
                                content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Status</span><span style="font-weight: 700; color: ${statusColor};">${project.schedule_status}</span></div>`;
                                const varianceLabel = project.schedule_variance_days > 0 ? '+' + Math.round(project.schedule_variance_days) + ' days late' : project.schedule_variance_days < 0 ? Math.abs(Math.round(project.schedule_variance_days)) + ' days early' : 'On time';
                                const varianceColor = project.schedule_variance_days > 0 ? '#ef4444' : project.schedule_variance_days < 0 ? '#10b981' : '#64748b';
                                content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Variance</span><span style="font-weight: 700; color: ${varianceColor};">${varianceLabel}</span></div>`;
                                content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Actual Duration</span><span style="font-weight: 700;">${Math.round(project.actual_duration)} Days</span></div>`;
                                content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Baseline Duration</span><span style="font-weight: 700;">${Math.round(project.baseline_duration)} Days</span></div>`;
                                content += `</div>`;
                            }
                            if (project.progress_planned !== null && project.progress_actual !== null) {
                                content += `<div style="border-top: 1px solid #e2e8f0; padding-top: 6px; display: grid; gap: 3px; font-size: 10px; color: #475569;">`;
                                content += `<div style="font-weight: 700; color: #0f172a; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.05em;">Progress Update</div>`;
                                content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Progress Planned</span><span style="font-weight: 700;">${project.progress_planned}%</span></div>`;
                                content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Progress Actual</span><span style="font-weight: 700;">${project.progress_actual}%</span></div>`;
                                if (project.progress_data_date) {
                                    content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Data Date</span><span style="font-weight: 700;">${project.progress_data_date}</span></div>`;
                                }
                                if (project.progress_top_constraints) {
                                    content += `<div style="margin-top: 3px; padding-top: 3px; border-top: 1px dashed #e2e8f0;"><span style="font-weight: 600; color: #94a3b8;">Top Constraints</span><p style="font-weight: 700; color: #334155; margin-top: 2px;">${project.progress_top_constraints}</p></div>`;
                                }
                                content += `</div>`;
                            }
                            content += `<div style="border-top: 1px solid #e2e8f0; padding-top: 6px; display: grid; gap: 3px; font-size: 10px; color: #475569;">`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Baseline Start</span><span style="font-weight: 700;">${project.baseline_start_date || '---'}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Baseline Finish</span><span style="font-weight: 700;">${project.baseline_finish_date || '---'}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Actual Start</span><span style="font-weight: 700;">${project.actual_start_date || '---'}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Actual Finish</span><span style="font-weight: 700;">${project.actual_finish_date || '---'}</span></div>`;
                            content += `</div>`;
                        } else if (currentTab === 'budget') {
                            const perfColor = project.budget_performance === 'Above Budget' ? '#ef4444' : project.budget_performance === 'Under Budget' ? '#10b981' : '#3b82f6';
                            content += `<div style="font-weight: 800; font-size: 20px; color: ${project.budget_index_percent < 0 ? '#ef4444' : project.budget_index_percent > 0 ? '#10b981' : '#94a3b8'}; margin-bottom: 8px;">${Math.round(project.budget_index_percent)}%</div>`;
                            content += `<div style="display: grid; gap: 3px; font-size: 10px; color: #475569;">`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Performance</span><span style="font-weight: 700; color: ${perfColor};">${project.budget_performance}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Budget Variance</span><span style="font-weight: 700;">ETB ${Math.round(project.budget_variance).toLocaleString()}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Contract Budget</span><span style="font-weight: 700;">ETB ${Math.round(project.contract_budget).toLocaleString()}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Revised Budget</span><span style="font-weight: 700;">ETB ${Math.round(project.revised_budget).toLocaleString()}</span></div>`;
                            content += `</div>`;
                        } else if (currentTab === 'cost') {
                            const perfColor = project.cost_performance === 'Above Allowable Cost' ? '#ef4444' : project.cost_performance === 'Below Allowable Cost' ? '#10b981' : '#3b82f6';
                            content += `<div style="font-weight: 800; font-size: 20px; color: ${project.cost_performance_percent < 0 ? '#ef4444' : project.cost_performance_percent > 0 ? '#10b981' : '#94a3b8'}; margin-bottom: 8px;">${Math.round(project.cost_performance_percent)}%</div>`;
                            content += `<div style="display: grid; gap: 3px; font-size: 10px; color: #475569;">`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Performance</span><span style="font-weight: 700; color: ${perfColor};">${project.cost_performance}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Allowable Cost</span><span style="font-weight: 700;">ETB ${Math.round(project.total_allowable_cost).toLocaleString()}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Cost at Completion</span><span style="font-weight: 700;">ETB ${Math.round(project.cost_at_completion).toLocaleString()}</span></div>`;
                            content += `</div>`;
                        }
                        content += `</div>`;
                        return content;
                    }
                },
                theme: { mode: 'light' },
                legend: { show: false },
            });

            chartInstance.render();
        }

        function renderStatusPieChart() {
            const existingChart = document.getElementById('statusPieChartInner');
            const legendEl = document.getElementById('statusPieLegend');
            if (!existingChart) return;

            const kpis = window.latestExecutiveKpis || {};
            const totalProjects = kpis.total_projects || {};
            const completed = totalProjects.completed || 0;
            const inProgress = totalProjects.in_progress || 0;
            const suspended = totalProjects.suspended || 0;

            const series = [completed, inProgress, suspended];
            const labels = ['Completed', 'In Progress', 'Suspended'];
            const colors = ['#10b981', '#f59e0b', '#f43f5e'];

            if (window.statusPieInstance) {
                window.statusPieInstance.destroy();
            }

            window.statusPieInstance = new ApexCharts(existingChart, {
                chart: {
                    type: 'pie',
                    height: '100%',
                    animations: { enabled: true, easing: 'easeinout', speed: 500 }
                },
                series: series,
                labels: labels,
                colors: colors,
                legend: { show: false },
                plotOptions: {
                    pie: {
                        startAngle: 0,
                        endAngle: 360,
                        expandOnClick: false,
                        dataLabels: {
                            offset: -4
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        if (val < 10) return '';
                        return Math.round(val) + '%';
                    },
                    style: {
                        fontSize: '9px',
                        fontWeight: 800,
                        colors: ['#ffffff']
                    },
                    dropShadow: {
                        enabled: false
                    }
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['#fff']
                },
                tooltip: { theme: 'light' }
            });

            window.statusPieInstance.render();

            if (legendEl) {
                legendEl.innerHTML = labels.map((label, i) => {
                    const count = series[i] || 0;
                    return `
                        <span class="inline-flex items-center gap-0.5 px-0.5 py-0.5 rounded-full status-pie-legend-chip whitespace-nowrap">
                            <span class="w-0.5 h-0.5 rounded-full shrink-0" style="background-color: ${colors[i]};"></span>
                            <span class="text-[7px] font-bold text-slate-600 uppercase tracking-wider">${label}</span>
                            <span class="text-[7px] font-black text-slate-900">${count}</span>
                        </span>
                    `;
                }).join('');
            }
        }

        function getScheduleBadge(status) {
            const config = {
                'Ahead of Schedule': 'bg-emerald-50 text-emerald-600 border-emerald-200',
                'On Schedule': 'bg-amber-50 text-amber-600 border-amber-200',
                'Behind Schedule': 'bg-rose-50 text-rose-600 border-rose-200',
                'Not Started': 'bg-slate-100 text-slate-500 border-slate-200',
            };
            return `<span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider ${config[status] || 'bg-slate-100 text-slate-600 border-slate-200'} border shadow-sm">${status}</span>`;
        }

        function getBudgetBadge(status) {
            const config = {
                'Under Budget': 'bg-emerald-50 text-emerald-600 border-emerald-200',
                'On Budget': 'bg-blue-50 text-blue-600 border-blue-200',
                'Above Budget': 'bg-rose-50 text-rose-600 border-rose-200',
            };
            return `<span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider ${config[status] || 'bg-slate-100 text-slate-600 border-slate-200'} border shadow-sm">${status}</span>`;
        }

        function getCostBadge(status) {
            const config = {
                'Below Allowable Cost': 'bg-emerald-50 text-emerald-600 border-emerald-200',
                'Within Allowable Cost': 'bg-blue-50 text-blue-600 border-blue-200',
                'Above Allowable Cost': 'bg-rose-50 text-rose-600 border-rose-200',
            };
            return `<span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider ${config[status] || 'bg-slate-100 text-slate-600 border-slate-200'} border shadow-sm">${status}</span>`;
        }

        function getOverallBadge(status) {
            const config = {
                'Good': 'bg-emerald-50 text-emerald-600 border-emerald-200',
                'Attention!': 'bg-rose-50 text-rose-600 border-rose-200',
                'Check Data': 'bg-amber-50 text-amber-600 border-amber-200',
            };
            return `<span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider ${config[status] || 'bg-slate-100 text-slate-600 border-slate-200'} border shadow-sm">${status}</span>`;
        }

        function openDrillDown(project) {
            if (!project) return;
            const drawer = document.getElementById('drillDownDrawer');
            const panel = document.getElementById('drawerPanel');
            document.getElementById('drawerProjectName').textContent = project.project_name;
            document.getElementById('drawerProjectCode').textContent = project.project_code;

            const scheduleColor = project.schedule_status === 'Behind Schedule' ? 'text-rose-600' : project.schedule_status === 'Ahead of Schedule' ? 'text-emerald-600' : 'text-amber-600';
            const budgetColor = project.budget_performance === 'Above Budget' ? 'text-rose-600' : project.budget_performance === 'Under Budget' ? 'text-emerald-600' : 'text-blue-600';
            const costColor = project.cost_performance === 'Above Allowable Cost' ? 'text-rose-600' : project.cost_performance === 'Below Allowable Cost' ? 'text-emerald-600' : 'text-blue-600';
            const overallColor = project.overall_performance === 'Good' ? 'text-emerald-600' : 'text-rose-600';

            document.getElementById('drawerContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Schedule</p>
                            <p class="text-lg font-black ${scheduleColor}">${project.schedule_performance_percent}%</p>
                            <p class="text-[8px] font-black text-slate-500 uppercase tracking-wider mt-1">${project.schedule_status}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Budget</p>
                            <p class="text-lg font-black ${budgetColor}">${project.budget_index_percent}%</p>
                            <p class="text-[8px] font-black text-slate-500 uppercase tracking-wider mt-1">${project.budget_performance}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost</p>
                            <p class="text-lg font-black ${costColor}">${project.cost_performance_percent}%</p>
                            <p class="text-[8px] font-black text-slate-500 uppercase tracking-wider mt-1">${project.cost_performance}</p>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Overall Performance</span>
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider ${project.overall_performance === 'Good' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200'}">${project.overall_performance}</span>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
                        <h4 class="text-xs font-black text-slate-900">Schedule Variance</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Variance</p>
                                <p class="text-sm font-black">
                                    ${project.schedule_variance_days > 0 ? '<span class="text-rose-600">+' + Math.round(project.schedule_variance_days) + ' days late</span>' : project.schedule_variance_days < 0 ? '<span class="text-emerald-600">' + Math.abs(Math.round(project.schedule_variance_days)) + ' days early</span>' : '<span class="text-slate-900">On time</span>'}
                                </p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Duration</p>
                                <p class="text-sm font-black text-slate-900">${Math.round(project.actual_duration)} Days</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Baseline Duration</p>
                                <p class="text-sm font-black text-slate-900">${Math.round(project.baseline_duration)} Days</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
                        <h4 class="text-xs font-black text-slate-900">Budget Variance</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Variance</p>
                                <p class="text-sm font-black ${project.budget_variance > 0 ? 'text-rose-600' : project.budget_variance < 0 ? 'text-emerald-600' : 'text-slate-900'}">ETB ${number_format(Math.abs(project.budget_variance))}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Contract Budget</p>
                                <p class="text-sm font-black text-slate-900">ETB ${number_format(project.contract_budget)}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revised Budget</p>
                                <p class="text-sm font-black text-slate-900">ETB ${number_format(project.revised_budget)}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
                        <h4 class="text-xs font-black text-slate-900">Cost Variance</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Variance</p>
                                <p class="text-sm font-black ${project.cost_variance < 0 ? 'text-rose-600' : project.cost_variance > 0 ? 'text-emerald-600' : 'text-slate-900'}">ETB ${number_format(Math.abs(project.cost_variance))}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Allowable Cost</p>
                                <p class="text-sm font-black text-slate-900">ETB ${number_format(project.total_allowable_cost)}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Cost at Completion</p>
                                <p class="text-sm font-black text-slate-900">ETB ${number_format(project.cost_at_completion)}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
                        <h4 class="text-xs font-black text-slate-900">Timeline</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Baseline Start</p>
                                <p class="text-sm font-black text-slate-900">${project.baseline_start_date || '---'}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Baseline Finish</p>
                                <p class="text-sm font-black text-slate-900">${project.baseline_finish_date || '---'}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Start</p>
                                <p class="text-sm font-black text-slate-900">${project.actual_start_date || '---'}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Finish</p>
                                <p class="text-sm font-black text-slate-900">${project.actual_finish_date || '---'}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-xl p-4">
                        <a href="/admin/projects/${project.id}" class="flex items-center justify-center w-full px-4 py-2.5 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all">
                            View Full Details
                        </a>
                    </div>
                </div>
            `;

            drawer.classList.remove('hidden');
            setTimeout(() => {
                panel.classList.remove('translate-x-full');
            }, 10);
        }

        function closeDrillDown() {
            const drawer = document.getElementById('drillDownDrawer');
            const panel = document.getElementById('drawerPanel');
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                drawer.classList.add('hidden');
            }, 300);
        }

        function exportCSV() {
            const projects = getStatusFilteredProjects();
            if (projects.length === 0) {
                alert('No data to export');
                return;
            }
            const headers = ['Project Code', 'Project Name', 'Schedule Performance %', 'Schedule Status', 'Budget Index %', 'Budget Performance', 'Cost Performance %', 'Cost Performance', 'Overall Performance'];
            const rows = projects.map(p => [
                p.project_code,
                p.project_name,
                p.schedule_performance_percent + '%',
                p.schedule_status,
                p.budget_index_percent + '%',
                p.budget_performance,
                p.cost_performance_percent + '%',
                p.cost_performance,
                p.overall_performance,
            ]);
            const csvContent = [headers, ...rows].map(e => e.join(',')).join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `dashboard-export-${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
        }

        function exportPDF() {
            window.print();
        }

        function exportChartPNG() {
            if (chartInstance) {
                chartInstance.dataURI().then(({ imgURI }) => {
                    const link = document.createElement('a');
                    link.href = imgURI;
                    link.download = `chart-${currentTab}-${new Date().toISOString().split('T')[0]}.png`;
                    link.click();
                });
            }
        }

        function number_format(number) {
            return new Intl.NumberFormat('en-US').format(Math.round(number));
        }

        let analyticsCharts = {};
        let activeChartFilter = null;

        const analyticsConfig = {
            schedule_performance: {
                title: 'Schedule Performance',
                description: 'Completed projects schedule performance',
                categories: [
                    { key: 'ahead', label: 'Ahead', abbr: 'AHD', color: '#10b981' },
                    { key: 'on_schedule', label: 'ON', abbr: 'ONS', color: '#00ADC5' },
                    { key: 'behind', label: 'Behind', abbr: 'BHD', color: '#f43f5e' },
                ],
                totalLabel: 'Completed',
                filterParam: 'schedule_filter',
                emptyMessage: 'No completed projects available for schedule review.',
            },
            delivery_method: {
                title: 'Delivery Method',
                description: 'Completed projects delivery approach.',
                categories: [
                    { key: 'DB', label: 'DB', color: '#10b981' },
                    { key: 'DBB', label: 'DBB', color: '#00ADC5' },
                ],
                totalLabel: 'Completed',
                filterParam: 'delivery_method',
                emptyMessage: 'No completed projects available for delivery method breakdown.',
            },
            contract_variation: {
                title: 'Contract Variation',
                description: 'Projects with contract additions, omissions, or no change.',
                categories: [
                    { key: 'addition', label: 'Addition', abbr: 'ADD', color: '#00ADC5' },
                    { key: 'omission', label: 'Omission', abbr: 'OMT', color: '#f59e0b' },
                    { key: 'no_variation', label: 'No Variation', abbr: 'NON', color: '#10b981' },
                ],
                totalLabel: 'Total',
                filterParam: 'variation_type',
                emptyMessage: 'No project variation data available.',
            },
            consultancy_sector: {
                title: 'Consultancy Sector',
                description: 'Completed projects by consultancy sector',
                categories: [
                    { key: 'private', label: 'Private', abbr: 'PRV', color: '#10b981' },
                    { key: 'client_engineering', label: 'Client Engineering', abbr: 'CET', color: '#6366f1' },
                    { key: 'government', label: 'Government', abbr: 'GOV', color: '#f59e0b' },
                ],
                totalLabel: 'Completed',
                filterParam: 'sector',
                emptyMessage: 'No completed projects available for sector breakdown.',
            },
            cost_performance: {
                title: 'Cost Performance',
                description: 'Completed projects cost health against allowable limits.',
                categories: [
                    { key: 'below', label: 'Below', abbr: 'BLW', color: '#10b981' },
                    { key: 'with_allowable', label: 'Within', abbr: 'WTH', color: '#00ADC5' },
                    { key: 'above', label: 'Above', abbr: 'ABV', color: '#f43f5e' },
                ],
                totalLabel: 'Below/With/Above',
                filterParam: 'cost_filter',
                emptyMessage: 'No completed projects available for cost performance review.',
            },
        };

        function renderAnalyticsGrid() {
            const grid = document.getElementById('analyticsGrid');
            if (!grid) return;

            const analytics = window.latestAnalytics || {};
            grid.innerHTML = '';

            Object.keys(analyticsConfig).forEach(chartId => {
                const config = analyticsConfig[chartId];
                const data = analytics[chartId] || {};
                const total = data.total || 0;
                const accentColor = config.categories[0]?.color || '#6366f1';

                const card = document.createElement('div');
                card.className = 'analytics-card group relative bg-white rounded-2xl border shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col analytics-card-pie';
                card.innerHTML = `
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none analytics-card-hover-overlay"></div>
                    <div class="relative z-10 flex flex-col flex-1 p-2.5">
                        <div class="flex items-center justify-between mb-1.5">
                            <div>
                                <h4 class="text-[9px] font-black text-slate-900 uppercase tracking-widest leading-none">${config.title}</h4>
                            </div>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="flex items-center justify-center" style="width: 200px; height: 200px;">
                                <div id="chart-${chartId}" class="w-full h-full relative" style="z-index: 1; overflow: visible !important;"></div>
                            </div>
                            <div id="legend-${chartId}" class="analytics-legend-wrapper flex flex-row flex-wrap items-center justify-center gap-1"></div>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });

            Object.keys(analyticsConfig).forEach(chartId => {
                createPieChart(chartId, analytics[chartId] || {});
            });

            if (window.lucide) lucide.createIcons();
        }

        function getInsight(chartId, data, total) {
            if (total === 0) return 'No data available.';

            const config = analyticsConfig[chartId];
            let maxKey = null;
            let maxCount = 0;
            config.categories.forEach(cat => {
                const count = data[cat.key] || 0;
                if (count > maxCount) {
                    maxCount = count;
                    maxKey = cat;
                }
            });

            if (!maxKey || maxCount === 0) return 'No data available.';

            const percent = total > 0 ? Math.round((maxCount / total) * 100) : 0;
            if (percent === 100) return `All projects are ${maxKey.label.toLowerCase()}.`;
            if (percent >= 50) return `Most projects are ${maxKey.label.toLowerCase()}.`;
            return `${maxKey.label} is the largest group at ${percent}%.`;
        }

        function createPieChart(chartId, data) {
            const config = analyticsConfig[chartId];
            const total = data.total || 0;
            const chartEl = document.getElementById(`chart-${chartId}`);
            const legendEl = document.getElementById(`legend-${chartId}`);

            if (!chartEl) return;

            if (total === 0) {
                chartEl.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-slate-400">
                        <i data-lucide="pie-chart" class="w-6 h-6 mb-1 text-slate-300"></i>
                        <p class="text-[8px] font-black uppercase tracking-widest">${config.emptyMessage}</p>
                    </div>
                `;
                if (window.lucide) lucide.createIcons();
                if (legendEl) legendEl.innerHTML = '';
                return;
            }

            const series = config.categories.map(cat => data[cat.key] || 0);
            const labels = config.categories.map(cat => cat.label);
            const colors = config.categories.map(cat => cat.color);

            const allZero = series.every(v => v === 0);
            if (allZero) {
                chartEl.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-slate-400">
                        <i data-lucide="pie-chart" class="w-6 h-6 mb-1 text-slate-300"></i>
                        <p class="text-[8px] font-black uppercase tracking-widest">${config.emptyMessage}</p>
                    </div>
                `;
                if (window.lucide) lucide.createIcons();
                if (legendEl) legendEl.innerHTML = '';
                return;
            }

            if (analyticsCharts[chartId]) {
                const oldTooltip = chartEl.parentElement.querySelector('.pie-custom-tooltip');
                if (oldTooltip) oldTooltip.remove();
                analyticsCharts[chartId].destroy();
            }

            const wrapper = chartEl.parentElement;
            const customTooltip = document.createElement('div');
            customTooltip.className = 'pie-custom-tooltip';
            customTooltip.style.cssText = 'position: absolute; pointer-events: none; z-index: 50; display: none; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); padding: 10px 14px; min-width: 200px; font-family: Inter, sans-serif; transition: opacity 0.15s ease;';
            wrapper.style.position = wrapper.style.position || 'relative';
            wrapper.appendChild(customTooltip);

            analyticsCharts[chartId] = new ApexCharts(chartEl, {
                chart: {
                    type: 'pie',
                    height: '100%',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500,
                        dynamicAnimation: { enabled: true, speed: 400 }
                    },
                    events: {
                        dataPointSelection: (event, chartContext, { dataPointIndex }) => {
                            const clickedCat = config.categories[dataPointIndex];
                            if (clickedCat) {
                                onChartSegmentClick(chartId, clickedCat.key, clickedCat.label);
                            }
                        },
                        mouseMove: (event, chartContext, opts) => {
                            const dataPointIndex = opts && opts.dataPointIndex;
                            chartContext.el.style.cursor = typeof dataPointIndex === 'number' && dataPointIndex >= 0 ? 'pointer' : 'default';

                            if (typeof dataPointIndex === 'number' && dataPointIndex >= 0 && dataPointIndex < config.categories.length) {
                                const cat = config.categories[dataPointIndex];
                                const count = data[cat.key] || 0;
                                const percent = total > 0 ? Math.round((count / total) * 100) : 0;
                                customTooltip.innerHTML = `
                                    <div style="font-weight: 800; font-size: 11px; color: #0f172a; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">${cat.label}</div>
                                    <div style="font-weight: 800; font-size: 22px; color: ${cat.color}; margin-bottom: 4px;">${count} <span style="font-size: 11px; font-weight: 700; color: #64748b;">Projects</span></div>
                                    <div style="font-weight: 700; font-size: 11px; color: #64748b;">${percent}% of ${total}</div>
                                `;
                                customTooltip.style.display = 'block';

                                const rect = wrapper.getBoundingClientRect();
                                let left = event.clientX - rect.left + 12;
                                let top = event.clientY - rect.top - 12;

                                customTooltip.style.left = left + 'px';
                                customTooltip.style.top = top + 'px';
                            } else {
                                customTooltip.style.display = 'none';
                            }
                        },
                        mouseLeave: () => {
                            customTooltip.style.display = 'none';
                        }
                    },
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 2,
                        blur: 4,
                        opacity: 0.15,
                    }
                },
                plotOptions: {
                    pie: {
                        startAngle: 0,
                        endAngle: 360,
                        expandOnClick: false,
                        dataLabels: {
                            offset: -5
                        }
                    }
                },
                series: series,
                labels: labels,
                colors: colors,
                legend: { show: false },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['#fff']
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        if (val < 8) return '';
                        return Math.round(val) + '%';
                    },
                    style: {
                        fontSize: '10px',
                        fontWeight: 700,
                        colors: ['#ffffff']
                    },
                    dropShadow: {
                        enabled: false
                    }
                },
                tooltip: { enabled: false },
                responsive: [
                    {
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 200
                            },
                            dataLabels: {
                                style: {
                                    fontSize: '9px'
                                }
                            }
                        }
                    }
                ]
            });

            analyticsCharts[chartId].render();
            buildLegend(chartId, data, config);
        }

        function buildLegend(chartId, data, config) {
            const legendEl = document.getElementById(`legend-${chartId}`);
            if (!legendEl) return;

            legendEl.innerHTML = config.categories.map(cat => {
                const count = data[cat.key] || 0;
                const isActive = activeChartFilter && activeChartFilter.chart === chartId && activeChartFilter.value === cat.key;
                const activeClass = isActive ? 'analytics-legend-active' : 'analytics-legend-inactive';
                return `
                    <div class="analytics-legend-item flex items-center gap-0.5 py-0.5 cursor-pointer hover:bg-slate-50 rounded transition-all px-1 whitespace-nowrap ${activeClass}" onclick="onChartSegmentClick('${chartId}', '${cat.key}', '${cat.label}')">
                        <span class="w-1 h-1 rounded-full flex-shrink-0" style="background-color: ${cat.color};"></span>
                        <span class="text-[8px] font-black text-slate-600 uppercase tracking-wider truncate">${cat.label}</span>
                        <span class="text-[8px] font-black text-slate-900 ml-auto">${count}</span>
                    </div>
                `;
            }).join('');
        }

        function onChartSegmentClick(chartId, value, label) {
            if (activeChartFilter && activeChartFilter.chart === chartId && activeChartFilter.value === value) {
                activeChartFilter = null;
            } else {
                activeChartFilter = { chart: chartId, value: value, label: label };
            }

            if (chartId === 'consultancy_sector') {
                const sectorSelect = document.getElementById('filterSector');
                if (sectorSelect) sectorSelect.value = value;
            }

            updateClearFilterButton();
            loadDashboardData();
        }

        function updateClearFilterButton() {
            const btn = document.getElementById('clearChartFilterBtn');
            if (!btn) return;
            if (activeChartFilter) {
                btn.classList.remove('hidden');
                btn.classList.add('flex');
            } else {
                btn.classList.add('hidden');
                btn.classList.remove('flex');
            }
        }

        function clearChartFilter() {
            activeChartFilter = null;
            updateClearFilterButton();
            loadDashboardData();
        }

        function refreshAnalytics() {
            loadDashboardData();
        }

        function exportAnalyticsCSV() {
            const analytics = window.latestAnalytics || {};
            if (!analytics.schedule_performance || analytics.schedule_performance.total === 0) {
                alert('No analytics data to export');
                return;
            }

            let csvContent = 'Chart,Category,Count,Percentage\n';
            Object.keys(analyticsConfig).forEach(chartId => {
                const config = analyticsConfig[chartId];
                const data = analytics[chartId] || {};
                const total = data.total || 0;
                config.categories.forEach(cat => {
                    const count = data[cat.key] || 0;
                    const percent = total > 0 ? Math.round((count / total) * 100) : 0;
                    csvContent += `${config.title},${cat.label},${count},${percent}%\n`;
                });
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `executive-analytics-${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
        }

        let comparativeCharts = {};

        function renderComparativeAnalytics() {
            const grid = document.getElementById('comparativeGrid');
            if (!grid) return;
            const data = window.latestComparativeAnalytics || {};
            grid.innerHTML = '';

            Object.keys(data).forEach(chartId => {
                const chartData = data[chartId];
                const card = document.createElement('div');
                card.className = 'comparative-card bg-white rounded-[18px] border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-250 overflow-hidden flex flex-col';
                
                const accentColor = chartData.series?.[0]?.counts ? '#00ADC5' : '#6366f1';
                card.innerHTML = `
                    <div class="h-0.5 w-full" style="background: linear-gradient(to right, ${accentColor}, ${accentColor}66);"></div>
                    <div class="p-4 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="text-[9px] font-black text-slate-900 uppercase tracking-widest">${chartData.title}</h4>
                                <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-0.5">${chartData.description}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <button onclick="exportComparativeChartPNG('${chartId}')" class="w-7 h-7 rounded-lg hover:bg-slate-50 flex items-center justify-center text-slate-300 hover:text-slate-600 transition-colors" title="Export PNG">
                                    <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                </button>
                                <button onclick="toggleComparativeFullscreen('${chartId}')" class="w-7 h-7 rounded-lg hover:bg-slate-50 flex items-center justify-center text-slate-300 hover:text-slate-600 transition-colors" title="Fullscreen">
                                    <i data-lucide="maximize" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                        <div id="comparative-legend-${chartId}" class="mt-3 flex flex-wrap items-center gap-3"></div>
                        <div class="flex-1 min-h-[280px] mt-2">
                            <div id="comparative-chart-${chartId}" class="w-full h-full"></div>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });

            Object.keys(data).forEach(chartId => {
                createComparativeChart(chartId, data[chartId]);
            });

            if (window.lucide) lucide.createIcons();
        }

        function createComparativeChart(chartId, chartData) {
            const chartEl = document.getElementById(`comparative-chart-${chartId}`);
            const legendEl = document.getElementById(`comparative-legend-${chartId}`);
            if (!chartEl) return;

            if (comparativeCharts[chartId]) {
                comparativeCharts[chartId].destroy();
            }

            const series = chartData.series.map(s => ({
                name: s.name,
                data: s.values,
            }));

            const colors = {
                'Behind Schedule': ['#EF4444', '#F87171'],
                'Ahead of Schedule': ['#22C55E', '#4ADE80'],
                'On Schedule': ['#f59e0b', '#f59e0b'],
                'Under Budget': ['#22C55E', '#4ADE80'],
                'On Budget': ['#f59e0b', '#f59e0b'],
                'Above Budget': ['#EF4444', '#F87171'],
                'Below Allowable Cost': ['#22C55E', '#4ADE80'],
                'Within Allowable Cost': ['#FFA500', '#FFA500'],
                'Above Allowable Cost': ['#EF4444', '#F87171'],
                'Average SPI %': ['#6366f1', '#6366f1'],
                'Projects': ['#00ADC5', '#00ADC5'],
            };

            const isHorizontal = chartData.horizontal === true;
            const barColors = chartData.series.map(s => colors[s.name] || ['#00ADC5', '#00ADC5']);
            const groupTotals = (chartData.summaries || []).map(s => s.total || 0);
            const isSingleSeries = chartData.series.length === 1;
            const palette = ['#10b981', '#f59e0b', '#00ADC5', '#6366f1', '#EF4444', '#8b5cf6', '#EC4899', '#14b8a6'];
            const pointColors = isSingleSeries
                ? chartData.xaxis.map((_, i) => palette[i % palette.length])
                : barColors.map(c => c[0]);

            comparativeCharts[chartId] = new ApexCharts(chartEl, {
                chart: {
                    type: 'bar',
                    height: '100%',
                    stacked: false,
                    labels: isHorizontal ? chartData.xaxis : undefined,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500,
                        dynamicAnimation: { enabled: true, speed: 400 }
                    },
                    toolbar: { show: false },
                    background: 'transparent',
                    events: {
                        dataPointSelection: (event, chartContext, { seriesIndex, dataPointIndex, w }) => {
                            const category = w.globals.labels[dataPointIndex];
                            const seriesName = w.globals.seriesNames[seriesIndex];
                            const value = w.globals.series[seriesIndex][dataPointIndex];
                            const count = chartData.series[seriesIndex]?.counts?.[dataPointIndex] ?? value;
                            const groupTotal = groupTotals[dataPointIndex] || 0;
                            const percent = groupTotal > 0 ? Math.round((count / groupTotal) * 100) : 0;
                            openComparativeDrillDown(category, seriesName, count, percent, groupTotal);
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: isHorizontal,
                        columnWidth: isHorizontal ? '80%' : '60%',
                        borderRadius: 8,
                        borderRadiusApplication: isHorizontal ? 'bottom' : 'top',
                        distributed: isSingleSeries,
                    }
                },
                series: series,
                xaxis: isHorizontal ? {
                    categories: chartData.xaxis,
                    labels: {
                        style: { fontSize: '9px', fontWeight: 700, colors: '#475569' },
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                } : {
                    categories: chartData.xaxis,
                    labels: {
                        style: { fontSize: '9px', fontWeight: 700, colors: '#475569' },
                        rotate: -30,
                        rotateAlways: false,
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: isHorizontal ? {
                    min: 0,
                    max: 100,
                    tickAmount: 5,
                    labels: {
                        style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' },
                        formatter: (val) => val + '%'
                    }
                } : {
                    min: 0,
                    max: 100,
                    tickAmount: 5,
                    labels: {
                        style: { fontSize: '9px', fontWeight: 600, colors: '#64748b' },
                        formatter: (val) => val + '%'
                    }
                },
                colors: pointColors,
                fill: {
                    type: isSingleSeries ? 'solid' : 'gradient',
                    gradient: isSingleSeries ? undefined : {
                        shade: 'light',
                        type: isHorizontal ? 'horizontal' : 'vertical',
                        shadeIntensity: 0.2,
                        gradientToColors: barColors.map(c => c[1]),
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 0.9,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '9px',
                        fontWeight: 800,
                        colors: ['#fff']
                    },
                    formatter: (val) => val > 0 ? (isHorizontal ? val : val + '%') : '',
                    textAnchor: isHorizontal ? 'start' : 'middle',
                    offsetX: isHorizontal ? 6 : 0,
                    offsetY: isHorizontal ? 0 : -16,
                },
                legend: { show: false },
                tooltip: {
                    enabled: true,
                    theme: 'light',
                    custom: function({ series, seriesIndex, dataPointIndex, w }) {
                        const category = w.globals.labels[dataPointIndex];
                        const seriesName = w.globals.seriesNames[seriesIndex];
                        const percent = series[seriesIndex][dataPointIndex];
                        const count = chartData.series[seriesIndex]?.counts?.[dataPointIndex] ?? 0;
                        const groupTotal = groupTotals[dataPointIndex] || 0;
                        const color = isSingleSeries ? pointColors[dataPointIndex] : (barColors[seriesIndex]?.[0] || '#6366f1');
                        return `
                            <div style="padding: 12px 16px; font-family: 'Inter', sans-serif; min-width: 220px; border-radius: 12px; background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                                <div style="font-weight: 800; font-size: 11px; color: #0f172a; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em;">${category}</div>
                                <div style="font-weight: 800; font-size: 24px; color: ${color}; margin-bottom: 4px;">${percent}${isHorizontal ? '' : '%'}</div>
                                <div style="font-weight: 700; font-size: 11px; color: #475569; margin-bottom: 8px;">${count} Projects • ${groupTotal} in group</div>
                                <div style="font-size: 9px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Click to view projects</div>
                            </div>
                        `;
                    }
                },
                grid: {
                    borderColor: '#EEF2F7',
                    strokeDashArray: 4,
                    padding: { left: 8, right: 12, top: 12, bottom: 8 },
                    xaxis: { lines: { show: isHorizontal } },
                    yaxis: { lines: { show: !isHorizontal } },
                },
                markers: {
                    shape: 'circle',
                    size: 0,
                },
                responsive: [
                    {
                        breakpoint: 768,
                        options: {
                            plotOptions: {
                                bar: {
                                    columnWidth: isHorizontal ? '85%' : '65%',
                                }
                            },
                            dataLabels: {
                                offsetY: isHorizontal ? 0 : -12,
                                offsetX: isHorizontal ? 4 : 0,
                                style: { fontSize: '8px' }
                            },
                            xaxis: isHorizontal ? {} : {
                                labels: {
                                    rotate: -45,
                                }
                            }
                        }
                    }
                ]
            });

            comparativeCharts[chartId].render();
            buildComparativeLegend(chartId, chartData, barColors);
        }

        function buildComparativeLegend(chartId, chartData, barColors) {
            const legendEl = document.getElementById(`comparative-legend-${chartId}`);
            if (!legendEl) return;
            const isSingleSeries = chartData.series.length === 1;
            const palette = ['#10b981', '#f59e0b', '#00ADC5', '#6366f1', '#EF4444', '#8b5cf6', '#EC4899', '#14b8a6'];

            if (isSingleSeries) {
                legendEl.innerHTML = chartData.xaxis.map((label, i) => {
                    const color = palette[i % palette.length];
                    const count = chartData.series[0]?.counts?.[i] ?? chartData.series[0]?.data?.[i] ?? 0;
                    return `
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-sm" style="background-color: ${color};"></span>
                            <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">${label}</span>
                            <span class="text-[8px] font-black text-slate-400">${count} projects</span>
                        </div>
                    `;
                }).join('');
            } else {
                legendEl.innerHTML = chartData.series.map((s, i) => {
                    const color = barColors[i]?.[0] || '#6366f1';
                    const totalCount = chartData.series[i]?.counts?.reduce((a, b) => a + b, 0) || 0;
                    return `
                        <div class="flex items-center gap-1.5 cursor-pointer hover:opacity-80 transition-opacity" onclick="toggleComparativeSeries('${chartId}', ${i})">
                            <span class="w-2.5 h-2.5 rounded-sm" style="background-color: ${color};"></span>
                            <span class="text-[9px] font-black text-slate-600 uppercase tracking-wider">${s.name}</span>
                            <span class="text-[8px] font-black text-slate-400">${totalCount} projects</span>
                        </div>
                    `;
                }).join('');
            }
        }

        function toggleComparativeSeries(chartId, seriesIndex) {
            const chart = comparativeCharts[chartId];
            if (!chart) return;
            const series = chart.w.config.series;
            if (series[seriesIndex].hidden) {
                chart.toggleSeries(seriesIndex);
            } else {
                chart.hideSeries(seriesIndex);
            }
        }

        function openComparativeDrillDown(category, seriesName, count, percent, groupTotal) {
            const drawer = document.getElementById('drillDownDrawer');
            const panel = document.getElementById('drawerPanel');
            document.getElementById('drawerProjectName').textContent = category;
            document.getElementById('drawerProjectCode').textContent = seriesName;

            const matchingKey = Object.keys(window.latestComparativeAnalytics || {}).find(key => {
                const d = window.latestComparativeAnalytics[key];
                return d.xaxis.includes(category);
            });
            const chartData = matchingKey ? window.latestComparativeAnalytics[matchingKey] : null;

            document.getElementById('drawerContent').innerHTML = `
                <div class="space-y-4">
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">${category}</p>
                        <p class="text-2xl font-black text-slate-900">${count} <span class="text-xs font-bold text-slate-500">Projects</span></p>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">${percent}% of ${groupTotal}</p>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-xl p-4">
                        <h4 class="text-xs font-black text-slate-900 mb-2">Performance Category</h4>
                        <p class="text-sm font-black text-slate-700">${seriesName}</p>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-xl p-4">
                        <h4 class="text-xs font-black text-slate-900 mb-2">Summary</h4>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">${groupTotal} total projects in this category. ${count} fall under ${seriesName}.</p>
                    </div>
                </div>
            `;

            drawer.classList.remove('hidden');
            setTimeout(() => {
                panel.classList.remove('translate-x-full');
            }, 10);
        }

        function toggleComparativeFullscreen(chartId) {
            const chart = comparativeCharts[chartId];
            if (!chart) return;
            const container = document.getElementById(`comparative-chart-${chartId}`);
            if (!document.fullscreenElement) {
                container.requestFullscreen().catch(() => {});
            } else {
                document.exitFullscreen();
            }
        }

        function exportComparativeChartPNG(chartId) {
            const chart = comparativeCharts[chartId];
            if (!chart) return;
            chart.dataURI().then(({ imgURI }) => {
                const link = document.createElement('a');
                link.href = imgURI;
                link.download = `comparative-${chartId}-${new Date().toISOString().split('T')[0]}.png`;
                link.click();
            });
        }

        function exportComparativeCSV() {
            const data = window.latestComparativeAnalytics || {};
            if (!Object.keys(data).length) {
                alert('No comparative data to export');
                return;
            }

            let csvContent = 'Chart,Category,Series,Count,Percentage\n';
            Object.keys(data).forEach(chartId => {
                const chartData = data[chartId];
                chartData.series.forEach(s => {
                    s.counts?.forEach((count, idx) => {
                        const category = chartData.xaxis[idx] || '';
                        const percent = s.values[idx] || 0;
                        csvContent += `${chartData.title},"${category}","${s.name}",${count},${percent}%\n`;
                    });
                });
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `comparative-analytics-${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
        }

        function refreshComparative() {
            loadDashboardData();
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDrillDown();
            }
        });
    </script>

    <style>
        .dashboard-kpi-card {
            position: relative;
            overflow: hidden;
        }
        .dashboard-kpi-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(248,250,252,0.4));
            pointer-events: none;
            z-index: 0;
        }
        .dashboard-kpi-card > * {
            position: relative;
            z-index: 1;
        }
        .dashboard-kpi-card:hover {
            transform: translateY(-4px);
        }
        .counter {
            display: inline-block;
        }

        .analytics-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 300px;
            position: relative;
            overflow: visible;
        }
        .analytics-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        #analyticsGrid .apexcharts-canvas {
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }
        #analyticsGrid .apexcharts-pie {
            max-width: 100%;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.08));
            transition: filter 0.3s ease;
        }
        #analyticsGrid .analytics-card:hover .apexcharts-pie {
            filter: drop-shadow(0 8px 12px rgba(0,0,0,0.15));
        }
        #analyticsGrid .apexcharts-pie-label {
            font-family: 'Inter', sans-serif !important;
            font-size: 10px !important;
        }
        #analyticsGrid .analytics-card {
            background: #FFFFFF;
        }
        #analyticsGrid .analytics-card-pie,
        #analyticsGrid .analytics-card-pie * {
            border: none !important;
            outline: none !important;
        }
        #analyticsGrid .apexcharts-canvas,
        #analyticsGrid .apexcharts-canvas > *,
        #analyticsGrid .apexcharts-tooltip,
        #analyticsGrid .apexcharts-tooltip * {
            overflow: visible !important;
        }
        #analyticsGrid .apexcharts-tooltip {
            z-index: 50 !important;
        }
        .analytics-card-pie .apexcharts-svg,
        .analytics-card-pie .apexcharts-canvas {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        #statusPieChartInner,
        #statusPieChartInner * {
            border: none !important;
            outline: none !important;
        }
        .analytics-card-pie {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
        }
        .analytics-legend-item {
            transition: all 0.15s ease;
            line-height: 1;
            border: 1px solid transparent;
        }
        .analytics-legend-item:hover {
            background: #f8fafc;
        }
        .analytics-legend-wrapper {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 3px;
            align-items: center;
            justify-content: center;
        }
        .analytics-insight {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .analytics-card {
                height: auto;
                min-height: 300px;
            }
            .analytics-card > div:last-child > div:first-child {
                width: 180px;
                height: 180px;
            }
        }
        @media (max-width: 480px) {
            .analytics-card > div:last-child > div:first-child {
                width: 160px;
                height: 160px;
            }
        }

        #chartContainer {
            height: 420px;
            overflow: hidden;
        }
        #chartContainer .apexcharts-bar-area {
            filter: drop-shadow(0 3px 4px rgba(0,0,0,0.12));
            transition: filter 0.2s ease;
        }
        #chartContainer .apexcharts-bar-area:hover {
            filter: drop-shadow(0 5px 8px rgba(0,0,0,0.2));
        }
        #chartContainer .apexcharts-gridline {
            stroke: #EEF2F7;
            stroke-width: 1;
            stroke-dasharray: 4;
        }
        #chartContainer .apexcharts-xaxistooltip, #chartContainer .apexcharts-yaxistooltip {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .comparative-card {
            position: relative;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .comparative-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        #comparativeGrid .apexcharts-canvas {
            margin: 0 auto;
        }
        #comparativeGrid .apexcharts-bar-area {
            filter: drop-shadow(0 2px 3px rgba(0,0,0,0.08));
        }
        #comparativeGrid .apexcharts-bar-area:hover {
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));
        }
        #comparativeGrid .apexcharts-gridline {
            stroke: #EEF2F7;
            stroke-width: 1;
            stroke-dasharray: 4;
        }
        .comparative-summary-chip {
            background: linear-gradient(to bottom, #FFFFFF, #F8FAFC);
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .max-w-\\[1700px\\] > * {
                visibility: visible;
            }
            .max-w-\\[1700px\\] {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                max-width: 100%;
            }
            #drillDownDrawer, .fixed, button {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
