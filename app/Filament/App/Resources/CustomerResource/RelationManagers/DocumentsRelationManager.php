<?php

namespace App\Filament\App\Resources\CustomerResource\RelationManagers;

use App\Models\Document;
use App\Models\Enums\DocumentStatusEnum;
use App\Models\Enums\DocumentTypeEnum;
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
                Forms\Components\TextInput::make('ref_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ref_number')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->width('150px')
                    ->badge()
                    ->color(fn (Document $document): string => match ($document->type) {
                        DocumentTypeEnum::RECEIPT => 'warning',
                        DocumentTypeEnum::INVOICE => 'success',
                        DocumentTypeEnum::QUOTE => 'danger',
                        default => 'info',
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_date')
                    ->date('d/m/Y')
                    ->width('150px')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ref_number')
                    ->width('150px')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gross_price')
                    ->width('150px')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->width('150px')
                    ->badge()
                    ->color(fn (Document $document): string => match ($document->status) {
                        DocumentStatusEnum::PAID => 'success',
                        DocumentStatusEnum::SENT => 'warning',
                        DocumentStatusEnum::CANCELLED => 'danger',
                        default => 'info',
                    })
                    ->sortable()
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
                Tables\Actions\ViewAction::make(),
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }
}
