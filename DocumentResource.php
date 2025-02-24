<?php
//
//
//use App\Filament\Resources\DocumentResource\Pages;
//use App\Filament\Resources\DocumentResource\RelationManagers;
//use App\Models\Document;
//use App\Models\DocumentWorksite;
//use App\Models\Enums\DocumentStatusEnum;
//use App\Models\Enums\DocumentTypeEnum;
//use App\Models\Enums\WorksitePaymentStatusEnum;
//use App\Models\Enums\WorksiteStatusEnum;
//use App\Models\PaymentMethod;
//use App\Models\Vat;
//use App\Models\Worksite;
//use Awcodes\TableRepeater\Components\TableRepeater;
//use Awcodes\TableRepeater\Header;
//use Filament\Forms;
//use Filament\Forms\Form;
//use Filament\Resources\Resource;
//use Filament\Tables;
//use Filament\Tables\Table;
//use Hamcrest\Core\Set;
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletingScope;
//use Illuminate\Support\Carbon;
//
//class DocumentResource extends Resource
//{
//    protected static ?string $model = Document::class;
//
//    protected static ?string $navigationIcon = 'heroicon-o-document-text';
//
//    public static function form(Form $form): Form
//    {
//        return $form
//            ->schema([
//                Forms\Components\Section::make('Testata')
//                    ->schema([
//                        Forms\Components\Group::make()
//                            ->schema([
//                                Forms\Components\Fieldset::make()
//                                    ->schema([
//                                        Forms\Components\Select::make('type')
//                                            ->options(DocumentTypeEnum::class)
//                                            ->required()
//                                            ->columnSpanFull(),
//                                        Forms\Components\TextInput::make('ref_number')
//                                            ->maxLength(255),
//                                        Forms\Components\DatePicker::make('document_date')
//                                            ->default(now())
//                                            ->required(),
//                                    ])
//                                    //->columns(2)
//                                    ->columnSpan(1),
//                                Forms\Components\Group::make()
//                                    ->schema([
//                                        Forms\Components\Select::make('customer_id')
//                                            ->relationship(name: 'customer', titleAttribute: 'name')
//                                            ->searchable()
//                                            ->preload()
//                                            ->createOptionForm(function () {
//                                                return [
//                                                    Forms\Components\TextInput::make('name'),
//                                                    Forms\Components\TextInput::make('vat_code'),
//                                                    Forms\Components\TextInput::make('vat_id'),
//                                                    Forms\Components\TextInput::make('street'),
//                                                    Forms\Components\TextInput::make('number'),
//                                                    Forms\Components\TextInput::make('zip_code'),
//                                                    Forms\Components\TextInput::make('city'),
//                                                    Forms\Components\TextInput::make('state'),
//                                                    Forms\Components\TextInput::make('country'),
//                                                    Forms\Components\TextInput::make('sdi_code'),
//                                                    Forms\Components\TextInput::make('daily_cost'),
//                                                    Forms\Components\TextInput::make('extra_time_cost'),
//                                                    Forms\Components\TextInput::make('daily_hours'),
//                                                ];
//                                            })
//                                            ->required(),
//                                        Forms\Components\View::make('test')
//                                            ->view('components.test')
//                                            ->columnSpan(1),
//                                    ]),
//                                Forms\Components\Group::make()
//                                    ->schema([
//                                        Forms\Components\Select::make('status')
//                                            ->options(DocumentStatusEnum::class)
//                                            ->required(),
//                                        Forms\Components\TextInput::make('total_net')
//                                            ->default(0)
//                                            //->disabled()
//                                            ->inlineLabel()
//                                            ->columnSpan(1),
//                                        Forms\Components\TextInput::make('total_vat')
//                                            ->default(0)
//                                            //->disabled()
//                                            ->inlineLabel()
//                                            ->columnSpan(1),
//                                        Forms\Components\TextInput::make('total')
//                                            ->default(0)
//                                            //->disabled()
//                                            ->inlineLabel()
//                                            ->columnSpan(1),
//                                    ])
//                            ])
//                            ->columns(3)
//                            ->columnSpanFull(),
//                    ]),
//                TableRepeater::make('document_rows')
//                    ->label('Righe')
//                    ->headers([
//                        Header::make('Description'),
//                        Header::make('UM')
//                            ->width('80px'),
//                        Header::make('Quantity')
//                            ->width('80px'),
//                        Header::make('Price')
//                            ->width('100px'),
//                        Header::make('Vat code')
//                            ->width('80px'),
//                        Header::make('Vat')
//                            ->width('100px'),
//                        Header::make('Total')
//                            ->width('100px'),
//                    ])
//                    ->schema([
////                        Forms\Components\MorphToSelect::make('productable')
////                            ->types([
////                                Forms\Components\MorphToSelect\Type::make(Worksite::class)
////                                    //->getOptionLabelFromRecordUsing(fn($record) => class_basename($record::class) . ': '.$record->name)
////                                    ->titleAttribute('name'),
////                            ])->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
////                                if ($state['productable_type']) {
////                                    if ($state['productable_id']) {
////                                        $product = $state['productable_type']::find($state['productable_id']);
////                                        //dump($product);
////                                        !$get('description') && $set('description', "Consulenza tecnica per Vostro evento \"{$product->name}\" dal " . $product->start_date->format('d/m/y') . " al " . $product->end_date->format('d/m/y'));
////                                        $set('quantity', 1);
////                                        $set('price', $product->total_remuneration);
////
////                                        $vat = Vat::find(1);
////                                        $set('vat_id', $vat->id);
////                                        $vatPrice = $product->total_remuneration * $vat->value / 100;
////                                        $set('vat', $vatPrice);
////
////                                        $set('total', $product->total_remuneration + $vatPrice);
////                                    }
////                                }
////                            })
////                            ->live()
////                            ->searchable()
////                            ->preload()
////                            ->columnSpan(4),
//                        Forms\Components\Textarea::make('description')
//                            ->rows(1)
//                            ->autosize()
//                            ->columnSpan(8)
//                            ->required(),
//                        Forms\Components\Select::make('measure_unit_id')
//                            ->relationship(name: 'measure_unit', titleAttribute: 'abbreviation')
//                            ->default(1)
//                            ->native(false)
//                            ->columnSpan(2)
//                            ->required(),
//                        Forms\Components\TextInput::make('quantity')
//                            ->columnSpan(2)
//                            ->required(),
//                        Forms\Components\TextInput::make('price')
//                            ->columnSpan(2)
//                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
//                                $vat = Vat::find($get('vat_id'));
//                                $vatPrice = $state * $vat->value / 100;
//                                $set('vat', $vatPrice);
//                                $set('total', $state + $vatPrice);
//                            })
//                            ->live(onBlur: true)
//                            ->required(),
//                        Forms\Components\Select::make('vat_id')
//                            ->relationship(name: 'vat', titleAttribute: 'code')
//                            ->searchable(['code', 'description'])
////                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
////                                $vat = Vat::find($state);
////                                $vatPrice = $get('price') * $vat->value / 100;
////                                $set('vat', $vatPrice);
////                                $set('total', $get('price') + $vatPrice);
////                            })
//                            ->live()
//                            //->getOptionLabelFromRecordUsing(fn($record) => $record->code . ' - ' . $record->description)
//                            //->preload()
//                            ->native(false)
//                            ->default(1)
//                            ->columnSpan(2)
//                            ->required(),
//                        Forms\Components\TextInput::make('vat')
//                            ->columnSpan(2)
//                            ->required(),
//                        Forms\Components\TextInput::make('total')
//                            ->columnSpan(2)
//                            ->required(),
//                    ])
//                    ->relationship('document_rows')
//                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
////                        $total_net = 0;
////                        $total_vat = 0;
////                        $total = 0;
////                        foreach ($state as $row) {
////                            $total_net += $row['price'];
////                            $total_vat += $row['vat'];
////                            $total += $row['total'];
////                        }
////                        $set('total_net', $total_net);
////                        $set('total_vat', $total_vat);
////                        $set('total', $total);
//                    })
//                    ->extraItemActions([
//                        Forms\Components\Actions\Action::make('save')
//                            ->label('Salva')
//                            ->action(function (array $arguments, Forms\Components\Repeater $component, Forms\Set $set): void {
//                                $total_net = 0;
//                                $total_vat = 0;
//                                $total = 0;
//                                foreach ($component->getState() as $row) {
//                                    $total_net += $row['price'];
//                                    $total_vat += $row['vat'];
//                                    $total += $row['total'];
//                                }
//                                $set('total_net', $total_net);
//                                $set('total_vat', $total_vat);
//                                $set('total', $total);
//                            })
//                            ->button(),
//                    ])
//                    ->columns(12)
//                    ->reorderableWithButtons(true)
//                    ->collapsible()
//                    ->cloneable()
//                    ->columnSpanFull(),
//                Forms\Components\Section::make('Pagamenti')
//                    ->schema([
//                        Forms\Components\Select::make('payment_method_id')
//                            ->relationship(name: 'paymentMethod', titleAttribute: 'name')
//                            ->searchable()
//                            ->preload()
//                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
//                                if ($state) {
//                                    $paymentMethod = PaymentMethod::find($state);
//
//                                    //dump($paymentMethod->paymentMethodInstallments);
//                                    $payments = [];
//                                    foreach ($paymentMethod->paymentMethodInstallments as $i => $installment) {
//                                        $payments[] =
//                                            [
//                                                'expiration_date' => now()->addDays($installment->days)->toDateString(),
//                                                'amount' => $get('total') / count($paymentMethod->paymentMethodInstallments),
//                                                'payment_method_id' => $state,
//                                            ];
//                                    }
//                                    $set('payments', $payments);
//
////                            for ($i = 0; $i < count($paymentMethod->paymentMethodInstallments); $i++) {
////                                $set('payments.record-'.$i.'.0.payment_date', now()->addDays($paymentMethod->paymentMethodInstallments[$i]->days));
////                            }
//                                }
//                            })
//                            ->live()
//                            ->required(),
//                        TableRepeater::make('payments')
//                            ->label('Pagamenti')
//                            ->reorderable(true)
//                            ->headers([
//                                Header::make('Scadenza'),
//                                Header::make('Data pagamento'),
//                                Header::make('Importo'),
//                                Header::make('Metodo di pagamento'),
//                            ])
//                            ->schema([
//                                Forms\Components\DatePicker::make('expiration_date')
//                                    ->default(now())
//                                    ->required(),
//                                Forms\Components\DatePicker::make('payment_date')
//                                    ->default(now()),
//                                Forms\Components\TextInput::make('amount')
//                                    ->required(),
//                                Forms\Components\Select::make('payment_method_id')
//                                    ->relationship(name: 'payment_method', titleAttribute: 'name'),
//                            ])
//                            ->relationship('payments')
//                            ->columns(4)
//                            ->default([])
//                            ->columnSpanFull(),
//                    ]),
//
//                Forms\Components\Section::make('Riferimento cantieri')
//                    ->schema([
//                        Forms\Components\Repeater::make('worksites')
//                            ->label('Cantieri')
////                            ->headers([
////                                Header::make('Cantiere'),
////                                Header::make('Stato pagamento'),
////                            ])
//                            ->schema([
//                                Forms\Components\Select::make('worksite_id')
//                                    ->options(Worksite::all()->pluck('name', 'id'))
//                                    ->searchable()
//                                    ->preload()
//                                    ->required(),
//                                Forms\Components\Select::make('worksite_payment_status')
//                                    ->options(WorksitePaymentStatusEnum::class)
//                                    ->default(0)
//                                    ->required(),
//                            ])
//                            //->relationship('worksites')
//                            ->relationship(name: 'worksites')
//                            ->columns(4)
//                            ->columnSpanFull(),
//                    ]),
//
//                Forms\Components\Textarea::make('notes')
//                    ->columnSpanFull(),
//                Forms\Components\Select::make('worksites')
//                    ->relationship(name: 'worksites', titleAttribute: 'name')
////                        modifyQueryUsing: function (Builder $query) {
////                            $query
////                                ->where('status', WorksiteStatusEnum::ACCEPTED->value)
////                                ->orWhere('status', WorksiteStatusEnum::IN_PROGRESS->value)
////                                ->orWhere('status', WorksiteStatusEnum::COMPLETED->value)
////                                ->where('is_payed', false);
////                        }
////                        modifyQueryUsing: function (Builder $query, $record) {
////                            if ($record) {
////                                $query
////                                    ->whereNotIn('worksites.id', DocumentWorksite::all()->pluck('worksite_id'))
////                                    ->where(function (Builder $query) use ($record) {
////                                        $query
////                                            ->where('status', WorksiteStatusEnum::ACCEPTED->value)
////                                            ->orWhere('status', WorksiteStatusEnum::IN_PROGRESS->value)
////                                            ->orWhere('status', WorksiteStatusEnum::COMPLETED->value)
////                                            ->where('is_payed', false);
////                                    })
////                                    ->orWhere('document_id', $record->id);
////                            } else {
////                                $query
////                                    ->whereNotIn('worksites.id', DocumentWorksite::all()->pluck('worksite_id'))
////                                    ->where(function (Builder $query) use ($record) {
////                                        $query
////                                            ->where('status', WorksiteStatusEnum::ACCEPTED->value)
////                                            ->orWhere('status', WorksiteStatusEnum::IN_PROGRESS->value)
////                                            ->orWhere('status', WorksiteStatusEnum::COMPLETED->value)
////                                            ->where('is_payed', false);
////                                    });
////                            }
////                        }
////                    ->pivotData(function ($record) {
////                        return [
////                            'worksite_payment_status' => WorksitePaymentStatusEnum::PAID->value,
////                        ];
////                    })
//                    ->columnSpanFull()
//                    ->multiple()
//                    ->preload(),
//            ]);
//    }
//
//    public static function table(Table $table): Table
//    {
//        return $table
//            ->columns([
//                Tables\Columns\TextColumn::make('type')
//                    ->width('150px')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('document_date')
//                    ->date('d/m/Y')
//                    ->width('150px')
//                    ->sortable(),
//                Tables\Columns\TextColumn::make('ref_number')
//                    ->width('150px')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('customer.name')
//                    ->numeric()
//                    ->sortable(),
//                Tables\Columns\TextColumn::make('total')
//                    ->width('150px')
//                    ->money('EUR'),
//                Tables\Columns\SelectColumn::make('status')
//                    ->options(DocumentStatusEnum::class)
//                    ->width('150px')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('created_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//                Tables\Columns\TextColumn::make('updated_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//            ])
//            ->defaultSort('document_date', 'desc')
//            ->filters([
//                //
//            ])
//            ->actions([
//                Tables\Actions\EditAction::make(),
//            ])
//            ->bulkActions([
////                Tables\Actions\BulkActionGroup::make([
////                    Tables\Actions\DeleteBulkAction::make(),
////                ]),
//            ]);
//    }
//
//    public static function getRelations(): array
//    {
//        return [
//            //RelationManagers\DocumentRowsRelationManager::class
//        ];
//    }
//
//    public static function getPages(): array
//    {
//        return [
//            'index' => Pages\ListDocuments::route('/'),
//            'create' => Pages\CreateDocument::route('/create'),
//            'edit' => Pages\EditDocument::route('/{record}/edit'),
//        ];
//    }
//}
