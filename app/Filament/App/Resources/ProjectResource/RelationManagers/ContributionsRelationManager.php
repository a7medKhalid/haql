<?php

namespace App\Filament\App\Resources\ProjectResource\RelationManagers;

use App\Forms\Components\FolderUpload;
use App\Models\Contribution;
use App\Services\RepoService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;

class ContributionsRelationManager extends RelationManager
{
    protected static string $relationship = 'contributions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(255),
                FolderUpload::make('files')
                    ->required()


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->using(
                    fn (Request $request, array $data, string $model) => $this->createContribution($request,$data, $model)
                ),
            ])
            ->actions([

            ])
            ->bulkActions([

            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    private function createContribution(Request $request, array $data, string $model) : Model
    {


        //get files from request
        $project = $this->getOwnerRecord();

        $contribution = Contribution::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'project_id' => $project->id,
            'contributor_id' => auth()->user()->id,
        ]);

        $RepoService = new RepoService($project->id);

        $commit_name = $RepoService->uploadFiles($contribution->id, $data['files']['folder']);

        $contribution->commit_name = $commit_name;
        $contribution->save();

        return $contribution;
    }

}
