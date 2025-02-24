<?php

namespace App\Filament\Resources\WorksiteResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkDaysRelationManager extends RelationManager
{
    protected static string $relationship = 'work_days';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('date')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->modifyQueryUsing(function (Builder $query) {
                $query->with(['outgoings']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Start')
                    ->dateTime('d/m/y H:i')
                    ->sortable(),
//                Tables\Columns\TextColumn::make('end_date_time')
//                    ->label('End')
//                    ->dateTime('d/m/y H:i')
//                    ->sortable(),
                Tables\Columns\TextColumn::make('total_hours'),
                Tables\Columns\TextColumn::make('extra_time'),
                Tables\Columns\TextColumn::make('travel_cost')
                    ->money('EUR')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('meal_cost')
                    ->money('EUR')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('extra_cost')
                    ->money('EUR')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('calculate_extra_cost')
                    ->label('Extra Time')
                    ->toggleable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('total_remuneration')
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_extra_cost')
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('worksite.name')
                    ->label('Worksite')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable()
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
            ->defaultSort('date', 'desc')
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
                //
            ]);
    }
}
