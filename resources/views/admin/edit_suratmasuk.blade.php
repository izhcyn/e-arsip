<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Masuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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

            $("#toggleForm").click(function() {
                $(".user-form").slideToggle(); // Show/hide the form
                $(this).text($(this).text() == 'Minimize Form' ? 'Tambah User Baru' : 'Minimize Form');
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
                    <a href="#">EDIT SURAT MASUK</a>
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

            <!-- Form untuk edit user -->
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header">Edit Surat Masuk</div>
                    <div class="card-body">
                        <form action="{{ route('suratmasuk.update', $suratMasuk->suratmasuk_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="no_surat">No Surat</label>
                                <input type="text" class="form-control" id="no_surat" name="no_surat"
                                    value="{{ $suratMasuk->no_surat }}" required>
                            </div>

                            <div class="form-group">
                                <label for="kode_indeks">Kode Indeks</label>
                                <select class="form-control" id="kode_indeks" name="kode_indeks" required>
                                    <option value="" disabled>Pilih Kode Indeks</option>
                                    @foreach ($indeks as $indek)
                                        <option value="{{ $indek->kode_indeks }}"
                                            {{ $suratMasuk->kode_indeks == $indek->kode_indeks ? 'selected' : '' }}>
                                            {{ $indek->kode_indeks }} - {{ $indek->judul_indeks }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="asal_surat">Asal Surat</label>
                                <input type="text" class="form-control" id="asal_surat" name="asal_surat"
                                    value="{{ $suratMasuk->asal_surat }}" required>
                            </div>

                            <div class="form-group">
                                <label for="perihal">Perihal</label>
                                <input type="text" class="form-control" id="perihal" name="perihal"
                                    value="{{ $suratMasuk->perihal }}" required>
                            </div>

                            <div class="form-group">
                                <label for="penerima">Penerima</label>
                                <input type="text" class="form-control" id="penerima" name="penerima"
                                    value="{{ $suratMasuk->penerima }}" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_diterima">Tanggal Diterima</label>
                                <input type="date" class="form-control" id="tanggal_diterima"
                                    name="tanggal_diterima" value="{{ $suratMasuk->tanggal_diterima }}" required>
                            </div>

                            <div class="form-group">
                                @php
                                    $filePath = asset('storage/' . $suratMasuk->dokumen); // Path untuk file PDF
                                    $fileName = basename($filePath); // Menampilkan nama file
                                @endphp
                                <label for="dokumen">Unggah Dokumen</label>
                                <input type="file" class="form-control" id="dokumen" name="dokumen">
                                @if ($suratMasuk->dokumen)
                                    <small>Dokumen saat ini: <a href="{{ asset('storage/' . $suratMasuk->dokumen) }}"
                                            target="_blank">{{ $fileName }}</a></small>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>

                    </div>
                </div>
            </div>



            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </div>
    </div>
</body>

</html>
