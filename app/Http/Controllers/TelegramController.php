<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Models\TelegraphBot;

class TelegramController extends Controller
{
    public function sendMessage()
    {
        $bot = TelegraphBot::first();

        Telegraph::bot($bot)
            ->message('Hello from Laravel 12 Telegraph!')
            ->send();

        return "Message Sent Successfully!";
    }
}