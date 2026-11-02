<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function create(User $user): bool
    {
        return $user->isSuperadmin() || ($user->isAdmin() && $user->business_unit_id !== null);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->canManageProject($project);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->isSuperadmin() || ($user->isAdmin() && $user->canManageProject($project));
    }
}
