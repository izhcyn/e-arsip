<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        // Menghitung total surat masuk, keluar, indeks, dan pengguna
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalIndeks = SuratMasuk::distinct('kode_indeks')->count();
        $totalUsers = User::count();

        // Mengambil surat yang diterima/keluar hari ini
        $today = Carbon::today();
        $suratMasukHariIni = SuratMasuk::whereDate('tanggal_diterima', $today)->get();
        $suratKeluarHariIni = SuratKeluar::whereDate('tanggal_keluar', $today)->get();

        return view('user.dashboard', [
            'totalSuratMasuk' => $totalSuratMasuk,
            'totalSuratKeluar' => $totalSuratKeluar,
            'totalIndeks' => $totalIndeks,
            'suratMasukHariIni' => $suratMasukHariIni,
            'suratKeluarHariIni' => $suratKeluarHariIni
        ]);
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
