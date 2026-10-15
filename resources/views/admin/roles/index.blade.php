<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">

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
                        'label' => 'Admin',
                        'icon' => 'crown',
                        'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'desc' => 'Full system control, including roles and permissions'
                    ],
                    'manager' => [
                        'label' => 'Management',
                        'icon' => 'user-cog',
                        'color' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                        'desc' => 'View-only access to all pages and records'
                    ],
                    'user' => [
                        'label' => 'Project Coordinator',
                        'icon' => 'user',
                        'color' => 'bg-slate-50 text-slate-600 border-slate-100',
                        'desc' => 'Edit assigned projects and their progress updates'
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
                            {{ $config['label'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <details class="group bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-5 marker:hidden">
                <div>
                    <h3 class="text-sm font-black text-slate-900">Access tiers at a glance</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">Compare permissions for Admin, Management, and Project Coordinators.</p>
                </div>
                <i data-lucide="chevron-down" class="h-4 w-4 shrink-0 text-slate-400 transition-transform group-open:rotate-180"></i>
            </summary>
            <div class="overflow-x-auto border-t border-slate-100">
                <table class="w-full min-w-[720px] text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-500 uppercase tracking-widest">Feature</th>
                            <th class="px-5 py-3 text-[9px] font-black text-emerald-700 uppercase tracking-widest">Admin</th>
                            <th class="px-5 py-3 text-[9px] font-black text-indigo-700 uppercase tracking-widest">Management</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-700 uppercase tracking-widest">Project Coordinator</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-600">
                        @foreach([
                            ['Access Control', 'Full access', 'No access', 'No access'],
                            ['User management', 'Full access', 'View only', 'No access'],
                            ['View all projects', 'Yes', 'Yes', 'Yes'],
                            ['Edit assigned projects', 'Yes', 'No', 'Yes'],
                            ['Edit unassigned projects', 'Yes', 'No', 'No'],
                            ['View all progress updates', 'Yes', 'Yes', 'Yes'],
                            ['Edit assigned project updates', 'Yes', 'No', 'Yes'],
                            ['Edit unassigned project updates', 'Yes', 'No', 'No'],
                            ['Create projects', 'Yes', 'No', 'No'],
                            ['Delete projects', 'Yes', 'No', 'No'],
                            ['Create progress updates', 'Yes', 'No', 'Assigned projects only'],
                            ['Delete progress updates', 'Yes', 'No', 'No'],
                            ['View dashboards and reports', 'Yes', 'Yes', 'As permitted'],
                        ] as [$feature, $adminAccess, $managementAccess, $coordinatorAccess])
                            <tr>
                                <td class="px-5 py-3 text-slate-800">{{ $feature }}</td>
                                <td class="px-5 py-3">{{ $adminAccess }}</td>
                                <td class="px-5 py-3">{{ $managementAccess }}</td>
                                <td class="px-5 py-3">{{ $coordinatorAccess }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>

        <!-- Users Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gradient-to-b from-[#00515f] to-black">
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30 text-center">#</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Identity</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Department</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Role</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Assigned Projects</th>
                            <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Status</th>
                            <th class="px-6 py-3 text-right border-b border-[#00515f]/30"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @php
                            $users = $users ?? collect();
                        @endphp
                        @forelse($users as $user)
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 odd:bg-white even:bg-slate-50/20">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-900 text-[10px] font-black tabular-nums">{{ $loop->iteration }}</span>
                                </td>
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
                                        {{ $user->role_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->isUser() && $user->assignedProjects->isNotEmpty())
                                        <div class="flex max-w-xs flex-wrap gap-1">
                                            @foreach($user->assignedProjects as $project)
                                                <span class="rounded-md bg-cyan-50 px-2 py-1 text-[9px] font-bold text-cyan-800">
                                                    {{ $project->project_code ?: $project->project_name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-[9px] font-medium text-slate-400">
                                            {{ $user->isUser() ? 'No projects assigned' : 'Not applicable' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $user->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                                        {{ $user->is_active ? 'Active' : 'Offline' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" onclick="openEditIdentityModal({{ $user->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
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
                                <td colspan="7" class="px-6 py-16 text-center">
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

        @if(method_exists($users, 'hasPages') && $users->hasPages())
            <div class="px-4 py-4 border-t border-slate-50 bg-slate-50/30 rounded-b-3xl">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <div id="editIdentityModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 py-8">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeEditIdentityModal()"></div>
            <div class="relative flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-slate-50 shadow-2xl">
                <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white px-6 py-3">
                    <h3 class="text-base font-black text-slate-900">Edit Identity</h3>
                    <button type="button" onclick="closeEditIdentityModal()" class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition hover:bg-slate-100" aria-label="Close edit identity">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div id="editIdentityModalContent" class="overflow-y-auto p-5 sm:p-6">
                    <div class="flex justify-center py-12">
                        <div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div>
                    </div>
                </div>
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
                    bindAssignedProjectVisibility('createUserRole', 'createAssignedProjects');
                    bindProjectPicker('createProjectPicker');
                    if (window.lucide) lucide.createIcons();
                });
        }

        function openEditIdentityModal(userId) {
            const content = document.getElementById('editIdentityModalContent');
            content.innerHTML = '<div class="flex justify-center py-12"><div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div></div>';
            document.getElementById('editIdentityModal').classList.remove('hidden');

            fetch(`/admin/users/${userId}/edit?partial=1`)
                .then(response => {
                    if (!response.ok) throw new Error('Unable to load this identity for editing.');
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                    bindAssignedProjectVisibility('editIdentityRole', 'editIdentityAssignedProjects');
                    bindProjectPicker('editIdentityProjectPicker');
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => {
                    content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-semibold text-rose-700">${error.message}</p>`;
                });
        }

        function bindAssignedProjectVisibility(roleId, assignmentsId) {
            const roleSelect = document.getElementById(roleId);
            const assignments = document.getElementById(assignmentsId);
            if (!roleSelect || !assignments) return;

            const updateVisibility = () => {
                assignments.classList.toggle('hidden', roleSelect.value !== 'user');
            };

            roleSelect.addEventListener('change', updateVisibility);
            updateVisibility();
        }

        function bindProjectPicker(pickerId) {
            const picker = document.getElementById(pickerId);
            if (!picker) return;

            const checkboxes = picker.querySelectorAll('input[name="assigned_project_ids[]"]');
            const countLabel = picker.querySelector('[data-project-selection-count]');
            const updateCount = () => {
                const selectedCount = [...checkboxes].filter(checkbox => checkbox.checked).length;
                countLabel.textContent = selectedCount
                    ? `${selectedCount} project${selectedCount === 1 ? '' : 's'} selected`
                    : 'Select projects';
            };

            checkboxes.forEach(checkbox => checkbox.addEventListener('change', updateCount));
            updateCount();
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function closeEditIdentityModal() {
            document.getElementById('editIdentityModal').classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeEditIdentityModal();
            }
        });
    </script>
</x-app-layout>
