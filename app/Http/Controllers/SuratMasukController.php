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
        // Ambil data surat masuk berdasarkan ID
        $suratMasuk = SuratMasuk::findOrFail($id);

        // Ambil semua data indeks untuk dropdown
        $indeks = Indeks::all();

        // Kirim data ke view
        return view('super_admin.edit_suratmasuk', compact('suratMasuk', 'indeks'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'no_surat' => 'required|string|max:255',
            'kode_indeks' => 'required|string|max:50',
            'asal_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'tanggal_diterima' => 'required|date',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // Dokumen bisa null, validasi file
        ]);

        // Temukan surat berdasarkan ID
        $suratMasuk = SuratMasuk::findOrFail($id);

        // Simpan file baru jika ada, atau gunakan file lama
        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('uploads', 'public'); // Simpan file baru ke storage
        } else {
            $path = $suratMasuk->dokumen; // Gunakan dokumen lama
        }

        // Update data surat masuk
        $suratMasuk->update([
            'no_surat' => $request->no_surat,
            'kode_indeks' => $request->kode_indeks,
            'asal_surat' => $request->asal_surat,
            'perihal' => $request->perihal,
            'penerima' => $request->penerima,
            'tanggal_diterima' => $request->tanggal_diterima,
            'dokumen' => $path, // Update dokumen (baru atau lama)
        ]);

        // Redirect ke halaman surat masuk dengan pesan sukses
        return redirect()->route('suratmasuk.index')->with('success', 'Surat masuk berhasil diperbarui.');
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
