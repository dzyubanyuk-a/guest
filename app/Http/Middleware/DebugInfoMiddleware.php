<?php

namespace App\Http\Middleware;

use Closure;

class DebugInfoMiddleware
{
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $response->headers->set('X-Debug-Time', round(($endTime - $startTime) * 1000, 2));
        $response->headers->set('X-Debug-Memory', round(($endMemory - $startMemory) / 1024, 2));

        return $response;
    }
}
