@php
    $active = 'logout';
    $title = 'Konfirmasi Logout';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=1">
</head>
<body class="logout-blur-page">
<div class="app-shell blur-background">
    <aside class="sidebar">
        <div class="sidebar-logo">
            @include('admin.partials.sipibs-logo')
            <div class="sidebar-title" style="font-size:22px;">SIPIBS</div>
            <div class="sidebar-subtitle">INVENTORY SYSTEM</div>
            <div style="margin-top:12px;"><span class="status blue">ADMIN PANEL</span></div>
        </div>
        <div class="menu-caption">MAIN MENU</div>
        <nav class="nav-list">
            <a class="nav-item" href="{{ url('/dashboard-admin') }}"><i class="bi bi-house-fill"></i> Dashboard</a>
            <div class="menu-caption nav-caption">MASTER DATA</div>
            <a class="nav-item" href="{{ url('/admin/data-master') }}"><i class="bi bi-box-seam-fill"></i> Data Master <span style="margin-left:auto;">›</span></a>
<a class="nav-item" href="{{ url('/admin/data-user') }}"><i class="bi bi-people-fill"></i> Data User <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">TRANSAKSI</div>
            <a class="nav-item" href="{{ url('/admin/peminjaman') }}"><i class="bi bi-journal-check"></i> Peminjaman <span style="margin-left:auto;">›</span></a>
            <a class="nav-item" href="{{ url('/admin/pengembalian') }}"><i class="bi bi-card-checklist"></i> Pengembalian <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">LAPORAN</div>
            <a class="nav-item" href="{{ url('/admin/laporan') }}"><i class="bi bi-bar-chart-fill"></i> Laporan <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">AKUN</div>
            <a class="nav-item" href="{{ url('/admin/profil') }}"><i class="bi bi-person-fill"></i> Profil <span style="margin-left:auto;">›</span></a>
            <a class="nav-item active" href="{{ url('/admin/logout') }}"><i class="bi bi-box-arrow-left"></i> Logout <span style="margin-left:auto;">›</span></a>
        </nav>
        <div class="admin-profile"><img class="avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin"><div><strong>Online</strong><span>Administrator<br>Super Admin</span></div></div>
    </aside>

    <main class="main-area">
        <header class="topbar profile-topbar">
            <div class="profile-search-box"><i class="bi bi-search"></i><input placeholder="Cari..."></div>
            <div class="top-actions">@include('admin.partials.notification-bell')<i class="bi bi-question-circle"></i><div class="top-user"><div><strong id="top-user-name">Admin SIPIBS</strong><span>SUPER ADMIN</span></div><img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin"></div></div>
        </header>

        <section class="content">
            <div class="profile-grid-container">
                <div class="profile-main-col">
                    <div class="profile-header-card">
                        <div class="avatar-upload-wrap">
                            <img class="profile-avatar-lg" src="{{ asset('images/PROFIL.png') }}" alt="Admin SIPIBS">
                            <button type="button" class="btn-avatar-edit"><i class="bi bi-pencil-fill"></i></button>
                        </div>
                        <div class="profile-header-info">
                            <h2>Admin SIPIBS</h2>
                            <div class="profile-meta-grid">
                                <div><i class="bi bi-person-badge"></i><span>NIP: <strong>198501012010011001</strong></span></div>
                                <div><i class="bi bi-envelope"></i><span>admin@sipibs.sch.id</span></div>
                                <div><i class="bi bi-shield-check"></i><span>Role: <strong>Administrator System</strong></span></div>
                                <div><i class="bi bi-check-circle-fill green-icon"></i><span>Status: <strong class="green-text">Aktif</strong></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-tabs-card">
                        <div class="profile-tabs-header">
                            <button type="button" class="tab-btn active">Data Pribadi</button>
                            <button type="button" class="tab-btn">Keamanan Akun</button>
                            <button type="button" class="tab-btn">Log Aktivitas</button>
                        </div>
                        <div class="profile-tab-content">
                            <div class="form-grid-2">
                                <div><label class="plain-label">Nama Lengkap</label><input class="plain-input" value="Admin SIPIBS" disabled></div>
                                <div><label class="plain-label">Nomor Induk Pegawai (NIP)</label><input class="plain-input" value="198501012010011001" disabled></div>
                                <div><label class="plain-label">Alamat Email</label><input class="plain-input" value="admin@sipibs.sch.id" disabled></div>
                                <div><label class="plain-label">Nomor Telepon</label><input class="plain-input" value="+62 812 3456 7890" disabled></div>
                            </div>
                            <div style="margin-top:16px;">
                                <label class="plain-label">Alamat Lengkap</label>
                                <textarea class="plain-textarea" rows="3" disabled>Jl. Pendidikan No. 123, Kelurahan Maju Jaya, Kecamatan Cerdas, Kota Pintar, 12345</textarea>
                            </div>
                            <div class="profile-form-btns">
                                <button type="button" class="btn-admin-light" disabled>Reset</button>
                                <button type="button" class="btn-admin-primary blue-solid" disabled>Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="profile-side-col">
                    <div class="side-card">
                        <div class="side-card-head"><h3>Ringkasan User</h3><i class="bi bi-people"></i></div>
                        <div class="user-summary-grid">
                            <div class="user-summary-card total-card">
                                <span class="card-label">Total Pengguna</span>
                                <div class="card-val-row"><h3 class="card-big-num">1,248</h3><span class="pill-badge-blue">+12 bln ini</span></div>
                                <div class="bar-track"><div class="bar-progress" style="width: 78%;"></div></div>
                            </div>
                            <div class="user-summary-card flex-stat-card">
                                <div class="stat-icon-blue"><i class="bi bi-person-check-fill"></i></div>
                                <div><span class="card-label">Pengguna Aktif</span><h3 class="card-med-num blue-num">1,150</h3></div>
                            </div>
                            <div class="user-summary-card flex-stat-card">
                                <div class="stat-icon-red"><i class="bi bi-clipboard-check"></i></div>
                                <div><span class="card-label">Menunggu Verifikasi</span><h3 class="card-med-num red-num">98</h3></div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </main>
</div>

<!-- Modal Logout Overlay (Sesuai Gambar Referensi) -->
<div class="logout-modal-backdrop">
    <div class="logout-modal-box">
        <div class="logout-red-bar"></div>
        <div class="logout-icon-box">
            <i class="bi bi-box-arrow-right"></i>
        </div>
        <h2>Konfirmasi Logout</h2>
        <p>Apakah Anda yakin ingin keluar dari sistem SIPIBS? Pastikan semua pekerjaan dan perubahan data inventaris Anda telah disimpan.</p>
        <div class="logout-modal-actions">
            <a href="{{ url('/admin/profil') }}" class="btn-logout-cancel" id="btnLogoutCancel">Batal</a>
            <a href="{{ url('/login-admin') }}" class="btn-logout-confirm" id="btnLogoutConfirm">Ya, Keluar <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>
