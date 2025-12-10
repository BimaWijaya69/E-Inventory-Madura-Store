<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PreventBackHistory; // Middleware kustom

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tambahkan middleware ke group 'web'
        $middleware->group('web', [
            \Illuminate\Session\Middleware\StartSession::class, // Start session terlebih dahulu
            PreventBackHistory::class, // Middleware kustom Anda
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
