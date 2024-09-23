<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response ;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('x-api-key');
        if($token !== config('app.api_token')) {
            return Response::json([
                'message' => 'Invalid API Token'
            ], 400);
        }
        return $next($request);
    }
}
