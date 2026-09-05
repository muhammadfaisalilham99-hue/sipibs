@php
    $active = 'pengembalian';
    $title = 'Manajemen Pengembalian';
    $isLogout = false;
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
    <link rel="stylesheet" href="{{ asset('css/admin-pengembalian.css') }}?v=15">
</head>
<body class="{{ $isLogout ? 'modal-page' : '' }}">
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-logo">
            @include('admin.partials.sipibs-logo')
            <div class="sidebar-title" style="font-size:22px;">SIPIBS</div>
            <div class="sidebar-subtitle">INVENTORY SYSTEM</div>
            <div style="margin-top:12px;"><span class="status blue">ADMIN PANEL</span></div>
        </div>
        <div class="menu-caption">MAIN MENU</div>
        <nav class="nav-list">
            <a class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ url('/dashboard-admin') }}"><i class="bi bi-house-fill"></i> Dashboard</a>
            <div class="menu-caption nav-caption">MASTER DATA</div>
            <a class="nav-item {{ $active === 'master' ? 'active' : '' }}" href="{{ url('/admin/data-master') }}"><i class="bi bi-box-seam-fill"></i> Data Master <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'user' ? 'active' : '' }}" href="{{ url('/admin/data-user') }}"><i class="bi bi-people-fill"></i> Data User <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">TRANSAKSI</div>
            <a class="nav-item {{ $active === 'peminjaman' ? 'active' : '' }}" href="{{ url('/admin/peminjaman') }}"><i class="bi bi-journal-check"></i> Peminjaman <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'pengembalian' ? 'active' : '' }}" href="{{ url('/admin/pengembalian') }}"><i class="bi bi-card-checklist"></i> Pengembalian <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'denda' ? 'active' : '' }}" href="{{ url('/admin/denda') }}"><i class="bi bi-cash-coin"></i> Denda <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">LAPORAN</div>
            <a class="nav-item {{ $active === 'laporan' ? 'active' : '' }}" href="{{ url('/admin/laporan') }}"><i class="bi bi-bar-chart-fill"></i> Laporan <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">AKUN</div>
            <a class="nav-item {{ $active === 'profil' ? 'active' : '' }}" href="{{ url('/admin/profil') }}"><i class="bi bi-person-fill"></i> Profil <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'logout' ? 'active' : '' }}" href="{{ url('/admin/logout') }}"><i class="bi bi-box-arrow-left"></i> Logout <span style="margin-left:auto;">›</span></a>
        </nav>
        <div class="admin-profile"><img class="avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin"><div><strong>Online</strong><span>Administrator<br>Super Admin</span></div></div>
    </aside>

    <main class="return-admin-main">
        <header class="return-topbar">
            <h1>Manajemen Pengembalian</h1>
            <div class="return-search"><i class="bi bi-search"></i><input id="returnSearchInput" type="text" placeholder="Cari ID Peminjaman..."></div>
            <div class="return-top-actions">
                @include('admin.partials.notification-bell')
                <button type="button" class="top-icon-btn" id="helpTopBtn"><i class="bi bi-question-circle"></i></button>
                <div class="return-admin-user"><div><strong>Admin SIPIBS(ical)</strong><span>Administrator</span></div><img class="return-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="User"><i class="bi bi-chevron-down"></i></div>
            </div>
        </header>

        <section class="return-content">
            <div class="return-detail-card">
                <div class="return-section-title table-title"><i class="bi bi-list-ul"></i><span>Detail Barang yang Dikembalikan</span></div>
                <div class="return-table-wrap">
                    <table class="return-detail-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Nama User</th>
                                <th>Jumlah</th>
                                <th>Foto Barang</th>
                                <th>Kondisi Saat Kembali</th>
                                <th>Keterangan</th>
                                <th>Jenis Denda</th>
                                <th>Keputusan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="returnItemsBody"></tbody>
                    </table>
                </div>
                <div class="return-action-row">
                    <div class="return-note-info"><i class="bi bi-info-circle"></i>Pastikan semua data kondisi barang telah diperiksa fisik sebelum disimpan.</div>
                    <button type="button" class="return-btn secondary" id="cancelReturnBtn">Batal</button>
                    <button type="button" class="return-btn primary" id="saveReturnBtn"><i class="bi bi-floppy-fill"></i>Simpan Pengembalian</button>
                </div>
            </div>

            <div class="return-archive-card">
                <div class="return-archive-header">
                    <div class="return-section-title table-title"><i class="bi bi-archive-fill"></i><span>Riwayat Pengembalian (Arsip)</span></div>
                    <div class="return-archive-tools">
                        <span class="archive-count-badge" id="archiveCountBadge">0 pengembalian</span>
                        <button type="button" class="archive-collapse-btn" id="archiveCollapseBtn" aria-expanded="true" title="Lipat / buka arsip"><i class="bi bi-chevron-up"></i></button>
                    </div>
                </div>
                <div class="return-archive-body" id="archiveBody">
                    <div class="return-archive-scroll">
                        <table class="return-detail-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Nama User</th>
                                    <th>Jumlah</th>
                                    <th>Kondisi Saat Kembali</th>
                                    <th>Keterangan</th>
                                    <th>Jenis Denda</th>
                                    <th>Keputusan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="returnArchiveBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="return-bottom-grid">
                <section class="return-auto-card" style="grid-column:1 / -1;">
                    <div class="return-shield"><i class="bi bi-shield-check"></i></div>
                    <div><h3>Verifikasi Otomatis</h3><p>Sistem akan secara otomatis memperbarui status stok barang di “Data Master” setelah Anda menekan tombol Simpan.</p><span><i class="bi bi-check-circle"></i> Stok akan bertambah sesuai jumlah barang yang dikembalikan.</span></div>
                </section>
            </div>
        </section>
    </main>
</div>

<div class="return-toast" id="returnToast"></div>
<script>
    window.PENGEMBALIAN_DEFAULT_PHOTO = '{{ asset("images/PROYEKTOR EPSON.jpg") }}';
</script>
<script src="{{ asset('js/admin-pengembalian.js') }}?v=22"></script>
@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>
