<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <script>
        $(document).ready(function() {
            // Toggle dropdown saat item utama sidebar diklik
            $(".siderbar_menu > li").click(function(e) {
                // Tutup semua dropdown lain
                $(".siderbar_menu .accordion").removeClass("show");
                $(".siderbar_menu .arrow").removeClass("rotate");
                $(".siderbar_menu > li").removeClass("active"); // Hapus active dari semua menu utama

                // Buka dropdown yang diklik dan tambahkan kelas active
                $(this).addClass("active");
                $(this).find(".accordion").toggleClass("show");
                $(this).find(".arrow").toggleClass("rotate");

                // Cegah bubbling
                e.stopPropagation();
            });

            // Saat submenu diklik, tambahkan active ke menu utama
            $(".accordion li a").click(function() {
                // Tambahkan kelas active ke item utama ketika submenu diklik
                $(this).closest("li").addClass("active");
                $(this).addClass("active"); // Set submenu juga aktif
            });

            // Saat halaman di-load, buka dropdown dan aktifkan panah jika submenu aktif
            $(".accordion .active").closest(".accordion").addClass("show").closest("li").addClass("active").find(
                ".arrow").addClass("rotate");

            // Menu hamburger untuk membuka sidebar (mobile)
            $(".hamburger").click(function() {
                $(".wrapper").addClass("active");
            });

            // Tutup sidebar ketika klik close atau background shadow
            $(".close, .bg_shadow").click(function() {
                $(".wrapper").removeClass("active");
            });
        });
    </script>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="bg_shadow"></div>
            <div class="sidebar_inner">
                <div class="close">
                    <i class="fas fa-times"></i>
                </div>

                <div class="arsip_info">
                    <div class="logo_img">
                        <img src="/assets/Logo_bulat.png" alt="logo_RB">
                    </div>
                    <div class="arsip_data">
                        <p class="arsip">E-ARSIP<br /> RADAR BOGOR</p>
                    </div>
                </div>

                <ul class="siderbar_menu">
                    <li class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.dashboard') }}">
                            <div class="icon"><i class="fa fa-tachometer" aria-hidden="true"></i></div>
                            <div class="title">DASHBOARD</div>
                        </a>
                    </li>
                    <li class="{{ request()->is('surat*') ? 'active' : '' }}">
                        <a href="#">
                            <div class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                            <div class="title">SURAT</div>
                            <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <ul class="accordion">
                            <li><a href="{{ route('buatsurat.index') }}"
                                    class="{{ request()->routeIs('buatsurat.index') ? 'active' : '' }}">Buat Surat</a>
                            </li>
                            <li><a href="{{ route('drafts.index') }}"
                                    class="{{ request()->routeIs('drafts.index') ? 'active' : '' }}">Draft Surat</a>
                            </li>
                            <li><a href="{{ route('suratmasuk.index') }}"
                                    class="{{ request()->routeIs('suratmasuk.index') ? 'active' : '' }}">Surat Masuk</a>
                            </li>
                            <li><a href="{{ route('suratkeluar.index') }}"
                                    class="{{ request()->routeIs('suratkeluar.index') ? 'active' : '' }}">Surat
                                    Keluar</a></li>
                            <li><a href="{{ route('laporan.index') }}"
                                    class="{{ request()->routeIs('laporan.index') ? 'active' : '' }}">Laporan</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('pengaturan*') ? 'active' : '' }}">
                        <a href="#">
                            <div class="icon"><i class="fa fa-cog" aria-hidden="true"></i></div>
                            <div class="title">PENGATURAN</div>
                            <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <ul class="accordion">
                            <li><a href="{{ route('indeks.index') }}"
                                    class="{{ request()->routeIs('indeks.index') ? 'active' : '' }}">indeks</a></li>
                            <li><a href="{{ route('template.index') }}"
                                    class="{{ request()->routeIs('template.index') ? 'active' : '' }}">Template
                                    Surat</a></li>
                            <li><a href="{{ route('profile.index') }}"
                                    class="{{ request()->routeIs('profile.index') ? 'active' : '' }}">Profile</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="logout_btn">
                    <a href="/">Logout</a>
                </div>

            </div>
        </div>
        <div class="main_container">
            <div class="navbar">
                <div class="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="logo">
                    <a href="#">LAPORAN</a>
                </div>
                <div class="user_info">
                    @if ($user->profile_picture)
                        <img src="{{ asset('uploads/profile_pictures/' . $user->profile_picture) }}"
                            alt="Profile Picture">
                        <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                    @else
                        <i class="fas fa-user-circle"></i>
                        <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                    @endif
                </div>
            </div>
            <div class="container-chart">
                <!-- Tempat grafik -->
                <div class="canvas-chart mt-4">
                    <h4 style="font-weight: 700;">Grafik Surat Bulanan</h4>

                    <!-- Form untuk memilih jenis data, bulan, dan tahun -->
                    <form id="filterForm" method="GET" action="{{ route('laporan.index') }}">
                        <select name="jenis_data" required>
                            <option value="surat_masuk" {{ $jenisData == 'surat_masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="surat_keluar" {{ $jenisData == 'surat_keluar' ? 'selected' : '' }}>Surat Keluar</option>
                            <option value="keseluruhan" {{ $jenisData == 'keseluruhan' ? 'selected' : '' }}>Keseluruhan</option>
                        </select>

                        <select name="bulan" required>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>{{ $bulanIndo[$i] }}</option>
                            @endfor
                        </select>

                        <select name="tahun" required>
                            @for ($i = 2020; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == $tahun ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>

                        <select name="periode" required>
                            <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Per Hari</option>
                            <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>Per Minggu</option>
                            <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Per Bulan</option>
                            <option value="tahunan" {{ $periode == 'tahunan' ? 'selected' : '' }}>Per Tahun</option>
                        </select>

                        <button type="submit">Filter</button>
                    </form>

                    <canvas id="myChart"></canvas>
                </div>
                <!-- Bagian data laporan -->
                <div class="data-laporan mt-4">
                    <p>Total {{ ucfirst(str_replace('_', ' ', $jenisData)) }} Hari Ini:
                        <strong>{{ $totalSuratHariIni }}</strong></p>

                    <p>Total {{ ucfirst(str_replace('_', ' ', $jenisData)) }} Minggu Ini:
                        <strong>{{ $totalSuratMingguIni }}</strong></p>

                    <p>Total {{ ucfirst(str_replace('_', ' ', $jenisData)) }} Bulan Ini:
                        <strong>{{ $totalSuratBulanIni }}</strong></p>

                    <p>Total Data {{ ucfirst(str_replace('_', ' ', $jenisData)) }} Bulan Ini:
                        <strong>{{ array_sum($jumlahPerMinggu) }}</strong></p>
                </div>

            </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var periode = "{{ $periode }}"; // Get period from PHP

// Initialize labels and data arrays
var labels = [];
var data = [];

@if($periode === 'harian')
    // Data for daily (31 days max)
    labels = Array.from({ length: 31 }, (_, i) => 'Hari ' + (i + 1));
    data = [
        @foreach($data['harian'] as $day => $count)
            {{ $count }},
        @endforeach
    ];
@elseif($periode === 'mingguan')
    // Data for weekly (4 weeks max)
    labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
    data = [
        @foreach($data['mingguan'] as $week => $count)
            {{ $count }},
        @endforeach
    ];
@elseif($periode === 'bulanan')
    // Data for monthly
    labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    data = [
        @foreach($data['bulanan'] as $month => $count)
            {{ $count }},
        @endforeach
    ];
@elseif($periode === 'tahunan')
    // Data for yearly
    labels = Object.keys({!! json_encode($data['tahunan']) !!});
    data = Object.values({!! json_encode($data['tahunan']) !!});
@endif

// Create the chart with Chart.js
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Surat',
            data: data,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

<script>
// Ambil elemen form
var filterForm = document.getElementById('filterForm');

// Tambahkan event listener untuk setiap elemen form
filterForm.querySelectorAll('select').forEach(function(selectElement) {
    selectElement.addEventListener('change', function() {
        // Ketika ada perubahan, submit form secara otomatis
        filterForm.submit();
    });
});


</script>

</html>
