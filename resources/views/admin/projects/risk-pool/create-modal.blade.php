@include('admin.projects.risk-pool.spreadsheet', [
    'mode'              => 'create',
    'risk'              => null,
    'categories'        => \App\Models\ProjectRiskPool::availableCategories(),
    'existingRisks'     => $existingRisks ?? collect(),
])
