<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
           '/register',
          '/login',
            '/products',
            '/products/{id}',
        ]);
        // Add Sanctum's middleware for stateful requests
        $middleware->alias([
            'sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'abilities' => CheckAbilities::class,  // For checking all abilities
            'ability' => CheckForAnyAbility::class,  // For checking at least one ability
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
