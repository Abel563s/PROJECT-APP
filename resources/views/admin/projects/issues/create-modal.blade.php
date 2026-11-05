    <form action="{{ $project ? route('admin.projects.issues.store', $project) : route('admin.projects.issues.store') }}" method="POST" id="createIssueForm" class="space-y-4">
    @csrf
    <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}">
    <input type="hidden" name="sector" id="issueSector" value="{{ $project->consultancy_sector ?? '' }}">
    <input type="hidden" name="project_code" id="issueProjectCode" value="{{ $project->project_code ?? '' }}">
    <input type="hidden" name="last_updated_date" id="lastUpdatedDate">

    <!-- Section 1: Project Reference -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="folder" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Project Reference</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Issue will be registered for this project</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Code</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_code ?? '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project Name</p>
                <p class="text-xs font-black text-slate-900">{{ $project->project_name ?? '---' }}</p>
            </div>
        </div>
    </div>

    <!-- Section 2: Issue Details -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Issue Details</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core issue information</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Issue Type</label>
                <select name="issue_type" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select Type</option>
                    <option value="Design Completion/Approval">Design Completion/Approval</option>
                    <option value="Material Submittal/Approval">Material Submittal/Approval</option>
                    <option value="Material Delivery">Material Delivery</option>
                    <option value="Labor">Labor</option>
                    <option value="Machinery/Equipment Tools">Machinery/Equipment Tools</option>
                    <option value="Subcontractor">Subcontractor</option>
                    <option value="Finance">Finance</option>
                    <option value="Supplier">Supplier</option>
                    <option value="Operation">Operation</option>
                    <option value="Client Issue">Client Issue</option>
                    <option value="General">General</option>
                    <option value="Procurement">Procurement</option>
                    <option value="Contract Issue">Contract Issue</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Impact</label>
                <select name="impact" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select Impact</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Status</label>
                <p data-issue-status class="inline-flex rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-[10px] font-bold text-slate-600">Raised</p>
                <p class="text-[8px] font-medium text-slate-400">Automatically set from the planned action and issue closed dates.</p>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Issue</label>
                <input type="text" name="issue" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Description</label>
                <textarea name="description" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Area Affected</label>
                <textarea name="area_affected" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
        </div>
    </div>

    <!-- Section 3: Dates & People -->
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
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Data Date</label>
                <input type="date" name="date" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Raised Date</label>
                <input type="date" name="raised_date" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Raised By</label>
                <input type="text" name="raised_by" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Action Plan</label>
                <textarea name="action_plan" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Planned Action Date</label>
                <input type="date" name="planned_resolution_date"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Issue Closed Date</label>
                <input type="date" name="issue_closed_date"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Action By</label>
                <input type="text" name="action_by"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Remark</label>
                <textarea name="remark" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="createIssueForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Create Issue
        </button>
    </div>
</form>
