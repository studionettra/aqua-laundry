<?php

use App\Http\Middleware\CheckLevel;
use App\Http\Middleware\NoCache;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use RealRashid\SweetAlert\Facades\Alert;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'nochace' => NoCache::class,
            'checklevel' => CheckLevel::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (! $request->expectsJson()) {
                Alert::toast('Sesi Anda telah habis, silakan login kembali.', 'warning');

                return redirect()->route('login');
            }
        });

        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if (! $request->expectsJson()) {
                Alert::toast('Halaman kedaluwarsa (419), silakan login kembali.', 'warning');

                return redirect()->route('login');
            }
        });
    })->create();
