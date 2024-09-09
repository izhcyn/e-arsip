<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar; // Model SuratMasuk
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    public function index()
    {
        // Ambil semua data dari tabel surat_masuk
        $suratKeluar = SuratKeluar::all();

        // Kirim data ke view
        return view('super_admin.suratkeluar', compact('suratKeluar'));
    }
}
