<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }
    // Menangani proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        // Tentukan apakah inputan adalah email atau username
        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        // Cek kredensial pengguna
        if (Auth::attempt([$loginType => $request->email, 'password' => $request->password])) {
            // Login berhasil
            $user = Auth::user();
            return $this->redirectUser($user);
            return $this->redirectUser($user)->with('success', 'Login berhasil!'); // Flash message untuk login berhasil
        } else {
            // Login gagal
            return redirect()->back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->withInput()->with('error', 'Login gagal. Periksa kembali kredensial Anda.'); // Flash message untuk login gagal
        }
    }


    // Redirect pengguna berdasarkan role
    protected function redirectUser($user)
    {
        if ($user->role == 'super_admin') {
            return redirect('super_admin/dashboard');
        } elseif ($user->role == 'admin') {
            return redirect('admin/dashboard');
        } elseif ($user->role == 'user') {
            return redirect('users/dashboard');
        }
        return redirect('/');
    }
    // Menangani logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
