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
                    <li class="active"><a href="{{ route('superadmin.dashboard') }}">
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
                            <li><a href="{{ route('buatsurat.index') }}" class="active">Buat Surat</a></li>
                            <li><a href="{{ route('draft.index') }}" class="active">Draft Surat</a></li>
                            <li><a href="{{ route('suratmasuk.index') }}" class="active">Surat Masuk</a></li>
                            <li><a href="{{ route('suratkeluar.index') }}" class="active">Surat Keluar</a></li>
                            <li><a href="{{ route('laporan.index') }}" class="active">Laporan</a></li>
                        </ul>
                    </li>
                    <li><a href="#">
                            <div class="icon"><i class="fa fa-cog" aria-hidden="true"></i></div>
                            <div class="title">PENGATURAN</div>
                            <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <ul class="accordion">
                            <li><a href="{{ route('indeks.index') }}" class="active">indeks</a></li>
                            <li><a href="{{ route('template.index') }}" class="active">Template Surat</a></li>
                            <li><a href="{{ route('users.index') }}" class="active">User</a></li>
                            <li><a href="{{ route('profile.index') }}" class="active">Profile</a></li>
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
                    <a href="#">DRAFT</a>
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
            <div class="container">
                <h3 style="color: #00689d; margin-top:5%">Saved Drafts</h3>

                @if ($drafts->isEmpty())
                    <p>No drafts found.</p>
                @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Perihal</th>
                                <th>Kepada</th>
                                <th>Penulis</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($drafts as $draft)
                                <tr>
                                    <td>{{ $draft->tanggal ?? 'N/A' }}</td>
                                    <td>{{ $draft->perihal ?? 'No Subject' }}</td>
                                    <td>{!! $draft->kepada ?? 'N/A' !!}</td>
                                    <td>{!! $draft->penulis ?? 'N/A' !!}</td>
                                    <td>
                                        <!-- Button to load the draft -->
                                        <a href="{{ route('draft.loadById', $draft->id) }}" class="btn btn-primary">
                                            Edit Draft
                                        </a>

                                        <!-- Button to delete the draft -->
                                        <form action="{{ route('draft.delete', $draft->id) }}" method="POST"
                                            style="display:block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this draft?');">
                                                Hapus Draft
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
</body>

</html>
