<x-guest-layout>
    <section aria-labelledby="firstLoginPasswordTitle" class="relative z-10 mx-auto w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 text-left shadow-xl sm:p-8">
        <div class="mb-6 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
                <i data-lucide="key-round" class="h-6 w-6"></i>
            </div>
            <h2 id="firstLoginPasswordTitle" class="text-2xl font-black leading-tight tracking-tight text-slate-900 font-outfit">
                Create your new password
            </h2>
            <p class="mt-3 text-sm font-medium leading-relaxed text-slate-600">
                Your temporary password must be replaced before you can use the system.
            </p>
        </div>

        <form method="POST" action="{{ route('password.first-login.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="password" class="mb-2 block text-sm font-bold text-slate-800">New password</label>
                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" autofocus
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-base font-medium text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-4 focus:ring-cyan-100">
                <p class="mt-1.5 text-xs font-medium text-slate-500">Use at least 8 characters.</p>
                @error('password')
                    <p class="mt-2 text-sm font-semibold text-rose-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-800">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-base font-medium text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-4 focus:ring-cyan-100">
            </div>

            <button type="submit" class="w-full rounded-xl bg-cyan-700 px-4 py-3.5 text-sm font-extrabold tracking-wide text-white shadow-lg shadow-cyan-900/15 transition hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-200">
                Save password and continue
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-5 border-t border-slate-100 pt-4 text-center">
            @csrf
            <button type="submit" class="text-sm font-semibold text-slate-600 underline-offset-4 transition hover:text-slate-900 hover:underline">Sign out</button>
        </form>
    </section>
</x-guest-layout>
