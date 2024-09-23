<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // access locale from request or cookie or config('app.locale')
        $locale = request('locale', Cookie::get('locale', config('app.locale')));

        // set locale
        App::setLocale($locale);

        // send locale in cookie to the next request
        Cookie::queue('locale', $locale, 60 * 24 * 365);

        return $next($request);
    }
}
