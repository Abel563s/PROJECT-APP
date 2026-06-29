<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="shield-lock" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Security <span class="font-black text-slate-900">Protocols</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Personal Identity Authentication</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-100 rounded-xl shadow-sm">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[9px] font-black text-slate-900 tracking-widest uppercase leading-tight">Active Session</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
                <p class="text-xs font-black text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-100 rounded-xl p-4">
                <div class="flex items-center gap-3 text-rose-600 mb-3">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Authentication Errors</span>
                </div>
                <ul class="space-y-1.5">
                    @foreach ($errors->all() as $error)
                        <li class="text-xs font-bold text-rose-500 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-rose-400"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Profile Section -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Profile Identity</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Personal credentials</p>
                    </div>
                </div>

                <form action="{{ route('admin.settings.profile.update') }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>

                    <button type="submit"
                        class="w-full py-2 bg-indigo-500 text-white rounded-lg font-black text-[9px] uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20">
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- Password Section -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Security Key</h3>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Update authentication credentials</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.password.update') }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Current Key</label>
                                <input type="password" name="current_password" required placeholder="Verify current"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">New Key</label>
                                <input type="password" name="password" required placeholder="New passphrase"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Confirm Key</label>
                                <input type="password" name="password_confirmation" required placeholder="Confirm new"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
                                Synchronize Keys
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Note -->
                <div class="bg-slate-900 rounded-2xl p-4 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-[#00ADC5]/10 rounded-full blur-2xl"></div>
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">Protocol Note</p>
                            <p class="text-xs font-bold text-white/80 mt-1 leading-relaxed">
                                Updating your identity key will terminate all other active sessions immediately. Verify your connection stability before proceeding.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
