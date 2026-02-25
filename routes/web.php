<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-message', [TelegramController::class, 'sendMessage']);