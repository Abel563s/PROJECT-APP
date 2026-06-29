<form action="{{ route('admin.weekly-updates.update', $update->id) }}" method="POST" id="editModalForm" class="space-y-4">
    @csrf
    @method('PUT')

    <!-- Section 1: Project Info -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="briefcase" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Project</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Select project for weekly update</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project Name</label>
                <select name="project_id" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $update->project_id == $project->id ? 'selected' : '' }}>{{ $project->project_name }} ({{ $project->project_code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Contact Person (Client)</label>
                <input type="text" name="contact_person" value="{{ $update->contact_person }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Responsible Person (Internal)</label>
                <input type="text" name="responsible_person" value="{{ $update->responsible_person }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <!-- Section 2: Reconnaissance Logic -->
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
            <div class="space-y-1">
                <label class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">Current Status</label>
                <textarea name="status" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $update->status }}</textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Activities Planned Next Week</label>
                <textarea name="activity_planned_next_week" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $update->activity_planned_next_week }}</textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Remaining Items</label>
                <textarea name="remaining_items" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $update->remaining_items }}</textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-rose-500 uppercase tracking-widest">Constraints & Roadblocks</label>
                <textarea name="constraints" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $update->constraints }}</textarea>
            </div>
        </div>
        <div class="space-y-1">
            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Expected Completion Date</label>
            <input type="date" name="expected_completion_date" value="{{ $update->expected_completion_date ? $update->expected_completion_date : '' }}"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="editModalForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Update
        </button>
    </div>
</form>
