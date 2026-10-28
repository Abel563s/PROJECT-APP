@include('admin.projects.risk-pool.spreadsheet', ['mode' => 'edit', 'risk' => $risk, 'categories' => \App\Models\ProjectRiskPool::availableCategories()])
