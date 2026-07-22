<x-app-layout>
    <div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.project-sites.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#00ADC5] hover:border-[#00ADC5]/20 hover:bg-[#f0fbfd] transition-all">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">New Project Site</h1>
                    <p class="text-slate-500 font-medium text-sm">Add a new project site location.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <form action="{{ route('admin.project-sites.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Site Name</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl" placeholder="e.g. Main Warehouse">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Site Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl" placeholder="e.g. WH-01">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl resize-none" placeholder="Full address...">{{ old('address') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('admin.project-sites.index') }}" class="px-8 py-3 rounded-2xl font-bold text-slate-500 hover:bg-slate-100 transition-all">Cancel</a>
                    <button type="submit" class="bg-[#00ADC5] hover:bg-[#0099b3] text-white px-10 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-[#00ADC5]/20 hover:scale-105 active:scale-95">Create Site</button>
                </div>
            </form>
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
