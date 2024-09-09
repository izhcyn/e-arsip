<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Indeks; // Pastikan model ini sudah ada
use App\Models\User;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    // Metode untuk menampilkan dashboard
    public function index()
    {
        $totalSuratMasuk = SuratMasuk::count();  // Gunakan 'SuratMasuk' (CamelCase)

        // Menghitung jumlah surat keluar
        $totalSuratKeluar = SuratKeluar::count();  // Contoh model lain jika ada

        // Menghitung total indeks
        $totalIndeks = SuratMasuk::distinct('kode_indeks')->count();

        // Menghitung total pengguna
        $totalUsers = User::count();

        // Menghitung surat masuk dan keluar hari ini
        $today = Carbon::today();
        $suratMasukHariIni = SuratMasuk::whereDate('tanggal_diterima', $today)->count();
        $suratKeluarHariIni = SuratKeluar::whereDate('tanggal_keluar', $today)->count();

        return view('super_admin.dashboard', [
            'totalSuratMasuk' => $totalSuratMasuk,
            'totalSuratKeluar' => $totalSuratKeluar,
            'totalIndeks' => $totalIndeks,
            'totalUsers' => $totalUsers,
            'suratMasukHariIni' => $suratMasukHariIni,
            'suratKeluarHariIni' => $suratKeluarHariIni
        ]);

        $suratMasuk = SuratMasuk::all();

        // Mengirim data ke view
        return view('super_admin.suratmasuk', compact('suratMasuk'));
    }

    // Metode untuk menampilkan daftar pengguna
    public function showUsers()
    {
        $users = User::paginate(5);
        return view('super_admin.users', compact('users'));
    }

    // Metode untuk membuat pengguna baru
    public function create()
    {
        return view('super_admin.create_user');
    }

    // Menyimpan data pengguna baru
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|min:8',
        'role' => 'required|in:superadmin,admin,user',
    ]);

    // Simpan data user baru ke database
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => bcrypt($request->password), // Pastikan password di-hash
        'role' => $request->role,
    ]);

    return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
}




    // Mengedit pengguna
   // Menampilkan form edit user
public function edit($id)
{
    $user = User::findOrFail($id); // Ambil data user berdasarkan ID
    return view('super_admin.edit_user', compact('user'));
}

// Menyimpan perubahan user
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,'.$id, // Pastikan email tetap unik kecuali untuk user ini
        'username' => 'required|string|max:255|unique:users,username,'.$id,
        'role' => 'required|in:superadmin,admin,user',
    ]);

    // Update data user
    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'role' => $request->role,
    ]);

    return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
}


    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus');
    }

    // Menampilkan detail pengguna
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Pengguna tidak ditemukan');
        }
        return view('super_admin.show_user', compact('user'));
    }
}
