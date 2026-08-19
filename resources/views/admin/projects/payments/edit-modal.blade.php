<div class="space-y-4">
    <form action="{{ route('admin.projects.payments.update', $payment) }}" method="POST" id="editPaymentModalForm" class="space-y-3">
        @csrf
        @method('PUT')

        <div class="space-y-1">
            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Certificate Number</label>
            <input type="text" name="certificate_number" value="{{ $payment->certificate_number }}"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Submitted Amount</label>
                <input type="number" step="0.01" name="submitted_amount" id="edit_submitted_amount" value="{{ $payment->submitted_amount }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Date Submitted</label>
                <input type="date" name="submitted_date" value="{{ $payment->submitted_date?->format('Y-m-d') }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-amber-600 uppercase tracking-widest">Certified Amount</label>
                <input type="number" step="0.01" name="certified_amount" id="edit_certified_amount" value="{{ $payment->certified_amount }}"
                    class="w-full px-3 py-2 bg-amber-50/20 border border-amber-100 rounded-lg text-[10px] font-black text-slate-900 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Date Certified</label>
                <input type="date" name="certified_date" value="{{ $payment->certified_date?->format('Y-m-d') }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-emerald-600 uppercase tracking-widest">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" id="edit_amount_paid" value="{{ $payment->amount_paid }}"
                    class="w-full px-3 py-2 bg-emerald-50/20 border border-emerald-100 rounded-lg text-[10px] font-black text-emerald-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Payment Date</label>
                <input type="date" name="payment_date" value="{{ $payment->payment_date?->format('Y-m-d') }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>
        </div>

        <div class="space-y-1">
            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Remarks</label>
            <textarea name="remarks" rows="2"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-medium text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">{{ $payment->remarks }}</textarea>
        </div>

        <button type="submit"
            class="w-full py-2.5 bg-[#00ADC5] text-white font-black rounded-lg hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20 flex items-center justify-center gap-2 group">
            <span class="text-[10px] uppercase tracking-widest">Update Certificate</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1"></i>
        </button>
    </form>
</div>
