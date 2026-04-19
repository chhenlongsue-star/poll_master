<?php

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
        // 1. Trust all proxies (Required for Render)
        // This stops the "flash and redirect" by letting Laravel know the site is secure.
        $middleware->trustProxies(at: '*');

        // 2. Add this to ensure HTTPS is enforced on all redirects
        // This is the missing piece that prevents the redirect back to a "broken" login.
        $middleware->trustProxies(headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB);

        // 3. Register your custom Role Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();