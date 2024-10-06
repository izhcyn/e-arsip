<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar; // Model SuratMasuk
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Kirim data ke view
        return view('super_admin.laporan');
    }
}
