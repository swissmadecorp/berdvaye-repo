<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command("email:tracking")
            ->dailyAt('18:00')
            ->timezone('America/New_York');
    })
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias(
            ['role' => Spatie\Permission\Middleware\RoleMiddleware::class],
            ['permission' => Spatie\Permission\Middleware\PermissionMiddleware::class],
            ['role_or_permission' => Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class],
            ['Excel' => Maatwebsite\Excel\Facades\Excel::class],
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
