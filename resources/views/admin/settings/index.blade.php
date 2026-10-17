<x-app-layout>
    <div class="mx-auto w-full max-w-5xl space-y-6 px-4 py-6 font-inter lg:px-6">
        <header class="flex flex-col justify-between gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100">
                    <i data-lucide="sliders-horizontal" aria-hidden="true" class="h-5 w-5"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-700">Account</p>
                    <h2 class="mt-0.5 text-2xl font-bold tracking-tight text-slate-900 font-outfit">Account settings</h2>
                    <p class="mt-1 text-sm text-slate-500">Manage your profile details and sign-in security in one place.</p>
                </div>
            </div>
            <div class="inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                <span class="h-2 w-2 rounded-full bg-emerald-500" aria-hidden="true"></span>
                Signed in as {{ $user->role_label }}
            </div>
        </header>

        @if(session('success'))
            <div role="status" class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                <i data-lucide="circle-check" aria-hidden="true" class="h-5 w-5 shrink-0"></i>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4">
                <div class="mb-2 flex items-center gap-2 text-rose-700">
                    <i data-lucide="triangle-alert" aria-hidden="true" class="h-4 w-4"></i>
                    <span class="text-sm font-bold">Please review the fields below.</span>
                </div>
                <ul class="space-y-1 text-sm font-medium text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.settings.account.update') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 divide-y divide-slate-100 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:divide-x lg:divide-y-0">
                <section aria-labelledby="profileSectionHeading" class="space-y-5 p-5 sm:p-7">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                            <i data-lucide="user-round" aria-hidden="true" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h3 id="profileSectionHeading" class="text-base font-bold text-slate-900">Personal details</h3>
                            <p class="mt-1 text-sm text-slate-500">Update the name and email associated with your account.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="accountName" class="mb-1.5 block text-sm font-semibold text-slate-700">Full name</label>
                            <input id="accountName" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                autocomplete="name"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                            @error('name') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="accountEmail" class="mb-1.5 block text-sm font-semibold text-slate-700">Email address</label>
                            <input id="accountEmail" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                autocomplete="email"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                            @error('email') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section aria-labelledby="securitySectionHeading" class="space-y-5 p-5 sm:p-7">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                            <i data-lucide="key-round" aria-hidden="true" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h3 id="securitySectionHeading" class="text-base font-bold text-slate-900">Change password</h3>
                            <p class="mt-1 text-sm text-slate-500">Optional. Leave these fields blank to keep your current password.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="currentPassword" class="mb-1.5 block text-sm font-semibold text-slate-700">Current password</label>
                            <input id="currentPassword" type="password" name="current_password" autocomplete="current-password"
                                placeholder="Required to set a new password"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                            @error('current_password') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="newPassword" class="mb-1.5 block text-sm font-semibold text-slate-700">New password</label>
                                <input id="newPassword" type="password" name="password" autocomplete="new-password"
                                    placeholder="At least 8 characters"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                                @error('password') <p class="mt-1 text-xs font-medium text-rose-700">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="confirmPassword" class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm new password</label>
                                <input id="confirmPassword" type="password" name="password_confirmation" autocomplete="new-password"
                                    placeholder="Re-enter new password"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                            </div>
                        </div>
                        <p class="flex items-start gap-2 text-xs leading-relaxed text-slate-500">
                            <i data-lucide="info" aria-hidden="true" class="mt-0.5 h-3.5 w-3.5 shrink-0"></i>
                            Your current password is verified before a new one is saved.
                        </p>
                    </div>
                </section>
            </div>

            <footer class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                <p class="text-xs text-slate-500">Profile and password changes are saved together.</p>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-cyan-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-600 focus:ring-offset-2">
                    <i data-lucide="save" aria-hidden="true" class="h-4 w-4"></i>
                    Save account settings
                </button>
            </footer>
        </form>
    </div>
</x-app-layout>
