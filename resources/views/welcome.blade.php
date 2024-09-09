<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-Arsip</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg" style="background-color: rgba(202, 240, 248, 0.2); box-shadow: 0px 4px 2px -2px rgba(128, 128, 128, 0.25);">
            <div class="container d-flex align-items-center justify-content-between">
                <!-- Link untuk logo -->
                <a class="navbar-logo" href="https://radarbogor.jawapos.com/">
                    <img src="assets/logo.png" alt="logo" class="img-fluid" style="max-width: 50%; height: auto;">
                </a>
                <div class="d-flex">
                    <!-- Link untuk About Us -->
                    <a href="https://radarbogor.jawapos.com/about-us" class="btn about-btn me-3">About Us</a>
                    <!-- Penutupan tag <a> yang benar -->
                    <a href="{{ url('/login') }}" class="btn login-btn" type="submit">Login</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="container-fluid main-content">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="sub-title">E-ARSIP</h1>
                <img src="assets/logo.png" alt="logo" class="logo-fluid" style="max-width: 80%; height: auto; padding-bottom: 20px;">
                <p class="description">Digitalisasi dan Efisiensi dalam<br />
                    Pengelolaan Surat Menyurat</p>
                <!-- Menggunakan <a> untuk tautan login -->
                <a href="{{ url('/login') }}" class="btn login-main-btn" type="button">LOGIN</a>
            </div>
            <div class="col-md-6 text-center position-relative illustration-container">
                <img src="assets/wp1.png" alt="Illustration 1" class="illustration wp1">
                <img src="assets/wp2.png" alt="Illustration 2" class="illustration wp2">
                <img src="assets/wp3.png" alt="Illustration 3" class="illustration wp3">
            </div>
        </div>
    </div>

</body>
</html>
