<?php

use App\Jobs\ProcessTenantDatabaseCreationJob;
use App\Jobs\ProcessTenantMigrationJob;
use App\Models\Document;
use App\Models\Worksite;
use Filament\Facades\Filament;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
//    return Document::selectRaw(
//        'MONTH(document_date) as month, YEAR(document_date) as year, SUM(gross_price) as total'
//    )
//        ->where('year', 2025)
//        ->where(function (Builder $query) {
//            $query->where('type', 'invoice')
//                ->orWhere('type', 'receipt');
//        })
//        ->where('status', '!=', 'draft')
//        ->groupBy('month', 'year')
//        ->dd();

    return view('welcome');
});

Route::get('/pulsantone', function () {
    return view('pulsantone');
});
