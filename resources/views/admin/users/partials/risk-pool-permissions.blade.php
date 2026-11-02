<div id="{{ $riskPoolPermissionId }}" class="hidden rounded-xl border border-violet-100 bg-violet-50/40 p-4 space-y-3">
    <div class="flex items-center gap-2">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 text-violet-700">
            <i data-lucide="shield-check" class="h-4 w-4"></i>
        </div>
        <div>
            <h4 class="text-sm font-black text-slate-900">Risk Pool Permissions</h4>
            <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Manage shared risk pool records</p>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
        @foreach(['create' => 'Create risks', 'edit' => 'Edit risks', 'delete' => 'Delete risks'] as $action => $label)
            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:border-violet-300">
                <input type="hidden" name="risk_pool_permissions[{{ $action }}]" value="0">
                <input type="checkbox" name="risk_pool_permissions[{{ $action }}]" value="1"
                    @checked((bool) old('risk_pool_permissions.'.$action, data_get($riskPoolPermissionState ?? [], $action, false)))
                    class="rounded border-slate-300 text-violet-700 focus:ring-violet-500">
                <span>{{ $label }}</span>
            </label>
        @endforeach
    </div>
    <p class="text-[10px] font-medium text-slate-500">These permissions apply to the Risk Pool, EOT Pool, and Contract Clauses sections.</p>
</div>
