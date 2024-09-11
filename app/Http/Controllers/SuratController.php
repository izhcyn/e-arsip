<?php

// app/Http/Controllers/SuratController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;

class SuratController extends Controller
{
    public function create()
    {
        return view('super_admin.buatsurat'); // Tampilan form
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'no_surat' => 'required|string|max:255',
            'indeks' => 'required|string',
            'perihal' => 'required|string|max:255',
            'kepada' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'isi_surat' => 'required|string',
            'penulis' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'template_surat' => 'nullable|file|mimes:pdf,docx',
            'signature' => 'nullable|file|mimes:jpg,png,jpeg',
        ]);

        // Proses file upload (jika ada)
        $templatePath = null;
        if ($request->hasFile('template_surat')) {
            $templatePath = $request->file('template_surat')->store('templates');
        }

        $signaturePath = null;
        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('signatures');
        }

        // Simpan data surat ke database
        Surat::create([
            'tanggal' => $request->tanggal,
            'no_surat' => $request->no_surat,
            'indeks' => $request->indeks,
            'perihal' => $request->perihal,
            'lampiran' => $request->lampiran,
            'kepada' => $request->kepada,
            'alamat' => $request->alamat,
            'isi_surat' => $request->isi_surat,
            'penulis' => $request->penulis,
            'jabatan' => $request->jabatan,
            'notes' => $request->notes,
            'template_surat' => $templatePath,
            'signature' => $signaturePath,
        ]);

        return redirect()->back()->with('success', 'Surat berhasil disimpan');
    }
}
