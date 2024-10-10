<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk; // Model SuratMasuk
use Illuminate\Http\Request;
use App\Models\Indeks;
use Illuminate\Support\Facades\Storage;


class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        // Get the number of records to display per page from the request, default to 5
        $perPage = $request->get('limit', 5);

        // Fetch the records with pagination based on the selected limit
        $suratMasuk = SuratMasuk::orderBy('created_at', 'desc')->paginate($perPage);

        // Fetch total surat masuk per month (group by month)
        $totalSuratPerBulanMasuk = SuratMasuk::selectRaw('MONTH(tanggal_diterima) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Fetch index usage in Surat Masuk
        $indeksUsageMasuk = SuratMasuk::selectRaw('kode_indeks, COUNT(*) as total')
            ->groupBy('kode_indeks')
            ->pluck('total', 'kode_indeks');

        // Fetch indeks data for the form
        $indeks = Indeks::all();

        // Send the data to the view
        return view('super_admin.suratmasuk', compact('suratMasuk', 'indeks', 'totalSuratPerBulanMasuk', 'indeksUsageMasuk'));
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
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:15360',
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
            // Set zona waktu ke Indonesia (Waktu Indonesia Barat)
            date_default_timezone_set('Asia/Jakarta');

            // Mengambil file dari request
            $file = $request->file('dokumen');

            // Membuat nama file unik dengan waktu lokal Indonesia (format YmdHis)
            $fileName = date('dmyHis') . '_' . $file->getClientOriginalName(); // Format contoh: 20240924123015_FileName.jpg

            // Menyimpan file ke storage dengan folder 'uploads' dan nama file yang sudah ditentukan
            $path = $file->storeAs('uploads', $fileName, 'public');

            // Simpan nama file yang dihasilkan ke dalam database
            $suratMasuk->dokumen = $path;
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
