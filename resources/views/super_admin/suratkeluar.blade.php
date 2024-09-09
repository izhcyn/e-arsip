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
        $(document).ready(function(){
            $(".siderbar_menu li").click(function(){
              $(".siderbar_menu li").removeClass("active");
              $(this).addClass("active");
            });

            $(".hamburger").click(function(){
              $(".wrapper").addClass("active");
            });

            $(".close, .bg_shadow").click(function(){
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
                    <li class="active"><a href="/super_admin/dashboard">
                        <div class="icon"><i class="fa fa-tachometer" aria-hidden="true"></i></div>
                        <div class="title">DASHBOARD</div>
                        </a>
                    </li>
                    <li><a href="#">
                        <div class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                        <div class="title">SURAT</div>
                        <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <ul class="accordion">
                            <li><a href="#" class="active">Buat Surat</a></li>
                            <li><a href="#" class="active">Draft Surat</a></li>
                            <li><a href="/super_admin/suratmasuk" class="active">Surat Masuk</a></li>
                            <li><a href="/super_admin/suratkeluar" class="active">Surat Keluar</a></li>
                        </ul>
                    </li>
                    <li><a href="#">
                        <div class="icon"><i class="fa fa-cog" aria-hidden="true"></i></div>
                        <div class="title">PENGATURAN</div>
                        <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <ul class="accordion">
                            <li><a href="#" class="active">indeks</a></li>
                            <li><a href="/super_admin/user" class="active">User</a></li>
                            <li><a href="#" class="active">Change Password</a></li>
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
                    <a href="#">Surat Keluar</a>
                </div>
                <div class="user_info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                </div>
            </div>

            <div class="container mt-5">
                <!-- Form toggle button -->
                <button id="toggleForm" class="btn btn-secondary mt-3">Tambah Surat Keluar</button>

                <!-- Form untuk tambah user yang bisa di-minimize -->
                <div class="card mt-4 user-form" style="display: none;">
                    @if (session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif
                    <div class="card-header">Surat Keluar</div>
                    <div class="card-body">
                        <form action="{{ route('user.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="superadmin">Super_Admin</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>


                    </div>
                </div>

                <!-- Tabel pengguna -->
                <div class="suratmasuk-section">
            <div class="suratmasuk-card">
            <h3>Surat Keluar</h3>
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
                    <tbody>
                        @if($suratKeluar->isEmpty())
                            <tr>
                                <td colspan="8">Tidak ada surat keluar.</td>
                            </tr>
                        @else
                            @foreach($suratKeluar as $item)
                                <tr>
                                    <td>{{ $item->no_surat }}</td>
                                    <td>{{ $item->kode_indeks }}</td>
                                    <td>{{ $item->asal_surat }}</td>
                                    <td>{{ $item->perihal }}</td>
                                    <td>{{ $item->penerima }}</td>
                                    <td>{{ $item->tanggal_diterima }}</td>
                                    <td><a href="{{ $item->dokumen }}">Dokumen</a></td>
                                    <td>
                                        <a href="{{ route('surat.show', $item->id) }}" class="btn btn-primary btn-sm" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('surat.download', $item->id) }}" class="btn btn-info btn-sm" title="Download PDF">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        @if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
                                        <a href="{{ route('surat.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif

                                        @if(auth()->user()->role == 'super_admin')
                                        <form action="{{ route('surat.destroy', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">
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

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </div>
    </div>
</body>
</html>
