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

class UsersController extends Controller
{
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
                return view('users.dashboard', [
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

    public function suratMasuk()
    {
        $suratMasuk = SuratMasuk::all();

        return view('user.surat_masuk', ['suratMasuk' => $suratMasuk]);
    }

    public function suratKeluar()
    {
        $suratKeluar = SuratKeluar::all();

        return view('user.surat_keluar', ['suratKeluar' => $suratKeluar]);
    }
}
