<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Setting</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/user.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <style>
/* Contoh styling untuk profile dan form */
.profile-edit-form {
    width: 50%;
    margin: auto;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
}

.form-group input {
    width: 100%;
    padding: 0.5rem;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn-save {
    padding: 0.7rem 1.5rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
    </style>
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
                    <li class="active"><a href="{{ route('superadmin.dashboard')}}">
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
                            <li><a href="{{ route('super_admin.buatsurat')}}" class="active">Buat Surat</a></li>
                            <li><a href="{{ route('draft.index') }}" class="active">Draft Surat</a></li>
                            <li><a href="{{ route('suratmasuk.index')}}" class="active">Surat Masuk</a></li>
                            <li><a href="{{ route('suratkeluar.index')}}" class="active">Surat Keluar</a></li>
                            <li><a href="{{ route('laporan.index') }}" class="active">Laporan</a></li>
                        </ul>
                    </li>
                    <li><a href="#">
                        <div class="icon"><i class="fa fa-cog" aria-hidden="true"></i></div>
                        <div class="title">PENGATURAN</div>
                        <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <ul class="accordion">
                            <li><a href="{{ route('indeks.index')}}" class="active">indeks</a></li>
                            <li><a href="{{ route('template.index')}}" class="active">Template Surat</a></li>
                            <li><a href="{{ route('users.index') }}" class="active">User</a></li>
                            <li><a href="{{ route('superadmin.profile') }}" class="active">Profile</a></li>
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
                    <a href="#">Profile</a>
                </div>
                <div class="user_info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                </div>
            </div>

            <!-- Container centered and spaced from navbar -->
            <div class="container mt-5 d-flex justify-content-center align-items-center">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <!-- Use Font Awesome user icon if no profile picture is uploaded -->
                        @if($user->profile_picture)
                            <img src="{{ asset('uploads/profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture">
                        @else
                            <i class="fas fa-user-circle"></i>
                        @endif
                    </div>
                    <div class="profile-details">
                        <div class="profile-name">{{ $user->name }}</div>
                        <div class="profile-info">
                            <div class="detail">
                                <span class="label">Username: </span>
                                <span class="value">{{ $user->username }}</span>
                            </div>
                            <div class="detail">
                                <span class="label">Email: </span>
                                <span class="value">{{ $user->email }}</span>
                            </div>
                            <div class="detail">
                                <span class="label">Role: </span>
                                <span class="value">{{ $user->role }}</span>
                            </div>
                        </div>
                        <div class="profile-actions">
                            <a href="{{ route('superadmin.profile.edit') }}" class="action-btn edit">Edit Profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
