<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use App\Models\TelegramBot;
use DefStudio\Telegraph\Models\TelegraphBot as TelegraphBotModel;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/telegram', [TelegramController::class, 'index']);

// Send message
Route::post('/send-message', [TelegramController::class, 'sendMessage']);

// New routes for additional functionality
Route::delete('/delete-message/{id}', [TelegramController::class, 'deleteMessage']);
Route::put('/update-message/{id}', [TelegramController::class, 'updateMessage']);
Route::delete('/bulk-delete-messages', [TelegramController::class, 'bulkDeleteMessages']);
Route::delete('/clear-all-messages', [TelegramController::class, 'clearAllMessages']);
Route::get('/search-messages', [TelegramController::class, 'searchMessages']);
Route::get('/setup-bot', function () {
    // Create bot
    $bot = TelegraphBotModel::create([
        'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR_BOT_TOKEN'),
        'name' => 'My Telegram Bot'
    ]);
    
    // Create chat
    $bot->chats()->create([
        'chat_id' => env('TELEGRAM_CHAT_ID', 'YOUR_CHAT_ID'),
        'name' => 'My Chat'
    ]);
    
    return "Bot and Chat created successfully!";
});