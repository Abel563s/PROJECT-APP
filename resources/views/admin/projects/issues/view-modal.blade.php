<div class="space-y-4">
    <span class="hidden issue-data-date">{{ $issue->date ? $issue->date->format('M d, Y') : '---' }}</span>
    <div class="bg-slate-50 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Issue</p>
                <p class="text-lg font-black text-slate-900">{{ $issue->issue }}</p>
            </div>
            <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $issue->status === 'Open' ? 'bg-sky-50 text-sky-600 border-sky-200' : ($issue->status === 'In Progress' ? 'bg-amber-50 text-amber-600 border-amber-200' : ($issue->status === 'Closed' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200')) }} border shadow-sm">
                {{ $issue->status }}
            </span>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Issue Owner</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->issue_type ?? '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Impact</p>
                @php
                    $impactColors = [
                        'Low' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                        'Medium' => 'bg-amber-50 text-amber-600 border-amber-200',
                        'High' => 'bg-rose-50 text-rose-600 border-rose-200',
                    ];
                    $impactColor = $impactColors[$issue->impact] ?? 'bg-slate-100 text-slate-400 border-slate-200';
                @endphp
                <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider shadow-sm {{ $impactColor }}">
                    {{ $issue->impact ?? '---' }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="folder" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Project Reference</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Linked project information</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->project?->project_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Code</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->project_code ?? '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Sector</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->sector ?? '---' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Details</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Issue description and affected area</p>
            </div>
        </div>
        <div class="space-y-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Description</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $issue->description ?? '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Area Affected</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $issue->area_affected ?? '---' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Dates & Responsibility</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Tracking accountability</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Raised Date</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->raised_date ? $issue->raised_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Raised By</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->raised_by ?? '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Planned Action Date</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->planned_resolution_date ? $issue->planned_resolution_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Days Left</p>
                <p class="text-xs font-black {{ $issue->days_left !== null && $issue->days_left < 0 && $issue->status !== 'Closed' ? 'text-rose-600' : 'text-slate-900' }}">
                    @if($issue->days_left === null)
                        ---
                    @elseif($issue->days_left < 0)
                        {{ abs($issue->days_left) }} {{ abs($issue->days_left) === 1 ? 'day' : 'days' }} overdue
                    @else
                        {{ $issue->days_left }} {{ $issue->days_left === 1 ? 'day' : 'days' }} left
                    @endif
                </p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Action By</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->action_by ?? '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Issue Closed Date</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->issue_closed_date ? $issue->issue_closed_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Last Updated</p>
                <p class="text-xs font-black text-slate-900">{{ $issue->last_updated_date ? $issue->last_updated_date->format('M d, Y') : '---' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="clipboard" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Action Plan & Remarks</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Resolution tracking</p>
            </div>
        </div>
        <div class="space-y-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Action Plan</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $issue->action_plan ?? '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Remark</p>
                <p class="text-xs font-black text-slate-900 whitespace-pre-wrap">{{ $issue->remark ?? '---' }}</p>
            </div>
        </div>
    </div>
</div>
