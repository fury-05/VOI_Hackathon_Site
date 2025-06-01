<?php // bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // Ensure this use statement is present

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add your route middleware aliases here
        $middleware->alias([
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Example existing alias
            'is_admin' => \App\Http\Middleware\IsAdmin::class, // <-- ADD THIS LINE
            // Add other custom aliases here
        ]);

        // You can also add to middleware groups if needed, e.g.:
        // $middleware->web(append: [
        //     \App\Http\Middleware\ExampleMiddleware::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
