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
            @keyframes dashboardHeadingEnter {
                from { opacity: 0; transform: translateY(12px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes dashboardHeadingAccent {
                from { transform: scaleX(0); opacity: 0.35; }
                to { transform: scaleX(1); opacity: 1; }
            }
            .dashboard-page-heading {
                animation: dashboardHeadingEnter 650ms cubic-bezier(0.2, 0.7, 0.2, 1) both;
            }
            .dashboard-page-heading h1 {
                background: linear-gradient(100deg, #0f172a 0%, #164e63 70%, #0891b2 100%);
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .dashboard-page-heading-accent {
                width: 3.5rem;
                height: 3px;
                margin-top: 0.65rem;
                border-radius: 999px;
                background: linear-gradient(90deg, #06b6d4, #6366f1);
                transform-origin: left;
                animation: dashboardHeadingAccent 800ms 180ms cubic-bezier(0.2, 0.7, 0.2, 1) both;
            }
            .dashboard-page-heading p {
                animation: dashboardHeadingEnter 650ms 100ms cubic-bezier(0.2, 0.7, 0.2, 1) both;
            }
            @media (prefers-reduced-motion: reduce) {
                .dashboard-page-heading,
                .dashboard-page-heading p,
                .dashboard-page-heading-accent {
                    animation: none;
                }
            }
            .kpi-card-total { border: 1px solid #cbd5e1 !important; background-color: rgba(224, 242, 254, 0.4) !important; }
            .kpi-card-total > div:last-child { padding: 14px !important; }
            .kpi-card-completed { border: 1px solid #cbd5e1 !important; background-color: rgba(167, 243, 208, 0.35) !important; }
            .kpi-card-completed > div:last-child { padding: 14px !important; }
            .kpi-card-in-progress { border: 1px solid #cbd5e1 !important; background-color: rgba(253, 230, 138, 0.35) !important; }
            .kpi-card-in-progress > div:last-child { padding: 14px !important; }
            .kpi-card-suspended { border: 1px solid #cbd5e1 !important; background-color: rgba(254, 205, 211, 0.35) !important; }
            .kpi-card-suspended > div:last-child { padding: 14px !important; }
            .kpi-badge-total { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #0e7490 !important; }
            .kpi-badge-completed { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #047857 !important; }
            .kpi-badge-in-progress { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #b45309 !important; }
            .kpi-badge-suspended { background-color: rgba(255, 255, 255, 0.85) !important; border: 1px solid #cbd5e1 !important; color: #be123c !important; }
            .kpi-icon-total { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #06b6d4 !important; }
            .kpi-icon-completed { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #10b981 !important; }
            .kpi-icon-in-progress { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #f59e0b !important; }
            .kpi-icon-suspended { background-color: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #f43f5e !important; }
            .performance-card { border: 1px solid #cbd5e1 !important; }
            .kpi-progress-track { background-color: rgba(255,255,255,0.6) !important; }
            .kpi-insight-text { color: #64748b !important; margin-top: 4px !important; }
            .kpi-card-total {
                background:
                    radial-gradient(circle at 50% 45%, rgba(6, 182, 212, 0.08), transparent 48%),
                    linear-gradient(145deg, #ffffff 0%, #f0f9ff 100%) !important;
            }
            .kpi-total-chart {
                min-height: 96px;
                height: 96px;
            }
            .kpi-total-status {
                border-top: 1px solid rgba(203, 213, 225, 0.65);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.25rem;
                padding-top: 0.4rem;
            }
            #executiveKpiRow .dashboard-kpi-card {
                display: flex;
                flex-direction: column;
            }
            #executiveKpiRow .dashboard-kpi-card > div:last-child {
                flex: 1;
                display: flex;
                flex-direction: column;
            }
            .analytics-legend-inactive { background-color: rgba(255,255,255,0.55) !important; border: 1px solid #cbd5e1 !important; }
            .analytics-legend-active { background-color: rgba(255,255,255,0.85) !important; border: 1px solid #cbd5e1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.06) !important; }
            .analytics-card-hover-overlay { background: rgba(248,250,252,0.5) !important; }
            .dashboard-filters {
                border: 1px solid #e2e8f0;
                background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
                box-shadow: 0 12px 28px -24px rgba(15, 23, 42, 0.45), 0 2px 6px rgba(15, 23, 42, 0.04);
            }
            .dashboard-filter-field { display: block; min-width: 0; }
            .dashboard-filter-label {
                display: block;
                margin: 0 0 0.4rem 0.15rem;
                color: #64748b;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: 0.09em;
                line-height: 1;
                text-transform: uppercase;
            }
            .dashboard-filter-control {
                height: 40px;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                background-color: #ffffff;
                color: #334155;
                font-size: 10px;
                font-weight: 700;
                transition: border-color 150ms ease, box-shadow 150ms ease, background-color 150ms ease;
            }
            .dashboard-filter-control:hover { border-color: #cbd5e1; background-color: #ffffff; }
            .dashboard-filter-control:focus {
                border-color: rgba(6, 182, 212, 0.6);
                outline: none;
                box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.12);
            }
        </style>

        <!-- Dashboard Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="dashboard-page-heading">
                <h1 class="text-2xl sm:text-[1.75rem] font-black tracking-tight font-outfit">Project Performance Overview</h1>
                <div class="dashboard-page-heading-accent"></div>
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.16em] mt-2">Real-time portfolio health metrics</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="dashboard-filters w-full rounded-2xl p-4 sm:p-5">
            <div class="mb-4 flex flex-col gap-2 border-b border-slate-100 pb-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                        <i data-lucide="sliders-horizontal" class="h-4 w-4"></i>
                    </span>
                    <div>
                        <h2 class="text-xs font-extrabold tracking-tight text-slate-800">Project Filters</h2>
                        <p class="mt-0.5 text-[8px] font-semibold uppercase tracking-[0.12em] text-slate-400">Refine your dashboard view</p>
                    </div>
                </div>
                <button onclick="clearFilters()" class="inline-flex items-center justify-center gap-1.5 self-start rounded-lg px-2.5 py-1.5 text-[9px] font-bold text-slate-500 transition hover:bg-rose-50 hover:text-rose-600 sm:self-auto" title="Clear Filters">
                    <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i>
                    Reset filters
                </button>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-6">
                <label class="dashboard-filter-field relative xl:col-span-2">
                    <span class="dashboard-filter-label">Search projects</span>
                    <i data-lucide="search" class="absolute left-3.5 top-[2.35rem] h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="searchInput" placeholder="Search by project name or code..."
                        oninput="handleSearch(this.value)"
                        class="dashboard-filter-control w-full pl-10 pr-3">
                </label>

                <label class="dashboard-filter-field relative">
                    <span class="dashboard-filter-label">Project sector</span>
                    <div class="relative">
                        <select id="filterSector" onchange="applyFilters()" class="dashboard-filter-control w-full appearance-none pl-3 pr-9">
                            <option value="all">All Sectors</option>
                        </select>
                        <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </label>

                <label class="dashboard-filter-field relative">
                    <span class="dashboard-filter-label">Delivery method</span>
                    <div class="relative">
                        <select id="filterDeliveryMethod" onchange="applyFilters()" class="dashboard-filter-control w-full appearance-none pl-3 pr-9">
                            <option value="all">All Delivery Methods</option>
                        </select>
                        <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </label>

                <label class="dashboard-filter-field relative">
                    <span class="dashboard-filter-label">Consultant type</span>
                    <div class="relative">
                        <select id="filterConsultantType" onchange="applyFilters()" class="dashboard-filter-control w-full appearance-none pl-3 pr-9">
                            <option value="all">All Consultant Types</option>
                        </select>
                        <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </label>

                <label class="dashboard-filter-field relative">
                    <span class="dashboard-filter-label">Project status</span>
                    <div class="relative">
                        <select id="filterLifecycleStatus" onchange="applyFilters()" class="dashboard-filter-control w-full appearance-none pl-3 pr-9">
                            <option value="all">All Project Statuses</option>
                            <option value="completed">Completed</option>
                            <option value="in progress">In Progress</option>
                        </select>
                        <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </label>
            </div>
        </div>

        <!-- Executive KPI Summary Cards -->
        <div id="executiveKpiRow" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
            <!-- Total Projects -->
            <div class="dashboard-kpi-card group relative rounded-2xl border shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden cursor-pointer kpi-card-total" onclick="filterByStatus('all')">
                <div class="relative z-10 flex h-full flex-col p-3.5">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl border flex items-center justify-center shadow-sm kpi-icon-total">
                                <i data-lucide="folder-open" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Portfolio Overview</p>
                                <p class="text-xs font-black text-slate-800">Total Projects</p>
                            </div>
                        </div>
                        <span class="counter text-2xl font-black text-slate-900 tracking-tighter leading-none" data-target="0">0</span>
                    </div>
                    <div class="kpi-total-chart relative my-1 w-full">
                        <div id="statusPieChartInner" class="h-full w-full"></div>
                        <span id="statusPieTotal" class="pointer-events-none absolute inset-0 z-10 flex items-center justify-center text-sm font-extrabold text-slate-900"></span>
                    </div>
                    <div class="kpi-total-status mt-auto">
                        <span class="inline-flex min-w-0 items-center gap-1 whitespace-nowrap">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            <span class="text-[7px] font-bold text-slate-500">Completed</span>
                            <span class="text-[8px] font-black text-slate-900 kpi-breakdown-count" data-key="completed">0</span>
                        </span>
                        <span class="inline-flex min-w-0 items-center gap-1 whitespace-nowrap">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            <span class="text-[7px] font-bold text-slate-500">In Progress</span>
                            <span class="text-[8px] font-black text-slate-900 kpi-breakdown-count" data-key="in_progress">0</span>
                        </span>
                        <span class="inline-flex min-w-0 items-center gap-1 whitespace-nowrap">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            <span class="text-[7px] font-bold text-slate-500">Suspended</span>
                            <span class="text-[8px] font-black text-slate-900 kpi-breakdown-count" data-key="suspended">0</span>
                        </span>
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
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                        <span class="kpi-count-label">0</span> <span class="text-slate-500 normal-case tracking-normal">Projects</span>
                    </p>
                    <p class="text-[7px] font-bold text-slate-400 kpi-insight-text"></p>
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
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                        <span class="kpi-count-label">0</span> <span class="text-slate-500 normal-case tracking-normal">Active</span>
                    </p>
                    <p class="text-[7px] font-bold text-slate-400 kpi-insight-text"></p>
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
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                        <span class="kpi-count-label">0</span> <span class="text-slate-500 normal-case tracking-normal">Projects</span>
                    </p>
                    <p class="text-[7px] font-bold text-slate-400 kpi-insight-text"></p>
                </div>
            </div>
        </div>

        <div id="completedProjectsAnalytics" class="bg-white rounded-2xl shadow-lg">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Completed Projects Analytics</h3>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Distribution across key project dimensions</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest border border-emerald-100">Completed</span>
            </div>
            <div class="p-5">
                <div id="analyticsGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <!-- Charts injected by JS -->
                </div>
            </div>
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
                </div>
            </div>

            <!-- Filters -->
            <div class="px-5 py-3 bg-white border-b border-slate-100">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Filter By</span>
                        <select id="sectionFilterCategory" onchange="updateSectionFilterValues()" class="appearance-none bg-white border border-slate-200 rounded-lg pl-2 pr-7 py-1.5 text-[8px] font-black text-slate-600 uppercase tracking-wider cursor-pointer hover:border-slate-300 transition-all focus:ring-2 focus:ring-slate-100 focus:border-slate-300 shadow-sm">
                            <option value="">All Categories</option>
                            <option value="status">Project Status</option>
                            <option value="consultancy_sector">Consultancy Sector</option>
                            <option value="delivery_method">Delivery Method</option>
                            <option value="schedule_status">Schedule Status</option>
                            <option value="project_type">Project Sector</option>
                            <option value="budget_performance">Budget Performance</option>
                            <option value="cost_performance">Cost Performance</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Value</span>
                        <select id="sectionFilterValue" onchange="applySectionFilter()" disabled class="appearance-none bg-white border border-slate-200 rounded-lg pl-2 pr-7 py-1.5 text-[8px] font-black text-slate-600 uppercase tracking-wider cursor-pointer hover:border-slate-300 transition-all focus:ring-2 focus:ring-slate-100 focus:border-slate-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                            <option value="">All</option>
                        </select>
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

        <!-- Project Performance Matrix -->
        <div id="projectPerformanceMatrix" class="matrix-section-container bg-white rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-black text-slate-900 tracking-tight">Project Performance Matrix</h3>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Grouped performance breakdowns by dimension</p>
            </div>
            <div class="p-5 space-y-4">
                <!-- Schedule Performance -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="p-4 border-b border-slate-50 text-center">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Schedule Performance</h4>
                    </div>
                    <div class="p-4">
                        <div id="matrixScheduleRow" class="grid grid-cols-1 md:grid-cols-3 gap-3"></div>
                    </div>
                </div>

                <!-- Contract Budget Performance -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="p-4 border-b border-slate-50 text-center">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Contract Budget Performance</h4>
                    </div>
                    <div class="p-4">
                        <div id="matrixBudgetRow" class="grid grid-cols-1 md:grid-cols-3 gap-3"></div>
                    </div>
                </div>

                <!-- Cost Performance -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="p-4 border-b border-slate-50 text-center">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Cost Performance</h4>
                    </div>
                    <div class="p-4">
                        <div id="matrixCostRow" class="grid grid-cols-1 md:grid-cols-3 gap-3"></div>
                    </div>
                </div>
            </div>
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
        let allProjects = [];
        let filteredProjects = [];
        let currentPage = 1;
        let perPage = 15;
        let totalPages = 1;
        let chartInstance = null;
        let searchTimeout = null;
        let dashboardRequestSequence = 0;
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
            const requestSequence = ++dashboardRequestSequence;
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

            const searchInput = document.getElementById('searchInput');
            if (searchInput && searchInput.value.trim()) params.append('search', searchInput.value.trim());

            if (activeChartFilter) {
                params.append(activeChartFilter.chart, activeChartFilter.value);
            }

            fetch(`{{ route('admin.dashboard.data') }}?${params.toString()}`)
                .then(response => {
                    if (!response.ok) throw new Error(`Dashboard request failed with status ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (requestSequence !== dashboardRequestSequence) return;
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
                    renderStatusPieChart();
                    renderProjectPerformanceMatrix();
                    updateSubtitle();
                })
                .catch(err => {
                    if (requestSequence !== dashboardRequestSequence) return;
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
            currentPage = 1;
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
            clearTimeout(searchTimeout);
            document.getElementById('filterSector').value = 'all';
            document.getElementById('filterDeliveryMethod').value = 'all';
            document.getElementById('filterConsultantType').value = 'all';
            document.getElementById('filterLifecycleStatus').value = 'all';
            document.getElementById('searchInput').value = '';
            const categorySelect = document.getElementById('sectionFilterCategory');
            const valueSelect = document.getElementById('sectionFilterValue');
            if (categorySelect) categorySelect.value = '';
            if (valueSelect) {
                valueSelect.innerHTML = '<option value="">All</option>';
                valueSelect.value = '';
                valueSelect.disabled = true;
            }
            sectionFilter = { category: null, value: null };
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

            const totalInsight = document.querySelector('#executiveKpiRow .dashboard-kpi-card:first-child .kpi-insight-text');
            if (totalInsight) {
                const parts = [];
                if (completed > 0) parts.push(`${completed} completed`);
                if (inProgress > 0) parts.push(`${inProgress} active`);
                if (suspended > 0) parts.push(`${suspended} suspended`);
                totalInsight.textContent = parts.length > 0 ? parts.join(' · ') : 'No projects';
            }

            const completedInsight = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(2) .kpi-insight-text');
            if (completedInsight) {
                const pct = totalForBreakdown > 0 ? Math.round((completed / totalForBreakdown) * 100) : 0;
                completedInsight.textContent = `${pct}% of project portfolio`;
            }

            const inProgressInsight = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(3) .kpi-insight-text');
            if (inProgressInsight) {
                const pct = totalForBreakdown > 0 ? Math.round((inProgress / totalForBreakdown) * 100) : 0;
                inProgressInsight.textContent = `${pct}% of project portfolio`;
            }

            const suspendedInsight = document.querySelector('#executiveKpiRow .dashboard-kpi-card:nth-child(4) .kpi-insight-text');
            if (suspendedInsight) {
                suspendedInsight.textContent = suspended > 0 ? `${suspended} project${suspended > 1 ? 's' : ''} currently suspended` : 'No suspended projects';
            }
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
            if (absNum >= 1_000_000_000) {
                const val = (rounded / 1_000_000_000).toFixed(2);
                return `${val}<span class="text-[10px] font-black text-slate-400 ml-0.5">B</span>`;
            }
            if (absNum >= 1_000_000) {
                const val = (rounded / 1_000_000).toFixed(1);
                const trimmed = val.replace(/\.0+$/, '');
                return `${trimmed}<span class="text-[10px] font-black text-slate-400 ml-0.5">M</span>`;
            }
            if (absNum >= 1_000) {
                return formatNumber(rounded) + `<span class="text-[10px] font-black text-slate-400 ml-0.5">K</span>`;
            }
            return formatNumber(rounded);
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
                const statusLabel = 'Completed';
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
            return filteredProjects.filter(p => (p.status || '').toLowerCase() === 'completed');
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
            const aheadLabel = currentTab === 'schedule' ? 'Ahead' : currentTab === 'budget' ? 'Under Budget' : 'Below Allowable';
            const onLabel = currentTab === 'schedule' ? 'On Schedule' : currentTab === 'budget' ? 'On Budget' : 'Within Allowable';
            const behindLabel = currentTab === 'schedule' ? 'Behind' : currentTab === 'budget' ? 'Above Budget' : 'Above Allowable';

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

            const visibleProjects = sorted;
            const categories = [];
            const values = [];
            const colors = [];
            const sortedProjects = [];

            visibleProjects.forEach((p, idx) => {
                categories.push(`${formatChartLabel(p, idx)}`);
                const val = currentTab === 'schedule' ? p.schedule_performance_percent : currentTab === 'budget' ? p.budget_index_percent : p.cost_performance_percent;
                values.push(val);
                if (currentTab === 'schedule') {
                    if (p.schedule_performance_percent >= 100) colors.push('#10b981');
                    else if (p.schedule_performance_percent >= 70) colors.push('#f59e0b');
                    else colors.push('#ef4444');
                } else if (currentTab === 'budget' || currentTab === 'cost') {
                    if (currentTab === 'budget') {
                        if (val < 100) colors.push('#10b981');
                        else if (val === 100) colors.push('#f59e0b');
                        else colors.push('#ef4444');
                    } else {
                        if (val < 100) colors.push('#ef4444');
                        else if (val === 100) colors.push('#f59e0b');
                        else colors.push('#10b981');
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
            const showDataLabels = categories.length <= 20;

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
                    enabled: showDataLabels,
                    style: {
                        fontSize: dataLabelFontSize,
                        fontWeight: 800,
                        colors: ['#fff']
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
                        content += `<div style="font-weight: 800; font-size: 12px; color: #0f172a; margin-bottom: 6px;">${project.project_code}</div>`;

                        if (currentTab === 'schedule') {
                            const statusColor = project.schedule_status === 'Behind Schedule' ? '#ef4444' : project.schedule_status === 'Ahead of Schedule' ? '#10b981' : '#3b82f6';
                            const schedulePercent = Math.round(project.schedule_performance_percent);
                            let schedulePercentColor = '#ef4444';
                            if (schedulePercent >= 100) schedulePercentColor = '#10b981';
                            else if (schedulePercent >= 70) schedulePercentColor = '#f59e0b';
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
                            const perfColor = project.budget_performance === 'Above Budget' ? '#ef4444' : project.budget_performance === 'Under Budget' ? '#10b981' : '#f59e0b';
                            const bpi = Math.round(project.budget_index_percent);
                            const bpiColor = bpi < 100 ? '#10b981' : bpi > 100 ? '#ef4444' : '#f59e0b';
                            content += `<div style="font-weight: 800; font-size: 20px; color: ${bpiColor}; margin-bottom: 8px;">${bpi}%</div>`;
                            content += `<div style="display: grid; gap: 3px; font-size: 10px; color: #475569;">`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Performance</span><span style="font-weight: 700; color: ${perfColor};">${project.budget_performance}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Budget at Completion</span><span style="font-weight: 700;">ETB ${Math.round(project.budget_at_completion).toLocaleString()}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Revised Budget</span><span style="font-weight: 700;">ETB ${Math.round(project.revised_budget).toLocaleString()}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Contract Budget</span><span style="font-weight: 700;">ETB ${Math.round(project.contract_budget).toLocaleString()}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Budget Variance</span><span style="font-weight: 700;">ETB ${Math.round(project.budget_variance).toLocaleString()}</span></div>`;
                            content += `</div>`;
                        } else if (currentTab === 'cost') {
                            const perfColor = project.cost_performance === 'Above Allowable Cost' ? '#ef4444' : project.cost_performance === 'Below Allowable Cost' ? '#10b981' : '#f59e0b';
                            const cpi = Math.round(project.cost_performance_percent);
                            const cpiColor = cpi < 100 ? '#ef4444' : cpi > 100 ? '#10b981' : '#f59e0b';
                            content += `<div style="font-weight: 800; font-size: 20px; color: ${cpiColor}; margin-bottom: 8px;">${cpi}%</div>`;
                            content += `<div style="display: grid; gap: 3px; font-size: 10px; color: #475569;">`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Performance</span><span style="font-weight: 700; color: ${perfColor};">${project.cost_performance}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Allowable Cost</span><span style="font-weight: 700;">ETB ${Math.round(project.total_allowable_cost).toLocaleString()}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Cost at Completion</span><span style="font-weight: 700;">ETB ${Math.round(project.cost_at_completion).toLocaleString()}</span></div>`;
                            content += `<div style="display: flex; justify-content: space-between;"><span style="font-weight: 600; color: #94a3b8;">Cost Variance</span><span style="font-weight: 700;">ETB ${Math.round(project.cost_variance).toLocaleString()}</span></div>`;
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
            const totalEl = document.getElementById('statusPieTotal');

            if (totalEl) {
                totalEl.textContent = String(series.reduce((total, value) => total + Number(value || 0), 0));
            }

            if (window.statusPieInstance) {
                window.statusPieInstance.destroy();
            }

            window.statusPieInstance = new ApexCharts(existingChart, {
                chart: {
                    type: 'donut',
                    height: 96,
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
                            offset: -2
                        },
                        donut: {
                            size: '58%'
                        }
                    }
                },
                dataLabels: {
                    enabled: false,
                    formatter: function(val) {
                        if (val < 15) return '';
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
                tooltip: {
                    theme: 'light',
                    style: {
                        fontSize: '10px',
                        fontFamily: 'Inter, sans-serif'
                    }
                }
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

        function renderProjectPerformanceMatrix() {
            const matrix = window.latestComparativeAnalytics || {};

            const scheduleConfigs = [
                { key: 'time_by_delivery', container: 'matrixScheduleRow', title: 'Schedule Performance by Delivery Method' },
                { key: 'time_by_variation', container: 'matrixScheduleRow', title: 'Schedule Performance by Contract Variation' },
                { key: 'time_by_sector', container: 'matrixScheduleRow', title: 'Schedule Performance by Consultancy Sector' },
            ];

            const budgetConfigs = [
                { key: 'budget_by_delivery', container: 'matrixBudgetRow', title: 'Contract Budget Performance by Delivery Method' },
                { key: 'budget_by_sector', container: 'matrixBudgetRow', title: 'Contract Budget Performance by Consultancy Sector' },
                { key: 'budget_by_schedule', container: 'matrixBudgetRow', title: 'Contract Budget Performance by Schedule Performance' },
            ];

            const costConfigs = [
                { key: 'cost_by_delivery', container: 'matrixCostRow', title: 'Cost Performance by Delivery Method' },
                { key: 'cost_by_variation', container: 'matrixCostRow', title: 'Cost Performance by Contract Variation' },
                { key: 'cost_by_sector', container: 'matrixCostRow', title: 'Cost Performance by Consultancy Sector' },
            ];

            const allConfigs = [...scheduleConfigs, ...budgetConfigs, ...costConfigs];

            allConfigs.forEach(config => {
                const container = document.getElementById(config.container);
                if (!container) return;

                const chartData = matrix[config.key];
                if (!chartData) return;

                const chartId = `matrix-${config.key}`;
                const isSectorChart = chartId.includes('time_by_sector') || chartId.includes('budget_by_sector') || chartId.includes('cost_by_sector');
                const card = document.createElement('div');
                card.className = 'matrix-chart-card bg-white rounded-xl border border-slate-200 shadow-sm transition-all duration-300';
                card.innerHTML = `
                    <div class="matrix-chart-heading px-3.5 pt-3.5 pb-2">
                        <span class="matrix-chart-heading-mark"></span>
                        <p class="text-[8px] font-extrabold text-slate-700 uppercase tracking-wider leading-snug">${config.title}</p>
                    </div>
                    <div class="px-2 pb-3">
                        <div id="${chartId}" style="height: 205px;"></div>
                    </div>
                `;
                container.appendChild(card);

                const isTimeBySectorChart = config.key === 'time_by_sector';
                const categories = chartData.xaxis || [];
                const sourceSeries = chartData.series || [];
                const series = isTimeBySectorChart
                    ? [{
                        name: 'Average SPI (%)',
                        data: sourceSeries[0]?.values || [],
                    }]
                    : sourceSeries.map(ds => ({
                        name: ds.name,
                        data: ds.values || [],
                    }));
                const sectorColors = ['#06b6d4', '#6366f1', '#10b981'];

                const getColorForDataset = (name) => {
                    const normalized = (name || '').toLowerCase();
                    if (normalized.includes('behind') || normalized.includes('above') || normalized.includes('above allowable') || normalized.includes('above budget')) return '#ef4444';
                    if (normalized.includes('ahead') || normalized.includes('below') || normalized.includes('below allowable') || normalized.includes('under')) return '#10b981';
                    if (normalized.includes('on') || normalized.includes('within')) return '#f59e0b';
                    return '#00ADC5';
                };

                const colors = isTimeBySectorChart
                    ? sectorColors
                    : sourceSeries.map(ds => getColorForDataset(ds.name));

                if (categories.length === 0 || series.length === 0) {
                    document.getElementById(chartId).innerHTML = `
                        <div class="flex items-center justify-center h-full text-slate-400 text-[9px] font-black">No data</div>
                    `;
                    return;
                }

                new ApexCharts(document.getElementById(chartId), {
                    chart: {
                        type: 'bar',
                        height: 205,
                        parentHeightOffset: 0,
                        animations: { enabled: true, easing: 'easeinout', speed: 550 },
                        toolbar: { show: false },
                        background: 'transparent',
                        foreColor: '#64748b',
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '48%',
                            borderRadius: 6,
                            borderRadiusApplication: 'end',
                            distributed: isTimeBySectorChart,
                            dataLabels: { position: 'top' },
                            backgroundBarColors: ['#f1f5f9'],
                            backgroundBarOpacity: 0.45,
                            backgroundBarRadius: 6,
                        },
                    },
                    series: series,
                    xaxis: {
                        categories: categories,
                        labels: {
                            style: { fontSize: '8px', fontWeight: 600, colors: '#64748b' },
                            rotate: 0,
                            rotateAlways: false,
                            hideOverlappingLabels: true,
                            trim: false,
                            maxHeight: 44,
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                    },
                    yaxis: {
                        labels: {
                            style: { fontSize: '8px', fontWeight: 600, colors: '#94a3b8' },
                            formatter: (val) => `${Math.round(val)}%`,
                        },
                    },
                    colors: colors,
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: 'vertical',
                            shadeIntensity: 0.12,
                            gradientToColors: colors.map(color => color),
                            inverseColors: false,
                            opacityFrom: 1,
                            opacityTo: 0.78,
                            stops: [0, 90, 100],
                        },
                    },
                    legend: {
                        show: !isTimeBySectorChart,
                        position: 'top',
                        horizontalAlign: 'left',
                        fontSize: '8px',
                        fontWeight: 600,
                        labels: { colors: '#64748b' },
                        markers: { width: 7, height: 7, radius: 3 },
                        itemMargin: { horizontal: 7, vertical: 2 },
                    },
                    tooltip: {
                        theme: 'light',
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Inter, sans-serif'
                        },
                        y: { formatter: (val) => `${Math.round(val)}%` },
                    },
                    grid: {
                        borderColor: '#e9eef5',
                        strokeDashArray: 4,
                        xaxis: { lines: { show: false } },
                        yaxis: { lines: { show: true } },
                        padding: { top: 16, right: 8, bottom: 0, left: 4 },
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(value, options) {
                            const count = sourceSeries[options.seriesIndex]?.counts?.[options.dataPointIndex] ?? 0;
                            return count > 0 ? String(count) : '';
                        },
                        offsetY: -10,
                        style: {
                            fontSize: '9px',
                            fontWeight: 800,
                            colors: ['#475569'],
                        },
                        textAnchor: 'middle',
                        background: { enabled: false },
                        dropShadow: { enabled: false },
                    },
                    states: {
                        hover: { filter: { type: 'lighten', value: 0.08 } },
                        active: { filter: { type: 'darken', value: 0.04 } },
                    },
                    theme: { mode: 'light' },
                }).render();
            });
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
                'On Budget': 'bg-rose-50 text-rose-600 border-rose-200',
                'Above Budget': 'bg-rose-50 text-rose-600 border-rose-200',
            };
            return `<span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider ${config[status] || 'bg-slate-100 text-slate-600 border-slate-200'} border shadow-sm">${status}</span>`;
        }

        function getCostBadge(status) {
            const config = {
                'Below Allowable Cost': 'bg-emerald-50 text-emerald-600 border-emerald-200',
                'Within Allowable Cost': 'bg-rose-50 text-rose-600 border-rose-200',
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
            const budgetColor = project.budget_performance === 'Above Budget' ? 'text-rose-600' : project.budget_performance === 'Under Budget' ? 'text-emerald-600' : 'text-rose-600';
            const costColor = project.cost_performance === 'Above Allowable Cost' ? 'text-rose-600' : project.cost_performance === 'Below Allowable Cost' ? 'text-emerald-600' : 'text-rose-600';
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
            delivery_method: {
                title: 'Delivery Method',
                description: 'Completed projects delivery approach.',
                categories: [
                    { key: 'DB', label: 'DB', abbr: 'DB', color: '#10b981' },
                    { key: 'DBB', label: 'DBB', abbr: 'DBB', color: '#00ADC5' },
                ],
                totalLabel: 'Completed',
                filterParam: 'delivery_method',
                emptyMessage: 'No completed projects available for delivery method breakdown.',
                order: 1,
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
                order: 2,
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
                order: 3,
            },
            closing_status: {
                title: 'Closing Status',
                description: 'Completed projects closing status for FA and PA received.',
                categories: [
                    { key: 'pa_received', label: 'PA Received', abbr: 'PA', color: '#6366f1' },
                    { key: 'fa_received', label: 'FA Received', abbr: 'FA', color: '#10b981' },
                ],
                totalLabel: 'Completed',
                filterParam: 'closing_status',
                emptyMessage: 'No completed projects available for closing status review.',
                order: 4,
            },
            cost_performance: {
                title: 'Cost Performance',
                description: 'Completed projects cost health against allowable limits.',
                categories: [
                    { key: 'below', label: 'Below', abbr: 'BLW', color: '#10b981' },
                    { key: 'with_allowable', label: 'Within', abbr: 'WTH', color: '#f59e0b' },
                    { key: 'above', label: 'Above', abbr: 'ABV', color: '#f43f5e' },
                ],
                totalLabel: 'Below/With/Above',
                filterParam: 'cost_filter',
                emptyMessage: 'No completed projects available for cost performance review.',
                order: 5,
            },
            schedule_performance: {
                title: 'Schedule Performance',
                description: 'Completed projects schedule performance',
                categories: [
                    { key: 'ahead', label: 'Ahead', abbr: 'AHD', color: '#10b981' },
                    { key: 'on_schedule', label: 'ON', abbr: 'ONS', color: '#f59e0b' },
                    { key: 'behind', label: 'Behind', abbr: 'BHD', color: '#f43f5e' },
                ],
                totalLabel: 'Completed',
                filterParam: 'schedule_filter',
                emptyMessage: 'No completed projects available for schedule review.',
                order: 6,
            },
            budget_performance: {
                title: 'Budget Performance',
                description: 'Completed projects budget health against revised budget.',
                categories: [
                    { key: 'under', label: 'Under', abbr: 'UND', color: '#10b981' },
                    { key: 'on_budget', label: 'On Budget', abbr: 'ON', color: '#f59e0b' },
                    { key: 'above', label: 'Above', abbr: 'ABV', color: '#f43f5e' },
                ],
                totalLabel: 'Completed',
                filterParam: 'budget_filter',
                emptyMessage: 'No completed projects available for budget performance review.',
                order: 7,
            },
            project_sector: {
                title: 'Project Sector',
                description: 'Completed projects by project sector.',
                categories: [
                    { key: 'building', label: 'Building', abbr: 'BLD', color: '#10b981' },
                    { key: 'fit_out', label: 'Fit-Out', abbr: 'FIT', color: '#00ADC5' },
                    { key: 'infrastructure', label: 'Infrastructure', abbr: 'INF', color: '#f59e0b' },
                    { key: 'water_energy', label: 'Water & Energy', abbr: 'W&E', color: '#6366f1' },
                ],
                totalLabel: 'Completed',
                filterParam: 'project_sector',
                emptyMessage: 'No completed projects available for sector breakdown.',
                order: 8,
            },
        };

        const analyticsChartOrder = Object.keys(analyticsConfig).sort((a, b) => (analyticsConfig[a].order || 0) - (analyticsConfig[b].order || 0));

        function renderAnalyticsGrid() {
            const grid = document.getElementById('analyticsGrid');
            if (!grid) return;

            const analytics = window.latestAnalytics || {};
            grid.innerHTML = '';

            analyticsChartOrder.forEach(chartId => {
                const config = analyticsConfig[chartId];
                const data = analytics[chartId] || {};
                const total = data.total || 0;
                const accentColor = config.categories[0]?.color || '#6366f1';

                const card = document.createElement('div');
                card.className = 'analytics-card group relative bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col analytics-card-pie';
                card.innerHTML = `
                    <div class="relative z-10 flex flex-col flex-1 p-3 pt-4">
                        <div class="flex items-center justify-center mb-2">
                            <h4 class="text-[9px] font-black text-slate-900 uppercase tracking-widest leading-none">${config.title}</h4>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="flex items-center justify-center" style="width: 170px; height: 170px;">
                                <div id="chart-${chartId}" class="w-full h-full relative" style="z-index: 1; overflow: visible !important;"></div>
                            </div>
                            <div id="legend-${chartId}" class="analytics-legend-wrapper flex flex-row flex-wrap items-center justify-center gap-1"></div>
                            <div id="insight-${chartId}" class="analytics-card-insight w-full text-center">
                                <p id="analytics-insight-primary-${chartId}" class="text-[8px] font-black text-slate-700 leading-tight"></p>
                                <p id="analytics-insight-secondary-${chartId}" class="text-[7px] font-bold text-slate-400 leading-tight mt-0.5"></p>
                                <p id="analytics-insight-status-${chartId}" class="text-[7px] font-black mt-0.5"></p>
                            </div>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });

            analyticsChartOrder.forEach(chartId => {
                createPieChart(chartId, analytics[chartId] || {});
                const data = analytics[chartId] || {};
                const total = data.total || 0;
                const insight = getInsight(chartId, data, total);

                const primaryEl = document.getElementById(`analytics-insight-primary-${chartId}`);
                const secondaryEl = document.getElementById(`analytics-insight-secondary-${chartId}`);
                const statusEl = document.getElementById(`analytics-insight-status-${chartId}`);

                if (primaryEl) primaryEl.textContent = insight.primary;
                if (secondaryEl) secondaryEl.textContent = insight.secondary;
                if (statusEl) {
                    statusEl.textContent = insight.statusText;
                    statusEl.style.color = insight.statusColor;
                }
            });

            if (window.lucide) lucide.createIcons();
        }

        function getInsight(chartId, data, total) {
            if (total === 0) {
                return {
                    primary: 'No data available.',
                    secondary: '',
                    statusColor: '#94a3b8',
                    statusText: 'No data',
                };
            }

            const categories = analyticsConfig[chartId]?.categories || [];
            const counts = categories.map(cat => data[cat.key] || 0);

            switch (chartId) {
                case 'schedule_performance': {
                    const ahead = counts[0] || 0;
                    const onSchedule = counts[1] || 0;
                    const behind = counts[2] || 0;

                    if (behind > 0) {
                        return {
                            primary: `${behind} project${behind > 1 ? 's' : ''} ${behind > 1 ? 'are' : 'is'} currently behind schedule.`,
                            secondary: 'Schedule status: Attention required',
                            statusColor: '#f43f5e',
                            statusText: 'Attention required',
                        };
                    }
                    if (ahead > 0 && onSchedule > 0) {
                        return {
                            primary: `${ahead} project${ahead > 1 ? 's' : ''} ahead, ${onSchedule} on schedule, ${behind} behind.`,
                            secondary: 'Schedule status: Mixed',
                            statusColor: '#f59e0b',
                            statusText: 'Mixed',
                        };
                    }
                    if (ahead > 0) {
                        return {
                            primary: `${ahead} project${ahead > 1 ? 's' : ''} ${ahead > 1 ? 'are' : 'is'} ahead of schedule.`,
                            secondary: 'Schedule status: On track',
                            statusColor: '#10b981',
                            statusText: 'On track',
                        };
                    }
                    return {
                        primary: 'All projects are currently on schedule.',
                        secondary: 'Schedule status: On track',
                        statusColor: '#10b981',
                        statusText: 'On track',
                    };
                }

                case 'delivery_method': {
                    const db = counts[0] || 0;
                    const dbb = counts[1] || 0;
                    const activeMethods = [db, dbb].filter(v => v > 0).length;

                    if (activeMethods === 0) {
                        return {
                            primary: 'No delivery method data available.',
                            secondary: '',
                            statusColor: '#94a3b8',
                            statusText: 'No data',
                        };
                    }

                    if (activeMethods === 1) {
                        const method = db > 0 ? 'Design-Build' : 'Design-Bid-Build';
                        return {
                            primary: `${Math.max(db, dbb)} project${Math.max(db, dbb) > 1 ? 's' : ''} using ${method}.`,
                            secondary: `Most common: ${method}`,
                            statusColor: '#00ADC5',
                            statusText: method,
                        };
                    }

                    return {
                        primary: `Projects distributed across ${activeMethods} delivery methods.`,
                        secondary: `Most common: ${db >= dbb ? 'Design-Build' : 'Design-Bid-Build'}`,
                        statusColor: '#00ADC5',
                        statusText: 'Mixed',
                    };
                }

                case 'contract_variation': {
                    const addition = counts[0] || 0;
                    const omission = counts[1] || 0;
                    const noVariation = counts[2] || 0;

                    if (addition > 0 && omission === 0 && noVariation === 0) {
                        return {
                            primary: `${addition} project${addition > 1 ? 's' : ''} ${addition > 1 ? 'have' : 'has'} contract additions.`,
                            secondary: `Variation status: ${addition} addition${addition > 1 ? 's' : ''}`,
                            statusColor: '#00ADC5',
                            statusText: 'Additions',
                        };
                    }

                    if (addition === 0 && omission === 0 && noVariation === 0) {
                        return {
                            primary: 'No contract variations have been recorded.',
                            secondary: 'Variation status: None',
                            statusColor: '#10b981',
                            statusText: 'None',
                        };
                    }

                    const parts = [];
                    if (addition > 0) parts.push(`${addition} addition${addition > 1 ? 's' : ''}`);
                    if (omission > 0) parts.push(`${omission} omission${omission > 1 ? 's' : ''}`);
                    if (noVariation > 0) parts.push(`${noVariation} no variation${noVariation > 1 ? 's' : ''}`);

                    return {
                        primary: `Contract variations include ${parts.slice(0, 2).join(' and ')}.`,
                        secondary: `Variation status: ${parts[0]}`,
                        statusColor: '#f59e0b',
                        statusText: 'Mixed',
                    };
                }

                case 'consultancy_sector': {
                    const private = counts[0] || 0;
                    const client = counts[1] || 0;
                    const government = counts[2] || 0;

                    if (private === 0 && client === 0 && government === 0) {
                        return {
                            primary: 'No consultancy sector data available.',
                            secondary: '',
                            statusColor: '#94a3b8',
                            statusText: 'No data',
                        };
                    }

                    const maxCount = Math.max(private, client, government);
                    const maxLabel = categories[counts.indexOf(maxCount)]?.label || 'Unknown';

                    if (maxCount === total) {
                        return {
                            primary: `${maxLabel} projects represent 100% of the current portfolio.`,
                            secondary: `Primary sector: ${maxLabel}`,
                            statusColor: '#6366f1',
                            statusText: maxLabel,
                        };
                    }

                    const percent = total > 0 ? Math.round((maxCount / total) * 100) : 0;
                    return {
                        primary: `${maxLabel} is the primary sector at ${percent}%.`,
                        secondary: `Primary sector: ${maxLabel}`,
                        statusColor: '#6366f1',
                        statusText: maxLabel,
                    };
                }

                case 'cost_performance': {
                    const below = counts[0] || 0;
                    const within = counts[1] || 0;
                    const above = counts[2] || 0;

                    if (above > 0 && within === 0 && below === 0) {
                        return {
                            primary: `${above} project${above > 1 ? 's' : ''} ${above > 1 ? 'are' : 'is'} currently above the target cost.`,
                            secondary: 'Cost status: Above target',
                            statusColor: '#f43f5e',
                            statusText: 'Above target',
                        };
                    }

                    if (within > 0 && above === 0 && below === 0) {
                        return {
                            primary: 'All projects are currently within the target cost.',
                            secondary: 'Cost status: Within target',
                            statusColor: '#10b981',
                            statusText: 'Within target',
                        };
                    }

                    if (below > 0 && within > 0 && above > 0) {
                        return {
                            primary: `${within} within target, ${above} above target, ${below} below target.`,
                            secondary: 'Cost status: Mixed',
                            statusColor: '#f59e0b',
                            statusText: 'Mixed',
                        };
                    }

                    if (within > 0 && above > 0) {
                        return {
                            primary: `${within} within target, while ${above} ${above > 1 ? 'are' : 'is'} above target.`,
                            secondary: 'Cost status: Mixed',
                            statusColor: '#f59e0b',
                            statusText: 'Mixed',
                        };
                    }

                    if (below > 0) {
                        return {
                            primary: `${below} project${below > 1 ? 's' : ''} ${below > 1 ? 'are' : 'is'} below the target cost.`,
                            secondary: 'Cost status: Below target',
                            statusColor: '#10b981',
                            statusText: 'Below target',
                        };
                    }

                    return {
                        primary: 'No cost performance data available.',
                        secondary: '',
                        statusColor: '#94a3b8',
                        statusText: 'No data',
                    };
                }

                case 'closing_status': {
                    const paReceived = counts[0] || 0;
                    const faReceived = counts[1] || 0;

                    if (faReceived > 0 && paReceived === 0) {
                        return {
                            primary: `${faReceived} project${faReceived > 1 ? 's' : ''} ${faReceived > 1 ? 'have' : 'has'} received FA.`,
                            secondary: 'Closing status: FA received',
                            statusColor: '#10b981',
                            statusText: 'FA received',
                        };
                    }

                    if (paReceived > 0 && faReceived === 0) {
                        return {
                            primary: `${paReceived} project${paReceived > 1 ? 's' : ''} ${paReceived > 1 ? 'have' : 'has'} received PA.`,
                            secondary: 'Closing status: PA received',
                            statusColor: '#6366f1',
                            statusText: 'PA received',
                        };
                    }

                    if (faReceived > 0 && paReceived > 0) {
                        return {
                            primary: `${faReceived} with FA, ${paReceived} with PA received.`,
                            secondary: 'Closing status: Mixed',
                            statusColor: '#6366f1',
                            statusText: 'Mixed',
                        };
                    }

                    return {
                        primary: 'No closing status data available.',
                        secondary: '',
                        statusColor: '#94a3b8',
                        statusText: 'No data',
                    };
                }

                case 'budget_performance': {
                    const under = counts[0] || 0;
                    const onBudget = counts[1] || 0;
                    const above = counts[2] || 0;

                    if (above > 0 && onBudget === 0 && under === 0) {
                        return {
                            primary: `${above} project${above > 1 ? 's' : ''} ${above > 1 ? 'are' : 'is'} above revised budget.`,
                            secondary: 'Budget status: Above budget',
                            statusColor: '#f43f5e',
                            statusText: 'Above budget',
                        };
                    }

                    if (onBudget > 0 && above === 0 && under === 0) {
                        return {
                            primary: 'All projects are currently on budget.',
                            secondary: 'Budget status: On budget',
                            statusColor: '#10b981',
                            statusText: 'On budget',
                        };
                    }

                    if (under > 0 && onBudget > 0 && above > 0) {
                        return {
                            primary: `${under} under, ${onBudget} on budget, ${above} above budget.`,
                            secondary: 'Budget status: Mixed',
                            statusColor: '#f59e0b',
                            statusText: 'Mixed',
                        };
                    }

                    if (under > 0) {
                        return {
                            primary: `${under} project${under > 1 ? 's' : ''} ${under > 1 ? 'are' : 'is'} under revised budget.`,
                            secondary: 'Budget status: Under budget',
                            statusColor: '#10b981',
                            statusText: 'Under budget',
                        };
                    }

                    return {
                        primary: 'No budget performance data available.',
                        secondary: '',
                        statusColor: '#94a3b8',
                        statusText: 'No data',
                    };
                }

                case 'project_sector': {
                    const building = counts[0] || 0;
                    const fitOut = counts[1] || 0;
                    const infrastructure = counts[2] || 0;
                    const waterEnergy = counts[3] || 0;

                    if (building === 0 && fitOut === 0 && infrastructure === 0 && waterEnergy === 0) {
                        return {
                            primary: 'No project sector data available.',
                            secondary: '',
                            statusColor: '#94a3b8',
                            statusText: 'No data',
                        };
                    }

                    const maxCount = Math.max(building, fitOut, infrastructure, waterEnergy);
                    const maxLabel = categories[counts.indexOf(maxCount)]?.label || 'Unknown';

                    if (maxCount === total) {
                        return {
                            primary: `${maxLabel} projects represent 100% of the current portfolio.`,
                            secondary: `Primary sector: ${maxLabel}`,
                            statusColor: '#6366f1',
                            statusText: maxLabel,
                        };
                    }

                    const percent = total > 0 ? Math.round((maxCount / total) * 100) : 0;
                    return {
                        primary: `${maxLabel} is the primary sector at ${percent}%.`,
                        secondary: `Primary sector: ${maxLabel}`,
                        statusColor: '#6366f1',
                        statusText: maxLabel,
                    };
                }

                default:
                    return {
                        primary: `${total} project${total > 1 ? 's' : ''} in this category.`,
                        secondary: '',
                        statusColor: '#6366f1',
                        statusText: 'Active',
                    };
            }
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
                analyticsCharts[chartId].destroy();
            }

            analyticsCharts[chartId] = new ApexCharts(chartEl, {
                chart: {
                    type: 'donut',
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
                        },
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '9px',
                                    fontWeight: 700,
                                    color: '#64748b',
                                    offsetY: -2
                                },
                                value: {
                                    show: true,
                                    fontSize: '16px',
                                    fontWeight: 800,
                                    color: '#0f172a',
                                    offsetY: 3
                                },
                                total: {
                                    show: true,
                                    fontSize: '10px',
                                    fontWeight: 800,
                                    color: '#64748b',
                                    label: 'PROJECTS',
                                    formatter: function() {
                                        return total;
                                    }
                                }
                            }
                        }
                    }
                },
                series: series,
                labels: labels,
                colors: colors,
                legend: { show: false },
                stroke: {
                    show: false,
                    width: 0,
                    colors: ['transparent']
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    enabled: true,
                    theme: 'light',
                    style: {
                        fontSize: '10px',
                        fontFamily: 'Inter, sans-serif'
                    },
                    y: {
                        formatter: function(val) {
                            if (val === null || val === undefined) return '';
                            return `${Math.round(val)} projects`;
                        }
                    }
                },
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

        .analytics-container {
            background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.02);
        }
        .analytics-container:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.03);
        }
        #completedProjectsAnalytics {
            border: none !important;
            outline: none !important;
        }

        .analytics-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 320px;
            position: relative;
            overflow: visible;
            background: #FFFFFF;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
        }
        .analytics-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04), 0 0 0 1px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }
        .analytics-card::before {
            content: none;
        }
        .analytics-card:hover::before {
            content: none;
        }
        #analyticsGrid .apexcharts-canvas {
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }
        #analyticsGrid .apexcharts-donut,
        #analyticsGrid .apexcharts-pie {
            max-width: 100%;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.08));
            transition: filter 0.3s ease;
        }
        #analyticsGrid .analytics-card:hover .apexcharts-donut,
        #analyticsGrid .analytics-card:hover .apexcharts-pie {
            filter: drop-shadow(0 8px 12px rgba(0,0,0,0.15));
        }
        #analyticsGrid .apexcharts-donut-label,
        #analyticsGrid .apexcharts-pie-label {
            font-family: 'Inter', sans-serif !important;
            font-size: 10px !important;
        }
        #analyticsGrid .apexcharts-donut {
            position: relative;
        }
        #analyticsGrid .apexcharts-donut::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            pointer-events: none;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
        }
        #analyticsGrid .analytics-card {
            background: #FFFFFF;
        }
        #analyticsGrid {
            position: relative;
            isolation: isolate;
        }
        #analyticsGrid .analytics-card-pie:hover {
            z-index: 30;
        }
        #analyticsGrid .apexcharts-canvas,
        #analyticsGrid .apexcharts-canvas > *,
        #analyticsGrid .apexcharts-tooltip,
        #analyticsGrid .apexcharts-tooltip * {
            overflow: visible !important;
        }
        #analyticsGrid .apexcharts-tooltip {
            z-index: 1000 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 10px !important;
            box-shadow: none !important;
            padding: 6px 9px !important;
            font-family: 'Inter', sans-serif !important;
            pointer-events: none !important;
        }
        #analyticsGrid .apexcharts-tooltip-title {
            font-weight: 800 !important;
            font-size: 10px !important;
            color: #ffffff !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
            margin-bottom: 6px !important;
            padding-bottom: 5px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        #analyticsGrid .apexcharts-tooltip-series {
            font-weight: 900 !important;
            font-size: 22px !important;
            color: #ffffff !important;
            line-height: 1 !important;
        }
        #analyticsGrid .apexcharts-tooltip-text {
            font-size: 9px !important;
            font-weight: 700 !important;
            color: rgba(255, 255, 255, 0.72) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
        }
        #analyticsGrid .apexcharts-tooltip-percent {
            font-weight: 800 !important;
            font-size: 11px !important;
            color: #ffffff !important;
        }
        #analyticsGrid .apexcharts-tooltip-y-value {
            color: #ffffff !important;
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
            border: 1px solid #e2e8f0;
            background: linear-gradient(155deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 8px 22px -18px rgba(15, 23, 42, 0.55);
        }
        .analytics-card-pie:hover {
            border-color: #cbd5e1;
            box-shadow: 0 18px 32px -22px rgba(15, 23, 42, 0.55);
        }
        .analytics-legend-item {
            transition: all 0.15s ease;
            line-height: 1;
            border: 1px solid transparent;
            backdrop-filter: blur(4px);
        }
        .analytics-legend-item:hover {
            background: rgba(255,255,255,0.85);
            border-color: rgba(203, 213, 225, 0.6);
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }
        .analytics-legend-wrapper {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 4px;
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
        #matrixScheduleRow .apexcharts-canvas,
        #matrixBudgetRow .apexcharts-canvas,
        #matrixCostRow .apexcharts-canvas {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        .matrix-chart-card {
            position: relative;
            overflow: visible;
            background: linear-gradient(155deg, #ffffff 0%, #f8fafc 100%) !important;
            border-color: #e5eaf1 !important;
        }
        .matrix-chart-card::before {
            content: '';
            position: absolute;
            inset: 0 0 auto;
            height: 2px;
            background: linear-gradient(90deg, #00adc5, #38bdf8, #818cf8);
            opacity: 0.72;
            pointer-events: none;
        }
        .matrix-chart-card:hover {
            transform: translateY(-3px);
            border-color: #cbd5e1 !important;
            box-shadow: 0 12px 24px -16px rgba(15, 23, 42, 0.35);
        }
        .matrix-chart-heading {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            min-height: 39px;
        }
        .matrix-chart-heading-mark {
            width: 7px;
            height: 7px;
            margin-top: 2px;
            flex: none;
            border-radius: 999px;
            background: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.12);
        }
        #matrixScheduleRow .apexcharts-svg,
        #matrixBudgetRow .apexcharts-svg,
        #matrixCostRow .apexcharts-svg {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        .matrix-section-container {
            border: 1px solid #e2e8f0 !important;
            box-shadow: none !important;
        }
        #projectPerformanceMatrix {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        #matrixScheduleRow .apexcharts-bar-area,
        #matrixBudgetRow .apexcharts-bar-area,
        #matrixCostRow .apexcharts-bar-area {
            filter: drop-shadow(0 2px 2px rgba(15, 23, 42, 0.1));
            transition: filter 0.18s ease;
        }
        #matrixScheduleRow .apexcharts-bar-area:hover,
        #matrixBudgetRow .apexcharts-bar-area:hover,
        #matrixCostRow .apexcharts-bar-area:hover {
            filter: drop-shadow(0 4px 4px rgba(15, 23, 42, 0.18));
        }
        #matrixScheduleRow .apexcharts-xaxis-label,
        #matrixBudgetRow .apexcharts-xaxis-label,
        #matrixCostRow .apexcharts-xaxis-label {
            white-space: normal !important;
            text-overflow: unset !important;
            overflow: visible !important;
            max-height: none !important;
        }
        .matrix-sector-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: center;
        }
        .matrix-sector-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 4px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .analytics-card-insight {
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
            border-radius: 8px;
            padding: 6px 8px;
            margin-top: 8px;
        }
        .analytics-insight-primary {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .analytics-insight-secondary {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .analytics-insight-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .analytics-insight-status::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }
    </style>
</x-app-layout>
