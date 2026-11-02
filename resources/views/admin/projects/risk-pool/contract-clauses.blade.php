<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        @include('admin.projects.risk-pool.partials._tabs', [
            'activeTab' => 'contract-clauses',
            'stats' => ['total' => $total],
            'canCreateClauses' => $canCreateClauses,
        ])

        <form method="GET" action="{{ route('admin.projects.risk-pool.contract-clauses') }}" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(16rem,1fr)_auto]">
                <label class="space-y-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Search Contract Clauses</span>
                    <span class="relative block"><i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Row number, item, standard, or explanation" class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-bold text-slate-700 placeholder:text-slate-400 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100"></span>
                </label>
                <div class="flex items-end gap-2"><button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white transition hover:bg-[#00515f]"><i data-lucide="search" class="h-3.5 w-3.5"></i>Search</button>@if(request()->filled('search'))<a href="{{ route('admin.projects.risk-pool.contract-clauses') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[9px] font-black uppercase tracking-widest text-rose-600 transition hover:bg-rose-500 hover:text-white"><i data-lucide="x" class="h-3.5 w-3.5"></i>Clear</a>@endif</div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1120px] table-fixed text-left">
                    <thead><tr class="border-b border-[#00515f]/30 bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black uppercase tracking-widest text-white">
                        <th class="w-16 px-2 py-3">Row #</th><th class="w-44 px-2 py-3">Item</th><th class="w-28 px-2 py-3">PPA</th><th class="w-28 px-2 py-3">FIDIC Red</th><th class="w-28 px-2 py-3">FIDIC Yellow</th><th class="w-28 px-2 py-3">FIDIC Silver</th><th class="w-64 px-2 py-3">Explanation for Planners</th><th class="w-28 px-2 py-3 text-right">Actions</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($clauses as $clause)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="whitespace-nowrap px-2 py-3"><span class="inline-flex min-w-8 justify-center rounded-lg border border-slate-200 bg-white px-2 py-1 text-[9px] font-black text-slate-700 shadow-sm tabular-nums">{{ $clause->row_number }}</span></td>
                                <td class="px-2 py-3"><p class="truncate text-xs font-bold text-slate-800" title="{{ $clause->item }}">{{ $clause->item }}</p></td>
                                <td class="px-2 py-3 text-[10px] font-semibold text-slate-600"><span class="block truncate" title="{{ $clause->ppa }}">{{ $clause->ppa ?: '—' }}</span></td>
                                <td class="px-2 py-3 text-[10px] font-semibold text-slate-600"><span class="block truncate" title="{{ $clause->fidic_red }}">{{ $clause->fidic_red ?: '—' }}</span></td>
                                <td class="px-2 py-3 text-[10px] font-semibold text-slate-600"><span class="block truncate" title="{{ $clause->fidic_yellow }}">{{ $clause->fidic_yellow ?: '—' }}</span></td>
                                <td class="px-2 py-3 text-[10px] font-semibold text-slate-600"><span class="block truncate" title="{{ $clause->fidic_silver }}">{{ $clause->fidic_silver ?: '—' }}</span></td>
                                <td class="px-2 py-3"><p class="line-clamp-2 break-words text-[10px] font-medium leading-4 text-slate-600" title="{{ $clause->planner_explanation }}">{{ $clause->planner_explanation }}</p></td>
                                <td class="px-2 py-3 text-right"><div class="flex items-center justify-end gap-1.5">
                                    <button type="button" onclick="openClauseView({{ $clause->id }})" class="rounded-md border border-slate-200 bg-white p-1.5 text-slate-400 transition hover:border-sky-500 hover:text-sky-500" title="View"><i data-lucide="eye" class="h-3.5 w-3.5"></i></button>
                                    @if($canEditClauses)<button type="button" onclick="openClauseEdit({{ $clause->id }})" class="rounded-md border border-slate-200 bg-white p-1.5 text-slate-400 transition hover:border-amber-500 hover:text-amber-500" title="Edit"><i data-lucide="pencil" class="h-3.5 w-3.5"></i></button>@endif
                                    @if($canDeleteClauses)<button type="button" onclick="deleteClause({{ $clause->id }})" class="rounded-md border border-slate-200 bg-white p-1.5 text-slate-400 transition hover:border-rose-500 hover:text-rose-500" title="Delete"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i></button>@endif
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-16 text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-200"><i data-lucide="file-text" class="h-7 w-7"></i></div><h3 class="mt-3 text-sm font-black text-slate-800">No contract clauses found</h3><p class="mt-1 text-xs text-slate-500">Add a clause or adjust the search.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($clauses->hasPages())<div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/30 px-4 py-3 sm:flex-row sm:items-center sm:justify-between"><span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Showing {{ $clauses->firstItem() ?? 0 }} to {{ $clauses->lastItem() ?? 0 }} of {{ $clauses->total() }} clauses</span>{{ $clauses->links() }}</div>@endif
        </div>
    </div>

    <div id="clauseFormModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" style="display:none"><div class="flex max-h-[92vh] w-full max-w-[96vw] flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"><div class="flex flex-shrink-0 items-center justify-between border-b border-[#00515f] bg-gradient-to-b from-[#00515f] to-black px-6 py-3"><div class="flex items-center gap-3"><i data-lucide="file-text" class="h-5 w-5 text-white"></i><div><h3 id="clauseFormTitle" class="text-base font-black text-white">Add New Contract Clause</h3><p class="text-[9px] font-black uppercase tracking-widest text-slate-300">Contract clause repository</p></div></div><button type="button" onclick="closeClauseFormModal()" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white/80 hover:bg-white/20" aria-label="Close"><i data-lucide="x" class="h-4 w-4"></i></button></div><div id="clauseFormContent" class="flex-1 overflow-auto p-5"></div></div></div>
    <div id="clauseViewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" style="display:none"><div class="flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"><div class="flex items-center justify-between border-b border-[#00515f] bg-gradient-to-b from-[#00515f] to-black px-6 py-3"><div class="flex items-center gap-3"><i data-lucide="file-search" class="h-5 w-5 text-white"></i><h3 class="text-base font-black text-white">Contract Clause Details</h3></div><button type="button" onclick="closeClauseView()" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white/80 hover:bg-white/20" aria-label="Close"><i data-lucide="x" class="h-4 w-4"></i></button></div><div id="clauseViewContent" class="flex-1 overflow-y-auto p-5"></div></div></div>

    <script>
        const clauseRoutes = {
            create: @json(route('admin.projects.risk-pool.contract-clauses.create-modal')),
            store: @json(route('admin.projects.risk-pool.contract-clauses.store')),
            show: @json(route('admin.projects.risk-pool.contract-clauses.show', ['clause' => '__ID__'])),
            edit: @json(route('admin.projects.risk-pool.contract-clauses.edit-modal', ['clause' => '__ID__'])),
            update: @json(route('admin.projects.risk-pool.contract-clauses.update', ['clause' => '__ID__'])),
            destroy: @json(route('admin.projects.risk-pool.contract-clauses.destroy', ['clause' => '__ID__'])),
        };
        const clauseCsrf = @json(csrf_token());
        const clauseFormModal = document.getElementById('clauseFormModal');
        const clauseViewModal = document.getElementById('clauseViewModal');
        function clauseModalOpen(modal) { modal.classList.remove('hidden'); modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
        function clauseModalClose(modal) { modal.classList.add('hidden'); modal.style.display = 'none'; document.body.style.overflow = ''; }
        function notifyClause(message, error = false) { const toast = document.createElement('div'); toast.className = `fixed bottom-5 right-5 z-[80] rounded-xl px-4 py-3 text-sm font-bold text-white shadow-xl ${error ? 'bg-rose-600' : 'bg-emerald-600'}`; toast.textContent = message; document.body.appendChild(toast); setTimeout(() => toast.remove(), 3200); }
        async function fetchClauseHtml(url) { const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }); if (!response.ok) throw new Error('Unable to load the contract clause.'); return response.text(); }
        async function openCreateClauseModal() { document.getElementById('clauseFormTitle').textContent = 'Add New Contract Clause'; clauseModalOpen(clauseFormModal); const content = document.getElementById('clauseFormContent'); content.innerHTML = '<div class="py-10 text-center text-sm font-bold text-slate-400">Loading clauses...</div>'; try { content.innerHTML = await fetchClauseHtml(clauseRoutes.create); bindClauseForm(content); if (window.lucide) lucide.createIcons(); } catch (error) { content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-bold text-rose-700">${error.message}</p>`; } }
        async function openClauseEdit(id) { document.getElementById('clauseFormTitle').textContent = 'Edit Contract Clause'; clauseModalOpen(clauseFormModal); const content = document.getElementById('clauseFormContent'); content.innerHTML = '<div class="py-10 text-center text-sm font-bold text-slate-400">Loading clause...</div>'; try { content.innerHTML = await fetchClauseHtml(clauseRoutes.edit.replace('__ID__', id)); bindClauseForm(content); if (window.lucide) lucide.createIcons(); } catch (error) { content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-bold text-rose-700">${error.message}</p>`; } }
        function bindClauseForm(container) {
            const form = container.querySelector('#clauseForm'); if (!form) return;
            const tbody = form.querySelector('[data-clause-rows]');
            const renumberNames = () => {
                const rows = [...tbody.querySelectorAll('[data-clause-row]')]; const edit = form.dataset.mode === 'edit';
                rows.forEach((row, index) => { if (row.dataset.readonly === '1') return; const prefix = edit ? '' : `rows[${index}]`; row.querySelectorAll('[data-field]').forEach(field => { field.name = edit ? field.dataset.field : `${prefix}[${field.dataset.field}]`; }); const idField = row.querySelector('[data-record-id-field]'); if (idField && !edit) idField.name = `${prefix}[id]`; });
                const count = form.querySelector('[data-clause-row-count]'); if (count) count.textContent = `${rows.length} ${rows.length === 1 ? 'row' : 'rows'}`;
                const submit = form.querySelector('[data-clause-submit]'); if (!edit && submit) submit.disabled = !rows.some(row => row.dataset.readonly !== '1');
            };
            renumberNames();
            form.querySelector('[data-clause-add-row]')?.addEventListener('click', () => { const next = Math.max(0, ...[...tbody.querySelectorAll('[data-field="row_number"]')].map(input => Number(input.value) || 0)) + 1; const fragment = form.querySelector('template[data-clause-row-template]').content.cloneNode(true); const row = fragment.querySelector('[data-clause-row]'); row.querySelector('[data-field="row_number"]').value = next; tbody.prepend(fragment); renumberNames(); if (window.lucide) lucide.createIcons(); row.querySelector('[data-field="item"]').focus(); });
            tbody.addEventListener('click', event => { const button = event.target.closest('[data-clause-remove-row]'); if (button) { button.closest('[data-clause-row]').remove(); renumberNames(); } });
            form.addEventListener('submit', async event => {
                event.preventDefault(); const button = form.querySelector('[data-clause-submit]'); const errorBox = form.querySelector('[data-clause-errors]'); errorBox.classList.add('hidden'); errorBox.textContent = ''; button.disabled = true;
                try { const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-CSRF-TOKEN': clauseCsrf, 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } }); const data = await response.json().catch(() => ({})); if (!response.ok) throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Unable to save contract clauses.'); clauseModalClose(clauseFormModal); notifyClause(data.message || 'Contract clauses saved.'); window.location.reload(); }
                catch (error) { errorBox.textContent = error.message; errorBox.classList.remove('hidden'); }
                finally { button.disabled = false; }
            });
        }
        async function openClauseView(id) { clauseModalOpen(clauseViewModal); const content = document.getElementById('clauseViewContent'); content.innerHTML = '<div class="py-10 text-center text-sm font-bold text-slate-400">Loading clause...</div>'; try { content.innerHTML = await fetchClauseHtml(clauseRoutes.show.replace('__ID__', id)); if (window.lucide) lucide.createIcons(); } catch (error) { content.innerHTML = `<p class="rounded-xl bg-rose-50 p-4 text-sm font-bold text-rose-700">${error.message}</p>`; } }
        async function deleteClause(id) { if (!confirm('Delete this contract clause? This cannot be undone.')) return; try { const response = await fetch(clauseRoutes.destroy.replace('__ID__', id), { method: 'DELETE', headers: { 'X-CSRF-TOKEN': clauseCsrf, 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } }); const data = await response.json().catch(() => ({})); if (!response.ok) throw new Error(data.message || 'Unable to delete contract clause.'); notifyClause(data.message || 'Contract clause deleted.'); window.location.reload(); } catch (error) { notifyClause(error.message, true); } }
        function closeClauseFormModal() { clauseModalClose(clauseFormModal); }
        function closeClauseView() { clauseModalClose(clauseViewModal); }
        document.addEventListener('keydown', event => { if (event.key === 'Escape') { closeClauseFormModal(); closeClauseView(); } });
    </script>
</x-app-layout>
