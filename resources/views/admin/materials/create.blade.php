<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.material.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#00ADC5] hover:border-[#00ADC5]/20 hover:bg-[#f0fbfd] transition-all">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">New Material</h1>
                    <p class="text-slate-500 font-medium text-sm">Capture material details and procurement timeline.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.material.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/30">
                    <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-[#00ADC5]"></i>
                        Material Information
                    </h2>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Project</label>
                        <select name="project_id" id="projectSearch" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_code }} - {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Material Name</label>
                        <input type="text" name="material_name" required value="{{ old('material_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl" placeholder="e.g. Cement bags">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Supplier Name</label>
                        <input type="text" name="supplier_name" required value="{{ old('supplier_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl" placeholder="Supplier name">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Discipline</label>
                        <select name="discipline" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl">
                            <option value="">Select Discipline</option>
                            <option value="Civil" {{ old('discipline') == 'Civil' ? 'selected' : '' }}>Civil</option>
                            <option value="MEP" {{ old('discipline') == 'MEP' ? 'selected' : '' }}>MEP</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Procurement Product</label>
                        <select name="procurement_product" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl">
                            <option value="">Select Product</option>
                            <option value="Material" {{ old('procurement_product') == 'Material' ? 'selected' : '' }}>Material</option>
                            <option value="Service" {{ old('procurement_product') == 'Service' ? 'selected' : '' }}>Service</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Supply Route</label>
                        <select name="supply_route" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl">
                            <option value="">Select Route</option>
                            <option value="Import" {{ old('supply_route') == 'Import' ? 'selected' : '' }}>Import</option>
                            <option value="Local" {{ old('supply_route') == 'Local' ? 'selected' : '' }}>Local</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Approval</label>
                        <select name="approval" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl">
                            <option value="No" {{ old('approval', 'No') == 'No' ? 'selected' : '' }}>No</option>
                            <option value="Yes" {{ old('approval', 'No') == 'Yes' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Procurement Type</label>
                        <select name="procurement_type" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl">
                            <option value="">Select Procurement Type</option>
                            @foreach(['LVA','RFQ-Local','RFQ-Import-Birr','RFQ-Import-USD','IFB-ICB','IFB-NCB','RFP','LTA'] as $t)
                                <option value="{{ $t }}" {{ old('procurement_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/30">
                    <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5 text-[#00ADC5]"></i>
                        Procurement Timeline
                    </h2>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">Design Phase</h4>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Design Planned</label>
                            <input type="date" name="design_planned" value="{{ old('design_planned') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Design Actual</label>
                            <input type="date" name="design_actual" value="{{ old('design_actual') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Spec Preparation</label>
                            <input type="date" name="spec_preparation" value="{{ old('spec_preparation') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">Procurement Phase</h4>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Request Order</label>
                            <input type="date" name="request_order" value="{{ old('request_order') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Supplier Identification</label>
                            <input type="date" name="supplier_identification" value="{{ old('supplier_identification') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Material Submittal</label>
                            <input type="date" name="material_submittal" value="{{ old('material_submittal') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Planned Start Date</label>
                            <input type="date" name="planned_start_date" value="{{ old('planned_start_date') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">Submission & Logistics</h4>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Material Approval</label>
                            <input type="date" name="material_approval" value="{{ old('material_approval') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">LC</label>
                            <input type="date" name="lc" value="{{ old('lc') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Payment</label>
                            <input type="date" name="payment" value="{{ old('payment') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Shipment of Material</label>
                            <input type="date" name="shipment_of_material" value="{{ old('shipment_of_material') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Logistics to Dry Port</label>
                            <input type="date" name="logistics_to_dry_port" value="{{ old('logistics_to_dry_port') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest">Site & Production</h4>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Clearance of Material</label>
                            <input type="date" name="clearance_of_material" value="{{ old('clearance_of_material') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Delivery to Site</label>
                            <input type="date" name="delivery_to_site" value="{{ old('delivery_to_site') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Production Comp</label>
                            <input type="date" name="production_comp" value="{{ old('production_comp') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Production Days</label>
                            <input type="number" name="production_days" value="{{ old('production_days') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Shipment Days</label>
                            <input type="number" name="shipment_days" value="{{ old('shipment_days') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] outline-none transition-all rounded-xl">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pb-12">
                <a href="{{ route('admin.material.index') }}" class="px-8 py-3 rounded-2xl font-bold text-slate-500 hover:bg-slate-100 transition-all">Cancel</a>
                <button type="submit" class="bg-[#00ADC5] hover:bg-[#0099b3] text-white px-10 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-[#00ADC5]/20 hover:scale-105 active:scale-95">Submit Material</button>
            </div>
        </form>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        function initProjectSearch() {
            const select = document.querySelector('select[id="projectSearch"]');
            if (!select || select.dataset.initialized) return;
            select.dataset.initialized = 'true';

            const wrapper = document.createElement('div');
            wrapper.className = 'relative';
            select.parentNode.insertBefore(wrapper, select);

            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search project...';
            searchInput.className = 'w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#00ADC5] focus:ring-4 focus:ring-[#00ADC5]/10 outline-none transition-all rounded-xl mb-1';
            wrapper.appendChild(searchInput);

            select.classList.add('hidden');
            wrapper.appendChild(select);

            const dropdown = document.createElement('div');
            dropdown.className = 'absolute z-50 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-40 overflow-y-auto hidden';
            dropdown.style.top = searchInput.offsetHeight + 4 + 'px';
            wrapper.appendChild(dropdown);

            function renderOptions(filter = '') {
                dropdown.innerHTML = '';
                const options = Array.from(select.options);
                const filterLower = filter.toLowerCase();

                options.forEach(function(option) {
                    if (!option.value) return;
                    const text = option.text.toLowerCase();
                    if (text.includes(filterLower)) {
                        const item = document.createElement('div');
                        item.className = 'px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 cursor-pointer';
                        item.textContent = option.text;
                        item.addEventListener('click', function() {
                            select.value = option.value;
                            searchInput.value = option.text;
                            dropdown.classList.add('hidden');
                        });
                        dropdown.appendChild(item);
                    }
                });

                if (dropdown.children.length === 0 && filter) {
                    const empty = document.createElement('div');
                    empty.className = 'px-4 py-2.5 text-sm font-bold text-slate-400';
                    empty.textContent = 'No projects found';
                    dropdown.appendChild(empty);
                }
            }

            searchInput.addEventListener('input', function() {
                renderOptions(this.value);
                dropdown.classList.remove('hidden');
            });

            searchInput.addEventListener('focus', function() {
                renderOptions(this.value);
                dropdown.classList.remove('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            if (select.value) {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption) {
                    searchInput.value = selectedOption.text;
                }
            }
        }

        initProjectSearch();
    </script>
</x-app-layout>
