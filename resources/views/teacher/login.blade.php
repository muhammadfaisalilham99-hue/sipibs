<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Guru / Staf - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=3">
    <style>
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 10px;
            border: 1px solid #bae6fd;
        }
        .switch-link {
            text-align: center;
            margin-top: 14px;
            font-size: 12px;
            color: #64748b;
        }
        .switch-link a {
            color: #00337c;
            font-weight: 700;
            text-decoration: none;
        }
        .switch-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main class="auth-page">
        <section class="auth-brand">
            @include('user.partials.sipibs-logo')
            <div><span class="role-badge"><i class="bi bi-person-workspace"></i> PORTAL GURU &amp; STAF</span></div>
            <h1 class="auth-title">Masuk Guru / Staf</h1>
            <p class="auth-subtitle">Sistem Inventaris &amp; Peminjaman Barang Sekolah</p>
        </section>
        <section class="auth-card">
            @if ($errors->any())
                <div style="color:#dc2626;margin-bottom:14px;font-size:14px;">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div style="color:#16a34a;margin-bottom:14px;font-size:14px;">{{ session('success') }}</div>
            @endif
            <form action="{{ route('teacher.login.post') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-row">
                    <label class="form-label" for="nip">NIP / NUPTK</label>
                    <div class="field"><i class="bi bi-card-heading"></i><input id="nip" name="identity_number" type="text" value="{{ old('identity_number') }}" placeholder="Masukkan NIP atau NUPTK" autocomplete="username" readonly onfocus="this.removeAttribute('readonly')" required></div>
                </div>
                <div class="form-row">
                    <label class="form-label" for="password"><span>Kata Sandi</span><a class="forgot-link" href="#">Lupa Kata Sandi?</a></label>
                    <div class="field"><i class="bi bi-lock"></i><input id="password" name="password" type="password" placeholder="Masukkan kata sandi" autocomplete="current-password" readonly onfocus="this.removeAttribute('readonly')" required><i class="bi bi-eye eye" onclick="togglePassword(this)"></i></div>
                </div>
                <button class="primary-btn" type="submit" style="background-color:#075985;">Masuk sebagai Guru</button>
            </form>
            <div class="auth-foot">Belum punya akun guru?<a href="{{ url('/register') }}">Daftar di sini</a></div>
            <div class="switch-link">Bukan guru? <a href="{{ url('/login') }}">Login sebagai Siswa</a></div>
        </section>
        <footer class="auth-copyright">© 2026 SIPIBS - Sistem Inventaris &amp; Peminjaman Barang Sekolah</footer>
    </main>
    <script>
        function togglePassword(icon) {
            const input = icon.parentElement.querySelector('input');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('bi-eye', !isHidden);
            icon.classList.toggle('bi-eye-slash', isHidden);
        }
    </script>
</body>
</html>
