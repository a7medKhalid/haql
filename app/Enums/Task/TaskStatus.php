<?php

namespace App\Enums\Task;

enum TaskStatus: string
{
    case InProgress = 'in_progress';
    case Finished = 'finished';

}
