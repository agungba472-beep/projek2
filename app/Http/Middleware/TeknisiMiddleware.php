<?php

namespace App\Http\Middleware;

use Closure;

class TeknisiMiddleware
{
    public function handle($request, Closure $next)
    {
        // Pastikan user sudah login dan role-nya Teknisi
        if (auth()->check() && auth()->user()->role === 'Teknisi') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak.');
    }
}
