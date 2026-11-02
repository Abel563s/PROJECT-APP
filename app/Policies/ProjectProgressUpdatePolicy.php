<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectProgressUpdate;
use App\Models\User;

class ProjectProgressUpdatePolicy
{
    public function create(User $user, Project $project): bool
    {
        return $user->canManageProject($project);
    }

    public function update(User $user, ProjectProgressUpdate $progressUpdate): bool
    {
        return $user->canManageProject($progressUpdate->project);
    }

    public function delete(User $user, ProjectProgressUpdate $progressUpdate): bool
    {
        return $user->isSuperadmin() || ($user->isAdmin() && $user->canManageProject($progressUpdate->project));
    }
}
