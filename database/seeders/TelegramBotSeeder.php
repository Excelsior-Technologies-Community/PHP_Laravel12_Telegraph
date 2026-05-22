<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DefStudio\Telegraph\Models\TelegraphBot;

class TelegramBotSeeder extends Seeder
{
    public function run()
    {
        $bot = TelegraphBot::create([
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'name' => 'My Telegram Bot'
        ]);

        $bot->chats()->create([
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'name' => 'My Chat'
        ]);
    }
}