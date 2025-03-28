<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckWorkOrderAccess;
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
            CheckRole::class,
            CheckWorkOrderAccess::class
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'check.work.order.access' => \App\Http\Middleware\CheckWorkOrderAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
