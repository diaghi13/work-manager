<?php

namespace App\Filament\App\Resources\DocumentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentRowsRelationManager extends RelationManager
{
    protected static string $relationship = 'document_rows';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->type('number'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->type('number'),
                Forms\Components\TextInput::make('vat')
                    ->required()
                    ->type('number'),

                Forms\Components\TextInput::make('total')
                    ->required()
                    ->type('number'),
            ])
            ->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_id')
            ->columns([
                Tables\Columns\TextColumn::make('document_id'),
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
