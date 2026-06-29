<form action="{{ route('admin.progress-updates.store') }}" method="POST" id="progressModalForm" class="space-y-4">
    @csrf

    <!-- Section 1: Performance Matrix -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="target" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Performance Matrix</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core performance data</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project</label>
                <select name="project_id" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select Project...</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->project_name }} ({{ $project->custom_id }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Planned (%)</label>
                <input type="number" step="0.01" name="progress_planned" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Actual (%)</label>
                <input type="number" step="0.01" name="progress_actual" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="col-span-2 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Calculated SPI Index</p>
                        <h4 class="text-lg font-black text-slate-900 mt-0.5" id="spiDisplay">0.00%</h4>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-[#00ADC5] shadow-sm">
                        <i data-lucide="gauge" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Financials & Timeline -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Commercial & Temporal</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Revenue and completion</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Revenue Planned (ETB)</label>
                <input type="number" step="0.01" name="revenue_planned"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black text-emerald-600 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Revenue Actual (ETB)</label>
                <input type="number" step="0.01" name="revenue_actual"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-black text-emerald-600 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Target Completion</label>
                <input type="date" name="completion_date"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <!-- Section 3: Operational Constraints -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Operational Log</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Constraints and issues</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Top Constraints</label>
                <textarea name="top_constraints" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Client Issues</label>
                <textarea name="client_issue" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Design Completion</label>
                <textarea name="design_completion_approval" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Material Submittal</label>
                <textarea name="material_submittal_approval" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Material Delivery</label>
                <textarea name="material_delivery" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Labor</label>
                <textarea name="labor" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Machinery</label>
                <textarea name="machinery_equipment" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Subcontractor</label>
                <textarea name="subcontractor" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Finance</label>
                <textarea name="finance" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Constraints</label>
                <textarea name="operation_constraint" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="progressModalForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Create Update
        </button>
    </div>
</form>
