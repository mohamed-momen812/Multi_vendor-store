<?php

use App\Http\Middleware\MarkNotificationAsRead;
use App\Http\Middleware\SetAppLocale;
use App\Http\Middleware\UpdateUserLastActiveAt;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            UpdateUserLastActiveAt::class, // add global middleware to check UserLastActiveAt, and must be at end to access to the session middleware to get user
            MarkNotificationAsRead::class, // custom middleware
            SetAppLocale::class
        ]);
        $middleware->api(append: [
            // CheckApiToken::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
