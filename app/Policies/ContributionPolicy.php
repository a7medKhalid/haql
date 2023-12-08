<?php

namespace App\Policies;

use App\Enums\Contribution\ContributionStatus;
use App\Models\Contribution;
use App\Models\User;

class ContributionPolicy
{
    public function merge(User $user, Contribution $model)
    {
        return $model->project->owner_id === $user->id && $model->status === ContributionStatus::Open->value;
    }
}
