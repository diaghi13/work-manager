<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\WorksiteResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class WorksitesRelationManager extends RelationManager
{
    protected static string $relationship = 'worksites';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return WorksiteResource::table($table);
    }
}
