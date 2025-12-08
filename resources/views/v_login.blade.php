<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Sarpras JTIK</title>
    <!-- Tambahkan link font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Link Font Awesome untuk ikon mata -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #007bff;
            --primary-dark: #0056b3;
            --bg-light: #f4f7f9;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            --card-radius: 20px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .login-container {
            background: #fff;
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            width: 900px;
            max-width: 95%;
            display: flex;
            align-items: stretch;
            position: relative;
            min-height: 500px;
        }
        .logo {
            position: absolute;
            top: 25px;
            right: 35px;
            width: 80px;
            height: 80px;
            z-index: 10;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* LEFT COLUMN (VISUAL & BRANDING) */
        .left {
            flex: 1;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: var(--card-radius) 0 0 var(--card-radius);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
            text-align: center;
        }
        .left h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .left p {
            font-size: 14px;
            margin-bottom: 40px;
            opacity: 0.8;
        }
        .left img {
            width: 250px;
            max-width: 90%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        /* RIGHT COLUMN (FORM) */
        .right {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary-dark);
            line-height: 1.3;
            text-align: center;
        }
        .subtitle {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 40px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 25px;
            text-align: left;
            width: 100%;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
        }
        
        /* WRAPPER BARU UNTUK INPUT DAN IKON */
        .input-wrapper {
            position: relative; /* Ini adalah kunci untuk positioning ikon */
        }

        input {
            width: 100%;
            /* Padding input disesuaikan agar ikon mata muat di kanan */
            padding: 12px 40px 12px 15px; 
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }
        input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }
        
        /* IKON TOGGLE PASSWORD */
        .toggle-password {
            position: absolute;
            /* Posisi ikon di dalam input-wrapper */
            top: 50%; 
            right: 15px;
            /* Menjamin posisi tengah vertikal yang akurat */
            transform: translateY(-50%); 
            cursor: pointer;
            color: #aaa;
            padding: 5px;
            font-size: 16px;
            z-index: 10;
        }
        /* TOMBOL SUBMIT */
        button {
            width: 100%;
            padding: 14px 0;
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 17px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s, transform 0.1s;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }
        button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }


        /* MEDIA QUERIES (RESPONSIVE) */
        @media (max-width: 992px) {
            .login-container { 
                width: 500px;
                flex-direction: column;
                min-height: 100%;
                padding: 0;
            }
            .left { 
                display: none;
            }
            .right { 
                flex: none;
                padding: 40px 30px;
            }
            h1 {
                font-size: 24px;
            }
            .logo {
                top: 15px;
                right: 15px;
                width: 60px;
                height: 60px;
            }
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
            <h2>SARPRAS JTIK</h2>
            <p>Sistem Informasi Sarana Prasarana Jaringan Teknologi Informasi dan Komputer</p>
            <img src="{{ asset('images/logo2.jpg') }}" alt="Ilustrasi Login">
        </div>

        <!-- Form kanan -->
        <div class="right">
            <h1>Login ke Sistem</h1>
            <p class="subtitle">Masuk menggunakan akun Mahasiswa/Dosen/Teknisi/Admin Anda.</p>

            @if(session('error'))
                <p style="color:var(--primary-dark); background:#ffe6e6; padding:10px; border-radius:8px; text-align:center;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </p>
            @endif

            <form method="POST" action="{{ route('login.proses') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <!-- WRAPPER BARU -->
                    <div class="input-wrapper"> 
                        <input type="password" id="password" name="password" required>
                        
                        <!-- ikon mata -->
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>
                <button type="submit">LOGIN</button>
            </form>
        </div>
    </div>

    <!-- SCRIPT UNTUK TOGGLE PASSWORD -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
    <script src="{{ asset('template') }}/js/scripts.js"></script>
</body>
</html>