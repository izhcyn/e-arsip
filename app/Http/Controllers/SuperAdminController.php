<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Indeks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
class SuperAdminController extends Controller
{
    // Metode untuk menampilkan dashboard
    public function index()
    {
        $totalSuratMasukBulan = SuratMasuk::selectRaw('MONTH(tanggal_diterima) as month, COUNT(*) as total')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    // Fill missing months with 0
    $totalSuratMasukBulan = array_replace(array_fill(1, 12, 0), $totalSuratMasukBulan);

    // Get total surat keluar per month
    $totalSuratKeluarBulan = SuratKeluar::selectRaw('MONTH(tanggal_keluar) as month, COUNT(*) as total')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    // Fill missing months with 0
    $totalSuratKeluarBulan = array_replace(array_fill(1, 12, 0), $totalSuratKeluarBulan);

    // Get today's date
    $tanggalHariIni = Carbon::today();

    // Get surat masuk today, limited to 5
    $suratMasukHariIni = SuratMasuk::whereDate('tanggal_diterima', $tanggalHariIni)
        ->latest('tanggal_diterima')
        ->limit(5)
        ->get();

    // Get surat keluar today, limited to 5
    $suratKeluarHariIni = SuratKeluar::whereDate('tanggal_keluar', $tanggalHariIni)
        ->latest('tanggal_keluar')
        ->limit(5)
        ->get();
        // Ambil tanggal hari ini

        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalIndeks = Indeks::count();
        $totalUsers = User::count();

            // Get the authenticated user
            $user = Auth::user();

            if ($user->role == 'super_admin') {
                return view('super_admin.dashboard', [
                    'totalSuratMasukBulan' => $totalSuratMasukBulan,
                    'totalSuratKeluarBulan' => $totalSuratKeluarBulan,
                    'suratMasukHariIni' => $suratMasukHariIni,
                    'suratKeluarHariIni' => $suratKeluarHariIni,
                    'totalSuratMasuk' => $totalSuratMasuk,
                    'totalSuratKeluar' => $totalSuratKeluar,
                    'totalIndeks' => $totalIndeks,
                    'totalUsers' => $totalUsers
                ]);
            } elseif ($user->role == 'admin') {
                return view('admin.dashboard', [
                    'totalSuratMasukBulan' => $totalSuratMasukBulan,
                    'totalSuratKeluarBulan' => $totalSuratKeluarBulan,
                    'suratMasukHariIni' => $suratMasukHariIni,
                    'suratKeluarHariIni' => $suratKeluarHariIni,
                    'totalSuratMasuk' => $totalSuratMasuk,
                    'totalSuratKeluar' => $totalSuratKeluar,
                    'totalIndeks' => $totalIndeks,
                    'totalUsers' => $totalUsers
                ]);
            } elseif ($user->role == 'user') {
                return view('user.dashboard', [
                    'totalSuratMasukBulan' => $totalSuratMasukBulan,
                    'totalSuratKeluarBulan' => $totalSuratKeluarBulan,
                    'suratMasukHariIni' => $suratMasukHariIni,
                    'suratKeluarHariIni' => $suratKeluarHariIni,
                    'totalSuratMasuk' => $totalSuratMasuk,
                    'totalSuratKeluar' => $totalSuratKeluar,
                    'totalIndeks' => $totalIndeks,
                    'totalUsers' => $totalUsers
                ]);
        }

        return view('dashboard', [
            'totalSuratMasukBulan' => $totalSuratMasukBulan,
            'totalSuratKeluarBulan' => $totalSuratKeluarBulan,
            'suratMasukHariIni' => $suratMasukHariIni,
            'suratKeluarHariIni' => $suratKeluarHariIni,
            'totalSuratMasuk' => $totalSuratMasuk,
            'totalSuratKeluar' => $totalSuratKeluar,
            'totalIndeks' => $totalIndeks,
            'totalUsers' => $totalUsers
        ]);
    }
    // Metode untuk menampilkan daftar pengguna
   // In your Controller
public function showUsers(Request $request)
{
    // Get the number of records to display per page from the request, default to 5
    $perPage = $request->get('limit', 5);

    // Apply filtering based on input values
    $query = User::query();

    if ($request->has('name')) {
        $query->where('name', 'like', '%' . $request->input('name') . '%');
    }

    if ($request->has('email')) {
        $query->where('email', 'like', '%' . $request->input('email') . '%');
    }

    if ($request->has('username')) {
        $query->where('username', 'like', '%' . $request->input('username') . '%');
    }

    if ($request->has('role')) {
        $query->where('role', 'like', '%' . $request->input('role') . '%');
    }

    // Fetch users with pagination based on the selected limit
    $users = $query->paginate($perPage);

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
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
}}




    // Mengedit pengguna
    // Menampilkan form untuk mengedit pengguna
    public function edit($id)
    {
        // Find the user by their ID
        $user = User::findOrFail($id);

        // Return the view for editing the user
        return view('super_admin.edit_user', compact('user'));

    }


    // Update User in the Database
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:super_admin,admin,user',
        ]);

        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;

        // Update the password only if it is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update the role
        $user->role = $request->role;

        // Save the updated user data
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }




    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Pengguna tidak ditemukan');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus');
    }


}
