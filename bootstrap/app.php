<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // No additional routing needed
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Trust semua proxy (Cloudflare Tunnel / cloudflared) supaya
        // Laravel mendeteksi HTTPS dari header X-Forwarded-Proto.
        // Tanpa ini: cookie session Secure tidak ter-set (login gagal)
        // dan asset di-generate sebagai http:// (mixed content, foto gagal).
        $middleware->trustProxies(at: '*');

        // Ensure web middleware group is applied
        $middleware->web();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
