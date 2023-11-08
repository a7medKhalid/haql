<?php

namespace App\Enums\Contribution;

enum ContributionStatus: string
{
    case Open = 'open';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Archived = 'archived';

}
