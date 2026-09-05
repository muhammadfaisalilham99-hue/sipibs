<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=1">
</head>
<body>
    <main class="auth-page">
        <section class="auth-brand">
            @include('user.partials.sipibs-logo')
            <h1 class="auth-title">Masuk ke SIPIBS</h1>
            <p class="auth-subtitle">Sistem Inventaris &amp; Peminjaman Barang Sekolah</p>
        </section>
        <section class="auth-card">
            <div class="auth-tabs">
                <button class="auth-tab active">Siswa / Guru</button>
                <a class="auth-tab" href="{{ url('/login-admin') }}" style="display:grid;place-items:center;">Admin</a>
            </div>
            @if ($errors->any())
                <div style="color:#dc2626;margin-bottom:14px;font-size:14px;">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div style="color:#16a34a;margin-bottom:14px;font-size:14px;">{{ session('success') }}</div>
            @endif
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-row">
                    <label class="form-label" for="nama">Nama Lengkap</label>
                    <div class="field"><i class="bi bi-person"></i><input id="nama" type="text" placeholder="Masukkan nama lengkap"></div>
                </div>
                <div class="form-row">
                    <label class="form-label" for="nis">NIS / NIP</label>
                    <div class="field"><i class="bi bi-person-badge"></i><input id="nis" name="identity_number" type="text" value="{{ old('identity_number') }}" placeholder="Masukkan NIS atau NIP" required></div>
                </div>
                <div class="form-row">
                    <label class="form-label" for="password"><span>Kata Sandi</span><a class="forgot-link" href="#">Lupa Kata Sandi?</a></label>
                    <div class="field"><i class="bi bi-lock"></i><input id="password" name="password" type="password" placeholder="Masukkan kata sandi" required><i class="bi bi-eye eye" onclick="togglePassword(this)"></i></div>
                </div>
                <button class="primary-btn" type="submit">Masuk</button>
            </form>
            <div class="auth-foot">Belum punya akun?<a href="{{ url('/register') }}">Daftar Di sini</a></div>
        </section>
        <footer class="auth-copyright">© 2026 SIPIBS - Sistem Inventaris &amp; Peminjaman Barang Sekolah</footer>
    </main>
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
            var form = document.querySelector('form[action*="login"]');
            if (form) {
                form.addEventListener('submit', function () {
                    try {
                        var name = (document.getElementById('nama') || {}).value || '';
                        var nis = (document.getElementById('nis') || {}).value || '';
                        if (name) {
                            var profile = readJson('sipibs_user_profile', {});
                            profile.name = name;
                            if (nis) profile.nis = nis;
                            if (nis) profile.identity_number = nis;
                            localStorage.setItem('sipibs_user_profile', JSON.stringify(profile));
                        }
                    } catch (e) {}
                });
            }
        })();
    </script>
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
