<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\NewMessageEvent;
use App\Listeners\BroadcastNewMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Event::listen(NewMessageEvent::class, [BroadcastNewMessage::class, 'handle']);
    }
}
