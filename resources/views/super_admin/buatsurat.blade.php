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
    <script src="https://cdn.tiny.cloud/1/zgev9qnaivedr9z3cynwqe34owhimfprefdpid7lnhlfdpy1/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
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
                       <li><a href="/super_admin/template" class="active">Template Surat</a></li>
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
                <label for="tanggal">Tanggal<span class="star">*</span></label>
                <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>

                <label for="noSurat">No. Surat<span class="star">*</span></label>
                <input type="text" id="noSurat" name="no_surat" value="{{ old('no_surat') }}" required>

                <label for="indeks">Indeks<span class="star">*</span></label>
                <select id="indeks" name="indeks" class="form-control" required>
                    @foreach ($indeks as $indek)
                        <option value="{{ $indek->judul_indeks }}">{{ $indek->judul_indeks }}</option>
                    @endforeach
                </select>


                <label for="perihal">Perihal<span class="star">*</span></label>
                <input type="text" id="perihal" name="perihal" value="{{ old('perihal') }}" required>

                <label for="templateSurat">Template (Optional):</label><br />
                <select id="templateSurat" name="templateSurat" class="form-control">
                    <option value="">-- Pilih Template --</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" data-content="{{ $template->isi_surat }}">{{ $template->judul_template }}</option>
                    @endforeach
                </select>

                <br /><label for="lampiran">Lampiran<span class="star">*</span></label>
                <input placeholder="isi dengan - jika tidak ada lampiran" type="text" id="lampiran" name="lampiran" required>

                <label for="kepada">Kepada<span class="star">*</span></label>
                <input type="text" id="kepada" name="kepada" value="{{ old('kepada') }}" required>

                <label for="alamat">Alamat<span class="star">*</span></label>
                <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" required>

                <label for="isiSurat">Isi Surat<span class="star">*</span></label>
                <textarea id="isiSurat" name="isi_surat" class="form-control">{{ old('isi_surat') }} @required(true)</textarea>

                <label for="penulis">Penulis<Span class="star">*</Span></label>
                <input type="text" id="penulis" name="penulis" value="{{ old('penulis') }}" required>

                <label for="jabatan">Jabatan<span class="star">*</span></label>
                <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required>

                <label for="notes">Notes (optional) :</label>
                <textarea id="notes" name="notes">{{ old('notes') }}</textarea>

                <!-- Input file tanda tangan -->
                <label for="signature">Upload Tanda Tangan (optional) :</label>
                <input type="file" id="signature" name="signature" class="file-upload-input" accept="image/png">
                <p class="file-upload-note">*File harus berformat .png</p>

                <label for="lampiranUpload">Upload Lampiran (optional) :</label>
                <input type="file" id="lampiranUpload" name="lampiranUpload" class="file-upload-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <p class="file-upload-note">*File bisa berupa pdf, doc, docx, jpg, png, jpeg</p>

                <button type="submit">Download  dan Simpan Surat</button>
            </form>
        </div>

        <script>
            tinymce.init({
                selector: '#isiSurat',  // Menggunakan textarea dengan id "isiSurat"
                plugins: 'table lists',  // Menambahkan plugin tabel dan list
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | table',  // Toolbar dengan fitur table
                menubar: 'file edit view insert format table tools help',  // Menambahkan menu insert untuk table
                height: 500,  // Mengatur tinggi editor
                setup: function (editor) {
                    editor.on('init', function () {
                        this.getContainer().style.transition = 'border-color 0.15s ease-in-out';
                    });
                }
            });

            document.getElementById('signature').addEventListener('change', function () {
                        const file = this.files[0];
                        const errorMsg = document.getElementById('error-msg');

                        if (file && file.type !== 'image/png') {
                            errorMsg.style.display = 'block';
                            this.value = ''; // Reset input jika file bukan PNG
                        } else {
                            errorMsg.style.display = 'none';
                        }
                    });

            // Using jQuery to handle template selection
        $('#templateSurat').on('change', function () {
            // Get the selected template's content
            var selectedContent = $(this).find(':selected').data('content');

            // If a template is selected, fill the "Isi Surat" field with the template content
            if (selectedContent) {
                tinymce.get('isiSurat').setContent(selectedContent); // Using TinyMCE to set content
            } else {
                tinymce.get('isiSurat').setContent(''); // Clear the field if no template is selected
            }
        });

        </script>



        </body>
</html>
