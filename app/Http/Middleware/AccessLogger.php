<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AccessLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info(json_encode(['[START]', $request->method(), $request->path(), new DateTime()]));
        Log::debug(json_encode([$request->json()]));

        $response = $next($request);

        // Log::debug(json_encode([$response]));
        Log::info(json_encode(['[END]', $request->method(), $request->path(), new DateTime()]));

        return $response;
    }
}
