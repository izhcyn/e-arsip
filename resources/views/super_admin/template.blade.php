<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Indeks</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/user.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                            <li><a href="/super_admin/indeks" class="active">indeks</a></li>
                            <li><a href="/super_admin/template" class="active">Template Surat</a></li>
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
                    <a>TEMPLATE SURAT</a>
                </div>
                <div class="user_info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                </div>
            </div>

            <div class="container mt-5">
                <div class="d-flex justify-content-between">
                    <h1 class="heading-daftar-pengguna">Daftar Template Surat</h1>
                </div>

                <!-- Form toggle button -->
                <button id="toggleForm" class="btn btn-secondary mt-3">Tambah Template Surat Baru</button>

                <!-- Form untuk tambah template surat yang bisa di-minimize -->
                <div class="card mt-4 user-form" style="display: none;">
                    <div class="card-header">Tambah Template Surat Baru</div>
                    <div class="card-body">
                        <form action="{{ route('template.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="judul_template">Judul Template</label>
                                <input type="text" class="form-control" id="judul_template" name="judul_template" required>
                            </div>

                            <div class="form-group">
                                <label for="isi_surat">Isi Surat</label>
                                <textarea id="isiSurat" name="isi_surat">{{ old('isi_surat') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Template</button>
                        </form>
                    </div>
                </div>

                <!-- Tabel template surat -->
                <div class="card card-table mt-4">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>Judul Template</th>
                                <th>Isi Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($templates as $template)
                                <tr>
                                    <td>{{ $template->judul_template }}</td>
                                    <td>{{ Str::limit(strip_tags($template->isi_surat), 50) }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-info btn-sm" onclick="showDetail('{{ $template->judul_template }}', '{{ $template->isi_surat }}')">Detail</a>
                                        <a href="{{ route('template.edit', $template->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('template.destroy', $template->id) }}" method="POST" id="delete-form-{{ $template->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $template->id }})">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Tombol Detail -->

                <!-- Modal -->
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Detail Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Konten detail surat akan di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
                </div>
                </div>
                </div>


                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    function confirmDelete(templateId) {
                        Swal.fire({
                            title: "Apa kamu yakin?",
                            text: "Data ini tidak dapat dikembalikan",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#28a745",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Ya, hapus ini!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Temukan form dengan ID yang sesuai dan submit
                                document.getElementById("delete-form-" + templateId).submit();
                            }
                        });
                    }

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

                    function showDetail(judul, isiSurat) {
                        // Set the modal title
                        document.getElementById('modalTitle').innerText = judul;

                        // Set the modal body content
                        document.getElementById('modalBody').innerHTML = `<div class="form-group">
                            <label>Isi Surat</label>
                            <div class="border p-3" style="white-space: pre-wrap; background-color: #f8f9fa;">
                                ${isiSurat}
                            </div>
                        </div>`;

                        // Initialize the modal
                        var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

                        // Show the modal
                        detailModal.show();
                    }


                                        // Toggling form visibility
                </script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
        </div>
    </div>
</body>
</html>
