<?php

use App\Http\Middleware\AccessLogger;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(AccessLogger::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (BadRequestException $e) {
            Log::info(json_encode([__METHOD__, $e->getMessage()]));
            Log::info(json_encode([__METHOD__, $e->getTraceAsString()]));
            abort(Response::HTTP_BAD_REQUEST);
        });
        $exceptions->report(function (UnauthorizedException $e) {
            Log::info(json_encode([__METHOD__, $e->getMessage()]));
            Log::info(json_encode([__METHOD__, $e->getTraceAsString()]));
            abort(Response::HTTP_UNAUTHORIZED);
        });
        $exceptions->report(function (Exception $e) {
            Log::error(json_encode([__METHOD__, $e->getMessage()]));
            Log::error(json_encode([__METHOD__, $e->getTraceAsString()]));
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    })->create();
