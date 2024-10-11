<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Indeks;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    // Get total surat masuk per month
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

    // Count all surat and other totals
    $totalSuratMasuk = SuratMasuk::count();
    $totalSuratKeluar = SuratKeluar::count();
    $totalIndeks = Indeks::count();
    $totalUsers = User::count();

    // Pass all variables to the view
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

        // Get the authenticated user
        $user = Auth::user();

        dd($totalSuratMasukBulan);
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
}}
