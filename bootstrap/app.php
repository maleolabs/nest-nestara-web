<?php

use App\Http\Middleware\DashboardMiddleware;
use App\Http\Middleware\Izin\RevisiIzinMiddleware;
use App\Http\Middleware\Izin\ShowIzinMiddleware;
use App\Http\Middleware\ProfileMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use Tymon\JWTAuth\Http\Middleware\RefreshToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'dashboard' => DashboardMiddleware::class,
            'show_izin' => ShowIzinMiddleware::class,
            'revisi_izin' => RevisiIzinMiddleware::class,
            'profile' => ProfileMiddleware::class,

            'jwt.auth' => Authenticate::class,
            'jwt.refresh' => RefreshToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
