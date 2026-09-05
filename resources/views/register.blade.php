<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun SIPIBS - Sistem Peminjaman & Inventaris Barang Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #eaf2fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            color: #1e293b;
        }

        .card-container {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(2, 43, 105, 0.08);
            position: relative;
            overflow: hidden;
        }

        .card-inner {
            background: #ffffff;
            border-radius: 16px;
        }

        .card-header-section {
            padding: 32px 24px 12px 24px;
            text-align: center;
        }

        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .logo-wrapper img {
            width: 80px;
            height: auto;
        }

        .title {
            font-size: 21px;
            font-weight: 800;
            color: #00337c;
            margin-bottom: 6px;
            letter-spacing: -0.2px;
        }

        .subtitle {
            font-size: 12px;
            font-weight: 500;
            color: #5a6b82;
            line-height: 1.4;
        }

        .card-body-section {
            padding: 20px 28px 28px 28px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #64748b;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .input-toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            font-size: 16px;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            transition: color 0.2s ease;
        }

        .input-toggle:hover {
            color: #00337c;
        }

        .form-input {
            width: 100%;
            height: 42px;
            padding: 8px 40px 8px 42px;
            background-color: #f1f6fb;
            border: 1px solid #d0dfed;
            border-radius: 8px;
            font-size: 13.5px;
            color: #1e293b;
            font-weight: 500;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #8a99ad;
            font-weight: 400;
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: #00337c;
            box-shadow: 0 0 0 3px rgba(0, 51, 124, 0.1);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 16px;
            margin-bottom: 22px;
            cursor: pointer;
        }

        .checkbox-container input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid #cbd5e1;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            margin-top: 2px;
            background-color: #ffffff;
            display: grid;
            place-content: center;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        .checkbox-container input[type="checkbox"]:checked {
            background-color: #00337c;
            border-color: #00337c;
        }

        .checkbox-container input[type="checkbox"]:checked::before {
            content: "\F26E";
            font-family: "bootstrap-icons";
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
        }

        .checkbox-label {
            font-size: 11.5px;
            line-height: 1.45;
            color: #475569;
            font-weight: 500;
            user-select: none;
        }

        .checkbox-label a {
            color: #00337c;
            font-weight: 700;
            text-decoration: none;
        }

        .checkbox-label a:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            height: 44px;
            background-color: #00337c;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.2s ease, transform 0.1s ease;
            box-shadow: 0 4px 10px rgba(0, 51, 124, 0.2);
        }

        .btn-submit:hover {
            background-color: #002257;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .btn-submit i {
            font-size: 15px;
        }

        .footer-divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 20px 0 16px 0;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .btn-login-wrapper {
            text-align: center;
        }

        .btn-login {
            display: inline-block;
            padding: 6px 36px;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            color: #00337c;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            background-color: #ffffff;
        }

        .btn-login:hover {
            border-color: #00337c;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

    <div class="card-container">
        <div class="card-inner">
            <!-- Header Section -->
            <div class="card-header-section">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/sipibs-logo-baru.png') }}" alt="SIPIBS Logo">
                </div>
                <h1 class="title">Daftar Akun SIPIBS</h1>
                <p class="subtitle">Sistem Peminjaman & Inventaris Barang Sekolah</p>
            </div>

            <!-- Form Section -->
            <div class="card-body-section">
                @if ($errors->any())
                    <div style="color:#dc2626;margin-bottom:14px;font-size:14px;">{{ $errors->first() }}</div>
                @endif
                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" placeholder="Nama Lengkap" required>
                        </div>
                    </div>

                    <!-- NIS / NIP -->
                    <div class="form-group">
                        <label class="form-label" for="identity_number">NIS / NIP</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-card-heading"></i>
                            </span>
                            <input type="text" id="identity_number" name="identity_number" class="form-input" value="{{ old('identity_number') }}" placeholder="Nomor Induk Siswa/Pegawai" required>
                        </div>
                    </div>

                    <!-- Alamat Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">Alamat Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="Email@gmail.com" required>
                        </div>
                    </div>

                    <!-- Kata Sandi & Konfirmasi -->
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="password">Kata Sandi</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" id="password" name="password" class="form-input" placeholder="********" required>
                                <button type="button" class="input-toggle" onclick="togglePassword('password', this)" aria-label="Tampilkan kata sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Konfirmasi</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </span>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="********" required>
                                <button type="button" class="input-toggle" onclick="togglePassword('password_confirmation', this)" aria-label="Tampilkan konfirmasi kata sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms Checkbox -->
                    <label class="checkbox-container">
                        <input type="checkbox" name="terms" required>
                        <span class="checkbox-label">
                            Saya menyetujui <a href="#">Syarat & Ketentuan</a> penggunaan sistem inventaris sekolah.
                        </span>
                    </label>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        Register <i class="bi bi-arrow-right"></i>
                    </button>

                    <div class="footer-divider"></div>

                    <!-- Already have account -->
                    <div class="footer-text">Sudah memiliki akun?</div>
                    <div class="btn-login-wrapper">
                        <a href="{{ url('/login-siswa') }}" class="btn-login">Masuk</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function readJson(key, fallback) {
                try {
                    var v = JSON.parse(localStorage.getItem(key) || 'null');
                    return v || fallback;
                } catch (e) {
                    return fallback;
                }
            }
            var form = document.querySelector('form[action*="register"]');
            if (form) {
                form.addEventListener('submit', function () {
                    try {
                        var name = (document.getElementById('name') || {}).value || '';
                        var nis = (document.getElementById('identity_number') || {}).value || '';
                        var email = (document.getElementById('email') || {}).value || '';
                        if (name) {
                            var profile = readJson('sipibs_user_profile', {});
                            profile.name = name;
                            if (nis) profile.nis = nis;
                            if (email) profile.email = email;
                            localStorage.setItem('sipibs_user_profile', JSON.stringify(profile));
                        }
                    } catch (e) {}
                });
            }
        })();
    </script>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
                btn.setAttribute('aria-label', 'Sembunyikan kata sandi');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
                btn.setAttribute('aria-label', 'Tampilkan kata sandi');
            }
        }
    </script>
</body>
</html>
