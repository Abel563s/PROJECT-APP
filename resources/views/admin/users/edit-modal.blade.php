<form action="{{ route('admin.users.update', $user) }}" method="POST" id="editIdentityModalForm" class="space-y-4">
    @csrf
    @method('PUT')

    <div data-edit-identity-errors class="hidden rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700" role="alert"></div>

    @php
        $canManageOrg = auth()->user()->isSuperadmin() || auth()->user()->isAdmin();
        $selectedBuId = auth()->user()->isAdmin()
            ? auth()->user()->business_unit_id
            : old('business_unit_id', $user->business_unit_id);
        $selectedSectorId = old('sector_id', $user->sector_id);
    @endphp

    <!-- Basic Details -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 rounded-xl border border-slate-100 bg-white p-4">
        <div class="space-y-1">
            <label for="editIdentityName" class="text-[9px] font-black uppercase tracking-widest text-slate-400">Full Name</label>
            <input id="editIdentityName" type="text" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5]/30 focus:bg-white focus:ring-2 focus:ring-cyan-100">
        </div>
        <div class="space-y-1">
            <label for="editIdentityEmail" class="text-[9px] font-black uppercase tracking-widest text-slate-400">Email</label>
            <input id="editIdentityEmail" type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5]/30 focus:bg-white focus:ring-2 focus:ring-cyan-100">
        </div>
        <div class="space-y-1">
            <label for="editIdentityRole" class="text-[9px] font-black uppercase tracking-widest text-slate-400">Role</label>
            <select id="editIdentityRole" name="role" required onchange="updateEditIdentityFields()"
                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5]/30 focus:bg-white focus:ring-2 focus:ring-cyan-100">
                <option value="user" @selected(old('role', $user->role) === 'user')>Project Coordinator</option>
                <option value="manager" @selected(old('role', $user->role) === 'manager')>Management</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                @if(auth()->user()->isSuperadmin())
                    <option value="superadmin" @selected(old('role', $user->role) === 'superadmin')>Superadmin</option>
                @endif
            </select>
        </div>
        <div class="space-y-1">
            <label for="editIdentityStatus" class="text-[9px] font-black uppercase tracking-widest text-slate-400">Status</label>
            <select id="editIdentityStatus" name="is_active" required
                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5]/30 focus:bg-white focus:ring-2 focus:ring-cyan-100">
                <option value="1" @selected((string) old('is_active', (int) $user->is_active) === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', (int) $user->is_active) === '0')>Inactive</option>
            </select>
        </div>
    </div>

    @if(auth()->user()->isSuperadmin())
        @include('admin.users.partials.risk-pool-permissions', [
            'riskPoolPermissionId' => 'editRiskPoolPermissions',
            'riskPoolPermissionState' => $user->risk_pool_permissions ?? [],
        ])
    @endif

    <!-- Organizational Assignment -->
    <div id="editOrgAssignment" @class([
        'bg-white border border-slate-100 rounded-xl p-4 space-y-3',
        'hidden' => !in_array(old('role', $user->role), ['user', 'manager', 'admin', 'superadmin'], true),
    ])>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">
                <i data-lucide="building-2" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Organizational Assignment</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Business Unit &amp; Sector</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="space-y-1">
                <label for="editIdentityBU" class="text-[9px] font-black uppercase tracking-widest text-slate-400">Business Unit</label>
                <select id="editIdentityBU" name="business_unit_id" onchange="updateEditSectorOptions()"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5]/30 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                    {{ auth()->user()->isAdmin() ? 'disabled' : '' }}>
                    <option value="">None</option>
                    @foreach($businessUnits as $bu)
                        <option value="{{ $bu->id }}" data-sectors='@json($bu->activeSectors)' @selected($selectedBuId == $bu->id)>{{ $bu->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label for="editIdentitySector" class="text-[9px] font-black uppercase tracking-widest text-slate-400">Sector</label>
                <select id="editIdentitySector" name="sector_id"
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5]/30 focus:bg-white focus:ring-2 focus:ring-cyan-100">
                    <option value="">None</option>
                    @foreach($sectors as $sector)
                        <option value="{{ $sector->id }}" @selected($selectedSectorId == $sector->id)>{{ $sector->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div id="editIdentityAssignedProjects" @class([
        'space-y-3 rounded-xl border border-cyan-100 bg-cyan-50/40 p-4',
        'hidden' => old('role', $user->role) !== 'user',
    ])>
        <div>
            <h4 class="text-sm font-black text-slate-900">Assigned Projects</h4>
            <p class="mt-1 text-[10px] font-medium text-slate-500">Choose project codes this coordinator can edit.</p>
        </div>
        <details id="editIdentityProjectPicker" class="group relative">
            <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:border-[#00ADC5]/40">
                <span data-project-selection-count>Select projects</span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-transform group-open:rotate-180"></i>
            </summary>
            <div class="mt-1 rounded-lg border border-slate-200 bg-white p-2 shadow-lg">
                <label class="sr-only" for="editIdentityProjectSearch">Search assigned projects</label>
                <input id="editIdentityProjectSearch" type="search" data-project-search
                    placeholder="Search projects..."
                    class="mb-2 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 placeholder:text-slate-400 focus:border-[#00ADC5] focus:outline-none focus:ring-2 focus:ring-cyan-100">
                <div class="max-h-40 overflow-y-auto">
                <?php $assignedProjectIds = old('assigned_project_ids', $user->assignedProjects->modelKeys()); ?>
                @foreach($projects as $project)
                    <label data-project-option class="flex cursor-pointer items-center gap-2 rounded-md px-2 py-1.5 text-xs font-semibold text-slate-700 hover:bg-cyan-50">
                        <input type="checkbox" name="assigned_project_ids[]" value="{{ $project->id }}"
                            @checked(in_array($project->id, $assignedProjectIds))
                            class="rounded border-slate-300 text-[#00ADC5] focus:ring-[#00ADC5]">
                        <span>{{ $project->project_code ?: $project->project_name }}</span>
                    </label>
                @endforeach
                <p data-project-empty class="hidden px-2 py-2 text-xs font-medium text-slate-400">No matching projects.</p>
                @if($projects->isEmpty())
                    <p class="px-2 py-2 text-xs font-medium text-slate-400">No projects available.</p>
                @endif
                </div>
            </div>
        </details>
        <x-input-error :messages="$errors->get('assigned_project_ids')" />
    </div>

    <div class="space-y-3 rounded-xl border border-amber-100 bg-amber-50/60 p-4">
        <div>
            <h4 class="text-sm font-black text-amber-900">Change Password <span class="font-medium text-amber-700">(optional)</span></h4>
            <p class="mt-1 text-[10px] font-medium text-amber-700">Leave both fields blank to keep the current password.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <input type="password" name="password" value="" autocomplete="new-password" placeholder="New password"
                class="w-full rounded-lg border border-amber-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-amber-100">
            <input type="password" name="password_confirmation" value="" autocomplete="new-password" placeholder="Confirm new password"
                class="w-full rounded-lg border border-amber-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-amber-100">
        </div>
        <x-input-error :messages="$errors->get('password')" />
    </div>

    <div class="flex justify-end pt-1">
        <button type="submit" form="editIdentityModalForm"
            class="rounded-lg bg-[#00ADC5] px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-white shadow-lg shadow-cyan-500/20 transition hover:bg-[#008C9E]">
            Save Identity
        </button>
    </div>
</form>
