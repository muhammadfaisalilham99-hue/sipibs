<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=25">
</head>
<body class="{{ ($showLogout ?? false) ? 'user-logout-page' : '' }}">
@php
    $userName = Auth::check() ? Auth::user()->name : 'Rizky Pratama';
    $userEmail = Auth::check() ? Auth::user()->email : 'rizky.pratama@email.com';
@endphp
<div class="app-shell">
    <aside class="sidebar user-sidebar">
        <div class="sidebar-logo">
            @include('user.partials.sipibs-logo')
            <div class="sidebar-title">SIPIBS</div>
            <div class="sidebar-subtitle">INVENTORY</div>
        </div>
        <nav class="nav-list">
            <a class="nav-item" href="{{ url('/dashboard-user') }}"><i class="bi bi-grid"></i> Dashboard</a>
            <a class="nav-item" href="#" id="inventaris-toggle"><i class="bi bi-box-seam"></i> Inventaris <span class="nav-arrow">^</span></a>
            <div class="nav-sub-list collapsed" id="inventaris-sub">
                <a class="nav-sub-item" href="{{ url('/katalog-alat') }}">Katalog Barang</a>
                <a class="nav-sub-item" href="{{ url('/kondisi-barang') }}">Kondisi Barang</a>
            </div>
            <a class="nav-item" href="{{ url('/peminjaman-user') }}"><i class="bi bi-gem"></i> Peminjaman</a>
            <a class="nav-item" href="{{ url('/pengembalian-user') }}"><i class="bi bi-calendar-check"></i> Pengembalian</a>
            <a class="nav-item" href="{{ url('/denda-user') }}"><i class="bi bi-cash-coin"></i> Denda</a>
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item active" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-area">
        <header class="topbar">
            <div class="topbar-left"></div>
            <div class="top-actions">
                @include('user.partials.notification-bell')

                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar">
                </div>
            </div>
        </header>

        <section class="content user-profile-content">
            <div class="profile-page-title">
                <h1>Profil Saya</h1>
                <div class="profile-breadcrumb"><i class="bi bi-house-door-fill"></i> Beranda <span>/</span> <strong>Profil Saya</strong></div>
            </div>

            <div class="profile-hero-card">
                <div class="profile-photo-wrap">
                    <img id="profile-photo" src="{{ asset('images/PROFIL.png') }}" alt="Foto Profil">
                    <label class="profile-camera-btn" for="profile-photo-input"><i class="bi bi-camera-fill"></i></label>
                    <input id="profile-photo-input" type="file" accept="image/*" hidden>
                </div>
                <div class="profile-hero-info">
                    <div class="profile-name-row">
                        <h2 id="hero-name">{{ $userName }}</h2>
                        <span>Siswa</span>
                    </div>
                    <div class="profile-contact-grid">
                        <div><i class="bi bi-envelope"></i> <span id="hero-email">{{ $userEmail }}</span></div>
                        <div><i class="bi bi-calendar2"></i> Bergabung sejak 15 Januari 2024</div>
                    </div>
                </div>
                <div class="profile-quote-card">"Gunakan fasilitas sekolah dengan bijak<br>dan bertanggung jawab."</div>
            </div>

            <div class="profile-layout-grid">
                <div>
                    <div class="profile-tabs-user">
                        <button class="active" type="button" data-tab="personal">Informasi Pribadi</button>
                        <button type="button" data-tab="security">Keamanan Akun</button>
                        <button type="button" data-tab="notifications">Pengaturan Notifikasi</button>
                    </div>

                    <form class="profile-form-card" id="profile-form">
                        <div class="profile-tab-panel active" id="tab-personal">
                            <div class="profile-form-grid">
                                <label>Nama Lengkap
                                    <div class="profile-field"><i class="bi bi-person"></i><input id="input-name" value="{{ $userName }}"></div>
                                </label>
                                <label>Email
                                    <div class="profile-field"><i class="bi bi-envelope"></i><input id="input-email" value="{{ $userEmail }}"></div>
                                </label>
                                <label>Kelas
                                    <div class="profile-field"><i class="bi bi-mortarboard"></i><input id="input-class" value="XI IPA 1"></div>
                                </label>
                                <label>NIS
                                    <div class="profile-field"><i class="bi bi-card-text"></i><input id="input-nis" value="1234567890"></div>
                                </label>
                                <label>Tanggal Lahir
                                    <div class="profile-field"><i class="bi bi-calendar3"></i><input id="input-birthdate" value="15/08/2007"></div>
                                </label>
                                <label>Jenis Kelamin
                                    <div class="profile-field"><i class="bi bi-gender-male"></i><select id="input-gender"><option>Laki-laki</option><option>Perempuan</option></select></div>
                                </label>
                            </div>
                        </div>

                        <div class="profile-tab-panel" id="tab-security">
                            <h3>Keamanan Akun</h3>
                            <p>Perbarui password dan opsi keamanan akun Anda.</p>
                            <div class="profile-form-grid">
                                <label>Password Lama
                                    <div class="profile-field"><i class="bi bi-lock"></i><input type="password" placeholder="Masukkan password lama"></div>
                                </label>
                                <label>Password Baru
                                    <div class="profile-field"><i class="bi bi-shield-lock"></i><input type="password" placeholder="Masukkan password baru"></div>
                                </label>
                                <label>Konfirmasi Password Baru
                                    <div class="profile-field"><i class="bi bi-check2-circle"></i><input type="password" placeholder="Ulangi password baru"></div>
                                </label>
                                <label>Verifikasi Login
                                    <div class="profile-field"><i class="bi bi-phone"></i><select><option>Aktif - OTP Email</option><option>Nonaktif</option></select></div>
                                </label>
                            </div>
                            <div class="security-options">
                                <label><input type="checkbox" checked> Kirim notifikasi jika ada login baru.</label>
                                <label><input type="checkbox" checked> Keluar otomatis dari perangkat lama setelah ganti password.</label>
                            </div>
                        </div>

                        <div class="profile-tab-panel" id="tab-notifications">
                            <h3>Pengaturan Notifikasi</h3>
                            <p>Atur pemberitahuan yang ingin Anda terima dari SIPIBS.</p>
                            <div class="notification-list">
                                <label><span><strong>Persetujuan Peminjaman</strong><small>Info saat pengajuan disetujui atau ditolak admin.</small></span><input type="checkbox" checked></label>
                                <label><span><strong>Pengingat Pengembalian</strong><small>Notifikasi sebelum batas waktu pengembalian barang.</small></span><input type="checkbox" checked></label>
                                <label><span><strong>Barang Terlambat</strong><small>Peringatan jika pengembalian melewati batas waktu.</small></span><input type="checkbox" checked></label>
                                <label><span><strong>Newsletter Sistem</strong><small>Info pembaruan fitur dan pengumuman sekolah.</small></span><input type="checkbox"></label>
                            </div>
                        </div>

                        <div class="profile-save-row">
                            <button type="submit"><i class="bi bi-floppy"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>

                <aside class="profile-side-stack">
                    <div class="profile-side-card">
                        <h3>Ringkasan Akun</h3>
                        <div class="summary-item"><span class="blue"><i class="bi bi-clipboard-check"></i></span><div><strong>Total Peminjaman</strong><small>Semua waktu</small></div><b>0</b></div>
                        <div class="summary-item"><span class="light-blue"><i class="bi bi-clock"></i></span><div><strong>Peminjaman Aktif</strong><small>Sedang dipinjam</small></div><b>0</b></div>
                        <div class="summary-item"><span class="green"><i class="bi bi-check-circle"></i></span><div><strong>Peminjaman Selesai</strong><small>Telah dikembalikan</small></div><b>0</b></div>
                        <div class="summary-item"><span class="red"><i class="bi bi-exclamation-triangle"></i></span><div><strong>Keterlambatan</strong><small>Peminjaman terlambat</small></div><b class="red-text">0</b></div>
                    </div>
                    <div class="profile-side-card activity-card-user">
                        <div class="activity-title"><h3>Aktivitas Terakhir</h3><a href="#" id="activity-toggle">Lihat Semua</a></div>
                        <div class="activity-item"><span class="blue"><i class="bi bi-check2-circle"></i></span><div><strong>Peminjaman baru disetujui</strong><p>Kamera Canon EOS 1300D</p></div><small>20 Mei<br>2024</small></div>
                        <div class="activity-item"><span class="green"><i class="bi bi-clipboard-check"></i></span><div><strong>Barang dikembalikan</strong><p>Laptop Dell Inspiron 14</p></div><small>18 Mei 2024</small></div>
                        <div class="activity-item"><span class="orange"><i class="bi bi-three-dots"></i></span><div><strong>Menunggu persetujuan</strong><p>Tripod Kamera</p></div><small>17 Mei<br>2024</small></div>
                        <div class="activity-item"><span class="light-blue"><i class="bi bi-plus-circle"></i></span><div><strong>Peminjaman baru</strong><p>Proyektor Epson XGA</p></div><small>15 Mei 2024</small></div>
                        <div class="activity-item activity-more"><span class="blue"><i class="bi bi-check2-circle"></i></span><div><strong>Peminjaman baru disetujui</strong><p>Speaker Portable JBL</p></div><small>12 Mei 2024</small></div>
                        <div class="activity-item activity-more"><span class="green"><i class="bi bi-clipboard-check"></i></span><div><strong>Barang dikembalikan</strong><p>Mikroskop Olympus CX23</p></div><small>10 Mei 2024</small></div>
                        <div class="activity-item activity-more"><span class="light-blue"><i class="bi bi-plus-circle"></i></span><div><strong>Peminjaman baru</strong><p>Mouse Logitech M170</p></div><small>8 Mei 2024</small></div>
                        <div class="activity-item activity-more"><span class="red"><i class="bi bi-exclamation-triangle"></i></span><div><strong>Keterlambatan pengembalian</strong><p>Bola Basket Molten</p></div><small>3 Mei 2024</small></div>
                    </div>
                </aside>
            </div>
        </section>
    </main>
