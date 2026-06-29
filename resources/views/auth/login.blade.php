<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Projects App') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .bg-grid {
            background-image: linear-gradient(rgba(0, 173, 197, 0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 173, 197, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 bg-grid">

    <div class="w-full max-w-md animate-fade-in">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#00ADC5] mb-4 shadow-xl shadow-[#00ADC5]/30">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain brightness-0 invert">
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Projects <span class="text-[#00ADC5]">RCF</span></h1>
            <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">Authenticate to continue</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 space-y-6">
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                    <p class="text-xs font-black text-emerald-700">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 rounded-xl p-3">
                    <div class="flex items-center gap-2 text-rose-600 mb-2">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Authentication Error</span>
                    </div>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs font-bold text-rose-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">
                        Corporate Email
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#00ADC5] transition-colors">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username" placeholder="name@company.com"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between ml-1">
                        <label for="password" class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                            Access Key
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-[9px] font-black text-[#00ADC5] uppercase tracking-widest hover:text-[#007A8A] transition-colors">
                                Lost Key?
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#00ADC5] transition-colors">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center ml-1">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4 h-4 text-[#00ADC5] border-2 border-slate-200 rounded-md focus:ring-[#00ADC5] focus:ring-offset-0 bg-white transition-all cursor-pointer">
                    <label for="remember_me"
                        class="ml-2.5 text-xs font-bold text-slate-500 cursor-pointer uppercase tracking-tight">
                        Stay authenticated
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-slate-900 border-2 border-slate-900 py-3 rounded-xl text-sm font-black text-white uppercase tracking-widest shadow-lg shadow-slate-900/20 hover:bg-[#00ADC5] hover:border-[#00ADC5] hover:shadow-cyan-200 transition-all active:scale-[0.98]">
                    Sign In
                </button>
            </form>

            <!-- Info -->
            <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                <div class="p-2 bg-cyan-50 rounded-lg shrink-0">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-[#00ADC5]"></i>
                </div>
                <p class="text-[9px] font-bold text-slate-400 leading-relaxed uppercase tracking-tight">
                    Use your official domain credentials to access your specific department dashboard.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-[10px] font-bold text-slate-400 mt-6 uppercase tracking-widest">
            {{ config('app.name', 'Projects App') }} v1.0
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
