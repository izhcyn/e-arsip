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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $(".siderbar_menu li").click(function() {
                $(".siderbar_menu li").removeClass("active");
                $(this).addClass("active");
            });

            $(".hamburger").click(function() {
                $(".wrapper").addClass("active");
            });

            $(".close, .bg_shadow").click(function() {
                $(".wrapper").removeClass("active");
            });
        });

        function confirmDelete(suratmasukId) {
            Swal.fire({
                title: "Apa kamu yakin?",
                text: "Data ini tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus ini!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("delete-form-" + suratmasukId).submit();
                }
            });
        }

        function removeChart(chartId) {
            document.getElementById(chartId).parentElement.parentElement.style.display = 'none';
        }

        // Function to minimize chart
        function toggleChart(chartId) {
            const chartContainer = document.getElementById(chartId).parentElement.parentElement;
            const canvas = chartContainer.querySelector('canvas');
            if (canvas.style.display === 'none') {
                canvas.style.display = 'block';
            } else {
                canvas.style.display = 'none';
            }
        }
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
                    <a href="#">DASHBOARD</a>
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
            <div class="content">
                <div class="dashboard-container">
                    <div class="dashboard-card">
                        <div class="dashboard-icon">
                            <img src="/assets/surat_masuk.png" alt="Surat Masuk">
                        </div>
                        <div class="dashboard-info">
                            <h3>Total Surat Masuk</h3>
                            <span>{{ $totalSuratMasuk }}</span>
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="dashboard-icon">
                            <img src="/assets/surat_keluar.png" alt="Surat Keluar">
                        </div>
                        <div class="dashboard-info">
                            <h3>Total Surat Keluar</h3>
                            <span>{{ $totalSuratKeluar }}</span>
                        </div>
                    </div>

                    @if (isset($totalIndeks))
                        <div class="dashboard-card">
                            <div class="dashboard-icon">
                                <img src="/assets/indeks.png" alt="Indeks">
                            </div>
                            <div class="dashboard-info">
                                <h3>Indeks</h3>
                                <span>{{ $totalIndeks }}</span>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Total Surat Masuk
                            </div>
                            <div class="card-body">
                                <canvas id="chartIncoming" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Total Surat Keluar
                            </div>
                            <div class="card-body">
                                <canvas id="chartOutgoing" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="surat-section">
                <div class="surat-card">
                    <h3>Surat Masuk Hari ini</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Indeks Surat</th>
                                <th>Asal Surat</th>
                                <th>Perihal</th>
                                <th>Penerima</th>
                                <th>Tanggal Diterima</th>
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($suratMasukHariIni->isEmpty())
                                <tr>
                                    <td colspan="8">Tidak ada surat masuk hari ini.</td>
                                </tr>
                            @else
                                @foreach ($suratMasukHariIni as $item)
                                    <tr>
                                        <td>{{ $item->no_surat }}</td>
                                        <td>{{ $item->kode_indeks }}</td>
                                        <td>{{ $item->asal_surat }}</td>
                                        <td>{{ $item->perihal }}</td>
                                        <td>{{ $item->penerima }}</td>
                                        <td>{{ $item->tanggal_diterima }}</td>
                                        <td>@php
                                            $filePath = asset('storage/' . $item->dokumen); // Path untuk file PDF
                                            $fileName = $item->dokumen; // Menampilkan nama file asli yang disimpan
                                        @endphp

                                            <!-- Tampilkan link untuk download dan preview -->
                                            <a href="{{ $filePath }}" target="_blank">{{ $fileName }}</a>
                                        </td>
                                        <td>
                                            <!-- Tombol untuk mengedit data -->
                                            @if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
                                                <a href="{{ route('suratmasuk.edit', $item->suratmasuk_id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            <!-- Tombol untuk menghapus data -->
                                            @if (auth()->user()->role == 'super_admin')
                                                <form action="{{ route('suratmasuk.destroy', $item->suratmasuk_id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        title="Hapus"
                                                        onclick="confirmDeleteSM({{ $item->suratkeluar_id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="surat-section">
                <div class="surat-card">
                    <h3>Surat Keluar Hari ini</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Indeks Surat</th>
                                <th>Perihal</th>
                                <th>Penulis</th>
                                <th>Penerima</th>
                                <th>Tanggal Keluar</th>
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($suratKeluarHariIni->isEmpty())
                                <tr>
                                    <td colspan="8">Tidak ada surat keluar hari ini.</td>
                                </tr>
                            @else
                                @foreach ($suratKeluarHariIni as $item)
                                    <tr>
                                        <td>{{ $item->no_surat }}</td>
                                        <td>{{ $item->kode_indeks }}</td>
                                        <td>{{ $item->perihal }}</td>
                                        <td>{{ strip_tags($item->penulis) }}</td>
                                        <td>{{ strip_tags($item->penerima) }}</td>
                                        <td>{{ $item->tanggal_keluar }}</td>
                                        <td><a href="{{ $item->dokumen }}">Lihat Dokumen</a></td>
                                        <td>
                                            @if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
                                                <a href="{{ route('suratkeluar.edit', $item->suratkeluar_id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            @if (auth()->user()->role == 'super_admin')
                                                <form
                                                    action="{{ route('suratkeluar.destroy', $item->suratkeluar_id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        title="Hapus"
                                                        onclick="confirmDeleteSK({{ $item->suratkeluar_id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        function confirmDeleteSM(suratmasukId) {
            Swal.fire({
                title: "Apa kamu yakin?",
                text: "Data ini tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "##28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus ini!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Temukan form dengan ID yang sesuai dan submit
                    document.getElementById("delete-form-" + suratmasukId).submit();
                }
            });
        }

        function confirmDeleteSK(suratkeluarId) {
            Swal.fire({
                title: "Apa kamu yakin?",
                text: "Data ini tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "##28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus ini!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Temukan form dengan ID yang sesuai dan submit
                    document.getElementById("delete-form-" + suratkeluarId).submit();
                }
            });
        }
    </script>
    <script>
        // For Total Surat Masuk
        var ctxIncoming = document.getElementById('chartIncoming').getContext('2d');
        var chartIncoming = new Chart(ctxIncoming, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Total Surat Masuk',
                    data: @json(array_values($totalSuratMasukBulan)), // This assumes the variable is passed from the controller
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // For Total Surat Keluar
        var ctxOutgoing = document.getElementById('chartOutgoing').getContext('2d');
        var chartOutgoing = new Chart(ctxOutgoing, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Total Surat Keluar',
                    data: @json(array_values($totalSuratKeluarBulan)), // This assumes the variable is passed from the controller
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


</body>

</html>
