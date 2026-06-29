<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.payments.index') }}" 
                   class="w-9 h-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#00ADC5] hover:border-[#00ADC5]/20 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Payment <span class="font-black text-slate-900">Protocols</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">{{ $project->project_name }} - {{ $project->custom_id }}</p>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2 text-slate-400">
                    <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest">Contract Value</span>
                </div>
                <p class="text-sm font-black text-slate-900">ETB {{ number_format($contractValue, 2) }}</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2 text-slate-400">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest">Total Certified</span>
                </div>
                <p class="text-sm font-black text-amber-600">ETB {{ number_format($totalCertified, 2) }}</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2 text-slate-400">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest">Total Paid</span>
                </div>
                <p class="text-sm font-black text-emerald-600">ETB {{ number_format($totalPaid, 2) }}</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2 text-slate-400">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest">Total Submitted</span>
                </div>
                <p class="text-sm font-black text-indigo-600">ETB {{ number_format($totalSubmitted, 2) }}</p>
            </div>

            <div class="bg-slate-900 p-4 rounded-2xl border-none shadow-lg space-y-2">
                <div class="flex items-center gap-2 text-slate-300">
                    <div class="w-7 h-7 rounded-lg bg-slate-800 text-sky-400 flex items-center justify-center">
                        <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest">Outstanding</span>
                </div>
                <p class="text-sm font-black text-white">ETB {{ number_format($outstandingBalance, 2) }}</p>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="flex items-center gap-2 text-slate-400">
                    <div class="w-7 h-7 rounded-lg bg-cyan-50 text-[#00ADC5] flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-[8px] font-black uppercase tracking-widest">Progress</span>
                </div>
                <p class="text-sm font-black text-slate-900">{{ number_format($paymentProgress, 1) }}%</p>
                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-[#00ADC5] rounded-full" style="width: {{ $paymentProgress }}%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <!-- LEFT COLUMN: Payment History -->
            <div class="xl:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-sky-50 text-sky-600 rounded-lg flex items-center justify-center">
                                <i data-lucide="history" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Payment History</h3>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                    <th class="px-4 py-3">Cert Details</th>
                                    <th class="px-4 py-3 text-center">Submitted</th>
                                    <th class="px-4 py-3 text-center">Certified</th>
                                    <th class="px-4 py-3 text-center">Paid</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($project->payments->sortByDesc('certificate_date') as $payment)
                                    <tr class="hover:bg-slate-50/80 transition-all group">
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="font-black text-slate-900 text-xs">#{{ $payment->certificate_number }}</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $payment->certificate_date->format('d M, Y') }}</span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            @if($payment->submitted_amount)
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-black text-indigo-600">ETB {{ number_format($payment->submitted_amount, 2) }}</span>
                                                    <span class="text-[9px] font-bold text-slate-400">{{ $payment->submitted_date ? $payment->submitted_date->format('d M, Y') : '-' }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-300 text-xs">-</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            @if($payment->certified_amount)
                                                <div class="flex flex-col border-x border-slate-50 px-2">
                                                    <span class="text-xs font-black text-amber-600">ETB {{ number_format($payment->certified_amount, 2) }}</span>
                                                    <span class="text-[9px] font-bold text-slate-400">{{ $payment->certified_date ? $payment->certified_date->format('d M, Y') : '-' }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-300 text-xs">-</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            @if($payment->amount_paid)
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-black text-emerald-600">ETB {{ number_format($payment->amount_paid, 2) }}</span>
                                                    <span class="text-[9px] font-bold text-slate-400">{{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : '-' }}</span>
                                                </div>
                                            @else
                                                <span class="text-slate-300 text-xs">-</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            @php
                                                $status = $payment->status;
                                                $statusClass = [
                                                    'Paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'Partially Paid' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    'Pending' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                ][$status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                            @endphp
                                            <span class="inline-flex px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $statusClass }} border">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button onclick="showPaymentDetail({{ $payment->id }})"
                                                class="p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-sky-500 hover:border-sky-500 hover:shadow-sm transition-all">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-200">
                                                    <i data-lucide="file-minus" class="w-5 h-5"></i>
                                                </div>
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No payment certificates recorded yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Add New Payment Form -->
            <div id="addPaymentForm" class="space-y-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-[#00ADC5] text-white rounded-lg flex items-center justify-center">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Record Payment</h3>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">New certificate entry</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.projects.payments.store', $project) }}" method="POST" class="space-y-3">
                        @csrf

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Cert Number</label>
                                <input type="text" name="certificate_number" required placeholder="CERT-001"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Cert Date</label>
                                <input type="date" name="certificate_date" required
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
                            </div>
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
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-3">
                    <div id="modalIcon" class="w-8 h-8 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle" class="text-sm font-black text-slate-900 tracking-tight">Certificate Details</h3>
                        <p id="modalSubtitle" class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Breakdown Summary</p>
                    </div>
                </div>
                <button onclick="closePaymentModal()"
                    class="w-7 h-7 rounded-lg hover:bg-white text-slate-400 hover:text-slate-600 transition-all flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-5 space-y-4">
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100">
                        <p class="text-[8px] font-black text-indigo-400 uppercase tracking-widest mb-1">Submitted</p>
                        <p id="modal_submitted_amount" class="text-sm font-black text-indigo-600"></p>
                        <p id="modal_submitted_date" class="text-[9px] font-bold text-indigo-400 mt-0.5"></p>
                    </div>
                    <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-100">
                        <p class="text-[8px] font-black text-amber-600 uppercase tracking-widest mb-1">Certified</p>
                        <p id="modal_certified_amount" class="text-sm font-black text-amber-600"></p>
                        <p id="modal_certified_date" class="text-[9px] font-bold text-amber-400 mt-0.5"></p>
                    </div>
                    <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100">
                        <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest mb-1">Total Paid</p>
                        <p id="modal_amount_paid" class="text-sm font-black text-emerald-600"></p>
                        <p id="modal_payment_date" class="text-[9px] font-bold text-emerald-400 mt-0.5"></p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Remarks</p>
                    <div class="p-3 bg-slate-50 rounded-lg text-slate-600 font-medium text-xs border border-slate-100 italic" id="modal_remarks">
                        No additional remarks recorded for this certificate.
                    </div>
                </div>

                <div id="modal_status_banner"
                    class="p-3 rounded-xl flex items-center justify-between font-black text-xs uppercase tracking-widest">
                    <span>Current Status</span>
                    <span id="modal_status_text"></span>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100">
                <button onclick="closePaymentModal()"
                    class="w-full py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg hover:bg-slate-100 transition-all text-xs">Close</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function formatDate(dateString) {
                if (!dateString) return '-';
                return new Date(dateString).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            }

            function formatCurrency(amount) {
                return 'ETB ' + (parseFloat(amount) || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function showPaymentDetail(paymentId) {
                fetch(`/admin/payments/${paymentId}`)
                    .then(response => response.json())
                    .then(data => {
                        const modal = document.getElementById('paymentModal');

                        document.getElementById('modalTitle').textContent = `Certificate #${data.certificate_number}`;
                        document.getElementById('modalSubtitle').textContent = `Certificate Date: ${formatDate(data.certificate_date)}`;

                        document.getElementById('modal_submitted_amount').textContent = formatCurrency(data.submitted_amount);
                        document.getElementById('modal_submitted_date').textContent = data.submitted_date ? `On ${formatDate(data.submitted_date)}` : 'Not recorded';

                        document.getElementById('modal_certified_amount').textContent = formatCurrency(data.certified_amount);
                        document.getElementById('modal_certified_date').textContent = data.certified_date ? `On ${formatDate(data.certified_date)}` : 'Not recorded';

                        document.getElementById('modal_amount_paid').textContent = formatCurrency(data.amount_paid);
                        document.getElementById('modal_payment_date').textContent = data.payment_date ? `On ${formatDate(data.payment_date)}` : 'Not recorded';

                        document.getElementById('modal_remarks').textContent = data.remarks || 'No additional remarks.';

                        const banner = document.getElementById('modal_status_banner');
                        const text = document.getElementById('modal_status_text');
                        const icon = document.getElementById('modalIcon');

                        const target = parseFloat(data.certified_amount) || parseFloat(data.submitted_amount) || 0;
                        const paid = parseFloat(data.amount_paid) || 0;

                        if (target > 0 && paid >= target) {
                            banner.className = 'p-3 rounded-xl flex items-center justify-between font-black text-xs uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100';
                            text.textContent = 'Fully Paid';
                            icon.className = 'w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-500 text-white';
                        } else if (paid > 0) {
                            banner.className = 'p-3 rounded-xl flex items-center justify-between font-black text-xs uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100';
                            text.textContent = 'Partially Paid';
                            icon.className = 'w-8 h-8 rounded-lg flex items-center justify-center bg-amber-500 text-white';
                        } else {
                            banner.className = 'p-3 rounded-xl flex items-center justify-between font-black text-xs uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-100';
                            text.textContent = 'Payment Pending';
                            icon.className = 'w-8 h-8 rounded-lg flex items-center justify-center bg-rose-500 text-white';
                        }

                        modal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                        if (window.lucide) lucide.createIcons();
                    });
            }

            function closePaymentModal() {
                document.getElementById('paymentModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            document.getElementById('paymentModal').addEventListener('click', function (e) {
                if (e.target === this) closePaymentModal();
            });
        </script>
    @endpush
</x-app-layout>
