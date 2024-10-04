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
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

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

        $indeksData = Indeks::where('kode_indeks', $request->indeks)->first();

        if (!$indeksData) {
            return redirect()->back()->with('error', 'Indeks not found.');
        }

        // Increment the last number
        $newSuratNumber = (int)$indeksData->last_number + 1;

        // Generate no_surat by only using the new number
        $noSurat = str_pad($newSuratNumber, 3, '0', STR_PAD_LEFT);

        // Handle signature upload if present
        $signaturePath = null;
        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('signatures', 'public');
        }

        // Handle attachment upload if present
        $lampiranPath = null;
        if ($request->hasFile('file_lampiran')) {
            $lampiranPath = $request->file('file_lampiran')->store('file_lampirans', 'public');
        }

        // Data for the PDF view
        $data = [
            'tanggal' => $request->tanggal,
            'no_surat' => $noSurat,
            'kode_indeks' => $request->indeks,
            'kode_surat' => $request->kode_surat,
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

        // Generate the main PDF
        $pdf = PDF::loadView('pdf.surat', $data);
        $pdfFileName = $request->kode_surat . 'surat ' . $request->perihal . '.pdf';
        $pdfPath = storage_path('app/public/surat_keluar/' . $pdfFileName);
        Storage::put('public/surat_keluar/' . $pdfFileName, $pdf->output());

        // Check if the attachment is a PDF
        if ($lampiranPath && pathinfo($lampiranPath, PATHINFO_EXTENSION) === 'pdf') {
            $fullPdfPath = storage_path('app/public/' . $pdfPath);
            $attachmentPdfPath = storage_path('app/public/' . $lampiranPath);

            // Create a new FPDI instance
            $pdfMerger = new Fpdi();
            $pageCount = $pdfMerger->setSourceFile($fullPdfPath);

            // Add pages from the main PDF
            for ($i = 1; $i <= $pageCount; $i++) {
                $templateId = $pdfMerger->importPage($i);
                $pdfMerger->AddPage();
                $pdfMerger->useTemplate($templateId);
            }

            // Add pages from the attachment PDF
            $attachmentPageCount = $pdfMerger->setSourceFile($attachmentPdfPath);
            for ($i = 1; $i <= $attachmentPageCount; $i++) {
                $templateId = $pdfMerger->importPage($i);
                $pdfMerger->AddPage();
                $pdfMerger->useTemplate($templateId);
            }

            // Save the merged PDF
            $pdfMerger->Output($fullPdfPath, 'F');
        }

        // If the attachment is an image
        if ($lampiranPath && in_array(pathinfo($lampiranPath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
            $fullPdfPath = storage_path('app/public/' . $pdfPath);

            // Create a new PDF instance with the existing PDF and add a new page for the image
            $pdfMerger = new Fpdi();
            $pageCount = $pdfMerger->setSourceFile($fullPdfPath);

            // Add pages from the main PDF
            for ($i = 1; $i <= $pageCount; $i++) {
                $templateId = $pdfMerger->importPage($i);
                $pdfMerger->AddPage();
                $pdfMerger->useTemplate($templateId);
            }

            // Add a new page for the image attachment
            $pdfMerger->AddPage();
            $pdfMerger->Image(storage_path('app/public/' . $lampiranPath), 10, 10, 190); // Adjust image size and position as needed

            // Save the merged PDF
            $pdfMerger->Output($fullPdfPath, 'F');
        }

        $indeksData->last_number = $newSuratNumber;
        $indeksData->save();

        // Store to database
        $suratKeluar = new SuratKeluar();
        $suratKeluar->no_surat = $noSurat;
        $suratKeluar->kode_indeks = $request->indeks;
        $suratKeluar->perihal = $request->perihal;
        $suratKeluar->penerima = $request->kepada;
        $suratKeluar->penulis = $request->penulis;
        $suratKeluar->tanggal_keluar = $request->tanggal;
        $suratKeluar->dokumen = $pdfPath;
        $suratKeluar->save();
        // Return download response
        return response()->download(storage_path('app/public/surat_keluar/' . $pdfFileName));
    }

    public function getLastNumber($indeks)
    {
        // Find the index by kode_indeks
        $indeksData = Indeks::where('kode_indeks', $indeks)->first();

        if (!$indeksData) {
            return response()->json(['error' => 'Indeks not found'], 404);
        }

        // Return the last number incremented by 1
        $nextNumber = (int)$indeksData->last_number + 1;

        return response()->json(['nextNumber' => $nextNumber]);
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
