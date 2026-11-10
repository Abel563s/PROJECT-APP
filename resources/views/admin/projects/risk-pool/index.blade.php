<x-app-layout>
@include('admin.projects.risk-pool.partials._tabs', ['activeTab' => 'risk-pool'])
 <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
       <!-- Search & Filter -->
    @php
        $riskPoolFiltersActive = trim((string) request('search')) !== ''
            || request('risk_type', 'all') !== 'all'
            || request('risk_category', 'all') !== 'all'
            || request('overall_impact_level', 'all') !== 'all'
            || request('impact_on_timeline', 'all') !== 'all'
            || request('impact_on_cost', 'all') !== 'all';
        $riskPoolAdvancedFiltersActive = request()->anyFilled(['impact_on_timeline', 'impact_on_cost', 'overall_impact_level']);
    @endphp
    <form id="riskPoolFilters" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm" autocomplete="off">
        <div class="admin-filter-grid gap-3">
            <label class="space-y-1">
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Risks</span>
                <span class="relative block">
                    <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input id="riskPoolSearch" type="search" name="search" value="{{ request('search') }}"
                        placeholder="Risk ID, risk, category, stakeholder"
                    
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                </span>
            </label>
            <label class="space-y-1">
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Risk Type</span>
                <select id="riskPoolType" name="risk_type" onchange="riskPoolTypeChanged()"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    <option value="all" @selected(request('risk_type', 'all') === 'all')>All Types</option>
                    @foreach(\App\Models\ProjectRiskPool::RISK_TYPES as $type)
                        <option value="{{ $type }}" @selected(request('risk_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1">
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Risk Category</span>
                <select id="riskPoolCategory" name="risk_category" onchange="riskPoolRefresh()"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    <option value="all" @selected(request('risk_category', 'all') === 'all')>All Categories</option>
                </select>
            </label>
            <div class="flex flex-wrap items-end gap-2">
                <button type="button" aria-controls="riskPoolAdvancedFilters" aria-expanded="{{ $riskPoolAdvancedFiltersActive ? 'true' : 'false' }}" onclick="toggleFilterPanel(this, 'riskPoolAdvancedFilters')"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[9px] font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-[#00515f]">
                    <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i>More filters
                    <i data-lucide="chevron-down" class="filter-panel-chevron h-3.5 w-3.5 transition-transform {{ $riskPoolAdvancedFiltersActive ? 'rotate-180' : '' }}"></i>
                </button>
                <div id="riskPoolClearWrap" class="{{ $riskPoolFiltersActive ? '' : 'hidden' }}">
                <a id="riskPoolClearBtn" href="{{ route('admin.projects.risk-pool.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-600 rounded-lg border border-rose-200 hover:bg-rose-500 hover:text-white transition-all active:scale-95 shadow-sm text-[9px] font-black uppercase tracking-widest">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>Clear Filters
                </a>
                </div>
            </div>
            <div id="riskPoolAdvancedFilters" class="admin-filter-advanced-panel {{ $riskPoolAdvancedFiltersActive ? '' : 'hidden' }} rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                <div class="admin-filter-advanced-grid">
                    <label class="space-y-1">
                        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Time</span>
                        <select id="riskPoolTimelineImpact" name="impact_on_timeline" onchange="riskPoolRefresh()"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                            <option value="all" @selected(request('impact_on_timeline', 'all') === 'all')>All Impacts</option>
                            @foreach(\App\Models\ProjectRiskPool::IMPACT_LEVELS as $level)
                                <option value="{{ $level }}" @selected(request('impact_on_timeline') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="space-y-1">
                        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Cost</span>
                        <select id="riskPoolCostImpact" name="impact_on_cost" onchange="riskPoolRefresh()"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                            <option value="all" @selected(request('impact_on_cost', 'all') === 'all')>All Impacts</option>
                            @foreach(\App\Models\ProjectRiskPool::IMPACT_LEVELS as $level)
                                <option value="{{ $level }}" @selected(request('impact_on_cost') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="space-y-1">
                        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Impact on Quality</span>
                        <select id="riskPoolImpact" name="overall_impact_level" onchange="riskPoolRefresh()"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                            <option value="all" @selected(request('overall_impact_level', 'all') === 'all')>All Impacts</option>
                            @foreach(\App\Models\ProjectRiskPool::IMPACT_LEVELS as $level)
                                <option value="{{ $level }}" @selected(request('overall_impact_level') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>
        </div>
    </form>
    @include('admin.partials.filter-panel-toggle-script')

    <!-- Table View -->
    <div id="risk-pool-table">
        @include('admin.projects.risk-pool.partials._table')
    </div>
</div>

<!-- Spreadsheet Editor Modal -->
<div id="riskPoolEditorModal"
    class="fixed inset-0 z-50 hidden items-start justify-center bg-slate-900/40 p-2 sm:p-4 backdrop-blur-sm"
    style="position:fixed; inset:0; z-index:50; display:none; align-items:flex-start; justify-content:center; padding:1rem; background-color:rgba(15,23,42,0.4);">
    <div class="flex max-h-[94vh] w-full max-w-[96vw] xl:max-w-[1580px] flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
        style="display:flex; flex-direction:column; max-height:94vh; width:100%; max-width:96vw; overflow:hidden; border-radius:1rem; background-color:white;">
        <div class="flex flex-shrink-0 items-center justify-between border-b border-[#00515f] bg-gradient-to-b from-[#00515f] to-black px-6 py-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white" id="spreadsheetModalTitle">Add New Risk</h3>
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest" id="spreadsheetModalSubtitle">Bulk-edit multiple risks in the spreadsheet</p>
                </div>
            </div>
            <button type="button" onclick="closeSpreadsheetModal()" aria-label="Close"
                class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white/80 transition-colors hover:bg-white/20">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
        <div id="spreadsheetModalContent" class="flex-1 overflow-y-auto p-5">
            <div class="flex items-center justify-center py-12">
                <div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div>
            </div>
        </div>
    </div>
</div>

<!-- Unified Risk Details Modal -->
<div id="riskPoolViewModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm"
    style="position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:1rem; background-color:rgba(15,23,42,0.4);">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh]"
        style="width:100%; max-width:56rem; overflow:hidden; display:flex; flex-direction:column; max-height:92vh; background-color:white; border-radius:1rem;">
        <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white">Risk Details</h3>
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">View risk information</p>
                </div>
            </div>
            <button type="button" onclick="closeViewModal()" aria-label="Close"
                class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
        <div id="viewModalContent" class="p-5 overflow-y-auto flex-1">
            <div class="flex items-center justify-center py-12">
                <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
</div>

<!-- Category Manager Modal -->
<div id="riskPoolCategoryModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm"
    style="position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:1rem; background-color:rgba(15,23,42,0.4);">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[88vh]"
        style="width:100%; max-width:48rem; overflow:hidden; display:flex; flex-direction:column; max-height:88vh; background-color:white; border-radius:1rem;">
        <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white">Manage Risk Categories</h3>
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Add, rename or deactivate categories</p>
                </div>
            </div>
            <button type="button" onclick="closeCategoryModal()" aria-label="Close"
                class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
        <div id="categoryModalContent" class="p-5 overflow-y-auto flex-1">
            <div class="flex items-center justify-center py-12">
                <div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const riskPoolIndexRoute    = @json(route('admin.projects.risk-pool.index'));
    const riskPoolSuggestRoute  = @json(route('admin.projects.risk-pool.suggest-id'));
    const riskPoolBulkRoute     = @json(route('admin.projects.risk-pool.bulk-store'));
    const riskPoolCategoryRoute = @json(route('admin.projects.risk-pool.categories.index'));
    const riskPoolShowRoute     = @json(route('admin.projects.risk-pool.show', ['risk' => '__RISK__']));
    const riskPoolEditRoute     = @json(route('admin.projects.risk-pool.edit-modal', ['risk' => '__RISK__']));
    const riskPoolCreateRoute   = @json(route('admin.projects.risk-pool.create-modal'));
    const riskPoolDeleteRoute   = @json(route('admin.projects.risk-pool.destroy', ['risk' => '__RISK__']));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const canCreateRiskPool = @json($canCreateRiskPool);
    const canEditRiskPool = @json($canEditRiskPool);
    const canDeleteRiskPool = @json($canDeleteRiskPool);
    let refreshSpreadsheetCategories = null;

    const riskPoolState = {
        search:       @json(request('search')),
        riskType:     @json(request('risk_type')),
        category:     @json(request('risk_category')),
        impact:       @json(request('overall_impact_level')),
        timelineImpact: @json(request('impact_on_timeline')),
        costImpact:    @json(request('impact_on_cost')),
        sort:         @json($sort),
        direction:    @json($direction),
        view:         @json($viewMode),
        page:         @json(request('page')),
        perPage:      @json(request('per_page', 15)),
        filtersActive: {{ (request()->filled('search') || request('risk_type','all') !== 'all' || request('risk_category','all') !== 'all' || request('overall_impact_level','all') !== 'all' || request('impact_on_timeline','all') !== 'all' || request('impact_on_cost','all') !== 'all') ? 'true' : 'false' }},
    };

    const riskPoolSearch   = document.getElementById('riskPoolSearch');
    const riskPoolType     = document.getElementById('riskPoolType');
    const riskPoolCategory = document.getElementById('riskPoolCategory');
    const riskPoolImpact   = document.getElementById('riskPoolImpact');
    const riskPoolTimelineImpact = document.getElementById('riskPoolTimelineImpact');
    const riskPoolCostImpact = document.getElementById('riskPoolCostImpact');
    document.getElementById('riskPoolFilters')?.addEventListener('submit', function (event) {
        event.preventDefault();
        riskPoolRefresh();
    });

    // Initialize category dropdown based on the currently selected risk type
    const initialRiskType = riskPoolType ? riskPoolType.value : 'all';
    loadRiskPoolCategories(initialRiskType);

    function riskPoolBuildQS(extra = {}) {
        const params = new URLSearchParams();
        if (riskPoolState.search) params.set('search', riskPoolState.search);
        if (riskPoolState.riskType && riskPoolState.riskType !== 'all') params.set('risk_type', riskPoolState.riskType);
        if (riskPoolState.category && riskPoolState.category !== 'all') params.set('risk_category', riskPoolState.category);
        if (riskPoolState.impact && riskPoolState.impact !== 'all') params.set('overall_impact_level', riskPoolState.impact);
        if (riskPoolState.timelineImpact && riskPoolState.timelineImpact !== 'all') params.set('impact_on_timeline', riskPoolState.timelineImpact);
        if (riskPoolState.costImpact && riskPoolState.costImpact !== 'all') params.set('impact_on_cost', riskPoolState.costImpact);
        if (riskPoolState.page) params.set('page', riskPoolState.page);
        if (riskPoolState.perPage && riskPoolState.perPage != 15) params.set('per_page', riskPoolState.perPage);
        params.set('sort', riskPoolState.sort || 'risk_id');
        params.set('direction', riskPoolState.direction || 'asc');
        for (const k in extra) {
            const v = extra[k];
            if (v !== null && v !== '' && v !== 'all') params.set(k, v);
            else params.delete(k);
        }
        return params.toString();
    }

    function riskPoolPushState() {
        const qs = riskPoolBuildQS({ view: riskPoolState.view || null });
        const url = qs ? riskPoolIndexRoute + '?' + qs : riskPoolIndexRoute;
        window.history.replaceState({}, '', url);
    }

    function fetchPartial(partial) {
        const qs = riskPoolBuildQS();
        const url = qs ? riskPoolIndexRoute + '?' + qs + '&partial=' + partial : riskPoolIndexRoute + '?partial=' + partial;
        return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => data.html);
    }

    function renderIcons() { if (window.lucide) { try { lucide.createIcons(); } catch (e) {} } }

    async function refreshView() {
        try {
            const qs = riskPoolBuildQS();
            const html = await fetch(riskPoolIndexRoute + '?' + qs + (qs ? '&' : '') + 'partial=table', { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json()).then(d => d.html);
            const table = document.getElementById('risk-pool-table');
            if (table) { table.classList.remove('hidden'); table.innerHTML = html; }
            attachPaginationHandlers();
            renderIcons();
        } catch (e) { console.error(e); }
    }

    async function refreshSummary() {
        try {
            const html = await fetchPartial('summary');
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            const totalEl = tmp.querySelector('[data-kpi-total]');
            const sizeEl = document.getElementById('riskPoolSize');
            if (totalEl && sizeEl) sizeEl.textContent = totalEl.textContent;
            renderIcons();
        } catch (e) { console.error(e); }
    }

    async function refreshAll() { await Promise.all([refreshSummary(), refreshView()]); }

    async function deleteRiskPoolItem(riskId) {
        if (!canDeleteRiskPool) { showNotification('You do not have permission to delete risk pool items.', 'error'); return; }
        if (!confirm('Delete this risk from the pool? This cannot be undone.')) return;
        const url = riskPoolDeleteRoute.replace('__RISK__', encodeURIComponent(riskId));
        try {
            const resp = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
            });
            const data = await resp.json().catch(() => ({}));
            if (resp.ok) { showNotification(data.message || 'Risk removed from the pool.'); refreshAll(); }
            else { showNotification(data.message || 'Unable to delete the risk.', 'error'); }
        } catch (e) { console.error(e); showNotification('Unable to delete the risk.', 'error'); }
    }

    function riskPoolFilterByImpact(level) {
        riskPoolState.impact = level;
        if (riskPoolImpact) riskPoolImpact.value = level;
        refreshAll();
        riskPoolPushState();
        updateClearFiltersVisibility();
    }

    function attachPaginationHandlers() {
        const table = document.getElementById('risk-pool-table');
        if (!table) return;
        table.querySelectorAll('a[href*="page="]').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = new URL(this.href);
                riskPoolState.page = url.searchParams.get('page') || '1';
                riskPoolPushState();
                refreshView();
            });
        });
    }

     function riskPoolClearFilters() {
        riskPoolState.search = ''; riskPoolState.riskType = ''; riskPoolState.category = ''; riskPoolState.impact = ''; riskPoolState.timelineImpact = ''; riskPoolState.costImpact = ''; riskPoolState.page = '';
        if (riskPoolSearch) riskPoolSearch.value = '';
        if (riskPoolType) riskPoolType.value = 'all';
        if (riskPoolCategory) riskPoolCategory.value = 'all';
        if (riskPoolImpact) riskPoolImpact.value = 'all';
        if (riskPoolTimelineImpact) riskPoolTimelineImpact.value = 'all';
        if (riskPoolCostImpact) riskPoolCostImpact.value = 'all';
        loadRiskPoolCategories('all').then(function () {
            refreshAll();
            riskPoolPushState();
            updateClearFiltersVisibility();
        });
    }

    function updateClearFiltersVisibility() {
        const wrap = document.getElementById('riskPoolClearWrap');
        if (!wrap) return;
        const active = (riskPoolState.search && riskPoolState.search.trim() !== '')
            || (riskPoolState.riskType && riskPoolState.riskType !== 'all')
            || (riskPoolState.category && riskPoolState.category !== 'all')
            || (riskPoolState.impact && riskPoolState.impact !== 'all')
            || (riskPoolState.timelineImpact && riskPoolState.timelineImpact !== 'all')
            || (riskPoolState.costImpact && riskPoolState.costImpact !== 'all');
        wrap.classList.toggle('hidden', !active);
    }

    function riskPoolTypeChanged() {
        riskPoolState.riskType = riskPoolType.value;
        riskPoolState.category = 'all';
        if (riskPoolCategory) {
            riskPoolCategory.innerHTML = '<option value="all">Loading categories...</option>';
            riskPoolCategory.value = 'all';
        }
        loadRiskPoolCategories(riskPoolType.value).then(function () {
            riskPoolRefresh();
        });
    }

    async function loadRiskPoolCategories(riskType) {
        try {
            const params = new URLSearchParams({ format: 'options' });
            if (riskType && riskType !== 'all') {
                params.set('risk_type', riskType);
            }
            const response = await fetch('/admin/projects/risk-pool/categories?' + params, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
            });
            if (!response.ok) throw new Error('Failed to load categories');
            const data = await response.json();
            const categories = data.categories || [];
            if (riskPoolCategory) {
                riskPoolCategory.innerHTML = '<option value="all">All Categories</option>';
                categories.forEach(function (cat) {
                    const option = document.createElement('option');
                    option.value = cat.name;
                    option.textContent = cat.name;
                    riskPoolCategory.appendChild(option);
                });
            }
        } catch (e) {
            console.error(e);
            if (riskPoolCategory) {
                riskPoolCategory.innerHTML = '<option value="all">All Categories</option>';
            }
        }
    }

    function riskPoolRefresh() {
        if (riskPoolSearch) riskPoolState.search = riskPoolSearch.value;
        if (riskPoolType) riskPoolState.riskType = riskPoolType.value;
        if (riskPoolCategory) riskPoolState.category = riskPoolCategory.value;
        if (riskPoolImpact) riskPoolState.impact = riskPoolImpact.value;
        if (riskPoolTimelineImpact) riskPoolState.timelineImpact = riskPoolTimelineImpact.value;
        if (riskPoolCostImpact) riskPoolState.costImpact = riskPoolCostImpact.value;
        riskPoolState.page = '';
        refreshAll();
        riskPoolPushState();
        updateClearFiltersVisibility();
    }

    let searchDebounce;
    if (riskPoolSearch) {
        riskPoolSearch.addEventListener('input', function () {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(function () {
                riskPoolState.search = riskPoolSearch.value;
                refreshAll();
                riskPoolPushState();
      updateClearFiltersVisibility();
     attachPaginationHandlers();

            }, 400);
        });
    }

    function showNotification(message, type) {
        type = type || 'success';
        let container = document.getElementById('riskPoolNotification');
        if (!container) {
            container = document.createElement('div');
            container.id = 'riskPoolNotification';
            container.className = 'fixed top-20 right-6 z-[60] flex flex-col gap-3 w-80';
            document.body.appendChild(container);
        }
        const el = document.createElement('div');
        el.className = 'rounded-2xl border p-4 shadow-xl flex items-center gap-3 text-sm font-bold ' + (type === 'error'
            ? 'bg-rose-50 border-rose-100 text-rose-800' : 'bg-emerald-50 border-emerald-100 text-emerald-800');
        const icon = type === 'error' ? 'alert-circle' : 'check-circle';
        el.innerHTML = '<i data-lucide="' + icon + '" class="w-4 h-4"></i><span>' + message + '</span>';
        container.appendChild(el);
        renderIcons();
        setTimeout(() => { el.remove(); if (container.children.length === 0) container.remove(); }, 4500);
    }

    /* ===================== Spreadsheet Editor ===================== */
    let spreadsheetDirty = false;
    let spreadsheetContainer = null;
    const COLS = ['risk_id', 'risk_type', 'risk_category', 'specific_risk', 'impact_on_timeline', 'impact_on_timeline_description', 'impact_on_cost', 'impact_on_cost_description', 'overall_impact_level', 'impact_on_quality_description', 'other_potential_impact', 'stakeholder'];

    function openSpreadsheetModal() {
        const modal = document.getElementById('riskPoolEditorModal');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeSpreadsheetModal() {
        if (spreadsheetDirty && !confirm('You have unsaved changes in the spreadsheet. Discard them and close?')) return;
        spreadsheetDirty = false;
        const modal = document.getElementById('riskPoolEditorModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
        spreadsheetContainer = null;
        refreshSpreadsheetCategories = null;
    }

    async function openSpreadsheetEditor(riskId) {
        if (riskId && !canEditRiskPool) { showNotification('You do not have permission to edit risk pool items.', 'error'); return; }
        if (!riskId && !canCreateRiskPool && !canEditRiskPool) { showNotification('You do not have permission to manage risk pool items.', 'error'); return; }
        const modal = document.getElementById('riskPoolEditorModal');
        const content = document.getElementById('spreadsheetModalContent');
        const title = document.getElementById('spreadsheetModalTitle');
        const subtitle = document.getElementById('spreadsheetModalSubtitle');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        spreadsheetDirty = false;
        if (title) title.textContent = riskId ? 'Edit Risk' : (canCreateRiskPool ? 'Add New Risk' : 'Edit Risk Pool');
        if (subtitle) subtitle.textContent = riskId ? 'Edit risk details in the spreadsheet' : 'Manage risk pool records in the spreadsheet';
        content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div></div>';

        const url = riskId ? riskPoolEditRoute.replace('__RISK__', encodeURIComponent(riskId)) : riskPoolCreateRoute;
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Unable to load the spreadsheet editor (' + response.status + ').');
            content.innerHTML = await response.text();
            spreadsheetContainer = content;
            initSpreadsheetEditor(content);
            renderIcons();
        } catch (error) {
            console.error(error);
            content.innerHTML = '<p class="rounded-xl bg-rose-50 p-4 text-xs font-bold text-rose-700">Unable to load the spreadsheet editor. Please try again.</p>';
        }
    }

    function initSpreadsheetEditor(container) {
        const stateEl = container.querySelector('#spreadsheetState');
        if (!stateEl) return;
        const state = JSON.parse(stateEl.textContent);
        if (!Array.isArray(state.rows)) state.rows = [];

        const tbody   = container.querySelector('#ssGridBody');
        const addBtn  = container.querySelector('#ssAddRow');
        const saveBtn = container.querySelector('#ssSaveAll');
        const cancelBtn = container.querySelector('#ssCancel');
        const manageBtn = container.querySelector('#ssManageCategories');
        const rowCount = container.querySelector('#ssRowCount');
        const errorsBox = container.querySelector('#ssErrors');

        let activeCell = { row: 0, col: 0 };

        function elInput(name, value, ri) {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'rows[' + ri + '][' + name + ']';
            input.value = value ?? '';
            input.className = 'ss-input rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 w-full text-[10px] font-black text-slate-800 focus:border-[#00ADC5] focus:ring-cyan-100';
            input.dataset.ri = ri;
            input.dataset.field = name;
            if (name === 'risk_id') input.classList.add('w-28');
            if (name === 'risk_id') {
                input.addEventListener('input', () => { input.dataset.autoSuggested = 'false'; });
            }
            attach(input);
            return input;
        }
        function elSelect(name, options, value, ri, placeholder) {
            const select = document.createElement('select');
            select.name = 'rows[' + ri + '][' + name + ']';
            select.className = 'ss-select rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 w-full text-[10px] font-black text-slate-700 focus:border-[#00ADC5] focus:ring-cyan-100';
            select.dataset.ri = ri;
            select.dataset.field = name;
            const opt0 = document.createElement('option'); opt0.value = ''; opt0.textContent = placeholder; select.appendChild(opt0);
            options.forEach(o => {
                const o2 = document.createElement('option'); o2.value = o; o2.textContent = o; if (o === value) o2.selected = true; select.appendChild(o2);
            });
            select.addEventListener('change', markDirty);
            return select;
        }
        function elCategorySelect(ri, selectedValue = '', typeValue = null) {
            const type = typeValue ?? getCellValue(ri, 'risk_type');
            const row = state.rows[ri] || {};
            const select = document.createElement('select');
            select.name = 'rows[' + ri + '][risk_category]';
            select.className = 'ss-select rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 w-full text-[10px] font-black text-slate-700 focus:border-[#00ADC5] focus:ring-cyan-100';
            select.dataset.ri = ri;
            select.dataset.field = 'risk_category';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            const categories = (state.categories || []).filter(category =>
                category.risk_type === type
                || (row.id && category.name === selectedValue && !category.risk_type)
            );
            placeholder.textContent = !type
                ? 'Select type first'
                : (categories.length ? 'Select category' : 'No categories assigned to this type');
            select.appendChild(placeholder);

            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.name;
                option.textContent = category.name;
                option.selected = category.name === selectedValue;
                select.appendChild(option);
            });
            select.disabled = !type;
            select.addEventListener('change', () => {
                markDirty();
                maybeAutoSuggest(ri);
            });
            return select;
        }
        function elTextarea(name, value, ri, rows) {
            const ta = document.createElement('textarea');
            ta.name = 'rows[' + ri + '][' + name + ']';
            ta.rows = rows;
            ta.value = value ?? '';
            ta.className = 'ss-textarea rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 w-full text-[10px] font-black text-slate-800 resize-y focus:border-[#00ADC5] focus:ring-cyan-100';
            ta.dataset.ri = ri;
            ta.dataset.field = name;
            ta.style.minWidth = '120px';
            attach(ta);
            return ta;
        }
        function elWrap(cell, raw) {
            const td = document.createElement('td');
            td.className = 'px-2 py-2 align-top';
            if (raw) td.appendChild(cell); else { td.textContent = ''; td.append(cell); }
            return td;
        }
        function riskIdCell(row, ri) {
            const wrap = document.createElement('div');
            wrap.className = 'flex items-center gap-1';
            const input = elInput('risk_id', row.risk_id, ri);
            input.classList.add('w-28');
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.innerHTML = '<i data-lucide="sparkles" class="w-3 h-3"></i>';
            btn.title = 'Suggest next ID';
            btn.setAttribute('aria-label', 'Suggest next Risk ID');
            btn.className = 'shrink-0 rounded border border-slate-200 bg-slate-50 p-1 text-slate-400 hover:border-[#00ADC5] hover:text-[#00ADC5]';
            btn.onclick = () => suggestRiskId(ri, btn);
            wrap.appendChild(input); wrap.appendChild(btn);
            return wrap;
        }
        function actionCell(row, ri) {
            const td = document.createElement('td');
            td.className = 'px-2 py-2 align-top text-center';
            if (!row.id) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.innerHTML = '<i data-lucide="trash-2" class="w-3.5 h-3.5"></i>';
                btn.title = 'Remove this unsaved row';
                btn.className = 'rounded border border-slate-200 bg-white p-1 text-slate-400 hover:text-rose-500 hover:border-rose-500';
                btn.onclick = () => removeRow(ri);
                td.appendChild(btn);
            } else {
                const sp = document.createElement('span');
                sp.className = 'inline-flex items-center rounded-lg border border-slate-200 bg-white px-2 py-1 text-[9px] font-black text-slate-400';
                sp.textContent = 'Saved';
                td.appendChild(sp);
            }
            return td;
        }
        function attach(el) {
            el.addEventListener('focus', () => { activeCell.row = +el.dataset.ri; activeCell.col = COLS.indexOf(el.dataset.field); });
            el.addEventListener('input', markDirty);
            el.addEventListener('keydown', e => {
                if (e.key === 'Tab') {
                    const inputs = [...tbody.querySelectorAll('input.ss-input, select.ss-select, textarea.ss-textarea')];
                    const idx = inputs.indexOf(document.activeElement);
                    if (e.shiftKey) { if (idx > 0) { inputs[idx - 1].focus(); e.preventDefault(); } }
                    else { if (idx < inputs.length - 1) { inputs[idx + 1].focus(); e.preventDefault(); } }
                }
            });
        }
        function markDirty() { spreadsheetDirty = true; }
        function updateRowCount() {
            const c = state.rows.length;
            if (rowCount) rowCount.textContent = c + ' row' + (c === 1 ? '' : 's');
        }
        function renderRow(row, ri) {
            row = row || {};
            const tr = document.createElement('tr');
            tr.className = 'ss-row border-b border-slate-100/60';
            tr.dataset.rowIndex = ri;
            tr.appendChild(elWrap(riskIdCell(row, ri)));
            const typeSelect = elSelect('risk_type', state.riskTypes, row.risk_type, ri, 'Select type');
            tr.appendChild(elWrap(typeSelect));
            const categoryCell = elWrap(elCategorySelect(ri, row.risk_category, row.risk_type));
            typeSelect.addEventListener('change', () => {
                const categorySelect = categoryCell.querySelector('select');
                const selectedCategory = (state.categories || []).find(category => category.name === categorySelect?.value);
                const keepCategory = selectedCategory?.risk_type === typeSelect.value;
                categoryCell.replaceChildren(elCategorySelect(ri, keepCategory ? selectedCategory.name : '', typeSelect.value));
            });
            tr.appendChild(categoryCell);
            tr.appendChild(elWrap(elInput('specific_risk', row.specific_risk, ri)));

            // Timeline (Select + Textbox below)
            const timelineBox = document.createElement('div');
            timelineBox.className = 'flex flex-col gap-1.5 min-w-[140px]';
            timelineBox.appendChild(elSelect('impact_on_timeline', state.impactLevels, row.impact_on_timeline, ri, 'Select impact'));
            const timelineDesc = elInput('impact_on_timeline_description', row.impact_on_timeline_description, ri);
            timelineDesc.placeholder = 'Timeline details...';
            timelineBox.appendChild(timelineDesc);
            tr.appendChild(elWrap(timelineBox, true));

            // Cost (Select + Textbox below)
            const costBox = document.createElement('div');
            costBox.className = 'flex flex-col gap-1.5 min-w-[140px]';
            costBox.appendChild(elSelect('impact_on_cost', state.impactLevels, row.impact_on_cost, ri, 'Select impact'));
            const costDesc = elInput('impact_on_cost_description', row.impact_on_cost_description, ri);
            costDesc.placeholder = 'Cost details...';
            costBox.appendChild(costDesc);
            tr.appendChild(elWrap(costBox, true));

            // Quality (Select + Textbox below)
            const qualityBox = document.createElement('div');
            qualityBox.className = 'flex flex-col gap-1.5 min-w-[140px]';
            qualityBox.appendChild(elSelect('overall_impact_level', state.impactLevels, row.overall_impact_level, ri, 'Select impact'));
            const qualityDesc = elInput('impact_on_quality_description', row.impact_on_quality_description, ri);
            qualityDesc.placeholder = 'Quality details...';
            qualityBox.appendChild(qualityDesc);
            tr.appendChild(elWrap(qualityBox, true));

            // Other Potential Impact (textarea)
            tr.appendChild(elWrap(elTextarea('other_potential_impact', row.other_potential_impact, ri, 2)));

            tr.appendChild(elWrap(elTextarea('stakeholder', row.stakeholder, ri, 2)));
            tr.appendChild(actionCell(row, ri));
            return tr;
        }
        let pasteBound = false;
        function renderRows() {
            tbody.innerHTML = '';
            state.rows.forEach((row, ri) => tbody.appendChild(renderRow(row, ri)));
            updateRowCount();
            if (!pasteBound) { bindPaste(); pasteBound = true; }
            renderIcons();
        }
        refreshSpreadsheetCategories = async () => {
            const response = await fetch(riskPoolCategoryRoute + '?format=options', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok || !Array.isArray(data.categories)) {
                throw new Error(data.message || 'Unable to refresh risk categories.');
            }
            syncStateFromDOM();
            state.categories = data.categories;
            state.rows = state.rows.map(row => {
                const category = state.categories.find(option => option.name === row.risk_category);
                return category?.risk_type ? { ...row, risk_type: category.risk_type } : row;
            });
            renderRows();
        };
        function syncStateFromDOM() {
            state.rows = collectRows();
        }
        function addRow() { syncStateFromDOM(); state.rows.unshift({}); renderRows(); const first = tbody.querySelector('tr[data-row-index="0"] input.ss-input, tr[data-row-index="0"] select.ss-select'); if (first) first.focus(); }
        function removeRow(ri) { syncStateFromDOM(); if (!confirm('Remove this row? It has not been saved.')) return; state.rows.splice(ri, 1); renderRows(); }
        async function suggestRiskId(ri, button = null) {
            const category = getCellValue(ri, 'risk_category');
            if (!category) { showNotification('Please select a risk category first.', 'error'); return; }
            if (button) button.disabled = true;
            try {
                const riskType = getCellValue(ri, 'risk_type');
                const query = new URLSearchParams({ category, risk_type: riskType });
                const r = await fetch(riskPoolSuggestRoute + '?' + query.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                });
                const data = await r.json().catch(() => ({}));
                const validationMessage = Object.values(data.errors || {}).flat()[0];
                if (!r.ok) throw new Error(validationMessage || data.message || 'Unable to suggest a Risk ID.');
                if (!data.risk_id) throw new Error('No Risk ID prefix is assigned to this category.');
                if (getCellValue(ri, 'risk_category') !== category || getCellValue(ri, 'risk_type') !== riskType) return;
                const input = getCellInput(ri, 'risk_id');
                if (input) {
                    input.value = nextAvailableSuggestedId(data.risk_id, input);
                    input.dataset.autoSuggested = 'true';
                    markDirty();
                }
            } catch (error) {
                console.error(error);
                showNotification(error.message || 'Unable to suggest a Risk ID.', 'error');
            } finally {
                if (button) button.disabled = false;
            }
        }
        function nextAvailableSuggestedId(suggested, currentInput) {
            const match = suggested.match(/^(\d+)\.(\d+)$/);
            if (!match) return suggested;

            const prefix = match[1];
            let suffix = Number(match[2]);
            const usedIds = new Set(
                [...tbody.querySelectorAll('input[data-field="risk_id"]')]
                    .filter(input => input !== currentInput)
                    .map(input => input.value.trim())
            );
            let candidate = suggested;
            while (usedIds.has(candidate)) {
                suffix += 1;
                candidate = prefix + '.' + String(suffix).padStart(2, '0');
            }

            return candidate;
        }
        function maybeAutoSuggest(ri) {
            const input = getCellInput(ri, 'risk_id');
            if (input && (!input.value || input.dataset.autoSuggested === 'true')) suggestRiskId(ri);
        }
        function getCellInput(ri, field) {
            return tbody.querySelector('tr[data-row-index="' + ri + '"] [data-field="' + field + '"]');
        }
        function getCellValue(ri, field) { const el = getCellInput(ri, field); return el ? el.value : ''; }
        function collectRows() {
            const rows = [];
            tbody.querySelectorAll('tr.ss-row').forEach(tr => {
                const ri = parseInt(tr.dataset.rowIndex, 10);
                const row = (state.rows[ri] || {});
                const collected = {
                    id: row.id || null,
                    risk_id: '',
                    risk_type: '',
                    risk_category: '',
                    specific_risk: '',
                    impact_on_timeline: '',
                    impact_on_timeline_description: '',
                    impact_on_cost: '',
                    impact_on_cost_description: '',
                    overall_impact_level: '',
                    impact_on_quality_description: '',
                    other_potential_impact: '',
                    stakeholder: ''
                };
                tr.querySelectorAll('input.ss-input, select.ss-select, textarea.ss-textarea').forEach(el => { if (el.dataset.field) collected[el.dataset.field] = el.value; });
                rows.push(collected);
            });
            return rows;
        }
        function validateRows(rows) {
            const errors = {};
            const seenIds = {};
            rows.forEach((row, i) => {
                const base = 'rows.' + i;
                if (!row.risk_id) (errors[base + '.risk_id'] = errors[base + '.risk_id'] || []).push('Risk ID is required.');
                if (!row.id && !row.risk_type) (errors[base + '.risk_type'] = errors[base + '.risk_type'] || []).push('Risk type is required.');
                if (!row.risk_category) (errors[base + '.risk_category'] = errors[base + '.risk_category'] || []).push('Risk category is required.');
                if (!row.specific_risk) (errors[base + '.specific_risk'] = errors[base + '.specific_risk'] || []).push('Specific risk is required.');
                if (row.risk_id && seenIds[row.risk_id] !== undefined) {
                    (errors[base + '.risk_id'] = errors[base + '.risk_id'] || []).push("Duplicate Risk ID '" + row.risk_id + "' within the batch.");
                } else { seenIds[row.risk_id] = i; }
            });
            return Object.keys(errors).length ? errors : null;
        }
        function clearHighlights() { tbody.querySelectorAll('tr.ss-row').forEach(tr => tr.classList.remove('bg-rose-50/60')); }
        function highlightRow(i) { const tr = tbody.querySelector('tr[data-row-index="' + i + '"]'); if (tr) tr.classList.add('bg-rose-50/60'); }
        function renderSheetErrors(rowErrors, summary) {
            if (!errorsBox) return;
            let html = '<p class="mb-2">' + (summary || 'Please correct the highlighted rows before saving:') + '</p><ul class="list-inside list-disc space-y-1">';
            Object.keys(rowErrors).forEach(field => rowErrors[field].forEach(msg => { html += '<li>' + field + ': ' + msg + '</li>'; }));
            html += '</ul>';
            errorsBox.innerHTML = html;
            errorsBox.classList.remove('hidden');
            renderIcons();
        }
        async function saveAll() {
            clearHighlights();
            errorsBox && errorsBox.classList.add('hidden');
            const rows = collectRows();
            const clientErrors = validateRows(rows);
            if (clientErrors) { renderSheetErrors(clientErrors); Object.keys(clientErrors).forEach(f => { const m = f.match(/^rows\.(\d+)\./); if (m) highlightRow(parseInt(m[1], 10)); }); return; }
            const original = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="flex items-center gap-1.5"><span class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>Saving...</span>';
            try {
                const response = await fetch(riskPoolBulkRoute, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': state.csrf || csrfToken, Accept: 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ _token: state.csrf || csrfToken, rows: rows }),
                });
                const data = await response.json().catch(() => ({}));
                if (response.status === 422) {
                    const errs = data.errors || {};
                    const rowErrors = {};
                    Object.keys(errs).forEach(field => {
                        const m = field.match(/^rows\.(\d+)\.(.+)$/);
                        if (m) { rowErrors[field] = errs[field]; highlightRow(parseInt(m[1], 10)); }
                        else { rowErrors[field] = errs[field]; }
                    });
                    renderSheetErrors(rowErrors, data.message || 'Please correct the highlighted rows.');
                    return;
                }
                if (!response.ok) { showNotification(data.message || 'Unable to save risks.', 'error'); return; }
                spreadsheetDirty = false;
                showNotification(data.message || 'Risks saved successfully.');
                closeSpreadsheetModal();
                refreshSummary();
                refreshView();
            } catch (e) {
                console.error(e);
                showNotification('Unable to save risks. Please try again.', 'error');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = original;
            }
        }
        function bindPaste() {
            tbody.addEventListener('paste', function (e) {
                const text = (e.clipboardData || window.clipboardData).getData('text');
                if (!text) return;
                const pasted = text.split(/\r?\n/).filter(r => r !== '').map(r => r.split('\t'));
                e.preventDefault();
                syncStateFromDOM();
                let startRow = activeCell.row >= 0 ? activeCell.row : state.rows.length - 1;
                if (startRow < 0) startRow = 0;
                const startCol = Math.min(activeCell.col >= 0 ? activeCell.col : 0, COLS.length - 1);
                pasted.forEach((pastedRow, rOffset) => {
                    const targetRow = startRow + rOffset;
                    while (targetRow >= state.rows.length) state.rows.push({});
                    pastedRow.forEach((cellValue, cOffset) => {
                        const col = startCol + cOffset;
                        if (col >= COLS.length) return;
                        state.rows[targetRow] = state.rows[targetRow] || {};
                        state.rows[targetRow][COLS[col]] = cellValue;
                    });
                });
                renderRows();
                markDirty();
            });
        }

        addBtn?.addEventListener('click', addRow);
        cancelBtn?.addEventListener('click', closeSpreadsheetModal);
        saveBtn?.addEventListener('click', saveAll);
        manageBtn?.addEventListener('click', () => { openCategoryManager(); });
        renderRows();
        renderIcons();
    }

    /* ===================== Details Modal ===================== */
    async function openRiskDetails(id) {
        const modal = document.getElementById('riskPoolViewModal');
        const content = document.getElementById('viewModalContent');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="h-8 w-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div></div>';
        const url = riskPoolShowRoute.replace('__RISK__', encodeURIComponent(id)) + '?partial=1';
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Unable to load risk (' + response.status + ').');
            content.innerHTML = await response.text();
            renderIcons();
        } catch (error) {
            console.error(error);
            content.innerHTML = '<p class="rounded-xl bg-rose-50 p-4 text-xs font-bold text-rose-700">Unable to load the risk.</p>';
        }
    }

    async function editRiskFromDetails(id) {
        closeViewModal();
        await openSpreadsheetEditor(id);
    }

    function closeViewModal() {
        const modal = document.getElementById('riskPoolViewModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    /* ===================== Category Manager ===================== */
    async function openCategoryManager() {
        const modal = document.getElementById('riskPoolCategoryModal');
        const content = document.getElementById('categoryModalContent');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div></div>';
        try {
            const response = await fetch(riskPoolCategoryRoute);
            if (!response.ok) throw new Error('Unable to load categories (' + response.status + ').');
            content.innerHTML = await response.text();
            initCategoryManager(content);
            renderIcons();
        } catch (error) {
            console.error(error);
            content.innerHTML = '<p class="rounded-xl bg-rose-50 p-4 text-xs font-bold text-rose-700">Unable to load categories.</p>';
        }
    }

    function closeCategoryModal() {
        const modal = document.getElementById('riskPoolCategoryModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function initCategoryManager(container) {
        const stateEl = container.querySelector('#categoryManagerState');
        if (!stateEl) return;
        const state = JSON.parse(stateEl.textContent);
        const addForm = container.querySelector('#catAddForm');
        const errorsBox = container.querySelector('#catErrors');
        const list = container.querySelector('#catList');

        function renderCatError(message) {
            if (!errorsBox) return;
            errorsBox.textContent = message;
            errorsBox.classList.remove('hidden');
        }

        container.querySelectorAll('.cat-edit, .cat-order').forEach(input => {
            input.dataset.initialValue = input.value;
        });

        async function saveCategoryField(input) {
            const id = input.dataset.id;
            const field = input.dataset.field;
            const value = input.value;
            if (!id || !field || value === input.dataset.initialValue) return false;
            if (input.categorySavePromise) return input.categorySavePromise;

            const payload = { [field]: field === 'display_order' ? Number(value) : value };
            const token = state.csrf || csrfToken;
            input.categorySavePromise = (async () => {
                const response = await fetch(state.routes.update.replace('__ID__', id), {
                    method: 'PUT',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, Accept: 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ...payload, _token: token }),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.message || 'Unable to update category.');
                input.dataset.initialValue = value;
                if (field === 'risk_type' && refreshSpreadsheetCategories) {
                    await refreshSpreadsheetCategories();
                }
                return true;
            })();

            try {
                return await input.categorySavePromise;
            } finally {
                input.categorySavePromise = null;
            }
        }

        addForm?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(addForm);
            const submitButton = addForm.querySelector('button[type="submit"]');
            const originalButtonContent = submitButton?.innerHTML;
            const token = state.csrf || csrfToken;
            formData.set('_token', token);
            if (submitButton) submitButton.disabled = true;
            try {
                const response = await fetch(state.routes.store, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, Accept: 'application/json' },
                    body: formData,
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.message || 'Unable to add category.');
                addForm.reset();
                showNotification('Category added.');
                await reloadCategoryManager();
            } catch (error) {
                renderCatError(error.message);
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonContent;
                }
            }
        });

        // Inline rename / code / order: save on blur.
        container.querySelectorAll('.cat-edit, .cat-order').forEach(inp => {
            inp.addEventListener('blur', async () => {
                try {
                    if (await saveCategoryField(inp)) showNotification('Category updated.');
                } catch (error) { renderCatError(error.message); }
            });
        });

        // Active toggle: save on change.
        container.querySelectorAll('.cat-toggle').forEach(toggle => {
            toggle.addEventListener('change', async () => {
                const id = toggle.dataset.id;
                const wasChecked = toggle.checked;
                toggle.disabled = true;
                const token = state.csrf || csrfToken;
                try {
                    const response = await fetch(state.routes.update.replace('__ID__', id), {
                        method: 'PUT',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, Accept: 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ is_active: wasChecked ? 1 : 0, _token: token }),
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        toggle.checked = !wasChecked;
                        throw new Error(data.message || 'Unable to update category.');
                    }
                    await reloadCategoryManager(false);
                    showNotification('Category updated.');
                } catch (error) {
                    toggle.disabled = false;
                    renderCatError(error.message);
                }
            });
        });

        // Delete (deactivates when in use, else hard-deletes per backend rule).
        container.querySelectorAll('[data-action="delete"]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;
                if (!confirm('Remove this category? Existing risk records referencing it will be preserved.')) return;
                const token = state.csrf || csrfToken;
                try {
                    const response = await fetch(state.routes.destroy.replace('__ID__', id), {
                        method: 'DELETE',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, Accept: 'application/json' },
                        body: new URLSearchParams({ _token: token }),
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) throw new Error(data.message || 'Unable to remove category.');
                    showNotification('Category removed.');
                    await reloadCategoryManager();
                } catch (error) { renderCatError(error.message); }
            });
        });

        // Save button: flush all inline edits to the server, then refresh.
        const saveAllBtn = container.querySelector('#catSaveAll');
        saveAllBtn?.addEventListener('click', async () => {
            const inputs = container.querySelectorAll('.cat-edit, .cat-order');
            const saves = [...inputs].map(saveCategoryField);
            try {
                saveAllBtn.disabled = true;
                saveAllBtn.innerHTML = '<span class="flex items-center gap-1.5"><span class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>Saving...</span>';
                const results = await Promise.all(saves);
                if (results.some(Boolean)) {
                    await reloadCategoryManager();
                    showNotification('Categories saved.');
                }
            } catch (error) { renderCatError(error.message || 'Some categories failed to save.'); }
            finally {
                if (saveAllBtn) {
                    saveAllBtn.disabled = false;
                    saveAllBtn.innerHTML = '<i data-lucide="save" class="h-3 w-3"></i> Save';
                    renderIcons();
                }
            }
        });

        async function reloadCategoryManager(refreshPool = true) {
            const response = await fetch(riskPoolCategoryRoute, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Unable to reload categories.');
            const html = await response.text();
            const content = document.getElementById('categoryModalContent');
            if (content) {
                content.innerHTML = html;
                initCategoryManager(content);
                renderIcons();
            }
            if (refreshSpreadsheetCategories) await refreshSpreadsheetCategories();
            if (refreshPool) {
                refreshSummary();
                refreshView();
            }
        }
    }

    /* ===================== Modal backdrop / escape ===================== */
    function onModalBackdrop(modal, closeFn) {
        modal?.addEventListener('click', (e) => { if (e.target === e.currentTarget) closeFn(); });
    }
    onModalBackdrop(document.getElementById('riskPoolEditorModal'), () => {
        if (!spreadsheetDirty || confirm('Discard unsaved changes?')) closeSpreadsheetModal();
    });
     onModalBackdrop(document.getElementById('riskPoolViewModal'), closeViewModal);
     onModalBackdrop(document.getElementById('riskPoolCategoryModal'), closeCategoryModal);

     updateClearFiltersVisibility();

     document.addEventListener('keydown', (e) => {
         if (e.key === 'Escape') {
             closeViewModal();
             closeSpreadsheetModal();
             closeCategoryModal();
         }
     });
 </script>
@endpush
</x-app-layout>
