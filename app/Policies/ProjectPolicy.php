<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    // Policies must be registered in the AuthServiceProvider
    use HandlesAuthorization;

    public function update(User $user, Project $project)
    {
        return $user->is($project->owner);
    }
}
