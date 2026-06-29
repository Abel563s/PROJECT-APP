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

        <!-- Role Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $roleConfig = [
                    'admin' => [
                        'label' => 'Root Admin',
                        'icon' => 'crown',
                        'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'desc' => 'Full system access and configuration'
                    ],
                    'manager' => [
                        'label' => 'Lead Manager',
                        'icon' => 'user-cog',
                        'color' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                        'desc' => 'Project oversight and team coordination'
                    ],
                    'user' => [
                        'label' => 'Standard User',
                        'icon' => 'user',
                        'color' => 'bg-slate-50 text-slate-600 border-slate-100',
                        'desc' => 'Basic operational access'
                    ],
                ];
            @endphp

            @foreach($roles as $role => $count)
                @php $config = $roleConfig[$role] ?? $roleConfig['user']; @endphp
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3 hover:shadow-lg hover:shadow-cyan-100 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl {{ $config['color'] }} flex items-center justify-center border">
                            <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5"></i>
                        </div>
                        <span class="text-2xl font-black text-slate-900">{{ $count }}</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900">{{ $config['label'] }}</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $config['desc'] }}</p>
                    </div>
                    <div class="pt-2 border-t border-slate-50">
                        <span class="inline-flex px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-wider {{ $config['color'] }} border">
                            {{ ucfirst($role) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#00ADC5] to-[#00ADC5]">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Identity</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Department</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Role</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00ADC5]/30">Status</th>
                            <th class="px-6 py-3 text-right border-b border-[#00ADC5]/30"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @php
                            $users = $users ?? \App\Models\User::latest()->take(10)->get();
                        @endphp
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
                                <td class="px-6 py-4">
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest bg-slate-100 px-2 py-0.5 rounded">{{ $user->employee_id ?? '---' }}</span>
                                </td>
                                <td class="px-6 py-4">
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
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $user->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                                        {{ $user->is_active ? 'Active' : 'Offline' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
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

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
            }
        });
    </script>
</x-app-layout>
