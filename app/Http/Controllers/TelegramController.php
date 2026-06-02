<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use App\Models\Message;
use App\Models\TelegraphBotCommand;
use App\Models\TelegraphAutoReply;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function index()
    {
        $messages = Message::with('telegraphChat.bot')->latest()->paginate(10);
        $bots = TelegraphBot::all();
        $autoReplies = TelegraphAutoReply::latest()->get();
        $commands = TelegraphBotCommand::with('bot')->latest()->get();

        return view('telegram', compact('messages', 'bots', 'autoReplies', 'commands'));
    }

    public function getNewMessages(Request $request)
    {
        $lastId = $request->input('last_id', 0);
        $newMessages = Message::with('telegraphChat.bot')
            ->where('id', '>', $lastId)
            ->latest()
            ->get();

        return response()->json([
            'messages' => $newMessages
        ]);
    }

   public function sendMessage(Request $request)
{
    $request->validate([
        'message' => 'required|string'
    ]);

    $chat = TelegraphChat::first();

    if (!$chat) {
        return redirect()->back()->with('error', 'No active chat room found to send message!');
    }

    try {
        $chat->html($request->message)->send();

        Message::create([
            'telegraph_chat_id' => $chat->id,
            'message' => $request->message,
            'direction' => 'outbound',
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Telegram Connection Error: ' . $e->getMessage());
    }
}

    public function webhook(Request $request, $token)
    {
        $bot = TelegraphBot::where('token', $token)->firstOrFail();
        $webhookData = $request->all();

        if (!isset($webhookData['message'])) {
            return response()->json(['status' => 'success']);
        }

        $tgMessage = $webhookData['message'];
        $chatId = $tgMessage['chat']['id'];
        $chatName = $tgMessage['chat']['first_name'] ?? ($tgMessage['chat']['title'] ?? 'Unknown');

        $chat = TelegraphChat::firstOrCreate(
            ['chat_id' => $chatId],
            ['name' => $chatName, 'telegraph_bot_id' => $bot->id]
        );

        $text = $tgMessage['text'] ?? '';
        $fileType = null;
        $filePath = null;

        if (isset($tgMessage['photo'])) {
            $fileType = 'image';
            $photo = end($tgMessage['photo']);
            $filePath = $this->downloadTelegramFile($bot->token, $photo['file_id'], 'images');
            $text = $tgMessage['caption'] ?? 'Photo Message';
        } elseif (isset($tgMessage['voice'])) {
            $fileType = 'voice';
            $filePath = $this->downloadTelegramFile($bot->token, $tgMessage['voice']['file_id'], 'voice');
            $text = 'Voice Message';
        } elseif (isset($tgMessage['document'])) {
            $fileType = 'pdf';
            $filePath = $this->downloadTelegramFile($bot->token, $tgMessage['document']['file_id'], 'documents');
            $text = $tgMessage['document']['file_name'] ?? 'Document Message';
        }

        Message::create([
            'telegraph_chat_id' => $chat->id,
            'message' => $text,
            'file_type' => $fileType,
            'file_path' => $filePath,
            'direction' => 'inbound',
        ]);

        if ($text) {
            $this->handleAutoReply($chat, $text);
        }

        return response()->json(['status' => 'success']);
    }

    private function downloadTelegramFile($token, $fileId, $folder)
    {
        $response = Http::get("https://api.telegram.org/bot{$token}/getFile?file_id={$fileId}");
        if ($response->successful() && isset($response->json()['result']['file_path'])) {
            $tgFilePath = $response->json()['result']['file_path'];
            $fileUrl = "https://api.telegram.org/file/bot{$token}/{$tgFilePath}";
            $fileContents = Http::get($fileUrl)->body();
            $localPath = "public/telegram/{$folder}/" . basename($tgFilePath);
            Storage::put($localPath, $fileContents);
            return Storage::url($localPath);
        }
        return null;
    }

    private function handleAutoReply($chat, $text)
    {
        $replies = TelegraphAutoReply::where('is_active', true)->get();
        foreach ($replies as $reply) {
            $matched = false;
            if ($reply->match_type === 'exact' && strtolower(trim($text)) === strtolower(trim($reply->keyword))) {
                $matched = true;
            } elseif ($reply->match_type === 'contains' && str_contains(strtolower($text), strtolower($reply->keyword))) {
                $matched = true;
            }

            if ($matched) {
                $chat->html($reply->reply_text)->send();
                Message::create([
                    'telegraph_chat_id' => $chat->id,
                    'message' => $reply->reply_text,
                    'direction' => 'outbound',
                ]);
                break;
            }
        }
    }

    public function storeReplyRule(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
            'reply_text' => 'required|string',
            'match_type' => 'required|string',
        ]);

        TelegraphAutoReply::create($request->all());
        return redirect()->back()->with('success', 'Auto-Reply rule created successfully');
    }

    public function deleteReplyRule($id)
    {
        TelegraphAutoReply::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Auto-Reply rule deleted successfully');
    }

    public function storeCommand(Request $request)
    {
        $request->validate([
            'telegraph_bot_id' => 'required',
            'command' => 'required|string',
            'description' => 'required|string',
        ]);

        TelegraphBotCommand::create($request->all());
        $this->syncBotCommands($request->telegraph_bot_id);

        return redirect()->back()->with('success', 'Command created and synced with Telegram');
    }

    public function deleteCommand($id)
    {
        $command = TelegraphBotCommand::findOrFail($id);
        $botId = $command->telegraph_bot_id;
        $command->delete();
        $this->syncBotCommands($botId);

        return redirect()->back()->with('success', 'Command deleted and synced with Telegram');
    }

    private function syncBotCommands($botId)
    {
        $bot = TelegraphBot::findOrFail($botId);
        $commands = TelegraphBotCommand::where('telegraph_bot_id', $botId)->get();
        
        $tgCommands = [];
        foreach ($commands as $cmd) {
            $tgCommands[] = [
                'command' => ltrim($cmd->command, '/'),
                'description' => $cmd->description
            ];
        }

        Http::post("https://api.telegram.org/bot{$bot->token}/setMyCommands", [
            'commands' => $tgCommands
        ]);
    }

    public function sendManualReply(Request $request, $chatId)
    {
        $request->validate(['text' => 'required|string']);
        $chat = TelegraphChat::findOrFail($chatId);
        $chat->html($request->text)->send();

        Message::create([
            'telegraph_chat_id' => $chat->id,
            'message' => $request->text,
            'direction' => 'outbound',
        ]);

        return redirect()->back()->with('success', 'Message sent successfully');
    }

    public function updateMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);
        $message = Message::findOrFail($id);
        $message->update(['message' => $request->message]);

        return response()->json(['success' => true, 'message' => 'Message updated successfully']);
    }

    public function deleteMessage($id)
    {
        Message::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Message deleted successfully']);
    }

    public function bulkDeleteMessages(Request $request)
    {
        if ($request->has('message_ids')) {
            Message::whereIn('id', $request->message_ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected messages deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No messages selected']);
    }

    public function clearAllMessages()
    {
        Message::truncate();
        return response()->json(['success' => true, 'message' => 'All messages cleared successfully']);
    }
}