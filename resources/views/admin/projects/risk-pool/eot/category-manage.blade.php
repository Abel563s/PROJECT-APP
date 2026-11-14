<div class="space-y-4">
    <div>
        <h4 class="text-sm font-black text-slate-900">Base of EOT Categories</h4>
        <p class="mt-1 text-[9px] font-black uppercase tracking-widest text-slate-400">Categories available on EOT records. Inactive categories are hidden from new entries.</p>
    </div>
    <div id="eotCategoryErrors" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs font-bold text-rose-700" role="alert"></div>

    @if($canCreateCategory)
        <form id="eotCategoryAddForm" class="grid grid-cols-1 gap-2 sm:grid-cols-[1fr_140px_auto]">
            <input name="name" type="text" required maxlength="255" placeholder="New base of EOT"
                class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
            <input name="display_order" type="number" min="0" placeholder="Display order"
                class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 focus:border-cyan-300 focus:bg-white focus:ring-cyan-100">
            <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white transition hover:bg-[#00515f]"><i data-lucide="plus" class="h-3.5 w-3.5"></i>Add Category</button>
        </form>
    @endif

    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full min-w-[560px] text-left text-xs text-slate-700">
            <thead><tr class="bg-slate-50 text-[9px] font-black uppercase tracking-widest text-slate-500"><th class="px-3 py-2">Base of EOT</th><th class="px-3 py-2">Order</th><th class="px-3 py-2">Active</th><th class="px-3 py-2 text-center">Used By</th><th class="px-3 py-2 text-right">Action</th></tr></thead>
            <tbody>
            @forelse($categories as $category)
                @php($usage = \App\Models\EotEntry::where('eot_category_id', $category->id)->count())
                <tr data-eot-cat-row data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-active="{{ $category->is_active ? 1 : 0 }}" class="border-t border-slate-100">
                    <td class="px-3 py-2"><input data-eot-category-edit data-field="name" data-id="{{ $category->id }}" value="{{ $category->name }}" @disabled(!$canEditCategory) class="w-full rounded-md border border-slate-200 px-2 py-1 text-xs font-bold focus:border-cyan-300 focus:ring-cyan-100"></td>
                    <td class="px-3 py-2"><input data-eot-category-edit data-field="display_order" data-id="{{ $category->id }}" type="number" min="0" value="{{ $category->display_order }}" @disabled(!$canEditCategory) class="w-20 rounded-md border border-slate-200 px-2 py-1 text-xs font-bold focus:border-cyan-300 focus:ring-cyan-100"></td>
                    <td class="px-3 py-2"><input data-eot-category-active data-id="{{ $category->id }}" type="checkbox" @checked($category->is_active) @disabled(!$canEditCategory) class="rounded border-slate-300 text-cyan-700 focus:ring-cyan-600"></td>
                    <td class="px-3 py-2 text-center"><span class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[9px] font-black tabular-nums">{{ $usage }}</span></td>
                    <td class="px-3 py-2 text-right">@if($canDeleteCategory)<button type="button" data-eot-category-delete data-id="{{ $category->id }}" @disabled($usage > 0) title="{{ $usage > 0 ? 'Deactivate categories that are in use' : 'Delete category' }}" class="rounded-md border border-slate-200 bg-white p-1.5 text-slate-400 transition hover:border-rose-500 hover:text-rose-500 disabled:cursor-not-allowed disabled:opacity-40"><i data-lucide="trash-2" class="h-3.5 w-3.5"></i></button>@endif</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-3 py-8 text-center text-xs font-semibold text-slate-400">No EOT categories yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <p class="text-[9px] font-semibold text-slate-400">Changes to names and order save when you leave a field. Deactivate a category that is already in use.</p>
</div>

<script type="application/json" id="eotCategoryState">{!! json_encode([
    'store' => route('admin.projects.risk-pool.eot.categories.store'),
    'update' => route('admin.projects.risk-pool.eot.categories.update', ['category' => '__ID__']),
    'destroy' => route('admin.projects.risk-pool.eot.categories.destroy', ['category' => '__ID__']),
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) !!}</script>
