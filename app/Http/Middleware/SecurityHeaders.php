<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!method_exists($response, 'header')) {
            return $response;
        }

        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->header('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        $viteUrl = app()->isLocal() ? 'http://localhost:5173 ws://localhost:5173' : '';

        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tailwindcss.com $viteUrl; " .

               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com $viteUrl; " .

               "font-src 'self' https://fonts.gstatic.com; " .
               "img-src 'self' data: blob: https: $viteUrl; " .

               "connect-src 'self' wss: https: $viteUrl;";

        $response->header('Content-Security-Policy', $csp);

        return $response;
    }
}
