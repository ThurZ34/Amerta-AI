<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->business) {
            if (! $request->routeIs('setup-bisnis') &&
                ! $request->routeIs('setup-bisnis.store') &&
                ! $request->routeIs('dashboard-selection') &&
                ! $request->routeIs('dashboard-selection.join')) {
                return redirect()->route('dashboard-selection');
            }
        }

        if ($user && $user->business) {
            if ($request->routeIs('setup-bisnis')) {
                return redirect()->route('analisis.dashboard');
            }
        }

        return $next($request);
    }
}
