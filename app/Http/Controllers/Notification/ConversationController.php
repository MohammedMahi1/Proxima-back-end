<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        // Retrieve conversations for the authenticated user
        $conversations = Conversation::where('user_id', auth()->id())->get();

        return response()->json($conversations);
    }

    public function store(Request $request)
    {

        $user = auth()->user();
        // Create a new conversation
        $conversation = Conversation::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
        ]);

        return response()->json($conversation, 201);
    }

    public function show(Conversation $conversation)
    {
        // Load messages for a conversation
        $conversation->load('messages');

        return response()->json($conversation);
    }
    public function addUser(Request $request, Conversation $conversation)
    {
        $user = auth()->user();
        $userId = $request->input('user_id');

        $conversation->users()->attach($userId);

        return response()->json(null, 204);
    }
}
