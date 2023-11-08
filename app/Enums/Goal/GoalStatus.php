<?php

namespace App\Enums\Goal;

enum GoalStatus: string
{
    case InProgress = 'in_progress';
    case Finished = 'finished';

}
