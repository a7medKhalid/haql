<?php

namespace App\Filament\App\Resources\ProjectResource\RelationManagers;

use App\Enums\Contribution\ContributionStatus;
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
                Tables\Columns\TextColumn::make('status'),



            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->options(fn() => array_column(ContributionStatus::cases(), 'value')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->using(
                    fn (Request $request, array $data, string $model) => $this->createContribution($request,$data, $model)
                ),
            ])
            ->actions([
                Tables\Actions\Action::make('downloadFiles')
                    ->label('Download Files')
                    ->action(fn (Model $record) => $this->downloadFiles($record)),
                Tables\Actions\Action::make('merge')
                    ->label('Merge')
                    ->action(fn (Model $record) => $this->merge($record)),

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

        $commit_name = $RepoService->uploadFiles($contribution->id, $data['files']);

        $contribution->commit_name = $commit_name;
        $contribution->save();

        return $contribution;
    }


    private function downloadFiles(Model $record)
    {
        $project = $this->getOwnerRecord();
        $RepoService = new RepoService($project->id);
        $RepoService->download($record->id);

        $file_path = $RepoService->download($record->id);

        return response()->download($file_path);

    }

    private function merge($contribution)
    {
        $project = $this->getOwnerRecord();
        $RepoService = new RepoService($project->id);
        $RepoService->merge($contribution->id);

        $contribution->update([
            'status' => ContributionStatus::Accepted,
        ]);

    }


}
