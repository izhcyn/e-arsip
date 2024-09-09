<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk; // Model SuratMasuk
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        // Ambil semua data dari tabel surat_masuk
        $suratMasuk = SuratMasuk::all();

        // Kirim data ke view
        return view('super_admin.suratmasuk', compact('suratMasuk'));
    }
}
