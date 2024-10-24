<?php

// app/Http/Controllers/SuratController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\Indeks;
use App\Models\TemplateSurat;
use Illuminate\Support\Facades\Storage;
use PDF;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use App\Models\Draft;

class SuratController extends Controller
{

    public function create()
    {
        // Mengambil data indeks dan template
        $indeks = Indeks::all();
        $templates = TemplateSurat::all();

        $user = Auth::user();

        if ($user->role == 'super_admin') {
            return view('super_admin.buatsurat', compact('indeks', 'templates'));
        } elseif ($user->role == 'admin') {
            return view('admin.buatsurat', compact('indeks', 'templates'));
        }

        // Mengirim data ke view
    }

    public function showDrafts()
    {
        // Fetch all drafts saved by the authenticated user, ordered by creation date (newest first)
        $drafts = Draft::where('user_id', auth()->id())
                       ->orderBy('created_at', 'desc') // Sort by 'created_at' column in descending order
                       ->get();

        $user = Auth::user();

        if ($user->role == 'super_admin') {
            return view('super_admin.drafts', compact('drafts'));
        } elseif ($user->role == 'admin') {
            return view('admin.drafts', compact('drafts'));
        }
    }


    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'tanggal' => 'required|date',
        'indeks' => 'required|string',
        'perihal' => 'required|string',
        'lampiran' => 'required|string',
        'kepada' => 'required|string',
        'alamat' => 'required|string',
        'isi_surat' => 'required|string',
        'penulis' => 'required|string',
        'jabatan' => 'required|string',
        'signature' => 'nullable|file|mimes:png',
        'notes' => 'nullable|string',
        'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
    ]);

    // Mencari data indeks yang sesuai
    $indeksData = Indeks::where('kode_indeks', $request->indeks)->first();
    if (!$indeksData) {
        return redirect()->back()->with('error', 'Indeks tidak ditemukan.');
    }

    $kodeSurat = $indeksData->kode_surat;

    // Menghitung nomor surat baru
    $newSuratNumber = (int)$indeksData->last_number + 1;
    $noSurat = str_pad($newSuratNumber, 3, '0', STR_PAD_LEFT);

    // Mengunggah tanda tangan jika ada
    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $signaturePath = $request->file('signature')->store('signatures', 'public');
    }

    // Mengunggah lampiran jika ada
    $lampiranPath = null;
    if ($request->hasFile('file_lampiran')) {
        $lampiranPath = $request->file('file_lampiran')->store('file_lampirans', 'public');
    }

    // Data untuk PDF
    $data = [
        'tanggal' => $request->tanggal,
        'no_surat' => $noSurat,
        'kode_indeks' => $request->indeks,
        'kode_surat' => $kodeSurat,
        'perihal' => $request->perihal,
        'lampiran' => $request->lampiran,
        'kepada' => $request->kepada,
        'alamat' => $request->alamat,
        'isi_surat' => $request->isi_surat,
        'penulis' => $request->penulis,
        'jabatan' => $request->jabatan,
        'signature' => $signaturePath,
        'notes' => $request->notes,
        'file_lampiran' => $lampiranPath,
    ];

    // Membuat PDF utama
    $pdf = \PDF::loadView('pdf.surat', $data);
    $pdfFileName = $request->kode_indeks . 'surat ' . $request->perihal . '.pdf';
    $pdfPath = 'surat_keluar/' . $pdfFileName;
    $fullPdfPath = storage_path('app/public/' . $pdfPath);
    $pdf->save($fullPdfPath);

    // Buat instance baru FPDI
    $pdfMerger = new Fpdi();
    $pageCount = $pdfMerger->setSourceFile($fullPdfPath);
    for ($i = 1; $i <= $pageCount; $i++) {
        $templateId = $pdfMerger->importPage($i);
        $pdfMerger->AddPage();
        $pdfMerger->useTemplate($templateId);
    }

    // Jika ada lampiran PDF, tambahkan ke dokumen utama
    if ($lampiranPath && pathinfo($lampiranPath, PATHINFO_EXTENSION) == 'pdf') {
        $lampiranFullPath = storage_path('app/public/' . $lampiranPath);
        $lampiran_pageCount = $pdfMerger->setSourceFile($lampiranFullPath);
        for ($i = 1; $i <= $lampiran_pageCount; $i++) {
            $templateId = $pdfMerger->importPage($i);
            $pdfMerger->AddPage();
            $pdfMerger->useTemplate($templateId);
        }
    }

    // Simpan PDF yang digabungkan
    $pdfMerger->Output($fullPdfPath, 'F');

    // Memperbarui nomor terakhir pada indeks
    $indeksData->last_number = $newSuratNumber;
    $indeksData->save();

    // Menyimpan data surat keluar ke database
    $suratKeluar = new SuratKeluar();
    $suratKeluar->no_surat = $noSurat;
    $suratKeluar->kode_indeks = $request->indeks;
    $suratKeluar->perihal = $request->perihal;
    $suratKeluar->penerima = $request->kepada;
    $suratKeluar->penulis = $request->penulis;
    $suratKeluar->tanggal_keluar = $request->tanggal;
    $suratKeluar->dokumen = $pdfPath;
    $suratKeluar->save();

    // Logika redireksi berdasarkan role pengguna
    $user = Auth::user();
    if ($user->role == 'super_admin') {
        $route = 'superadmin.suratkeluar';
    } elseif ($user->role == 'admin') {
        $route = 'admin.suratkeluar';
    }

    // Mengarahkan pengguna ke halaman sesuai role
    return response()->download(storage_path('app/public/surat_keluar/' . $pdfFileName));
}

