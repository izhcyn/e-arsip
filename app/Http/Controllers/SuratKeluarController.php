<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar; // Model SuratMasuk
use Illuminate\Http\Request;
use App\Models\Indeks;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data dari tabel surat_masuk
        $perPage = $request->get('limit', 5); // Default to 5 records per page if not set
        $suratKeluar = SuratKeluar::orderBy('created_at', 'desc')->paginate($perPage);
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
            'dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:15360',  // Batas ukuran file maksimal 15MB
        ]);

        // Proses upload file
        if ($request->hasFile('dokumen')) {
            // Set zona waktu ke Indonesia (Waktu Indonesia Barat)
            date_default_timezone_set('Asia/Jakarta');

            // Mengambil file dari request
            $file = $request->file('dokumen');

            // Membuat nama file yang unik dengan menambahkan timestamp (format: dmyHis)
            $fileName = date('dmyHis') . '_' . $file->getClientOriginalName();

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
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:15360', // File bisa kosong, tidak wajib
        ]);

        // Temukan surat keluar berdasarkan ID
        $suratKeluar = SuratKeluar::findOrFail($id);
        $suratKeluar->no_surat = $request->no_surat;
        $suratKeluar->kode_indeks = $request->kode_indeks;
        $suratKeluar->perihal = $request->perihal;
        $suratKeluar->penulis = $request->penulis;
        $suratKeluar->penerima = $request->penerima;
        $suratKeluar->tanggal_keluar = $request->tanggal_keluar;

        // Handle the file upload if a new document is uploaded
        if ($request->hasFile('dokumen')) {
            // Set zona waktu ke Indonesia (Waktu Indonesia Barat)
            date_default_timezone_set('Asia/Jakarta');

            // Mengambil file dari request
            $file = $request->file('dokumen');

            // Membuat nama file unik dengan waktu lokal Indonesia (format YmdHis)
            $fileName = date('dmyHis') . '_' . $file->getClientOriginalName(); // Format contoh: 20240924123015_FileName.jpg

            // Menyimpan file ke storage dengan folder 'uploads' dan nama file yang sudah ditentukan
            $path = $file->storeAs('uploads', $fileName, 'public');

            // Simpan nama file yang dihasilkan ke dalam database
            $suratKeluar->dokumen = $path;
        }

        $suratKeluar->save();

        return redirect()->route('suratkeluar.index')->with('success', 'Surat keluar berhasil diupdate.');
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

