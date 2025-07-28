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
        // تسجيل middleware مخصص للنظام
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'beneficiary' => \App\Http\Middleware\BeneficiaryMiddleware::class,
            'store' => \App\Http\Middleware\StoreMiddleware::class,
            'charity' => \App\Http\Middleware\CharityMiddleware::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'check.beneficiary.profile' => \App\Http\Middleware\CheckBeneficiaryProfile::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
