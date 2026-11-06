<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function index()
    {
        try {
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            return view('login');
        } catch (\Throwable $e) {
            Log::error('Gagal membuka halaman login: '.$e->getMessage());
            Alert::toast('Terjadi kesalahan saat memuat halaman login.', 'error');

            return view('login');
        }
    }

    public function actionLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                Alert::toast('Login berhasil, selamat datang '.Auth::user()->name.'!', 'success');

                return redirect()->intended(route('dashboard'));
            }

            Alert::toast('Email atau password salah.', 'error');

            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        } catch (\Throwable $e) {
            Log::error('Gagal login: '.$e->getMessage());
            Alert::toast('Terjadi kesalahan saat login. Silakan coba lagi.', 'error');

            return back()->onlyInput('email');
        }
    }

    public function actionLogout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Alert::toast('Anda telah logout.', 'success');

            return redirect()->route('login');
        } catch (\Throwable $e) {
            Log::error('Gagal logout: '.$e->getMessage());
            Alert::toast('Terjadi kesalahan saat logout.', 'error');

            return redirect()->route('dashboard');
        }
    }
}
