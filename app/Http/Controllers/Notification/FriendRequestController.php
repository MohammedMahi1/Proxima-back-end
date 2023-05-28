<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function sendFriendRequest(Request $request,$id)
    {
        // Assuming you have an authenticated user
        $sender = $request->user();
        // Check if the sender and recipient are already friends
        if ($sender->isFriendsWith($id)) {
            return response()->json(['message' => 'You are already friends.'], 422);
        }

        // Check if a friend request already exists between the sender and recipient
        if ($sender->hasFriendRequestPending($id) || $id->hasFriendRequestPending($sender)) {
            return response()->json(['message' => 'Friend request already pending.'], 422);
        }

        // Send friend request
        $sender->sendFriendRequest($id);

        return response()->json(['message' => 'Friend request sent successfully.']);
    }
}
