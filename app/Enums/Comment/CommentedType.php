<?php

namespace App\Enums\Comment;

enum CommentedType: string
{
    case Comment = 'comment';
    case Project = 'project';
    case Issue = 'issue';
    case Contribution = 'contribution';

}
