<form action="{{ route('admin.projects.update', $project->id) }}" method="POST" id="editModalForm" class="space-y-4">
    @csrf
    @method('PUT')

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
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project ID</label>
                <input type="text" disabled value="{{ $project->custom_id }}"
                     class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-400 cursor-not-allowed">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project Code</label>
                <input type="text" name="project_code" value="{{ $project->project_code }}" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project Name</label>
                <input type="text" name="project_name" value="{{ $project->project_name }}" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Type</label>
                <select name="project_type" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="Building" {{ $project->project_type == 'Building' ? 'selected' : '' }}>Building</option>
                    <option value="Fit-Out" {{ $project->project_type == 'Fit-Out' ? 'selected' : '' }}>Fit-Out</option>
                    <option value="Infrastructure" {{ $project->project_type == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                    <option value="Mixed (Bui/Road)" {{ $project->project_type == 'Mixed (Bui/Road)' ? 'selected' : '' }}>Mixed (Bui/Road)</option>
                    <option value="Mixed (Bui/Fit-Out)" {{ $project->project_type == 'Mixed (Bui/Fit-Out)' ? 'selected' : '' }}>Mixed (Bui/Fit-Out)</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Method</label>
                <select name="delivery_method" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="DB" {{ $project->delivery_method == 'DB' ? 'selected' : '' }}>DB</option>
                    <option value="DBB" {{ $project->delivery_method == 'DBB' ? 'selected' : '' }}>DBB</option>
                    <option value="DB-LS" {{ $project->delivery_method == 'DB-LS' ? 'selected' : '' }}>DB-LS</option>
                    <option value="DB-ADM" {{ $project->delivery_method == 'DB-ADM' ? 'selected' : '' }}>DB-ADM</option>
                    <option value="DBB-ADM" {{ $project->delivery_method == 'DBB-ADM' ? 'selected' : '' }}>DBB-ADM</option>
                    <option value="DB-CP" {{ $project->delivery_method == 'DB-CP' ? 'selected' : '' }}>DB-CP</option>
                </select>
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
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Client</label>
                <input type="text" name="project_client" value="{{ $project->project_client }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Consultant</label>
                <input type="text" name="consultant" value="{{ $project->consultant }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Sector</label>
                <select name="consultancy_sector"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="Government" {{ $project->consultancy_sector == 'Government' ? 'selected' : '' }}>Government</option>
                    <option value="Private" {{ $project->consultancy_sector == 'Private' ? 'selected' : '' }}>Private</option>
                    <option value="Client / Engineering Team" {{ $project->consultancy_sector == 'Client / Engineering Team' ? 'selected' : '' }}>Client / Engineering Team</option>
                </select>
            </div>
        </div>
        <div class="space-y-1">
            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Scope</label>
                <textarea name="scope" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $project->scope }}</textarea>
        </div>
    </div>

    <!-- Section 3: Financial -->
    <div class="bg-slate-800 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-white">Budget & Cost Management</h4>
                <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">Capital (ETB)</p>
            </div>
        </div>
        <div class="grid grid-cols-5 gap-2">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-white/40 uppercase tracking-widest">Budget</label>
                <input type="number" step="0.01" name="contract_budget" value="{{ $project->contract_budget }}"
                    class="w-full px-2 py-1.5 bg-white/5 border border-white/10 rounded-md text-[10px] font-black text-[#00ADC5] focus:ring-2 focus:ring-cyan-500/20 focus:bg-white/10 focus:border-white/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-white/40 uppercase tracking-widest">Variation</label>
                <input type="number" step="0.01" name="variation" value="{{ $project->variation }}"
                    class="w-full px-2 py-1.5 bg-white/5 border border-white/10 rounded-md text-[10px] font-black text-[#00ADC5] focus:ring-2 focus:ring-cyan-500/20 focus:bg-white/10 focus:border-white/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-white/40 uppercase tracking-widest">Supp.</label>
                <input type="number" step="0.01" name="supplementary" value="{{ $project->supplementary }}"
                    class="w-full px-2 py-1.5 bg-white/5 border border-white/10 rounded-md text-[10px] font-black text-[#00ADC5] focus:ring-2 focus:ring-cyan-500/20 focus:bg-white/10 focus:border-white/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-white/40 uppercase tracking-widest">Allowable</label>
                <input type="number" step="0.01" name="total_allowable_cost" value="{{ $project->total_allowable_cost }}"
                    class="w-full px-2 py-1.5 bg-white/5 border border-white/10 rounded-md text-[10px] font-black text-white focus:ring-2 focus:ring-cyan-500/20 focus:bg-white/10 focus:border-white/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-white/40 uppercase tracking-widest">CAC</label>
                <input type="number" step="0.01" name="cost_at_completion" value="{{ $project->cost_at_completion }}"
                    class="w-full px-2 py-1.5 bg-white/5 border border-white/10 rounded-md text-[10px] font-black text-white focus:ring-2 focus:ring-cyan-500/20 focus:bg-white/10 focus:border-white/20 transition-all">
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
        <div class="grid grid-cols-3 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Baseline Start</label>
                <input type="date" name="baseline_start_date" value="{{ $project->baseline_start_date ? $project->baseline_start_date->format('Y-m-d') : '' }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Baseline Finish</label>
                <input type="date" name="baseline_finish_date" value="{{ $project->baseline_finish_date ? $project->baseline_finish_date->format('Y-m-d') : '' }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Actual Start</label>
                <input type="date" name="actual_start_date" value="{{ $project->actual_start_date ? $project->actual_start_date->format('Y-m-d') : '' }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Actual Finish</label>
                <input type="date" name="actual_finish_date" value="{{ $project->actual_finish_date ? $project->actual_finish_date->format('Y-m-d') : '' }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Last Approved EOT</label>
                <input type="date" name="approved_eot" value="{{ $project->approved_eot ? $project->approved_eot->format('Y-m-d') : '' }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Revision</label>
                <input type="text" name="revision_number" value="{{ $project->revision_number }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
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
        <div class="grid grid-cols-4 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Status</label>
                <select name="closing_status"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="FA Received" {{ $project->closing_status == 'FA Received' ? 'selected' : '' }}>FA Received</option>
                    <option value="PA Received" {{ $project->closing_status == 'PA Received' ? 'selected' : '' }}>PA Received</option>
                    <option value="PPA Received" {{ $project->closing_status == 'PPA Received' ? 'selected' : '' }}>PPA Received</option>
                    <option value="Snag / Di-Snag" {{ $project->closing_status == 'Snag / Di-Snag' ? 'selected' : '' }}>Snag / Di-Snag</option>
                    <option value="Waiting for PA" {{ $project->closing_status == 'Waiting for PA' ? 'selected' : '' }}>Waiting for PA</option>
                    <option value="Not Completed" {{ $project->closing_status == 'Not Completed' ? 'selected' : '' }}>Not Completed</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">PPA</label>
                <input type="date" name="ppa_received_at" value="{{ $project->ppa_received_at ? $project->ppa_received_at->format('Y-m-d') : '' }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">PA</label>
                <input type="date" name="pa_received_at" value="{{ $project->pa_received_at ? $project->pa_received_at->format('Y-m-d') : '' }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">FA</label>
                <input type="date" name="fa_received_at" value="{{ $project->fa_received_at ? $project->fa_received_at->format('Y-m-d') : '' }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="editModalForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Update Project
        </button>
    </div>
</form>
