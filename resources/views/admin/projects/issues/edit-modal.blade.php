<form action="{{ route('admin.projects.issues.update', $issue->id) }}" method="POST" id="editIssueForm" class="space-y-4">
    @csrf
    @method('PUT')
    <input type="hidden" name="last_updated_date" id="lastUpdatedDate" value="{{ $issue->last_updated_date ?? now()->format('Y-m-d H:i') }}">

    <!-- Section 1: Project Reference -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="folder" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Project Reference</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Link issue to existing project</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project</label>
                <select name="project_id" id="issueProjectSelect" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" data-sector="{{ $project->consultancy_sector }}" data-code="{{ $project->project_code }}" {{ $issue->project_id == $project->id ? 'selected' : '' }}>
                            {{ $project->project_code }} - {{ $project->project_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Sector</label>
                <input type="text" name="sector" id="issueSector" value="{{ $issue->sector }}" readonly
                    class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 cursor-not-allowed focus:ring-0">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project Code</label>
                <input type="text" name="project_code" id="issueProjectCode" value="{{ $issue->project_code }}" readonly
                    class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 cursor-not-allowed focus:ring-0">
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
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Issue Owner</label>
                <select name="issue_type" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select Type</option>
                    @foreach(['Design Completion/Approval', 'Material Submittal/Approval', 'Material Delivery', 'Labor', 'Machinery/Equipment Tools', 'Subcontractor', 'Finance', 'Supplier', 'Operation', 'Client Issue', 'General', 'Procurement', 'Contract Issue'] as $type)
                        <option value="{{ $type }}" {{ $issue->issue_type === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Impact</label>
                <select name="impact" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select Impact</option>
                    <option value="Low" {{ $issue->impact === 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $issue->impact === 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $issue->impact === 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Status</label>
                @if(in_array($issue->status, ['Open', 'Closed', 'In Progress'], true))
                    <select name="status" required
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                        <option value="Open" {{ $issue->status === 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Closed" {{ $issue->status === 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                @else
                    <input type="hidden" name="status" value="{{ $issue->status }}">
                    <p class="rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-[10px] font-bold text-slate-500">
                        {{ $issue->status }} <span class="font-normal">(existing status retained)</span>
                    </p>
                @endif
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Issue</label>
                <input type="text" name="issue" required value="{{ $issue->issue }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Description</label>
                <textarea name="description" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $issue->description }}</textarea>
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Area Affected</label>
                <textarea name="area_affected" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-rose-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $issue->area_affected }}</textarea>
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
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Raised Date</label>
                <input type="date" name="raised_date" required value="{{ $issue->raised_date ? $issue->raised_date->format('Y-m-d') : '' }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Raised By</label>
                <input type="text" name="raised_by" required value="{{ $issue->raised_by }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Action Plan</label>
                <textarea name="action_plan" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $issue->action_plan }}</textarea>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Planned Action Date</label>
                <input type="date" name="planned_resolution_date" value="{{ $issue->planned_resolution_date ? $issue->planned_resolution_date->format('Y-m-d') : '' }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Action By</label>
                <input type="text" name="action_by" value="{{ $issue->action_by }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Issue Closed Date</label>
                <input type="date" name="issue_closed_date" value="{{ $issue->issue_closed_date ? $issue->issue_closed_date->format('Y-m-d') : '' }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Last Updated</label>
                <input type="text" id="lastUpdatedDisplay" value="{{ $issue->last_updated_date ? $issue->last_updated_date->format('M d, Y') : now()->format('M d, Y') }}" readonly
                    class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500 cursor-not-allowed focus:ring-0">
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Remark</label>
                <textarea name="remark" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">{{ $issue->remark }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="editIssueForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Update Issue
        </button>
    </div>
</form>
