<x-app-layout>
    <div class="py-4 px-4 space-y-4 max-w-[1700px] mx-auto font-inter">

        <!-- Compact Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.closeout.index') }}" 
                   class="w-9 h-9 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#00ADC5] hover:border-[#00ADC5]/20 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit leading-none">
                        Project <span class="font-black text-slate-900">Closeout</span>
                    </h2>
                    <p class="text-[8px] font-black text-[#00ADC5] uppercase tracking-[0.3em] mt-1">{{ $project->project_name }} - {{ $project->custom_id }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="window.print()" 
                        class="px-4 py-2 bg-white border border-slate-100 text-slate-600 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-slate-50 active:scale-95 shadow-sm">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="printer" class="w-3.5 h-3.5 text-[#00ADC5]"></i>
                        Print
                    </span>
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Card 1: Project Identity -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Asset Identity</p>
                    <h4 class="text-sm font-black text-slate-900 leading-tight">{{ $project->project_name }}</h4>
                    <p class="text-[9px] font-bold text-indigo-500 uppercase mt-0.5">{{ $project->project_client }}</p>
                </div>
            </div>

            <!-- Card 2: Financial Magnitude -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i data-lucide="banknote" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Final Contract Sum</p>
                    <h4 class="text-sm font-black text-slate-900 leading-tight">ETB {{ number_format($project->total_project_value) }}</h4>
                    <p class="text-[9px] font-bold text-emerald-500 uppercase mt-0.5">Total Reconciliation</p>
                </div>
            </div>

            <!-- Card 3: Payment Status -->
            <div class="bg-white p-4 rounded-2xl border border-[#00ADC5]/20 shadow-lg shadow-cyan-50 space-y-2 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-[#00ADC5]/5 rounded-full blur-2xl"></div>
                <div class="w-10 h-10 rounded-xl bg-[#e6f7fa] flex items-center justify-center text-[#00ADC5]">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Outstanding Balance</p>
                    <h4 class="text-sm font-black text-slate-900 leading-tight">ETB {{ number_format($project->outstanding_balance) }}</h4>
                    <p class="text-[9px] font-bold text-[#00ADC5] uppercase mt-0.5">Total Balance</p>
                </div>
            </div>

            <!-- Card 4: Execution Progress -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
                <div class="space-y-2">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Physical Completion</p>
                    <div class="flex items-end justify-between">
                        <h4 class="text-sm font-black text-slate-900 leading-none">{{ number_format($project->physical_completion_percent) }}%</h4>
                        <span class="text-[8px] font-black text-slate-400">{{ $project->physical_completion_percent >= 100 ? 'FINALIZED' : 'ACTIVE' }}</span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-[#00ADC5] rounded-full" style="width: {{ $project->physical_completion_percent }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            
            <!-- SECTION 2: Project Information -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center text-slate-900">
                        <i data-lucide="info" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Project Metadata</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Operational registry details</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                    <div class="space-y-0.5">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Project Name</p>
                        <p class="text-xs font-black text-slate-900">{{ $project->project_name }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Client Node</p>
                        <p class="text-xs font-black text-slate-900">{{ $project->project_client }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Asset Location</p>
                        <p class="text-xs font-black text-slate-900">{{ $project->location ?: 'Not Listed' }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Lead Manager</p>
                        <p class="text-xs font-black text-slate-900">{{ $project->project_manager ?: 'Pending Assignment' }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Reconnaissance Start</p>
                        <p class="text-xs font-black text-slate-900">{{ $project->actual_start_date ? $project->actual_start_date->format('M d, Y') : '---' }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Final Handover</p>
                        <p class="text-xs font-black text-slate-900">{{ $project->actual_finish_date ? $project->actual_finish_date->format('M d, Y') : '---' }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Protocol Duration</p>
                        <p class="text-xs font-black text-slate-900">{{ $project->actual_duration }} Days</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Current Status</p>
                        <span class="inline-flex px-2 py-0.5 bg-cyan-50 text-[#00ADC5] rounded-md text-[9px] font-black uppercase tracking-wider mt-0.5">{{ $project->closing_status }}</span>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Financial Summary -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Financial Audit</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Reconciliation of capital deployment</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- A. Contract Details -->
                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-1.5">Contract Details</p>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="font-medium text-slate-500">Original Contract Sum</span>
                            <span class="font-black text-slate-900">ETB {{ number_format($project->contract_budget) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="font-medium text-slate-500">Variations & Supplementary</span>
                            <span class="font-black text-emerald-500">+ ETB {{ number_format($project->variation + $project->supplementary) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Revised Contract Sum</span>
                            <span class="text-sm font-black text-slate-900">ETB {{ number_format($project->total_project_value) }}</span>
                        </div>
                    </div>

                    <!-- B. Payment Summary -->
                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-1.5">Payment Summary</p>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="font-medium text-slate-500">Total Certified</span>
                            <span class="font-black text-slate-900">ETB {{ number_format($project->total_certified) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-rose-500">
                            <span class="font-medium">Retention (Held)</span>
                            <span class="font-black">- ETB {{ number_format($project->retention) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-[#00ADC5]">
                            <span class="font-bold">Total Paid to Date</span>
                            <span class="font-black">ETB {{ number_format($project->total_paid) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-900 rounded-xl text-white shadow-lg">
                            <span class="text-[10px] font-black uppercase tracking-widest text-white/50">Outstanding Balance</span>
                            <span class="text-sm font-black text-white">ETB {{ number_format($project->outstanding_balance) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Technical Completion -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i data-lucide="check-square" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Technical Protocol</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Engineering and handover states</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-[9px] font-black text-slate-400 uppercase tracking-widest">
                            <span>Physical Progress Baseline</span>
                            <span class="text-[#00ADC5]">{{ number_format($project->physical_completion_percent) }}% COMPREHENSIVE</span>
                        </div>
                        <div class="h-2 w-full bg-white rounded-full overflow-hidden border border-slate-100">
                            <div class="h-full bg-gradient-to-r from-[#00ADC5] to-blue-500 rounded-full" style="width: {{ $project->physical_completion_percent }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 border border-slate-50 rounded-xl space-y-0.5">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Handover Date</p>
                            <p class="text-xs font-black text-slate-900">{{ $project->handover_date ? $project->handover_date->format('M d, Y') : 'PENDING' }}</p>
                        </div>
                        <div class="p-3 border border-slate-50 rounded-xl space-y-0.5">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Liability Period</p>
                            <p class="text-xs font-black text-slate-900">{{ $project->defects_liability_period ?: 'Not Defined' }}</p>
                        </div>
                        <div class="p-3 border border-slate-50 rounded-xl space-y-1">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Snag List</p>
                            @php
                                $snagColors = [
                                    'Pending' => 'bg-amber-100 text-amber-600',
                                    'In Progress' => 'bg-indigo-100 text-indigo-600',
                                    'Completed' => 'bg-emerald-100 text-emerald-600',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $snagColors[$project->snag_list_status] ?? 'bg-slate-100' }}">
                                {{ $project->snag_list_status }}
                            </span>
                        </div>
                        <div class="p-3 border border-slate-50 rounded-xl space-y-1">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Completion Cert</p>
                            @php
                                $certColors = [
                                    'Pending' => 'bg-rose-100 text-rose-600',
                                    'Issued' => 'bg-emerald-100 text-emerald-600',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $certColors[$project->completion_certificate_status] ?? 'bg-slate-100' }}">
                                {{ $project->completion_certificate_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Document Checklist -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                        <i data-lucide="folder-check" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Dossier Checklist</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Verification of final protocol documentation</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                <th class="pb-2">Document</th>
                                <th class="pb-2">Status</th>
                                <th class="pb-2 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach([
                                'Completion Certificate' => 'doc_completion_cert_status',
                                'As-Built Drawings' => 'doc_as_built_status',
                                'Final BOQ' => 'doc_final_boq_status',
                                'Handover Report' => 'doc_handover_report_status'
                            ] as $label => $field)
                            <tr class="group">
                                <td class="py-3 font-bold text-xs text-slate-700">{{ $label }}</td>
                                <td class="py-3">
                                    @php
                                        $docStatus = $project->$field;
                                        $docColors = [
                                            'Uploaded' => 'bg-emerald-50 text-emerald-600',
                                            'Pending' => 'bg-amber-50 text-amber-600',
                                            'Approved' => 'bg-indigo-50 text-indigo-600',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $docColors[$docStatus] ?? 'bg-slate-50 text-slate-400' }}">
                                        {{ $docStatus }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    @if($docStatus == 'Uploaded' || $docStatus == 'Approved')
                                        <button class="text-[9px] font-black text-[#00ADC5] uppercase tracking-wider hover:underline">View</button>
                                    @else
                                        <button class="text-[9px] font-black text-slate-400 uppercase tracking-wider hover:text-[#00ADC5]">Upload</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 6: Lessons Learned -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white">
                        <i data-lucide="lightbulb" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Intelligence & Lessons Learned</h3>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Post-operational analysis</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-2">
                        <div class="flex items-center gap-1.5 text-[9px] font-black text-rose-500 uppercase tracking-widest">
                            <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                            Challenges
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-[10px] font-medium text-slate-600 leading-relaxed">
                            {{ $project->challenges_faced ?: 'No significant operational challenges were recorded.' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-1.5 text-[9px] font-black text-indigo-500 uppercase tracking-widest">
                            <i data-lucide="dollar-sign" class="w-3 h-3"></i>
                            Financial Performance
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-[10px] font-medium text-slate-600 leading-relaxed">
                            {{ $project->financial_performance_notes ?: 'Financial deployment remained within established baseline variances.' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-1.5 text-[9px] font-black text-amber-500 uppercase tracking-widest">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            Schedule Performance
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-[10px] font-medium text-slate-600 leading-relaxed">
                            {{ $project->schedule_performance_notes ?: 'Project timeline was reconciled with no critical path deviations.' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-1.5 text-[9px] font-black text-[#00ADC5] uppercase tracking-widest">
                            <i data-lucide="sparkles" class="w-3 h-3"></i>
                            Recommendations
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-[10px] font-medium text-slate-600 leading-relaxed">
                            {{ $project->recommendations ?: 'Operational logic should be archived for future protocol replication.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .bg-slate-50, .bg-indigo-50, .bg-emerald-50, .bg-cyan-50, .bg-amber-50 { background-color: #f8fafc !important; }
            .shadow-sm, .shadow-lg, .shadow-xl { box-shadow: none !important; }
            .rounded-2xl, .rounded-xl { border-radius: 0.5rem !important; }
        }
    </style>
    @endpush
</x-app-layout>
