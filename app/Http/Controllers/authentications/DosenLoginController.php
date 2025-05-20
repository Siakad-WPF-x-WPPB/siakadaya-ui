<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest; // Anda bisa buat request validasi sendiri

class DosenLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:dosen')->except('logout'); // 'guest:dosen'
    }

    public function showLoginForm()
    {
        $pageConfigs = ['myLayout' => 'blank'];
        return view('pages.login.login-dosen', ['pageConfigs' => $pageConfigs]);
    }

    public function login(Request $request) // Ganti LoginRequest dengan Request atau buat LoginRequest khusus dosen
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Coba login dengan guard 'dosen'
        if (Auth::guard('dosen')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dosen-dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records for an dosen.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('dosen')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}