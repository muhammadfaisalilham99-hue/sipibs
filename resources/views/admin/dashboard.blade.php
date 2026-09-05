<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=1">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-logo">
            @include('admin.partials.sipibs-logo')
            <div class="sidebar-title" style="font-size:22px;">SIPIBS</div>
            <div class="sidebar-subtitle">INVENTORY SYSTEM</div>
            <div style="margin-top:12px;"><span class="status blue"><i class="bi bi-shield-check"></i> ADMIN PANEL</span></div>
        </div>
        <div class="menu-caption">MAIN MENU</div>
        <nav class="nav-list">
            <a class="nav-item active" href="{{ url('/dashboard-admin') }}"><i class="bi bi-house-fill"></i> Dashboard</a>
            <div class="menu-caption nav-caption">MASTER DATA</div>
            <a class="nav-item" href="{{ url('/admin/data-master') }}"><i class="bi bi-box-seam-fill"></i> Data Master <span style="margin-left:auto;">›</span></a>
<a class="nav-item" href="{{ url('/admin/data-user') }}"><i class="bi bi-people-fill"></i> Data User <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">TRANSAKSI</div>
            <a class="nav-item" href="{{ url('/admin/peminjaman') }}"><i class="bi bi-journal-check"></i> Peminjaman <span style="margin-left:auto;">›</span></a>
            <a class="nav-item" href="{{ url('/admin/pengembalian') }}"><i class="bi bi-card-checklist"></i> Pengembalian <span style="margin-left:auto;">›</span></a>
            <a class="nav-item" href="{{ url('/admin/denda') }}"><i class="bi bi-cash-coin"></i> Denda <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">LAPORAN</div>
            <a class="nav-item" href="{{ url('/admin/laporan') }}"><i class="bi bi-bar-chart-fill"></i> Laporan <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">AKUN</div>
            <a class="nav-item" href="{{ url('/admin/profil') }}"><i class="bi bi-person-fill"></i> Profil <span style="margin-left:auto;">›</span></a>
            <a class="nav-item" href="{{ url('/admin/logout') }}"><i class="bi bi-box-arrow-left"></i> Logout <span style="margin-left:auto;">›</span></a>
        </nav>
        <div class="admin-profile"><img class="avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin"><div><strong>Online</strong><span>Administrator<br>Super Admin</span></div></div>
    </aside>
    <main class="main-area">
        <header class="topbar">
            <div class="page-label">Dashboard Overview</div>
            <div class="top-actions">@include('admin.partials.notification-bell')<i class="bi bi-question-circle"></i><div class="top-user"><div><strong id="top-user-name">Administrator</strong><span>Admin Staff</span></div><img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin"></div></div>
        </header>
        <section class="content">
            <div class="hero-card"><h1>SELAMAT DATANG DI SIPIBS👋</h1><p>Kelola peminjaman dan inventaris barang sekolah dengan mudah melalui dashboard SIPIBS yang terintegrasi.</p></div>
            <div class="stats-grid dashboard-stats">
                <div class="stat-card"><div class="stat-icon"><i class="bi bi-clipboard-check"></i></div><span class="trend up">+12%</span><div class="stat-info"><span>Total Alat</span><strong>15</strong></div></div>
                <div class="stat-card"><div class="stat-icon"><i class="bi bi-gem"></i></div><span class="trend down">-5%</span><div class="stat-info"><span>Alat Dipinjam</span><strong>42</strong></div></div>
                <div class="stat-card"><div class="stat-icon orange"><i class="bi bi-clipboard-x"></i></div><span class="trend neutral">Baru</span><div class="stat-info"><span>Menunggu Persetujuan</span><strong class="orange">8</strong></div></div>
                <div class="stat-card"><div class="stat-icon green"><i class="bi bi-calendar-check"></i></div><span class="trend neutral">Hari ini</span><div class="stat-info"><span>Pengembalian Hari Ini</span><strong class="green">15</strong></div></div>
            </div>
            <div class="dash-grid">
                <section class="panel">
                    <div class="panel-head"><h2>Peminjaman Terbaru</h2><a href="{{ url('/admin/peminjaman') }}">Lihat Semua</a></div>
                        <table class="table">
                            <thead><tr><th>No</th><th>Nama Peminjam</th><th>Alat</th><th>Tanggal Pinjam</th><th>Status</th><th>Aksi</th></tr></thead>
                            <tbody id="recentLoansBody"></tbody>
                        </table>
                </section>
                <aside class="side-section">
                    <section class="panel"><div class="panel-head"><h2>Informasi Status</h2></div><div class="status-list"><div class="status-item"><i class="bi bi-clock"></i><div><strong>Menunggu</strong><span>Peminjaman memerlukan verifikasi admin.</span></div></div><div class="status-item green"><i class="bi bi-check2-circle"></i><div><strong>Disetujui</strong><span>Barang siap diambil oleh peminjam.</span></div></div><div class="status-item blue"><i class="bi bi-arrow-left-right"></i><div><strong>Dipinjam</strong><span>Alat saat ini sedang digunakan.</span></div></div></div></section>
                    <section class="panel"><div class="panel-head"><h2>Aksi Cepat</h2></div><div class="quick-actions"><a class="quick-btn" href="{{ url('/admin/data-master') }}"><i class="bi bi-plus-square"></i> TAMBAH ALAT</a><a class="quick-btn" href="{{ url('/admin/laporan') }}"><i class="bi bi-download"></i> EKSPOR DATA</a></div></section>
                </aside>
            </div>
        </section>
    </main>
</div>
@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('recentLoansBody');
    const dummyIds = ['PMJ-2026-01', 'PMJ-2026-02', 'PMJ-2026-03', 'PMJ-2026-04', 'PMJ-2026-05', 'PMJ-2026-06'];
    function readJson(key, fallback) {
        try { return JSON.parse(localStorage.getItem(key) || 'null') || fallback; }
        catch (e) { return fallback; }
    }
    let loans = readJson('sipibsAdminLoanList', []);
    if (!loans.length) loans = readJson('sipibsLoanHistory', []);
    loans = loans.filter(item => item && item.id && dummyIds.indexOf(item.id) === -1);
    const recent = loans.slice(0, 3);
    const labels = { pending: 'MENUNGGU', approved: 'DISETUJUI', rejected: 'DITOLAK', returned: 'SELESAI' };
    const css = { pending: 'yellow', approved: 'green', rejected: 'red', returned: 'blue' };
    if (!recent.length) {
        body.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#888;padding:18px;">Belum ada peminjaman terbaru.</td></tr>';
        return;
    }
    body.innerHTML = recent.map((item, index) => {
        const status = item.status || 'pending';
        return '<tr>' +
            '<td>' + (index + 1) + '</td>' +
            '<td><strong>' + (item.borrower || '-') + '</strong></td>' +
            '<td>' + (item.item || '-') + '</td>' +
            '<td>' + (item.startDate || '-') + '</td>' +
            '<td><span class="status ' + (css[status] || 'yellow') + '">' + (labels[status] || status) + '</span></td>' +
            '<td><button class="action-btn" title="Lihat detail"><i class="bi bi-eye"></i></button></td>' +
            '</tr>';
    }).join('');
});
</script>
</body>
</html>



