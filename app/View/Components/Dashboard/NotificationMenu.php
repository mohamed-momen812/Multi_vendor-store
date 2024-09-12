<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationMenu extends Component
{
    public $notifications;
    public $newCount;


    /**
     * Create a new component instance.
     */
    public function __construct($count = 10) // when calling the component access $count
    {
        // user  has trait notifiable so each user have notifications and unreadNotifications and readNotifications relations
        $user = Auth::user();
        $this->notifications = $user->notifications()->take($count)->get(); // we can access the relation query builder from notifications(
        $this->newCount = $user->unreadNotifications()->count();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.notification-menu');
    }
}
