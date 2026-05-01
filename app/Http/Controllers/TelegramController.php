<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Models\TelegraphBot;
use App\Models\Message;

class TelegramController extends Controller
{
    // ✅ Show UI + message history
    public function index()
    {
        $messages = Message::latest()->get();
        return view('telegram', compact('messages'));
    }

    // ✅ Send message
    public function sendMessage(Request $request)
    {
        // Validate message
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Get bot
        $bot = TelegraphBot::first();

        if (!$bot) {
            return back()->with('error', 'Bot not found. Please create bot first.');
        }

        // Get chat
        $chat = $bot->chats()->first();

        if (!$chat) {
            return back()->with('error', 'Chat not found. Please add chat_id in database.');
        }

        // Send message to Telegram
        Telegraph::bot($bot)
            ->chat($chat->chat_id)
            ->message($request->message)
            ->send();

        // Save using Model
        Message::create([
            'message' => $request->message
        ]);

        // ✅ IMPORTANT: redirect back with success
        return redirect('/telegram')->with('success', 'Message Sent Successfully!');
    }
}