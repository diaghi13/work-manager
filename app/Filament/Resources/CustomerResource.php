<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\WorksitesRelationManager;
use App\Models\Customer;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        CreateAction::make()
            ->successRedirectUrl('customer.index');

        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\TextInput::make('vat_code')
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('vat_id')
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('sdi_code')
                            ->columnSpan(2),
                    ])
                    ->columns(6),
                Forms\Components\Fieldset::make('Indirizzo')
                    ->schema([
                        Forms\Components\TextInput::make('street')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('number')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('city')
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('zip_code')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('state'),
                        Forms\Components\TextInput::make('country')
                            ->default('Italia')
                            ->columnSpan(2),
                    ])
                    ->columns(6),
                Forms\Components\Fieldset::make('Condizioni di pagamento')
                    ->schema([
                        Forms\Components\TextInput::make('daily_cost'),
                        Forms\Components\TextInput::make('extra_time_cost'),
                        Forms\Components\TextInput::make('daily_hours'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('vat_code'),
                Tables\Columns\TextColumn::make('vat_id'),
                Tables\Columns\TextColumn::make('street'),
                Tables\Columns\TextColumn::make('number'),
                Tables\Columns\TextColumn::make('zip_code'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\TextColumn::make('sdi_code'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            WorksitesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            //'show' => Pages\EditCustomer::route('/{record}/show'),
        ];
    }
}
