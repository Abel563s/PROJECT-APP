<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Financial <span class="font-black text-slate-900">Protocols</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Project Payment Management</p>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($projects as $project)
                <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:shadow-cyan-100 transition-all duration-300 overflow-hidden">
                    <div class="p-5 space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                                <i data-lucide="building-2" class="w-5 h-5"></i>
                            </div>
                            <span class="px-2 py-0.5 bg-slate-50 text-slate-400 rounded-md text-[8px] font-black tracking-widest uppercase">
                                {{ $project->custom_id }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-sm font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">
                                {{ $project->project_name }}
                            </h3>
                            <p class="text-[9px] font-black text-[#00ADC5] mt-0.5">{{ $project->project_code }}</p>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-slate-400 font-black uppercase tracking-widest">Contract Value</span>
                                <span class="text-slate-900 font-black">ETB {{ number_format($project->total_project_value) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-slate-400 font-black uppercase tracking-widest">Total Certified</span>
                                <span class="text-amber-500 font-black">ETB {{ number_format($project->total_certified) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-slate-400 font-black uppercase tracking-widest text-[#00ADC5]">Remaining Balance</span>
                                <span class="text-slate-900 font-black">ETB {{ number_format($project->total_project_value - $project->total_certified) }}</span>
                            </div>

                            @php
                                $progress = $project->total_project_value > 0 ? ($project->total_certified / $project->total_project_value) * 100 : 0;
                            @endphp

                            <div class="pt-1.5">
                                <div class="flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400 mb-1">
                                    <span>Progress</span>
                                    <span>{{ number_format($progress, 1) }}%</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#00ADC5] to-blue-500 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-2.5 bg-slate-50 border-t border-slate-100">
                        <a href="{{ route('admin.projects.payments.manage', $project) }}"
                            class="w-full flex items-center justify-center gap-2 py-2 bg-white border border-slate-200 text-slate-700 font-black text-[9px] uppercase tracking-widest rounded-lg hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-300 shadow-sm">
                            <span>Manage Payments</span>
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($projects->isEmpty())
            <div class="py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-50">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-200">
                        <i data-lucide="database" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-base font-black text-slate-900">No Projects Found</h3>
                    <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Create a project first to initialize financial protocols.</p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
