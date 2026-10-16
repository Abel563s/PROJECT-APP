<x-guest-layout>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 px-4 py-8 backdrop-blur-sm">
        <section role="dialog" aria-modal="true" aria-labelledby="firstLoginPasswordTitle"
            class="w-full max-w-md rounded-3xl border border-white/70 bg-white p-7 shadow-2xl">
            <div class="mb-6 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <i data-lucide="key-round" class="h-6 w-6"></i>
                </div>
                <h2 id="firstLoginPasswordTitle" class="text-xl font-black tracking-tight text-slate-900 font-outfit">Create your new password</h2>
                <p class="mt-2 text-sm font-medium text-slate-500">
                    Your temporary password must be replaced before you can use the system.
                </p>
            </div>

            <form method="POST" action="{{ route('password.first-login.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="password" class="mb-1 block text-xs font-bold uppercase tracking-widest text-slate-500">New password</label>
                    <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" autofocus
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 focus:border-cyan-500 focus:ring-cyan-100">
                    @error('password')
                        <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-xs font-bold uppercase tracking-widest text-slate-500">Confirm new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 focus:border-cyan-500 focus:ring-cyan-100">
                </div>

                <button type="submit" class="w-full rounded-xl bg-[#00ADC5] px-4 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-cyan-500/20 transition hover:bg-[#008C9E]">
                    Save password and continue
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
                @csrf
                <button type="submit" class="text-xs font-bold text-slate-500 transition hover:text-slate-800">Sign out</button>
            </form>
        </section>
    </div>
</x-guest-layout>
