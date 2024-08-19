<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageAnalysisController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/analyze', [ImageAnalysisController::class, 'analyze']);

