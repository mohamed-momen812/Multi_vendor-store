<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderCreateNotification
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
    public function handle(object $event): void
    {
        $store_id = $event->order->store_id;

        $user = User::where('store_id', $store_id)->first();
        $user->notify(new OrderCreatedNotification($event->order));

        // $users = User::where("store_id", $store_id)->get();
        // Notification::send($users, new OrderCreatedNotification($event->order));

    }
}
