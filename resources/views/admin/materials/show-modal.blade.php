<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-black text-[10px]">
                {{ strtoupper(substr($material->material_code ?? 'MT', 0, 2)) }}
            </div>
            <div>
                <h4 class="text-base font-black text-slate-900 leading-tight">{{ $material->material_name }}</h4>
                <p class="text-[9px] font-black text-[#00ADC5] uppercase tracking-widest">{{ $material->material_code }}</p>
            </div>
        </div>
        @if(auth()->user() && auth()->user()->isAdmin())
            <form action="{{ route('admin.material.destroy', $material) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this material?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                    Delete
                </button>
            </form>
        @endif
    </div>

    <!-- Section 1: Material Identity -->
    <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                <i data-lucide="package" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900">Material Identity</h4>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core details</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Material Code</p>
                <p class="text-xs font-black text-slate-900">{{ $material->material_code }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Project</p>
                <p class="text-xs font-black text-slate-900">{{ $material->project?->project_code ?? 'Not Assigned' }} - {{ $material->project?->project_name ?? 'Not Assigned' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Material Name</p>
                <p class="text-xs font-black text-slate-900">{{ $material->material_name }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Supplier</p>
                <p class="text-xs font-black text-slate-900">{{ $material->supplier_name ?? 'Not Specified' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Discipline</p>
                <p class="text-xs font-black text-slate-900">{{ $material->discipline ?? 'Not Specified' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Product</p>
                <p class="text-xs font-black text-slate-900">{{ $material->procurement_product ?? 'Not Specified' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Route</p>
                <p class="text-xs font-black text-slate-900">{{ $material->supply_route ?? 'Not Specified' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Approval</p>
                <p class="text-xs font-black text-slate-900">{{ $material->approval ?? 'No' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Procurement Type</p>
                <p class="text-xs font-black text-slate-900">{{ $material->procurement_type ?? 'Not Specified' }}</p>
            </div>
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
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Schedule dates</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
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
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                    <p class="text-xs font-black text-slate-900">{{ $d?->format('M d, Y') ?? '---' }}</p>
                </div>
            @endforeach

            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Production Days</p>
                <p class="text-xs font-black text-slate-900">{{ $material->production_days ?? '---' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Shipment Days</p>
                <p class="text-xs font-black text-slate-900">{{ $material->shipment_days ?? '---' }}</p>
            </div>
        </div>
    </div>
</div>
