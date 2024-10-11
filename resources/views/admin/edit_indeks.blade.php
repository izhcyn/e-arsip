<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Indeks</title>
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
               <a href="#">Indeks</a>
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

            <!-- Form untuk edit user -->
<div class="card mt-4">
    <div class="card-header">Edit Indeks</div>
    <div class="card-body">
        <form action="{{ route('indeks.update', $indeks->indeks_id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Tambahkan method PUT untuk update -->

            <div class="form-group">
                <label for="kode_indeks">Kode Indeks</label>
                <input type="text" class="form-control" id="kode_indeks" name="kode_indeks" value="{{ old('kode_indeks', $indeks->kode_indeks) }}" required>
            </div>

            <div class="form-group">
                <label for="kode_surat">Kode Surat</label>
                <input type="text" class="form-control" id="kode_surat" name="kode_surat" value="{{ old('kode_surat', $indeks->kode_surat) }}" required>
            </div>

            <div class="form-group">
                <label for="judul_indeks">Judul Indeks</label>
                <input type="text" class="form-control" id="judul_indeks" name="judul_indeks" value="{{ old('judul_indeks', $indeks->judul_indeks) }}" required>
            </div>

            <div class="form-group">
                <label for="last_number">No.Surat Terakhir</label>
                <input type="last_number" class="form-control" id="last_number" name="last_number" value="{{ old('last_number', $indeks->last_number) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>




            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </div>
    </div>
</body>
</html>
