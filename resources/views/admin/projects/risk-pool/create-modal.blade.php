@include('admin.projects.risk-pool.spreadsheet', [
    'mode'              => 'create',
    'risk'              => null,
    'categories'        => \App\Models\ProjectRiskPool::availableCategories(),
    'existingRisks'     => $existingRisks ?? collect(),
    'canCreateRiskPool' => $canCreateRiskPool ?? auth()->user()->canManageRiskPool('create'),
    'canEditRiskPool'   => $canEditRiskPool ?? auth()->user()->canManageRiskPool('edit'),
    'canDeleteRiskPool' => $canDeleteRiskPool ?? auth()->user()->canManageRiskPool('delete'),
])
