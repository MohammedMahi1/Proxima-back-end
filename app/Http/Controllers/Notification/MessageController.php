<?php

namespace App\Http\Controllers\Notification;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification\Conversation;
use App\Models\Notification\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Conversation $conversation)
    {
        $messages = $conversation->messages()->with('user')->orderBy('created_at', 'DESC')->get();
        return response()->json($messages);
    }
    public function store(Conversation $conversation, Request $request)
    {
        // Create a new message in the conversation
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

        // Broadcast the new message using Pusher
        event(new NewMessageEvent($message));

        return response()->json($message, 201);
    }
}
