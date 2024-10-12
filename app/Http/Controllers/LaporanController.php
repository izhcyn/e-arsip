<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil bulan dan tahun dari request atau gunakan nilai default
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);
        $jenisData = $request->input('jenis_data', 'surat_masuk');

        // Array nama bulan dalam bahasa Indonesia
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Inisialisasi array untuk menyimpan jumlah per minggu
        $jumlahPerMinggu = [0, 0, 0, 0]; // 4 minggu

        // Ambil tanggal sekarang
        $today = Carbon::today();
        $currentWeek = Carbon::now()->weekOfMonth;

        // Inisialisasi variabel total surat
        $totalSuratHariIni = 0;
        $totalSuratMingguIni = 0;
        $totalSuratBulanIni = 0;

        // Mengambil data berdasarkan jenis yang dipilih
        switch ($jenisData) {
            case 'surat_keluar':
                $surat = SuratKeluar::whereMonth('tanggal_keluar', $bulan)
                    ->whereYear('tanggal_keluar', $tahun)
                    ->get();
                break;

            case 'keseluruhan':
                $suratMasuk = SuratMasuk::whereMonth('tanggal_diterima', $bulan)
                    ->whereYear('tanggal_diterima', $tahun)
                    ->get();

                $suratKeluar = SuratKeluar::whereMonth('tanggal_keluar', $bulan)
                    ->whereYear('tanggal_keluar', $tahun)
                    ->get();

                // Gabungkan hanya surat masuk dan surat keluar
                $surat = $suratMasuk->concat($suratKeluar);
                break;

            case 'surat_masuk':
            default:
                $surat = SuratMasuk::whereMonth('tanggal_diterima', $bulan)
                    ->whereYear('tanggal_diterima', $tahun)
                    ->get();
                break;
        }

        // Loop melalui semua surat dan hitung berdasarkan minggu dan hari
        foreach ($surat as $item) {
            $tanggalSurat = Carbon::parse($item->created_at ?? $item->tanggal_diterima ?? $item->tanggal_keluar);

            // Hitung total surat bulan ini
            $totalSuratBulanIni++;

            // Hitung surat berdasarkan minggu
            $minggu = $tanggalSurat->weekOfMonth - 1;
            if ($minggu >= 0 && $minggu < 4) {
                $jumlahPerMinggu[$minggu]++;
            }

            // Hitung total surat hari ini
            if ($tanggalSurat->isToday()) {
                $totalSuratHariIni++;
            }

            // Hitung total surat minggu ini
            if ($tanggalSurat->weekOfMonth == $currentWeek) {
                $totalSuratMingguIni++;
            }
        }

        $user = Auth::user();

        if ($user->role == 'super_admin') {
            return view('super_admin.laporan', compact('jumlahPerMinggu', 'bulan', 'tahun', 'jenisData', 'totalSuratHariIni', 'totalSuratMingguIni', 'totalSuratBulanIni', 'bulanIndo'));
        } elseif ($user->role == 'admin') {
            return view('admin.laporan', compact('jumlahPerMinggu', 'bulan', 'tahun', 'jenisData', 'totalSuratHariIni', 'totalSuratMingguIni', 'totalSuratBulanIni', 'bulanIndo'));
        }
        // Kirim data ke view
        return view('laporan', compact('jumlahPerMinggu', 'bulan', 'tahun', 'jenisData', 'totalSuratHariIni', 'totalSuratMingguIni', 'totalSuratBulanIni', 'bulanIndo'));
    }
}
