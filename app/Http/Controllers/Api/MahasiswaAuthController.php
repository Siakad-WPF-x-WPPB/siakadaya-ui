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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        if (!$mahasiswa || !Hash::check($request->password, $mahasiswa->password)) {
            return response()->json(['message' => 'Email/NRP atau password salah'], 401);
        }

        $token = $mahasiswa->createToken('mahasiswa-app-token')->plainTextToken;

        return response()->json([
            'message' => 'Login mahasiswa berhasil',
            'user' => [
                'id' => $mahasiswa->id,
                'prodi_id' => $mahasiswa->prodi_id,
                'kelas_id' => $mahasiswa->kelas_id,
                'nama_prodi' => $mahasiswa->programStudi->nama ?? null,
                'nama_kelas' => $mahasiswa->kelas->pararel ?? null,
                'nrp' => $mahasiswa->nrp,
                'nama' => $mahasiswa->nama,
                'jenis_kelamin' => $mahasiswa->jenis_kelamin,
                'telepon' => $mahasiswa->telepon,
                'email' => $mahasiswa->email,
                'agama' => $mahasiswa->agama,
                'semester' => $mahasiswa->semester,
                'tanggal_lahir' => $mahasiswa->tanggal_lahir,
                'tanggal_masuk' => $mahasiswa->tanggal_masuk,
                'status' => $mahasiswa->status,
                'alamat_jalan' => $mahasiswa->alamat_jalan,
                'provinsi' => $mahasiswa->provinsi,
                'kode_pos' => $mahasiswa->kode_pos,
                'negara' => $mahasiswa->negara,
                'kelurahan' => $mahasiswa->kelurahan,
                'kecamatan' => $mahasiswa->kecamatan,
                'kota' => $mahasiswa->kota,
                'created_at' => $mahasiswa->created_at,
                'updated_at' => $mahasiswa->updated_at,
                // tambahkan field lain sesuai kebutuhan
            ],
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