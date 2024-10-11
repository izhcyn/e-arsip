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
               <a href="#">Buat Surat</a>
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
          <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <img src="/assets/heading_surat2.png" alt="heading">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="buatSuratForm" action="{{ route('super_admin.store') }}" method="POST" enctype="multipart/form-data">
                 @csrf
                <label for="tanggal">Tanggal<span class="star">*</span></label>
                <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>

                <label for="indeks">Indeks<span class="star">*</span></label>
                <select id="indeks" name="indeks" class="form-control" required>
                    <option value="">-- Pilih Indeks --</option>
                    @foreach ($indeks as $indek)
                        <option value="{{ $indek->kode_indeks }}">{{ $indek->kode_indeks }} - {{ $indek->judul_indeks }}</option>
                    @endforeach
                </select>

                <label for="noSurat">No. Surat<span class="star">*</span></label>
                <input type="text" id="noSurat" name="no_surat" value="{{ old('no_surat') }}" readonly required>

                <label for="perihal">Perihal<span class="star">*</span></label>
                <input type="text" id="perihal" name="perihal" value="{{ old('perihal') }}" required>


                <br /><label for="lampiran">Lampiran<span class="star">*</span></label>
                <input placeholder="isi dengan - jika tidak ada lampiran" type="text" id="lampiran" name="lampiran" value="{{ old('lampiran')}}">

                <label for="file_lampiran">Upload Lampiran (optional) :</label>
                <input type="file" id="file_lampiran" name="file_lampiran" class="file-upload-input" accept=".pdf,.jpg,.jpeg,.png">
                <p class="file-upload-note">*File bisa berupa pdf, jpg, png, jpeg</p>

                <label for="kepada">Kepada<span class="star">*</span></label>
                <textarea id="kepada" name="kepada" class="form-control" required>{{ old('kepada') }}</textarea>

                <label for="alamat">Alamat<span class="star">*</span></label>
                <textarea id="alamat" name="alamat" required>{{ old('alamat') }}</textarea>

                <label for="templateSurat">Template (Optional):</label><br />
                <select id="templateSurat" name="templateSurat" class="form-control">
                    <option value="">-- Pilih Template --</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" data-content="{{ $template->isi_surat }}">{{ $template->judul_template }}</option>
                    @endforeach
                </select>

                <label for="isiSurat">Isi Surat<span class="star">*</span></label>
                <textarea id="isiSurat" name="isi_surat">{{ old('isi_surat') }}</textarea>

                <label for="penulis">Penulis<span class="star">*</span></label>
                <input type="text" id="penulis" name="penulis" value="{{ old('penulis', Auth::user()->name) }}" required>

                <label for="jabatan">Jabatan<span class="star">*</span></label>
                <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required>

                <label for="notes">Notes (optional) :</label>
                <textarea id="notes" name="notes" class="form-control">{{ old('notes') }}</textarea>

                <!-- Input file tanda tangan -->
                <label for="signature">Upload Tanda Tangan (optional) :</label>
                <input type="file" id="signature" name="signature" class="file-upload-input" accept="image/png">
                <p class="file-upload-note">*File harus berformat .png</p>

                <button type="submit" formtarget="_blank" id="downloadButton">Download dan Simpan Surat</button>

            </form>
        </div>

        <script>
            tinymce.init({
                selector: '#isiSurat',  // Menggunakan textarea dengan id "isiSurat"
                plugins: 'table lists',  // Menambahkan plugin tabel dan list
                toolbar: 'undo redo | formatselect | bold italic underline| alignleft aligncenter alignright alignjustify | bullist numlist | table',  // Toolbar dengan fitur table
                menubar: 'file edit view insert format table tools help',  // Menambahkan menu insert untuk table
                height: 500,  // Mengatur tinggi editor
                setup: function (editor) {
                    editor.on('init', function () {
                        this.getContainer().style.transition = 'border-color 0.15s ease-in-out';
                    });
                }
            });

            tinymce.init({
                selector: '#kepada, #alamat, #penulis, #notes',  // Menggunakan textarea dengan id "isiSurat"
                plugins: 'table lists',  // Menambahkan plugin tabel dan list
                toolbar: 'undo redo | formatselect | bold italic underline| alignleft aligncenter alignright alignjustify | bullist numlist | table',  // Toolbar dengan fitur table
                menubar: 'file edit view insert format tools help',  // Menambahkan menu insert untuk table
                height: 180,  // Mengatur tinggi editor
                setup: function (editor) {
                    editor.on('init', function () {
                        this.getContainer().style.transition = 'border-color 0.15s ease-in-out';
                    });
                }
            });

            // Sinkronisasi konten TinyMCE sebelum form di-submit
            $('#buatSuratForm').on('submit', function(e) {
                var content = tinymce.get('isiSurat').getContent();

                if (content === '') {
                    e.preventDefault();
                    alert('Isi Surat harus diisi.');
                } else {
                    tinymce.triggerSave();  // Sinkronkan konten TinyMCE dengan textarea
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

            $(document).ready(function() {
                // When the user selects an index
                $('#indeks').on('change', function() {
                    var selectedIndeks = $(this).val();

                    // If an index is selected, fetch the last number via AJAX
                    if (selectedIndeks) {
                        $.ajax({
                            url: '/get-last-number/' + selectedIndeks,
                            method: 'GET',
                            success: function(response) {
                                if (response.nextNumber) {
                                    // Set the no_surat input with the new auto-incremented number
                                    var noSurat = selectedIndeks + '/' + String(response.nextNumber).padStart(3, '0');
                                    $('#noSurat').val(noSurat);
                                }
                            },
                            error: function(xhr) {
                                alert('Error fetching last number');
                            }
                        });
                    } else {
                        $('#noSurat').val(''); // Clear the no_surat input if no index is selected
                    }
                });
            });

        document.getElementById('downloadButton').addEventListener('click', function(event) {
        event.preventDefault();  // Mencegah form agar tidak langsung dikirim
        var form = document.getElementById('buatSuratForm');

        // Simpan form ke tab baru untuk download file
        form.target = '_blank';
        form.submit();

        // Tunggu beberapa detik lalu redirect ke halaman suratkeluar
        setTimeout(function(){
                window.location.href = "/admin/suratkeluar";  // Redirect ke halaman suratkeluar
            }, 2000);  // Set timeout 2 detik (sesuaikan jika perlu)
        });

        </script>

    </div>
</body>
</html>
