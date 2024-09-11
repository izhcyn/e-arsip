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

    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,',
            'username' => 'required|string|unique:users,username,',
            'password' => 'required|min:8',
            'role' => 'required|in:super_admin,admin,user',

        ]);



        // Simpan data pengguna baru ke dalam database
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'username' => $request->username, // Tambahkan username di sini
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
}}




    // Mengedit pengguna
    // Menampilkan form untuk mengedit pengguna
    public function edit($id)
    {
        $user = User::find($id); // Temukan pengguna berdasarkan ID

        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Pengguna tidak ditemukan');
        }

        return view('super_admin.edit_user', compact('user')); // Tampilkan form edit dengan data pengguna
    }

    // Mengupdate pengguna
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        // Validasi input
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|min:8', // Password boleh kosong jika tidak diubah
            'role' => 'required|in:super_admin,admin,user',
        ]);

        // Update data pengguna
        $user->email = $request->email;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->role = $request->role;

        // Jika ada input password, maka update password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui');
    }




    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Pengguna tidak ditemukan');
        }

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
