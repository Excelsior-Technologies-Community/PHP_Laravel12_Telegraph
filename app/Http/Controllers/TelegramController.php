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
        $messages = Message::latest()->paginate(10);
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

        try {
            // Send message to Telegram
            Telegraph::bot($bot)
                ->chat($chat->chat_id)
                ->message($request->message)
                ->send();

            // Save using Model
            Message::create([
                'message' => $request->message
            ]);

            return redirect('/telegram')->with('success', 'Message Sent Successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    // ✅ Delete single message
    public function deleteMessage($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete message!'
            ], 500);
        }
    }

    // ✅ Update message
    public function updateMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        try {
            $message = Message::findOrFail($id);
            $oldMessage = $message->message;
            $message->message = $request->message;
            $message->save();

            // Optional: Send update to Telegram
            $bot = TelegraphBot::first();
            if ($bot && $bot->chats()->first()) {
                $chat = $bot->chats()->first();
                Telegraph::bot($bot)
                    ->chat($chat->chat_id)
                    ->message("✏️ Message Updated:\n\nOLD: " . $oldMessage . "\n\nNEW: " . $request->message)
                    ->send();
            }

            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update message!'
            ], 500);
        }
    }

    // ✅ Bulk delete messages
    public function bulkDeleteMessages(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id'
        ]);

        try {
            Message::whereIn('id', $request->message_ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => count($request->message_ids) . ' messages deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete messages!'
            ], 500);
        }
    }

    // ✅ Clear all messages
    public function clearAllMessages()
    {
        try {
            $count = Message::count();
            Message::truncate();
            
            return response()->json([
                'success' => true,
                'message' => $count . ' messages cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear messages!'
            ], 500);
        }
    }

    // ✅ Search messages
    public function searchMessages(Request $request)
    {
        $search = $request->get('search', '');
        
        if (empty($search)) {
            $messages = Message::latest()->paginate(10);
        } else {
            $messages = Message::where('message', 'like', '%' . $search . '%')
                ->latest()
                ->paginate(10);
        }
        
        if ($request->ajax()) {
            return view('partials.message-table', compact('messages'))->render();
        }
        
        return view('telegram', compact('messages', 'search'));
    }
}