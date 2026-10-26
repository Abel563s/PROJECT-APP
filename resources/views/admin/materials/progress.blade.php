<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-3 border-b border-slate-100 items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Material <span class="font-black text-slate-900">Progress</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Procurement Execution Monitor</p>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 text-white/90"></i>
                    <span class="text-base font-black text-white tracking-tight">{{ $total }}</span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Materials Registered</span>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.material.index') }}"
                    class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-1.5">
                        <i data-lucide="package" class="w-3 h-3"></i>
                        Materials Registry
                    </span>
                </a>
            </div>
        </div>

        <!-- Search & Filter -->
        @php
            $progressFiltersActive = request()->anyFilled(['search', 'project_id', 'discipline', 'procurement_type', 'supply_route']);
        @endphp
        <form action="{{ route('admin.material-progress.index') }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-{{ $progressFiltersActive ? '6' : '5' }}">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Materials</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by material name or supplier..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project</span>
                    <select name="project_id" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->project_code }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Discipline</span>
                    <select name="discipline" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Disciplines</option>
                        <option value="Civil" {{ request('discipline') == 'Civil' ? 'selected' : '' }}>Civil</option>
                        <option value="MEP" {{ request('discipline') == 'MEP' ? 'selected' : '' }}>MEP</option>
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Procurement Type</span>
                    <select name="procurement_type" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Procurement Types</option>
                        @foreach(['LVA','RFQ-Local','RFQ-Import-Birr','RFQ-Import-USD','IFB-ICB','IFB-NCB','RFP','LTA'] as $t)
                            <option value="{{ $t }}" {{ request('procurement_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Supply Route</span>
                    <select name="supply_route" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Routes</option>
                        <option value="Import" {{ request('supply_route') == 'Import' ? 'selected' : '' }}>Import</option>
                        <option value="Local" {{ request('supply_route') == 'Local' ? 'selected' : '' }}>Local</option>
                    </select>
                </label>

                @if($progressFiltersActive)
                    <div class="flex items-end justify-end">
                        <a href="{{ route('admin.material-progress.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 text-rose-600 rounded-lg border border-rose-200 hover:bg-rose-500 hover:text-white transition-all active:scale-95 shadow-sm text-[9px] font-black uppercase tracking-widest">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </form>

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black">
<th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">#</th>
<th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Material Identity</th>
<th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project Site</th>
<th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Planned Status</th>
<th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Actual Status</th>
<th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Balanced Days</th>
<th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">Float</th>
<th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Completion</th>
<th class="px-6 py-3 text-right border-b border-[#00515f]/30"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($materials as $m)
<tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
    <td class="px-6 py-4">
        <span class="text-xs font-black text-slate-400">
            {{ ($materials->currentPage() - 1) * $materials->perPage() + $loop->iteration }}
        </span>
    </td>
    <td class="px-6 py-4">
        <div class="space-y-1">
            <p class="text-sm font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">
                {{ $m['material_name'] }}
            </p>
            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                {{ $m['supplier_name'] }}</p>
        </div>
    </td>
    <td class="px-6 py-4">
        <p class="text-xs font-black text-slate-700">{{ $m['project_code'] }}</p>
    </td>
@php
    $pct = $m['percent_complete'] ?? 0;
    $progressColor = match(true) {
        $pct >= 76 => ['from' => '#10b981', 'to' => '#34d399', 'bg' => 'bg-emerald-500'],
        $pct >= 51 => ['from' => '#3b82f6', 'to' => '#60a5fa', 'bg' => 'bg-blue-500'],
        $pct >= 26 => ['from' => '#f97316', 'to' => '#fb923c', 'bg' => 'bg-orange-400'],
        default => ['from' => '#ef4444', 'to' => '#f87171', 'bg' => 'bg-red-500'],
    };
    $statusColors = [
        'Design' => ['from' => '#3b82f6', 'to' => '#60a5fa', 'text' => 'text-blue-700', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500'],
        'Specification' => ['from' => '#6366f1', 'to' => '#818cf8', 'text' => 'text-indigo-700', 'bg' => 'bg-indigo-50', 'border' => 'border-indigo-200', 'dot' => 'bg-indigo-500'],
        'Request Order' => ['from' => '#8b5cf6', 'to' => '#a78bfa', 'text' => 'text-violet-700', 'bg' => 'bg-violet-50', 'border' => 'border-violet-200', 'dot' => 'bg-violet-500'],
        'Supplier Identification' => ['from' => '#a855f7', 'to' => '#c084fc', 'text' => 'text-purple-700', 'bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'dot' => 'bg-purple-500'],
        'Material Submittal' => ['from' => '#d946ef', 'to' => '#e879f9', 'text' => 'text-fuchsia-700', 'bg' => 'bg-fuchsia-50', 'border' => 'border-fuchsia-200', 'dot' => 'bg-fuchsia-500'],
        'Material Approval' => ['from' => '#ec4899', 'to' => '#f472b6', 'text' => 'text-pink-700', 'bg' => 'bg-pink-50', 'border' => 'border-pink-200', 'dot' => 'bg-pink-500'],
        'Payment' => ['from' => '#f43f5e', 'to' => '#fb7185', 'text' => 'text-rose-700', 'bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'dot' => 'bg-rose-500'],
        'LC' => ['from' => '#f97316', 'to' => '#fb923c', 'text' => 'text-orange-700', 'bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'dot' => 'bg-orange-500'],
        'Production' => ['from' => '#f59e0b', 'to' => '#fbbf24', 'text' => 'text-amber-700', 'bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
        'Shipment' => ['from' => '#eab308', 'to' => '#facc15', 'text' => 'text-yellow-700', 'bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'dot' => 'bg-yellow-500'],
        'Logistics' => ['from' => '#84cc16', 'to' => '#a3e635', 'text' => 'text-lime-700', 'bg' => 'bg-lime-50', 'border' => 'border-lime-200', 'dot' => 'bg-lime-500'],
        'Clearance' => ['from' => '#22c55e', 'to' => '#4ade80', 'text' => 'text-green-700', 'bg' => 'bg-green-50', 'border' => 'border-green-200', 'dot' => 'bg-green-500'],
        'Delivery' => ['from' => '#10b981', 'to' => '#34d399', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
        'No Planned Dates' => ['from' => '#64748b', 'to' => '#94a3b8', 'text' => 'text-slate-600', 'bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400'],
        'No Actual Progress' => ['from' => '#64748b', 'to' => '#94a3b8', 'text' => 'text-slate-600', 'bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400'],
    ];
    $plannedStatus = $m['planned_stage'] ?? 'No Planned Dates';
    $actualStatus = $m['actual_stage'] ?? 'No Actual Progress';
    $plannedColor = $statusColors[$plannedStatus] ?? $statusColors['No Planned Dates'];
    $actualColor = $statusColors[$actualStatus] ?? $statusColors['No Actual Progress'];
@endphp
<td class="px-6 py-4">
    <span class="status-pill" style="background: linear-gradient(135deg, {{ $plannedColor['from'] }}, {{ $plannedColor['to'] }}); color: white;">
        <span class="status-pill-dot" style="background: rgba(255,255,255,0.4);"></span>
        {{ $plannedStatus }}
    </span>
</td>
<td class="px-6 py-4">
    <span class="status-pill" style="background: linear-gradient(135deg, {{ $actualColor['from'] }}, {{ $actualColor['to'] }}); color: white;">
        <span class="status-pill-dot" style="background: rgba(255,255,255,0.4);"></span>
        {{ $actualStatus }}
    </span>
</td>
    <td class="px-6 py-4 text-center">
        @php
            $balancedDelay = $m['balanced_delay'] ?? null;
            if ($balancedDelay === null) {
                $bdClass = 'text-slate-400';
                $bdText = '—';
            } elseif ($balancedDelay > 0) {
                $bdClass = 'text-emerald-600';
                $bdText = '+' . $balancedDelay . 'd';
            } elseif ($balancedDelay < 0) {
                $bdClass = 'text-rose-600';
                $bdText = $balancedDelay . 'd';
            } else {
                $bdClass = 'text-amber-600';
                $bdText = '0d';
            }
        @endphp
        <span class="text-[10px] font-black {{ $bdClass }} uppercase tracking-widest">{{ $bdText }}</span>
    </td>
    <td class="px-6 py-4 text-center">
        @php
            $floatValue = $m['float_days'] ?? null;
            if ($floatValue === null) {
                $floatClass = 'text-slate-400';
                $floatText = '—';
            } elseif ($floatValue > 0) {
                $floatClass = 'text-slate-900';
                $floatText = '+' . $floatValue . 'd';
            } elseif ($floatValue < 0) {
                $floatClass = 'text-rose-600';
                $floatText = $floatValue . 'd';
            } else {
                $floatClass = 'text-amber-600';
                $floatText = '0d';
            }
        @endphp
        <span class="text-[10px] font-black {{ $floatClass }} uppercase tracking-widest">{{ $floatText }}</span>
    </td>
    <td class="px-6 py-4">
        <div class="w-40">
            <div class="progress-3d-bar">
                <div class="progress-3d-fill" style="width: {{ $pct }}%; background: linear-gradient(to bottom, {{ $progressColor['from'] }}, {{ $progressColor['to'] }});"></div>
            </div>
            <p class="text-[10px] font-black text-slate-600 mt-2">{{ $pct }}% Complete</p>
        </div>
    </td>
    <td class="px-6 py-4 text-right">
        <div class="flex items-center justify-end gap-1.5">
            <button type="button"
                class="view-progress w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-gradient-to-b hover:from-[#00515f] hover:to-black hover:text-white hover:border-[#00515f] transition-all duration-200"
                title="View Progress" data-id="{{ $m['id'] }}">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
            </button>
        </div>
    </td>
</tr>
                        @empty
<tr>
    <td colspan="9" class="px-6 py-16 text-center">
        <div class="flex flex-col items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                <i data-lucide="database" class="w-8 h-8"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-black text-slate-900">Operational Vacancy</h3>
                <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">No material progress records discovered in the current subset.</p>
            </div>
            <a href="{{ route('admin.material.index') }}"
                class="px-5 py-2 bg-slate-900 text-white rounded-lg font-black text-[9px] uppercase tracking-widest shadow-lg shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                Browse Materials
            </a>
        </div>
    </td>
</tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-slate-50 flex items-center justify-between bg-slate-50/30">
                <div class="flex items-center gap-2">
                    @if($materials->hasPages())
                        <div class="flex items-center gap-2">
@if($materials->onFirstPage())
    <span class="group/nav w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-300 cursor-not-allowed">
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
    </span>
@else
    <a href="{{ $materials->previousPageUrl() }}"
        class="group/nav w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:border-[#00ADC5] hover:text-[#00ADC5] hover:shadow-md hover:shadow-cyan-100 transition-all duration-200">
        <i data-lucide="chevron-left" class="w-4 h-4 group-hover/nav:-translate-x-0.5 transition-transform"></i>
    </a>
@endif

<div class="flex items-center gap-1.5 px-3 h-10 bg-white border border-slate-100 rounded-xl">
    <span class="text-[9px] font-black text-slate-900 tracking-widest">{{ $materials->currentPage() }}</span>
    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">of</span>
    <span class="text-[9px] font-black text-slate-400 tracking-widest">{{ $materials->lastPage() }}</span>
</div>

@if($materials->hasMorePages())
    <a href="{{ $materials->nextPageUrl() }}"
        class="group/nav w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:border-[#00ADC5] hover:text-[#00ADC5] hover:shadow-md hover:shadow-cyan-100 transition-all duration-200">
        <i data-lucide="chevron-right" class="w-4 h-4 group-hover/nav:translate-x-0.5 transition-transform"></i>
    </a>
@else
    <span class="group/nav w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-300 cursor-not-allowed">
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
    </span>
@endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                <!-- Colored Header -->
                <div class="shrink-0 bg-gradient-to-r from-[#00515F] to-[#00ADC5] px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white">
<i data-lucide="eye" class="w-4 h-4"></i>
                        </div>
                        <div>
<h3 class="text-base font-black text-white">Progress Details</h3>
<p class="text-[8px] font-black text-white/70 uppercase tracking-widest">Procurement Execution Monitor</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="refreshProgress()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors" title="Refresh Progress">
<i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        </button>
                        <button onclick="exportPDF()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors" title="Export PDF">
<i data-lucide="download" class="w-4 h-4"></i>
                        </button>
                        <button onclick="closeViewModal()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors" title="Close">
<i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto">
                    <div id="viewModalContent" class="p-6">
                        <div class="flex items-center justify-center py-12">
<div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.view-progress');
                if (!btn) return;
                e.preventDefault();
                const id = btn.getAttribute('data-id');
                if (!id) return alert('Missing id');
                window.currentMaterialId = id;

                fetch('/admin/material-progress/' + id, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                    .then(async r => {
                        const text = await r.text();
                        let json = null;
                        try { json = text ? JSON.parse(text) : null; } catch (e) { json = null; }
                        if (!r.ok) {
const msg = (json && json.message) ? json.message : (text || ('HTTP ' + r.status));
throw new Error(msg);
                        }
                        if (!json || !json.material) {
throw new Error('Invalid response from server');
                        }
                        return json;
                    })
                    .then(data => renderDetail(data))
                    .catch(err => { console.error('Load progress failed:', err); alert('Failed to load progress:\n' + (err.message || err)); });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeViewModal();
                }
            });
        });

        function openViewModal() {
            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function refreshProgress() {
            if (!window.currentMaterialId) return;
            const content = document.getElementById('viewModalContent');
            content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div></div>';
            fetch('/admin/material-progress/' + window.currentMaterialId, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                .then(async r => {
                    const text = await r.text();
                    let json = null;
                    try { json = text ? JSON.parse(text) : null; } catch (e) { json = null; }
                    if (!r.ok) throw new Error((json && json.message) ? json.message : ('HTTP ' + r.status));
                    if (!json || !json.material) throw new Error('Invalid response');
                    return json;
                })
                .then(data => renderDetail(data))
                .catch(err => { console.error('Refresh failed:', err); alert('Failed to refresh:\n' + (err.message || err)); });
        }

        function exportPDF() {
            window.print();
        }

        function fmt(d) {
            if (!d) return '—';
            try {
                const date = new Date(d);
                if (isNaN(date.getTime())) return d;
                return date.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            } catch (e) { return d; }
        }

        function renderDetail(payload) {
            window.currentMaterialId = payload.material?.id || window.currentMaterialId;
            const mat = payload.material || {};
            const c = payload.computed || {};
            const percent = c.percent_complete ?? 0;
            const status = c.status || 'Not Started';
            const totalStages = c.total_stages || 0;
            const completedStages = c.completed_stages || 0;
            const plannedStage = payload.planned_stage || '—';
            const actualStage = payload.actual_stage || '—';
            const balancedDelay = c.balanced_delay ?? null;
            const deliveryFloat = c.float_days ?? null;

            // Health indicator
            let health = { text: 'No Data', color: 'bg-slate-100 text-slate-500 border-slate-200', emoji: '' };
            if (balancedDelay !== null) {
                if (balancedDelay >= 0) {
                    health = { text: 'On Schedule', color: 'bg-emerald-50 text-emerald-600 border-emerald-200', emoji: '🟢' };
                } else if (balancedDelay >= -15) {
                    health = { text: 'At Risk', color: 'bg-amber-50 text-amber-600 border-amber-200', emoji: '🟡' };
                } else {
                    health = { text: 'Critical Delay', color: 'bg-rose-50 text-rose-600 border-rose-200', emoji: '🔴' };
                }
            }

            const schedulePercent = percent;
            const delayDays = balancedDelay !== null ? Math.abs(balancedDelay) : 0;
            const delayText = balancedDelay !== null ? (balancedDelay > 0 ? 'On Time' : Math.abs(balancedDelay) + ' Days Late') : '—';
            const floatDays = deliveryFloat !== null ? Math.abs(deliveryFloat) : 0;
            const floatText = deliveryFloat !== null ? (deliveryFloat > 0 ? 'Late' : (deliveryFloat < 0 ? 'Early' : 'On Time')) : '—';

            // Timeline summary stats
            const stages = [
                ['Design', 'design_planned', 'design_forecast', 'design_actual', 'design_variance'],
                ['Specification', 'spec_planned', 'spec_forecast', 'spec_actual', 'spec_variance'],
                ['Request Order', 'ro_planned', 'ro_forecast', 'ro_actual', 'ro_variance'],
                ['Supplier Identification', 'supplier_planned', 'supplier_forecast', 'supplier_actual', 'supplier_variance'],
                ['Material Submittal', 'submittal_planned', 'submittal_forecast', 'submittal_actual', 'submittal_variance'],
                ['Material Approval', 'approval_planned', 'approval_forecast', 'approval_actual', 'approval_variance'],
                ['Payment', 'payment_planned', 'payment_forecast', 'payment_actual', 'payment_variance'],
                ['LC', 'lc_planned', 'lc_forecast', 'lc_actual', 'lc_variance'],
                ['Production', 'production_planned', 'production_forecast', 'production_actual', 'production_variance'],
                ['Shipment', 'shipment_planned', 'shipment_forecast', 'shipment_actual', 'shipment_variance'],
                ['Logistics', 'logistics_planned', 'logistics_forecast', 'logistics_actual', 'logistics_variance'],
                ['Clearance', 'clearance_planned', 'clearance_forecast', 'clearance_actual', 'clearance_variance'],
                ['Delivery', 'delivery_planned', 'delivery_forecast', 'delivery_actual', 'delivery_variance'],
            ];

            let delayedCount = 0;
            let totalVariance = 0;
            let varianceCount = 0;
            let forecastFinish = null;

            stages.forEach(s => {
                const variance = (typeof c[s[4]] === 'number') ? c[s[4]] : null;
                if (variance !== null && variance > 0) {
                    delayedCount++;
                    totalVariance += variance;
                    varianceCount++;
                }
                const forecast = c[s[2]];
                const actual = c[s[3]];
                const d = actual || forecast;
                if (d) {
                    const date = new Date(d);
                    if (!isNaN(date.getTime()) && (!forecastFinish || date > new Date(forecastFinish))) {
                        forecastFinish = d;
                    }
                }
            });

            const avgDelay = varianceCount > 0 ? Math.round(totalVariance / varianceCount) : 0;
            const remainingStages = totalStages - completedStages;
            const ringColor = status === 'Completed' ? '#10b981' : (status === 'Delayed' ? '#f43f5e' : (status === 'In Progress' ? '#3b82f6' : '#94a3b8'));
            const circumference = 2 * Math.PI * 38;
            const strokeDashoffset = circumference - (percent / 100) * circumference;

            const html = `
                <div class="space-y-4 animate-fade-in">
                    <!-- Sticky Header -->
                    <div class="sticky top-0 z-20 bg-white/95 backdrop-blur-xl rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <!-- Compact Progress Summary -->
                        <div class="p-5">
                            <div class="flex items-start gap-5">
                                <!-- Progress Circle -->
                                <div class="relative w-[100px] h-[100px] shrink-0">
                                    <svg class="w-[100px] h-[100px] -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="38" stroke="#e2e8f0" stroke-width="7" fill="none"/>
                                        <circle id="progressRing" cx="50" cy="50" r="38" stroke="${ringColor}" stroke-width="7" fill="none" stroke-linecap="round" stroke-dasharray="${circumference}" stroke-dashoffset="${circumference}" class="transition-all duration-1000 ease-out"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-xl font-black text-slate-900">${percent}<span class="text-slate-400">%</span></div>
                                            <div class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Complete</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Project Info + Meta -->
                                <div class="flex-1 min-w-0">
                                    <div class="rounded-xl border border-slate-100 overflow-hidden">
                                        <div class="bg-slate-50/80 px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                                            <h4 class="text-sm font-black text-slate-900 leading-tight truncate">${mat.material_name || 'Material #' + mat.id}</h4>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest truncate ml-2">${mat.supplier_name || 'Unknown Supplier'}</span>
                                        </div>
                                        <table class="w-full text-[10px]">
                                            <tbody class="divide-y divide-slate-50">
                                                <tr>
                                                    <td class="px-4 py-2 font-bold text-slate-400 uppercase tracking-wider w-28">Procurement</td>
                                                    <td class="px-4 py-2 font-black text-slate-700">${mat.procurement_type || '—'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-2 font-bold text-slate-400 uppercase tracking-wider">Contract</td>
                                                    <td class="px-4 py-2 font-black text-slate-700">${mat.contract_type || '—'}</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-2 font-bold text-slate-400 uppercase tracking-wider">Planned</td>
                                                    <td class="px-4 py-2 font-black text-slate-700">${plannedStage}</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-2 font-bold text-slate-400 uppercase tracking-wider">Actual</td>
                                                    <td class="px-4 py-2 font-black text-slate-700">${actualStage}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Stat Cards + Health -->
                                <div class="flex flex-col gap-2 shrink-0">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="bg-white border border-slate-100 rounded-xl p-2 shadow-sm text-center min-w-[72px]">
                                            <div class="text-sm font-black text-slate-900">${completedStages}<span class="text-slate-400">/${totalStages}</span></div>
                                            <div class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Stages</div>
                                        </div>
                                        <div class="bg-white border border-slate-100 rounded-xl p-2 shadow-sm text-center min-w-[72px]">
                                            <div id="statSchedule" class="text-sm font-black text-slate-900">${schedulePercent}<span class="text-slate-400">%</span></div>
                                            <div class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Complete</div>
                                        </div>
                                        <div class="bg-white border border-slate-100 rounded-xl p-2 shadow-sm text-center min-w-[72px]">
                                            <div class="text-sm font-black ${balancedDelay !== null && balancedDelay > 0 ? 'text-emerald-600' : (balancedDelay !== null && balancedDelay < 0 ? 'text-rose-600' : 'text-slate-900')}">${delayDays}</div>
                                            <div class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-0.5">${delayText}</div>
                                        </div>
                                        <div class="bg-white border border-slate-100 rounded-xl p-2 shadow-sm text-center min-w-[72px]">
                                            <div class="text-sm font-black ${deliveryFloat !== null && deliveryFloat < 0 ? 'text-emerald-600' : (deliveryFloat !== null && deliveryFloat > 0 ? 'text-rose-600' : 'text-slate-900')}">${floatDays}</div>
                                            <div class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-0.5">${floatText}</div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-2 rounded-xl border ${health.color} text-center">
                                        <div class="text-lg leading-none mb-0.5">${health.emoji}</div>
                                        <div class="text-[9px] font-black uppercase tracking-wider leading-tight">${health.text}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Legend -->
                        <div class="px-5 pb-3 border-t border-slate-50 flex items-center justify-between">
                            <div class="flex items-center gap-3 text-[9px] font-black uppercase tracking-wider text-slate-500">
                                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>On Time</span>
                                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>Delayed</span>
                                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>Pending</span>
                            </div>
                        </div>

                        <!-- Timeline Summary -->
                        <div class="px-5 py-2.5 border-t border-slate-100 flex items-center gap-4 bg-slate-50/50">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Timeline Summary</span>
                            <div class="h-4 w-px bg-slate-200"></div>
                            <div class="flex items-center gap-1 text-[10px] font-black text-slate-700">
                                <span class="text-emerald-600">${completedStages}</span>
                                <span class="text-slate-400">Completed</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] font-black text-slate-700">
                                <span class="text-slate-500">${remainingStages}</span>
                                <span class="text-slate-400">Remaining</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] font-black text-slate-700">
                                <span class="text-rose-600">${delayedCount}</span>
                                <span class="text-slate-400">Delayed</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] font-black text-slate-700">
                                <span class="text-amber-600">${avgDelay}d</span>
                                <span class="text-slate-400">Avg Delay</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] font-black text-slate-700">
                                <span class="text-slate-400">Forecast Finish:</span>
                                <span class="text-slate-700">${forecastFinish ? fmt(forecastFinish) : '—'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Procurement Timeline -->
                    <div class="bg-white border border-slate-100 rounded-2xl p-4">
                        <div class="space-y-2">
                            ${renderStages(c)}
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('viewModalContent').innerHTML = html;
            openViewModal();
            if (window.lucide) lucide.createIcons();

            // Animate progress ring
            setTimeout(() => {
                const ring = document.getElementById('progressRing');
                if (ring) ring.setAttribute('stroke-dashoffset', strokeDashoffset);
            }, 50);
        }

        function renderRow(label, planned, forecast, actual, variance, isLast) {
            const hasPlanned = planned || forecast;
            const hasActual = actual;

            const plannedDate = planned ? new Date(planned) : null;
            const actualDate = actual ? new Date(actual) : null;
            const isEarly = plannedDate && actualDate && !isNaN(plannedDate) && !isNaN(actualDate) && actualDate < plannedDate;
            const isDelayed = plannedDate && actualDate && !isNaN(plannedDate) && !isNaN(actualDate) && actualDate > plannedDate;
            const isOnTime = plannedDate && actualDate && !isNaN(plannedDate) && !isNaN(actualDate) && actualDate.getTime() === plannedDate.getTime();
            const isPending = !hasActual;

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const effectiveDate = actualDate ? new Date(actualDate) : new Date(today);
            const varianceInDays = plannedDate && !isNaN(effectiveDate) ? Math.round((effectiveDate - plannedDate) / (1000 * 60 * 60 * 24)) : null;

            const dotColor = !hasActual ? 'bg-slate-300 border-slate-200' : (actualDate && plannedDate && actualDate > plannedDate ? 'bg-rose-500 border-rose-600' : 'bg-emerald-500 border-emerald-600');
            const cardBg = !hasActual ? 'bg-slate-50/50' : (actualDate && plannedDate && actualDate > plannedDate ? 'bg-rose-50/30' : 'bg-emerald-50/30');
            const cardBorder = !hasActual ? 'border-slate-100' : (actualDate && plannedDate && actualDate > plannedDate ? 'border-rose-100' : 'border-emerald-100');

            let delayPill = '';
            if (!hasActual && plannedDate && !isNaN(effectiveDate)) {
                const diff = Math.round((effectiveDate - plannedDate) / (1000 * 60 * 60 * 24));
                if (diff > 0) {
                    delayPill = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">⚠ ${diff} Days Late</span>`;
                } else if (diff < 0) {
                    delayPill = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">▲ ${Math.abs(diff)}d Days Early</span>`;
                } else {
                    delayPill = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">✔ On Time</span>`;
                }
            } else if (isDelayed) {
                delayPill = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">⚠ ${varianceInDays} Days Late</span>`;
            } else if (isOnTime) {
                delayPill = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">✔ On Time</span>`;
            } else if (isEarly) {
                delayPill = `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">▲ ${varianceInDays}d Days Early</span>`;
            }

            return `
                <div class="flex gap-3 group">
                    <div class="flex flex-col items-center shrink-0 w-6">
                        <div class="w-4 h-4 rounded-full border-2 ${dotColor} shrink-0 mt-1.5 z-10"></div>
                        ${!isLast ? `<div class="w-px flex-1 bg-slate-200 min-h-[8px]"></div>` : ''}
                    </div>

                    <div class="flex-1 min-w-0 p-3 rounded-xl border ${cardBorder} ${cardBg} hover:shadow-md hover:shadow-slate-200/50 hover:-translate-y-0.5 transition-all duration-200">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <div class="font-black text-xs text-slate-900 uppercase tracking-tight">${label}</div>
                            ${delayPill}
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            ${hasPlanned ? `
                                <div class="flex-1 bg-white border border-slate-100 rounded-lg px-2 py-1.5">
                                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">📅 Planned</div>
                                    <div class="text-[10px] font-black text-slate-700">${fmt(planned)}</div>
                                </div>
                            ` : ''}
                            <div class="flex-1 bg-white border border-slate-100 rounded-lg px-2 py-1.5">
                                <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">📋 Forecast</div>
                                <div class="text-[10px] font-black text-slate-700">${fmt(forecast)}</div>
                            </div>
                            <div class="flex-1 bg-white border ${hasActual ? 'border-emerald-100' : 'border-slate-100'} rounded-lg px-2 py-1.5">
                                <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">✅ Actual</div>
                                <div class="text-[10px] font-black ${hasActual ? 'text-emerald-600' : 'text-slate-400'}">${fmt(actual)}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        function renderStages(c) {
            const stages = [
                ['Design', 'design_planned', 'design_forecast', 'design_actual', 'design_variance'],
                ['Specification', 'spec_planned', 'spec_forecast', 'spec_actual', 'spec_variance'],
                ['Request Order', 'ro_planned', 'ro_forecast', 'ro_actual', 'ro_variance'],
                ['Supplier Identification', 'supplier_planned', 'supplier_forecast', 'supplier_actual', 'supplier_variance'],
                ['Material Submittal', 'submittal_planned', 'submittal_forecast', 'submittal_actual', 'submittal_variance'],
                ['Material Approval', 'approval_planned', 'approval_forecast', 'approval_actual', 'approval_variance'],
                ['Payment', 'payment_planned', 'payment_forecast', 'payment_actual', 'payment_variance'],
                ['LC', 'lc_planned', 'lc_forecast', 'lc_actual', 'lc_variance'],
                ['Production', 'production_planned', 'production_forecast', 'production_actual', 'production_variance'],
                ['Shipment', 'shipment_planned', 'shipment_forecast', 'shipment_actual', 'shipment_variance'],
                ['Logistics', 'logistics_planned', 'logistics_forecast', 'logistics_actual', 'logistics_variance'],
                ['Clearance', 'clearance_planned', 'clearance_forecast', 'clearance_actual', 'clearance_variance'],
                ['Delivery', 'delivery_planned', 'delivery_forecast', 'delivery_actual', 'delivery_variance'],
            ];

            const validStages = [];
            stages.forEach(s => {
                const planned = c[s[1]] ?? null;
                const forecast = c[s[2]] ?? null;
                const actual = c[s[3]] ?? null;
                if (planned || forecast || actual) {
                    const variance = (typeof c[s[4]] === 'number') ? c[s[4]] : null;
                    validStages.push({ name: s[0], planned, forecast, actual, variance });
                }
            });

            if (validStages.length === 0) {
                return '<p class="text-sm text-slate-400 text-center py-8">No timeline data available.</p>';
            }

            return validStages.map((s, i) => renderRow(s.name, s.planned, s.forecast, s.actual, s.variance, i === validStages.length - 1)).join('');
        }
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .progress-3d-bar {
            position: relative;
            height: 14px;
            border-radius: 999px;
            overflow: hidden;
            transform: perspective(200px) rotateX(10deg);
            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -2px rgba(0, 0, 0, 0.1),
                inset 0 2px 4px rgba(255, 255, 255, 0.4),
                inset 0 -2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .progress-3d-bar::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 999px;
            background: linear-gradient(
                to bottom,
                rgba(255, 255, 255, 0.3) 0%,
                rgba(255, 255, 255, 0.1) 40%,
                transparent 40%,
                rgba(0, 0, 0, 0.05) 100%
            );
            pointer-events: none;
        }

        .progress-3d-fill {
            height: 100%;
            border-radius: 999px;
            position: relative;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow:
                inset 0 2px 4px rgba(255, 255, 255, 0.3),
                inset 0 -2px 4px rgba(0, 0, 0, 0.15);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            box-shadow:
                0 2px 4px -1px rgba(0, 0, 0, 0.1),
                0 1px 2px -1px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
        }

        .status-pill:hover {
            transform: translateY(-1px);
            box-shadow:
                0 4px 8px -2px rgba(0, 0, 0, 0.15),
                0 2px 4px -2px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .status-pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.4);
        }
    </style>
</x-app-layout>



