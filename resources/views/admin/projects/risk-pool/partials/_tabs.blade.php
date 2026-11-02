@php
    $tabs = [
        'risk-pool' => ['label' => 'Risk Pool', 'icon' => 'folder-open', 'route' => 'admin.projects.risk-pool.index'],
        'eot' => ['label' => 'EOT Pool', 'icon' => 'clock', 'route' => 'admin.projects.risk-pool.eot'],
        'contract-clauses' => ['label' => 'Contract Clauses', 'icon' => 'file-text', 'route' => 'admin.projects.risk-pool.contract-clauses'],
    ];
@endphp

<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
            <i data-lucide="{{ $activeTab === 'eot' ? 'clock' : ($activeTab === 'contract-clauses' ? 'file-text' : 'folder-open') }}" class="w-4 h-4"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                @if($activeTab === 'eot')
                    EOT <span class="font-black text-slate-900">Pool</span>
                @elseif($activeTab === 'contract-clauses')
                    Contract <span class="font-black text-slate-900">Clauses</span>
                @else
                    Risk <span class="font-black text-slate-900">Pool</span>
                @endif
            </h2>
            <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">{{ $activeTab === 'eot' ? 'Extension of time register' : ($activeTab === 'contract-clauses' ? 'Contract clause repository' : 'Centralized risk repository') }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        @if(($canCreateRiskPool ?? false) && $activeTab === 'risk-pool')
            <button onclick="openSpreadsheetEditor()" type="button"
                class="group/btn relative overflow-hidden rounded-xl bg-slate-900 px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-slate-900/20 transition-all hover:scale-105 active:scale-95">
                <span class="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-[#00ADC5] to-blue-500 transition-transform duration-500 group-hover/btn:translate-x-0"></span>
                <span class="relative flex items-center gap-1.5"><i data-lucide="plus" class="h-3 w-3"></i>Add New Risk</span>
            </button>
        @endif
        @if(($canCreateEot ?? false) && $activeTab === 'eot')
            <button onclick="openCreateEotModal()" type="button"
                class="group/btn relative overflow-hidden rounded-xl bg-slate-900 px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-slate-900/20 transition-all hover:scale-105 active:scale-95">
                <span class="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-[#00ADC5] to-blue-500 transition-transform duration-500 group-hover/btn:translate-x-0"></span>
                <span class="relative flex items-center gap-1.5"><i data-lucide="plus" class="h-3 w-3"></i>Add New EOT</span>
            </button>
        @endif
        @if(($canCreateClauses ?? false) && $activeTab === 'contract-clauses')
            <button onclick="openCreateClauseModal()" type="button"
                class="group/btn relative overflow-hidden rounded-xl bg-slate-900 px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-slate-900/20 transition-all hover:scale-105 active:scale-95">
                <span class="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-[#00ADC5] to-blue-500 transition-transform duration-500 group-hover/btn:translate-x-0"></span>
                <span class="relative flex items-center gap-1.5"><i data-lucide="plus" class="h-3 w-3"></i>Add New Clause</span>
            </button>
        @endif
        @isset($stats)
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-b from-[#00515f] to-black rounded-lg shadow-sm border border-white/10">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-white/90"></i>
                <span class="text-base font-black text-white tracking-tight" id="riskPoolSize">{{ $stats['total'] }}</span>
                <span class="text-[9px] font-black text-white/80 uppercase tracking-wider">{{ $activeTab === 'eot' ? 'EOTs' : ($activeTab === 'contract-clauses' ? 'Clauses' : 'Risks') }}</span>
            </div>
        @endisset
    </div>
</div>

<nav class="mb-4 flex justify-center" role="tablist" aria-label="Risk pool sections">
    <div class="inline-flex items-center gap-2 bg-white border border-slate-200 rounded-xl p-1.5 shadow-sm overflow-x-auto">
        @foreach($tabs as $key => $tab)
            @php
                $active = $activeTab === $key;
                $baseClasses = 'flex items-center gap-2 px-4 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all whitespace-nowrap';
                $activeClasses = 'bg-gradient-to-b from-[#00515f] to-black text-white shadow-lg border border-slate-200';
                $inactiveClasses = 'bg-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-transparent';
            @endphp
            <a href="{{ route($tab['route']) }}" role="tab"
               class="{{ $baseClasses }} {{ $active ? $activeClasses : $inactiveClasses }}"
               {{ $active ? 'aria-selected="true" data-tab-active' : 'aria-selected="false"' }}>
                <i data-lucide="{{ $tab['icon'] }}" class="w-3.5 h-3.5"></i> {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</nav>
