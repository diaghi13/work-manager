<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use App\Models\DocumentWorksite;
use App\Models\Enums\DocumentStatusEnum;
use App\Models\Enums\DocumentTypeEnum;
use App\Models\Enums\WorksitePaymentStatusEnum;
use App\Models\Enums\WorksiteStatusEnum;
use App\Models\PaymentMethod;
use App\Models\Vat;
use App\Models\Worksite;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Brick\Money\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Testata')
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Fieldset::make()
                                    ->schema([
                                        Forms\Components\Select::make('type')
                                            ->options(DocumentTypeEnum::class)
                                            ->required()
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('ref_number')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('document_date')
                                            ->default(now())
                                            ->required(),
                                    ])
                                    //->columns(2)
                                    ->columnSpan(1),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('customer_id')
                                            ->relationship(name: 'customer', titleAttribute: 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm(function () {
                                                return [
                                                    Forms\Components\TextInput::make('name'),
                                                    Forms\Components\TextInput::make('vat_code'),
                                                    Forms\Components\TextInput::make('vat_id'),
                                                    Forms\Components\TextInput::make('street'),
                                                    Forms\Components\TextInput::make('number'),
                                                    Forms\Components\TextInput::make('zip_code'),
                                                    Forms\Components\TextInput::make('city'),
                                                    Forms\Components\TextInput::make('state'),
                                                    Forms\Components\TextInput::make('country'),
                                                    Forms\Components\TextInput::make('sdi_code'),
                                                    Forms\Components\TextInput::make('daily_cost'),
                                                    Forms\Components\TextInput::make('extra_time_cost'),
                                                    Forms\Components\TextInput::make('daily_hours'),
                                                ];
                                            })
                                            ->required(),
                                        Forms\Components\View::make('test')
                                            ->view('components.test')
                                            ->columnSpan(1),
                                    ]),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options(DocumentStatusEnum::class)
                                            ->required(),
                                        Forms\Components\TextInput::make('total_net')
                                            ->default(0)
                                            //->disabled()
                                            ->inlineLabel()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('total_vat')
                                            ->default(0)
                                            //->disabled()
                                            ->inlineLabel()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('total')
                                            ->default(0)
                                            //->disabled()
                                            ->inlineLabel()
                                            ->columnSpan(1),
                                    ])
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
                TableRepeater::make('document_rows')
                    ->label('Righe')
                    ->headers([
                        Header::make('Products')
                        ->width('120px'),
                        Header::make('Description'),
                        Header::make('UM')
                            ->width('80px'),
                        Header::make('Quantity')
                            ->width('80px'),
                        Header::make('Price')
                            ->width('100px'),
                        Header::make('Vat code')
                            ->width('80px'),
                        Header::make('Vat')
                            ->width('100px'),
                        Header::make('Total')
                            ->width('100px'),
                    ])
                    ->schema([
                        Forms\Components\Select::make('products')
                            ->options([
                                'Cantieri' => collect(DB::select('SELECT * FROM worksites LEFT JOIN (SELECT worksite_id, MAX(worksite_payment_status) as worksite_payment_status FROM document_worksite GROUP BY worksite_id) a_max ON a_max.worksite_id = worksites.id WHERE worksite_payment_status <> 3 OR worksite_payment_status IS NULL'))->pluck('name', 'id')->toArray()
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->rows(1)
                            ->autosize()
                            ->columnSpan(8)
                            ->required(),
                        Forms\Components\Select::make('measure_unit_id')
                            ->relationship(name: 'measure_unit', titleAttribute: 'abbreviation')
                            ->default(1)
                            ->native(false)
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->columnSpan(2)
                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                $vat = Vat::find($get('vat_id'));
                                $vatPrice = $state * $vat->value / 100;
                                $set('vat', $vatPrice);
                                $set('total', $state + $vatPrice);
                            })
                            ->live(onBlur: true)
                            ->required(),
                        Forms\Components\Select::make('vat_id')
                            ->relationship(name: 'vat', titleAttribute: 'code')
                            ->searchable(['code', 'description'])
//                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
//                                $vat = Vat::find($state);
//                                $vatPrice = $get('price') * $vat->value / 100;
//                                $set('vat', $vatPrice);
//                                $set('total', $get('price') + $vatPrice);
//                            })
                            ->live()
                            //->getOptionLabelFromRecordUsing(fn($record) => $record->code . ' - ' . $record->description)
                            //->preload()
                            ->native(false)
                            ->default(1)
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('vat')
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('total')
                            ->columnSpan(2)
                            ->required(),
                    ])
                    ->relationship('document_rows')
        ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
