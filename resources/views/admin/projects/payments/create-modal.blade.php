<div class="space-y-4">
    <form action="{{ route('admin.projects.payments.store', $project) }}" method="POST" id="createPaymentModalForm" class="space-y-3">
        @csrf

        <div class="space-y-1">
            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Certificate Number</label>
            <input type="text" name="certificate_number" placeholder="CERT-001"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Submitted Amount</label>
                <input type="number" step="0.01" name="submitted_amount" id="submitted_amount" placeholder="0.00"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Date Submitted</label>
                <input type="date" name="submitted_date"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-amber-600 uppercase tracking-widest">Certified Amount</label>
                <input type="number" step="0.01" name="certified_amount" id="certified_amount" placeholder="0.00"
                    class="w-full px-3 py-2 bg-amber-50/20 border border-amber-100 rounded-lg text-[10px] font-black text-slate-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Date Certified</label>
                <input type="date" name="certified_date"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-emerald-600 uppercase tracking-widest">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" id="amount_paid" placeholder="0.00"
                    class="w-full px-3 py-2 bg-emerald-50/20 border border-emerald-100 rounded-lg text-[10px] font-black text-emerald-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Payment Date</label>
                <input type="date" name="payment_date"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>
        </div>

        <div class="space-y-1">
            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Remarks</label>
            <textarea name="remarks" rows="2" placeholder="Additional notes..."
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-medium text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"></textarea>
        </div>

        <button type="submit"
            class="w-full py-2.5 bg-[#00ADC5] text-white font-black rounded-lg hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2 group">
            <span class="text-[10px] uppercase tracking-widest">Save Certificate</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1"></i>
        </button>
    </form>
</div>
