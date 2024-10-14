<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar; // Model SuratMasuk
use Illuminate\Http\Request;
use App\Models\Indeks;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai filter dari request
        $noSurat = $request->input('noSurat');
        $indeksSurat = $request->input('indeksSurat');
        $perihal = $request->input('perihal');
        $penulis = $request->input('penulis');
        $penerima = $request->input('penerima');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Mulai query untuk surat keluar
        $query = SuratKeluar::query();

    // Terapkan filter jika ada
    if ($noSurat) {
        $query->where('no_surat', 'like', "%{$noSurat}%");
    }
    if ($indeksSurat) {
        $query->where('kode_indeks', 'like', "%{$indeksSurat}%");
    }
    if ($perihal) {
        $query->where('perihal', 'like', "%{$perihal}%");
    }
    if ($penulis) {
        $query->where('penulis', 'like', "%{$penulis}%");
    }
    if ($penerima) {
        $query->where('penerima', 'like', "%{$penerima}%");
    }
    if ($startDate && $endDate) {
        $query->whereBetween('tanggal_keluar', [$startDate, $endDate]);
    } elseif ($startDate) {
        $query->where('tanggal_keluar', '>=', $startDate);
    } elseif ($endDate) {
        $query->where('tanggal_keluar', '<=', $endDate);
    }

    // Pagination
    $perPage = $request->get('limit', 5); // Default to 5 records per page if not set
    $suratKeluar = $query->orderBy('created_at', 'desc')->paginate($perPage);

    // Fetch total surat keluar per month (group by month)
// Fetch total surat keluar per bulan (group by month)
$totalSuratPerBulan = SuratKeluar::selectRaw('MONTH(tanggal_keluar) as month, COUNT(*) as total')
    ->groupBy('month')
    ->pluck('total', 'month');


    // Fetch indeks usage counts (group by indeks)
    $indeksUsage = SuratKeluar::selectRaw('kode_indeks, COUNT(*) as total')
        ->groupBy('kode_indeks')
        ->pluck('total', 'kode_indeks');

    // Ambil semua indeks untuk dropdown di form tambah surat
    $indeks = Indeks::all();

    $user = Auth::user();

    if ($user->role == 'super_admin') {
        return view('super_admin.suratkeluar', compact('suratKeluar', 'indeks', 'totalSuratPerBulan', 'indeksUsage'));
    } elseif ($user->role == 'admin') {
        return view('admin.suratkeluar', compact('suratKeluar', 'indeks', 'totalSuratPerBulan', 'indeksUsage'));
    } elseif ($user->role == 'user') {
        return view('users.suratkeluar', compact('suratKeluar', 'indeks', 'totalSuratPerBulan', 'indeksUsage'));
    }

    // Kirim data ke view
    return view('suratkeluar', compact('suratKeluar', 'indeks', 'totalSuratPerBulan', 'indeksUsage'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_surat' => 'required|string',
            'kode_indeks' => 'required|string',
            'perihal' => 'required|string',
            'penulis' => 'required|string',
            'penerima' => 'required|string',
            'tanggal_keluar' => 'required|date',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // Handle dokumen upload jika ada
        $dokumenPath = null;
        if ($request->hasFile('dokumen')) {
            // Set zona waktu ke Indonesia (Waktu Indonesia Barat)
            date_default_timezone_set('Asia/Jakarta');

            // Mengambil file dari request dan membuat nama file unik
            $file = $request->file('dokumen');
            $fileName = date('dmyHis') . '_' . $file->getClientOriginalName();
            $dokumenPath = $file->storeAs('uploads', $fileName, 'public');
        }

        // Simpan surat keluar ke database
        SuratKeluar::create([
            'no_surat' => $request->no_surat,
            'kode_indeks' => $request->kode_indeks,
            'perihal' => $request->perihal,
            'penulis' => $request->penulis,
            'penerima' => $request->penerima,
            'tanggal_keluar' => $request->tanggal_keluar,
            'dokumen' => $dokumenPath,  // Path ke dokumen yang di-upload
        ]);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('suratkeluar.index')->with('success', 'Surat keluar berhasil disimpan.');
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

    public function show($id)
{
    $suratKeluar = SuratKeluar::find($id);

    if (!$suratKeluar) {
        return redirect()->back()->with('error', 'Surat not found.');
    }

    return view('suratkeluar.show', compact('suratKeluar'));
}

}

