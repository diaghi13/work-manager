<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkDayResource\Pages;
use App\Filament\Resources\WorkDayResource\RelationManagers;
use App\Http\Middleware\RegisteredDatabaseHandlerMiddleware;
use App\Models\Customer;
use App\Models\Enums\WorkDayTypeEnum;
use App\Models\WorkDay;
use App\Models\Worksite;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkDayResource extends Resource
{
    protected static ?string $model = WorkDay::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Work';

    //protected static string | array $routeMiddleware = RegisteredDatabaseHandlerMiddleware::class;

    public static function getLabel(): ?string
    {
        return __('app.work_day.single');
    }

    /**
     * @return string|null
     */
    public static function getPluralLabel(): ?string
    {
        return __('app.work_day.multiple');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options(WorkDayTypeEnum::class)
                            ->default(WorkDayTypeEnum::WORK)
                            ->label(__('app.work_day.type'))
                            ->required(),
                        Forms\Components\Select::make('worksite_id')
                            ->native(false)
                            //->searchable()
                            ->relationship(name: 'worksite', titleAttribute: 'name')
                            ->label(__('app.work_day.worksite'))
                            ->required()
                            ->columnSpan(1)
                            ->live()
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                //if (!$get('daily_cost')) {

                                //}

                                if (!$get('worksite_id')) {
                                    $set('daily_cost', null);
                                    $set('daily_allowance', null);
                                } else {
                                    $worksite = Worksite::where('id', $get('worksite_id'))->get()->first();

                                    switch ($get('type')) {
                                        case WorkDayTypeEnum::WORK:
                                            $set('daily_cost', $worksite->daily_cost);
                                            $set('daily_allowance', $worksite->daily_allowance);
                                            break;
                                        case WorkDayTypeEnum::TRAVEL:
                                            $set('daily_cost', $worksite->daily_cost / 2);
                                            $set('daily_allowance', $worksite->daily_allowance);
                                            break;
                                        case WorkDayTypeEnum::OFF:
                                            $set('daily_cost', null);
                                            $set('daily_allowance', $worksite->daily_allowance);
                                            break;
                                        default:
                                            $set('daily_cost', null);
                                            $set('daily_allowance', null);
                                            break;
                                    }
                                }
                            }),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Forms\Components\Checkbox::make('all_worksites')
                    ->label(__('app.work_day.all_worksites'))
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        if ($get('all_worksites')) {
                            $set('worksite_id', Worksite::all()->pluck('id')->toArray());
                        }
                    }),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label(__('app.work_day.date'))
                            ->default(Carbon::today())
                            ->required(),
//                Forms\Components\DateTimePicker::make('start_date_time')
//                    ->label(__('app.start_date_time'))
//                    ->default(Carbon::today()->hour(8)->minute(0)->second(0))
//                    ->required(),
//                Forms\Components\DateTimePicker::make('end_date_time')
//                    ->label(__('app.end_date_time'))
//                    ->default(Carbon::today()->hour(18)->minute(0)->second(0))
//                    ->required(),
                        Forms\Components\TextInput::make('description')
                            ->label(__('app.work_day.description'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('daily_cost')
                            ->label(__('app.work_day.daily_cost'))
                            ->prefix('€')
                            ->numeric(),
                        Forms\Components\TextInput::make('daily_allowance')
                            ->label(__('app.work_day.daily_allowance'))
                            ->prefix('€')
                            ->numeric()
//                            ->hidden(
//                                fn(Forms\Get $get): bool => $get('worksite_id') ? Worksite::find($get('worksite_id'))->first()->daily_allowance : false
//                            ),
                        /*Forms\Components\TextInput::make('travel_cost')
                            ->label(__('app.travel_cost'))
                            ->prefix('€')
                            ->numeric(),
                        Forms\Components\TextInput::make('meal_cost')
                            ->label(__('app.meal_cost'))
                            ->prefix('€')
                            ->numeric(),
                        Forms\Components\TextInput::make('extra_cost')
                            ->label(__('app.extra_cost'))
                            ->prefix('€')
                            ->numeric(),*/
                    ])
                    ->columns(4)
                    ->columnSpanFull(),

//                Forms\Components\Textarea::make('extra_cost_description')
//                    ->label(__('app.extra_cost_description'))
//                    ->columnSpanFull(),
                Forms\Components\Toggle::make('calculate_extra_time_cost')
                    ->label(__('app.work_day.calculate_extra_time_cost'))
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with(['outgoings']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('app.work_day.date'))
                    ->dateTime('d/m/y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('worksite.name')
                    ->label(__('app.work_day.worksite'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('app.work_day.description'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('daily_cost')
                    ->label(__('app.work_day.daily_cost'))
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('total_hours')
                    ->label(__('app.work_day.total_hours')),
                Tables\Columns\TextColumn::make('extra_time')
                ->label(__('app.work_day.extra_time')),
                Tables\Columns\TextColumn::make('extra_time_cost')
                    ->label(__('app.work_day.extra_time_cost'))
                    ->money('EUR'),
                Tables\Columns\IconColumn::make('calculate_extra_time_cost')
                    ->label(__('app.work_day.calculate_extra_time_cost'))
                    ->toggleable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('total_remuneration')
                    ->label(__('app.work_day.total_remuneration'))
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('travel_cost')
                    ->label(__('app.work_day.travel_cost'))
                    ->money('EUR')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('meal_cost')
                    ->label(__('app.work_day.meal_cost'))
                    ->money('EUR')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('extra_cost')
                    ->label(__('app.work_day.extra_cost'))
                    ->money('EUR')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_extra_cost')
                    ->label(__('app.work_day.total_extra_cost'))
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->label(__('app.work_day.total_cost'))
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('daily_allowance')
                    ->label(__('app.work_day.daily_allowance'))
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('remaining_allowance')
                    ->label(__('app.work_day.remaining_allowance'))
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('app.work_day.customer'))
                    ->label('Customer')
                    ->sortable()
                    ->toggleable(),
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WorkDayDatetimesRelationManager::class,
            RelationManagers\OutgoingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkDays::route('/'),
            'create' => Pages\CreateWorkDay::route('/create'),
            'edit' => Pages\EditWorkDay::route('/{record}/edit'),
        ];
    }
}
