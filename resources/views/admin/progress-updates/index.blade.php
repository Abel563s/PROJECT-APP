<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

        <!-- Compact Header -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-3 border-b border-slate-100 items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Progress <span class="font-black text-slate-900">Updates</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Operational Performance Matrix</p>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5 text-white/90"></i>
                    <span class="text-base font-black text-white tracking-tight">{{ $updates->total() }}</span>
                    <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">Progress Updates</span>
                </div>
            </div>

            <div class="flex justify-end">
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperadmin() || auth()->user()->isUser())
                <button onclick="openCreateModal()"
                   class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                   <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                   <span class="relative flex items-center gap-1.5">
                       <i data-lucide="plus" class="w-3 h-3"></i>
                       New Update
                   </span>
                </button>
                @endif
            </div>
        </div>

        <section aria-label="Filtered revenue totals" class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div class="progress-revenue-card progress-revenue-card--planned group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 p-5 text-white shadow-lg shadow-emerald-900/15">
                <div class="progress-revenue-glow absolute -right-6 -top-8 h-28 w-28 rounded-full bg-white/10 transition-transform duration-300 group-hover:scale-125"></div>
                <div class="relative flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.18em] text-emerald-100">Revenue Planned</p>
                        <p class="mt-2 text-2xl font-black tracking-tight tabular-nums"><span class="mr-1 text-xs font-bold text-emerald-100">ETB</span>{{ number_format($totalRevenuePlannedWeekly, 2) }}</p>
                        <p class="mt-1 text-[9px] font-semibold text-emerald-100/90">Weekly · Data Date: {{ $latestDataDate ? \Carbon\Carbon::parse($latestDataDate)->format('d-M-Y') : '---' }}</p>
                    </div>
                    <span class="progress-revenue-icon flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/20 bg-white/15 shadow-inner"><i data-lucide="banknote" class="h-5 w-5"></i></span>
                </div>
            </div>
            <div class="progress-revenue-card progress-revenue-card--actual group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500 via-sky-600 to-blue-700 p-5 text-white shadow-lg shadow-blue-900/15">
                <div class="progress-revenue-glow absolute -right-6 -top-8 h-28 w-28 rounded-full bg-white/10 transition-transform duration-300 group-hover:scale-125"></div>
                <div class="relative flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.18em] text-sky-100">Revenue Actual</p>
                        <p class="mt-2 text-2xl font-black tracking-tight tabular-nums"><span class="mr-1 text-xs font-bold text-sky-100">ETB</span>{{ number_format($totalRevenueActualWeekly, 2) }}</p>
                        <p class="mt-1 text-[9px] font-semibold text-sky-100/90">Weekly · Data Date: {{ $latestDataDate ? \Carbon\Carbon::parse($latestDataDate)->format('d-M-Y') : '---' }}</p>
                    </div>
                    <span class="progress-revenue-icon flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/20 bg-white/15 shadow-inner"><i data-lucide="circle-dollar-sign" class="h-5 w-5"></i></span>
                </div>
            </div>
        </section>

        <!-- Search & Filter -->
        @php
            $progressUpdateFiltersActive = request()->anyFilled(['search', 'project_id', 'project_sector', 'data_date', 'business_unit_id', 'sector_id']);
            $progressAdvancedFiltersActive = request()->anyFilled(['project_id', 'project_sector', 'data_date']);
        @endphp
        <form action="{{ route('admin.progress-updates.index') }}" method="GET" class="progress-update-filters rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="admin-filter-grid gap-3">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Updates</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by project name, code or ID..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>

                @include('admin.partials.project-organization-filters')

                <div class="flex flex-wrap items-center justify-start gap-2 xl:justify-end">
                    <button type="button" aria-controls="progressAdvancedFilters" aria-expanded="{{ $progressAdvancedFiltersActive ? 'true' : 'false' }}" onclick="toggleFilterPanel(this, 'progressAdvancedFilters')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[9px] font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-[#00515f]">
                        <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i>More filters
                        <i data-lucide="chevron-down" class="filter-panel-chevron h-3.5 w-3.5 transition-transform {{ $progressAdvancedFiltersActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    @if($progressUpdateFiltersActive)
                        <a href="{{ route('admin.progress-updates.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition hover:bg-rose-500 hover:text-white"><i data-lucide="x" class="h-3.5 w-3.5"></i>Clear filters</a>
                    @endif
                </div>

                <div id="progressAdvancedFilters" class="admin-filter-advanced-panel {{ $progressAdvancedFiltersActive ? '' : 'hidden' }} rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                    <div class="admin-filter-advanced-grid gap-3">
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
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Project Sector</span>
                            <select name="project_sector" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                                <option value="">All Sectors</option>
                                @foreach(['Building', 'Fit-Out', 'Infrastructure', 'Water & Energy'] as $sector)
                                    <option value="{{ $sector }}" {{ request('project_sector') == $sector ? 'selected' : '' }}>{{ $sector }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="space-y-1">
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Data Date</span>
                            <input type="date" name="data_date" value="{{ request('data_date') }}" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        </label>
                    </div>
                </div>
            </div>
        </form>
        @include('admin.partials.filter-panel-toggle-script')

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">#</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Project</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Planned</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Actual</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">PSPI</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">CPI</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Revenue Planned</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Revenue Actual</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">FSPI</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Data Date</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Last Edit</th>
                            <th class="px-6 py-3 text-right border-b border-[#00515f]/30"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($updates as $update)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20 cursor-pointer" onclick="toggleConstraints(this)">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <p class="text-sm font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">
                                            {{ $update->project->project_code }}
                                        </p>
                                        <span class="text-[8px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $update->project->custom_id }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $plannedVal = (float) $update->progress_planned;
                                        $plannedColor = 'bg-slate-100 border-slate-200 text-slate-900';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $plannedColor }} border shadow-sm">{{ $plannedVal }}%</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $actualVal = (float) $update->progress_actual;
                                        $actualColor = 'bg-slate-100 border-slate-200 text-slate-900';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $actualColor }} border shadow-sm">{{ $actualVal }}%</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $spi = $update->progress_planned > 0 ? ($update->progress_actual / $update->progress_planned) : 0;
                                        $spiPercent = $spi * 100;
                                        $spiColor = $spiPercent >= 80 ? 'text-emerald-500' : ($spiPercent >= 70 ? 'text-amber-500' : 'text-rose-500');
                                        $spiBg = $spiPercent >= 80 ? 'bg-emerald-50 border-emerald-200' : ($spiPercent >= 70 ? 'bg-amber-50 border-amber-200' : 'bg-rose-50 border-rose-200');
                                        $spiArrow = $spiPercent >= 80 ? 'trending-up' : ($spiPercent >= 70 ? 'minus' : 'trending-down');
                                    @endphp
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $spiBg }} border shadow-sm {{ $spiColor }}">
                                            <i data-lucide="{{ $spiArrow }}" class="w-3.5 h-3.5"></i>
                                            {{ number_format($spiPercent, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $cpi = $update->cost_actual > 0 ? ($update->cost_planned / $update->cost_actual) : 0;
                                        $cpiPercent = $cpi * 100;
                                        $cpiColor = $cpi > 1.0 ? 'text-emerald-500' : ($cpi >= 1.0 ? 'text-amber-500' : 'text-rose-500');
                                        $cpiBg = $cpi > 1.0 ? 'bg-emerald-50 border-emerald-200' : ($cpi >= 1.0 ? 'bg-amber-50 border-amber-200' : 'bg-rose-50 border-rose-200');
                                        $cpiArrow = $cpi > 1.0 ? 'trending-up' : ($cpi >= 1.0 ? 'minus' : 'trending-down');
                                    @endphp
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $cpiBg }} border shadow-sm {{ $cpiColor }}">
                                            <i data-lucide="{{ $cpiArrow }}" class="w-3.5 h-3.5"></i>
                                            {{ number_format($cpi, 2) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-900 text-[11px] font-black tabular-nums tracking-tight shadow-sm">
                                        <span class="text-slate-500 font-bold mr-1">ETB</span>
                                        {{ number_format($update->revenue_planned) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-900 text-[11px] font-black tabular-nums tracking-tight shadow-sm">
                                        <span class="text-slate-500 font-bold mr-1">ETB</span>
                                        {{ number_format($update->revenue_actual) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $fspi = $update->revenue_planned > 0 ? ($update->revenue_actual / $update->revenue_planned) * 100 : 0;
                                        $fspiColor = $fspi >= 80 ? 'text-emerald-500' : ($fspi >= 70 ? 'text-amber-500' : 'text-rose-500');
                                        $fspiBg = $fspi >= 80 ? 'bg-emerald-50 border-emerald-200' : ($fspi >= 70 ? 'bg-amber-50 border-amber-200' : 'bg-rose-50 border-rose-200');
                                        $fspiArrow = $fspi >= 80 ? 'trending-up' : ($fspi >= 70 ? 'minus' : 'trending-down');
                                    @endphp
                                    <div class="flex items-center justify-end gap-1.5">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $fspiBg }} border shadow-sm {{ $fspiColor }}">
                                            <i data-lucide="{{ $fspiArrow }}" class="w-3.5 h-3.5"></i>
                                            {{ number_format($fspi, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="whitespace-nowrap text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $update->data_date ? \Carbon\Carbon::parse($update->data_date)->format('d-M-y') : '-' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="whitespace-nowrap text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $update->updated_at->format('d-M-y') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="event.stopPropagation(); openViewModal({{ $update->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#00ADC5] hover:text-white hover:border-[#00ADC5] transition-all duration-200"
                                            title="View">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @can('update', $update)
                                        <button onclick="event.stopPropagation(); openEditModal({{ $update->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @endcan
                                        @can('delete', $update)
                                            <form action="{{ route('admin.progress-updates.destroy', $update->id) }}" method="POST" class="inline" onclick="event.stopPropagation();"
                                                onsubmit="return confirm('Are you sure you want to delete this progress update?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-200"
                                                    title="Delete">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            <tr class="constraints-row bg-slate-50/60 hidden">
                                <td colspan="12" class="px-6 py-3">
                                    <div class="flex items-start gap-2">
                                        <span class="text-[8px] font-black text-[#00515f] uppercase tracking-widest mt-0.5 shrink-0">Top Constraints</span>
                                        <p class="text-xs font-black text-[#00515f] leading-relaxed">{{ $update->top_constraints ?: '-' }}</p>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                        <td colspan="12" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="trending-up" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="text-base font-black text-slate-900">No Record Found</h3>
                                        <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Please initiate a progress protocol update.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($updates->hasPages())
            <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                {{ $updates->links() }}
            </div>
        @endif
    </div>
    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeCreateModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
                <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f]/30 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 shrink-0">
                    <h3 class="text-base font-black text-white">New Progress Update</h3>
                    <button onclick="closeCreateModal()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="createModalContent" class="p-6 overflow-y-auto flex-1">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col border border-slate-200">
                <div class="sticky top-0 bg-gradient-to-b from-[#00515f] to-black border-b border-[#00515f] px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 flex-shrink-0">
                    <h3 class="text-base font-black text-white">Progress Update Details</h3>
                    <button onclick="closeViewModal()" class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:bg-white/20 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="viewModalContent" class="p-6 overflow-y-auto flex-1">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10 shrink-0">
                    <h3 class="text-base font-black text-slate-900">Edit Progress Update</h3>
                    <button onclick="closeEditModal()" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="editModalContent" class="p-6 overflow-y-auto flex-1">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            fetch(`/admin/progress-updates/create?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openViewModal(id) {
            fetch(`/admin/progress-updates/${id}?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('viewModalContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openEditModal(id) {
            fetch(`/admin/progress-updates/${id}/edit?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    document.getElementById('editModal').classList.remove('hidden');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function toggleConstraints(row) {
            const nextRow = row.nextElementSibling;
            if (nextRow && nextRow.classList.contains('constraints-row')) {
                nextRow.classList.toggle('hidden');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeViewModal();
                closeEditModal();
            }
        });
    </script>
</x-app-layout>
