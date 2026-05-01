<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/telegram', [TelegramController::class, 'index']);

// Use POST for sending message
Route::post('/send-message', [TelegramController::class, 'sendMessage']);