<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperadmin();
    }

    public function update(User $user, Project $project): bool
    {
        return $user->canManageProject($project);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin() || $user->isSuperadmin();
    }
}
