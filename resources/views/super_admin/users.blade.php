<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Setting</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/user.css">
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
                            <li><a href="/super_admin/buatsurat" class="active">Buat Surat</a></li>
                            <li><a href="/super_admin/draftsurat" class="active">Draft Surat</a></li>
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
                            <li><a href="/super_admin/indeks" class="active">indeks</a></li>
                            <li><a href="/super_admin/template" class="active">Template Surat</a></li>
                            <li><a href="{{ route('users.index') }}" class="active">User</a></li>
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
                    <a href="#">USERS</a>
                </div>
                <div class="user_info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                </div>
            </div>
            <div class="container mt-5">
                <div class="d-flex justify-content-between">
                    <h1 class="heading-daftar-pengguna">Daftar Pengguna</h1>
                </div>
                <!-- Form toggle button -->
                <button id="toggleForm" class="btn btn-secondary mt-3">Tambah User Baru</button>
                <!-- Form untuk tambah user yang bisa di-minimize -->
                <div class="card mt-4 user-form" style="display: none;">
                    <div class="card-header">Tambah User Baru</div>
                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="POST">
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

                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="super_admin">super_admin</option><!-- Sesuaikan dengan enum -->
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>

                <!-- Tabel pengguna -->
                <div class="card card-table mt-4">

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>username</th>
                                <th>password</th>
                                <th>role</th>
                                <th>Aksi</th>
                            </tr>
                            <tr>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->password }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach

            </div>

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            function confirmDelete(userId) {
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
                        document.getElementById("delete-form-" + userId).submit();
                    }
                });
            }
            </script>

            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </div>
    </div>
</body>
</html>
