<header
    class="h-16 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 flex items-center justify-between px-4 sm:px-6 lg:px-10 sticky top-0 z-30 shadow-[0_4px_20px_rgba(0,0,0,0.03)] transition-all duration-300">
    <div class="flex flex-col">
        <h1 class="text-lg font-black text-slate-900 tracking-tight leading-none font-outfit">
            @if(isset($header))
                <span class="bg-gradient-to-r from-[#00515f] to-[#00ADC5] bg-clip-text text-transparent">{{ $header }}</span>
            @else
                <span class="bg-gradient-to-r from-[#00515f] to-[#00ADC5] bg-clip-text text-transparent">@yield('page-title', 'Project Management')</span>
            @endif
        </h1>
        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1.5 font-inter">
            @php($roleLabel = Auth::user()->role_label)
            EEC | PROJECTS • <span class="text-[#00ADC5]">{{ $roleLabel }}</span>
        </p>
    </div>

    <div class="flex items-center gap-3">
        @include('partials.theme-toggle')

        <!-- Notifications Dropdown -->
        @include('partials.notifications-dropdown')

        <div class="relative group" x-data="{ open: false }">
            <button @click="open = !open"
                class="relative w-10 h-10 rounded-xl bg-gradient-to-br from-[#00515f]/10 to-[#00ADC5]/10 border border-[#00515f]/15 text-[#00515f] flex items-center justify-center font-black text-sm hover:from-[#00515f]/15 hover:to-[#00ADC5]/15 hover:border-[#00515f]/25 hover:shadow-lg hover:shadow-[#00ADC5]/10 transition-all duration-300 active:scale-95 group overflow-hidden">
                {{ substr(Auth::user()->name, 0, 1) }}
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-slate-100 overflow-hidden z-50 py-1"
                x-cloak>
                <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-r from-[#00515f]/5 to-[#00ADC5]/5">
                    <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest font-inter">Identity
                        Overview</p>
                </div>
                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-[#f0fbfd] hover:text-[#00515f] transition-all duration-200 font-inter">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    Settings
                </a>
                <div class="border-t border-slate-100"></div>
                <form method="POST" action="{{ route('logout') }}" data-logout-form>
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-rose-500 hover:bg-rose-50 transition-all duration-200 font-inter">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>