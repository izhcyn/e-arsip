<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // Metode untuk menampilkan dashboard
    public function index()
    {
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalIndeks = SuratMasuk::distinct('kode_indeks')->count();
        $totalUsers = User::count();

        $today = Carbon::today();
        $suratMasukHariIni = SuratMasuk::whereDate('tanggal_diterima', $today)->get();
        $suratKeluarHariIni = SuratKeluar::whereDate('tanggal_keluar', $today)->get();

        return view('super_admin.dashboard', [
            'totalSuratMasuk' => $totalSuratMasuk,
            'totalSuratKeluar' => $totalSuratKeluar,
            'totalIndeks' => $totalIndeks,
            'totalUsers' => $totalUsers,
            'suratMasukHariIni' => $suratMasukHariIni,
            'suratKeluarHariIni' => $suratKeluarHariIni
        ]);
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
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|min:8',
            'role' => 'required|in:superadmin,admin,user', // Pastikan role termasuk dalam opsi valid
        ]);


        // Simpan data ke database dengan hashing password
        User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
}



    // Mengedit pengguna
    public function edit($id)
    {
        $user = User::find($id);
        return view('super_admin.edit_user', compact('user'));
    }

    // Mengupdate pengguna
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui');
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
