<?php

use App\Models\Worksite;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pulsantone', function () {
    return view('pulsantone');
});
