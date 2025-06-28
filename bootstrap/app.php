<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\VerifyTwitchJwt;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1.  Make the CORS middleware run FIRST, globally
        $middleware->prepend(HandleCors::class);

        // 2.  Append your JWT middleware to the **api** group
        $middleware->api(append: [
            // VerifyTwitchJwt::class, // This was applying to all api routes
        ]);

        // 3.  Optionally register a short alias for route use
        $middleware->alias([
            'twitch.jwt' => VerifyTwitchJwt::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
