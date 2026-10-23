<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // LOGIKA KETAT:
        // Cek apakah user login DAN role-nya ADALAH 'admin'
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // PERBAIKAN DI SINI:
        // Gunakan nama route standar resource: 'tickets.index'
        return redirect()->route('tickets.index');
    }
}
