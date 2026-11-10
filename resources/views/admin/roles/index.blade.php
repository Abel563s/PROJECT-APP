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
              <div class="flex items-center gap-2.5">
                  @if(auth()->user()->isSuperadmin())
                  <button onclick="openOrgManagementModal()"
                      class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                      <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                      <span class="relative flex items-center gap-1.5">
                          <i data-lucide="building-2" class="w-3 h-3"></i>
                          Manage Business Units &amp; Sectors
                      </span>
                  </button>
                  @endif
                  <button onclick="openCreateModal()"
                     class="group/btn relative px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-slate-900/20">
                     <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-blue-500 translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                     <span class="relative flex items-center gap-1.5">
                         <i data-lucide="plus" class="w-3 h-3"></i>
                         New Identity
                     </span>
                 </button>
                 <button onclick="openCoordinatorDirectoryModal()"
                    class="group/btn relative px-5 py-2.5 bg-[#00515f] text-white rounded-xl font-black text-[9px] uppercase tracking-widest overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-lg shadow-[#00515f]/20">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#00ADC5] to-[#00515f] translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-500"></div>
                    <span class="relative flex items-center gap-1.5">
                        <i data-lucide="users" class="w-3 h-3"></i>
                        Coordinator Directory
                    </span>
                </button>
             </div>
         </div>

         <!-- Role Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @php
                $roleConfig = [
                    'superadmin' => [
                        'label' => 'Superadmin',
                        'icon' => 'shield-check',
                        'color' => 'bg-purple-50 text-purple-600 border-purple-100',
                    ],
                    'admin' => [
                        'label' => 'Admin',
                        'icon' => 'crown',
                        'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    ],
                    'manager' => [
                        'label' => 'Management',
                        'icon' => 'user-cog',
                        'color' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    ],
                    'user' => [
                        'label' => 'Project Coordinator',
                        'icon' => 'user',
                        'color' => 'bg-slate-50 text-slate-600 border-slate-100',
                    ],
                ];
            @endphp

            @foreach($roles as $role => $count)
                @php $config = $roleConfig[$role] ?? [
                    'label' => ucfirst(str_replace('_', ' ', $role)),
                    'icon' => 'users',
                    'color' => 'bg-slate-50 text-slate-600 border-slate-100',
                ]; @endphp
                <div class="flex items-center gap-2.5 rounded-xl border border-slate-100 bg-white px-3 py-2 shadow-sm transition-all duration-300 hover:shadow-md hover:shadow-cyan-100">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border {{ $config['color'] }}">
                        <i data-lucide="{{ $config['icon'] }}" class="h-3.5 w-3.5"></i>
                    </div>
                    <h3 class="min-w-0 flex-1 text-xs font-bold text-slate-700">{{ $config['label'] }}</h3>
                    <span class="text-lg font-black tabular-nums text-slate-900">{{ $count }}</span>
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

        <section aria-label="Search and filter identities" class="bg-white rounded-2xl border border-slate-100 p-3 shadow-sm">
            @php
                $identityAdvancedFiltersActive = request()->anyFilled(['business_unit_id', 'sector_id', 'project_id']);
            @endphp
            <form method="GET" action="{{ route('admin.roles.index') }}" class="admin-filter-grid gap-2.5">
                <div class="min-w-0">
                    <label for="identitySearch" class="mb-1 block text-[9px] font-bold uppercase tracking-wider text-slate-500">Search</label>
                    <div class="relative">
                        <i data-lucide="search" aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input id="identitySearch" type="search" name="search" value="{{ request('search') }}"
                            placeholder="Name, email, or employee ID"
                            class="w-full rounded-lg border border-slate-200 bg-white py-1.5 pl-9 pr-2 text-xs text-slate-900 placeholder:text-slate-400 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                    </div>
                </div>
                <div>
                    <label for="identityRoleFilter" class="mb-1 block text-[9px] font-bold uppercase tracking-wider text-slate-500">Role</label>
                    <select id="identityRoleFilter" name="role"
                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                    <option value="">All roles</option>
                         <option value="superadmin" @selected(request('role') === 'superadmin')>Superadmin</option>
                         <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                         <option value="manager" @selected(request('role') === 'manager')>Management</option>
                         <option value="user" @selected(request('role') === 'user')>Project Coordinator</option>
                    </select>
                </div>
                <div>
                    <label for="identityStatusFilter" class="mb-1 block text-[9px] font-bold uppercase tracking-wider text-slate-500">Status</label>
                    <select id="identityStatusFilter" name="status"
                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Offline</option>
                    </select>
                </div>
                <div>
                    <label for="identityProjectFilter" class="mb-1 block text-[9px] font-bold uppercase tracking-wider text-slate-500">Assigned Projects</label>
                    <select id="identityProjectFilter" name="project_id"
                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                        <option value="">All projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>
                                {{ $project->project_code ?: $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" aria-controls="identityAdvancedFilters" aria-expanded="{{ $identityAdvancedFiltersActive ? 'true' : 'false' }}" onclick="toggleFilterPanel(this, 'identityAdvancedFilters')"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-800">
                        <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5"></i>More filters
                        <i data-lucide="chevron-down" class="filter-panel-chevron h-3.5 w-3.5 transition-transform {{ $identityAdvancedFiltersActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                        <i data-lucide="filter" aria-hidden="true" class="h-3.5 w-3.5"></i>Apply
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">Clear</a>
                </div>
                <div id="identityAdvancedFilters" class="admin-filter-advanced-panel {{ $identityAdvancedFiltersActive ? '' : 'hidden' }} rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                    <div class="admin-filter-advanced-grid gap-3">
                        <div>
                            <label for="identityBusinessUnitFilter" class="mb-1 block text-[9px] font-bold uppercase tracking-wider text-slate-500">Business Unit</label>
                            <select id="identityBusinessUnitFilter" name="business_unit_id" onchange="this.form.querySelector('[name=sector_id]').value=''; this.form.submit()"
                                class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                                <option value="">All units</option>
                                @foreach($businessUnits as $businessUnit)
                                    <option value="{{ $businessUnit->id }}" @selected(\App\Support\ProjectOrganizationFilters::effectiveBusinessUnitId(request()) == $businessUnit->id)>{{ $businessUnit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="identitySectorFilter" class="mb-1 block text-[9px] font-bold uppercase tracking-wider text-slate-500">Organizational Sector</label>
                            <select id="identitySectorFilter" name="sector_id" class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
                                <option value="">All sectors</option>
                                @foreach($sectors as $sector)
                                    <option value="{{ $sector->id }}" @selected(request('sector_id') == $sector->id)>{{ $sector->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            @include('admin.partials.filter-panel-toggle-script')
        </section>

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
                             <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Business Unit</th>
                             <th class="px-6 py-3 text-[9px] font-black text-white uppercase tracking-widest border-b border-[#00515f]/30">Sector</th>
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
                                            'superadmin' => 'bg-purple-100/50 text-purple-600 border-purple-200',
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
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest bg-slate-100 px-2 py-0.5 rounded">
                                        @if($user->businessUnit)
                                            {{ $user->businessUnit->name }}
                                        @else
                                            <span class="text-slate-400">---</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest bg-slate-100 px-2 py-0.5 rounded">
                                        @if($user->sector)
                                            {{ $user->sector->name }}
                                        @else
                                            <span class="text-slate-400">---</span>
                                        @endif
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
                                        @if(auth()->user()->isSuperadmin() || (auth()->user()->business_unit_id !== null && $user->business_unit_id !== null && (int) $user->business_unit_id === (int) auth()->user()->business_unit_id))
                                        <button type="button" onclick="openEditIdentityModal({{ $user->id }})"
                                            class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200"
                                            title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @endif
                                        @if($user->id !== auth()->id() && (auth()->user()->isSuperadmin() || (auth()->user()->business_unit_id !== null && $user->business_unit_id !== null && (int) $user->business_unit_id === (int) auth()->user()->business_unit_id)))
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
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <i data-lucide="user-x" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="text-base font-black text-slate-900">No Identities Found</h3>
                                        <p class="text-[9px] uppercase font-black tracking-widest text-slate-400">
                                            {{ request()->hasAny(['search', 'role', 'status']) ? 'Try changing or clearing your filters.' : 'Initialize a new identity node to begin.' }}
                                        </p>
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
                <div class="sticky top-0 bg-gradient-to-r from-[#00ADC5] to-[#00515f] border-b border-[#00515f]/30 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-white">New Identity</h3>
                    <button onclick="closeCreateModal()" class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center transition hover:bg-white/30">
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

    <!-- Coordinator Directory Modal -->
    <div id="coordinatorDirectoryModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeCoordinatorDirectoryModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col">
                <div class="sticky top-0 bg-gradient-to-r from-[#00ADC5] to-[#00515f] border-b border-[#00515f]/30 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-white">Project Coordinator Directory</h3>
                    <button type="button" onclick="closeCoordinatorDirectoryModal()" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 text-white transition hover:bg-white/30" aria-label="Close coordinator directory">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div id="coordinatorDirectoryModalContent" class="overflow-y-auto p-6">
                    <div class="flex justify-center py-12">
                        <div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Org Management Modal -->
    <div id="orgManagementModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeOrgManagementModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-3 flex items-center justify-between rounded-t-2xl z-10">
                    <h3 class="text-base font-black text-slate-900">Business Units & Sectors</h3>
                    <button type="button" onclick="closeOrgManagementModal()" class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="orgManagementModalContent" class="p-0">
                    <div class="flex items-center justify-center py-12">
                        <div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openOrgManagementModal() {
            const content = document.getElementById('orgManagementModalContent');
            content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="w-8 h-8 border-2 border-[#00ADC5] border-t-transparent rounded-full animate-spin"></div></div>';
            document.getElementById('orgManagementModal').classList.remove('hidden');

            fetch(`/admin/business-units/manage?partial=1`)
                .then(response => {
                    if (!response.ok) throw new Error('Unable to load organizational management.');
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => {
                    content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-semibold text-rose-700">${error.message}</p>`;
                });
        }

        function closeOrgManagementModal() {
            document.getElementById('orgManagementModal').classList.add('hidden');
        }

        function openCoordinatorDirectoryModal() {
            const content = document.getElementById('coordinatorDirectoryModalContent');
            content.innerHTML = '<div class="flex justify-center py-12"><div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div></div>';
            document.getElementById('coordinatorDirectoryModal').classList.remove('hidden');

            fetch(`/admin/roles/coordinator-directory?partial=1`)
                .then(response => {
                    if (!response.ok) throw new Error('Unable to load coordinator directory.');
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => {
                    content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-semibold text-rose-700">${error.message}</p>`;
                });
        }

        function closeCoordinatorDirectoryModal() {
            document.getElementById('coordinatorDirectoryModal').classList.add('hidden');
        }

        function openCreateModal() {
            fetch(`/admin/users/create?partial=1`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    document.getElementById('createModal').classList.remove('hidden');
                    bindAssignedProjectVisibility('createUserRole', 'createAssignedProjects');
                    updateCreateIdentityFields();
                    updateCreateSectorOptions();
                    bindProjectPicker('createProjectPicker');
                    bindCreateIdentityForm();
                    if (window.lucide) lucide.createIcons();
                });
        }

        function bindCreateIdentityForm() {
            const form = document.getElementById('createModalForm');
            if (!form) return;

            form.addEventListener('submit', async event => {
                event.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                const errors = document.getElementById('createModalErrors');
                errors.classList.add('hidden');
                errors.textContent = '';
                submitButton.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    const result = await response.json();

                    if (!response.ok) {
                        const messages = Object.values(result.errors || {}).flat();
                        throw new Error(messages.join(' ') || result.message || 'Unable to create this identity.');
                    }

                    window.location.reload();
                } catch (error) {
                    errors.textContent = error.message;
                    errors.classList.remove('hidden');
                } finally {
                    submitButton.disabled = false;
                }
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
                    bindEditIdentityForm();
                    updateEditIdentityFields();
                    updateEditSectorOptions();
                    bindProjectPicker('editIdentityProjectPicker');
                    if (window.lucide) lucide.createIcons();
                })
                .catch(error => {
                    content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-semibold text-rose-700">${error.message}</p>`;
                });
        }

        function bindEditIdentityForm() {
            const form = document.getElementById('editIdentityModalForm');
            if (!form) return;

            form.addEventListener('submit', async event => {
                event.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                const errors = form.querySelector('[data-edit-identity-errors]');
                errors.classList.add('hidden');
                errors.textContent = '';
                submitButton.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    const result = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        const messages = Object.values(result.errors || {}).flat();
                        throw new Error(messages.join(' ') || result.message || 'Unable to save this identity.');
                    }

                    window.location.reload();
                } catch (error) {
                    errors.textContent = error.message;
                    errors.classList.remove('hidden');
                } finally {
                    submitButton.disabled = false;
                }
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

        function updateCreateIdentityFields() {
            const roleSelect = document.getElementById('createUserRole');
            const assignments = document.getElementById('createAssignedProjects');
            const orgAssignment = document.getElementById('createOrgAssignment');
            const passwordFields = document.getElementById('createInitialPasswordFields');
            const defaultPasswordNotice = document.getElementById('createDefaultPasswordNotice');
            const riskPoolPermissions = document.getElementById('createRiskPoolPermissions');
            if (!roleSelect || !assignments || !passwordFields || !defaultPasswordNotice) return;

            const selectedRole = roleSelect.value;
            const isAdmin = selectedRole === 'admin';
            const showOrg = ['user', 'manager', 'admin', 'superadmin'].includes(selectedRole);
            assignments.classList.toggle('hidden', selectedRole !== 'user');
            orgAssignment?.classList.toggle('hidden', !showOrg);
            riskPoolPermissions?.classList.toggle('hidden', !isAdmin);
            riskPoolPermissions?.querySelectorAll('input').forEach(input => { input.disabled = !isAdmin; });
            passwordFields.classList.toggle('hidden', !isAdmin);
            defaultPasswordNotice.classList.toggle('hidden', isAdmin);
            passwordFields.querySelectorAll('input').forEach(input => {
                input.disabled = !isAdmin;
                input.required = isAdmin;
            });
            bindAssignedProjectVisibility('createUserRole', 'createAssignedProjects');
            orgAssignment?.classList.toggle('hidden', !showOrg);
        }

        function updateCreateSectorOptions() {
            const buSelect = document.getElementById('createBusinessUnit');
            const sectorSelect = document.getElementById('createSector');
            if (!buSelect || !sectorSelect) return;

            sectorSelect.innerHTML = '<option value="">Select a Sector</option>';

            const selectedOption = buSelect.options[buSelect.selectedIndex];
            const sectors = selectedOption?.dataset?.sectors ? JSON.parse(selectedOption.dataset.sectors) : [];

            sectors.forEach(sector => {
                const option = document.createElement('option');
                option.value = sector.id;
                option.textContent = sector.name;
                sectorSelect.appendChild(option);
            });

            sectorSelect.disabled = buSelect.value === '';
        }

        function updateEditIdentityFields() {
            const roleSelect = document.getElementById('editIdentityRole');
            const orgAssignment = document.getElementById('editOrgAssignment');
            const assignments = document.getElementById('editIdentityAssignedProjects');
            const riskPoolPermissions = document.getElementById('editRiskPoolPermissions');
            if (!roleSelect) return;

            const selectedRole = roleSelect.value;
            const showOrg = ['user', 'manager', 'admin', 'superadmin'].includes(selectedRole);
            orgAssignment?.classList.toggle('hidden', !showOrg);
            assignments.classList.toggle('hidden', selectedRole !== 'user');
            const isAdmin = selectedRole === 'admin';
            riskPoolPermissions?.classList.toggle('hidden', !isAdmin);
            riskPoolPermissions?.querySelectorAll('input').forEach(input => { input.disabled = !isAdmin; });
        }

        function updateEditSectorOptions() {
            const buSelect = document.getElementById('editIdentityBU');
            const sectorSelect = document.getElementById('editIdentitySector');
            if (!sectorSelect) return;

            const previouslySelectedSectorId = sectorSelect.value;

            sectorSelect.innerHTML = '<option value="">None</option>';

            if (!buSelect || buSelect.value === '') {
                sectorSelect.disabled = true;
                return;
            }

            sectorSelect.disabled = false;
            sectorSelect.innerHTML = '<option value="">None</option>';

            const selectedOption = buSelect.options[buSelect.selectedIndex];
            const sectors = selectedOption?.dataset?.sectors ? JSON.parse(selectedOption.dataset.sectors) : [];

            sectors.forEach(sector => {
                const option = document.createElement('option');
                option.value = sector.id;
                option.textContent = sector.name;
                option.selected = String(sector.id) === previouslySelectedSectorId;
                sectorSelect.appendChild(option);
            });
        }

        function bindProjectPicker(pickerId) {
            const picker = document.getElementById(pickerId);
            if (!picker) return;

            const checkboxes = picker.querySelectorAll('input[name="assigned_project_ids[]"]');
            const options = [...picker.querySelectorAll('[data-project-option]')];
            const countLabel = picker.querySelector('[data-project-selection-count]');
            const searchInput = picker.querySelector('[data-project-search]');
            const emptyMessage = picker.querySelector('[data-project-empty]');
            const updateCount = () => {
                const selectedCount = [...checkboxes].filter(checkbox => checkbox.checked).length;
                countLabel.textContent = selectedCount
                    ? `${selectedCount} project${selectedCount === 1 ? '' : 's'} selected`
                    : 'Select projects';
            };

            searchInput?.addEventListener('input', () => {
                const search = searchInput.value.trim().toLocaleLowerCase();
                let visibleCount = 0;

                options.forEach(option => {
                    const matches = option.textContent.toLocaleLowerCase().includes(search);
                    option.classList.toggle('hidden', !matches);
                    if (matches) visibleCount++;
                });

                emptyMessage?.classList.toggle('hidden', options.length === 0 || visibleCount > 0);
            });

            checkboxes.forEach(checkbox => checkbox.addEventListener('change', updateCount));
            updateCount();
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function closeEditIdentityModal() {
            document.getElementById('editIdentityModal').classList.add('hidden');
            document.getElementById('orgManagementModal').classList.add('hidden');
        }

        const sectorApiBase = '/admin/sectors';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        async function showOrgError(message) {
            const el = document.getElementById('orgManagementErrors');
            if (!el) return;
            el.textContent = message;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 5000);
        }

        async function saveBusinessUnit(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const buId = formData.get('id');
            const url = buId ? `/admin/business-units/${buId}` : '/admin/business-units';
            const method = buId ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    body: formData,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Unable to save Business Unit.');
                closeOrgForm('buForm');
                closeOrgManagementModal();
                window.location.reload();
            } catch (error) {
                showOrgError(error.message);
            }
        }

        async function saveSector(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const sectorId = formData.get('id');
            const url = sectorId ? `${sectorApiBase}/${sectorId}` : `${sectorApiBase}`;
            const method = sectorId ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    body: formData,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Unable to save Sector.');
                closeOrgForm('sectorForm');
                closeOrgManagementModal();
                window.location.reload();
            } catch (error) {
                showOrgError(error.message);
            }
        }

        function openBuForm(buId = null) {
            const formContainer = document.getElementById('buFormContainer');
            if (!formContainer) return;
            let formHtml = '';
            if (buId) {
                fetch(`/admin/business-units/${buId}`, { headers: { 'X-CSRF-TOKEN': csrfToken } })
                    .then(r => r.json())
                    .then(bu => {
                        formHtml = `
                            <form id="buForm" onsubmit="saveBusinessUnit(event)" class="mb-4 rounded-xl border border-slate-100 bg-slate-50 p-4 space-y-4">
                                <h4 class="text-sm font-black text-slate-900">Edit Business Unit</h4>
                                <input type="hidden" name="id" value="${bu.id}">
                                <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Name *</label>
                                    <input type="text" name="name" value="${bu.name}" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"></label></div>
                                <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Code</label>
                                    <input type="text" name="code" value="${bu.code || ''}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"></label></div>
                                <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Description</label>
                                    <textarea name="description" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">${bu.description || ''}</textarea></div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" ${bu.is_active ? 'checked' : ''} class="h-4 w-4">
                                    <label class="text-[9px] font-black uppercase text-slate-500">Active</label>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="closeOrgForm('buForm')" class="px-3 py-1 text-[9px] font-black text-slate-500">Cancel</button>
                                    <button type="submit" class="px-3 py-1 text-[9px] font-black text-white bg-[#00ADC5] rounded-lg">Save</button>
                                </div>
                            </form>
                        `;
                        formContainer.innerHTML = formHtml;
                    });
                return;
            }
            formHtml = `
                <form id="buForm" onsubmit="saveBusinessUnit(event)" class="mb-4 rounded-xl border border-slate-100 bg-slate-50 p-4 space-y-4">
                    <h4 class="text-sm font-black text-slate-900">New Business Unit</h4>
                    <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Name *</label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"></label></div>
                    <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Code</label>
                        <input type="text" name="code" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"></label></div>
                    <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"></textarea></div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">
                        <label class="text-[9px] font-black uppercase text-slate-500">Active</label>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeOrgForm('buForm')" class="px-3 py-1 text-[9px] font-black text-slate-500">Cancel</button>
                        <button type="submit" class="px-3 py-1 text-[9px] font-black text-white bg-[#00ADC5] rounded-lg">Create</button>
                    </div>
                </form>
            `;
            formContainer.innerHTML = formHtml;
        }

        function openSectorForm(buId, sectorId = null) {
            const formContainer = document.getElementById('sectorFormContainer');
            if (!formContainer) return;
            let formHtml = '';
            if (sectorId) {
                fetch(`${sectorApiBase}/${sectorId}`, { headers: { 'X-CSRF-TOKEN': csrfToken } })
                    .then(r => r.json())
                    .then(sector => {
                        formHtml = `
                            <form id="sectorForm" onsubmit="saveSector(event)" class="mb-4 rounded-xl border border-slate-100 bg-slate-50 p-4 space-y-4">
                                <h4 class="text-sm font-black text-slate-900">Edit Sector</h4>
                                <input type="hidden" name="id" value="${sector.id}">
                                <input type="hidden" name="business_unit_id" value="${buId}">
                                <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Name *</label>
                                    <input type="text" name="name" value="${sector.name}" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"></label></div>
                                <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Description</label>
                                    <textarea name="description" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">${sector.description || ''}</textarea></div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" ${sector.is_active ? 'checked' : ''} class="h-4 w-4">
                                    <label class="text-[9px] font-black uppercase text-slate-500">Active</label>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="closeOrgForm('sectorForm')" class="px-3 py-1 text-[9px] font-black text-slate-500">Cancel</button>
                                    <button type="submit" class="px-3 py-1 text-[9px] font-black text-white bg-[#00ADC5] rounded-lg">Save</button>
                                </div>
                            </form>
                        `;
                        formContainer.innerHTML = formHtml;
                    });
                return;
            }
            formHtml = `
                <form id="sectorForm" onsubmit="saveSector(event)" class="mb-4 rounded-xl border border-slate-100 bg-slate-50 p-4 space-y-4">
                    <h4 class="text-sm font-black text-slate-900">New Sector</h4>
                    <input type="hidden" name="business_unit_id" value="${buId}">
                    <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Name *</label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"></label></div>
                    <div><label class="block text-[9px] font-black uppercase tracking-wider text-slate-500 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm"></textarea></div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4">
                        <label class="text-[9px] font-black uppercase text-slate-500">Active</label>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeOrgForm('sectorForm')" class="px-3 py-1 text-[9px] font-black text-slate-500">Cancel</button>
                        <button type="submit" class="px-3 py-1 text-[9px] font-black text-white bg-[#00ADC5] rounded-lg">Create</button>
                    </div>
                </form>
            `;
            formContainer.innerHTML = formHtml;
        }

        function closeOrgForm(formId) {
            const container = document.getElementById(formId + 'Container');
            if (container) container.innerHTML = '';
        }

        async function editBusinessUnit(buId) {
            closeOrgForm('sectorForm');
            openBuForm(buId);
        }

        async function editSector(sectorId) {
            const response = await fetch(`${sectorApiBase}/${sectorId}`, { headers: { 'X-CSRF-TOKEN': csrfToken } }).then(r => r.json());
            closeOrgForm('buForm');
            openSectorForm(response.business_unit_id, sectorId);
        }

        async function deleteBusinessUnit(buId) {
            if (!confirm('Are you sure?')) return;
            try {
                const response = await fetch(`/admin/business-units/${buId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Unable to delete.');
                window.location.reload();
            } catch (error) {
                showOrgError(error.message);
            }
        }

        async function deleteSector(sectorId) {
            if (!confirm('Are you sure?')) return;
            try {
                const response = await fetch(`${sectorApiBase}/${sectorId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Unable to delete.');
                window.location.reload();
            } catch (error) {
                showOrgError(error.message);
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeEditIdentityModal();
                const orgModal = document.getElementById('orgManagementModal');
                if (orgModal) orgModal.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
