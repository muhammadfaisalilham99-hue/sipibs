<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kondisi Barang - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=19">
</head>
<body>
@php
    $userName = Auth::check() ? Auth::user()->name : 'Dina Atalia';
    $items = [
        'PRJ-001' => ['name' => 'Mouse HP USB-2', 'room' => 'Lab Komputer', 'cat' => 'Elektronik', 'icon' => 'bi-mouse'],
        'CAM-002' => ['name' => 'Keyboard NuPhy Air75', 'room' => 'Lab Komputer', 'cat' => 'Elektronik', 'icon' => 'bi-keyboard'],
        'LAP-003' => ['name' => 'Laptop Lenovo Ideapad Slim 3', 'room' => 'Lab Komputer', 'cat' => 'Elektronik', 'icon' => 'bi-laptop'],
        'SPK-004' => ['name' => 'Headset Logitech G 432 7.1', 'room' => 'Lab Multimedia', 'cat' => 'Audio Visual', 'icon' => 'bi-headphones'],
        'TRI-005' => ['name' => '4K Webcam 1080P 60fps Mini Video Camera', 'room' => 'Lab Multimedia', 'cat' => 'Elektronik', 'icon' => 'bi-camera-video'],
        'MIK-006' => ['name' => 'Epson EX3240 SVGA 3LCD Projector 3200', 'room' => 'Ruang Kelas', 'cat' => 'Praktikum', 'icon' => 'bi-easel'],
        'GLO-007' => ['name' => 'Kabel Black High Speed 1.4 Version Gold-Plated HDMI', 'room' => 'Gudang Inventaris', 'cat' => 'Peralatan Kantor', 'icon' => 'bi-usb-plug'],
        'BOL-008' => ['name' => 'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin', 'room' => 'Gudang Inventaris', 'cat' => 'Peralatan Kantor', 'icon' => 'bi-plug'],
        'RKT-009' => ['name' => 'Kabel LAN CAT6 UTP Cable Networking', 'room' => 'Lab Jaringan', 'cat' => 'Peralatan Kantor', 'icon' => 'bi-ethernet'],
        'PPT-010' => ['name' => 'Kabel HDMI to VGA Adapter Gold Plated', 'room' => 'Gudang Inventaris', 'cat' => 'Peralatan Kantor', 'icon' => 'bi-usb-c'],
        'MS-011' => ['name' => 'Pen Wireless Remote Controller Laser Pointer', 'room' => 'Ruang Presentasi', 'cat' => 'Elektronik', 'icon' => 'bi-broadcast-pin'],
        'PRJ-012' => ['name' => 'Stop Kontak', 'room' => 'Gudang Inventaris', 'cat' => 'Elektronik', 'icon' => 'bi-outlet'],
        'HDM-013' => ['name' => '2pcs Multifunctional network tester 468 network cable', 'room' => 'Lab Jaringan', 'cat' => 'Peralatan Kantor', 'icon' => 'bi-router'],
        'LAN-014' => ['name' => 'Tang Crimping Tool RJ45 RJ11 HT-200R', 'room' => 'Lab Jaringan', 'cat' => 'Peralatan Kantor', 'icon' => 'bi-tools'],
        'ADP-015' => ['name' => 'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth', 'room' => 'Aula Sekolah', 'cat' => 'Elektronik', 'icon' => 'bi-speaker'],
    ];
    $selectedCode = $code ?: 'PRJ-001';
    $item = $items[$selectedCode] ?? $items['PRJ-001'];
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
                <a class="nav-sub-item active" href="{{ url('/kondisi-barang') }}">Kondisi Barang</a>
            </div>
            <a class="nav-item" href="{{ url('/peminjaman-user') }}"><i class="bi bi-gem"></i> Peminjaman</a>
            <a class="nav-item" href="{{ url('/pengembalian-user') }}"><i class="bi bi-calendar-check"></i> Pengembalian</a>
            <a class="nav-item" href="{{ url('/denda-user') }}"><i class="bi bi-cash-coin"></i> Denda</a>
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>
    <main class="main-area">
        <header class="topbar">
            <div class="search-box condition-search"><i class="bi bi-search"></i> Cari barang atau kode inventaris...</div>
            <div class="top-actions">
                @include('user.partials.notification-bell')

                <div class="top-user"><div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div><img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar"></div>
            </div>
        </header>
        <section class="content catalog-page-content condition-page-content">
            <div class="catalog-page-head">
                <div>
                    <div class="catalog-breadcrumb">Home <i class="bi bi-chevron-right"></i> Inventaris <i class="bi bi-chevron-right"></i> Kondisi Barang <i class="bi bi-chevron-right"></i> <span>Detail</span></div>
                    <h1>Detail Kondisi Barang</h1>
                    <p>Informasi detail kondisi dan lokasi barang inventaris.</p>
                </div>
                <div class="catalog-page-actions"><a class="filter-btn detail-back" href="{{ url('/kondisi-barang') }}"><i class="bi bi-arrow-left"></i> Kembali</a></div>
            </div>
            <div class="detail-condition-grid">
                <div class="detail-condition-card main">
                    <div class="detail-condition-hero"><i class="bi {{ $item['icon'] }}"></i></div>
                    <h2>{{ $item['name'] }}</h2>
                    <span class="condition-code">{{ $selectedCode }}</span>
                    <p>{{ $item['room'] }} • {{ $item['cat'] }}</p>
