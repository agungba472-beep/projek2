<?php

namespace App\Http\Middleware;

use Closure;

class DosenMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'Dosen') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak.');
    }
}
