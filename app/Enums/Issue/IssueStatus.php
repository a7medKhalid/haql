<?php

namespace App\Enums\Issue;

enum IssueStatus: string
{
    case Open = 'open';
    case Closed = 'closed';

}
