<form action="{{ route('admin.users.store') }}" method="POST" id="createModalForm" class="space-y-4">
    @csrf
    <div id="createModalErrors" class="hidden rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700" role="alert"></div>

    <!-- Section 1: Identity -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="fingerprint" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Identity Details</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core credentials</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Full Name</label>
                <input type="text" name="name" required placeholder="Johnathan Doe"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Email</label>
                <input type="email" name="email" required placeholder="name@company.com"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <!-- Section 2: Access Tier -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Access Tier</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Permission level</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Role</label>
                <select name="role" id="createUserRole" onchange="updateCreateIdentityFields()" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="user">Project Coordinator</option>
                    <option value="manager">Management</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">State</label>
                <select name="is_active" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="1">Active / Operational</option>
                    <option value="0">Decommissioned / Static</option>
                </select>
            </div>
        </div>
    </div>

    <div id="createAssignedProjects" @class([
        'bg-white border border-slate-100 rounded-xl p-4 space-y-3',
        'hidden' => old('role', 'user') !== 'user',
    ])>
        <div>
            <h4 class="text-sm font-black text-slate-900">Assigned Projects</h4>
            <p class="text-[9px] font-bold text-slate-400">Choose project codes this coordinator can edit.</p>
        </div>
        <details id="createProjectPicker" class="group relative">
            <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:border-[#00ADC5]/40 hover:bg-white">
                <span data-project-selection-count>Select projects</span>
                <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-transform group-open:rotate-180"></i>
            </summary>
            <div class="mt-1 max-h-40 overflow-y-auto rounded-lg border border-slate-200 bg-white p-2 shadow-lg">
            @foreach($projects as $project)
                <label class="flex cursor-pointer items-center gap-2 rounded-md px-2 py-1.5 text-xs font-semibold text-slate-700 hover:bg-cyan-50">
                    <input type="checkbox" name="assigned_project_ids[]" value="{{ $project->id }}"
                        @checked(in_array($project->id, old('assigned_project_ids', [])))
                        class="rounded border-slate-300 text-[#00ADC5] focus:ring-[#00ADC5]">
                    <span>{{ $project->project_code ?: $project->project_name }}</span>
                </label>
            @endforeach
            @if($projects->isEmpty())
                <p class="px-2 py-2 text-xs font-medium text-slate-400">No projects available.</p>
            @endif
            </div>
        </details>
    </div>

    <!-- Section 3: Initial Password -->
    <div id="createDefaultPasswordNotice" @class([
        'rounded-xl border border-cyan-100 bg-cyan-50/70 p-4 text-xs font-semibold text-cyan-900',
        'hidden' => old('role', 'user') === 'admin',
    ])>
        This identity will use the temporary password <span class="font-black">pass1234</span> for first login and must change it before accessing the system.
    </div>

    <div id="createInitialPasswordFields" @class([
        'bg-white border border-slate-100 rounded-xl p-4 space-y-3',
        'hidden' => old('role', 'user') !== 'admin',
    ])>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="key-round" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Admin Password</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Set this Admin's sign-in password</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                <input type="password" name="password" @disabled(old('role', 'user') !== 'admin') @required(old('role', 'user') === 'admin') placeholder="Minimum 8 characters"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Confirm</label>
                <input type="password" name="password_confirmation" @disabled(old('role', 'user') !== 'admin') @required(old('role', 'user') === 'admin') placeholder="Re-enter key"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="createModalForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Deploy Identity
        </button>
    </div>
</form>
