<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Indeks;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

 // Tambahkan ini di bagian atas file

 class IndeksController extends Controller
 {
    public function index(Request $request)
    {
        // Get the number of records to display per page, default to 5
        $perPage = $request->get('limit', 5);

        // Initialize the query
        $query = Indeks::query();

        // Apply filters if present
        if ($request->has('kode_indeks')) {
            $query->where('kode_indeks', 'like', '%' . $request->input('kode_indeks') . '%');
        }

        if ($request->has('kode_surat')) {
            $query->where('kode_surat', 'like', '%' . $request->input('kode_surat') . '%');
        }

        if ($request->has('judul_indeks')) {
            $query->where('judul_indeks', 'like', '%' . $request->input('judul_indeks') . '%');
        }

        if ($request->has('last_number')) {
            $query->where('last_number', 'like', '%' . $request->input('detail_indeks') . '%');
        }

        // Paginate the results
        $indeks = $query->paginate($perPage);

        $user = Auth::user();

        if ($user->role == 'super_admin') {
            return view('super_admin.indeks', compact('indeks'));
        } elseif ($user->role == 'admin') {
            return view('admin.indeks', compact('indeks'));
        } elseif ($user->role == 'user') {
            return view('users.indeks', compact('indeks') );
        }

        return view('indeks', compact('indeks'));
    }

     public function create()
     {
        $user = Auth::user();

        if ($user->role == 'super_admin') {
            return view('super_admin.create_indeks');
        } elseif ($user->role == 'admin') {
            return view('admin.create_indeks');
        }
         return view('create_indeks');
     }

     public function store(Request $request)
     {
        $request->validate([
            'kode_indeks' => 'required|string|max:50|unique:indeks,kode_indeks',
            'kode_surat' => 'required|string|max:50',
            'judul_indeks' => 'required|string|max:255',
            'last_number' => 'required|string|max:50',
        ]);


         // Simpan data indeks
         Indeks::create([
             'kode_indeks' => $request->kode_indeks,
             'kode_surat' => $request->kode_surat,
             'judul_indeks' => $request->judul_indeks,
             'last_number' => $request->last_number,
         ]);

         return redirect()->route('indeks.index')->with('success', 'Indeks berhasil ditambahkan');
     }

     public function edit($id)
     {
         // Temukan satu item berdasarkan primary key 'indeks_id'
         $indeks = Indeks::findOrFail($id);

         $user = Auth::user();
         if ($user->role == 'super_admin') {
            return view('super_admin.edit_indeks', compact('indeks'));
        } elseif ($user->role == 'admin') {
            return view('admin.edit_indeks', compact('indeks'));
        }
         // Kirimkan data single item ke view
         return view('edit_indeks', compact('indeks'));
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
            'last_number' => 'required|string|max:50',
        ]);

        // Update data
        $indeks->kode_indeks = $request->kode_indeks;
        $indeks->kode_surat = $request->kode_surat;
        $indeks->judul_indeks = $request->judul_indeks;
        $indeks->last_number = $request->last_number;

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
