<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route; // Tambahkan ini untuk route_has
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Logika redirect berdasarkan guard
                if ($guard === 'admin') {
                    // Pastikan rute 'admin.dashboard' ada dan valid
                    if (Route::has('admin-dashboard')) { // Gunakan fasad Route
                        return redirect(route('admin-dashboard'));
                    }
                    // Fallback jika rute dashboard tidak ada (seharusnya tidak terjadi)
                    // Anda bisa melempar exception atau redirect ke path default lain
                    // Contoh: return redirect('/admin/home');
                    // Atau biarkan default jika tidak ada rute spesifik
                }
                if ($guard === 'dosen') {
                    if (Route::has('dosen-dashboard')) {
                        return redirect(route('dosen-dashboard'));
                    }
                    // Contoh: return redirect('/dosen/home');
                }
            }
        }

        return $next($request);
    }
}
