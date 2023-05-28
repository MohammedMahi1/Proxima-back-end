<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\NewMessageEvent;
use Pusher\Pusher;
class BroadcastNewMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    use InteractsWithQueue;

    public function handle(NewMessageEvent $event)
    {
        $message = $event->message;
        $conversation = $message->conversation;

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );

        $pusher->trigger('conversation.' . $conversation->id, 'new-message', $message);
    }
}
