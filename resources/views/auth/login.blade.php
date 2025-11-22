<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Aplikasi Berkas</title>

    {{-- Font & CSS AdminLTE --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style>
        /* Style Global */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-color: #000; /* Default Mati (Gelap) */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            transition: background-color 0.5s ease;
        }

        /* Tombol Saklar Lampu */
        .lamp-switch {
            position: absolute;
            top: -20px; /* Tali menggantung dari atas */
            left: 50%;
            transform: translateX(-50%);
            cursor: pointer;
            z-index: 1000;
            text-align: center;
        }

        .lamp-cord {
            width: 2px;
            height: 100px;
            background: #555;
            margin: 0 auto;
            transition: height 0.3s;
        }

        .lamp-bulb {
            width: 40px;
            height: 40px;
            background: #444; /* Warna bohlam mati */
            border-radius: 50%;
            margin: 0 auto;
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
            transition: all 0.3s;
            position: relative;
        }
        
        /* Efek saat ditarik */
        .lamp-switch:active .lamp-cord {
            height: 110px;
        }

        /* Tampilan Login Box */
        .login-box {
            opacity: 0; /* Sembunyi saat mati */
            transform: scale(0.8);
            transition: all 0.8s ease-in-out;
            width: 360px;
            visibility: hidden; /* Agar tidak bisa diklik saat mati */
        }

        /* --- KONDISI SAAT NYALA (CLASS .lights-on) --- */
        
        body.lights-on {
            /* Background dengan efek cahaya terpusat (vignette) */
            background: radial-gradient(circle, #2a2a2a 10%, #000000 90%);
        }

        body.lights-on .lamp-bulb {
            background: #ffeb3b; /* Kuning Terang */
            box-shadow: 0 0 50px #ffeb3b, 0 0 100px #ffeb3b; /* Efek Cahaya Bersinar */
        }

        body.lights-on .login-box {
            opacity: 1;
            transform: scale(1);
            visibility: visible;
        }

        /* Kustomisasi Card Login */
        .card-outline {
            border-top: 3px solid #ffeb3b; /* Aksen Kuning Lampu */
            box-shadow: 0 0 20px rgba(255, 235, 59, 0.2);
        }
        
        .btn-login {
            background-color: #ffeb3b;
            color: #000;
            font-weight: bold;
            border: none;
        }
        .btn-login:hover {
            background-color: #fdd835;
        }
        
        /* Pesan Teks di Gelap */
        .dark-message {
            position: absolute;
            color: #333;
            font-size: 1.2rem;
            top: 50%;
            transform: translateY(100px);
            transition: opacity 0.5s;
        }
        body.lights-on .dark-message {
            opacity: 0;
        }
    </style>
</head>
<body>

    <!-- Saklar Lampu (Klik di sini) -->
    <div class="lamp-switch" onclick="toggleLights()">
        <div class="lamp-cord"></div>
        <div class="lamp-bulb"></div>
    </div>

    <!-- Pesan saat gelap -->
    <div class="dark-message">
        <i class="fas fa-arrow-up"></i> Klik lampu untuk menyalakan
    </div>

    <!-- Form Login (Hanya muncul jika lampu nyala) -->
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Aplikasi</b>BERKAS</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silakan masuk</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Input Email --}}
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Tombol Login --}}
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-block btn-login">Masuk</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>

    <script>
        // Fungsi Saklar Lampu
        function toggleLights() {
            document.body.classList.toggle('lights-on');
            
            // Mainkan suara klik (opsional)
            // var audio = new Audio('click-sound.mp3'); 
            // audio.play();
        }

        // Auto nyala jika ada error validasi (agar user tidak bingung)
        @if ($errors->any())
            document.body.classList.add('lights-on');
        @endif
    </script>
</body>
</html>