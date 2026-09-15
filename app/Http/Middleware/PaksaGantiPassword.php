<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaksaGantiPassword
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->wajib_ganti_password && !$request->routeIs('profile.*', 'logout')) {
            return redirect()->route('profile.edit')->with('warning', 'Silakan ganti password kamu terlebih dahulu.');
        }

        return $next($request);
    }
}