<x-app-layout>
    @include('admin.projects.risk-pool.partials._tabs', ['activeTab' => 'eot'])
    <div class="py-4 px-4 lg:px-6 w-full max-w-none font-inter">
        <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm">
            <div class="flex flex-col items-center gap-4 py-12">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-200">
                    <i data-lucide="clock" class="h-8 w-8"></i>
                </div>
                <div class="space-y-2 text-center">
                    <h3 class="text-lg font-black text-slate-900">EOT Pool</h3>
                    <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">This section is under construction.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
