<x-app-layout>
<div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                <i data-lucide="folder-open" class="w-4 h-4"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                    Risk <span class="font-black text-slate-900">Pool</span>
                </h2>
                <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Centralized risk repository</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($isAdmin)
                <button onclick="openSpreadsheetEditor()" type="button"
                    class="group/btn relative overflow-hidden rounded-xl bg-slate-900 px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-slate-900/20 transition-all hover:scale-105 active:scale-95">
                    <span class="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-[#00ADC5] to-blue-500 transition-transform duration-500 group-hover/btn:translate-x-0"></span>
                    <span class="relative flex items-center gap-1.5"><i data-lucide="plus" class="h-3 w-3"></i>Add New Risk</span>
                </button>
            @endif
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-white/90"></i>
                <span class="text-base font-black text-white tracking-tight" id="riskPoolSize">{{ $stats['total'] }}</span>
                <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Risks</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    @php
        $riskPoolFiltersActive = request()->anyFilled(['search', 'risk_category', 'overall_impact_level']);
    @endphp
    <form id="riskPoolFilters" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm" autocomplete="off">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-{{ $riskPoolFiltersActive ? '6' : '5' }}" style="grid-template-columns: repeat({{ $riskPoolFiltersActive ? 6 : 5 }}, minmax(0, 1fr));">
            <label class="space-y-1 sm:col-span-2">
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Risks</span>
                <span class="relative block">
                    <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input id="riskPoolSearch" type="search" name="search" value="{{ request('search') }}"
                        placeholder="Risk ID, risk, category, stakeholder, explanation"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                </span>
            </label>
            <label class="space-y-1">
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Risk Category</span>
                <select id="riskPoolCategory" name="risk_category" onchange="riskPoolRefresh()"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    <option value="all" @selected(request('risk_category', 'all') === 'all')>All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(request('risk_category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </label>
            <label class="space-y-1">
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Overall Impact</span>
                <select id="riskPoolImpact" name="overall_impact_level" onchange="riskPoolRefresh()"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    <option value="all" @selected(request('overall_impact_level', 'all') === 'all')>All Impacts</option>
                    @foreach(\App\Models\ProjectRiskPool::IMPACT_LEVELS as $level)
                        <option value="{{ $level }}" @selected(request('overall_impact_level') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
            </label>
            @if($riskPoolFiltersActive)
                <div class="flex items-end justify-end">
                    <a href="{{ route('admin.projects.risk-pool.index') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-600 rounded-lg border border-rose-200 hover:bg-rose-500 hover:text-white transition-all active:scale-95 shadow-sm text-[9px] font-black uppercase tracking-widest">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </form>

    <!-- Table View -->
    <div id="risk-pool-table">
        @include('admin.projects.risk-pool.partials._table')
    </div>
</div>

<!-- Spreadsheet Editor Modal -->
<div id="riskPoolEditorModal"
    class="fixed inset-0 z-50 hidden items-start justify-center bg-slate-900/40 p-4 backdrop-blur-sm"
    style="position:fixed; inset:0; z-index:50; display:none; align-items:flex-start; justify-content:center; padding:1rem; background-color:rgba(15,23,42,0.4);">
    <div class="flex max-h-[92vh] w-full max-w-7xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
        style="display:flex; flex-direction:column; max-height:92vh; width:100%; max-width:80rem; overflow:hidden; border-radius:1rem; background-color:white;">
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const isAdmin = @json($isAdmin);

    const riskPoolState = {
        search:       @json(request('search')),
        category:     @json(request('risk_category')),
        impact:       @json(request('overall_impact_level')),
        sort:         @json($sort),
        direction:    @json($direction),
        view:         @json($viewMode),
        filtersActive: {{ (request()->filled('search') || request('risk_category','all') !== 'all' || request('overall_impact_level','all') !== 'all') ? 'true' : 'false' }},
    };

    const riskPoolSearch   = document.getElementById('riskPoolSearch');
    const riskPoolCategory = document.getElementById('riskPoolCategory');
    const riskPoolImpact   = document.getElementById('riskPoolImpact');

    function riskPoolBuildQS(extra = {}) {
        const params = new URLSearchParams();
        if (riskPoolState.search) params.set('search', riskPoolState.search);
        if (riskPoolState.category && riskPoolState.category !== 'all') params.set('risk_category', riskPoolState.category);
        if (riskPoolState.impact && riskPoolState.impact !== 'all') params.set('overall_impact_level', riskPoolState.impact);
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

    function riskPoolFilterByImpact(level) {
        riskPoolState.impact = level;
        if (riskPoolImpact) riskPoolImpact.value = level;
        refreshAll();
        riskPoolPushState();
        updateClearFiltersVisibility();
    }

    function riskPoolClearFilters() {
        riskPoolState.search = ''; riskPoolState.category = ''; riskPoolState.impact = '';
        if (riskPoolSearch) riskPoolSearch.value = '';
        if (riskPoolCategory) riskPoolCategory.value = 'all';
        if (riskPoolImpact) riskPoolImpact.value = 'all';
        refreshAll();
        riskPoolPushState();
        updateClearFiltersVisibility();
    }

    function updateClearFiltersVisibility() {
        const btn = document.getElementById('riskPoolClearBtn');
        if (!btn) return;
        const active = (riskPoolState.search && riskPoolState.search.trim() !== '')
            || (riskPoolState.category && riskPoolState.category !== 'all')
            || (riskPoolState.impact && riskPoolState.impact !== 'all');
        btn.style.display = active ? 'inline-flex' : 'none';
    }

    function riskPoolRefresh() {
        if (riskPoolSearch) riskPoolState.search = riskPoolSearch.value;
        if (riskPoolCategory) riskPoolState.category = riskPoolCategory.value;
        if (riskPoolImpact) riskPoolState.impact = riskPoolImpact.value;
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
    const COLS = ['risk_id', 'risk_category', 'specific_risk', 'detailed_risk_explanation', 'impact_on_timeline', 'impact_on_cost', 'overall_impact_level', 'stakeholder', 'recommended_mitigation_measures'];

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
    }

    async function openSpreadsheetEditor(riskId) {
        const modal = document.getElementById('riskPoolEditorModal');
        const content = document.getElementById('spreadsheetModalContent');
        const title = document.getElementById('spreadsheetModalTitle');
        const subtitle = document.getElementById('spreadsheetModalSubtitle');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        spreadsheetDirty = false;
        if (title) title.textContent = riskId ? 'Edit Risk' : 'Add New Risk';
        if (subtitle) subtitle.textContent = riskId ? 'Edit risk details in the spreadsheet' : 'Bulk-edit multiple risks in the spreadsheet';
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
            select.addEventListener('change', () => { markDirty(); if (name === 'risk_category') maybeAutoSuggest(ri); });
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
            btn.className = 'shrink-0 rounded border border-slate-200 bg-slate-50 p-1 text-slate-400 hover:border-[#00ADC5] hover:text-[#00ADC5]';
            btn.onclick = () => suggestRiskId(ri);
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
            tr.appendChild(elWrap(elSelect('risk_category', state.categories, row.risk_category, ri, 'Select category')));
            tr.appendChild(elWrap(elInput('specific_risk', row.specific_risk, ri)));
            tr.appendChild(elWrap(elTextarea('detailed_risk_explanation', row.detailed_risk_explanation, ri, 2)));
            tr.appendChild(elWrap(elSelect('impact_on_timeline', state.impactLevels, row.impact_on_timeline, ri, 'Select impact')));
            tr.appendChild(elWrap(elSelect('impact_on_cost', state.impactLevels, row.impact_on_cost, ri, 'Select impact')));
            tr.appendChild(elWrap(elSelect('overall_impact_level', state.impactLevels, row.overall_impact_level, ri, 'Select impact')));
            tr.appendChild(elWrap(elTextarea('stakeholder', row.stakeholder, ri, 2)));
            tr.appendChild(elWrap(elTextarea('recommended_mitigation_measures', row.recommended_mitigation_measures, ri, 2)));
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
        function addRow() { state.rows.push({}); renderRows(); const lr = state.rows.length - 1; const first = tbody.querySelector('tr[data-row-index="' + lr + '"] input.ss-input, tr[data-row-index="' + lr + '"] select.ss-select'); if (first) first.focus(); }
        function removeRow(ri) { if (!confirm('Remove this row? It has not been saved.')) return; state.rows.splice(ri, 1); renderRows(); }
        async function suggestRiskId(ri) {
            const category = getCellValue(ri, 'risk_category');
            if (!category) { showNotification('Please select a risk category first.', 'error'); return; }
            try {
                const r = await fetch(riskPoolSuggestRoute + '?category=' + encodeURIComponent(category));
                const data = await r.json();
                if (data.risk_id) { const input = getCellInput(ri, 'risk_id'); if (input) input.value = data.risk_id; }
            } catch (e) { console.error(e); }
        }
        function maybeAutoSuggest(ri) { const input = getCellInput(ri, 'risk_id'); if (input && !input.value) suggestRiskId(ri); }
        function getCellInput(ri, field) { return tbody.querySelector('input.ss-input[name="rows[' + ri + '][' + field + ']"], select.ss-select[name="rows[' + ri + '][' + field + ']"], textarea.ss-textarea[name="rows[' + ri + '][' + field + '"]'); }
        function getCellValue(ri, field) { const el = getCellInput(ri, field); return el ? el.value : ''; }
        function collectRows() {
            const rows = [];
            tbody.querySelectorAll('tr.ss-row').forEach(tr => {
                const ri = parseInt(tr.dataset.rowIndex, 10);
                const row = (state.rows[ri] || {});
                const collected = { id: row.id || null, risk_id: '', risk_category: '', specific_risk: '', detailed_risk_explanation: '', impact_on_timeline: '', impact_on_cost: '', overall_impact_level: '', stakeholder: '', recommended_mitigation_measures: '' };
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
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ rows: rows }),
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
            errorsBox.innerHTML = '<p class="mb-2">' + message + '</p>';
            errorsBox.classList.remove('hidden');
            renderIcons();
        }

        addForm?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(addForm);
            try {
                const response = await fetch(state.routes.store, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
                    body: new URLSearchParams(formData),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.message || 'Unable to add category.');
                addForm.reset();
                await reloadCategoryManager();
            } catch (error) { renderCatError(error.message); }
        });

        // Inline rename / code / order: save on blur.
        container.querySelectorAll('.cat-edit, .cat-order').forEach(inp => {
            inp.addEventListener('blur', async () => {
                const id = inp.dataset.id;
                const field = inp.dataset.field;
                const value = inp.value;
                const payload = {};
                payload[field] = field === 'display_order' ? Number(value) : value;
                try {
                    const response = await fetch(state.routes.update.replace('__ID__', id), {
                        method: 'PUT',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) throw new Error(data.message || 'Unable to update category.');
                    showNotification('Category updated.');
                } catch (error) { renderCatError(error.message); }
            });
        });

        // Active toggle: save on change.
        container.querySelectorAll('.cat-toggle').forEach(toggle => {
            toggle.addEventListener('change', async () => {
                const id = toggle.dataset.id;
                try {
                    const response = await fetch(state.routes.update.replace('__ID__', id), {
                        method: 'PUT',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ is_active: toggle.checked ? 1 : 0 }),
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) throw new Error(data.message || 'Unable to update category.');
                    showNotification('Category updated.');
                } catch (error) { renderCatError(error.message); }
            });
        });

        // Delete (deactivates when in use, else hard-deletes per backend rule).
        container.querySelectorAll('[data-action="delete"]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;
                if (!confirm('Remove this category? Existing risk records referencing it will be preserved.')) return;
                try {
                    const response = await fetch(state.routes.destroy.replace('__ID__', id), {
                        method: 'DELETE',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) throw new Error(data.message || 'Unable to remove category.');
                    await reloadCategoryManager();
                } catch (error) { renderCatError(error.message); }
            });
        });

        async function reloadCategoryManager() {
            try {
                const response = await fetch(riskPoolCategoryRoute);
                const html = await response.text();
                const modal = document.getElementById('riskPoolCategoryModal');
                const content = document.getElementById('categoryModalContent');
                content.innerHTML = html;
                initCategoryManager(content);
                renderIcons();
                closeCategoryModal();
                refreshSummary();
                refreshView();
            } catch (e) { console.error(e); }
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