<span class="condition-badge green" id="detailConditionBadge"><i class="bi bi-circle-fill"></i> BAIK</span>
                </div>
                <div class="detail-condition-card">
                    <h3>Ringkasan Kondisi</h3>
                    <div class="detail-row"><span>Status</span><strong id="detailConditionRingkasan">Layak Pakai</strong></div>
                    <div class="detail-row"><span>Pemeriksaan Terakhir</span><strong>12 Agustus 2026</strong></div>
                    <div class="detail-row"><span>Petugas</span><strong>Sarpras SIPIBS</strong></div>
                    <div class="detail-row"><span>Catatan</span><strong id="detailConditionCatatan">Tidak ada kerusakan</strong></div>
                </div>
            </div>
            <div class="condition-table-card detail-history-card">
                <h3>Riwayat Kondisi</h3>
                <table class="admin-table condition-table">
                    <thead><tr><th>Tanggal</th><th>Kondisi</th><th>Lokasi</th><th>Keterangan</th></tr></thead>
                    <tbody>
                        <tr><td>12 Agustus 2026</td><td><span class="condition-badge green"><i class="bi bi-circle-fill"></i> BAIK</span></td><td>{{ $item['room'] }}</td><td>Pemeriksaan rutin selesai.</td></tr>
                        <tr><td>5 Agustus 2026</td><td><span class="condition-badge green"><i class="bi bi-circle-fill"></i> BAIK</span></td><td>{{ $item['room'] }}</td><td>Barang siap digunakan.</td></tr>
                        <tr><td>29 Juli 2026</td><td><span class="condition-badge yellow"><i class="bi bi-circle-fill"></i> RUSAK RINGAN</span></td><td>{{ $item['room'] }}</td><td>Adaptor diperiksa ulang.</td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<script>
    const invToggle = document.getElementById('inventaris-toggle');
    const invSub = document.getElementById('inventaris-sub');
    if (localStorage.getItem('invOpen') === '1') { invSub.classList.remove('collapsed'); invToggle.classList.add('open'); }
    invToggle.addEventListener('click', function (e) { e.preventDefault(); invSub.classList.toggle('collapsed'); invToggle.classList.toggle('open', !invSub.classList.contains('collapsed')); localStorage.setItem('invOpen', invSub.classList.contains('collapsed') ? '0' : '1'); });
    invSub.querySelectorAll('.nav-sub-item').forEach(function(link) { link.addEventListener('click', function() { localStorage.setItem('invOpen', '1'); }); });

    (function () {
        const code = '{{ $selectedCode }}';
        function condState(cond) {
            const c = String(cond || '').toUpperCase();
            if (c.includes('RUSAK') && !c.includes('RINGAN')) return { label: 'RUSAK BERAT', status: 'red', ringkasan: 'Tidak Layak Pakai', catatan: 'Barang rusak, perlu perbaikan' };
            if (c.includes('RUSAK')) return { label: 'RUSAK RINGAN', status: 'yellow', ringkasan: 'Kurang Layak', catatan: 'Barang mengalami kerusakan ringan' };
            return { label: 'BAIK', status: 'green', ringkasan: 'Layak Pakai', catatan: 'Tidak ada kerusakan' };
        }
        let master = [];
        try { master = JSON.parse(localStorage.getItem('sipibsMasterItems') || '[]'); } catch (e) {}
        const item = master.find(m => String(m.code).toUpperCase() === String(code).toUpperCase());
        const state = condState(item ? item.condition : 'BAIK');
        const badge = document.getElementById('detailConditionBadge');
        if (badge) {
            badge.className = 'condition-badge ' + state.status;
            badge.innerHTML = '<i class="bi bi-circle-fill"></i> ' + state.label;
        }
        const ringkasan = document.getElementById('detailConditionRingkasan');
        if (ringkasan) ringkasan.textContent = state.ringkasan;
        const catatan = document.getElementById('detailConditionCatatan');
        if (catatan) catatan.textContent = state.catatan;
    })();
</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>


