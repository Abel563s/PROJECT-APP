<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 pb-12 animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.material.show', $material) }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-900">Edit Material Timeline</h1>
                    <p class="text-slate-500">Update timeline dates and day counts for {{ $material->material_name }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6">
            <form action="{{ route('admin.material.update', $material) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="material_code" value="{{ $material->material_code }}">

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Project</label>
                    <select name="project_id" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-[#00ADC5] outline-none transition-all">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $material->project_id) == $project->id ? 'selected' : '' }}>
                                {{ $project->project_code }} - {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @foreach(['design_planned','design_actual','spec_preparation','request_order','supplier_identification','material_submittal','material_approval','finance','lc','production_comp','shipment_of_material','logistics_to_dry_port','clearance_of_material','delivery_to_site','planned_start_date'] as $field)
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">{{ ucwords(str_replace('_',' ', $field)) }}</label>
                        <input type="date" name="{{ $field }}" value="{{ old($field, optional($material->{$field})->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-[#00ADC5] outline-none transition-all">
                    </div>
                @endforeach

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Production Days</label>
                    <input type="number" name="production_days" value="{{ old('production_days', $material->production_days) }}" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-[#00ADC5] outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Shipment Days</label>
                    <input type="number" name="shipment_days" value="{{ old('shipment_days', $material->shipment_days) }}" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:border-[#00ADC5] outline-none transition-all">
                </div>

                <div class="md:col-span-2 flex justify-end gap-3 mt-4">
                    <a href="{{ route('admin.material.show', $material) }}" class="px-6 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition-all font-bold text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#00ADC5] text-white hover:bg-[#0099b3] transition-all font-bold text-sm shadow-lg shadow-[#00ADC5]/20">Save Timeline</button>
                </div>
            </form>
        </div>
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
            const select = document.querySelector('select[name="project_id"]');
            if (!select || select.dataset.initialized) return;
            select.dataset.initialized = 'true';

            const wrapper = document.createElement('div');
            wrapper.className = 'relative md:col-span-2';
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
