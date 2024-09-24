<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk; // Model SuratMasuk
use Illuminate\Http\Request;
use App\Models\Indeks;
use Illuminate\Support\Facades\Storage;


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
            'dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048', // Validasi dokumen sebagai file
        ]);

        // Simpan file jika ada
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Menggunakan nama asli ditambah timestamp untuk menghindari konflik
            $path = $file->storeAs('uploads', $fileName, 'public'); // Simpan file dengan nama asli
        } else {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan. Pastikan Anda mengunggah file.');
        }

        // Simpan data ke dalam tabel surat_masuk
        SuratMasuk::create([
            'no_surat' => $request->no_surat,
            'kode_indeks' => $request->kode_indeks,
            'asal_surat' => $request->asal_surat,
            'perihal' => $request->perihal,
            'penerima' => $request->penerima,
            'tanggal_diterima' => $request->tanggal_diterima,
            'dokumen' => $path, // Simpan path dokumen ke database
        ]);

        // Redirect kembali ke halaman surat masuk dengan pesan sukses
        return redirect()->route('suratmasuk.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);
        $indeks = Indeks::all(); // Assuming there's a related model for 'Indeks'
        return view('super_admin.edit_suratmasuk', compact('suratMasuk', 'indeks'));
    }

    // Function to update the letter in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_surat' => 'required|string|max:255',
            'kode_indeks' => 'required',
            'asal_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'tanggal_diterima' => 'required|date',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png'
        ]);

        $suratMasuk = SuratMasuk::findOrFail($id);
        $suratMasuk->no_surat = $request->no_surat;
        $suratMasuk->kode_indeks = $request->kode_indeks;
        $suratMasuk->asal_surat = $request->asal_surat;
        $suratMasuk->perihal = $request->perihal;
        $suratMasuk->penerima = $request->penerima;
        $suratMasuk->tanggal_diterima = $request->tanggal_diterima;

        // Handle the file upload if a new document is uploaded
        if ($request->hasFile('dokumen')) {
            // Delete the old file if it exists
            if ($suratMasuk->dokumen && Storage::exists('public/' . $suratMasuk->dokumen)) {
                Storage::delete('public/' . $suratMasuk->dokumen);
            }

            // Store the new file and save the path
            $filePath = $request->file('dokumen')->store('dokumen', 'public');
            $suratMasuk->dokumen = $filePath;
        }

        $suratMasuk->save();

        return redirect()->route('suratmasuk.index')->with('success', 'Surat masuk berhasil diupdate.');
    }


    public function destroy($id)
    {
        // Temukan surat masuk berdasarkan ID
        $suratMasuk = SuratMasuk::findOrFail($id);

        // Hapus surat masuk dari database
        $suratMasuk->delete();

        // Redirect ke halaman surat masuk dengan pesan sukses
        return redirect()->route('suratmasuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }
}
