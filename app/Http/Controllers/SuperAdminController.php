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
