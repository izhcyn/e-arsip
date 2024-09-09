<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Indeks; // Pastikan model ini sudah ada
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil tanggal hari ini
        $tanggalHariIni = Carbon::today();

        // Ambil data surat masuk dan keluar berdasarkan tanggal hari ini
        $suratMasukHariIni = SuratMasuk::whereDate('tanggal_diterima', $tanggalHariIni)->get();
        $suratKeluarHariIni = SuratKeluar::whereDate('tanggal_keluar', $tanggalHariIni)->get();

        // Menghitung total surat, indeks, dan users
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalIndeks = Indeks::count();
        $totalUsers = User::count();

        // Ambil user saat ini
        $user = Auth::user();

        // Cek peran user dan arahkan ke dashboard yang sesuai
        if ($user->role == 'super_admin') {
            return view('super_admin.dashboard', compact(
                'suratMasukHariIni',
                'suratKeluarHariIni',
                'totalSuratMasuk',
                'totalSuratKeluar',
                'totalIndeks',
                'totalUsers',
                'user'
            ));
        } elseif ($user->role == 'admin') {
            return view('admin.dashboard', compact(
                'suratMasukHariIni',
                'suratKeluarHariIni',
                'totalSuratMasuk',
                'totalSuratKeluar',
                'totalIndeks',
                'totalUsers',
                'user'
            ));
        } elseif ($user->role == 'user') {
            return view('user.dashboard', compact(
                'suratMasukHariIni',
                'suratKeluarHariIni',
                'totalSuratMasuk',
                'totalSuratKeluar',
                'totalIndeks',
                'totalUsers',
                'user'
            ));
        }

        return view('dashboard', compact(
            'suratMasukHariIni',
            'suratKeluarHariIni',
            'totalSuratMasuk',
            'totalSuratKeluar',
            'totalIndeks',
            'totalUsers',
            'user'
        ));
    }
}
