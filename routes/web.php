<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;
use DefStudio\Telegraph\Models\TelegraphBot as TelegraphBotModel;

Route::get('/', [TelegramController::class, 'index'])->name('telegram.index');
Route::get('/messages/api/realtime', [TelegramController::class, 'getNewMessages'])->name('messages.realtime');
Route::post('/messages/reply/{chatId}', [TelegramController::class, 'sendManualReply'])->name('messages.manualReply');

Route::post('/auto-replies', [TelegramController::class, 'storeReplyRule'])->name('auto_replies.store');
Route::delete('/auto-replies/{id}', [TelegramController::class, 'deleteReplyRule'])->name('auto_replies.destroy');

Route::post('/commands', [TelegramController::class, 'storeCommand'])->name('commands.store');
Route::delete('/commands/{id}', [TelegramController::class, 'deleteCommand'])->name('commands.destroy');

Route::post('/send-message', [TelegramController::class, 'sendMessage']);
Route::delete('/delete-message/{id}', [TelegramController::class, 'deleteMessage']);
Route::put('/update-message/{id}', [TelegramController::class, 'updateMessage']);
Route::delete('/bulk-delete-messages', [TelegramController::class, 'bulkDeleteMessages']);
Route::delete('/clear-all-messages', [TelegramController::class, 'clearAllMessages']);
Route::get('/search-messages', [TelegramController::class, 'searchMessages']);

Route::post('/webhook/telegram/{token}', [TelegramController::class, 'webhook'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/setup-bot', function () {
    $bot = TelegraphBotModel::create([
        'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR_BOT_TOKEN'),
        'name' => 'My Telegram Bot'
    ]);
    
    $bot->chats()->create([
        'chat_id' => env('TELEGRAM_CHAT_ID', 'YOUR_CHAT_ID'),
        'name' => 'My Chat'
    ]);
    
    return "Bot and Chat created successfully!";
});