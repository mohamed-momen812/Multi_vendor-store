<?php

use App\Http\Middleware\MarkNotificationAsRead;
use App\Http\Middleware\UpdateUserLastActiveAt;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            UpdateUserLastActiveAt::class, // add global middleware to check UserLastActiveAt, and must be at end to access to the session middleware to get user
            MarkNotificationAsRead::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
