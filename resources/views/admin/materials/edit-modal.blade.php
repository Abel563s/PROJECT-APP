<form action="{{ route('admin.material.update', $material->id) }}" method="POST" id="editModalForm" class="space-y-4">
    @csrf
    @method('PUT')
    <input type="hidden" name="material_code" value="{{ $material->material_code }}">

    <!-- Section 1: Material Information -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="package" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Material Information</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core details</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Material Code</label>
                <input type="text" disabled value="{{ $material->material_code }}"
                     class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-400 cursor-not-allowed">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Material Name</label>
                <input type="text" name="material_name" value="{{ $material->material_name }}" required
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project</label>
                <select name="project_id" id="projectSearch"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $material->project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->project_code }} - {{ $project->project_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Supplier</label>
                <input type="text" name="supplier_name" value="{{ $material->supplier_name }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Discipline</label>
                <select name="discipline"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select</option>
                    <option value="Civil" {{ $material->discipline == 'Civil' ? 'selected' : '' }}>Civil</option>
                    <option value="MEP" {{ $material->discipline == 'MEP' ? 'selected' : '' }}>MEP</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Product</label>
                <select name="procurement_product"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select</option>
                    <option value="Material" {{ $material->procurement_product == 'Material' ? 'selected' : '' }}>Material</option>
                    <option value="Service" {{ $material->procurement_product == 'Service' ? 'selected' : '' }}>Service</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Route</label>
                <select name="supply_route"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="">Select</option>
                    <option value="Import" {{ $material->supply_route == 'Import' ? 'selected' : '' }}>Import</option>
                    <option value="Local" {{ $material->supply_route == 'Local' ? 'selected' : '' }}>Local</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Approval</label>
                <select name="approval"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                    <option value="No" {{ old('approval', $material->approval ?? 'No') == 'No' ? 'selected' : '' }}>No</option>
                    <option value="Yes" {{ old('approval', $material->approval ?? 'No') == 'Yes' ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
        </div>
        <div class="space-y-1">
            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Procurement Type</label>
            <select name="procurement_type"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-cyan-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all cursor-pointer">
                <option value="">Select Procurement Type</option>
                @foreach(['LVA','RFQ-Local','RFQ-Import-Birr','RFQ-Import-USD','IFB-ICB','IFB-NCB','RFP','LTA'] as $t)
                    <option value="{{ $t }}" {{ $material->procurement_type == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Section 2: Timeline -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Procurement Timeline</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Optional milestones</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            @foreach(['design_planned','design_actual','spec_preparation','request_order','supplier_identification','material_submittal','material_approval','finance','lc','production_comp','shipment_of_material','logistics_to_dry_port','clearance_of_material','delivery_to_site','planned_start_date'] as $field)
                <div class="space-y-1">
                    <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ ucwords(str_replace('_',' ', $field)) }}</label>
                    <input type="date" name="{{ $field }}" value="{{ old($field, optional($material->{$field})->format('Y-m-d')) }}"
                        class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
                </div>
            @endforeach
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Production Days</label>
                <input type="number" name="production_days" value="{{ old('production_days', $material->production_days) }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
            <div class="space-y-1">
                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Shipment Days</label>
                <input type="number" name="shipment_days" value="{{ old('shipment_days', $material->shipment_days) }}"
                    class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-amber-100/50 focus:bg-white focus:border-[#00ADC5]/20 transition-all">
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit" form="editModalForm"
            class="px-6 py-2 bg-[#00ADC5] text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-[#00ADC5]/90 transition-all shadow-lg shadow-cyan-500/20">
            Update Material
        </button>
    </div>
</form>
