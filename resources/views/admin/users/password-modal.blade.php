<form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="pwModalForm" class="space-y-4">
    @csrf
    @method('PUT')

    <input type="hidden" name="name" value="{{ $user->name }}">
    <input type="hidden" name="email" value="{{ $user->email }}">
    <input type="hidden" name="role" value="{{ $user->role }}">
    <input type="hidden" name="is_active" value="{{ $user->is_active }}">

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700">
                <span class="font-black text-sm">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div>
                <p class="text-sm font-black text-slate-900">{{ $user->name }}</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="key-round" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Security Key</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Update authentication credentials</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">New Password</label>
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
        <button type="submit" form="pwModalForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Update Key
        </button>
    </div>
</form>
