<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Access <span class="font-black text-slate-900">Control</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">Identity Matrix & Node Permissions</p>
                </div>
            </div>
            <button onclick="openCreateModal()"
                class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                <span class="relative flex items-center gap-1.5">
                    <i data-lucide="plus" class="w-3 h-3"></i>
                    New Identity
                </span>
            </button>
        </div>

        <!-- Search & Filter -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="space-y-4">
            <div class="bg-white p-4 rounded-2xl shadow-lg shadow-slate-200/30 border border-slate-50 flex flex-wrap items-center gap-4">
                <div class="flex-1 relative min-w-[280px] group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#00ADC5] transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or email..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-xs font-bold text-slate-700 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                </div>

                <div class="flex items-center gap-3">
                    <div class="relative group/select">
                        <select name="role" onchange="this.form.submit()"
                            class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                            <option value="">All Tiers</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Root (Admin)</option>
                            <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Lead (Manager)</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Standard (User)</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                    </div>

                    <div class="relative group/select">
                        <select name="status" onchange="this.form.submit()"
                            class="appearance-none pl-6 pr-10 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-[9px] font-black uppercase tracking-widest text-slate-500 cursor-pointer hover:bg-slate-100 transition-all focus:ring-2 focus:ring-slate-100 focus:bg-white min-w-[150px]">
                            <option value="">All States</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Operational</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Offline</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none transition-transform group-hover/select:translate-y-[-40%]"></i>
                    </div>

                    @if(request()->anyFilled(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center justify-center w-10 h-10 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all active:scale-90 shadow-sm"
                            title="Clear Filters">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Data Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#00ADC5] to-[#00ADC5]">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Identity</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30 text-center">Department</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30 text-center">Role</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30 text-center">Status</th>
                            <th class="px-6 py-3 text-right border-b border-[#00ADC5]/30"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @forelse($users as $user)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-white shadow border border-slate-100 flex items-center justify-center transition-all duration-300 group-hover:rotate-[10deg] group-hover:scale-110 group-hover:border-[#00ADC5]">
                                            <span class="font-black text-[8px] text-slate-300 group-hover:text-[#00ADC5] tracking-tighter">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900 leading-tight group-hover:text-[#00ADC5] transition-colors">{{ $user->name }}</p>
                                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest bg-slate-100 px-2 py-0.5 rounded">{{ $user->employee_id ?? '---' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $roleStyles = match ($user->role) {
                                            'admin' => 'bg-emerald-100/50 text-emerald-600 border-emerald-200',
                                            'manager' => 'bg-indigo-100/50 text-indigo-600 border-indigo-200',
                                            default => 'bg-slate-100/50 text-slate-500 border-slate-200',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $roleStyles }} border">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $user->name }}">
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                        <input type="hidden" name="role" value="{{ $user->role }}">
                                        <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all {{ $user->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-rose-50 text-rose-600 hover:bg-rose-100' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                                            {{ $user->is_active ? 'Active' : 'Offline' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button onclick="openPwModal({{ $user->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Reset Password">
                                            <i data-lucide="key-round" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#00ADC5] hover:text-white hover:border-[#00ADC5] transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="user-cog" class="w-3.5 h-3.5"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Initiate user node decommission?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-200"
                                                    title="Delete">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="user-x" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="text-base font-black text-slate-900">No Identities Found</h3>
                                        <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">Initialize a new identity node to begin.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Identity Modal -->
    <div id="createModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeCreateModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">New Identity</h3>
                    <button onclick="closeCreateModal()" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="createModalContent" class="p-6">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Update Modal -->
    <div id="pwModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closePwModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">Reset Security Key</h3>
                    <button onclick="closePwModal()" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="pwModalContent" class="p-6">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            fetch(`/admin/users/create?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    lucide.createIcons();
                });
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openPwModal(userId) {
            fetch(`/admin/users/${userId}/password-modal?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('pwModalContent').innerHTML = html;
                    document.getElementById('pwModal').classList.remove('hidden');
                    lucide.createIcons();
                });
        }

        function closePwModal() {
            document.getElementById('pwModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closePwModal();
            }
        });
    </script>
</x-app-layout>
