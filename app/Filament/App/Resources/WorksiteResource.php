<?php

namespace App\Filament\App\Resources;

use App\Filament\Resources\WorksiteResource\Pages;
use App\Filament\Resources\WorksiteResource\RelationManagers;
use App\Helpers\DeviceHelper;
use App\Models\Customer;
use App\Models\Enums\WorksitePaymentStatusEnum;
use App\Models\Enums\WorksiteStatusEnum;
use App\Models\Enums\WorksiteTypeEnum;
use App\Models\Worksite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

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
                    ],
                    'documents'
                ]);
            })
            ->columns(
                DeviceHelper::isMobile()
                    ? static::getGridTableColumns()
                    : static::getListTableColumns()
            )
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('customer')
                    ->label(__('app.worksite.customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->placeholder(__('app.all')),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('app.worksite.status'))
                    ->options(WorksiteStatusEnum::class)
                    ->multiple()
                    ->placeholder(__('app.all')),
                Tables\Filters\Filter::make('start_date')
                    ->label(__('app.worksite.start_date'))
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label(__('app.worksite.start_date'))
                            ->placeholder(__('app.worksite.start_date')),
                        Forms\Components\DatePicker::make('end_date')
                            ->label(__('app.worksite.end_date'))
                            ->placeholder(__('app.worksite.end_date')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        switch (true) {
                            case $data['start_date'] && $data['end_date']:
                                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']]);
                                break;
                            case $data['start_date'] && !$data['end_date']:
                                $query->where('start_date', '>=', $data['start_date']);
                                break;
                            case !$data['start_date'] && $data['end_date']:
                                $query->where('start_date', '<=', $data['end_date']);
                                break;
                            case !$data['start_date'] && !$data['end_date']:
                                return;
                        }
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['start_date'] && $data['end_date']) {
                            $indicators['start_date'] = __('app.worksite.start_date') . ': ' . Carbon::make($data['start_date'])->format('d/m/Y') . ' - ' . Carbon::make($data['end_date'])->format('d/m/Y');
                        } elseif ($data['start_date']) {
                            $indicators['start_date'] = __('app.worksite.start_date') . ': ' . Carbon::make($data['start_date'])->format('d/m/Y');
                        } elseif ($data['end_date']) {
                            $indicators['end_date'] = __('app.worksite.end_date') . ': ' . Carbon::make($data['end_date'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('app.worksite.job_type'))
                    ->options(WorksiteTypeEnum::class)
                    ->multiple()
                    ->placeholder(__('app.all')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                ...(DeviceHelper::isMobile() ? [] : [
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('app.general'))
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('app.worksite.name'))
                            ->columnSpanFull(),
                        TextEntry::make('customer.name')
                            ->label(__('app.worksite.customer')),
                        TextEntry::make('type')
                            ->label(__('app.worksite.job_type')),
                        TextEntry::make('start_date')
                            ->label(__('app.worksite.start_date'))
                            ->date('d/m/y'),
                        TextEntry::make('end_date')
                            ->label(__('app.worksite.end_date'))
                            ->date('d/m/y'),
                        TextEntry::make('status')
                            ->label(__('app.worksite.status')),
                        //->options(WorksiteStatusEnum::class),
                        TextEntry::make('notes')
                            ->label(__('app.notes'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make(__('app.address'))
                    ->schema([
                        TextEntry::make('location')
                            ->label(__('app.worksite.location'))
                            ->columnSpanFull(),
                        Group::make()
                            ->schema([
                                TextEntry::make('address')
                                    ->label(__('app.worksite.address'))
                                    ->columnSpan(4),
                                TextEntry::make('number')
                                    ->label(__('app.worksite.number'))
                                    ->columnSpan(1),
                                TextEntry::make('zip_code')
                                    ->label(__('app.worksite.zip_code'))
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull()
                            ->columns(6),
                        Group::make()
                            ->schema([
                                TextEntry::make('city')
                                    ->label(__('app.worksite.city'))
                                    ->columnSpan(3),
                                TextEntry::make('province')
                                    ->label(__('app.worksite.province'))
                                    ->columnSpan(1),
                                TextEntry::make('country')
                                    ->label(__('app.worksite.country'))
                                    ->columnSpan(2),
                            ])
                            ->columnSpanFull()
                            ->columns(6),

                    ])
                    ->columns(2)
                    ->hidden(fn(Worksite $record) => $record->type === WorksiteTypeEnum::TOUR)
                    ->collapsible(),
                Section::make(__('app.payment_conditions'))
                    ->schema([
                        TextEntry::make('daily_cost')
                            ->label(__('app.worksite.daily_cost'))
                            ->money('EUR'),
                        TextEntry::make('extra_time_cost')
                            ->label(__('app.worksite.extra_time_cost'))
                            ->money('EUR'),
                        TextEntry::make('daily_hours')
                            ->label(__('app.worksite.daily_hours'))
                            ->money('EUR'),
                        TextEntry::make('daily_allowance')
                            ->label(__('app.worksite.daily_allowance'))
                            ->money('EUR'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\App\Resources\WorksiteResource\RelationManagers\WorkDaysRelationManager::class,
            \App\Filament\App\Resources\WorksiteResource\RelationManagers\DocumentsRelationManager::class,
            \App\Filament\App\Resources\WorksiteResource\RelationManagers\OutgoingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\WorksiteResource\Pages\ListWorksites::route('/'),
            'create' => \App\Filament\App\Resources\WorksiteResource\Pages\CreateWorksite::route('/create'),
            'view' => \App\Filament\App\Resources\WorksiteResource\Pages\ViewWorksite::route('/{record}'),
            'edit' => \App\Filament\App\Resources\WorksiteResource\Pages\EditWorksite::route('/{record}/edit'),
        ];
    }

    public static function getListTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('app.worksite.name'))
                ->sortable()
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
            Tables\Columns\TextColumn::make('payment_status')
                ->getStateUsing(fn(Worksite $record) =>
                    $record->documents->first()?->pivot->worksite_payment_status ?? WorksitePaymentStatusEnum::NOT_PAID
                )
                ->badge()
                ->color(fn(Worksite $worksite): string => match ($worksite->documents->first()?->pivot->worksite_payment_status ?? WorksitePaymentStatusEnum::NOT_PAID) {
                    WorksitePaymentStatusEnum::PAID => 'success',
                    WorksitePaymentStatusEnum::PENDING => 'primary',
                    WorksitePaymentStatusEnum::NOT_PAID => 'danger',
                    WorksitePaymentStatusEnum::PARTIALLY_PAID => 'warning',
                    default => 'info',
                }),
//                ->badge()
//                ->color(fn(Worksite $worksite): string => match ($worksite->payment_status) {
//                    WorksitePaymentStatusEnum::PAID => 'success',
//                    WorksitePaymentStatusEnum::PENDING => 'primary',
//                    WorksitePaymentStatusEnum::NOT_PAID => 'danger',
//                    WorksitePaymentStatusEnum::PARTIALLY_PAID => 'warning',
//                    default => 'info',
//                }),
            Tables\Columns\TextColumn::make('total_remuneration')
                ->money('EUR')
                ->sortable(),
//            ->summarize(
//                Tables\Columns\Summarizers\Summarizer::make()
//                    ->using(
//                        fn(Builder $query): string =>
//                            $query->sum('total_remuneration')
//                                ? $query->sum('total_remuneration')
//                                : '0.00'
//                    )
//                    ->label(__('app.total'))
//            ),
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
                Tables\Columns\TextColumn::make('start_date')
                    ->description(fn(Worksite $worksite) => $worksite->start_date->format('d/m/y') . ' - ' . $worksite->end_date->format('d/m/y')),
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
        ];
    }
}