public function suratKeluarAdmin()
{
    // Ambil semua surat keluar yang relevan untuk admin
    $suratKeluar = SuratKeluar::all();

    // Kirim data ke view untuk ditampilkan
    return view('admin.suratkeluar', compact('suratKeluar'));
}



    public function getLastNumber($indeks)
    {
        // Mengambil data indeks berdasarkan kode indeks
        $indeksData = Indeks::where('kode_indeks', $indeks)->first();

        if (!$indeksData) {
            return response()->json(['error' => 'Indeks tidak ditemukan'], 404);
        }

        // Mengembalikan nomor terakhir + 1
        $nextNumber = (int)$indeksData->last_number + 1;
        return response()->json(['nextNumber' => $nextNumber]);
    }

    public function balasSurat($id)
    {
        // Mengambil data surat masuk berdasarkan ID
        $suratMasuk = SuratMasuk::findOrFail($id);
        $indeks = Indeks::all(); // Data indeks jika diperlukan
        $templates = TemplateSurat::all(); // Jika ingin menggunakan template surat

        $user = Auth::user();

        if ($user->role == 'super_admin') {
            return view('super_admin.balas_surat', compact('suratMasuk', 'indeks', 'templates'));
        } elseif ($user->role == 'admin') {
            return view('admin.balas_surat', compact('suratMasuk', 'indeks', 'templates'));
        }
        return view('balas_surat', compact('suratMasuk', 'indeks', 'templates'));
    }

    public function storeBalasSurat(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'tanggal' => 'required|date',
        'indeks' => 'required|string',
        'perihal' => 'required|string',
        'lampiran' => 'required|string',
        'kepada' => 'required|string',
        'alamat' => 'required|string',
        'isi_surat' => 'required|string',
        'penulis' => 'required|string',
        'jabatan' => 'required|string',
        'signature' => 'nullable|file|mimes:png',
        'notes' => 'nullable|string',
        'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
    ]);

    // Mencari data indeks yang sesuai
    $indeksData = Indeks::where('kode_indeks', $request->indeks)->first();
    if (!$indeksData) {
        return redirect()->back()->with('error', 'Indeks tidak ditemukan.');
    }

    $kodeSurat = $indeksData->kode_surat;

    // Menghitung nomor surat baru
    $newSuratNumber = (int)$indeksData->last_number + 1;
    $noSurat = str_pad($newSuratNumber, 3, '0', STR_PAD_LEFT);

    // Mengunggah tanda tangan jika ada
    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $signaturePath = $request->file('signature')->store('signatures', 'public');
    }

    // Mengunggah lampiran jika ada
    $lampiranPath = null;
    if ($request->hasFile('file_lampiran')) {
        $lampiranPath = $request->file('file_lampiran')->store('file_lampirans', 'public');
    }

    // Data untuk PDF
    $data = [
        'tanggal' => $request->tanggal,
        'no_surat' => $noSurat,
        'kode_indeks' => $request->indeks,
        'kode_surat' => $kodeSurat,
        'perihal' => $request->perihal,
        'lampiran' => $request->lampiran,
        'kepada' => $request->kepada,
        'alamat' => $request->alamat,
        'isi_surat' => $request->isi_surat,
        'penulis' => $request->penulis,
        'jabatan' => $request->jabatan,
        'signature' => $signaturePath,
        'notes' => $request->notes,
        'file_lampiran' => $lampiranPath,
    ];

    // Membuat PDF utama
    $pdf = \PDF::loadView('pdf.surat', $data);
    $pdfFileName = $request->kode_indeks . '_balasan_' . $request->perihal . '.pdf';
    $pdfPath = 'surat_keluar/' . $pdfFileName;
    $fullPdfPath = storage_path('app/public/' . $pdfPath);
    $pdf->save($fullPdfPath);

    // Buat instance baru FPDI
    $pdfMerger = new Fpdi();

    // Tambahkan halaman dari PDF utama
    $pageCount = $pdfMerger->setSourceFile($fullPdfPath);
    for ($i = 1; $i <= $pageCount; $i++) {
        $templateId = $pdfMerger->importPage($i);
        $pdfMerger->AddPage();
        $pdfMerger->useTemplate($templateId);
    }

    // Jika ada lampiran PDF, tambahkan ke dokumen utama
    if ($lampiranPath && pathinfo($lampiranPath, PATHINFO_EXTENSION) == 'pdf') {
        $lampiranFullPath = storage_path('app/public/' . $lampiranPath);
        $lampiran_pageCount = $pdfMerger->setSourceFile($lampiranFullPath);
        for ($i = 1; $i <= $lampiran_pageCount; $i++) {
            $templateId = $pdfMerger->importPage($i);
            $pdfMerger->AddPage();
            $pdfMerger->useTemplate($templateId);
        }
    }

    // Simpan PDF yang digabungkan
    $pdfMerger->Output($fullPdfPath, 'F');

    // Memperbarui nomor terakhir pada indeks
    $indeksData->last_number = $newSuratNumber;
    $indeksData->save();

    // Menyimpan data surat keluar ke database
    $suratKeluar = new SuratKeluar();
    $suratKeluar->no_surat = $noSurat;
    $suratKeluar->kode_indeks = $request->indeks;
    $suratKeluar->perihal = $request->perihal;
    $suratKeluar->penerima = $request->kepada;
    $suratKeluar->penulis = $request->penulis;
    $suratKeluar->tanggal_keluar = $request->tanggal;
    $suratKeluar->dokumen = $pdfPath;
    $suratKeluar->save();

    // Mengembalikan file PDF yang dapat diunduh
    return response()->download(storage_path('app/public/surat_keluar/' . $pdfFileName));
}

