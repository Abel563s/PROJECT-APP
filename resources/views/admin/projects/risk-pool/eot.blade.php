<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        @include('admin.projects.risk-pool.partials._tabs', [
            'activeTab' => 'eot',
            'stats' => ['total' => $total],
            'canCreateEot' => $canCreateEot,
        ])

        @php($eotFiltersActive = request()->anyFilled(['search', 'category_id']))
        <form id="eotFilters" action="{{ route('admin.projects.risk-pool.eot') }}" method="GET" class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm">
            <div class="admin-filter-grid gap-3">
                <label class="min-w-0 space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search EOTs</span>
                    <span class="relative block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="EOT ID, reason, or base of risk"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                    </span>
                </label>
                <label class="min-w-0 space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Base of Risk</span>
                    <select name="category_id" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
                        <option value="">All Bases of Risk</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex items-end gap-2">
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white transition hover:bg-[#00515f]"><i data-lucide="search" class="h-3.5 w-3.5"></i>Search</button>
                    @if($eotFiltersActive)
                        <a href="{{ route('admin.projects.risk-pool.eot') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition hover:bg-rose-500 hover:text-white"><i data-lucide="x" class="h-3.5 w-3.5"></i>Clear</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left">
                    <thead>
                        <tr class="border-b border-[#00515f]/30 bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black uppercase tracking-widest text-white">
                            <th class="px-4 py-3">EOT ID</th>
                            <th class="px-4 py-3">Base of Risk</th>
                            <th class="px-4 py-3">Detail Reason of EOT</th>
                            <th class="px-4 py-3">Created</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($entries as $entry)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-4 py-3"><span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700 shadow-sm tabular-nums">{{ $entry->eot_id }}</span></td>
                                <td class="px-4 py-3"><span class="inline-flex rounded-lg border border-cyan-100 bg-cyan-50 px-2.5 py-1 text-[9px] font-black text-cyan-800">{{ $entry->baseOfRisk?->name ?? 'Category removed' }}</span></td>
                                <td class="max-w-2xl px-4 py-3"><p class="text-xs font-semibold leading-5 text-slate-700">{{ \Illuminate\Support\Str::limit($entry->detail_reason, 190) }}</p></td>
                                <td class="whitespace-nowrap px-4 py-3 text-xs font-semibold text-slate-500">{{ $entry->created_at?->format('d-M-y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" onclick="openEotViewModal({{ $entry->id }})" class="rounded-md border border-slate-200 bg-white p-1.5 text-slate-400 transition hover:border-sky-500 hover:text-sky-500" title="View"><i data-lucide="eye" class="h-3.5 w-3.5"></i></button>
                                        @if($canEditEot)
                                            <button type="button" onclick="openEditEotModal({{ $entry->id }})" class="rounded-md border border-slate-200 bg-white p-1.5 text-slate-400 transition hover:border-amber-500 hover:text-amber-500" title="Edit"><i data-lucide="pencil" class="h-3.5 w-3.5"></i></button>
                                        @endif
                                        @if($canDeleteEot)
                                            <button type="button" onclick="deleteEot({{ $entry->id }})" class="rounded-md border border-slate-200 bg-white p-1.5 text-slate-400 transition hover:border-rose-500 hover:text-rose-500" title="Delete"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-200"><i data-lucide="calendar-clock" class="h-7 w-7"></i></div><h3 class="mt-3 text-sm font-black text-slate-800">No EOT records found</h3><p class="mt-1 text-xs text-slate-500">Add an EOT or adjust the filters.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($entries->hasPages())
                <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/30 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Showing {{ $entries->firstItem() ?? 0 }} to {{ $entries->lastItem() ?? 0 }} of {{ $entries->total() }} EOTs</span>
                    {{ $entries->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="eotFormModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" style="display:none">
        <div class="flex max-h-[92vh] w-full max-w-7xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex flex-shrink-0 items-center justify-between border-b border-[#00515f] bg-gradient-to-b from-[#00515f] to-black px-6 py-3">
                <div class="flex items-center gap-3"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white"><i data-lucide="calendar-clock" class="h-4 w-4"></i></div><div><h3 id="eotFormModalTitle" class="text-base font-black text-white">Add New EOT</h3><p class="text-[9px] font-black uppercase tracking-widest text-slate-300">Extension of time record</p></div></div>
                <button type="button" onclick="closeEotFormModal()" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white/80 transition hover:bg-white/20" aria-label="Close"><i data-lucide="x" class="h-4 w-4"></i></button>
            </div>
            <div id="eotFormModalContent" class="flex-1 overflow-y-auto p-5"></div>
        </div>
    </div>
    <div id="eotViewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" style="display:none">
        <div class="flex max-h-[92vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-[#00515f] bg-gradient-to-b from-[#00515f] to-black px-6 py-3"><div class="flex items-center gap-3"><i data-lucide="file-clock" class="h-5 w-5 text-white"></i><h3 class="text-base font-black text-white">EOT Details</h3></div><button type="button" onclick="closeEotViewModal()" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white/80 hover:bg-white/20" aria-label="Close"><i data-lucide="x" class="h-4 w-4"></i></button></div>
            <div id="eotViewModalContent" class="flex-1 overflow-y-auto p-5"></div>
        </div>
    </div>
    <div id="eotCategoryModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm" style="display:none">
        <div class="flex max-h-[88vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-[#00515f] bg-gradient-to-b from-[#00515f] to-black px-6 py-3"><div class="flex items-center gap-3"><i data-lucide="list-tree" class="h-5 w-5 text-white"></i><h3 class="text-base font-black text-white">Manage EOT Categories</h3></div><button type="button" onclick="closeEotCategoryModal()" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white/80 hover:bg-white/20" aria-label="Close"><i data-lucide="x" class="h-4 w-4"></i></button></div>
            <div id="eotCategoryModalContent" class="flex-1 overflow-y-auto p-5"></div>
        </div>
    </div>

    <script>
        const eotRoutes = {
            create: @json(route('admin.projects.risk-pool.eot.create-modal')),
            suggest: @json(route('admin.projects.risk-pool.eot.suggest-id')),
            store: @json(route('admin.projects.risk-pool.eot.store')),
            show: @json(route('admin.projects.risk-pool.eot.show', ['eot' => '__ID__'])),
            edit: @json(route('admin.projects.risk-pool.eot.edit-modal', ['eot' => '__ID__'])),
            update: @json(route('admin.projects.risk-pool.eot.update', ['eot' => '__ID__'])),
            destroy: @json(route('admin.projects.risk-pool.eot.destroy', ['eot' => '__ID__'])),
            categories: @json(route('admin.projects.risk-pool.eot.categories.index')),
        };
        const eotCsrf = @json(csrf_token());
        const eotFormModal = document.getElementById('eotFormModal');
        const eotViewModal = document.getElementById('eotViewModal');
        const eotCategoryModal = document.getElementById('eotCategoryModal');
        const eotFilters = document.getElementById('eotFilters');
        let eotSearchTimer;
        eotFilters?.querySelector('[name="search"]')?.addEventListener('input', () => {
            clearTimeout(eotSearchTimer);
            eotSearchTimer = setTimeout(() => eotFilters.requestSubmit(), 350);
        });

        function notifyEot(message, isError = false) {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-5 right-5 z-[80] rounded-xl px-4 py-3 text-sm font-bold text-white shadow-xl ${isError ? 'bg-rose-600' : 'bg-emerald-600'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3200);
        }
        function modalOpen(modal) { modal.classList.remove('hidden'); modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
        function modalClose(modal) { modal.classList.add('hidden'); modal.style.display = 'none'; document.body.style.overflow = ''; }

        async function fetchEotHtml(url) {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Unable to load this EOT form.');
            return response.text();
        }
        async function generateNextEotId(row, force = false) {
            try {
                const categoryId = row?.querySelector('[data-field="eot_category_id"]')?.value;
                const input = row?.querySelector('[data-field="eot_id"]');
                if (!categoryId || !input || (!force && input.value.trim() && input.dataset.generated !== '1')) return;
                const url = new URL(eotRoutes.suggest, window.location.origin);
                url.searchParams.set('category_id', categoryId);
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } });
                const data = await response.json();
                if (!response.ok || !data.eot_id || !row) return;
                const existingIds = new Set([...document.querySelectorAll('#eotForm [data-field="eot_id"]')]
                    .filter(candidate => candidate !== input).map(candidate => candidate.value.trim().toUpperCase()));
                let candidate = data.eot_id;
                const match = candidate.match(/^(.*?)(\d+)$/);
                if (match) {
                    let number = Number(match[2]);
                    while (existingIds.has(candidate.toUpperCase())) candidate = `${match[1]}${String(++number).padStart(match[2].length, '0')}`;
                }
                input.value = candidate;
                input.dataset.generated = '1';
            } catch (error) { console.error(error); }
        }
        async function openCreateEotModal() {
            document.getElementById('eotFormModalTitle').textContent = 'Add New EOT';
            modalOpen(eotFormModal);
            const content = document.getElementById('eotFormModalContent');
            content.innerHTML = '<div class="py-10 text-center text-sm font-bold text-slate-400">Loading EOT form...</div>';
            try { content.innerHTML = await fetchEotHtml(eotRoutes.create); bindEotForm(content); if (window.lucide) lucide.createIcons(); }
            catch (error) { content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-bold text-rose-700">${error.message}</p>`; }
        }
        async function openEditEotModal(id) {
            document.getElementById('eotFormModalTitle').textContent = 'Edit EOT';
            modalOpen(eotFormModal);
            const content = document.getElementById('eotFormModalContent');
            content.innerHTML = '<div class="py-10 text-center text-sm font-bold text-slate-400">Loading EOT...</div>';
            try { content.innerHTML = await fetchEotHtml(eotRoutes.edit.replace('__ID__', id)); bindEotForm(content); if (window.lucide) lucide.createIcons(); }
            catch (error) { content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-bold text-rose-700">${error.message}</p>`; }
        }
        function bindEotForm(container) {
            const form = container.querySelector('#eotForm');
            if (!form) return;
            const rows = form.querySelector('[data-eot-rows]');
            const updateRowNames = () => {
                const isEdit = form.dataset.mode === 'edit';
                const currentRows = [...rows.querySelectorAll('[data-eot-row]')];
                currentRows.forEach((row, index) => {
                    if (row.dataset.readonly === '1') return;
                    const prefix = isEdit ? '' : `rows[${index}]`;
                    row.querySelectorAll('[data-field]').forEach(field => {
                        field.name = isEdit ? field.dataset.field : `${prefix}[${field.dataset.field}]`;
                    });
                    if (!isEdit) {
                        const recordId = row.querySelector('[data-record-id-field]');
                        if (recordId) recordId.name = `${prefix}[id]`;
                    }
                });
                const count = form.querySelector('[data-eot-row-count]');
                if (count) count.textContent = `${currentRows.length} ${currentRows.length === 1 ? 'row' : 'rows'}`;
                const hasSavableRows = currentRows.some(row => row.dataset.readonly !== '1');
                const submit = form.querySelector('[data-eot-submit]');
                if (submit && !isEdit) submit.disabled = !hasSavableRows;
            };
            updateRowNames();
            form.querySelector('[data-eot-add-row]')?.addEventListener('click', () => {
                const template = form.querySelector('template[data-eot-row-template]');
                const fragment = template.content.cloneNode(true);
                const newRow = fragment.querySelector('[data-eot-row]');
                rows.prepend(fragment);
                updateRowNames();
                if (window.lucide) lucide.createIcons();
                generateNextEotId(newRow);
            });
            rows.addEventListener('change', event => {
                const category = event.target.closest('[data-field="eot_category_id"]');
                if (category) generateNextEotId(category.closest('[data-eot-row]'));
            });
            rows.addEventListener('click', event => {
                const suggestButton = event.target.closest('[data-eot-suggest]');
                if (suggestButton) generateNextEotId(suggestButton.closest('[data-eot-row]'), true);
                const removeButton = event.target.closest('[data-eot-remove-row]');
                if (removeButton && rows.querySelectorAll('[data-eot-row]').length > 1) {
                    removeButton.closest('[data-eot-row]').remove();
                    updateRowNames();
                }
            });
            form.addEventListener('submit', async event => {
                event.preventDefault();
                const button = form.querySelector('[type="submit"]');
                const errorBox = form.querySelector('[data-eot-errors]');
                errorBox.classList.add('hidden'); errorBox.textContent = ''; button.disabled = true;
                try {
                    const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-CSRF-TOKEN': eotCsrf, 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Unable to save EOT.');
                    modalClose(eotFormModal); notifyEot(data.message || 'EOT saved.'); window.location.reload();
                } catch (error) { errorBox.textContent = error.message; errorBox.classList.remove('hidden'); }
                finally { button.disabled = false; }
            });
        }
        async function openEotViewModal(id) {
            modalOpen(eotViewModal);
            const content = document.getElementById('eotViewModalContent');
            content.innerHTML = '<div class="py-10 text-center text-sm font-bold text-slate-400">Loading EOT...</div>';
            try { content.innerHTML = await fetchEotHtml(eotRoutes.show.replace('__ID__', id)); if (window.lucide) lucide.createIcons(); }
            catch (error) { content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-bold text-rose-700">${error.message}</p>`; }
        }
        async function deleteEot(id) {
            if (!confirm('Delete this EOT record? This cannot be undone.')) return;
            try {
                const response = await fetch(eotRoutes.destroy.replace('__ID__', id), { method: 'DELETE', headers: { 'X-CSRF-TOKEN': eotCsrf, 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.message || 'Unable to delete EOT.');
                notifyEot(data.message || 'EOT deleted.'); window.location.reload();
            } catch (error) { notifyEot(error.message, true); }
        }
        function closeEotFormModal() { modalClose(eotFormModal); }
        function closeEotViewModal() { modalClose(eotViewModal); }
        function closeEotCategoryModal() { modalClose(eotCategoryModal); }

        async function openEotCategoryModal() {
            modalOpen(eotCategoryModal);
            const content = document.getElementById('eotCategoryModalContent');
            content.innerHTML = '<div class="py-10 text-center text-sm font-bold text-slate-400">Loading categories...</div>';
            try { content.innerHTML = await fetchEotHtml(eotRoutes.categories); bindEotCategoryManager(content); syncEotCategoryOptions(content); if (window.lucide) lucide.createIcons(); }
            catch (error) { content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-bold text-rose-700">${error.message}</p>`; }
        }
        function syncEotCategoryOptions(container) {
            const selects = document.querySelectorAll('#eotForm [data-field="eot_category_id"]');
            if (!selects.length) return;
            const categories = [...container.querySelectorAll('[data-eot-cat-row][data-active="1"]')].map(row => ({ id: row.dataset.id, name: row.dataset.name }));
            selects.forEach(select => {
                const current = select.value;
                const currentLabel = select.selectedOptions[0]?.textContent;
                select.innerHTML = '<option value="">Select base of risk</option>';
                categories.forEach(category => {
                    const option = document.createElement('option'); option.value = category.id; option.textContent = category.name; select.appendChild(option);
                });
                if (current && !categories.some(category => String(category.id) === current)) {
                    const inactiveOption = document.createElement('option'); inactiveOption.value = current; inactiveOption.textContent = currentLabel || 'Inactive category'; select.appendChild(inactiveOption);
                }
                if ([...select.options].some(option => option.value === current)) select.value = current;
            });
        }
        function bindEotCategoryManager(container) {
            const state = JSON.parse(container.querySelector('#eotCategoryState').textContent);
            const errorBox = container.querySelector('#eotCategoryErrors');
            const reportError = message => { errorBox.textContent = message; errorBox.classList.remove('hidden'); };
            const send = async (url, method, body) => {
                const response = await fetch(url, { method, headers: { 'X-CSRF-TOKEN': eotCsrf, 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify(body) });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.message || Object.values(data.errors || {}).flat().join(' ') || 'Unable to save category.');
                return data;
            };
            container.querySelector('#eotCategoryAddForm')?.addEventListener('submit', async event => {
                event.preventDefault(); const form = event.currentTarget; const payload = Object.fromEntries(new FormData(form).entries());
                try { await send(state.store, 'POST', payload); notifyEot('EOT category added.'); await openEotCategoryModal(); }
                catch (error) { reportError(error.message); }
            });
            container.querySelectorAll('[data-eot-category-edit]').forEach(input => input.addEventListener('blur', async () => {
                try {
                    await send(state.update.replace('__ID__', input.dataset.id), 'PUT', { [input.dataset.field]: input.value });
                    if (input.dataset.field === 'name') { input.closest('[data-eot-cat-row]').dataset.name = input.value; syncEotCategoryOptions(container); }
                    notifyEot('EOT category updated.');
                }
                catch (error) { reportError(error.message); }
            }));
            container.querySelectorAll('[data-eot-category-active]').forEach(input => input.addEventListener('change', async () => {
                try {
                    await send(state.update.replace('__ID__', input.dataset.id), 'PUT', { is_active: input.checked ? 1 : 0 });
                    input.closest('[data-eot-cat-row]').dataset.active = input.checked ? '1' : '0'; syncEotCategoryOptions(container); notifyEot('EOT category updated.');
                }
                catch (error) { input.checked = !input.checked; reportError(error.message); }
            }));
            container.querySelectorAll('[data-eot-category-delete]').forEach(button => button.addEventListener('click', async () => {
                if (!confirm('Delete this EOT category?')) return;
                try { await send(state.destroy.replace('__ID__', button.dataset.id), 'DELETE', {}); notifyEot('EOT category deleted.'); await openEotCategoryModal(); }
                catch (error) { reportError(error.message); }
            }));
        }
        document.addEventListener('keydown', event => { if (event.key === 'Escape') { closeEotFormModal(); closeEotViewModal(); closeEotCategoryModal(); } });
    </script>
</x-app-layout>
