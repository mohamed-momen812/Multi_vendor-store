<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkNotificationAsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if user push on notification on notifications in dashboard, the url contain notification_id query name, if that mark it as readed
        $notification_id = $request->query("notification_id");
        if ($notification_id) {
            $user = $request->user();
            if($user) {
                $notification = $user->notifications()->where("id", $notification_id)->first();
                if($notification) {
                    $notification->markAsRead(); // markAsRead() change the read_at in notification table
                }
            }
        }
        return $next($request);
    }
}
