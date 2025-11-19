<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Sarpras JTIK</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 850px;
            max-width: 95%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 40px 60px;
            position: relative;
        }
        .logo {
            position: absolute;
            top: 20px;
            right: 25px;
            width: 70px;
            height: 70px;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .left {
            flex: 1;
            text-align: center;
        }
        .left img {
            width: 200px;
            max-width: 80%;
            height: auto;
        }
        .right {
            flex: 1;
            text-align: center;
        }
        h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #222;
            line-height: 1.5em;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            color: #444;
        }
        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: 0.2s;
        }
        input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        button {
            width: 80%;
            padding: 12px 0;
            background: #007bff;
            border: none;
            border-radius: 25px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .position-relative {
        position: relative;
        }

       /* Pastikan div induk memiliki posisi relatif */
.form-group {
    position: relative;
    /* Tambahan styling lain jika diperlukan */
}

/* Styling untuk container ikon */
.toggle-password {
    position: absolute;
    top: 50%; /* Sesuaikan agar ikon berada di tengah vertikal */
    right: 10px; /* Jarak dari sisi kanan input */
    transform: translateY(-50%); /* Menyesuaikan posisi tengah vertikal yang lebih akurat */
    cursor: pointer;
    z-index: 100; /* Pastikan ikon di atas elemen lain */
}

        @media (max-width: 768px) {
            .login-container { flex-direction: column; padding: 30px; }
            .logo { top: 10px; right: 10px; width: 50px; height: 50px; }
        }
    </style>
    
</head>
<body>

    <div class="login-container">

        <!-- Logo pojok kanan atas -->
        <div class="logo">
            <img src="{{ asset('images/logo_polsub.png') }}" alt="Logo Kampus">
        </div>

        <!-- Gambar kiri -->
        <div class="left">
            <img src="{{ asset('images/logo_login.png') }}" alt="Login Illustration">
        </div>

        <!-- Form kanan -->
        <div class="right">
            <h1>SISTEM INFORMASI <br> SARPRAS JTIK <br> POLITEKNIK NEGERI SUBANG</h1>

            @if(session('error'))
                <p style="color:red;">{{ session('error') }}</p>
            @endif

            <form method="POST" action="{{ route('login.proses') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>

                <div class="form-group position-relative">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control"  required>
                    
                    <!-- ikon mata -->
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
<script src="{{ asset('template') }}/js/scripts.js"></script>
</body>
</html>
