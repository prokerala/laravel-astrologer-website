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
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\App\Exceptions\ServiceUnavailableException $e, $request) {
            \Illuminate\Support\Facades\Log::error('Service Unavailable: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getUserMessage(),
                    'success' => false
                ], 503);
            }
            
            return back()->with('error', $e->getUserMessage())->withInput();
        });
    })->create();
