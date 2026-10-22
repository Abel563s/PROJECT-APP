<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        New <span class="font-black text-slate-900">Risk</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Add a new risk item</p>
                </div>
            </div>
            <button onclick="closeCreateModal()" 
                class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 p-6">
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                        <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 mb-2">Create New Risk</h3>
                    <p class="text-[9px] uppercase font-black tracking-widest text-slate-400 mb-4">Risk creation form will be here</p>
                    <p class="text-xs text-slate-500 max-w-md">
                        This will contain a form for creating new risk items with fields for project selection, risk description, impact level, probability, mitigation strategies, and assigned responsible parties.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
