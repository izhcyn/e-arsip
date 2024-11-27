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
        $periode = $request->input('periode', 'bulanan');

        // Array nama bulan dalam bahasa Indonesia
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei',
            6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober',
            11 => 'November', 12 => 'Desember'
        ];

        // Data array untuk menyimpan jumlah surat
        $data = $this->getDataByPeriode($bulan, $tahun, $jenisData, $periode);

        // Ambil data sesuai dengan jenis data yang dipilih (surat masuk, surat keluar, atau keseluruhan)
        $surat = $this->getSuratData($bulan, $tahun, $jenisData);

        // Initialize totals
        $totalSuratHariIni = 0;
        $totalSuratMingguIni = 0;
        $totalSuratBulanIni = 0;
        $jumlahPerMinggu = [];

        // Process data (e.g., daily, weekly, monthly, yearly) and organize
        foreach ($surat as $item) {
            $tanggalSurat = Carbon::parse($item->tanggal_diterima ?? $item->tanggal_keluar);

            // Total Surat Hari Ini
            if ($tanggalSurat->isToday()) {
                $totalSuratHariIni++;
            }

            // Total Surat Minggu Ini
            $minggu = $tanggalSurat->weekOfMonth - 1;
            if ($minggu >= 0 && $minggu < 4) {
                $data['mingguan'][$minggu] = ($data['mingguan'][$minggu] ?? 0) + 1;
            }

            // Total Surat Bulan Ini
            if ($tanggalSurat->month == $bulan && $tanggalSurat->year == $tahun) {
                $totalSuratBulanIni++;
            }

            // Data per minggu (jumlah surat per minggu)
            $jumlahPerMinggu[$minggu] = ($jumlahPerMinggu[$minggu] ?? 0) + 1;
        }

        // Calculate total Surat Minggu Ini
        if (isset($data['mingguan'])) {
            $totalSuratMingguIni = array_sum($data['mingguan']);
        }

        // Calculate total Surat Bulan Ini
        if (isset($data['bulanan'][$bulan])) {
            $totalSuratBulanIni = $data['bulanan'][$bulan];
        }

        // Pass the calculated totals and other data to the view
        $viewData = compact('data', 'bulan', 'tahun', 'jenisData', 'bulanIndo', 'periode', 'totalSuratHariIni', 'totalSuratMingguIni', 'totalSuratBulanIni', 'jumlahPerMinggu');

        // Determine user role and render the appropriate view
        $user = Auth::user();
        if ($user->role == 'super_admin') {
            return view('super_admin.laporan', $viewData);
        } elseif ($user->role == 'admin') {
            return view('admin.laporan', $viewData);
        }

        return view('laporan', $viewData);
    }

    // Consolidated method to get SuratMasuk/SuratKeluar based on date filters
    private function getSuratData($bulan, $tahun, $jenisData)
    {
        switch ($jenisData) {
            case 'surat_keluar':
                return SuratKeluar::whereMonth('tanggal_keluar', $bulan)
                    ->whereYear('tanggal_keluar', $tahun)
                    ->get();
            case 'keseluruhan':
                $suratMasuk = SuratMasuk::whereMonth('tanggal_diterima', $bulan)
                    ->whereYear('tanggal_diterima', $tahun)
                    ->get();
                $suratKeluar = SuratKeluar::whereMonth('tanggal_keluar', $bulan)
                    ->whereYear('tanggal_keluar', $tahun)
                    ->get();
                return $suratMasuk->concat($suratKeluar);
            case 'surat_masuk':
            default:
                return SuratMasuk::whereMonth('tanggal_diterima', $bulan)
                    ->whereYear('tanggal_diterima', $tahun)
                    ->get();
        }
    }

    // General method to get data by periode (daily, weekly, monthly, yearly)
    private function getDataByPeriode($bulan, $tahun, $jenisData, $periode)
    {
        // Default to empty arrays to avoid undefined keys
        $data = [
            'harian' => [],
            'mingguan' => [],
            'bulanan' => [],
            'tahunan' => []
        ];

        switch ($periode) {
            case 'harian':
                $data['harian'] = $this->getDailyData($bulan, $tahun, $jenisData);
                break;
            case 'mingguan':
                $data['mingguan'] = $this->getWeeklyData($bulan, $tahun, $jenisData);
                break;
            case 'tahunan':
                $data['tahunan'] = $this->getYearlyData($tahun, $jenisData);
                break;
            case 'bulanan':
            default:
                $data['bulanan'] = $this->getMonthlyData($bulan, $tahun, $jenisData);
                break;
        }

        return $data;
    }

    // Consolidated method to retrieve daily data
    private function getDailyData($bulan, $tahun, $jenisData)
    {
        return $this->getSuratData($bulan, $tahun, $jenisData);
    }

    // Consolidated method to retrieve weekly data
    private function getWeeklyData($bulan, $tahun, $jenisData)
    {
        return $this->getSuratData($bulan, $tahun, $jenisData);
    }

    // Consolidated method to retrieve monthly data
    private function getMonthlyData($bulan, $tahun, $jenisData)
    {
        return $this->getSuratData($bulan, $tahun, $jenisData);
    }

    // Consolidated method to retrieve yearly data
    private function getYearlyData($tahun, $jenisData)
    {
        // Retrieve the unique years from the database and count the surat data for each
        $years = SuratMasuk::selectRaw('YEAR(tanggal_diterima) as year')
                    ->groupByRaw('YEAR(tanggal_diterima)')
                    ->get()
                    ->pluck('year');

        $data = [];
        foreach ($years as $year) {
            $suratCount = 0;
            switch ($jenisData) {
                case 'surat_masuk':
                    $suratCount = SuratMasuk::whereYear('tanggal_diterima', $year)->count();
                    break;
                case 'surat_keluar':
                    $suratCount = SuratKeluar::whereYear('tanggal_keluar', $year)->count();
                    break;
                case 'keseluruhan':
                    $suratCount = SuratMasuk::whereYear('tanggal_diterima', $year)->count() +
                                  SuratKeluar::whereYear('tanggal_keluar', $year)->count();
                    break;
            }
            $data[$year] = $suratCount;
        }

        return $data;
    }
}
