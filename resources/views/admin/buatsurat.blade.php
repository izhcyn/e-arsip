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
    <script src="https://cdn.tiny.cloud/1/0viyhi5ifj209mzkb66rqkh4rluwncrzmqyioj0245xy5p2a/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            $(".siderbar_menu li").click(function() {
                $(".siderbar_menu li").removeClass("active");
                $(this).addClass("active");
            });

            $(".hamburger").click(function() {
                $(".wrapper").addClass("active");
            });

            $(".close, .bg_shadow").click(function() {
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
                    <li class="active"><a href="{{ route('superadmin.dashboard') }}">
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
                            <li><a href="{{ route('buatsurat.index') }}" class="active">Buat Surat</a></li>
                            <li><a href="{{ route('drafts.index') }}" class="active">Draft Surat</a></li>
                            <li><a href="{{ route('suratmasuk.index') }}" class="active">Surat Masuk</a></li>
                            <li><a href="{{ route('suratkeluar.index') }}" class="active">Surat Keluar</a></li>
                            <li><a href="{{ route('laporan.index') }}" class="active">Laporan</a></li>
                        </ul>
                    </li>
                    <li><a href="#">
                            <div class="icon"><i class="fa fa-cog" aria-hidden="true"></i></div>
                            <div class="title">PENGATURAN</div>
                            <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <ul class="accordion">
                            <li><a href="{{ route('indeks.index') }}" class="active">indeks</a></li>
                            <li><a href="{{ route('template.index') }}" class="active">Template Surat</a></li>
                            <li><a href="{{ route('users.index') }}" class="active">User</a></li>
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
                    <a href="#">Buat Surat</a>
                </div>
                <div class="user_info">
                    @if ($user->profile_picture)
                        <img src="{{ asset('uploads/profile_pictures/' . $user->profile_picture) }}"
                            alt="Profile Picture">
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

                <form id="suratForm" action="{{ route('draft.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="draft_id" value="{{ $draft->id ?? '' }}">
                    <div class="form-group">
                        <label for="tanggal">Tanggal<span class="star">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control"
                            value="{{ $draft->tanggal ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="indeks">Indeks<span class="star">*</span></label>
                        <select id="indeks" name="indeks" class="form-control">
                            <option value="">-- Pilih Indeks --</option>
                            @foreach ($indeks as $index)
                                <option value="{{ $index->kode_indeks }}"
                                    {{ isset($draft) && $draft->indeks == $index->kode_indeks ? 'selected' : '' }}>
                                    {{ $index->kode_surat }} - {{ $index->judul_indeks }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <label for="noSurat">No. Surat<span class="star">*</span></label>
                    <input type="text" id="noSurat" name="no_surat" value="{{ $draft->no_surat ?? '' }}"
                        readonly required>

                    <div class="form-group">
                        <label for="perihal">Perihal<span class="star">*</span></label>
                        <input type="text" id="perihal" name="perihal" class="form-control"
                            value="{{ $draft->perihal ?? '' }}">
                    </div>

                    <br /><label for="lampiran">Lampiran<span class="star">*</span></label>
                    <input placeholder="isi dengan - jika tidak ada lampiran" type="text" id="lampiran"
                        name="lampiran" value="{{ old('lampiran') }}">

                    <label for="file_lampiran">Upload Lampiran (optional) :</label>
                    <input type="file" id="file_lampiran" name="file_lampiran" class="file-upload-input"
                        accept=".pdf,.jpg,.jpeg,.png">
                    <p class="file-upload-note">*File bisa berupa pdf, jpg, png, jpeg</p>

                    <div class="form-group">
                        <label for="kepada">Kepada<span class="star">*</span></label>
                        <textarea type="text" id="kepada" name="kepada" class="form-control"> {{ $draft->kepada ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat<span class="star">*</span></label>
                        <textarea id="alamat" name="alamat" class="form-control">{{ $draft->alamat ?? '' }}</textarea>
                    </div>

                    <label for="templateSurat">Template (Optional):</label><br />
                    <select id="templateSurat" name="templateSurat" class="form-control">
                        <option value="">-- Pilih Template --</option>
                        @foreach ($templates as $template)
                            <option value="{{ $template->id }}" data-content="{{ $template->isi_surat }}">
                                {{ $template->judul_template }}</option>
                        @endforeach
                    </select>

                    <label for="isiSurat">Isi Surat<span class="star">*</span></label>
                    <textarea id="isiSurat" name="isi_surat" class="form-control">{{ $draft->isi_surat ?? '' }}</textarea>

                    <label for="penulis">Penulis<span class="star">*</span></label>
                    <input type="text" id="penulis" name="penulis"
                        value="{{ old('penulis', Auth::user()->name) }}" required>

                    <label for="jabatan">Jabatan<span class="star">*</span></label>
                    <input type="text" id="jabatan" name="jabatan" value="{{ $draft->jabatan ?? '' }}"
                        required>

                    <label for="notes">Notes (optional) :</label>
                    <textarea id="notes" name="notes" class="form-control">{{ old('notes') }}</textarea>

                    <!-- Input file tanda tangan -->
                    <label for="signature">Upload Tanda Tangan (optional) :</label>
                    <input type="file" id="signature" name="signature" class="file-upload-input"
                        accept="image/png">
                    <p class="file-upload-note">*File harus berformat .png</p>

                    <!-- Tombol Download dan Simpan Surat -->
                    <button type="submit" id="downloadButton" class="btn btn-primary">Download dan Simpan
                        Surat</button>

                    <!-- Tombol Simpan ke Draft -->
                    <button type="button" id="saveDraftButton" class="btn btn-secondary"
                        style="margin: 0 0 15px 10%;">Simpan ke Draft</button>


                </form>
            </div>
            <script>
                // Inisialisasi TinyMCE untuk berbagai bidang teks
                tinymce.init({
                    selector: '#isiSurat', // Editor utama
                    plugins: 'table lists',
                    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | table',
                    menubar: 'file edit view insert format table tools help',
                    height: 500,
                    setup: function(editor) {
                        editor.on('init', function() {
                            this.getContainer().style.transition = 'border-color 0.15s ease-in-out';
                        });
                    }
                });

                tinymce.init({
                    selector: '#kepada, #alamat, #penulis, #notes', // Editor tambahan
                    plugins: 'table lists',
                    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | table',
                    menubar: 'file edit view insert format tools help',
                    height: 180,
                    setup: function(editor) {
                        editor.on('init', function() {
                            this.getContainer().style.transition = 'border-color 0.15s ease-in-out';
                        });
                    }
                });

                // Fungsi untuk memeriksa kolom wajib
                function checkRequiredFields() {
                    const fields = [{
                            selector: '#tanggal',
                            message: 'Tanggal harus diisi.'
                        },
                        {
                            selector: '#indeks',
                            message: 'Indeks harus dipilih.'
                        },
                        {
                            selector: '#perihal',
                            message: 'Perihal harus diisi.'
                        },
                        {
                            selector: '#lampiran',
                            message: 'Lampiran harus diisi.'
                        },
                        {
                            selector: '#kepada',
                            message: 'Kepada harus diisi.'
                        },
                        {
                            selector: '#alamat',
                            message: 'Alamat harus diisi.'
                        },
                        {
                            selector: '#isiSurat',
                            message: 'Isi Surat harus diisi.'
                        },
                        {
                            selector: '#penulis',
                            message: 'Penulis harus diisi.'
                        },
                        {
                            selector: '#jabatan',
                            message: 'Jabatan harus diisi.'
                        }
                    ];

                    for (const field of fields) {
                        const element = document.querySelector(field.selector);
                        if (!element || !element.value.trim()) {
                            alert(field.message);
                            element.focus();
                            element.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            return false;
                        }
                    }
                    return true;
                }

                // Validasi sebelum download dan simpan surat
                document.getElementById('downloadButton').addEventListener('click', function(event) {
                    event.preventDefault();
                    if (checkRequiredFields()) {
                        var form = document.getElementById('suratForm');
                        form.action = "{{ route('surat.store') }}"; // Pastikan rute sesuai dengan metode `store`
                        form.target = '_blank';
                        form.submit();

                        setTimeout(function() {
                            window.location.href = "{{ route('suratkeluar.index') }}";
                        }, 2000);
                    }
                });

                // Validasi file signature
                document.getElementById('signature').addEventListener('change', function() {
                    const file = this.files[0];
                    const errorMsg = document.getElementById('error-msg');
                    if (file && file.type !== 'image/png') {
                        errorMsg.style.display = 'block';
                        this.value = '';
                    } else {
                        errorMsg.style.display = 'none';
                    }
                });

                // Konten dari template yang dipilih
                $('#templateSurat').on('change', function() {
                    var selectedContent = $(this).find(':selected').data('content');
                    tinymce.get('isiSurat').setContent(selectedContent || '');
                });

                // Mengatur nomor surat berdasarkan indeks yang dipilih
                $(document).ready(function() {
                    $('#indeks').on('change', function() {
                        var selectedIndeks = $(this).val();
                        if (selectedIndeks) {
                            $.ajax({
                                url: '/get-last-number/' + selectedIndeks,
                                method: 'GET',
                                success: function(response) {
                                    if (response.nextNumber && !$('#noSurat').val()) {
                                        var noSurat = selectedIndeks + '/' + String(response.nextNumber)
                                            .padStart(3, '0');
                                        $('#noSurat').val(noSurat);
                                    }
                                },
                                error: function(xhr) {
                                    alert('Error fetching last number');
                                }
                            });
                        } else {
                            $('#noSurat').val('');
                        }
                    });
                });

                // Simpan ke Draft dengan tombol
                document.getElementById('saveDraftButton').addEventListener('click', function(event) {
                    event.preventDefault();
                    saveDraft();
                    window.location.href = "{{ route('drafts.index') }}";
                });

                // Fungsi untuk menyimpan draft
                function saveDraft() {
                    tinymce.triggerSave();
                    const formData = new FormData(document.getElementById('suratForm'));
                    let draftId = document.querySelector('input[name="draft_id"]').value;

                    fetch("{{ route('draft.save') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(text);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Draft berhasil disimpan.');
                            if (!draftId && data.draft_id) {
                                document.querySelector('input[name="draft_id"]').value = data.draft_id;
                            }

                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }

                // Auto-save setiap 10 detik
                setInterval(saveDraft, 10000);
            </script>
        </div>
</body>

</html>
