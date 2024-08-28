<?php

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationErrorException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
            'withStatus' => \App\Http\Middleware\CheckedStatusMiddleware::class,
            'withStatusCollection' => \App\Http\Middleware\CheckedStatusCollectionMiddleware::class,
        ]);
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ValidationErrorException $e, $request) {
            return $e->render($request);
        });

        $exceptions->renderable(function (NotFoundException $e, $request) {
            return $e->render($request);
        });
    })->create();
