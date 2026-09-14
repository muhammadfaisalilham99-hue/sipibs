<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun SIPIBS - Sistem Peminjaman &amp; Inventaris Barang Sekolah</title>
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
            max-width: 440px;
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
            padding: 16px 28px 28px 28px;
        }

        .role-switch-container {
            margin-bottom: 18px;
        }

        .role-switch-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 8px;
        }

        .role-switch {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background-color: #f1f6fb;
            border: 1px solid #d0dfed;
            border-radius: 10px;
            padding: 4px;
            gap: 4px;
        }

        .role-btn {
            padding: 8px 12px;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            border-radius: 7px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .role-btn.active {
            background-color: #00337c;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 51, 124, 0.25);
        }

        .form-group {
            margin-bottom: 14px;
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

        select.form-input {
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 12px;
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
            margin-top: 14px;
            margin-bottom: 20px;
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

        .teacher-only {
            display: none;
        }

        body.mode-guru .student-only {
            display: none !important;
        }

        body.mode-guru .teacher-only {
            display: block !important;
        }
    </style>
</head>
<body class="{{ old('role', 'siswa') === 'guru' ? 'mode-guru' : '' }}">

    <div class="card-container">
        <div class="card-inner">
            <!-- Header Section -->
            <div class="card-header-section">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/sipibs-logo-baru.png') }}" alt="SIPIBS Logo">
                </div>
                <h1 class="title">Daftar Akun SIPIBS</h1>
                <p class="subtitle">Sistem Peminjaman &amp; Inventaris Barang Sekolah</p>
            </div>

            <!-- Form Section -->
            <div class="card-body-section">
                @if ($errors->any())
                    <div style="color:#dc2626;margin-bottom:14px;font-size:14px;">{{ $errors->first() }}</div>
                @endif
                <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Masuk Sebagai -->
                    <div class="role-switch-container">
                        <label class="role-switch-label">Masuk Sebagai</label>
                        <input type="hidden" name="role" id="role_input" value="{{ old('role', 'siswa') }}">
                        <div class="role-switch">
                            <button type="button" class="role-btn {{ old('role', 'siswa') === 'siswa' ? 'active' : '' }}" id="btn_role_siswa" onclick="setRole('siswa')">
                                <i class="bi bi-mortarboard-fill"></i> Siswa
                            </button>
                            <button type="button" class="role-btn {{ old('role', 'siswa') === 'guru' ? 'active' : '' }}" id="btn_role_guru" onclick="setRole('guru')">
                                <i class="bi bi-person-workspace"></i> Guru / Staff
                            </button>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label class="form-label" for="name" id="label_name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" placeholder="Nama Lengkap" required>
                        </div>
                    </div>

                    <!-- NIS / NIP -->
                    <div class="form-group">
                        <label class="form-label" for="identity_number" id="label_identity">NIS / NIP</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-card-heading"></i>
                            </span>
                            <input type="text" id="identity_number" name="identity_number" class="form-input" value="{{ old('identity_number') }}" placeholder="Nomor Induk Siswa/Pegawai" required>
                        </div>
                    </div>

                                                            <!-- Upload KTP / Kartu Pelajar -->
                    <div class="form-group">
                        <label class="form-label" for="identity_document">Upload KTP / Kartu Pelajar</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-file-earmark-person"></i>
                            </span>
                            <input type="file" id="identity_document" name="identity_document" class="form-input" accept="image/jpeg,image/png,application/pdf" required>
                        </div>
                        <small style="display:block;margin-top:5px;color:#64748b;font-size:11px;">JPG, PNG, atau PDF. Maksimal 5 MB.</small>
                    </div>
<!-- Kelas / Ruangan (Khusus Siswa) -->
                    <div class="form-group student-only">
                        <label class="form-label" for="class_name">Kelas / Ruangan</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-mortarboard"></i>
                            </span>
                            <input type="text" id="class_name" name="class_name" class="form-input" value="{{ old('class_name') }}" placeholder="Masukkan Kelas / Ruangan">
                        </div>
                    </div>

                    <!-- Mata Pelajaran (Khusus Guru) -->
                    <div class="form-group teacher-only">
                        <label class="form-label" for="subject">Mata Pelajaran / Bidang</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-book"></i>
                            </span>
                            <input type="text" id="subject" name="subject" class="form-input" value="{{ old('subject') }}" placeholder="Contoh: Pemrograman Web / RPL">
                        </div>
                    </div>

                    <!-- Ruang Kerja / Lab (Khusus Guru) -->
                    <div class="form-group teacher-only">
                        <label class="form-label" for="room_name">Ruang Kerja / Lab</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-building"></i>
                            </span>
                            <input type="text" id="room_name" name="room_name" class="form-input" value="{{ old('room_name') }}" placeholder="Contoh: Lab Komputer 1 / Ruang Guru">
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="form-group">
                        <label class="form-label" for="birth_date">Tanggal Lahir</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-calendar3"></i>
                            </span>
                            <input type="date" id="birth_date" name="birth_date" class="form-input" value="{{ old('birth_date') }}">
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="form-group">
                        <label class="form-label" for="gender">Jenis Kelamin</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-gender-ambiguous"></i>
                            </span>
                            <select id="gender" name="gender" class="form-input">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">Alamat Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="Email@gmail.com" required>
                        </div>
                    </div>

                    <!-- Password Fields -->
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="password">Kata Sandi</label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" id="password" name="password" class="form-input" placeholder="********" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" required>
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
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="********" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" required>
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
                            Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> penggunaan sistem inventaris sekolah.
                        </span>
                    </label>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        Daftar Akun <i class="bi bi-arrow-right"></i>
                    </button>

                    <div class="footer-divider"></div>

                    <!-- Already have account -->
                    <div class="footer-text">Sudah memiliki akun?</div>
                    <div class="btn-login-wrapper">
                        <a href="{{ url('/login') }}" class="btn-login">Masuk</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setRole(role) {
            document.getElementById('role_input').value = role;
            const btnSiswa = document.getElementById('btn_role_siswa');
            const btnGuru = document.getElementById('btn_role_guru');
            const labelIdentity = document.getElementById('label_identity');
            const inputIdentity = document.getElementById('identity_number');
            const labelName = document.getElementById('label_name');
            const inputName = document.getElementById('name');

            if (role === 'guru') {
                document.body.classList.add('mode-guru');
                btnGuru.classList.add('active');
                btnSiswa.classList.remove('active');
                labelIdentity.textContent = 'NIP / NUPTK';
                inputIdentity.placeholder = 'Nomor Induk Pegawai';
                labelName.textContent = 'Nama Lengkap & Gelar';
                inputName.placeholder = 'Nama Lengkap beserta Gelar';
            } else {
                document.body.classList.remove('mode-guru');
                btnSiswa.classList.add('active');
                btnGuru.classList.remove('active');
                labelIdentity.textContent = 'NIS / NIP';
                inputIdentity.placeholder = 'Nomor Induk Siswa/Pegawai';
                labelName.textContent = 'Nama Lengkap';
                inputName.placeholder = 'Nama Lengkap';
            }
        }

        // Initialize state on page load
        (function() {
            var currentRole = document.getElementById('role_input').value || 'siswa';
            setRole(currentRole);
        })();

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


