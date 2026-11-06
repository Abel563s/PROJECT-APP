@php
    $impactLevels = \App\Models\ProjectRiskPool::IMPACT_LEVELS;
@endphp

<div class="category-manager space-y-3">
    <div class="space-y-2">
        <h4 class="text-sm font-black text-slate-900">Risk Categories</h4>
        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">
            Categories with a code prefix drive Risk ID suggestions. Deactivated categories are hidden from new selection.
        </p>
    </div>

    <div id="catErrors"
        class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-[9px] font-bold text-rose-700"
        role="alert"></div>

    @if($canCreateRiskCategory)
    <form id="catAddForm" class="grid grid-cols-1 gap-2 sm:grid-cols-5">
        <input id="catName" name="name" type="text" required placeholder="New category name"
            class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5] focus:bg-white focus:ring-cyan-100">
        <input id="catCode" name="code" type="text" required inputmode="numeric" pattern="[0-9]+" placeholder="ID prefix (e.g. 12)"
            class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5] focus:bg-white focus:ring-cyan-100">
        <select id="catRiskType" name="risk_type" required
            class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5] focus:bg-white focus:ring-cyan-100">
            <option value="">Assign risk type</option>
            @foreach(\App\Models\ProjectRiskPool::RISK_TYPES as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
        <input id="catOrder" name="display_order" type="number" min="0" placeholder="Display order"
            class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-[#00ADC5] focus:bg-white focus:ring-cyan-100">
        <button type="submit"
            class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-[#00515f]/90 active:scale-95">
            <i data-lucide="plus" class="h-3 w-3"></i> Add
        </button>
    </form>
    @endif

    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full text-[10px] font-bold text-slate-800">
            <thead>
                <tr class="bg-slate-50/80">
                    <th class="border-b border-slate-200 px-2 py-2 text-left">Name</th>
                    <th class="border-b border-slate-200 px-2 py-2 text-left">Code / Prefix</th>
                    <th class="border-b border-slate-200 px-2 py-2 text-left">Risk Type</th>
                    <th class="border-b border-slate-200 px-2 py-2 text-left">Order</th>
                    <th class="border-b border-slate-200 px-2 py-2 text-left">Active</th>
                    <th class="border-b border-slate-200 px-2 py-2 text-center">Used By</th>
                    <th class="border-b border-slate-200 px-2 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="catList">
                @forelse($active as $cat)
                    @php
                        $usage = \App\Models\ProjectRiskPool::where('risk_category', $cat->name)->count();
                    @endphp
                    <tr class="row-{{ $cat->id }}" data-cat-id="{{ $cat->id }}">
                        <td class="border-b border-slate-100/60 px-2 py-2 max-w-xs">
                            <input data-field="name" data-id="{{ $cat->id }}"
                                value="{{ $cat->name }}"
                                @disabled(!$canEditRiskCategory)
                                class="cat-edit rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-black text-slate-900 focus:border-[#00ADC5] focus:ring-cyan-100"
                                style="width:100%">
                        </td>
                        <td class="border-b border-slate-100/60 px-2 py-2">
                            <input data-field="code" data-id="{{ $cat->id }}" type="text" inputmode="numeric" pattern="[0-9]+"
                                value="{{ $cat->code }}"
                                @disabled(!$canEditRiskCategory)
                                placeholder="—"
                                class="cat-edit rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-black text-slate-700 focus:border-[#00ADC5] focus:ring-cyan-100 w-20">
                        </td>
                        <td class="border-b border-slate-100/60 px-2 py-2">
                            <select data-field="risk_type" data-id="{{ $cat->id }}"
                                @disabled(!$canEditRiskCategory)
                                class="cat-edit cat-type rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-black text-slate-700 focus:border-[#00ADC5] focus:ring-cyan-100">
                                <option value="">Assign type</option>
                                @foreach(\App\Models\ProjectRiskPool::RISK_TYPES as $type)
                                    <option value="{{ $type }}" @selected($cat->risk_type === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="border-b border-slate-100/60 px-2 py-2">
                            <input data-field="display_order" data-id="{{ $cat->id }}" type="number" min="0"
                                value="{{ $cat->display_order }}"
                                @disabled(!$canEditRiskCategory)
                                class="cat-order rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-black text-slate-900 focus:border-[#00ADC5] focus:ring-cyan-100 w-16">
                        </td>
                        <td class="border-b border-slate-100/60 px-2 py-2">
                            <label class="relative inline-flex h-6 w-11 items-center">
                                <input data-toggle="is_active" data-id="{{ $cat->id }}" type="checkbox"
                                    class="cat-toggle peer h-0 w-0 opacity-0" {{ $cat->is_active ? 'checked' : '' }} @disabled(!$canEditRiskCategory)>
                                <span class="peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-offset-1 absolute inset-0 cursor-pointer rounded-full border border-slate-300 transition-colors duration-200
                                    {{ $cat->is_active ? 'bg-green-500 ring-green-200' : 'bg-red-500 ring-rose-200' }}"></span>
                                <span class="absolute inline-block h-5 w-5 transform rounded-full bg-white shadow pointer-events-none transition-transform duration-200
                                    {{ $cat->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </label>
                        </td>
                        <td class="border-b border-slate-100/60 px-2 py-2 text-center align-middle">
                            <span class="inline-flex rounded-lg border border-slate-200 bg-white px-2 py-0.5 text-[8px] font-black text-slate-700 tabular-nums">{{ $usage }}</span>
                        </td>
                        <td class="border-b border-slate-100/60 px-2 py-2 text-right align-middle">
                            @if($canDeleteRiskCategory)
                            <button type="button" data-action="delete" data-id="{{ $cat->id }}"
                                {{ $usage > 0 ? 'disabled' : '' }}
                                class="cat-delete p-1.5 bg-white border border-slate-200 rounded-md text-slate-400 hover:text-rose-500 hover:border-rose-500 hover:shadow-sm transition-all"
                                title="Delete category">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-2 py-8 text-center">
                            <p class="text-xs font-black text-slate-400">No active categories. Add one above.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between pt-1">
        <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">
            Inactive categories are hidden here. Edits auto-save when you click away.
        </p>
        @if($canEditRiskCategory)
        <button type="button" id="catSaveAll"
            class="inline-flex items-center gap-1.5 rounded-xl bg-[#00515f] px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white shadow-lg shadow-[#00515f]/20 transition-all hover:bg-[#006572] active:scale-95">
            <i data-lucide="save" class="h-3 w-3"></i> Save
        </button>
        @endif
    </div>
</div>

<script type="application/json" id="categoryManagerState">{!! json_encode([
    'routes' => [
        'store'   => route('admin.projects.risk-pool.categories.store'),
        'update'  => route('admin.projects.risk-pool.categories.update', ['risk_category' => '__ID__']),
        'destroy' => route('admin.projects.risk-pool.categories.destroy', ['risk_category' => '__ID__']),
    ],
    'csrf' => csrf_token(),
]) !!}</script>
