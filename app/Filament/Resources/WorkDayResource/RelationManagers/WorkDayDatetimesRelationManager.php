<?php

namespace App\Filament\Resources\WorkDayResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkDayDatetimesRelationManager extends RelationManager
{
    protected static string $relationship = 'work_day_datetimes';

    public function form(Form $form): Form
    {
        $date = (new Carbon($this->getOwnerRecord()->date))->format('Y-m-d');

        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('start_datetime')
                    ->default($date . ' 09:00:00')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        if (!$get('end_datetime')) {
                            $set('end_datetime', Carbon::make($get('start_datetime'))->addHours(9)->format('Y-m-d H:i:s'));
                        }
                    }),
                Forms\Components\DateTimePicker::make('end_datetime')
                    ->default($date . ' 18:00:00')
                    ->required()
                    ->reactive(),
                Forms\Components\Textarea::make('notes')->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('start_datetime')
            ->columns([
                Tables\Columns\TextColumn::make('start_datetime')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('end_datetime')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('notes'),
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
