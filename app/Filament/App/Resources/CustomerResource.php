<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CustomerResource\RelationManagers\DocumentsRelationManager;
use App\Filament\App\Resources\CustomerResource\RelationManagers\WorksitesRelationManager;
use App\Filament\Resources\CustomerResource\Pages;
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

    public static function getLabel(): ?string
    {
        return __('app.customers.drawer_label');
    }

    /**
     * @return string|null
     */
    public static function getPluralLabel(): ?string
    {
        return __('app.customers.drawer_label');
    }

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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('app.customers.name')),
                Tables\Columns\TextColumn::make('vat_code')
                    ->label(__('app.customers.vat_code')),
                Tables\Columns\TextColumn::make('vat_id')
                    ->label(__('app.customers.vat_id')),
                Tables\Columns\TextColumn::make('street')
                    ->label(__('app.customers.street')),
                Tables\Columns\TextColumn::make('number')
                    ->label(__('app.customers.number')),
                Tables\Columns\TextColumn::make('zip_code')
                    ->label(__('app.customers.zip_code')),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('app.customers.city')),
                Tables\Columns\TextColumn::make('state')
                    ->label(__('app.customers.state')),
                Tables\Columns\TextColumn::make('country')
                    ->label(__('app.customers.country')),
                Tables\Columns\TextColumn::make('sdi_code')
                    ->label(__('app.customers.sdi_code')),
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
            WorksitesRelationManager::class,
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\CustomerResource\Pages\ListCustomers::route('/'),
            'create' => \App\Filament\App\Resources\CustomerResource\Pages\CreateCustomer::route('/create'),
            'edit' => \App\Filament\App\Resources\CustomerResource\Pages\EditCustomer::route('/{record}/edit'),
            //'show' => Pages\EditCustomer::route('/{record}/show'),
        ];
    }
}
