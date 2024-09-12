<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */

     // here we can access user because user is the object claa notify method
     public function via(object $notifiable): array // $notifiable User model to send notification to
    {
        return  ['mail', 'database'];
        // $channels = ['mail', 'database'];

        // if ($notifiable->notification_preferences['order_created']['sms'] ?? false) {
        //     $channels[] = 'vonage';
        // }
        // if ($notifiable->notification_preferences['order_created']['mail'] ?? false) {
        //     $channels[] = 'mail';
        // }
        // if ($notifiable->notification_preferences['order_created']['broadcast'] ?? false) {
        //     $channels[] = 'broadcast';
        // }
        // return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage // $notifiable User model to send notification to
    {
        $addr = $this->order->billingAddress; // relation from order
        return (new MailMessage)
                    ->subject("New Order # {$this->order->number}")
                    // ->from('momen@gmail.com') // not need because we have default value in .env file
                    ->line("A new order (#{$this->order->number}) created by {$addr->name} from {$addr->country_name}")
                    ->action('View Order', url('/dashboard'))
                    ->line('Thank you for using our application!');
    }


    /**
     * data column will store in notification table
     * @param object $notifiable
     * @return array
     */
    public function toDatabase(object $notifiable): array
    {
        $addr = $this->order->billingAddress; // relation from order
        return [
            'body' =>  "A new order (#{$this->order->number}) created by {$addr->name} from {$addr->country_name}",
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
