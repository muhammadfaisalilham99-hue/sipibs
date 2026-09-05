<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=1">
</head>
<body>
    <main class="auth-page">
        <section class="auth-brand">
            @include('admin.partials.sipibs-logo')
            <h1 class="auth-title">Masuk ke SIPIBS</h1>
            <p class="auth-subtitle">Sistem Inventaris &amp; Peminjaman Barang Sekolah</p>
        </section>
        <section class="auth-card" style="width:min(100%,410px);">
            <div class="auth-tabs">
                <a class="auth-tab" href="{{ url('/login-siswa') }}" style="display:grid;place-items:center;">Siswa / Guru</a>
                <button class="auth-tab active">Admin</button>
            </div>
            @if ($errors->any())
                <div style="color:#dc2626;margin-bottom:14px;font-size:14px;">{{ $errors->first() }}</div>
            @endif
            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div class="form-row">
                    <label class="form-label" for="admin-email">Email Admin</label>
                    <div class="field"><i class="bi bi-person"></i><input id="admin-email" name="email" type="email" value="{{ old('email') }}" placeholder="Masukkan email admin" required></div>
                </div>
                <div class="form-row">
                    <label class="form-label" for="admin-password">Kata Sandi</label>
                    <div class="field"><i class="bi bi-lock"></i><input id="admin-password" name="password" type="password" placeholder="Masukkan kata sandi" required><i class="bi bi-eye eye" onclick="togglePassword(this)"></i></div>
                </div>
                <button class="primary-btn" type="submit">Masuk</button>
            </form>
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

