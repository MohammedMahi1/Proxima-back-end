<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications;

        return response()->json($notifications);
    }

    public function update(Request $request, $notificationId)
    {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return response()->json(null, 204);
    }
}
