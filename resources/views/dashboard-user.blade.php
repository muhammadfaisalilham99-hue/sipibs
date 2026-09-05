<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=3">
    <style>
        .lightbox-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            visibility: hidden;
            transition: background 0.35s ease, visibility 0s 0.35s;
        }
        .lightbox-overlay.active {
            visibility: visible;
            background: rgba(0,0,0,0.88);
            transition: background 0.35s ease, visibility 0s 0s;
        }
        .lightbox-container {
            display: flex;
            align-items: flex-start;
            gap: 40px;
            max-width: 1060px;
            width: 92vw;
            cursor: default;
            padding-top: 30px;
            opacity: 0;
            transform: scale(0.9) translateY(30px);
            transition: opacity 0.4s ease, transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .lightbox-overlay.active .lightbox-container {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        .lightbox-img-wrap {
            flex: 0 0 520px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .lightbox-img-wrap img {
            width: 100%;
            max-height: 75vh;
            object-fit: contain;
            border-radius: 14px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.5);
            display: block;
        }
        .lightbox-spec {
            flex: 1;
            color: #fff;
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.4s ease 0.12s, transform 0.4s ease 0.12s;
        }
        .lightbox-overlay.active .lightbox-spec {
            opacity: 1;
            transform: translateX(0);
        }
        .lightbox-spec h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 6px;
        }
        .lightbox-spec .spec-badge {
            display: inline-block;
            background: rgba(59,130,246,0.25);
            color: #60a5fa;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 14px;
        }
        .lightbox-spec .spec-desc {
            font-size: 0.88rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
            margin-bottom: 18px;
        }
        .spec-table {
            width: 100%;
            border-collapse: collapse;
        }
        .spec-table tr {
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .spec-table tr:last-child { border-bottom: none; }
        .spec-table td {
            padding: 7px 0;
            font-size: 0.82rem;
            vertical-align: top;
        }
        .spec-table td:first-child {
            color: rgba(255,255,255,0.45);
            font-weight: 500;
            width: 130px;
            white-space: nowrap;
        }
        .spec-table td:last-child {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
        }
        .spec-features {
            margin-top: 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .spec-features span {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.75);
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #fff;
            font-size: 2.2rem;
            cursor: pointer;
            line-height: 1;
            opacity: 0;
            transform: rotate(90deg);
            transition: opacity 0.3s ease 0.15s, transform 0.3s ease 0.15s;
        }
        .lightbox-overlay.active .lightbox-close {
            opacity: 1;
            transform: rotate(0deg);
        }
        .item-image img { cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .item-image img:hover { transform: scale(1.03); box-shadow: 0 4px 16px rgba(0,0,0,0.15); }
    </style>
</head>
<body>
<div class="lightbox-overlay" id="lightbox">
    <span class="lightbox-close">&times;</span>
    <div class="lightbox-container" id="lightbox-container">
        <div class="lightbox-img-wrap">
            <img id="lightbox-img" src="" alt="Preview">
        </div>
        <div class="lightbox-spec" id="lightbox-spec"></div>
    </div>
</div>
@php
    $userName = Auth::check() ? Auth::user()->name : 'gashima';
    $userNumber = Auth::check() ? Auth::user()->identity_number : '444444';
@endphp
<div class="app-shell">
    <aside class="sidebar user-sidebar">
        <div class="sidebar-logo">
            @include('partials.sipibs-logo')
            <div class="sidebar-title">SIPIBS</div>
            <div class="sidebar-subtitle">INVENTORY</div>
        </div>
        <nav class="nav-list">
            <a class="nav-item active" href="#"><i class="bi bi-grid"></i> Dashboard</a>
            <a class="nav-item" href="#" id="inventaris-toggle"><i class="bi bi-box-seam"></i> Inventaris <span class="nav-arrow">^</span></a>
            <div class="nav-sub-list collapsed" id="inventaris-sub">
                <a class="nav-sub-item" href="{{ url('/katalog-alat') }}">Katalog Barang</a>
                <a class="nav-sub-item" href="{{ url('/kondisi-barang') }}">Kondisi Barang</a>
            </div>
            <a class="nav-item" href="{{ url('/peminjaman-user') }}"><i class="bi bi-gem"></i> Peminjaman</a>
            <a class="nav-item" href="{{ url('/pengembalian-user') }}"><i class="bi bi-calendar-check"></i> Pengembalian</a>
            <a class="nav-item" href="{{ url('/denda-user') }}"><i class="bi bi-cash-coin"></i> Denda</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>
    <main class="main-area">
        <header class="topbar">
            <div class="search-box"><i class="bi bi-search"></i> Cari barang atau riwayat...</div>
            <div class="top-actions">
                <i class="bi bi-bell"></i>
                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('PROFIL.png') }}" alt="Avatar">

                </div>
            </div>
        </header>
        <section class="content">
            <div class="hero-card">
                <h1>Selamat Datang, <span id="hero-greeting-name">{{ $userName }}</span>!👋</h1>
                <p>Selamat datang kembali di SIPIBS. Kelola peminjaman alat praktikmu dengan lebih mudah dan terorganisir di sini.</p>
                <i class="bi bi-mortarboard watermark"></i>
            </div>
            <div class="stats-grid user-stats">
                <div class="stat-card"><div class="stat-icon"><i class="bi bi-clipboard-check"></i></div><div class="stat-info"><span>Total Pinjam</span><strong>0</strong><span>Alat</span></div></div>
                <div class="stat-card"><div class="stat-icon"><i class="bi bi-clipboard-data"></i></div><div class="stat-info"><span>Menunggu Persetujuan</span><strong>0</strong><span>Pending</span></div></div>
                <div class="stat-card"><div class="stat-icon sky"><i class="bi bi-check2-circle"></i></div><div class="stat-info"><span>Selesai</span><strong>0</strong><span>Barang</span></div></div>
            </div>
            <div class="dash-grid">
                <section>
                    <div class="panel">
                        <div class="catalog-head">
                            <div class="catalog-title"><h2>Katalog Alat</h2><p>Daftar semua barang yang tersedia di sekolah</p></div>
                            <button class="mini-search"><i class="bi bi-search"></i></button>
                            <button class="filter-btn"><i class="bi bi-funnel"></i> Filter</button>
                            <button class="loan-btn"><i class="bi bi-plus"></i> Ajukan Peminjaman</button>
                        </div>
                        <div class="category-tabs"><span class="active">Semua</span><span>Elektronik</span><span>Komputer</span><span>Audio Visual</span><span>Olahraga</span><span>Praktikum</span></div>
                        <div class="item-grid">
                            <article class="item-card" data-specs='{"name":"Mouse HP USB-2","category":"Elektronik","desc":"Mouse gaming nirkabel ultra-lightweight berdesain skeleton/transparan berongga. Dibanderol harga terjangkau dengan spesifikasi kelas atas.","specs":[["Sensor","PixArt PAW3311"],["DPI","12.000 - 22.000 (atur mulai 800)"],["Berat","±65 gram"],["Koneksi","Wireless 2.4GHz / Bluetooth / USB-C"],["Polling Rate","125 Hz - 1000 Hz"],["Baterai","400mAh, hingga 180 jam"]],"features":["Skeleton Transparan","RGB Lighting","Kustomisasi Makro","Switch Tactile"]}'><div class="item-image"><span class="available">Tersedia</span><img src="{{ asset('MOUSE FURYCUBE G11.jpg') }}" alt="Mouse Furycube G11" style="width:100%;height:100%;object-fit:cover;"></div><h3>Mouse Furycube G11</h3><p>Elektronik<br>Kode: PRJ-001</p><button><i class="bi bi-clipboard-plus"></i> Ajukan Peminjaman</button></article>
                            <article class="item-card"><div class="item-image"><span class="available">Tersedia</span><i class="bi bi-camera"></i></div><h3>Keyboard Zoom 75</h3><p>Elektronik<br>Kode: CAM-002</p><button><i class="bi bi-clipboard-plus"></i> Ajukan Peminjaman</button></article>
                            <article class="item-card"><div class="item-image"><span class="available">Tersedia</span><i class="bi bi-laptop"></i></div><h3>Laptop Lenovo ideapad Slim 3</h3><p>Elektronik<br>Kode: LAP-003</p><button><i class="bi bi-clipboard-plus"></i> Ajukan Peminjaman</button></article>
                        </div>
                    </div>
                </section>
                <aside class="side-section">
                    <div class="panel"><div class="panel-head"><h2>Peminjaman Aktif</h2></div>
                        <div class="active-loan"><div class="loan-head"><strong>Laptop Asus (AL-002)</strong><span class="status blue">DIPINJAM</span></div><span>Dipinjam: 20 Mei 2025</span><br><span class="status red">BATAS KEMBALI 27 Mei 2025</span></div>
                        <div class="active-loan"><div class="loan-head"><strong>Kabel HDMI 5M</strong><span class="status yellow">MENUNGGU</span></div><span>Permintaan Anda sedang ditinjau admin Sarpras.</span></div>
                        <div class="active-loan"><div class="loan-head"><strong>Proyektor Epson</strong><span class="status green">DIKEMBALIKAN</span></div><span>Sudah dikembalikan pada 18 Mei 2025</span></div>
                        <a class="forgot-link" style="display:block;text-align:center;margin-top:12px;" href="#">Lihat Riwayat Lengkap</a>
                    </div>
                    <div class="return-info"><strong><i class="bi bi-info-circle"></i> Informasi Pengembalian</strong><br>Harap kembalikan alat sebelum pukul 15.00 WIB pada tanggal batas pengembalian untuk menghindari denda poin sanksi.</div>
                </aside>
            </div>
        </section>
    </main>
</div>
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
    window.history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);

    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxSpec = document.getElementById('lightbox-spec');

    function renderSpec(data) {
        let specsHtml = '';
        if (data.specs) {
            specsHtml = '<table class="spec-table">' +
                data.specs.map(s => '<tr><td>' + s[0] + '</td><td>' + s[1] + '</td></tr>').join('') +
                '</table>';
        }
        let featuresHtml = '';
        if (data.features) {
            featuresHtml = '<div class="spec-features">' +
                data.features.map(f => '<span>' + f + '</span>').join('') +
                '</div>';
        }
        return '<h2>' + data.name + '</h2>' +
            '<div class="spec-badge">' + data.category + '</div>' +
            '<p class="spec-desc">' + data.desc + '</p>' +
            specsHtml + featuresHtml;
    }

    document.querySelectorAll('.item-image img').forEach(img => {
        img.addEventListener('click', function () {
            lightboxImg.src = this.src;
            lightboxImg.alt = this.alt;
            const card = this.closest('.item-card');
            const specData = card?.getAttribute('data-specs');
            if (specData) {
                lightboxSpec.innerHTML = renderSpec(JSON.parse(specData));
            } else {
                const title = card?.querySelector('h3')?.textContent || this.alt;
                lightboxSpec.innerHTML = '<h2>' + title + '</h2>';
            }
            lightbox.classList.add('active');
        });
    });

    lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
            lightbox.classList.remove('active');
        }
    });
</script>
@include('partials.profile-sync')
</body>
</html>