public function saveDraft(Request $request)
{
    try {
        $draft = null;

        if ($request->has('draft_id') && !empty($request->draft_id)) {
            // Temukan draft yang ada berdasarkan ID
            $draft = Draft::find($request->draft_id);

            if ($draft) {
                // Update draft jika ditemukan
                $draft->update([
                    'tanggal' => $request->tanggal,
                    'indeks' => $request->indeks,
                    'perihal' => $request->perihal,
                    'lampiran' => $request->lampiran,
                    'kepada' => $request->kepada,
                    'alamat' => $request->alamat,
                    'isi_surat' => $request->isi_surat,
                    'penulis' => $request->penulis,
                    'jabatan' => $request->jabatan,
                    'notes' => $request->notes,
                    'user_id' => auth()->id(),
                ]);
            }
        }

        // Jika tidak ada draft_id atau draft tidak ditemukan, buat draft baru
        if (!$draft) {
            $draft = Draft::create([
                'tanggal' => $request->tanggal,
                'indeks' => $request->indeks,
                'perihal' => $request->perihal,
                'lampiran' => $request->lampiran,
                'kepada' => $request->kepada,
                'alamat' => $request->alamat,
                'isi_surat' => $request->isi_surat,
                'penulis' => $request->penulis,
                'jabatan' => $request->jabatan,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);
        }

        return response()->json(['message' => 'Draft berhasil disimpan!', 'draft_id' => $draft->id]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error menyimpan draft: ' . $e->getMessage()], 500);
    }
}




public function loadDraftById($id)
{
    // Fetch the specific draft by ID
    $draft = Draft::findOrFail($id);

    // Fetch the indeks data
    $indeks = Indeks::all();
    $templates = TemplateSurat::all();

    // Return view with draft data
    if (auth()->user()->role == 'super_admin') {
        return view('super_admin.buatsurat', compact('draft', 'indeks', 'templates'));
    } elseif (auth()->user()->role == 'admin') {
        return view('admin.buatsurat', compact('draft', 'indeks', 'templates'));
    }

    return view('buatsurat.create', compact('draft', 'indeks', 'templates'));
}


 public function show($id)
 {
     $suratKeluar = SuratKeluar::find($id);

     if (!$suratKeluar) {
         return redirect()->back()->with('error', 'Surat not found.');
     }

     return view('suratkeluar.show', compact('suratKeluar'));
 }

 public function deleteDraft($id)
 {
     try {
         // Temukan draft berdasarkan ID dan hapus
         $draft = Draft::findOrFail($id);
         $draft->delete();

         return redirect()->route('drafts.index')->with('success', 'Draft berhasil dihapus.');
     } catch (\Exception $e) {
         return redirect()->route('drafts.index')->with('error', 'Error menghapus draft: ' . $e->getMessage());
     }
 }


}
