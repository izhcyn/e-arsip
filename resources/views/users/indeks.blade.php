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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
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
                $(".user-form").slideToggle();
                $(this).text($(this).text() == 'Minimize Form' ? 'Tambah Indeks Baru' : 'Minimize Form');
            });

            // Handle limit change
            $('#recordsPerPage').change(function() {
                var limit = $(this).val();
                var url = new URL(window.location.href);
                url.searchParams.set('limit', limit);
                window.location.href = url.href;
            });

            // Handle filtering
            $('#filterKodeIndeks, #filterKodeSurat, #filterJudulIndeks, #filterLastNumber').on('keyup change', function() {
                var url = new URL(window.location.href);
                url.searchParams.set('kode_indeks', $('#filterKodeIndeks').val());
                url.searchParams.set('kode_surat', $('#filterKodeSurat').val());
                url.searchParams.set('judul_indeks', $('#filterJudulIndeks').val());
                url.searchParams.set('detail_indeks', $('#filterLastNumber').val());
                window.location.href = url.href;
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
                <li class="active"><a href="{{ route('users.dashboard')}}">
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
                     <li><a href="{{ route('suratmasuk.index')}}" class="active">Surat Masuk</a></li>
                     <li><a href="{{ route('suratkeluar.index')}}" class="active">Surat Keluar</a></li>
                  </ul>
              </li>
              <li><a href="#">
                  <div class="icon"><i class="fa fa-cog" aria-hidden="true"></i></div>
                  <div class="title">PENGATURAN</div>
                  <div class="arrow"><i class="fas fa-chevron-down"></i></div>
                  </a>
                <ul class="accordion">
                     <li><a href="{{ route('indeks.index')}}" class="active">indeks</a></li>
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
               <a href="#">Indeks</a>
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
            <div class="main_container" style="margin-left: 50px">
                <div class="container mt-5">
                    <!-- Form toggle button -->
                    <div class="mt-3">
                        <label for="recordsPerPage">Show records:</label>
                        <select id="recordsPerPage" class="form-control small-select" style="width: auto; display: inline-block;">
                            <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('limit') == 20 ? 'selected' : '' }}>20</option>
                        </select>
                    </div>

                    <!-- Tabel Indeks -->
                    <div class="card card-table mt-4">
                        @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 10%">Kode Indeks <input type="text" id="filterKodeIndeks" class="form-control" value="{{ request('kode_indeks') }}"></th>
                                    <th style="width: 10%">Kode Surat <input type="text" id="filterKodeSurat" class="form-control" value="{{ request('kode_surat') }}"></th>
                                    <th style="width: 20%">Judul Indeks <input type="text" id="filterJudulIndeks" class="form-control" value="{{ request('judul_indeks') }}"></th>
                                    <th style="width: 15%">No.Surat Terakhir <input type="text" id="filterLastNumber" class="form-control" value="{{ request('last_number') }}"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($indeks as $indek)
                                <tr>
                                    <td>{{ $indek->kode_indeks }}</td>
                                    <td>{{ $indek->kode_surat }}</td>
                                    <td>{{ $indek->judul_indeks }}</td>
                                    <td>{{ $indek->last_number }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                {{-- Previous Button --}}
                                <li class="page-item {{ $indeks->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $indeks->appends(request()->input())->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>

                                {{-- Page Numbers --}}
                                @for ($i = 1; $i <= $indeks->lastPage(); $i++)
                                <li class="page-item {{ $i == $indeks->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $indeks->appends(request()->input())->url($i) }}">{{ $i }}</a>
                                </li>
                                @endfor

                                {{-- Next Button --}}
                                <li class="page-item {{ $indeks->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $indeks->appends(request()->input())->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </div>
    </body>
    </html>
