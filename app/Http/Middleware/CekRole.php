<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
{
    // Cek apakah user sudah login & apakah role-nya sesuai daftar yang diizinkan
    if (in_array($request->user()->role, $roles)) {
        return $next($request);
    }

    // Kalau tidak cocok, tendang keluar (403 Forbidden)
    abort(403, 'Maaf, Anda tidak punya akses ke halaman ini.');
}
}
