<?php

namespace App\Http\Middleware;

use Closure;

class MahasiswaMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'Mahasiswa') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak.');
    }
}
