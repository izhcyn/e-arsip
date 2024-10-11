<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Setting</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* CSS untuk memusatkan form di halaman */
.profile-edit-container {
    display: flex;
    justify-content: center; /* Memusatkan secara horizontal */
    align-items: center; /* Memusatkan secara vertikal */
    height: 100vh; /* Tinggi penuh layar */
    padding: 20px; /* Padding agar tidak terlalu dekat dengan tepi layar */
    background-color: #f0f2f5; /* Latar belakang ringan untuk kontras */
}

/* Card yang membungkus form agar terlihat lebih elegan */
.profile-card {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow untuk efek 3D */
    max-width: 500px; /* Maksimal lebar form */
    width: 100%; /* Lebar form sesuai ukuran kontainer */
}

/* Avatar section styling */
/* Avatar section styling */
.profile-avatar {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background-color: #e0e0e0; /* Lingkaran abu-abu */
    position: relative;
}

.profile-avatar img,
.profile-avatar i {
    width: 80px; /* Ukuran ikon atau gambar */
    height: 80px; /* Ukuran ikon atau gambar */
    border-radius: 50%;
    object-fit: cover; /* Untuk memastikan gambar tetap dalam rasio */
    position: absolute; /* Pastikan gambar/ikon berada di tengah lingkaran */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Ini akan memastikan ikon berada tepat di tengah */
    color: #555; /* Warna ikon jika tidak ada gambar */
}

/* Tambahan jika ikon menggunakan Font Awesome */
.profile-avatar i {
    font-size: 5rem; /* Sesuaikan ukuran ikon Font Awesome */
}


/* Form styling */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

/* Button styling */
.profile-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.profile-actions .action-btn {
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    font-size: 16px;
}

.profile-actions .cancel {
    background-color: #f0f0f0;
    color: #333;
}

.profile-actions .action-btn:hover {
    background-color: #0056b3;
}

.profile-actions .cancel:hover {
    background-color: #dcdcdc;
}

/* Responsiveness */
@media (max-width: 768px) {
    .profile-card {
        max-width: 100%;
        padding: 20px;
    }

    .profile-actions {
        flex-direction: column;
        gap: 10px;
    }

    .profile-actions .action-btn {
        width: 100%;
    }
}

    </style>
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
                @if($user->profile_picture)
                    <img src="{{ asset('uploads/profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture">
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                @else
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                @endif
            </div>
            </div>
        </div>
</div>
<div class="profile-edit-container">
    <form action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-card">
        @csrf
        <div class="profile-avatar">
            @if($user->profile_picture)
                <img src="{{ asset('uploads/profile_pictures/' . $user->profile_picture) }}" alt="Profile Avatar" />
            @else
            <i class="fas fa-user-circle"></i>
            @endif
        </div>
        <div class="form-group">
            <label for="profile_picture">Upload Foto Profil</label>
            <input type="file" name="profile_picture" accept="image/*">
        </div>
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="password">Password (Opsional)</label>
            <input type="password" name="password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" name="password_confirmation">
        </div>
        <div class="profile-actions">
            <button type="submit" class="action-btn edit">Simpan Perubahan</button>
            <a href="{{ route('superadmin.profile') }}" class="action-btn cancel">Batal</a>
        </div>
    </form>
</div>

</body>
</html>
