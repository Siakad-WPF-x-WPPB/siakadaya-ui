<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa; // Gunakan model Mahasiswa
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class MahasiswaAuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:mahasiswas'], // Validasi ke tabel mahasiswas
    //         'nim' => ['required', 'string', 'unique:mahasiswas'], // Contoh
    //         'password' => ['required', 'confirmed', Password::defaults()],
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $mahasiswa = Mahasiswa::create([
    //         'nama' => $request->nama,
    //         'email' => $request->email,
    //         'nrp' => $request->nim,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     $token = $mahasiswa->createToken('mahasiswa-app-token')->plainTextToken; // Token untuk mahasiswa

    //     return response()->json([
    //         'message' => 'Registrasi mahasiswa berhasil',
    //         'user' => $mahasiswa, // user sekarang adalah instance Mahasiswa
    //         'token' => $token
    //     ], 201);
    // }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email', // atau 'nim' => 'required|string'
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cari mahasiswa berdasarkan email (atau NIM)
        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        if (!$mahasiswa || !Hash::check($request->password, $mahasiswa->password)) {
            return response()->json(['message' => 'Email/NRP atau password salah'], 401);
        }

        // Buat token untuk mahasiswa yang berhasil diautentikasi
        $token = $mahasiswa->createToken('mahasiswa-app-token')->plainTextToken;

        return response()->json([
            'message' => 'Login mahasiswa berhasil',
            'user' => $mahasiswa,
            'token' => $token
        ]);
    }

    public function profile(Request $request)
    {
        // $request->user() akan mengembalikan instance Mahasiswa karena tokennya milik Mahasiswa
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout mahasiswa berhasil']);
    }
}