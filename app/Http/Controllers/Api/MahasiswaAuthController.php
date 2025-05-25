<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa; // Pastikan model Mahasiswa sudah benar
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class MahasiswaAuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     // ... kode register Anda ...
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

        // Pastikan relasi 'programStudi' dan 'kelas' ada di model Mahasiswa
        // dan dapat diakses seperti ini.
        // Jika nama relasi atau nama field berbeda, sesuaikan.
        // Contoh: $mahasiswa->load(['programStudi', 'kelas']); // Eager loading jika diperlukan

        $token = $mahasiswa->createToken('mahasiswa-app-token')->plainTextToken;

        return response()->json([
            'message' => 'Login mahasiswa berhasil',
            'user' => [
                'id' => $mahasiswa->id,
                'prodi_id' => $mahasiswa->prodi_id,
                'kelas_id' => $mahasiswa->kelas_id,
                'nama_prodi' => $mahasiswa->programStudi->nama ?? null, // Diambil dari relasi programStudi, field 'nama'
                'nama_kelas' => $mahasiswa->kelas->pararel ?? null,   // Diambil dari relasi kelas, field 'pararel' (pastikan nama field ini benar)
                'nrp' => $mahasiswa->nrp,
                'nama' => $mahasiswa->nama,
                'jenis_kelamin' => $mahasiswa->jenis_kelamin,
                'telepon' => $mahasiswa->telepon,
                'email' => $mahasiswa->email,
                'agama' => $mahasiswa->agama,
                'semester' => $mahasiswa->semester,
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->toIso8601String() : null, // Format tanggal
                'tanggal_masuk' => $mahasiswa->tanggal_masuk ? $mahasiswa->tanggal_masuk->toIso8601String() : null, // Format tanggal
                'status' => $mahasiswa->status,
                'alamat_jalan' => $mahasiswa->alamat_jalan,
                'provinsi' => $mahasiswa->provinsi,
                'kode_pos' => $mahasiswa->kode_pos,
                'negara' => $mahasiswa->negara,
                'kelurahan' => $mahasiswa->kelurahan,
                'kecamatan' => $mahasiswa->kecamatan,
                'kota' => $mahasiswa->kota,
                'created_at' => $mahasiswa->created_at ? $mahasiswa->created_at->toIso8601String() : null, // Format tanggal
                'updated_at' => $mahasiswa->updated_at ? $mahasiswa->updated_at->toIso8601String() : null, // Format tanggal
            ],
            'token' => $token
        ]);
    }

    public function profile(Request $request)
    {
        $mahasiswa = $request->user();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Pengguna tidak diautentikasi.'], 401);
        }
        return response()->json([
            'user' => [
                'id' => $mahasiswa->id,
                'prodi_id' => $mahasiswa->prodi_id,
                'kelas_id' => $mahasiswa->kelas_id,
                'nama_prodi' => $mahasiswa->programStudi->nama ?? null,
                'nama_kelas' => $mahasiswa->kelas->pararel ?? null, // Pastikan 'pararel' adalah nama field yang benar untuk nama kelas
                'nrp' => $mahasiswa->nrp,
                'nama' => $mahasiswa->nama,
                'jenis_kelamin' => $mahasiswa->jenis_kelamin,
                'telepon' => $mahasiswa->telepon,
                'email' => $mahasiswa->email,
                'agama' => $mahasiswa->agama,
                'semester' => $mahasiswa->semester,
                'tanggal_lahir' => $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->toIso8601String() : null,
                'tanggal_masuk' => $mahasiswa->tanggal_masuk ? $mahasiswa->tanggal_masuk->toIso8601String() : null,
                'status' => $mahasiswa->status,
                'alamat_jalan' => $mahasiswa->alamat_jalan,
                'provinsi' => $mahasiswa->provinsi,
                'kode_pos' => $mahasiswa->kode_pos,
                'negara' => $mahasiswa->negara,
                'kelurahan' => $mahasiswa->kelurahan,
                'kecamatan' => $mahasiswa->kecamatan,
                'kota' => $mahasiswa->kota,
                'created_at' => $mahasiswa->created_at ? $mahasiswa->created_at->toIso8601String() : null,
                'updated_at' => $mahasiswa->updated_at ? $mahasiswa->updated_at->toIso8601String() : null,
            ]
            // Jika Anda ingin data profile ada di root JSON (tanpa key 'user'):
            // 'id' => $mahasiswa->id,
            // 'prodi_id' => $mahasiswa->prodi_id,
            // // ... dan seterusnya untuk semua field
            // 'nama_prodi' => $mahasiswa->programStudi->nama ?? null,
            // 'nama_kelas' => $mahasiswa->kelas->pararel ?? null,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout mahasiswa berhasil']);
    }
}
