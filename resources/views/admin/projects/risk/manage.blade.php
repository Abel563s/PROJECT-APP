<x-app-layout>
    <div class="py-4 px-4 lg:px-6 space-y-4 w-full max-w-none font-inter">
        <div class="flex flex-col gap-4 border-b border-slate-100 pb-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.risk.index') }}"
                   class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm transition-all hover:border-[#00ADC5]/20 hover:text-[#00ADC5]">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold leading-none tracking-tight text-slate-900">
                        Risk <span class="font-black">Register</span>
                    </h2>
                    <p class="mt-1 text-[8px] font-black uppercase tracking-[0.3em] text-[#00ADC5]">{{ $project->project_name }} - {{ $project->project_code }}</p>
                </div>
            </div>
            @if($canManageProject)
                <button onclick="openCreateModal()"
                    class="group/btn relative overflow-hidden rounded-xl bg-slate-900 px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-slate-900/20 transition-all hover:scale-105 active:scale-95">
                    <span class="absolute inset-0 translate-x-[-100%] bg-gradient-to-r from-[#00ADC5] to-blue-500 transition-transform duration-500 group-hover/btn:translate-x-0"></span>
                    <span class="relative flex items-center gap-1.5"><i data-lucide="plus" class="h-3 w-3"></i>New Risk</span>
                </button>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="space-y-2 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-2 text-slate-400"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600"><i data-lucide="alert-triangle" class="h-4 w-4"></i></div><span class="text-[8px] font-black uppercase tracking-widest">Total Risks</span></div>
                <p class="text-xl font-black text-slate-900">{{ $stats['total'] }}</p>
            </div>
            <div class="space-y-2 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-2 text-slate-400"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600"><i data-lucide="circle" class="h-4 w-4"></i></div><span class="text-[8px] font-black uppercase tracking-widest">Open</span></div>
                <p class="text-xl font-black text-sky-600">{{ $stats['open'] }}</p>
            </div>
            <div class="space-y-2 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-2 text-slate-400"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i data-lucide="loader" class="h-4 w-4"></i></div><span class="text-[8px] font-black uppercase tracking-widest">In Progress</span></div>
                <p class="text-xl font-black text-amber-600">{{ $stats['in_progress'] }}</p>
            </div>
            <div class="space-y-2 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-2 text-slate-400"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i data-lucide="check-circle" class="h-4 w-4"></i></div><span class="text-[8px] font-black uppercase tracking-widest">Closed</span></div>
                <p class="text-xl font-black text-emerald-600">{{ $stats['closed'] }}</p>
            </div>
            <div class="space-y-2 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-2 text-slate-400"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 text-rose-600"><i data-lucide="zap" class="h-4 w-4"></i></div><span class="text-[8px] font-black uppercase tracking-widest">Extreme Rating</span></div>
                <p class="text-xl font-black text-rose-600">{{ $stats['extreme'] }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#00515f]/30 bg-gradient-to-b from-[#00515f] to-black text-[9px] font-black uppercase tracking-widest text-white">
                            <th class="px-4 py-3 text-center">#</th>
                            <th class="px-4 py-3">Risk Name</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3 text-center">Type</th>
                            <th class="px-4 py-3 text-center">Rating (P × I)</th>
                            <th class="px-4 py-3 text-center">Urgency</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3">Risk Owner</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($risks as $risk)
                            @php
                                $ratingClasses = [
                                    'Low' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'High' => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'Extreme' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $statusClasses = [
                                    'Open' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'Inprogress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Closed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                ];
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-center"><span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-100 text-[10px] font-black tabular-nums text-slate-900">{{ $risks->firstItem() + $loop->index }}</span></td>
                                <td class="max-w-xs px-4 py-3">
                                    <p class="text-xs font-black leading-tight text-slate-900">{{ $risk->risk_name }}</p>
                                    <p class="mt-1 line-clamp-2 text-[10px] text-slate-500">{{ $risk->risk_description }}</p>
                                </td>
                                <td class="px-4 py-3"><span class="inline-flex rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black text-slate-700">{{ $risk->risk_category }}</span></td>
                                <td class="px-4 py-3 text-center"><span class="text-[10px] font-bold text-slate-700">{{ $risk->risk_type }}</span></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex flex-col items-center rounded-lg border px-2.5 py-1 {{ $ratingClasses[$risk->risk_level] }}">
                                        <span class="text-[10px] font-black">{{ $risk->risk_score }}</span>
                                        <span class="text-[8px] font-black uppercase tracking-wider">{{ $risk->risk_level }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-[10px] font-bold text-slate-600">{{ $risk->risk_urgency ?: '---' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex rounded-lg border px-2.5 py-1 text-[9px] font-black uppercase tracking-wider {{ $statusClasses[$risk->action_status] ?? 'border-slate-200 bg-slate-100 text-slate-500' }}">
                                        {{ $risk->action_status === 'Inprogress' ? 'In Progress' : $risk->action_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-[10px] font-bold text-slate-700">{{ $risk->owner_name ?: $risk->risk_owner ?: '---' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-200"><i data-lucide="alert-triangle" class="h-8 w-8"></i></div>
                                        <div><h3 class="text-lg font-black text-slate-900">No Risks Registered</h3><p class="mt-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Create a risk to start tracking this project.</p></div>
                                        @if($canManageProject)
                                            <button onclick="openCreateModal()" class="mt-1 rounded-lg bg-slate-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white hover:bg-cyan-700">Add First Risk</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/30 p-4 sm:flex-row sm:items-center sm:justify-between">
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Showing {{ $risks->firstItem() ?? 0 }} to {{ $risks->lastItem() ?? 0 }} of {{ $risks->total() }} risks</span>
                <div>{{ $risks->links() }}</div>
            </div>
        </div>
    </div>

    <div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm">
        <div class="flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex flex-shrink-0 items-center justify-between border-b border-[#00515f] bg-gradient-to-b from-[#00515f] to-black px-6 py-3">
                <div>
                    <h3 class="text-base font-black text-white">New Risk</h3>
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-300">Risk assessment for {{ $project->project_code }}</p>
                </div>
                <button type="button" onclick="closeCreateModal()" aria-label="Close"
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white/80 transition-colors hover:bg-white/20">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <div id="createModalContent" class="flex-1 overflow-y-auto p-5">
                <div class="flex items-center justify-center py-12"><div class="h-8 w-8 animate-spin rounded-full border-2 border-[#00ADC5] border-t-transparent"></div></div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            const modal = document.getElementById('createModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            fetch(@json(route('admin.projects.risk.create-modal', $project)))
                .then(response => {
                    if (!response.ok) throw new Error(`Unable to load the risk form (${response.status}).`);
                    return response.text();
                })
                .then(html => {
                    document.getElementById('createModalContent').innerHTML = html;
                    if (window.lucide) lucide.createIcons();
                    updateRiskRating();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('createModalContent').innerHTML = '<p class="rounded-xl bg-rose-50 p-4 text-xs font-bold text-rose-700">Unable to load the risk form. Please try again.</p>';
                });
        }

        function closeCreateModal() {
            const modal = document.getElementById('createModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function updateRiskRating() {
            const likelihoodInput = document.getElementById('likelihood');
            const severityInput = document.getElementById('severity');
            if (!likelihoodInput || !severityInput) return;

            const likelihood = Number(likelihoodInput.value);
            const severity = Number(severityInput.value);
            const score = likelihood * severity;
            const level = score >= 17 ? 'Extreme' : score >= 10 ? 'High' : score >= 5 ? 'Medium' : 'Low';
            const colors = { Low: 'text-emerald-600', Medium: 'text-amber-600', High: 'text-orange-600', Extreme: 'text-rose-600' };

            document.getElementById('likelihoodValue').textContent = likelihood;
            document.getElementById('severityValue').textContent = severity;
            document.getElementById('riskRatingScore').textContent = score;
            document.getElementById('riskRatingLevel').textContent = level;
            document.getElementById('riskRatingLevel').className = `text-[9px] font-black uppercase tracking-widest ${colors[level]}`;
        }

        document.getElementById('createModal').addEventListener('click', function (event) {
            if (event.target === this) closeCreateModal();
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') closeCreateModal();
        });
        @if($errors->any())
            openCreateModal();
        @endif
    </script>
</x-app-layout>
