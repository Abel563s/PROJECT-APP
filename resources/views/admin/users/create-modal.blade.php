<form action="{{ route('admin.users.store') }}" method="POST" id="createModalForm" class="space-y-4">
    @csrf

    <!-- Section 1: Identity -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="fingerprint" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Identity Details</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core credentials</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Full Name</label>
                <input type="text" name="name" required placeholder="Johnathan Doe"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Email</label>
                <input type="email" name="email" required placeholder="name@company.com"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <!-- Section 2: Access Tier -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Access Tier</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Permission level</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Role</label>
                <select name="role" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="user">Standard User</option>
                    <option value="manager">Lead Manager</option>
                    <option value="admin">Root Admin</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">State</label>
                <select name="is_active" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="1">Active / Operational</option>
                    <option value="0">Decommissioned / Static</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Section 3: Security Key -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="key-round" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Security Key</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Authentication credentials</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                <input type="password" name="password" required placeholder="Minimum 8 characters"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Confirm</label>
                <input type="password" name="password_confirmation" required placeholder="Re-enter key"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="createModalForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Deploy Identity
        </button>
    </div>
</form>
