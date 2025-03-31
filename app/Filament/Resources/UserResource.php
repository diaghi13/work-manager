<?php

namespace App\Filament\Resources;

use App\Filament\Actions\CreateTenantAction;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): ?string
    {
        return 'Utente';
    }

    public static function getPluralLabel(): string
    {
        return 'Utenti';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
//                Forms\Components\TextInput::make('password')
//                    ->password()
//                    ->required()
//                    ->maxLength(255),
                Forms\Components\TextInput::make('database')
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name'),
                Forms\Components\Select::make('tenants')
                    ->relationship('tenants', 'id')
                    ->multiple()
                    ->options(Tenant::all()->pluck('id', 'id')->toArray())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with(['tenants']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tenants')
                    ->view('filament.tables.columns.tenants')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\TextEntry::make('name'),
                \Filament\Infolists\Components\TextEntry::make('email'),
                \Filament\Infolists\Components\TextEntry::make('email_verified_at')
                    ->date(),
                \Filament\Infolists\Components\TextEntry::make('database'),
                Section::make([
                    Actions::make([
                        //CreateTenantAction::make(),
                        Action::make('createTenant')
                            ->label('Crea tenant')
                            ->icon('heroicon-o-plus-circle')
                            ->visible(fn() => true)
                            ->requiresConfirmation()
                            ->action(function (User $record, TenantService $service) {
                                $tenant = $service->create();

                                if (config('tenancy.database_sync')) {
                                    Tenant::switch(root: true);
                                }

                                $record->tenants()->attach($tenant, [
                                    'selected' => true,
                                ]);

                                Notification::make()
                                    ->title($tenant->id . ' creato')
                                    ->body('Il tenant è stato creato con successo')
                                    ->icon('heroicon-o-check-circle')
                                    ->iconColor('success')
                                    ->send();
                            }),
                    ]),
                    Group::make([
                        RepeatableEntry::make('tenants')
                            ->label('Tenants')
                            ->schema([
                                TextEntry::make('id')
                                    ->label('Database name'),
                            ])
                        //->contained(false),
                    ])
                        ->columnSpanFull(),
                ]),
            ]);
    }

    protected function CreateTenant()
    {
        return \Filament\Actions\Action::make('createTenant')
            ->label('Crea tenant')
            ->icon('heroicon-o-plus-circle')
            ->action(function () {

            });
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
