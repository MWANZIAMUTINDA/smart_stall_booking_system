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

    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | WEB MIDDLEWARE GROUP
        |--------------------------------------------------------------------------
        | Runs AFTER session + auth are initialized
        */
        $middleware->web(append: [
            \App\Http\Middleware\RestrictionMiddleware::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ALIASES
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            'admin'      => \App\Http\Middleware\AdminMiddleware::class,
            'officer'    => \App\Http\Middleware\OfficerMiddleware::class,
            'restricted' => \App\Http\Middleware\RestrictionMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();