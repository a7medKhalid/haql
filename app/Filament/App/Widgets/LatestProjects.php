<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\ProjectResource;
use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestProjects extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn() => Project::query()
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                    Tables\Columns\TextColumn::make('name')
                        ->label(__('Name'))
                        ->searchable()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('description')
                        ->label(__('Description'))
                        ->limit(30)
                        ->searchable()
                        ->sortable(),
                ]
            )->recordUrl(fn(Project $project) => ProjectResource\Pages\ViewProject::getUrl([$project]));
    }
}
