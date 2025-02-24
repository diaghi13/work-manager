<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorksiteResource\Pages;
use App\Filament\Resources\WorksiteResource\RelationManagers;
use App\Models\Customer;
use App\Models\Enums\WorksiteStatusEnum;
use App\Models\Enums\WorksiteTypeEnum;
use App\Models\Worksite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorksiteResource extends Resource
{
    protected static ?string $model = Worksite::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Work';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make(__('app.general'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('customer_id')
                            ->relationship(name: 'customer', titleAttribute: 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                if (!$get('daily_cost')) {
                                    $set('daily_cost', $get('customer_id') ? Customer::find($get('customer_id'))->daily_cost : null);
                                    $set('extra_time_cost', $get('customer_id') ? Customer::find($get('customer_id'))->extra_time_cost : null);
                                    $set('daily_hours', $get('customer_id') ? Customer::find($get('customer_id'))->daily_hours : null);
                                }
                            }),
                        Forms\Components\Select::make('type')
                            ->options(WorksiteTypeEnum::class)
                            ->default(WorksiteTypeEnum::TECHNICIAN)
                            ->live()
                            ->required(),
                        Forms\Components\DatePicker::make('start_date'),
                        Forms\Components\DatePicker::make('end_date'),
                        Forms\Components\Select::make('status')
                            ->options(WorksiteStatusEnum::class),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Fieldset::make(__('app.address'))
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255)
                            //->hidden(fn(Forms\Get $get) => $get('type') === WorksiteTypeEnum::TOUR)
                            ->hidden(fn(Forms\Get $get): bool => $get('type') === WorksiteTypeEnum::TOUR->value)
                            ->reactive()
                            ->columnSpanFull(),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('address')
                                    ->maxLength(255)
                                    ->hidden(fn(Forms\Get $get) => $get('type') === WorksiteTypeEnum::TOUR->value)
                                    ->columnSpan(4),
                                Forms\Components\TextInput::make('number')
                                    ->maxLength(255)
                                    ->hidden(fn(Forms\Get $get) => $get('type') === WorksiteTypeEnum::TOUR->value)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('zip_code')
                                    ->maxLength(255)
                                    ->hidden(fn(Forms\Get $get) => $get('type') === WorksiteTypeEnum::TOUR->value)
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull()
                            ->columns(6),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->maxLength(255)
                                    ->hidden(fn(Forms\Get $get) => $get('type') === WorksiteTypeEnum::TOUR->value)
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('province')
                                    ->maxLength(255)
                                    ->hidden(fn(Forms\Get $get) => $get('type') === WorksiteTypeEnum::TOUR->value)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('country')
                                    ->maxLength(255)
                                    ->hidden(fn(Forms\Get $get) => $get('type') === WorksiteTypeEnum::TOUR->value)
                                    ->columnSpan(2),
                            ])
                            ->columnSpanFull()
                            ->columns(6),

                    ])
                    ->columns(2)
                    ->hidden(fn(Forms\Get $get) => $get('type') === WorksiteTypeEnum::TOUR->value),
                Forms\Components\Fieldset::make(__('app.payment_conditions'))
                    ->schema([
                        Forms\Components\TextInput::make('daily_cost'),
                        Forms\Components\TextInput::make('extra_time_cost'),
                        Forms\Components\TextInput::make('daily_hours'),
                        Forms\Components\TextInput::make('daily_allowance'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with([
                    'work_days' => [
                        'worksite',
                        'outgoings',
                    ]
                ]);
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(Worksite $worksite): string => match ($worksite->type) {
                        WorksiteTypeEnum::TECHNICIAN => 'primary',
                        WorksiteTypeEnum::TOUR => 'info',
                        WorksiteTypeEnum::SERVICE => 'success',
                    })
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date('d/m/y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date('d/m/y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(Worksite $worksite): string => match ($worksite->status) {
                        WorksiteStatusEnum::COMPLETED, WorksiteStatusEnum::ACCEPTED => 'success',
                        WorksiteStatusEnum::REJECTED, WorksiteStatusEnum::CANCELLED => 'danger',
                        WorksiteStatusEnum::IN_PROGRESS => 'warning',
                        default => 'info',
                    })
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_remuneration')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_extra_cost')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_address'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable(),
            ])
            ->defaultSort('start_date', 'desc')
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
            RelationManagers\WorkDaysRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorksites::route('/'),
            'create' => Pages\CreateWorksite::route('/create'),
            'edit' => Pages\EditWorksite::route('/{record}/edit'),
        ];
    }
}
