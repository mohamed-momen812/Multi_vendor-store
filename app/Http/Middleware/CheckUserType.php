<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *  check if user->type in array passed or not if not (anauthorized) if in passed
 */
class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {

        $user = $request->user();

        if(!$user) {
            return redirect()->route("login");
        }

        if(!in_array($user->type, $types)) {
            abort(403, "You are not authorized");
        }

        return $next($request);
    }
}
