<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Indeks;
use Carbon\Carbon;
 // Tambahkan ini di bagian atas file

 class IndeksController extends Controller
 {
     public function index()
     {
        $indeks = Indeks::all(); // Mengambil semua data dari tabel 'indeks'
        // Kirimkan data ke view
        return view('super_admin.indeks', compact('indeks'));
     }

     public function create()
     {
         return view('super_admin.create_indeks');
     }

     public function store(Request $request)
     {
        $request->validate([
            'kode_indeks' => 'required|string|max:50|unique:indeks,kode_indeks',
            'kode_surat' => 'required|string|max:50',
            'judul_indeks' => 'required|string|max:255',
            'detail_indeks' => 'required|string|max:255',
        ]);


         // Simpan data indeks
         Indeks::create([
             'kode_indeks' => $request->kode_indeks,
             'kode_surat' => $request->kode_surat,
             'judul_indeks' => $request->judul_indeks,
             'detail_indeks' => $request->detail_indeks,
         ]);

         return redirect()->route('indeks.index')->with('success', 'Indeks berhasil ditambahkan');
     }

     public function edit($id)
     {
         // Temukan satu item berdasarkan primary key 'indeks_id'
         $indeks = Indeks::findOrFail($id);

         // Kirimkan data single item ke view
         return view('super_admin.edit_indeks', compact('indeks'));
     }


    public function update(Request $request, $id)
    {
        $indeks = Indeks::find($id);
        // Gunakan indeks_id

        // Validasi input
        $request->validate([
            'kode_indeks' => 'required|string|max:50|unique:indeks,kode_indeks,' . $id . ',indeks_id',
            'kode_surat' => 'required|string|max:50',
            'judul_indeks' => 'required|string|max:255',
            'detail_indeks' => 'required|string|max:255',
        ]);

        // Update data
        $indeks->kode_indeks = $request->kode_indeks;
        $indeks->kode_surat = $request->kode_surat;
        $indeks->judul_indeks = $request->judul_indeks;
        $indeks->detail_indeks = $request->detail_indeks;

        $indeks->save(); // Simpan perubahan

        return redirect()->route('indeks.index')->with('success', 'Indeks berhasil diperbarui');
    } // Tambahkan ini di bagian atas file



    public function destroy($id)
    {
        $indeks = Indeks::find($id); // Gunakan indeks_id

        if (!$indeks) {
            return redirect()->route('indeks.index')->with('error', 'Indeks tidak ditemukan');
        }

        $indeks->delete();

        return redirect()->route('indeks.index')->with('success', 'Indeks berhasil dihapus');
    }




    public function show($id){
        $Indeks = indeks::find($id);
        if (!$Indeks){
            return redirect()->route(Indeks.index)->with('error', 'Indeks tidak ditemukan');
        }
        return view('indeks.show', compact('Indeks'));
    }


}
