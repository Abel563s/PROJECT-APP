<aside id="sidebar"
    class="w-64 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] bg-gradient-to-b from-[#00ADC5] to-[#008C9E] flex flex-col z-40 relative shadow-[0_10px_40px_rgba(0,0,0,0.12)] group/sidebar overflow-visible">

    <div
        class="h-16 flex items-center justify-between px-6 border-b border-white/10 shrink-0 overflow-hidden relative z-10 transition-all duration-500">
        <div class="flex items-center gap-3">
            <div
                class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center shrink-0 border border-white/20 shadow-sm transition-transform hover:scale-110 active:scale-95 duration-300 backdrop-blur-sm">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-6 h-6 object-contain brightness-0 invert">
            </div>
            <div class="flex flex-col leading-none sidebar-text animate-pop-in">
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/80">Planning</span>
                <span class="text-sm font-black tracking-tighter mt-0.5 text-white">Intelligence</span>
            </div>
        </div>
    </div>

    <!-- Floating External Toggle -->
    <button id="sidebarToggle"
        class="absolute -right-4 top-10 w-8 h-8 flex items-center justify-center rounded-full bg-white border border-white/30 text-[#00ADC5] hover:text-[#008C9E] hover:border-white/50 hover:scale-110 active:scale-90 transition-all duration-300 shadow-xl z-[60] group/toggle">
        <i data-lucide="chevron-left"
            class="w-4 h-4 sidebar-toggle-icon transition-transform duration-500 group-hover/toggle:translate-x-[-2px]"></i>
        <i data-lucide="chevron-right"
            class="w-4 h-4 sidebar-toggle-icon hidden transition-transform duration-500 group-hover/toggle:translate-x-[2px]"></i>
    </button>

    <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto custom-scrollbar relative z-10 font-inter">
        @php
            $menu = [];

            if (Auth::check()) {
                if (Auth::user()->isAdmin()) {
                    // 1. Dashboard
                    $menu[] = [
                        'label' => 'Dashboard',
                        'icon' => 'layout-dashboard',
                        'route' => 'admin.dashboard',
                        'active' => request()->routeIs('admin.dashboard')
                    ];

                    // 2. Project Management
                    $menu[] = [
                        'label' => 'Project',
                        'icon' => 'briefcase',
                        'active' => request()->routeIs('admin.projects.*') && !request()->routeIs('admin.projects.analytics'),
                        'submenu' => [
                            [
                                'label' => 'Project Registry',
                                'route' => 'admin.projects.index',
                                'active' => request()->routeIs('admin.projects.index') && !request()->routeIs('admin.projects.create')
                            ],
                            [
                                'label' => 'Project Closeout',
                                'route' => 'admin.projects.closeout.index',
                                'active' => request()->routeIs('admin.projects.closeout.*')
                            ],
                            [
                                'label' => 'Project Payments',
                                'route' => 'admin.projects.payments.index',
                                'active' => request()->routeIs('admin.projects.payments.*')
                            ],
                        ]
                    ];

                    // 3. Project Reconnaissance
                    $menu[] = [
                        'label' => 'Progress Updates',
                        'icon' => 'trending-up',
                        'route' => 'admin.progress-updates.index',
                        'active' => request()->routeIs('admin.progress-updates.*'),
                        'pop' => true
                    ];

                    $menu[] = [
                        'label' => 'Weekly Closing',
                        'icon' => 'calendar-days',
                        'route' => 'admin.weekly-updates.index',
                        'active' => request()->routeIs('admin.weekly-updates.*'),
                        'pop' => true
                    ];

                    // 4. Reports (Moved out of dropdown)
                    $menu[] = [
                        'label' => 'Reports',
                        'icon' => 'bar-chart-3',
                        'route' => 'admin.projects.analytics',
                        'active' => request()->routeIs('admin.projects.analytics'),
                        'pop' => true
                    ];

                    // 5. Roles (User Management)
                    $menu[] = [
                        'label' => 'Access Control',
                        'icon' => 'users-2',
                        'route' => 'admin.roles.index',
                        'active' => request()->routeIs('admin.roles.*') || request()->routeIs('admin.users.*')
                    ];
                } else {
                    $menu[] = ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')];
                }
            }
        @endphp

        @foreach ($menu as $item)
            @if(isset($item['is_header']) && $item['is_header'])
                <div class="px-4 py-3 mt-4 first:mt-0">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/50">
                        {{ $item['label'] }}
                    </span>
                </div>
            @elseif(isset($item['submenu']))
                <div x-data="{ open: {{ $item['active'] ? 'true' : 'false' }} }" x-on:sidebar:collapsed.window="open = false" class="space-y-1">
                    <button @click="open = !open"
                        class="w-full flex items-center gap-3.5 p-3 rounded-xl transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] group/item relative {{ $item['active'] ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">

                        <i data-lucide="{{ $item['icon'] }}"
                            class="w-5 h-5 shrink-0 transition-all duration-500 {{ $item['active'] ? 'stroke-[2.5px] text-white' : 'opacity-70 group-hover/item:opacity-100' }}"></i>

                        <span
                            class="sidebar-text font-semibold text-sm whitespace-nowrap tracking-tight transition-all duration-500">{{ $item['label'] }}</span>

                        <i data-lucide="chevron-down" class="w-4 h-4 ml-auto transition-transform duration-300 sidebar-text"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse x-cloak class="pl-12 space-y-1 overflow-hidden">
                        @foreach ($item['submenu'] as $subitem)
                            <a href="{{ route($subitem['route']) }}"
                                class="flex items-center gap-3 p-2 rounded-lg text-sm transition-all duration-200 {{ $subitem['active'] ? 'text-white font-bold bg-white/10' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                                <span class="sidebar-text">{{ $subitem['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3.5 p-3 rounded-xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group/item relative 
                                                                                     {{ $item['active'] ? 'bg-white/15 text-white scale-[1.05] shadow-[0_15px_30px_rgba(0,0,0,0.15)] z-20' : 'text-white/70 hover:bg-white/10 hover:text-white hover:scale-[1.05] hover:shadow-[0_10px_20px_rgba(0,0,0,0.1)]' }}
                                                                                     {{ isset($item['pop']) && $item['pop'] ? 'border border-transparent hover:border-white/20 hover:bg-white/10 hover:shadow-xl hover:shadow-black/5' : '' }}">

                    @if($item['active'])
                        <div
                            class="absolute inset-y-2 left-0 w-1.5 bg-white rounded-full shadow-[0_0_15px_rgba(255,255,255,0.6)]">
                        </div>
                    @endif

                    <i data-lucide="{{ $item['icon'] }}"
                        class="w-5 h-5 shrink-0 transition-all duration-500 {{ $item['active'] ? 'stroke-[2.5px] scale-110 text-white' : 'opacity-70 group-hover/item:opacity-100 group-hover/item:scale-125 group-hover/item:rotate-[5deg]' }}"></i>

                    <span
                        class="sidebar-text font-bold text-sm whitespace-nowrap tracking-tight transition-all duration-500">{{ $item['label'] }}</span>

                    @if(isset($item['unread']) && $item['unread'] > 0)
                        <span
                            class="ml-auto bg-white text-[#00ADC5] text-[10px] font-black px-2 py-0.5 rounded-lg shadow-sm sidebar-text animate-pulse">
                            {{ $item['unread'] }}
                        </span>
                    @endif

                    @if(isset($item['pop']) && $item['pop'])
                        <div
                            class="absolute right-3 opacity-0 group-hover/item:opacity-100 transition-all duration-500 translate-x-4 group-hover/item:translate-x-0">
                            <i data-lucide="sparkles" class="w-3 h-3 text-white animate-spin-slow"></i>
                        </div>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    <div class="p-6 border-t border-white/10 bg-black/5 shrink-0 relative z-10 transition-all duration-500">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 overflow-hidden">
                <div
                    class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 text-white flex items-center justify-center font-black shadow-sm shrink-0 transition-all duration-500 hover:shadow-md hover:bg-white/20 hover:rotate-6 active:scale-95 backdrop-blur-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="sidebar-text overflow-hidden transition-all duration-500">
                    <p class="text-[13px] font-black text-white truncate tracking-tight">{{ Auth::user()->name }}
                    </p>
                    <div class="flex items-center gap-1.5 mt-1">
                        <div
                            class="w-1.5 h-1.5 rounded-full bg-emerald-300 shadow-[0_0_8px_rgba(16,185,129,0.4)] animate-pulse">
                        </div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-white/60">
                            {{ Auth::user()->role }} Mode
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="sidebar-text p-2 text-white/50 hover:text-white hover:bg-white/10 rounded-lg transition-all shadow-sm border border-transparent hover:border-white/20 group/settings"
                title="Security Settings">
                <i data-lucide="settings"
                    class="w-4 h-4 group-hover/settings:rotate-90 transition-transform duration-500"></i>
            </a>
        </div>
    </div>
</aside>

<style>
    @keyframes pop-in {
        0% {
            opacity: 0;
            transform: scale(0.9) translateX(-10px);
        }

        100% {
            opacity: 1;
            transform: scale(1) translateX(0);
        }
    }

    .animate-pop-in {
        animation: pop-in 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    #sidebar.w-20 .sidebar-text {
        display: none !important;
    }

    #sidebar.w-20 .h-16 {
        justify-content: center;
        padding: 0;
    }

    /* Fixed logo center in collapsed mode */
    #sidebar.w-20 .h-16 .flex.items-center.gap-3 {
        gap: 0;
    }

    #sidebar.w-20 nav {
        padding-left: 0;
        padding-right: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #sidebar.w-20 nav a {
        justify-content: center;
        width: 48px;
        height: 48px;
        padding: 0;
        margin: 0 auto;
    }

    #sidebar.w-20 .p-6 {
        padding: 1.5rem 0;
        display: flex;
        justify-content: center;
    }

    #sidebar.w-20 .p-6 .flex.items-center.gap-4 {
        gap: 0;
        justify-content: center;
        width: 100%;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
</style>
