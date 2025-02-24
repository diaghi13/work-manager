<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return (new \App\Models\Document)->available_worksites()->get();
    return \App\Models\Document::with('worksites')->get();
    return \App\Models\WorkDay::with('customer')->get();
    return view('welcome');
});
