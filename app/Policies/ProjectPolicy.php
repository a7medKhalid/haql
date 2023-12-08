<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{


    public function view(User $user, Project $model)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Project $model)
    {
        return $model->owner_id === $user->id;
    }

    public function delete(User $user, Project $model)
    {
        return $model->owner_id === $user->id;
    }

}
