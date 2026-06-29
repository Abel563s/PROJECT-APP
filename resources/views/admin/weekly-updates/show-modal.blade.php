<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
            <i data-lucide="file-text" class="w-5 h-5"></i>
        </div>
        <div>
            <h4 class="text-base font-black text-slate-900 leading-tight">{{ $update->project->project_name }}</h4>
            <p class="text-[9px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $update->project->custom_id }}</p>
        </div>
    </div>

    <!-- Project Info -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="briefcase" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Project Information</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Asset details</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Contact Person</p>
                <p class="text-xs font-black text-slate-900">{{ $update->contact_person ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Responsible Person</p>
                <p class="text-xs font-black text-slate-900">{{ $update->responsible_person ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Expected Completion</p>
                <p class="text-xs font-black text-slate-900">{{ $update->expected_completion_date ? \Carbon\Carbon::parse($update->expected_completion_date)->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Report Week</p>
                <p class="text-xs font-black text-slate-900">{{ $update->created_at->format('W, Y') }} (Week)</p>
            </div>
        </div>
    </div>

    <!-- Reconnaissance Logic -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="activity" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Reconnaissance Logic</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Current state and projections</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="col-span-2">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Current Status</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->status ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Activities Planned Next Week</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->activity_planned_next_week ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Remaining Items</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->remaining_items ?: '---' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest mb-1">Constraints & Roadblocks</p>
                <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $update->constraints ?: '---' }}</p>
            </div>
        </div>
    </div>
</div>
