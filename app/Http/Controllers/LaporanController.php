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
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);
        $jenisData = $request->input('jenis_data', 'surat_masuk');
        $periode = $request->input('periode', 'bulanan');

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei',
            6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober',
            11 => 'November', 12 => 'Desember'
        ];

        // Ambil data berdasarkan jenis dan periode
        $data = $this->getDataByPeriode($bulan, $tahun, $jenisData, $periode);

        $totalSuratHariIni = $this->getSuratDataToday($jenisData);
        $totalSuratMingguIni = $this->getSuratDataThisWeek($bulan, $tahun, $jenisData);
        $totalSuratBulanIni = $this->getSuratDataThisMonth($jenisData);
        $totalSuratTahunIni = $this->getSuratDataThisYear($jenisData);
        $jumlahPerMinggu = $data['mingguan'] ?? [];

        $viewData = compact(
            'data',
            'bulan',
            'tahun',
            'jenisData',
            'bulanIndo',
            'periode',
            'totalSuratHariIni',
            'totalSuratMingguIni',
            'totalSuratBulanIni',
            'totalSuratTahunIni',
            'jumlahPerMinggu'
        );

        $user = Auth::user();
        if ($user->role == 'super_admin') {
            return view('super_admin.laporan', $viewData);
        } elseif ($user->role == 'admin') {
            return view('admin.laporan', $viewData);
        }

        return view('laporan', $viewData);
    }

    public function getLaporan(Request $request)
{
    $jenisData = $request->input('jenis_data', 'keseluruhan'); // Default 'keseluruhan'
    $bulan = Carbon::now()->month;
    $tahun = Carbon::now()->year;
    $hariIni = Carbon::now()->toDateString();
    $mingguIni = Carbon::now()->weekOfYear;

    // Data harian
    $totalSuratHariIni = $this->getSuratDataHarian($hariIni, $jenisData);

    // Data mingguan
    $totalSuratMingguIni = $this->getSuratDataMingguan($mingguIni, $tahun, $jenisData);

    // Data bulanan
    $totalSuratBulanIni = $this->getSuratDataBulanan($bulan, $tahun, $jenisData);

    // Data tahunan (jumlah dari semua minggu dalam tahun)
    $jumlahPerMinggu = $this->getWeeklyData($bulan, $tahun, $jenisData);
    $totalSuratTahunIni = array_sum($jumlahPerMinggu);

    return view('laporan', compact(
        'totalSuratHariIni',
        'totalSuratMingguIni',
        'totalSuratBulanIni',
        'jumlahPerMinggu',
        'totalSuratTahunIni',
        'jenisData'
    ));
}

    // Dapatkan data berdasarkan jenis surat dan periode
    private function getSuratData($bulan = null, $tahun = null, $jenisData = 'keseluruhan')
    {
        $query = collect();

        // Kondisi untuk keseluruhan (surat masuk dan keluar digabungkan)
        if ($jenisData === 'keseluruhan') {
            // Surat Masuk: menggunakan tanggal_diterima
            $suratMasuk = SuratMasuk::query();
            // Surat Keluar: menggunakan tanggal_keluar
            $suratKeluar = SuratKeluar::query();

            // Filter berdasarkan bulan dan tahun jika ada
            if ($bulan && $tahun) {
                $suratMasuk->whereYear('tanggal_diterima', $tahun)
                    ->whereMonth('tanggal_diterima', $bulan);
                $suratKeluar->whereYear('tanggal_keluar', $tahun)
                    ->whereMonth('tanggal_keluar', $bulan);
            }

            // Ambil data surat masuk dan keluar dan gabungkan
            $query = $suratMasuk->get()->merge($suratKeluar->get());
        } else {
            // Jika hanya surat masuk atau surat keluar
            if ($jenisData === 'surat_masuk') {
                $query = SuratMasuk::query();
                if ($bulan && $tahun) {
                    $query->whereYear('tanggal_diterima', $tahun)
                          ->whereMonth('tanggal_diterima', $bulan);
                }
            } elseif ($jenisData === 'surat_keluar') {
                $query = SuratKeluar::query();
                if ($bulan && $tahun) {
                    $query->whereYear('tanggal_keluar', $tahun)
                          ->whereMonth('tanggal_keluar', $bulan);
                }
            }

            // Ambil data surat masuk atau keluar
            $query = $query->get();
        }

        return $query;
    }

    private function getSuratDataToday($jenisData)
    {
        $today = Carbon::today();

        if ($jenisData === 'keseluruhan') {
            $totalSuratMasuk = SuratMasuk::whereDate('tanggal_diterima', $today)->count();
            $totalSuratKeluar = SuratKeluar::whereDate('tanggal_keluar', $today)->count();

            return $totalSuratMasuk + $totalSuratKeluar;
        } elseif ($jenisData === 'surat_masuk') {
            return SuratMasuk::whereDate('tanggal_diterima', $today)->count();
        } elseif ($jenisData === 'surat_keluar') {
            return SuratKeluar::whereDate('tanggal_keluar', $today)->count();
        }

        return 0;
    }


    private function getSuratDataThisWeek($bulan, $tahun, $jenisData)
    {
        $currentWeek = Carbon::now()->weekOfYear;

        if ($jenisData === 'keseluruhan') {
            $totalSuratMasuk = SuratMasuk::whereYear('tanggal_diterima', $tahun)
                ->whereMonth('tanggal_diterima', $bulan)
                ->whereRaw('WEEKOFYEAR(tanggal_diterima) = ?', [$currentWeek])
                ->count();

            $totalSuratKeluar = SuratKeluar::whereYear('tanggal_keluar', $tahun)
                ->whereMonth('tanggal_keluar', $bulan)
                ->whereRaw('WEEKOFYEAR(tanggal_keluar) = ?', [$currentWeek])
                ->count();

            return $totalSuratMasuk + $totalSuratKeluar;
        } elseif ($jenisData === 'surat_masuk') {
            return SuratMasuk::whereYear('tanggal_diterima', $tahun)
                ->whereMonth('tanggal_diterima', $bulan)
                ->whereRaw('WEEKOFYEAR(tanggal_diterima) = ?', [$currentWeek])
                ->count();
        } elseif ($jenisData === 'surat_keluar') {
            return SuratKeluar::whereYear('tanggal_keluar', $tahun)
                ->whereMonth('tanggal_keluar', $bulan)
                ->whereRaw('WEEKOFYEAR(tanggal_keluar) = ?', [$currentWeek])
                ->count();
        }

        return 0;
    }


    private function getSuratDataThisMonth($jenisData)
    {
        $now = Carbon::now();

        if ($jenisData === 'keseluruhan') {
            $totalSuratMasuk = SuratMasuk::whereYear('tanggal_diterima', $now->year)
                ->whereMonth('tanggal_diterima', $now->month)
                ->count();

            $totalSuratKeluar = SuratKeluar::whereYear('tanggal_keluar', $now->year)
                ->whereMonth('tanggal_keluar', $now->month)
                ->count();

            return $totalSuratMasuk + $totalSuratKeluar;
        } elseif ($jenisData === 'surat_masuk') {
            return SuratMasuk::whereYear('tanggal_diterima', $now->year)
                ->whereMonth('tanggal_diterima', $now->month)
                ->count();
        } elseif ($jenisData === 'surat_keluar') {
            return SuratKeluar::whereYear('tanggal_keluar', $now->year)
                ->whereMonth('tanggal_keluar', $now->month)
                ->count();
        }

        return 0;
    }

    private function getSuratDataThisYear($jenisData)
    {
        $now = Carbon::now();

        if ($jenisData === 'keseluruhan') {
            $totalSuratMasuk = SuratMasuk::whereYear('tanggal_diterima', $now->year)->count();
            $totalSuratKeluar = SuratKeluar::whereYear('tanggal_keluar', $now->year)->count();

            return $totalSuratMasuk + $totalSuratKeluar;
        } elseif ($jenisData === 'surat_masuk') {
            return SuratMasuk::whereYear('tanggal_diterima', $now->year)->count();
        } elseif ($jenisData === 'surat_keluar') {
            return SuratKeluar::whereYear('tanggal_keluar', $now->year)->count();
        }

        return 0;
    }

    private function getDataByPeriode($bulan, $tahun, $jenisData, $periode)
    {
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
                $data['tahunan'] = $this->getYearlyData($jenisData);
                break;
            case 'bulanan':
            default:
                $data['bulanan'] = $this->getMonthlyData($tahun, $jenisData);
                break;
        }

        return $data;
    }

    // Consolidated method to retrieve daily data
    private function getDailyData($bulan, $tahun, $jenisData)
    {
        $now = Carbon::now();
        $lastDay = $now->month == $bulan && $now->year == $tahun ? $now->day : $now->daysInMonth;

        $data = collect();

        if ($jenisData === 'keseluruhan') {
            $suratMasuk = SuratMasuk::whereYear('tanggal_diterima', $tahun)
                ->whereMonth('tanggal_diterima', $bulan)
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal_diterima)->format('Y-m-d');
                });

            $suratKeluar = SuratKeluar::whereYear('tanggal_keluar', $tahun)
                ->whereMonth('tanggal_keluar', $bulan)
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal_keluar)->format('Y-m-d');
                });

            $data = $suratMasuk->mergeRecursive($suratKeluar);
        } else {
            $data = $this->getSuratData($bulan, $tahun, $jenisData)
                ->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal_diterima ?? $item->tanggal_keluar)->format('Y-m-d');
                });
        }

        $dailyData = [];
        for ($day = 1; $day <= $lastDay; $day++) {
            $date = Carbon::create($tahun, $bulan, $day)->format('Y-m-d');
            $dailyData[$date] = isset($data[$date]) ? count($data[$date]) : 0;
        }

        return $dailyData;
    }

    // Consolidated method to retrieve weekly data
    private function getWeeklyData($bulan, $tahun, $jenisData)
    {
        // Inisialisasi data kosong untuk 4 minggu
        $weeklyData = array_fill(1, 4, 0);

        if ($jenisData === 'keseluruhan') {
            // Data surat masuk
            $suratMasuk = SuratMasuk::whereYear('tanggal_diterima', $tahun)
                ->whereMonth('tanggal_diterima', $bulan)
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal_diterima)->weekOfMonth;
                });

            // Data surat keluar
            $suratKeluar = SuratKeluar::whereYear('tanggal_keluar', $tahun)
                ->whereMonth('tanggal_keluar', $bulan)
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal_keluar)->weekOfMonth;
                });

            // Hitung total surat masuk dan keluar untuk setiap minggu
            foreach (range(1, 4) as $week) {
                $weeklyData[$week] =
                    (isset($suratMasuk[$week]) ? count($suratMasuk[$week]) : 0) +
                    (isset($suratKeluar[$week]) ? count($suratKeluar[$week]) : 0);
            }
        } else {
            // Data berdasarkan jenis tertentu (misalnya hanya surat masuk atau keluar)
            $data = $this->getSuratData($bulan, $tahun, $jenisData)
                ->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal_diterima ?? $item->tanggal_keluar)->weekOfMonth;
                });

            // Hitung total data untuk setiap minggu
            foreach (range(1, 4) as $week) {
                $weeklyData[$week] = isset($data[$week]) ? count($data[$week]) : 0;
            }
        }

        return $weeklyData;
    }

    // Consolidated method to retrieve monthly data
    private function getMonthlyData($tahun, $jenisData)
    {
        $data = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $suratCount = 0;

            if ($jenisData == 'surat_masuk') {
                $suratCount = SuratMasuk::whereYear('tanggal_diterima', $tahun)
                    ->whereMonth('tanggal_diterima', $bulan)
                    ->count();
            } elseif ($jenisData == 'surat_keluar') {
                $suratCount = SuratKeluar::whereYear('tanggal_keluar', $tahun)
                    ->whereMonth('tanggal_keluar', $bulan)
                    ->count();
            } elseif ($jenisData == 'keseluruhan') {
                $suratCount = SuratMasuk::whereYear('tanggal_diterima', $tahun)
                    ->whereMonth('tanggal_diterima', $bulan)
                    ->count() +
                    SuratKeluar::whereYear('tanggal_keluar', $tahun)
                    ->whereMonth('tanggal_keluar', $bulan)
                    ->count();
            }

            // Simpan nama bulan dan jumlah surat
            $data[Carbon::createFromDate($tahun, $bulan, 1)->format('F')] = $suratCount;
        }

        return $data;
    }

    // Consolidated method to retrieve yearly data
    private function getYearlyData($jenisData)
    {
        $years = SuratMasuk::selectRaw('YEAR(tanggal_diterima) as year')
            ->union(
                SuratKeluar::selectRaw('YEAR(tanggal_keluar) as year')
            )
            ->groupBy('year')
            ->get()
            ->pluck('year'); // Mengambil daftar tahun unik

        $data = [];
        foreach ($years as $year) {
            $suratCount = 0;

            if ($jenisData == 'surat_masuk') {
                $suratCount = SuratMasuk::whereYear('tanggal_diterima', $year)->count();
            } elseif ($jenisData == 'surat_keluar') {
                $suratCount = SuratKeluar::whereYear('tanggal_keluar', $year)->count();
            } elseif ($jenisData == 'keseluruhan') {
                $suratCount = SuratMasuk::whereYear('tanggal_diterima', $year)->count() +
                              SuratKeluar::whereYear('tanggal_keluar', $year)->count();
            }

            // Simpan tahun dan jumlah surat
            $data[$year] = $suratCount;
        }

        return $data;
    }

    // Fungsi untuk data harian
    private function getSuratHarian($hariIni, $jenisData)
    {
        $suratData = $this->getSuratData(null, null, $jenisData);
        return $suratData->filter(function ($item) use ($hariIni) {
            return Carbon::parse($item->tanggal_diterima ?? $item->tanggal_keluar)->isToday();
        })->count();
    }

    // Fungsi untuk data mingguan
    private function getSuratMingguan($mingguIni, $tahun, $jenisData)
    {
        $suratData = $this->getSuratData(null, $tahun, $jenisData);
        return $suratData->filter(function ($item) use ($mingguIni) {
            return Carbon::parse($item->tanggal_diterima ?? $item->tanggal_keluar)->weekOfYear == $mingguIni;
        })->count();
    }


    // Fungsi untuk data bulanan
    private function getSuratBulanan($bulan, $tahun, $jenisData)
    {
        $suratData = $this->getSuratData($bulan, $tahun, $jenisData);
        return $suratData->count();
    }


}
