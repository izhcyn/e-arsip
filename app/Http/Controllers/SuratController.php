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
            'perihal' => 'required|string',
            'kepada' => 'required|string',
            'alamat' => 'required|string',
            'isi_surat' => 'required|string',
            'penulis' => 'required|string',
            'jabatan' => 'required|string',
        ]);

        // Simpan surat ke database
        $suratKeluar = new SuratKeluar();
        $suratKeluar->no_surat = $request->no_surat;
        $suratKeluar->tanggal = $request->tanggal;
        $suratKeluar->kode_indeks = $request->indeks;
        $suratKeluar->asal_surat = 'RADAR BOGOR';
        $suratKeluar->perihal = $request->perihal;
        $suratKeluar->penerima = $request->kepada;
        $suratKeluar->alamat = $request->alamat;
        $suratKeluar->isi_surat = $request->isi_surat;
        $suratKeluar->save();  // Simpan ke database

        // Data yang akan dipassing ke view PDF
        $data = [
            'tanggal' => $request->tanggal,
            'no_surat' => $request->no_surat,
            'perihal' => $request->perihal,
            'kepada' => $request->kepada,
            'alamat' => $request->alamat,
            'isi_surat' => $request->isi_surat,
            'penulis' => $request->penulis,
            'jabatan' => $request->jabatan,
        ];

        // Generate PDF dari view 'pdf.surat'
        $pdf = PDF::loadView('pdf.surat', $data);

        // Simpan PDF ke storage
        $pdfPath = 'surat_keluar/surat_' . $request->no_surat . '.pdf';
        \Storage::put($pdfPath, $pdf->output());

        // Update path PDF di database
        $suratKeluar->dokumen = $pdfPath;
        $suratKeluar->save();

        // Return download file
        return $pdf->download('surat_' . $request->no_surat . '.pdf');
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
