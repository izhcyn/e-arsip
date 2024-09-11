<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="assets/Logo_bulat.png" alt="Radar Bogor Logo" class="login-logo">
            <h1>E-ARSIP</h1>
        </div>
        <div class="login-card">
            <!-- Tampilkan pesan sukses -->
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <!-- Tampilkan pesan error -->
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email or username</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email or username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="show-password" onclick="togglePasswordVisibility()">
                    <label class="form-check-label" for="show-password">Lihat Password</label>
                </div>
                <button type="submit" class="btn login-button">LOGIN</button>
                <a class="back" href="/">
                    <img src="assets/back.png" alt="back" class="img-fluid" style="max-width: 10%; height: auto;">
                </a>
            </form>
        </div>
        <img src="assets/lp1.png" alt="Illustration Left" class="illustration wp-left">
        <img src="assets/lp2.png" alt="Illustration Right" class="illustration wp-right">
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById('password');
            var passwordFieldType = passwordField.getAttribute('type');
            if (passwordFieldType === 'password') {
                passwordField.setAttribute('type', 'text');
            } else {
                passwordField.setAttribute('type', 'password');
            }
        }
    </script>
</body>
</html>
