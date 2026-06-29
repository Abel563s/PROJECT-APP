<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="archive" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Project <span class="font-black text-slate-900">Closeout</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Final Protocol Reconciliation</p>
                </div>
            </div>
        </div>

        <!-- Closeout Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($projects as $project)
                <a href="{{ route('admin.projects.closeout.show', $project->id) }}"
                    class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:shadow-cyan-100 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <div class="relative z-10 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="w-11 h-11 rounded-xl bg-slate-50 flex items-center justify-center font-black text-[9px] text-[#00ADC5] border border-slate-100 group-hover:border-[#00ADC5]/30 group-hover:bg-white transition-all">
                                {{ $project->project_code }}
                            </div>
                            <span class="px-2 py-0.5 bg-slate-50 text-slate-400 rounded-md text-[8px] font-black uppercase tracking-widest group-hover:bg-cyan-50 group-hover:text-[#00ADC5] transition-all">
                                {{ $project->custom_id }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-sm font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">
                                {{ $project->project_name }}
                            </h3>
                            <p class="text-[10px] font-medium text-slate-400 mt-1 flex items-center gap-1.5">
                                <i data-lucide="building" class="w-3 h-3"></i>
                                {{ $project->project_client }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-50 flex items-center justify-between">
                            <div class="space-y-0.5">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Completion</p>
                                <p class="text-sm font-black text-slate-900">
                                    {{ number_format($project->physical_completion_percent) }}%
                                </p>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-[#00ADC5] group-hover:text-white transition-all">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-50">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-200">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-base font-black text-slate-900">No Projects Found</h3>
                        <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Initialize a project to begin closeout protocols.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
