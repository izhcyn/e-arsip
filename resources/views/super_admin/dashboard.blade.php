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
                       <li><a href="#" class="active">Surat Masuk</a></li>
                       <li><a href="#" class="active">Surat Keluar</a></li>
                    </ul>
                </li>
                <li><a href="#">
                    <div class="icon"><i class="fa fa-cog" aria-hidden="true"></i></div>
                    <div class="title">PENGATURAN</div>
                    <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                    </a>
                  <ul class="accordion">
                       <li><a href="#" class="active">indeks</a></li>
                       <li><a href="{{ route('user.index') }}" class="active">User</a></li>
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
               <a href="#">DASHBOARD</a>
            </div>
            <div class="user_info">
                <i class="fas fa-user-circle"></i>
                <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
            </div>
          </div>
          <div class="content">
            <div class="dashboard-container">
                <div class="dashboard-card">
                    <div class="dashboard-icon">
                        <img src="/assets/surat_masuk.png" alt="Surat Masuk">
                    </div>
                    <div class="dashboard-info">
                        <h3>Total Surat Masuk</h3>
                        <span>{{ $totalSuratMasuk }}</span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-icon">
                        <img src="/assets/surat_keluar.png" alt="Surat Keluar">
                    </div>
                    <div class="dashboard-info">
                        <h3>Total Surat Keluar</h3>
                        <span>{{ $totalSuratKeluar }}</span>
                    </div>
                </div>

                @if (isset($totalIndeks))
                <div class="dashboard-card">
                    <div class="dashboard-icon">
                        <img src="/assets/indeks.png" alt="Indeks">
                    </div>
                    <div class="dashboard-info">
                        <h3>Indeks</h3>
                        <span>{{ $totalIndeks }}</span>
                    </div>
                </div>
                @endif

                @if (isset($totalUsers))
                <div class="dashboard-card">
                    <div class="dashboard-icon">
                        <img src="/assets/user.png" alt="Users">
                    </div>
                    <div class="dashboard-info">
                        <h3>Users</h3>
                        <span>{{ $totalUsers }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="surat-section">
            <div class="surat-card">
            <h3>Surat Masuk Hari ini</h3>
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
                    @if($suratMasukHariIni->isEmpty())
                    <tr>
                        <td colspan="8">Tidak ada surat masuk hari ini.</td>
                    </tr>
                    @else
                    @foreach($suratMasukHariIni as $item)
                    <tr>
                        <td>{{ $item->no_surat }}</td>
                        <td>{{ $item->kode_indeks }}</td>
                        <td>{{ $item->asal_surat }}</td>
                        <td>{{ $item->perihal }}</td>
                        <td>{{ $item->penerima }}</td>
                        <td>{{ $item->tanggal_diterima }}</td>
                        <td><a href="{{ $item->dokumen }}">Lihat Dokumen</a></td>
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
            </div>
        </div>

        <div class="surat-section">
            <div class="surat-card">
            <h3>Surat Keluar Hari ini</h3>
            <table>
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Indeks Surat</th>
                        <th>Asal Surat</th>
                        <th>Perihal</th>
                        <th>Penulis</th>
                        <th>Penerima</th>
                        <th>Tanggal Diterima</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($suratKeluarHariIni->isEmpty())
                    <tr>
                        <td colspan="8">Tidak ada surat keluar hari ini.</td>
                    </tr>
                    @else
                    @foreach($suratKeluarHariIni as $item)
                    <tr>
                        <td>{{ $item->no_surat }}</td>
                        <td>{{ $item->kode_indeks }}</td>
                        <td>{{ $item->asal_surat }}</td>
                        <td>{{ $item->perihal }}</td>
                        <td>{{ $item->penulis }}</td>
                        <td>{{ $item->penerima }}</td>
                        <td>{{ $item->tanggal_diterima }}</td>
                        <td><a href="{{ $item->dokumen }}">Lihat Dokumen</a></td>
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
            </div>
        </div>
        </div>
      </div>
    </div>
</body>
</html>
