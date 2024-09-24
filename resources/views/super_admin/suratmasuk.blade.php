<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Masuk</title>
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
                    <a href="#">Surat Masuk</a>
                </div>
                <div class="user_info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}<br />{{ Auth::user()->role }}</span>
                </div>
            </div>

            <div class="container mt-5">
                <!-- Form toggle button -->
                <button id="toggleForm" class="btn btn-secondary mt-3">Tambah Surat Masuk</button>

                <!-- Form untuk tambah user yang bisa di-minimize -->
                <div class="card mt-4 user-form" style="display: none;">
                    <div class="card-header">Tambah Surat Masuk</div>
                    <div class="card-body">
                        <form action="{{ route('suratmasuk.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="no_surat">No Surat</label>
                                <input type="text" class="form-control" id="no_surat" name="no_surat" required>
                            </div>
                            <div class="form-group">
                                <label for="kode_indeks">Kode Indeks</label>
                                <select class="form-control" id="kode_indeks" name="kode_indeks" required>
                                    <option value="" disabled selected>Pilih Kode Indeks</option>
                                    @foreach ($indeks as $indek)
                                        <option value="{{ $indek->kode_indeks }}">{{ $indek->kode_indeks }} - {{ $indek->judul_indeks }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="asal_surat">Asal Surat</label>
                                <input type="text" class="form-control" id="asal_surat" name="asal_surat" required>
                            </div>
                            <div class="form-group">
                                <label for="perihal">Perihal</label>
                                <input type="text" class="form-control" id="perihal" name="perihal" required>
                            </div>
                            <div class="form-group">
                                <label for="penerima">Penerima</label>
                                <input type="text" class="form-control" id="penerima" name="penerima" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_diterima">Tanggal Diterima</label>
                                <input type="date" class="form-control" id="tanggal_diterima" name="tanggal_diterima" required>
                            </div>
                            <div class="form-group">
                                <label for="dokumen">Unggah Dokumen</label>
                                <input type="file" class="form-control" id="dokumen" name="dokumen" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="container mt-5">
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif
                <!-- Tabel surat masuk -->
                <div class="suratmasuk-section">
                    <div class="suratmasuk-card">
                        <h3>Surat Masuk</h3>
                        <table class="table table-striped">
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
                                @if($suratMasuk->isEmpty())
                                    <tr>
                                        <td colspan="8">Tidak ada surat masuk.</td>
                                    </tr>
                                @else
                                    @foreach($suratMasuk as $item)
                                        <tr>
                                            <td>{{ $item->no_surat }}</td>
                                            <td>{{ $item->kode_indeks }}</td>
                                            <td>{{ $item->asal_surat }}</td>
                                            <td>{{ $item->perihal }}</td>
                                            <td>{{ $item->penerima }}</td>
                                            <td>{{ $item->tanggal_diterima }}</td>
                                            <td>
                                                @php
                                                    $filePath = asset('storage/' . $item->dokumen); // Path untuk file PDF
                                                    $fileName = basename($filePath); // Menampilkan nama file
                                                @endphp

                                                <!-- Tampilkan link untuk download dan preview -->
                                                <a href="{{ $filePath }}" target="_blank">{{ $fileName }}</a>

                                            </td>
                                            <td>
                                                @if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
                                                <a href="{{ route('suratmasuk.edit', $item->suratmasuk_id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                @if(auth()->user()->role == 'super_admin')
                                                <form action="{{ route('suratmasuk.destroy', $item->suratmasuk_id) }}" method="POST" id="delete-form-{{ $item->suratmasuk_id }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->suratmasuk_id }})"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>

                        </table>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                              <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                  <span aria-hidden="true">&laquo;</span>
                                  <span class="sr-only">Previous</span>
                                </a>
                              </li>
                              <li class="page-item"><a class="page-link" href="#">1</a></li>
                              <li class="page-item"><a class="page-link" href="#">2</a></li>
                              <li class="page-item"><a class="page-link" href="#">3</a></li>
                              <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                  <span aria-hidden="true">&raquo;</span>
                                  <span class="sr-only">Next</span>
                                </a>
                              </li>
                            </ul>
                          </nav>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        function confirmDelete(suratmasukId) {
            Swal.fire({
                title: "Apa kamu yakin?",
                text: "Data ini tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "##28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus ini!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Temukan form dengan ID yang sesuai dan submit
                    document.getElementById("delete-form-" + suratmasukId).submit();
                }
            });
        }

        function showDocument(filePath, extension) {
        let previewHTML = '';

        if (['jpg', 'jpeg', 'png', 'gif'].includes(extension.toLowerCase())) {
            // Preview gambar
            previewHTML = `<img src="${filePath}" alt="Preview Gambar" style="max-width: 100%; height: auto;">`;
        } else if (extension.toLowerCase() === 'pdf') {
            // Preview PDF
            previewHTML = `<iframe src="${filePath}" width="100%" height="600px"></iframe>`;
        } else if (['doc', 'docx', 'xls', 'xlsx'].includes(extension.toLowerCase())) {
            // Preview Word/Excel dengan Google Docs Viewer
            previewHTML = `<iframe src="https://docs.google.com/gview?url=${filePath}&embedded=true" width="100%" height="600px"></iframe>`;
        } else {
            previewHTML = `<p>Dokumen tidak dapat ditampilkan. <a href="${filePath}" target="_blank">Download file</a></p>`;
        }

        // Masukkan konten preview ke dalam modal
        document.getElementById('documentPreview').innerHTML = previewHTML;

        // Tampilkan modal
        $('#documentModal').modal('show');
        }
        </script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </div>
    </div>
</body>
</html>
