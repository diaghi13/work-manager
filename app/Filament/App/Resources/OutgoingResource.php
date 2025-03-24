<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\OutgoingResource\Pages;
use App\Filament\Resources\OutgoingResource\RelationManagers;
use App\Models\Enums\OutgoingTypeEnum;
use App\Models\Outgoing;
use App\Models\WorkDay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OutgoingResource extends Resource
{
    protected static ?string $model = Outgoing::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    public static function form(Form $form): Form
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
                Forms\Components\Select::make('work_day')
                    ->options(fn () => WorkDay::all()->pluck('date', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('work_day.date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('work_day.worksite.name')
                    ->label('Worksite')
                    ->sortable(),
                Tables\Columns\TextColumn::make('work_day.description')
                    ->label('Work day description')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('work_day.date', 'desc')
            ->defaultSort('type')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\OutgoingResource\Pages\ListOutgoings::route('/'),
            'create' => \App\Filament\App\Resources\OutgoingResource\Pages\CreateOutgoing::route('/create'),
            'view' => \App\Filament\App\Resources\OutgoingResource\Pages\ViewOutgoing::route('/{record}'),
            'edit' => \App\Filament\App\Resources\OutgoingResource\Pages\EditOutgoing::route('/{record}/edit'),
        ];
    }
}
