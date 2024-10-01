<?php

// app/Http/Controllers/SuratController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\SuratKeluar;
use App\Http\Controllers\IndeksController;
use App\Http\Controller\TemplateSuratController;
use App\Models\Indeks;
use App\Models\TemplateSurat;
use PDF;

class SuratController extends Controller
{
    public function create()
    {
        // Fetch the required data
        $indeks = Indeks::all();  // Assuming you have an Indeks model
        $templates = TemplateSurat::all();

        // Pass the data to the view
        return view('super_admin.buatsurat', compact('indeks', 'templates'));
    }


    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'tanggal' => 'required|date',
            'no_surat' => 'required|string',
            'indeks' => 'required|string',
            'perihal' => 'required|string',
            'kepada' => 'required|string',
            'alamat' => 'required|string',
            'isi_surat' => 'required|string',
            'penulis' => 'required|string',
            'jabatan' => 'required|string',
            'signature' => 'nullable|file|mimes:png',
            'lampiranUpload' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // Simpan data ke tabel 'surats'
        $surat = new Surat();
        $surat->tanggal = $request->tanggal;
        $surat->no_surat = $request->no_surat;
        $surat->indeks = $request->indeks;
        $surat->perihal = $request->perihal;
        $surat->kepada = $request->kepada;
        $surat->alamat = $request->alamat;
        $surat->isi_surat = $request->isi_surat;
        $surat->penulis = $request->penulis;
        $surat->jabatan = $request->jabatan;
        $surat->notes = $request->notes;
        $surat->template_surat = $request->templateSurat;

        // Simpan tanda tangan (jika ada)
        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('signatures', 'public');
            $surat->signature = $signaturePath;
        }

        // Simpan lampiran (jika ada)
        if ($request->hasFile('lampiranUpload')) {
            $lampiranPath = $request->file('lampiranUpload')->store('lampiran', 'public');
            $surat->lampiran = $lampiranPath;
        }

        $surat->save();  // Simpan ke tabel 'surats'

        // Simpan ke tabel 'surat_keluar' dengan mengonversi `tanggal` ke `tanggal_keluar`
        $suratKeluar = new SuratKeluar();
        $suratKeluar->no_surat = $surat->no_surat;
        $suratKeluar->kode_indeks = $surat->indeks;
        $suratKeluar->perihal = $surat->perihal;
        $suratKeluar->penerima = $surat->kepada;
        $suratKeluar->penulis = $surat->penulis;
        $suratKeluar->tanggal_keluar = $surat->tanggal; // Menggunakan tanggal dari input
        $suratKeluar->dokumen = $this->generatePdf($surat);
        $suratKeluar->save();  // Simpan ke tabel 'surat_keluar'

        return $this->downloadPdf($surat);
    }

    private function generatePdf($surat)
    {
        // Data untuk PDF
        $data = [
            'tanggal' => $surat->tanggal,
            'no_surat' => $surat->no_surat,
            'perihal' => $surat->perihal,
            'kepada' => $surat->kepada,
            'alamat' => $surat->alamat,
            'isi_surat' => $surat->isi_surat,
            'penulis' => $surat->penulis,
            'jabatan' => $surat->jabatan,
        ];

        // Generate PDF
        $pdf = PDF::loadView('pdf.surat', $data);
        $pdfPath = 'surat_keluar/surat_' . $surat->no_surat . '.pdf';
        \Storage::put($pdfPath, $pdf->output());

        return $pdfPath;
    }

    private function downloadPdf($surat)
    {
        // Data untuk PDF
        $data = [
            'tanggal' => $surat->tanggal,
            'no_surat' => $surat->no_surat,
            'perihal' => $surat->perihal,
            'kepada' => $surat->kepada,
            'alamat' => $surat->alamat,
            'isi_surat' => $surat->isi_surat,
            'penulis' => $surat->penulis,
            'jabatan' => $surat->jabatan,
        ];

        // Generate PDF untuk di-download
        $pdf = PDF::loadView('pdf.surat', $data);
        return $pdf->download('surat_' . $surat->no_surat . '.pdf');
    }


    public function balasSurat($id)
    {
        // Retrieve the specific surat masuk data using the provided ID
        $suratMasuk = SuratMasuk::findOrFail($id);

        // Return the 'balas_surat' view with the surat masuk data
        return view('balas_surat', compact('suratMasuk'));
    }

    public function storeBalasSurat(Request $request, $id)
    {
        // Validation
        $request->validate([
            'reply' => 'required',
        ]);

        // Logic to store the reply in the database or send the reply

        return redirect()->route('suratmasuk.index')->with('success', 'Balasan surat berhasil dikirim.');
    }


}
