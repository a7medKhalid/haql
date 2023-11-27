<?php

namespace App\Filament\App\Resources\ProjectResource\Pages;

use App\Filament\App\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    public function afterCreate()
    {

        $project = $this->getRecord();
        $repo = new \App\Services\RepoService($project->id);
        $repo->create();
    }
}
