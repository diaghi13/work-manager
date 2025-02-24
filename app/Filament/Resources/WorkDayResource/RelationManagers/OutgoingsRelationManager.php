<?php

namespace App\Filament\Resources\WorkDayResource\RelationManagers;

use App\Models\Enums\OutgoingTypeEnum;
use App\Models\Outgoing;
use App\Models\WorkDay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutgoingsRelationManager extends RelationManager
{
    protected static string $relationship = 'outgoings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options(OutgoingTypeEnum::class)
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('attachments')
                    ->multiple()
                    ->disk('local')
                    ->directory(fn(Outgoing $record) => "outgoings/{$record->id}")
                    ->reorderable()
                    ->downloadable()
                    ->previewable()
                    ->openable()
                    ->visibility('private')
                    ->storeFileNamesIn('original_filenames')
                /*->saveRelationshipsUsing(function (Forms\Components\FileUpload $component, array $state, Outgoing $record) {
                    foreach ($state as $file) {
                        $record->attachments()->create([
                            'filename' => $file->filename,
                            'title' => $file->title,
                            'slug' => $file->slug,
                            'mime_type' => $file->mime_type,
                            'path' => $file->path,
                            'size' => $file->size,
                            'disk' => $file->disk,
                        ]);
                    }
                }),*/
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
