<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\NewMessageEvent;
use App\Notifications\NewMessageNotification;
class SendNewMessageNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewMessageEvent $event)
    {
        $message = $event->message;
        $conversation = $message->conversation;
        $users = $conversation->where('user_id', '!=', $message->user_id)->get();

        foreach ($users as $user) {
            $user->notify(new NewMessageNotification($message));
        }
    }
}
