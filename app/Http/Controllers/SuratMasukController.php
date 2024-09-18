<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk; // Model SuratMasuk
use Illuminate\Http\Request;
use App\Models\Indeks;

class SuratMasukController extends Controller
{
    public function index()
    {
        // Fetch all data from the surat_masuk table
        $suratMasuk = SuratMasuk::all();

        // Fetch data from the indeks table
        $indeks = Indeks::all();

        // Send both variables to the view
        return view('super_admin.suratmasuk', compact('suratMasuk', 'indeks'));
    }


    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'no_surat' => 'required|string|max:255',
            'kode_indeks' => 'required|string|max:50',
            'asal_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'tanggal_diterima' => 'required|date',
            'dokumen' => 'required|string|max:255',
        ]);

        // Simpan data ke dalam tabel surat_masuk
        SuratMasuk::create([
            'no_surat' => $request->no_surat,
            'kode_indeks' => $request->kode_indeks,
            'asal_surat' => $request->asal_surat,
            'perihal' => $request->perihal,
            'penerima' => $request->penerima,
            'tanggal_diterima' => $request->tanggal_diterima,
            'dokumen' => $request->dokumen,
        ]);

        // Redirect kembali ke halaman surat masuk dengan pesan sukses
        return redirect()->route('suratmasuk.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }


}
