<?php

namespace App\Http\Controllers\pages\dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class DosenProfileController extends Controller
{
    public function index()
    {
        $dosenId = Auth::guard('dosen')->id();
        $dosen = Dosen::with(['programStudi'])->findOrFail($dosenId);

        return view('pages.dosen.profile.index', compact('dosen'));
    }
}