//                        $total_net = 0;
//                        $total_vat = 0;
//                        $total = 0;
//                        foreach ($state as $row) {
//                            $total_net += $row['price'];
//                            $total_vat += $row['vat'];
//                            $total += $row['total'];
//                        }
//                        $set('total_net', $total_net);
//                        $set('total_vat', $total_vat);
//                        $set('total', $total);
        })
        ->extraItemActions([
            Forms\Components\Actions\Action::make('save')
                ->label('Salva')
                ->action(function (array $arguments, Forms\Components\Repeater $component, Forms\Set $set): void {
                    $total_net = 0;
                    $total_vat = 0;
                    $total = 0;
                    foreach ($component->getState() as $row) {
                        $total_net += $row['price'];
                        $total_vat += $row['vat'];
                        $total += $row['total'];
                    }
                    $set('total_net', $total_net);
                    $set('total_vat', $total_vat);
                    $set('total', $total);
                })
                ->button(),
        ])
        ->columns(12)
        ->orderColumn('order')
        ->collapsible()
        ->cloneable()
        ->columnSpanFull(),

                Forms\Components\Section::make('Pagamenti')
                    ->schema([
                        Forms\Components\Select::make('payment_method_id')
                            ->relationship(name: 'paymentMethod', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                $set('payments', []);

                                if ($state) {
                                    $paymentMethod = PaymentMethod::find($state);
                                    $endOfMonth = $paymentMethod->end_of_month;
                                    $payments = [];

                                    $total = Money::of($get('total'), 'EUR');
                                    $money = $total->split(count($paymentMethod->paymentMethodInstallments));

                                    foreach ($paymentMethod->paymentMethodInstallments as $i => $installment) {
                                        $date = Carbon::make($get('document_date'))->addDays($installment->days);

                                        if ($endOfMonth) {
                                            $date->endOfMonth();
                                        }

                                        $payments[] =
                                            [
                                                'expiration_date' => $date->toDateString(),
                                                'amount' => $money[$i]->getAmount()->toFloat(),
                                                'payment_method_id' => $state,
                                            ];
                                    }
                                    $set('payments', $payments);
                                }
                            })
                            ->live()
                            ->columnSpan(1)
                            ->required(),
                        TableRepeater::make('payments')
                            ->label('Pagamenti')
                            ->reorderable(true)
                            ->headers([
                                Header::make('Scadenza'),
                                Header::make('Data pagamento'),
                                Header::make('Importo'),
                                Header::make('Metodo di pagamento'),
                            ])
                            ->schema([
                                Forms\Components\DatePicker::make('expiration_date')
                                    ->default(now())
                                    ->required(),
                                Forms\Components\DatePicker::make('payment_date')
                                    ->default(now()),
                                Forms\Components\TextInput::make('amount')
                                    ->required(),
                                Forms\Components\Select::make('payment_method_id')
                                    ->relationship(name: 'payment_method', titleAttribute: 'name'),
                            ])
                            ->relationship('payments')
                            ->columns(4)
                            ->default([])
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columns(3),

                Forms\Components\Section::make('Riferimento cantieri')
                    ->schema([
                        TableRepeater::make('documentWorksites')
                            ->label('Cantieri')
                            ->headers([
                                Header::make('Cantiere'),
                                Header::make('Stato pagamento'),
                            ])
                            ->schema([
                                Forms\Components\Select::make('worksite_id')
                                    ->options(Worksite::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('worksite_payment_status')
                                    ->options(WorksitePaymentStatusEnum::class)
                                    ->pivotData(function ($record) {
                                        return [
                                            'worksite_payment_status' => $record->id,
                                        ];
                                    })
                                    ->columnSpan(1),
                            ])
                            ->default([])
                            ->relationship()
                            ->columns(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->width('150px')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_date')
                    ->date('d/m/Y')
                    ->width('150px')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ref_number')
                    ->width('150px')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->width('150px')
                    ->money('EUR'),
                Tables\Columns\SelectColumn::make('status')
                    ->options(DocumentStatusEnum::class)
                    ->width('150px')
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
            //RelationManagers\DocumentRowsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
