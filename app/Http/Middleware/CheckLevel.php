<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$levels
     */
    public function handle(Request $request, Closure $next, ...$levels): Response
    {
        // Pastikan user sudah login
        if (! Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Ambil nama level user saat ini. Relasinya harus dipanggil ($user->level)
        $userLevel = $user->level ? $user->level->level_name : null;

        // Cek apakah nama level user ada di dalam daftar parameter $levels yang dilempar dari rute
        if ($userLevel && in_array($userLevel, $levels)) {
            return $next($request);
        }

        // Jika tidak punya akses, lempar pesan error 403 (Forbidden)
        abort(403, 'Anda tidak memiliki hak akses (Otorisasi) untuk halaman ini.');
    }
}
