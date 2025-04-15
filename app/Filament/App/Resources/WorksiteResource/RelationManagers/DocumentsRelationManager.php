<?php

namespace App\Filament\App\Resources\WorksiteResource\RelationManagers;

use App\Models\Enums\DocumentStatusEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->width('150px')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_date')
                    ->date('d/m/Y')
                    ->width('150px')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ref_number')
                    ->width('150px')
                    ->searchable(),
                Tables\Columns\TextColumn::make('net_price')
                    ->width('150px')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('gross_price')
                    ->width('150px')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('status')
                    ->width('150px')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('document_date', 'desc')
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
