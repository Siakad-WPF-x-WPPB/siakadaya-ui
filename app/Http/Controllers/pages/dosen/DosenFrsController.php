<?php

namespace App\Http\Controllers\pages\dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class DosenFrsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dosen.frs.index');
    }
}
