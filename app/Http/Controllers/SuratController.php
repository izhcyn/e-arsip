<?php

// app/Http/Controllers/SuratController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Http\Controllers\IndeksController;
use App\Http\Controller\TemplateSuratController;
use App\Http\Controller\SuratMasukController;
use App\Models\Indeks;
use App\Models\TemplateSurat;
use Illuminate\Support\Facades\Storage;
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
        // Validate input
        $request->validate([
            'tanggal' => 'required|date',
            'no_surat' => 'required|string',
            'perihal' => 'required|string',
            'lampiran' => 'required|string',
            'kepada' => 'required|string',
            'alamat' => 'required|string',
            'isi_surat' => 'required|string',
            'penulis' => 'required|string',
            'jabatan' => 'required|string',
        ]);

        // Handle signature upload if present
        $signaturePath = null;
        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('signatures', 'public');
        }

        // Data for the PDF view
        $data = [
            'tanggal' => $request->tanggal,
            'no_surat' => $request->no_surat,
            'kode_indeks' => $request->indeks, // Pass kode_indeks
            'kode_surat' => $request->kode_surat, // Assume kode_surat is provided
            'perihal' => $request->perihal,
            'lampiran' => $request->lampiran,
            'kepada' => $request->kepada,
            'alamat' => $request->alamat,
            'isi_surat' => $request->isi_surat,
            'penulis' => $request->penulis,
            'jabatan' => $request->jabatan,
            'signature' => $signaturePath, // Pass signature path to the view
        ];

        // Generate PDF
        $pdf = PDF::loadView('pdf.surat', $data);

        // Save PDF to storage
        $pdfPath = 'surat_keluar/surat_' . $request->no_surat . '.pdf';
        Storage::put('public/' . $pdfPath, $pdf->output());

        // Store to database (optional)
        // ...

        // Return download response
        return $pdf->download('surat_' . $request->no_surat . '.pdf');
    }


    public function balasSurat($id)
    {
        // Retrieve the specific surat masuk data using the provided ID
        $suratMasuk = SuratMasuk::findOrFail($id);
        $indeks = Indeks::all();
        $templates = TemplateSurat::all();

        // Return the 'balas_surat' view with the surat masuk data
        return view('super_admin.balas_surat', compact('suratMasuk', 'indeks', 'templates'));
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
