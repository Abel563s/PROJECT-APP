<form action="{{ route('admin.projects.store') }}" method="POST" id="createModalForm" class="space-y-4">
    @csrf
    <input type="hidden" name="status" value="in progress">

    <!-- Section 1: Project Identity -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="fingerprint" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Project Information</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core deployment</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project Code</label>
                <input type="text" name="project_code" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project Name</label>
                <input type="text" name="project_name" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Construction Sector</label>
                <select name="project_type" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="Building">Building</option>
                    <option value="Fit-Out">Fit-Out</option>
                    <option value="Infrastructure">Infrastructure</option>
                    <option value="Water & Energy">Water & Energy</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Method</label>
                <select name="delivery_method" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="DB">DB</option>
                    <option value="DBB">DBB</option>
                    <option value="DB-LS">DB-LS</option>
                    <option value="DB-ADM">DB-ADM</option>
                    <option value="DBB-ADM">DBB-ADM</option>
                    <option value="DB-CP">DB-CP</option>
                </select>
            </div>
            <div class="col-span-2 space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Scope</label>
                <textarea name="scope" rows="2"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all"></textarea>
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
                <h4 class="text-sm font-black text-slate-900">Stakeholders </h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Partners</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Client</label>
                <input type="text" name="project_client"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Consultant</label>
                <input type="text" name="consultant"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Consultant Type</label>
                <select name="consultancy_sector"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="Government">Government</option>
                    <option value="Private">Private</option>
                    <option value="Client / Engineering Team">Client / Engineering Team</option>
                </select>
            </div>
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
        <div class="space-y-3">
            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-500 mb-2">Budget Management</p>
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-2">
                    <div class="space-y-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Contract Amount</label>
                        <input type="number" step="0.01" name="contract_budget"
                            class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-md text-[10px] font-black text-slate-900 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Variation</label>
                        <input type="number" step="0.01" name="variation"
                            class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-md text-[10px] font-black text-slate-900 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Supp.</label>
                        <input type="number" step="0.01" name="supplementary"
                            class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-md text-[10px] font-black text-slate-900 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Budget at Completion</label>
                        <input type="number" step="0.01" name="budget_at_completion"
                            class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-md text-[10px] font-black text-slate-900 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-500 mb-2">Cost Management</p>
                <div class="grid grid-cols-2 gap-2">
                    <div class="space-y-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Allowable</label>
                        <input type="number" step="0.01" name="total_allowable_cost"
                            class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-md text-[10px] font-black text-slate-900 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Cost at Completion</label>
                        <input type="number" step="0.01" name="cost_at_completion"
                            class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-md text-[10px] font-black text-slate-900 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>
                </div>
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
                <input type="date" name="baseline_start_date"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Baseline Finish</label>
                <input type="date" name="baseline_finish_date"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Actual Start</label>
                <input type="date" name="actual_start_date"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Actual Finish</label>
                <input type="date" name="actual_finish_date"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Approved EOT</label>
                <input type="date" name="approved_eot"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Revision</label>
                <input type="text" name="revision_number"
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
                <h4 class="text-sm font-black text-slate-900">Final Archival & Status</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Operational state</p>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Status</label>
                <select name="closing_status"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="FA Received">FA Received</option>
                    <option value="PA Received">PA Received</option>
                    <option value="PPA Received">PPA Received</option>
                    <option value="Snag / Di-Snag">Snag / Di-Snag</option>
                    <option value="Waiting for PA">Waiting for PA</option>
                    <option value="Not Completed">Not Completed</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">PPA</label>
                <input type="date" name="ppa_received_at"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">PA</label>
                <input type="date" name="pa_received_at"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">FA</label>
                <input type="date" name="fa_received_at"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="createModalForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Create Project
        </button>
    </div>
</form>

<script>
    function generateProjectCode() {
        const typeSelect = document.querySelector('select[name="project_type"]');
        const baselineInput = document.querySelector('input[name="baseline_start_date"]');
        const codeInput = document.querySelector('input[name="project_code"]');

        if (!typeSelect || !baselineInput || !codeInput) return;

        function updateCode() {
            const type = typeSelect.value;

            if (!type) {
                codeInput.value = '';
                return;
            }

            const prefix = type.substring(0, 3).toUpperCase();

            fetch(`/admin/projects/next-code?prefix=${prefix}`)
                .then(r => r.json())
                .then(data => {
                    if (data.code) codeInput.value = data.code;
                })
                .catch(() => {
                    codeInput.value = `${prefix}-001`;
                });
        }

        typeSelect.addEventListener('change', updateCode);
    }

    document.addEventListener('DOMContentLoaded', generateProjectCode);
</script>
