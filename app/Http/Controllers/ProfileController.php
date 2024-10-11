<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada
use App\Models\User; // Ini juga perlu jika menggunakan model User

class ProfileController extends Controller
{
    // Method untuk menampilkan profil user yang sedang login
    public function showProfile()
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        return view('super_admin.profile', compact('user'));
    }

    // Method untuk menampilkan halaman edit profil
    public function editProfile()
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        return view('super_admin.edit_profile', compact('user')); // Pastikan view-nya sesuai
    }

    // Method untuk memperbarui profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Ambil user yang sedang login

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file gambar
        ]);

        // Update user
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->password,
        ]);

        // Jika ada upload foto
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_pictures'), $filename);

            // Simpan path foto di database
            $user->profile_picture = $filename;
            $user->save();
        }

        // Redirect ke halaman profil
        return redirect()->route('superadmin.profile')->with('success', 'Profil berhasil diperbarui');
    }

}
