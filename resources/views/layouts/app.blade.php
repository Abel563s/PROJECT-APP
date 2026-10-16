<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Attendance System'))</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }

        /* Premium Card Standard */
        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
            transition: all 0.25s ease;
        }

        .premium-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        /* Modern Inputs & Buttons */
        input,
        select,
        textarea {
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
        }

        .btn-premium {
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        /* Refined Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        /* Executive Shadows & Depth */
        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(241, 245, 249, 0.8);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            transform: translateY(-6px);
        }

        /* Typography Weights */
        .text-title {
            font-weight: 700;
        }

        .text-metric {
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
        }

        .text-label {
            font-weight: 500;
        }

        :is(body.management-readonly, body.project-coordinator-readonly) form[method="POST"]:not([data-logout-form]):not([action*="notifications"]) button,
        :is(body.management-readonly, body.project-coordinator-readonly) a[href*="/create"],
        :is(body.management-readonly, body.project-coordinator-readonly) a[href*="/edit"],
        :is(body.management-readonly, body.project-coordinator-readonly) button[onclick*="create" i],
        :is(body.management-readonly, body.project-coordinator-readonly) button[onclick*="edit" i],
        :is(body.management-readonly, body.project-coordinator-readonly) button[onclick*="delete" i],
        :is(body.management-readonly, body.project-coordinator-readonly) button[onclick*="toggleStatus" i] {
            display: none !important;
        }
    </style>
</head>

<body @class([
    'bg-[#f7fbfc] text-slate-900 antialiased h-full font-inter',
    'management-readonly' => auth()->check() && auth()->user()->isManager(),
    'project-coordinator-readonly' => auth()->check()
        && auth()->user()->isUser()
        && !request()->routeIs(
            'admin.projects.index',
            'admin.projects.edit',
            'admin.progress-updates.index',
            'admin.progress-updates.create',
            'admin.progress-updates.edit'
        ),
])>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            @include('partials.header')

            <!-- Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 px-8 py-8 custom-scrollbar">
                <!-- Session Alerts -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        class="mb-6 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center justify-between shadow-sm shadow-emerald-100/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                            </div>
                            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show"
                        class="mb-6 bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-center justify-between shadow-sm shadow-rose-100/50 text-rose-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            </div>
                            <p class="text-sm font-bold">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif

                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>

    <script>
        // Sidebar State Management
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        // Function to update icons and classes
        function setSidebarState(collapsed) {
            const icons = document.querySelectorAll('.sidebar-toggle-icon');
            const sidebarText = document.querySelectorAll('.sidebar-text');

            if (collapsed) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');

                sidebarText.forEach(el => el.classList.add('hidden'));

                // Update toggle direction
                icons.forEach(icon => {
                    if (icon.getAttribute('data-sidebar-icon') === 'chevron-left') icon.classList.add('hidden');
                    if (icon.getAttribute('data-sidebar-icon') === 'chevron-right') icon.classList.remove('hidden');
                });
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');

                sidebarText.forEach(el => el.classList.remove('hidden'));

                // Update toggle direction
                icons.forEach(icon => {
                    if (icon.getAttribute('data-sidebar-icon') === 'chevron-left') icon.classList.remove('hidden');
                    if (icon.getAttribute('data-sidebar-icon') === 'chevron-right') icon.classList.add('hidden');
                });
            }

            // Close all open submenus when sidebar is collapsed
            if (collapsed) {
                window.dispatchEvent(new CustomEvent('sidebar:collapsed'));
            }

            localStorage.setItem('sidebar-collapsed', collapsed);
        }

        // Initialize state
        const storedState = localStorage.getItem('sidebar-collapsed') === 'true';
        setSidebarState(storedState);

        // Sidebar uses event delegation since the toggle is inside it
        sidebar.addEventListener('click', (e) => {
            if (e.target.closest('#sidebarToggle')) {
                const isCollapsed = sidebar.classList.contains('w-20');
                setSidebarState(!isCollapsed);
            }
        });

        // Initialize Lucide Icons
        function initLucide() {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                try {
                    window.lucide.createIcons();
                } catch (error) {
                    console.error('Failed to render Lucide icons from the application bundle.', error);
                }
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLucide);
        } else {
            initLucide();
        }

        (function ensureProductionIcons() {
            let fallbackRequested = false;

            function renderIcons() {
                initLucide();

                if (!document.querySelector('[data-lucide]:not(svg)') || fallbackRequested) return;

                fallbackRequested = true;
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/lucide@1.27.0/dist/umd/lucide.min.js';
                script.onload = function() {
                    if (!window.lucide || typeof window.lucide.createIcons !== 'function') {
                        console.error('Lucide fallback loaded without an icon renderer.');
                        return;
                    }

                    const fallbackIcons = {
                        ...window.lucide.icons,
                        Blueprint: window.lucide.icons.PanelsTopLeft,
                        ShieldLock: window.lucide.icons.ShieldCheck,
                    };

                    window.lucide.createIcons({ icons: fallbackIcons });
                };
                script.onerror = function() {
                    console.error('Failed to load the Lucide icon fallback.');
                };
                document.head.appendChild(script);
            }

            const observer = new MutationObserver(function(mutations) {
                const addedIcon = mutations.some(function(mutation) {
                    return Array.from(mutation.addedNodes).some(function(node) {
                        return node instanceof Element
                            && (node.matches('[data-lucide]:not(svg)') || node.querySelector('[data-lucide]:not(svg)'));
                    });
                });

                if (addedIcon) renderIcons();
            });
            observer.observe(document.documentElement, { childList: true, subtree: true });

            if (document.readyState === 'complete') {
                window.setTimeout(renderIcons, 0);
            } else {
                window.addEventListener('load', renderIcons, { once: true });
            }
        })();
    </script>

    @stack('scripts')

</body>

</html>