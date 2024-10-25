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
                        <li><a href="{{ route('users.index') }}"
                                class="{{ request()->routeIs('users.index') ? 'active' : '' }}">User</a></li>
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
               <a href="#">User</a>
            </div>
            <div class="user_info">
                @if($user->profile_picture)
                    <img src="{{ asset('uploads/profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture">
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                @else
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                @endif
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
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-eye" id="toggle-password" style="cursor: pointer;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>


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
                <div class="container mt-3">
                    <label for="recordsPerPage">Show records:</label>
                    <select id="recordsPerPage" class="form-control small-select" style="width: auto; display: inline-block;">
                        <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('limit') == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </div>

                <div class="container">
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
                                <th style="width: 20%">Name</th>
                                <th style="width: 20%">Email</th>
                                <th style="width: 10%">Username</th>
                                <th style="width: 15%">Password</th>
                                <th style="width: 10%">Role</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                            <tr>
                                <th><input type="text" id="filterName" class="form-control" value="{{ request('name') }}"></th>
                                <th><input type="text" id="filterEmail" class="form-control" value="{{ request('email') }}"></th>
                                <th><input type="text" id="filterUsername" class="form-control" value="{{ request('username') }}"></th>
                                <th></th>
                                <th><input type="text" id="filterRole" class="form-control" value="{{ request('role') }}"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
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
                        </tbody>
                    </table>
                </div> <!-- End of table container -->

                <div class="mt-3">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            {{-- Previous Button --}}
                            <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                   href="{{ $users->previousPageUrl() }}&limit={{ $users->perPage() }}"
                                   aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>

                            {{-- Page Numbers --}}
                            @php
                                $currentPage = $users->currentPage();
                                $lastPage = $users->lastPage();
                                $startPage = max(1, $currentPage - 1);
                                $endPage = min($lastPage, $currentPage + 1);
                            @endphp

                            {{-- First Page link --}}
                            @if ($startPage > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->url(1) }}&limit={{ $users->perPage() }}">1</a>
                                </li>
                                @if ($startPage > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            {{-- Page Range --}}
                            @for ($i = $startPage; $i <= $endPage; $i++)
                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $users->url($i) }}&limit={{ $users->perPage() }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Last Page link --}}
                            @if ($endPage < $lastPage)
                                @if ($endPage < $lastPage - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->url($lastPage) }}&limit={{ $users->perPage() }}">{{ $lastPage }}</a>
                                </li>
                            @endif

                            {{-- Next Button --}}
                            <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link"
                                   href="{{ $users->nextPageUrl() }}&limit={{ $users->perPage() }}"
                                   aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>


            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.getElementById('toggle-password').addEventListener('click', function() {
                    var passwordInput = document.getElementById('password');
                    var icon = document.getElementById('toggle-password');

                    // Toggle password visibility
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');  // Change icon to "eye slash" when showing password
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');  // Change icon back to "eye" when hiding password
                    }
                });

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
            <script>
                $(document).ready(function() {
                    // Handle limit change
                    $('#recordsPerPage').change(function() {
                        var limit = $(this).val(); // Get the selected limit value
                        var url = new URL(window.location.href); // Get the current URL
                        url.searchParams.set('limit', limit); // Set the limit parameter
                        window.location.href = url.href; // Reload the page with the new limit
                    });

                    // Handle filtering
                    $('#filterName, #filterEmail, #filterUsername, #filterRole').on('keyup change', function() {
                        var url = new URL(window.location.href);
                        url.searchParams.set('name', $('#filterName').val());
                        url.searchParams.set('email', $('#filterEmail').val());
                        url.searchParams.set('username', $('#filterUsername').val());
                        url.searchParams.set('role', $('#filterRole').val());
                        window.location.href = url.href;
                    });
                });
            </script>

            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </div>
    </div>
    </div>

</body>
</html>
