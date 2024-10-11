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
               <a href="#">Laporan</a>
            </div>
            <div class="user_info">
                <i class="fas fa-user-circle"></i>
                <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
            </div>
          </div>
          <div class="container-chart">
            <!-- Tempat grafik -->
            <div class="canvas-chart mt-4">
                <h4 style="font-weight: 700;">Grafik Surat Bulanan</h4>
                        <!-- Form untuk memilih jenis data, bulan, dan tahun -->
                        <form id="filterForm">
                            <select name="jenis_data" required>
                                <option value="surat_masuk" {{ $jenisData == 'surat_masuk' ? 'selected' : '' }}>Surat Masuk</option>
                                <option value="surat_keluar" {{ $jenisData == 'surat_keluar' ? 'selected' : '' }}>Surat Keluar</option>
                                <option value="keseluruhan" {{ $jenisData == 'keseluruhan' ? 'selected' : '' }}>Keseluruhan</option>
                            </select>

                            <select name="bulan" required>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>{{ $bulanIndo[$i] }}</option>
                                @endfor
                            </select>

                            <select name="tahun" required>
                                @for ($i = 2020; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ $i == $tahun ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </form>
                <canvas id="myChart"></canvas>
            </div>
            <!-- Bagian data laporan -->
            <div class="data-laporan mt-4">
                <p>Total {{ ucfirst(str_replace('_', ' ', $jenisData)) }} Hari Ini: <strong>{{ $totalSuratHariIni }}</strong></p>
                <p>Total {{ ucfirst(str_replace('_', ' ', $jenisData)) }} Minggu Ini: <strong>{{ $totalSuratMingguIni }}</strong></p>
                <p>Total {{ ucfirst(str_replace('_', ' ', $jenisData)) }} Bulan Ini: <strong>{{ $totalSuratBulanIni }}</strong></p>
                <p>Total Data {{ ucfirst(str_replace('_', ' ', $jenisData)) }} Bulan Ini: <strong>{{ array_sum($jumlahPerMinggu) }}</strong></p>
            </div>


        </div>
</body>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [{
                    label: 'Total {{ ucfirst($jenisData) }}',
                    data: {!! json_encode($jumlahPerMinggu) !!}, // Data dari controller
                    backgroundColor: '#0077B6',
                    borderColor: '#0077B6',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>

        <script>
            // Ambil elemen form
            var filterForm = document.getElementById('filterForm');

            // Tambahkan event listener untuk setiap elemen form
            filterForm.querySelectorAll('select').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    // Ketika ada perubahan, submit form secara otomatis
                    filterForm.submit();
                });
            });
        </script>

</html>
