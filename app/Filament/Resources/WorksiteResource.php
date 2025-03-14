<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorksiteResource\Pages;
use App\Filament\Resources\WorksiteResource\RelationManagers;
use App\Helpers\DeviceHelper;
use App\Models\Customer;
use App\Models\Enums\WorksiteStatusEnum;
use App\Models\Enums\WorksiteTypeEnum;
use App\Models\Worksite;
use Filament\Actions\ReplicateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorksiteResource extends Resource
{
    use HasToggleableTable;

    protected static ?string $model = Worksite::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationGroup(): ?string
    {
        return __('app.navigation.work');
    }

    public static function getLabel(): ?string
    {
        return __('app.worksite.single');
    }

    /**
     * @return string|null
     */
    public static function getPluralLabel(): ?string
    {
        return __('app.worksite.plural');
    }

//    public static function getRecordSubNavigation(\Filament\Resources\Pages\Page $page): array
//    {
//        return $page->generateNavigationItems([
//            Pages\ViewWorksite::class,
//            Pages\EditWorksite::class,
//        ]);
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make(__('app.general'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('app.worksite.name'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('customer_id')
                            ->label(__('app.worksite.customer'))
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
                            ->label(__('app.worksite.job_type'))
                            ->options(WorksiteTypeEnum::class)
                            ->default(WorksiteTypeEnum::TECHNICIAN)
                            ->live()
                            ->required(),
                        Forms\Components\DatePicker::make('start_date')
                        ->label(__('app.worksite.start_date')),
                        Forms\Components\DatePicker::make('end_date')
                        ->label(__('app.worksite.end_date')),
                        Forms\Components\Select::make('status')
                            ->label(__('app.worksite.status'))
                            ->options(WorksiteStatusEnum::class),
                        Forms\Components\Textarea::make('notes')
                            ->label(__('app.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Fieldset::make(__('app.address'))
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->label(__('app.worksite.location'))
                            ->maxLength(255)
                            ->reactive()
                            ->columnSpanFull(),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('address')
                                    ->label(__('app.worksite.address'))
                                    ->maxLength(255)
                                    ->columnSpan(4),
                                Forms\Components\TextInput::make('number')
                                    ->label(__('app.worksite.number'))
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('zip_code')
                                    ->label(__('app.worksite.zip_code'))
                                    ->maxLength(255)
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull()
                            ->columns(6),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->label(__('app.worksite.city'))
                                    ->maxLength(255)
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('province')
                                    ->label(__('app.worksite.province'))
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('country')
                                    ->label(__('app.worksite.country'))
                                    ->maxLength(255)
                                    ->columnSpan(2),
                            ])
                            ->columnSpanFull()
                            ->columns(6),

                    ])
                    ->columns(2)
                    ->hidden(fn(Forms\Get $get) => is_string($get('type'))
                        ? $get('type') === WorksiteTypeEnum::TOUR->value
                        : $get('type')?->value === WorksiteTypeEnum::TOUR->value
                    ),
                Forms\Components\Fieldset::make(__('app.payment_conditions'))
                    ->schema([
                        Forms\Components\TextInput::make('daily_cost')
                        ->label(__('app.worksite.daily_cost')),
                        Forms\Components\TextInput::make('extra_time_cost')
                        ->label(__('app.worksite.extra_time_cost')),
                        Forms\Components\TextInput::make('daily_hours')
                        ->label(__('app.worksite.daily_hours')),
                        Forms\Components\TextInput::make('daily_allowance')
                        ->label(__('app.worksite.daily_allowance')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $livewire = $table->getLivewire();

        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with([
                    'work_days' => [
                        'worksite',
                        'outgoings',
                    ]
                ]);
            })
            ->columns(
                DeviceHelper::isMobile()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->defaultSort('start_date', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ReplicateAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
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
            'show' => Pages\ViewWorksite::route('/{record}'),
            'edit' => Pages\EditWorksite::route('/{record}/edit'),
        ];
    }

    public static function getListTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('app.worksite.name'))
                ->searchable(),
            Tables\Columns\TextColumn::make('type')
                ->label(__('app.worksite.job_type'))
                ->badge()
                ->color(fn(Worksite $worksite): string => match ($worksite->type) {
                    WorksiteTypeEnum::TECHNICIAN => 'primary',
                    WorksiteTypeEnum::TOUR => 'info',
                    WorksiteTypeEnum::SERVICE => 'success',
                })
                ->grow(false)
                ->extraAttributes([
                    'style' => 'min-width: 100px;',
                ])
                ->sortable()
                ->toggleable()
                ->searchable(),
            Tables\Columns\TextColumn::make('location')
                ->label(__('app.worksite.location'))
                ->grow(false)
                ->extraAttributes([
                    'style' => 'min-width: 200px;',
                ])
                ->searchable(),
            Tables\Columns\TextColumn::make('start_date')
                ->date('d/m/y')
                ->grow(false)
                ->sortable(),
            Tables\Columns\TextColumn::make('end_date')
                ->date('d/m/y')
                ->grow(false)
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn(Worksite $worksite): string => match ($worksite->status) {
                    WorksiteStatusEnum::COMPLETED => 'success',
                    WorksiteStatusEnum::REJECTED, WorksiteStatusEnum::CANCELLED => 'danger',
                    WorksiteStatusEnum::IN_PROGRESS => 'warning',
                    default => 'info',
                })
                ->grow(false)
                ->extraAttributes([
                    'style' => 'min-width: 100px;',
                ])
                ->sortable()
                ->toggleable()
                ->searchable(),
            Tables\Columns\TextColumn::make('total_remuneration')
                ->money('EUR')
                ->sortable(),
            Tables\Columns\TextColumn::make('total_extra_cost')
                ->money('EUR')
                ->sortable(),
            Tables\Columns\TextColumn::make('remaining_allowance')
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
        ];
    }

    public static function getGridTableColumns(): array
    {
        return [
            Tables\Columns\Layout\Split::make([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(Worksite $worksite): string => match ($worksite->type) {
                        WorksiteTypeEnum::TECHNICIAN => 'primary',
                        WorksiteTypeEnum::TOUR => 'info',
                        WorksiteTypeEnum::SERVICE => 'success',
                    })
                    ->grow(false)
                    ->extraAttributes([
                        'style' => 'min-width: 100px;',
                    ])
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->grow(false)
                    ->extraAttributes([
                        'style' => 'min-width: 200px;',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date('d/m/y')
                    ->grow(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date('d/m/y')
                    ->grow(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(Worksite $worksite): string => match ($worksite->status) {
                        WorksiteStatusEnum::COMPLETED, WorksiteStatusEnum::ACCEPTED => 'success',
                        WorksiteStatusEnum::REJECTED, WorksiteStatusEnum::CANCELLED => 'danger',
                        WorksiteStatusEnum::IN_PROGRESS => 'warning',
                        default => 'info',
                    })
                    ->grow(false)
                    ->extraAttributes([
                        'style' => 'min-width: 100px;',
                    ])
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
            ])
                ->from('md'),
            Tables\Columns\Layout\Panel::make([
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
                Tables\Columns\TextColumn::make('daily_cost')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('extra_time_cost')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('daily_hours'),
                Tables\Columns\TextColumn::make('daily_allowance')
                    ->money('EUR'),
            ])->collapsible(),
        ];
    }
}
