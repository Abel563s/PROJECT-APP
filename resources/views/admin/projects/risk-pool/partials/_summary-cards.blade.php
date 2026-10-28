@php
    $kpiTones = [
        'slate'  => ['icon' => 'bg-slate-100 text-slate-600', 'value' => 'text-slate-900', 'border' => 'hover:border-slate-300'],
        'rose'   => ['icon' => 'bg-rose-100 text-rose-700',      'value' => 'text-rose-700',      'border' => 'hover:border-rose-200'],
        'amber'  => ['icon' => 'bg-amber-100 text-amber-700',      'value' => 'text-amber-700',     'border' => 'hover:border-amber-200'],
        'cyan'   => ['icon' => 'bg-cyan-100 text-cyan-700',        'value' => 'text-cyan-700',      'border' => 'hover:border-cyan-200'],
    ];
    $kpis = [
        ['label' => 'Total Risks',    'key' => 'total',  'icon' => 'triangle-alert', 'tone' => 'slate',  'desc' => 'All records',      'level' => null],
        ['label' => 'High Impact',    'key' => 'high',   'icon' => 'triangle-alert', 'tone' => 'rose',   'desc' => 'Overall impact',   'level' => 'High'],
        ['label' => 'Medium Impact',  'key' => 'medium', 'icon' => 'minus',          'tone' => 'amber',  'desc' => 'Overall impact',   'level' => 'Medium'],
        ['label' => 'Low Impact',     'key' => 'low',    'icon' => 'arrow-down',     'tone' => 'cyan',   'desc' => 'Overall impact',   'level' => 'Low'],
    ];
    $activeImpact = $filters['overall_impact_level'] ?? null;
@endphp

<div class="grid grid-cols-2 gap-3 sm:grid-cols-2 lg:grid-cols-4">
    @foreach($kpis as $kpi)
        @php
            $isActive = $kpi['level'] !== null && $activeImpact === $kpi['level'];
        @endphp
        <div class="group rounded-2xl border border-slate-100 bg-gradient-to-br from-white to-slate-50/80 p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md {{ $kpiTones[$kpi['tone']]['border'] }} {{ $isActive ? 'ring-2 ring-[#00ADC5]/30' : '' }}">
            <div class="flex items-center justify-between gap-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $kpiTones[$kpi['tone']]['icon'] }} transition-transform group-hover:scale-105">
                    <i data-lucide="{{ $kpi['icon'] }}" class="h-4 w-4"></i>
                </div>
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $kpi['label'] }}</span>
            </div>
            <p class="mt-3 text-2xl font-black leading-none tabular-nums {{ $kpiTones[$kpi['tone']]['value'] }}" @if($kpi['key'] === 'total') data-kpi-total @endif>{{ $stats[$kpi['key']] }}</p>
            <p class="mt-1 text-[8px] font-bold uppercase tracking-wider text-slate-400">{{ $kpi['desc'] }}</p>
            @if($isActive)
                <div class="mt-1 flex items-center gap-1 text-[8px] font-black text-[#00ADC5]">
                    <i data-lucide="funnel" class="w-3 h-3"></i><span>Filtering</span>
                </div>
            @endif
        </div>
    @endforeach
</div>

