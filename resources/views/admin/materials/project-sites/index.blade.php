<x-app-layout>
    <div class="py-10 px-6 space-y-8 max-w-[1700px] mx-auto font-inter animate-fade-in">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 pb-4 border-b border-slate-100">
            <div class="space-y-3">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#f0fbfd] flex items-center justify-center text-[#00ADC5] shadow-xl shadow-[#00ADC5]/10">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-light text-slate-400 tracking-tight font-outfit leading-none">
                            Project <span class="font-black text-slate-900">Sites</span>
                        </h2>
                        <p class="text-[10px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-2">Location Registry</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.project-sites.create') }}" class="group/btn relative px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-xl shadow-slate-900/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-2">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        Add Site
                    </span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[3.5rem] shadow-2xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/80 backdrop-blur-md">
                            <th class="px-12 py-10 text-[10px] font-black text-slate-500 uppercase tracking-[0.25em] border-b border-slate-100">Site Name</th>
                            <th class="px-8 py-10 text-[10px] font-black text-slate-500 uppercase tracking-[0.25em] border-b border-slate-100">Code</th>
                            <th class="px-8 py-10 text-[10px] font-black text-slate-500 uppercase tracking-[0.25em] border-b border-slate-100">Address</th>
                            <th class="px-12 py-10 text-right border-b border-slate-100"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($sites as $site)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-500 odd:bg-white even:bg-slate-50/20">
                                <td class="px-12 py-9">
                                    <p class="text-base font-black text-slate-900 group-hover:text-[#00ADC5] transition-colors">{{ $site->name }}</p>
                                </td>
                                <td class="px-8 py-9">
                                    <span class="inline-flex px-3 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-tight rounded-xl">{{ $site->code ?? '—' }}</span>
                                </td>
                                <td class="px-8 py-9">
                                    <span class="text-sm font-bold text-slate-700">{{ $site->address ?? '—' }}</span>
                                </td>
                                <td class="px-12 py-9 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.project-sites.destroy', $site) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this site?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-300" title="Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-12 py-32 text-center">
                                    <div class="flex flex-col items-center gap-6">
                                        <div class="relative">
                                            <div class="w-24 h-24 rounded-[3rem] bg-slate-50 flex items-center justify-center text-slate-200 animate-pulse">
                                                <i data-lucide="map-pin" class="w-12 h-12"></i>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <h3 class="text-2xl font-black text-slate-900">No Sites Found</h3>
                                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-400">No project sites registered.</p>
                                        </div>
                                        <a href="{{ route('admin.project-sites.create') }}" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                                            Add First Site
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
