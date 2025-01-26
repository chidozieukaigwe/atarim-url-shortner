<?php

use App\Http\Controllers\Api\V1\LinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json('Welcome Api Version 1 Url Shortener service');
});

Route::post('/encode', [LinkController::class, 'store'])->name('encode');

Route::get('/decode/{link:short_code}', [LinkController::class, 'show'])->name('decode');
