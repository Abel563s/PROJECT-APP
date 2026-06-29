<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-black text-[10px]">
                {{ $project->project_code }}
            </div>
            <div>
                <h4 class="text-base font-black text-slate-900 leading-tight">{{ $project->project_name }}</h4>
                <p class="text-[9px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $project->custom_id }}</p>
            </div>
        </div>
        @if(auth()->user() && auth()->user()->isAdmin())
            <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this project?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                    Delete
                </button>
            </form>
        @endif
    </div>

    <!-- Section 1: Project Identity -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="fingerprint" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Project Identity</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core deployment</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project ID</p>
                <p class="text-xs font-black text-slate-900">{{ $project->custom_id }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Code</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_code }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Name</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_name }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Type</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_type }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Method</p>
                <p class="text-xs font-black text-slate-900">{{ $project->delivery_method }}</p>
            </div>
        </div>
    </div>

    <!-- Section 2: Stakeholders -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Stakeholders & Scope</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Partners</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Client</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_client ?: 'Not Specified' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Consultant</p>
                <p class="text-xs font-black text-slate-900">{{ $project->consultant ?: 'Not Specified' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Sector</p>
                <p class="text-xs font-black text-slate-900">{{ $project->consultancy_sector ?: 'Not Specified' }}</p>
            </div>
        </div>
        <div>
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Scope</p>
            <p class="text-xs font-black text-slate-900 leading-relaxed">{{ $project->scope ?: 'No scope definition provided.' }}</p>
        </div>
    </div>

    <!-- Section 3: Financial -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-[#00ADC5]/10 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Budget & Cost Management</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Capital (ETB)</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-slate-50 rounded-lg p-2.5">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Contract Budget</p>
                <p class="text-sm font-black text-slate-900 font-mono">ETB {{ number_format($project->contract_budget) }}</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-2.5">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Variation</p>
                <p class="text-sm font-black text-slate-900 font-mono">ETB {{ number_format($project->variation) }}</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-2.5">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Supplementary</p>
                <p class="text-sm font-black text-slate-900 font-mono">ETB {{ number_format($project->supplementary) }}</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-2.5">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Allowable</p>
                <p class="text-sm font-black text-slate-900 font-mono">ETB {{ number_format($project->total_allowable_cost) }}</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-2.5">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Value</p>
                <p class="text-sm font-black text-slate-900 font-mono">ETB {{ number_format($project->total_project_value) }}</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-2.5">
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">CAC</p>
                <p class="text-sm font-black text-slate-900 font-mono">ETB {{ number_format($project->cost_at_completion) }}</p>
            </div>
        </div>
    </div>

    <!-- Section 4: Timeline -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Timeline</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Schedule dates</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Baseline Start</p>
                <p class="text-xs font-black text-slate-900">{{ $project->baseline_start_date ? $project->baseline_start_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Baseline Finish</p>
                <p class="text-xs font-black text-slate-900">{{ $project->baseline_finish_date ? $project->baseline_finish_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Start</p>
                <p class="text-xs font-black text-slate-900">{{ $project->actual_start_date ? $project->actual_start_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Finish</p>
                <p class="text-xs font-black text-slate-900">{{ $project->actual_finish_date ? $project->actual_finish_date->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Approved EOT</p>
                <p class="text-xs font-black text-slate-900">{{ $project->approved_eot ? $project->approved_eot->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Revision</p>
                <p class="text-xs font-black text-slate-900">{{ $project->revision_number ?: 'v1.0' }}</p>
            </div>
        </div>
    </div>

    <!-- Section 5: Closeout -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Closing Status</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Operational state</p>
            </div>
        </div>
        @php
            $colors = [
                'Not Completed' => 'bg-amber-100/50 text-amber-600 border-amber-200/50',
                'PPA Received' => 'bg-cyan-100/50 text-[#00ADC5] border-cyan-200/50',
                'PA Received' => 'bg-indigo-100/50 text-indigo-600 border-indigo-200/50',
                'FA Received' => 'bg-emerald-100/50 text-emerald-600 border-emerald-200/50',
                'Snag / Di-Snag' => 'bg-rose-100/50 text-rose-600 border-rose-200/50',
                'Waiting for PA' => 'bg-slate-100/50 text-slate-600 border-slate-200/50',
            ];
            $color = $colors[$project->closing_status] ?? 'bg-slate-100 text-slate-400 border-slate-200';
        @endphp
        <div class="grid grid-cols-4 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $color }} border">
                    {{ $project->closing_status }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">PPA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->ppa_received_at ? $project->ppa_received_at->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">PA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->pa_received_at ? $project->pa_received_at->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">FA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->fa_received_at ? $project->fa_received_at->format('M d, Y') : '---' }}</p>
            </div>
        </div>
    </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Closing Status</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Operational state</p>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $color ?? 'bg-slate-100 text-slate-400 border-slate-200' }} border">
                    {{ $project->closing_status }}
                </span>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">PPA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->ppa_received_at ? $project->ppa_received_at->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">PA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->pa_received_at ? $project->pa_received_at->format('M d, Y') : '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">FA Received</p>
                <p class="text-xs font-black text-slate-900">{{ $project->fa_received_at ? $project->fa_received_at->format('M d, Y') : '---' }}</p>
            </div>
        </div>
    </div>
</div>
