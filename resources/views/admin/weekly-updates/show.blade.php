<x-app-layout>
    <div class="py-10 px-6 max-w-[1200px] mx-auto font-inter">
        <div class="text-center py-20">
            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mx-auto mb-4">
                <i data-lucide="calendar-days" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900">Weekly Update</h3>
            <p class="text-[9px] uppercase font-black tracking-widest text-slate-400 mt-1">Use the view button on the registry to see details in a modal.</p>
            <a href="{{ route('admin.weekly-updates.index') }}" class="inline-block mt-4 px-5 py-2 bg-slate-900 text-white rounded-lg font-black text-[9px] uppercase tracking-widest">Back to Registry</a>
        </div>
    </div>
</x-app-layout>
