<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat Surat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/buatsurat.css">
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
                       <li><a href="/super_admin/templatesurat" class="active">Template Surat</a></li>
                       <li><a href="/super_admin/indeks" class="active">indeks</a></li>
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
               <a href="#">BUAT SURAT</a>
            </div>
            <div class="user_info">
                <i class="fas fa-user-circle"></i>
                <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
            </div>
          </div>
          <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <img src="/assets/heading_surat.png" alt="heading">
            <form action="{{ route('super_admin.store') }}" method="POST" enctype="multipart/form-data">
                 @csrf
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}">

                <label for="noSurat">No. Surat:</label>
                <input type="text" id="noSurat" name="no_surat" value="{{ old('no_surat') }}">

                <label for="indeks">Indeks:</label>
                <select id="indeks" name="indeks">
                    <option value="redaksi">Redaksi</option>
                    <option value="lainnya">Lainnya</option>
                </select>

                <label for="perihal">Perihal:</label>
                <input type="text" id="perihal" name="perihal" value="{{ old('perihal') }}">

                <label for="templateSurat">Panggil Template (Optional):</label><br />
                <select id="templateSurat" name="templateSurat">
                    <option value="terima PL">Terima PL</option>
                    <option value="iklan">Iklan</option>
                </select>

                <br /><label for="lampiran">Lampiran:</label>
                <input type="text" id="lampiran" name="lampiran"" value="isi dengan - jika tidak ada">

                <label for="kepada">Kepada:</label>
                <input type="text" id="kepada" name="kepada" value="{{ old('kepada') }}">

                <label for="alamat">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}">

                <label for="isiSurat">Isi Surat:</label>
                <textarea id="isiSurat" name="isi_surat">{{ old('isi_surat') }}</textarea>

                <!-- Table Creation Section -->
                <div id="tableInputContainer">
                    <h4>Buat Tabel di Isi Surat:</h4>
                    <label for="rows">Jumlah Baris:</label>
                    <input type="number" id="rows" name="rows" min="1">

                    <label for="cols">Jumlah Kolom:</label>
                    <input type="number" id="cols" name="cols" min="1">

                    <button type="button" onclick="addTable()">Tambahkan Tabel</button>
                </div>

                <label for="penulis">Penulis:</label>
                <input type="text" id="penulis" name="penulis" value="{{ old('penulis') }}">

                <label for="jabatan">Jabatan:</label>
                <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}">

                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes">{{ old('notes') }}</textarea>

                <label for="signature">Upload Tanda Tangan (Optional):</label>
                <input type="file" id="signature" name="signature">

                <br /><label for="lampiranUpload">Tambahkan Lampiran:</label>
                <input type="file" id="lampiranUpload" name="lampiranUpload"><br />

                <button type="submit">Simpan Surat</button>
            </form>
        </div>

        <script>
            function addTable() {
                const rows = document.getElementById('rows').value;
                const cols = document.getElementById('cols').value;
                const isiSurat = document.getElementById('isiSurat');

                let tableHTML = '<table border="1" style="width:100%; margin-top:10px;">';
                for (let i = 0; i < rows; i++) {
                    tableHTML += '<tr>';
                    for (let j = 0; j < cols; j++) {
                        tableHTML += '<td>&nbsp;</td>';
                    }
                    tableHTML += '</tr>';
                }
                tableHTML += '</table>';

                isiSurat.value += tableHTML;  // Append table to the existing content
            }
        </script>


        </body>
</html>
