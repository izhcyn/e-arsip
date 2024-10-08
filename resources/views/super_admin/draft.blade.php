<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Draft Surat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/surat.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
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
		});

        // Auto-save draft after user input

        $(document).ready(function() {
    let timeout;

    $(document).ready(function() {
            let timeout;

            // Setiap kali ada perubahan pada form, tunggu 2 detik untuk menyimpan draft
            $('#buatSuratForm').on('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    saveDraft();
                }, 2000); // Simpan draft 2 detik setelah input berhenti
            });

            function saveDraft() {
                const data = $('#buatSuratForm').serialize(); // Serialize form data

                $.ajax({
                    url: '/drafts/save',  // Endpoint untuk menyimpan draft
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        console.log(response.message);  // Tampilkan pesan sukses
                    },
                    error: function(xhr) {
                        console.log('Error saving draft');
                    }
                });
            }
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
                       <li><a href="#" class="active">Change Password</a></li>
                    </ul>
                </li>
              </ul>
             <div class="logout_btn">
                  <a href="/">Logout</a>
              </div>

          </div>
        </div>
        <div class="main_container" >
          <div class="navbar">
             <div class="hamburger">
               <i class="fas fa-bars"></i>
             </div>
             <div class="logo">
               <a href="#">Draft Surat</a>
            </div>
            <div class="user_info">
                <i class="fas fa-user-circle"></i>
                <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
            </div>
            </div>

            <div class="container" style="margin-top: 5%">
                <h2>Draft Surat</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Surat</th>
                            <th>Perihal</th>
                            <th>Kepada</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($drafts->isEmpty())
                                    <tr>
                                        <td colspan="5">Tidak ada Draft.</td>
                                    </tr>
                                @else
                        @foreach($drafts as $draft)
                        <tr>
                            <td>{{ $draft->no_surat }}</td>
                            <td>{{ $draft->perihal }}</td>
                            <td>{{ $draft->kepada }}</td>
                            <td>{{ $draft->tanggal }}</td>
                            <td>
                                <a href="{{ route('draft.edit', $draft->id) }}">Edit</a> |
                                <form action="{{ route('draft.destroy', $draft->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </td>

                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
</body>
</html>
