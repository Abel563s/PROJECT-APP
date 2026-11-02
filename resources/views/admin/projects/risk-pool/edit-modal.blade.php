@include('admin.projects.risk-pool.spreadsheet', [
    'mode' => 'edit',
    'risk' => $risk,
    'categories' => \App\Models\ProjectRiskPool::availableCategories(),
    'canCreateRiskPool' => auth()->user()->canManageRiskPool('create'),
    'canEditRiskPool' => auth()->user()->canManageRiskPool('edit'),
    'canDeleteRiskPool' => auth()->user()->canManageRiskPool('delete'),
])