</div>

<div class="profile-toast" id="profile-toast"><i class="bi bi-check-circle-fill"></i> Perubahan profil berhasil disimpan.</div>

@if($showLogout ?? false)
<div class="user-logout-overlay">
    <div class="user-logout-modal">
        <div class="user-logout-topline"></div>
        <div class="user-logout-icon"><i class="bi bi-box-arrow-right"></i></div>
        <h2>Konfirmasi Logout</h2>
        <p>Apakah Anda yakin ingin keluar dari sistem SIPIBS? Pastikan semua pekerjaan dan perubahan data inventaris Anda telah disimpan.</p>
        <div class="user-logout-actions">
            <a class="user-logout-cancel" href="{{ url('/profil-user') }}">Batal</a>
            <form method="POST" action="{{ route('logout') }}" class="user-logout-form">@csrf<button class="user-logout-confirm" type="submit">Ya, Keluar <i class="bi bi-arrow-right"></i></button></form>
        </div>
    </div>
</div>
@endif

<script>
    const invToggle = document.getElementById('inventaris-toggle');
    const invSub = document.getElementById('inventaris-sub');
    if (localStorage.getItem('invOpen') === '1') {
        invSub.classList.remove('collapsed');
        invToggle.classList.add('open');
    }
    invToggle.addEventListener('click', function (e) {
        e.preventDefault();
        invSub.classList.toggle('collapsed');
        invToggle.classList.toggle('open', !invSub.classList.contains('collapsed'));
        localStorage.setItem('invOpen', invSub.classList.contains('collapsed') ? '0' : '1');
    });
    invSub.querySelectorAll('.nav-sub-item').forEach(function(link) {
        link.addEventListener('click', function() {
            localStorage.setItem('invOpen', '1');
        });
    });

    document.querySelectorAll('.profile-tabs-user button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.profile-tabs-user button').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.profile-tab-panel').forEach(panel => panel.classList.remove('active'));
            button.classList.add('active');
            document.getElementById('tab-' + button.dataset.tab).classList.add('active');
        });
    });

    const activityToggle = document.getElementById('activity-toggle');
    const activityCard = activityToggle.closest('.activity-card-user');
    activityToggle.addEventListener('click', function (e) {
        e.preventDefault();
        const showAll = activityCard.classList.toggle('show-all');
        activityToggle.textContent = showAll ? 'Sembunyikan' : 'Lihat Semua';
    });

    document.getElementById('profile-photo-input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (event) {
            const dataUrl = event.target.result;
            document.getElementById('profile-photo').src = dataUrl;
            const avatar = document.getElementById('top-avatar');
            if (avatar) avatar.src = dataUrl;
            const saved = JSON.parse(localStorage.getItem('sipibs_user_profile') || '{}');
            saved.photo = dataUrl;
            localStorage.setItem('sipibs_user_profile', JSON.stringify(saved));
            if (window.applySipibsProfile) window.applySipibsProfile();
        };
        reader.readAsDataURL(file);
    });

    function getUserProfileFormData() {
        const saved = JSON.parse(localStorage.getItem('sipibs_user_profile') || '{}');
        return {
            name: document.getElementById('input-name').value,
            email: document.getElementById('input-email').value,
            className: document.getElementById('input-class').value,
            nis: document.getElementById('input-nis').value,
            birthdate: document.getElementById('input-birthdate').value,
            gender: document.getElementById('input-gender').value,
            photo: saved.photo || ''
        };
    }

    function applyUserProfileToPage(profile) {
        document.getElementById('hero-name').textContent = profile.name;
        document.getElementById('top-user-name').textContent = profile.name;
        document.getElementById('hero-email').textContent = profile.email;
        document.getElementById('input-name').value = profile.name;
        document.getElementById('input-email').value = profile.email;
        document.getElementById('input-class').value = profile.className;
        document.getElementById('input-nis').value = profile.nis;
        document.getElementById('input-birthdate').value = profile.birthdate;
        document.getElementById('input-gender').value = profile.gender;
        if (profile.photo) {
            document.getElementById('profile-photo').src = profile.photo;
            document.getElementById('top-avatar').src = profile.photo;
        }
    }

    document.getElementById('profile-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const profile = getUserProfileFormData();
        localStorage.setItem('sipibs_user_profile', JSON.stringify(profile));
        applyUserProfileToPage(profile);
        if (window.applySipibsProfile) window.applySipibsProfile();
        const toast = document.getElementById('profile-toast');
        toast.classList.add('active');
        setTimeout(() => toast.classList.remove('active'), 2500);
    });

    const savedUserProfile = JSON.parse(localStorage.getItem('sipibs_user_profile') || 'null');
    if (savedUserProfile) applyUserProfileToPage(savedUserProfile);

    window.history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);
</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>


