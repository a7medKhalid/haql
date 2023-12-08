<?php

namespace App\Filament\App\Resources;

use App\Enums\Project\License;
use App\Filament\App\Resources\ProjectResource\Pages;
use App\Filament\App\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): ?string
    {
        return __('Project');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Projects');
    }

//    public static function getEloquentQuery(): Builder
//    {
//        return self::$model::query()->where('owner_id', auth()->user()->id);
//    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('name')
                ->label(__('Name')),
            TextEntry::make('description')
                ->label(__('Description')),
            TextEntry::make('license')
                ->label(__('License')),
        ]);

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->autofocus()
                    ->required()
                    ->placeholder(__('Name')),
                Forms\Components\Textarea::make('description')
                    ->label(__('Description'))
                    ->required()
                    ->placeholder(__('Description')),
                Forms\Components\TextInput::make('license')
                    ->label(__('License'))
                    ->placeholder(__('License'))
                    ->disabled()
                    ->default(License::Waqf),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ContributionsRelationManager::class,
            RelationManagers\IssuesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }




}
