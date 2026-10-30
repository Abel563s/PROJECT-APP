<x-app-layout>
    <div class="py-10 px-6 space-y-12 max-w-[1400px] mx-auto font-inter">

        <!-- Premium Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 border-b border-slate-100 pb-10">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center font-black text-2xl text-[#00ADC5] border-2 border-slate-100 shadow-sm">
                    {{ strtoupper(substr($material->material_code ?? 'MT', 0, 2)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h2 class="text-4xl font-black text-slate-900 tracking-tight font-outfit">
                            {{ $material->material_name }}
                        </h2>
                        <span class="px-3 py-1 bg-cyan-50 text-[#00ADC5] rounded-xl text-[10px] font-black uppercase tracking-widest">{{ $material->material_code }}</span>
                    </div>
                    <p class="text-slate-500 font-medium flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        {{ $material->supplier_name ?? 'No Supplier' }} | {{ $material->discipline ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperadmin())
                    <a href="{{ route('admin.material.edit', $material) }}"
                        class="px-8 py-3 bg-slate-800 text-white rounded-[1.25rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                        Edit Material
                    </a>
                @endif
                <form action="{{ route('admin.material.destroy', $material) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this material?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="p-4 bg-white border border-slate-100 text-rose-500 rounded-2xl hover:bg-rose-50 transition-all">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Material Information -->
                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-50 space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i data-lucide="info" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Material Detail</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Project</p>
                            <p class="text-sm font-black text-slate-900">{{ $material->project?->project_code ?? 'Not Assigned' }}</p>
                            <p class="text-[9px] font-bold text-[#00ADC5] uppercase tracking-tighter">{{ $material->project?->project_name ?? 'Not Assigned' }}</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Supplier</p>
                            <p class="text-sm font-black text-slate-900">{{ $material->supplier_name ?? 'Not Specified' }}</p>
                            <p class="text-[9px] font-bold text-[#00ADC5] uppercase tracking-tighter">{{ $material->procurement_type ?? 'N/A' }} Type</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Product</p>
                            <p class="text-sm font-black text-slate-900">{{ $material->procurement_product ?? 'Not Specified' }}</p>
                            <p class="text-[9px] font-bold text-[#00ADC5] uppercase tracking-tighter">{{ $material->supply_route ?? 'N/A' }} Route</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Discipline</p>
                            <p class="text-sm font-black text-slate-900">{{ $material->discipline ?? 'Not Specified' }}</p>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Approval</p>
                            <p class="text-sm font-black text-slate-900">{{ $material->approval ?? 'No' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline Section -->
                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-50 space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                            <i data-lucide="clock" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Procurement Timeline</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $dates = [
                                'Design Planned' => $material->design_planned,
                                'Design Actual' => $material->design_actual,
                                'Spec Preparation' => $material->spec_preparation,
                                'Request Order' => $material->request_order,
                                'Supplier Identification' => $material->supplier_identification,
                                'Material Submittal' => $material->material_submittal,
                                'Material Approval' => $material->material_approval,
                                'Finance' => $material->finance,
                                'LC' => $material->lc,
                                'Payment' => $material->payment,
                                'Production Comp' => $material->production_comp,
                                'Shipment of Material' => $material->shipment_of_material,
                                'Logistics to Dry Port' => $material->logistics_to_dry_port,
                                'Clearance of Material' => $material->clearance_of_material,
                                'Delivery to Site' => $material->delivery_to_site,
                                'Planned Start Date' => $material->planned_start_date,
                            ];
                        @endphp

                        @foreach($dates as $label => $d)
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                                <p class="text-sm font-black text-slate-900">{{ $d?->format('M d, Y') ?? '---' }}</p>
                            </div>
                        @endforeach

                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Production Days</p>
                            <p class="text-sm font-black text-slate-900">{{ $material->production_days ?? '---' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Shipment Days</p>
                            <p class="text-sm font-black text-slate-900">{{ $material->shipment_days ?? '---' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-10">
                <!-- Status Card -->
                <div class="bg-slate-800 p-8 rounded-[3rem] shadow-2xl shadow-slate-900/40 text-white space-y-6 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-[#00ADC5]/20 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Approval Status</span>
                        <span class="w-2 h-2 rounded-full bg-[#00ADC5] animate-pulse"></span>
                    </div>
                    <div class="relative z-10">
                        <h4 class="text-2xl font-black tracking-tight">{{ $material->approval ?? 'No' }}</h4>
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-1">Type: {{ $material->procurement_type ?? 'N/A' }}</p>
                    </div>

                    <div class="relative z-10 space-y-3">
                        <div class="flex items-center justify-between text-[10px] font-black text-white/60">
                            <span>SUPPLIER</span>
                            <span>{{ $material->supplier_name ?? 'UNSET' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] font-black text-white/60">
                            <span>ROUTE</span>
                            <span>{{ $material->supply_route ?? 'UNSET' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] font-black text-white/60">
                            <span>PRODUCT</span>
                            <span>{{ $material->procurement_product ?? 'UNSET' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Code Card -->
                <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-50 space-y-6">
                    <div class="space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Material Identity</p>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-50">
                                <div>
                                    <p class="text-xs font-black text-slate-900">{{ $material->material_code }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Material Code</p>
                                </div>
                            </div>

                            <div class="p-6 bg-slate-50 rounded-2xl text-center space-y-1">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Discipline</p>
                                <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $material->discipline ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
