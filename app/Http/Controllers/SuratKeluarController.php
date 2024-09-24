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
        // Validasi input dari form
        $request->validate([
            'no_surat' => 'required|string|max:255',
            'kode_indeks' => 'required|string|max:50',
            'perihal' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'tanggal_keluar' => 'required|date',
            'dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        // Proses upload file
        if ($request->hasFile('dokumen')) {
            // Mengambil file dari request
            $file = $request->file('dokumen');

            // Membuat nama file yang unik dengan menambahkan timestamp
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Menyimpan file ke storage dengan folder 'uploads'
            $path = $file->storeAs('uploads', $fileName, 'public');
        } else {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan. Pastikan Anda mengunggah file.');
        }

        // Menyimpan data surat keluar ke database
        SuratKeluar::create([
            'no_surat' => $request->no_surat,
            'kode_indeks' => $request->kode_indeks,
            'perihal' => $request->perihal,
            'penulis' => $request->penulis,
            'penerima' => $request->penerima,
            'tanggal_keluar' => $request->tanggal_keluar,
            'dokumen' => $path,  // Menyimpan path file dokumen
        ]);

        return redirect()->route('suratkeluar.index')->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Ambil data surat masuk berdasarkan ID
        $suratKeluar = SuratKeluar::findOrFail($id);

        // Ambil semua data indeks untuk dropdown
        $indeks = Indeks::all();

        // Kirim data ke view
        return view('super_admin.edit_suratkeluar', compact('suratKeluar', 'indeks'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'no_surat' => 'required|string|max:255',
            'kode_indeks' => 'required|string|max:50',
            'perihal' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'tanggal_keluar' => 'required|date',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // File bisa kosong, tidak wajib
        ]);

        // Temukan surat keluar berdasarkan ID
        $suratKeluar = SuratKeluar::findOrFail($id);

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('uploads', 'public'); // Simpan file baru ke storage
        } else {
            $path = $suratKeluar->dokumen; // Gunakan dokumen lama
        }

        $suratKeluar->update([
            'no_surat'=>$request->no_surat,
            'kode_indeks'=>$request->kode_indeks,
            'perihal'=>$request->perihal,
            'penulis'=>$request->penulis,
            'penerima'=>$request->penerima,
            'tanggal_keluar'=>$request->tanggal_keluar,
            'dokumen'=>$path,
        ]);

        $suratKeluar = new SuratKeluar();
        $suratKeluar->no_surat = $request->no_surat;
        $suratKeluar->kode_indeks = $request->kode_indeks;
        $suratKeluar->perihal = $request->perihal;
        $suratKeluar->penulis = $request->penulis;
        $suratKeluar->penerima = $request->penerima;
        $suratKeluar->tanggal_keluar = $request->tanggal_keluar;

        // Redirect ke halaman index surat keluar dengan pesan sukses
        return redirect()->route('suratkeluar.index')->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Temukan surat masuk berdasarkan ID
        $suratKeluar = SuratKeluar::findOrFail($id);

        // Hapus surat masuk dari database
        $suratKeluar->delete();

        // Redirect ke halaman surat masuk dengan pesan sukses
        return redirect()->route('suratkeluar.index')->with('success', 'Surat keluar berhasil dihapus.');
    }
}

