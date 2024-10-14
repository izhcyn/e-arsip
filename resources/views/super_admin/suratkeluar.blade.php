<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Keluar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="/css/surat.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .table th,
        .table td {
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        .table th input {
            width: 90%;
            box-sizing: border-box;
        }

        .table th input,
        .table th select {
            margin: 0;
            padding: 5px;
            font-size: 14px;
        }

        .table th {
            position: relative;
        }

        /* Make sure filters fit with the columns */
        .table th input {
            margin: 0;
            padding: 5px;
            font-size: 14px;
        }

        .table th select {
            width: 100%;
            padding: 5px;
        }

        /* Responsive handling */
        @media (max-width: 768px) {

            .table th,
            .table td {
                display: block;
                width: 100%;
                text-align: left;
            }

            .table th::before,
            .table td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
            }

            .table tbody tr {
                margin-bottom: 10px;
                display: block;
            }

            .table {
                width: 100%;
                display: block;
                overflow-x: auto;
            }
        }
    </style>
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

            $("#toggleForm").click(function() {
                $(".user-form").slideToggle(); // Show/hide the form
                $(this).text($(this).text() == 'Minimize Form' ? 'Tambah User Baru' : 'Minimize Form');
            });
        });

        $(document).ready(function() {
            // Load filter values from URL on page load
            loadFiltersFromURL();

            // Show records per page change event
            $('#recordsPerPage').change(function() {
                updateFiltersInURL();
                location.reload(); // Reload page to apply changes in the backend
            });

            // Text filters change event
            $("#filterNoSurat, #filterIndeksSurat, #filterAsalSurat, #filterPerihal, #filterPenerima").on(
                "keyup change",
                function() {
                    applyFilters();
                    updateFiltersInURL();
                });

            // Date filter logic
            $("#startDate, #endDate").on("change", function() {
                applyDateFilter();
                updateFiltersInURL();
            });


            function applyFilters() {
                var noSurat = $("#filterNoSurat").val().toLowerCase();
                var indeksSurat = $("#filterIndeksSurat").val().toLowerCase();
                var perihal = $("#filterPerihal").val().toLowerCase();
                var penulis = $("#filterPenulis").val().toLowerCase(); // Tambahkan untuk filter penulis
                var penerima = $("#filterPenerima").val().toLowerCase(); // Tambahkan untuk filter penerima

                $("table tbody tr").filter(function() {
                    $(this).toggle(
                        $(this).find('td:eq(0)').text().toLowerCase().indexOf(noSurat) > -1 &&
                        $(this).find('td:eq(1)').text().toLowerCase().indexOf(indeksSurat) > -1 &&
                        $(this).find('td:eq(2)').text().toLowerCase().indexOf(perihal) > -1 &&
                        $(this).find('td:eq(3)').text().toLowerCase().indexOf(penulis) > -1 &&
                        // Pastikan sesuai urutan
                        $(this).find('td:eq(4)').text().toLowerCase().indexOf(penerima) > -1
                    );
                });
            }


            // Apply date filters
            function applyDateFilter() {
                var startDate = $("#startDate").val() ? new Date($("#startDate").val()) : null;
                var endDate = $("#endDate").val() ? new Date($("#endDate").val()) : null;

                $("table tbody tr").each(function() {
                    var rowDateStr = $(this).find('td:eq(5)').text()
                .trim(); // Assuming date is in the 6th column (index 5)
                    var rowDate = new Date(rowDateStr);

                    // Check if the row date is a valid date
                    if (rowDateStr && !isNaN(rowDate.getTime())) {
                        var visible = true;

                        // Compare with start date if it's set
                        if (startDate && rowDate < startDate) {
                            visible = false;
                        }

                        // Compare with end date if it's set
                        if (endDate && rowDate > endDate) {
                            visible = false;
                        }

                        // Toggle row visibility
                        $(this).toggle(visible);
                    } else {
                        // If rowDate is not valid, hide the row
                        $(this).toggle(false);
                    }
                });
            }

            // Update URL with filter values
            function updateFiltersInURL() {
                var url = new URL(window.location.href);
                url.searchParams.set('noSurat', $("#filterNoSurat").val());
                url.searchParams.set('indeksSurat', $("#filterIndeksSurat").val());
                url.searchParams.set('perihal', $("#filterPerihal").val());
                url.searchParams.set('penulis', $("#filterPenulis").val());
                url.searchParams.set('penerima', $("#filterPenerima").val());
                url.searchParams.set('startDate', $("#startDate").val());
                url.searchParams.set('endDate', $("#endDate").val());
                url.searchParams.set('limit', $('#recordsPerPage').val());

                // Update the page URL without reloading
                window.history.replaceState(null, null, url.href);
            }

            // Load filter values from the URL
            function loadFiltersFromURL() {
                var urlParams = new URLSearchParams(window.location.search);
                $("#filterNoSurat").val(urlParams.get('noSurat') || '');
                $("#filterIndeksSurat").val(urlParams.get('indeksSurat') || '');
                $("#filterAsalSurat").val(urlParams.get('asalSurat') || '');
                $("#filterPerihal").val(urlParams.get('perihal') || '');
                $("#filterPenerima").val(urlParams.get('penerima') || '');
                $("#startDate").val(urlParams.get('startDate') || '');
                $("#endDate").val(urlParams.get('endDate') || '');
                $('#recordsPerPage').val(urlParams.get('limit') || '5');

                // Apply filters after setting the values
                applyFilters();
                applyDateFilter();
            }
        });

        function confirmDelete(suratmasukId) {
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
                    document.getElementById("delete-form-" + suratmasukId).submit();
                }
            });
        }

        // Function to remove chart
        function removeChart(chartId) {
            document.getElementById(chartId).parentElement.parentElement.style.display = 'none';
        }

        // Function to minimize chart
        function toggleChart(chartId) {
            const chartContainer = document.getElementById(chartId).parentElement.parentElement;
            const canvas = chartContainer.querySelector('canvas');
            if (canvas.style.display === 'none') {
                canvas.style.display = 'block';
            } else {
                canvas.style.display = 'none';
            }
        }

        // Ensure charts are drawn after the page fully loads
        $(document).ready(function() {
            // Data for Outgoing Letters Per Month
            const ctxOutgoing = document.getElementById('chartOutgoing').getContext('2d');

            // Dynamically get the labels (months) and data (total surat) from PHP
            const totalSuratPerBulan = @json($totalSuratPerBulan);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const totalSuratData = new Array(12).fill(0); // Array for 12 months initialized with 0

            // Populate totalSuratData with actual values
            Object.keys(totalSuratPerBulan).forEach(month => {
                totalSuratData[month - 1] = totalSuratPerBulan[month]; // Fill data at appropriate index
            });

            const chartOutgoing = new Chart(ctxOutgoing, {
                type: 'bar',
                data: {
                    labels: months, // Display months as x-axis labels
                    datasets: [{
                        label: 'Total Surat Keluar',
                        data: totalSuratData, // Use the dynamically created array
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Surat Keluar'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan'
                            }
                        }
                    }
                }
            });

            // Data for Index Usage in Outgoing Letters
            const ctxIndex = document.getElementById('chartIndex').getContext('2d');

            // Dynamically get the indeks labels and data from PHP
            const indeksUsage = @json($indeksUsage);
            const indeksLabels = Object.keys(indeksUsage);
            const indeksData = Object.values(indeksUsage);

            const chartIndex = new Chart(ctxIndex, {
                type: 'bar',
                data: {
                    labels: indeksLabels, // Display indeks codes as x-axis labels
                    datasets: [{
                        label: 'Jumlah Indeks Dipakai',
                        data: indeksData, // Use the dynamically created array
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Indeks'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Indeks'
                            }
                        }
                    }
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
                    <a href="#">Surat Keluar</a>
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


            <div class="container mt-5">
                <!-- Toggle Form Button -->
                <button id="toggleForm" class="btn btn-secondary mt-3">Tambah Surat Keluar</button>
                <!-- Form for Adding Surat Keluar -->
                <div class="card mt-4 user-form" style="display: none;">
                    <div class="card-header">Tambah Surat Keluar</div>
                    <div class="card-body">
                        <form action="{{ route('suratkeluar.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <option value="{{ $indek->kode_indeks }}">{{ $indek->kode_indeks }} -
                                            {{ $indek->judul_indeks }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="perihal">Perihal</label>
                                <input type="text" class="form-control" id="perihal" name="perihal" required>
                            </div>
                            <div class="form-group">
                                <label for="penulis">Penulis</label>
                                <input type="text" class="form-control" id="penulis" name="penulis" required>
                            </div>
                            <div class="form-group">
                                <label for="penerima">Penerima</label>
                                <input type="text" class="form-control" id="penerima" name="penerima" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_keluar">Tanggal Keluar</label>
                                <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar"
                                    required>
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

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="container mt-5">
                <label for="recordsPerPage">Show records:</label>
                <select id="recordsPerPage" class="form-control small-select"
                    style="width: auto; display: inline-block;">
                    <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('limit') == 20 ? 'selected' : '' }}>20</option>
                </select>
            </div>

            <div class="suratmasuk-section">
                <div class="suratmasuk-card">
                    <h3>Tabel Surat Keluar</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 8%">No. Surat</th>
                                <th style="width: 8%">Indeks Surat</th>
                                <th style="width: 10%">Perihal</th>
                                <th style="width: 15%">Penulis</th>
                                <th style="width: 15%">Penerima</th>
                                <th style="width: 15%">Tanggal Keluar</th>
                                <th style="width: 10%">Dokumen</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                            <tr>
                                <th><input type="text" id="filterNoSurat" class="form-control"></th>
                                <th><input type="text" id="filterIndeksSurat" class="form-control"></th>
                                <th><input type="text" id="filterPerihal" class="form-control"></th>
                                <th><input type="text" id="filterPenulis" class="form-control"></th>
                                <!-- Pastikan ID sesuai -->
                                <th><input type="text" id="filterPenerima" class="form-control"></th>
                                <!-- Pastikan ID sesuai -->
                                <th>
                                    <input type="date" id="startDate" class="form-control d-inline-block"
                                        style="width: 45%;">
                                    <input type="date" id="endDate" class="form-control d-inline-block"
                                        style="width: 45%;">
                                </th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($suratKeluar->isEmpty())
                                <tr>
                                    <td colspan="8">Tidak ada surat keluar.</td>
                                </tr>
                            @else
                                @foreach ($suratKeluar as $item)
                                    <tr>
                                        <td>{{ $item->no_surat }}</td>
                                        <td>{{ $item->kode_indeks }}</td>
                                        <td>{{ $item->perihal }}</td>
                                        <td>{{ strip_tags($item->penulis) }}</td>
                                        <td>{{ strip_tags($item->penerima) }}</td>
                                        <td>{{ $item->tanggal_keluar }}</td>
                                        <td><a href="{{ asset('storage/' . $item->dokumen) }}"
                                                target="_blank">{{ basename($item->dokumen) }}</a></td>
                                        <td>
                                            @if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
                                                <a href="{{ route('suratkeluar.edit', $item->suratkeluar_id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit"><i
                                                        class="fas fa-edit"></i></a>
                                            @endif
                                            @if (auth()->user()->role == 'super_admin')
                                                <form
                                                    action="{{ route('suratkeluar.destroy', $item->suratkeluar_id) }}"
                                                    method="POST" id="delete-form-{{ $item->suratkeluar_id }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete({{ $item->suratkeluar_id }})"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></button>
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


            <!-- Links Pagination -->
            <div class="mt-3">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        {{-- Previous Button --}}
                        <li class="page-item {{ $suratKeluar->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $suratKeluar->previousPageUrl() }}&limit={{ $suratKeluar->perPage() }}"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $suratKeluar->lastPage(); $i++)
                            <li class="page-item {{ $i == $suratKeluar->currentPage() ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ $suratKeluar->url($i) }}&limit={{ $suratKeluar->perPage() }}">{{ $i }}</a>
                            </li>
                        @endfor
                        {{-- Next Button --}}
                        <li class="page-item {{ $suratKeluar->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link"
                                href="{{ $suratKeluar->nextPageUrl() }}&limit={{ $suratKeluar->perPage() }}"
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Total Surat Keluar
                                <span class="minimize-btn" onclick="toggleChart('chartOutgoing')">
                                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                </span>
                                <span class="close-btn" onclick="removeChart('chartOutgoing')">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="card-body">
                                <canvas id="chartOutgoing" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Total Indeks Dipakai
                                <span class="minimize-btn" onclick="toggleChart('chartIndex')">
                                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                </span>
                                <span class="close-btn" onclick="removeChart('chartIndex')">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="card-body">
                                <canvas id="chartIndex" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </div>
</body>

</html>
