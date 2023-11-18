<?php

namespace App\Models;

use App\Enums\Project\License;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $casts = [
        'license' => License::class,
    ];

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

}
