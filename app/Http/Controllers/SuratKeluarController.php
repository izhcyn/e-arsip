<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar; // Model SuratMasuk
use Illuminate\Http\Request;
use App\Models\Indeks;

class SuratKeluarController extends Controller
{
    public function index()
    {
        // Ambil semua data dari tabel surat_masuk
        $suratKeluar = SuratKeluar::all();
        $indeks = Indeks::all();

        // Kirim data ke view
        return view('super_admin.suratkeluar', compact('suratKeluar', 'indeks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required|string|max:255',
            'kode_indeks' => 'required|string|max:50',
            'perihal' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'tanggal_keluar' => 'required|date',
            'dokumen' => 'required|string|max:255',

        ]);

        SuratKeluar::create([
            'no_surat' => $request->no_surat,
            'kode_indeks' => $request->kode_indeks,
            'perihal' => $request->perihal,
            'penulis' => $request->penulis,
            'penerima' => $request->penerima,
            'tanggal_keluar' => $request->tanggal_keluar,
            'dokumen' => $request->dokumen,
            ]);

            return redirect()->route('suratkeluar.index')->with('success', 'Surat keluar berhasil ditambahkan.');
    }
}
