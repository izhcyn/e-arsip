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


        .profile-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 30px;
            background-color: #eef5ff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 48px;
            color: #ccc;
        }

        .profile-avatar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail {
            font-size: 20px;
        }

        .profile-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .action-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit {
            background-color: #1c73db;
            color: #fff;
        }

        .cancel {
            background-color: #ccc;
            color: #000;
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
                <li class="active"><a href="{{ route('users.dashboard')}}">
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
                     <li><a href="{{ route('suratmasuk.index')}}" class="active">Surat Masuk</a></li>
                     <li><a href="{{ route('suratkeluar.index')}}" class="active">Surat Keluar</a></li>
                  </ul>
              </li>
              <li><a href="#">
                  <div class="icon"><i class="fa fa-cog" aria-hidden="true"></i></div>
                  <div class="title">PENGATURAN</div>
                  <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                  </a>
                <ul class="accordion">
                     <li><a href="{{ route('indeks.index')}}" class="active">indeks</a></li>
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
               <a href="#">Profile Setting</a>
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
                            <a href="{{ route('profile.edit') }}" class="action-btn edit">Edit Profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
