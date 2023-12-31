<?php

namespace App\Filament\App\Resources\ProjectResource\Pages;

use App\Filament\App\Resources\ProjectResource;
use App\Services\RepoService;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }


    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadFiles')
                ->label(__('Download Files'))
                ->action(fn (Model $record) => $this->downloadFiles($record)),

        ];
    }


    private function downloadFiles(Model $project)
    {
        $RepoService = new RepoService($project->id);
        
        $file_path = $RepoService->download('master');

        return response()->download($file_path);

    }
}
