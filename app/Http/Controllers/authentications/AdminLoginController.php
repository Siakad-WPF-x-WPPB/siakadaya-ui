<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest; // Anda bisa buat request validasi sendiri

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout'); // 'guest:admin'
    }

    public function showLoginForm()
    {
        return view('auth.admin-login'); // Buat view: resources/views/auth/admin-login.blade.php
    }

    public function login(Request $request) // Ganti LoginRequest dengan Request atau buat LoginRequest khusus Admin
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Coba login dengan guard 'admin'
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard')); // Arahkan ke dashboard admin
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records for an admin.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}