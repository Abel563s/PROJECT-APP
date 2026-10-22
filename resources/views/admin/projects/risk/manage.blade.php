<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Risk <span class="font-black text-slate-900">Management</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Project: {{ $project->project_code }} - {{ $project->project_name }}</p>
                </div>
            </div>
            <a href="{{ route('admin.projects.risk.index') }}" 
               class="flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-slate-900/20">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Back to Risk Register
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 p-6">
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                        <i data-lucide="settings" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2">Risk Management</h3>
                    <p class="text-[9px] uppercase font-black tracking-widest text-slate-400 mb-4">Risk management interface for {{ $project->project_code }}</p>
                    <p class="text-xs text-slate-500 max-w-md">
                        This page will contain detailed risk management tools including risk identification, assessment, mitigation planning, and tracking for this specific project.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
