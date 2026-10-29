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
        $middleware->redirectTo(
            guests: '/login',
            users: function () {
                if (auth()->user()?->role === 'superadmin') {
                    return route('dashboard-superadmin');
                }
                return route('dashboard');
            }
        );

        $middleware->alias([
            'subscription.access' => \App\Http\Middleware\EnsureSubscriptionActive::class,
            'employee.access' => \App\Http\Middleware\EnsureEmployeeAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
