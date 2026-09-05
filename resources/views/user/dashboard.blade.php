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
     <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=21">
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
        var isAuthed = {!! Auth::check() ? 'true' : 'false' !!};
        if (!isAuthed) {
            function apply() {
                try {
                    var profile = readJson('sipibs_user_profile', null);
                    var name = profile && (profile.name || profile.nama || '');
                    var nis = profile && (profile.nis || profile.identity_number || '');
                    if (name) {
                        ['top-user-name', 'hero-greeting-name'].forEach(function (id) {
                            var el = document.getElementById(id);
                            if (el) el.textContent = name;
                        });
                        if (nis) {
                            var topUser = document.getElementById('top-user-name');
                            if (topUser && topUser.nextElementSibling) topUser.nextElementSibling.textContent = nis;
                        }
                    }
                } catch (e) {}
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', apply);
            } else {
                apply();
            }
        }
    })();
</script>
<div class="app-shell">
    <aside class="sidebar user-sidebar">
        <div class="sidebar-logo">
            @include('user.partials.sipibs-logo')
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
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>
    <main class="main-area">
        <header class="topbar">
            <div class="search-box"><i class="bi bi-search"></i><input id="dashboardSearch" type="text" placeholder="Cari barang atau riwayat..."></div>
            <div class="top-actions">
                @include('user.partials.notification-bell')
                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar">

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
                <div class="stat-card"><div class="stat-icon"><i class="bi bi-clipboard-check"></i></div><div class="stat-info"><span>Total Pinjam</span><strong id="dashboard-total-pinjam">0</strong><span>Alat</span></div></div>
                <div class="stat-card"><div class="stat-icon"><i class="bi bi-clipboard-data"></i></div><div class="stat-info"><span>Menunggu Persetujuan</span><strong id="dashboard-pending-pinjam">0</strong><span>Pending</span></div></div>
                <div class="stat-card"><div class="stat-icon sky"><i class="bi bi-check2-circle"></i></div><div class="stat-info"><span>Selesai</span><strong id="dashboard-selesai-pinjam">0</strong><span>Barang</span></div></div>
            </div>
            <div class="dash-grid">
                <section>
                    <div class="panel">
                        <div class="catalog-head">
                            <div class="catalog-title"><h2>Daftar Barang</h2><p>Daftar semua barang yang tersedia di sekolah</p></div>
                        </div>
                        <div class="item-grid">
                            <article class="item-card" data-specs='{"name":"HP USB 2-Button Optical Mouse","category":"Elektronik","desc":"Mouse standar HP dengan desain simetris/ambidextrous, nyaman digunakan tangan kiri atau kanan. Cocok untuk penggunaan sehari-hari di workstation.","specs":[["Koneksi","USB Dongle 2.4GHz / Bluetooth 5.1 / Kabel USB"],["Sensor","Optik, 1600 DPI"],["Desain","Simetris / Ambidextrous"],["Tombol","3 Tombol (Klik Kiri, Kanan, Scroll Wheel)"],["Baterai","AA/AAA, hingga 12-15 bulan"],["Kompatibilitas","Windows 11/10/8/7, Mac, Chrome OS"]],"features":["Ambidextrous","Plug & Play","Daya Tahan Baterai Lama","Multi OS"]}'><div class="item-image"><span class="available">Tersedia</span><img src="{{ asset('images/mouse HP.png') }}" alt="HP USB 2-Button Optical Mouse" style="width:100%;height:100%;object-fit:contain;background:#fff;padding:6px;box-sizing:border-box;"></div><h3>Mouse HP USB-2</h3><p>Elektronik<br>Kode: MS-001</p><a href="{{ url('/peminjaman-user?barang=' . urlencode('Mouse HP USB-2')) }}"><i class="bi bi-clipboard-plus"></i> Ajukan Peminjaman</a></article>
                            <article class="item-card" data-specs='{"name":"Keyboard NuPhy Air75","category":"Elektronik","desc":"Keyboard mekanik nirkabel berprofil tipis (low-profile) dengan tata letak 75%. Terkenal dengan desain estetis, bodi sangat tipis, dan kompatibilitas tinggi untuk Windows serta macOS.","specs":[["Tombol","84 Tombol (Layout 75%)"],["Koneksi","Wireless 2.4GHz / Bluetooth 5.1 / USB Type-C"],["Baterai","4000mAh, hingga ratusan jam"],["Backlight","RGB LED + Side Light"],["Hot-Swappable","Ya (switch profil rendah)"],["Keycaps","PBT Double-shot"],["Kompatibilitas","Windows, macOS, Linux, Android, iOS"]],"features":["Low-Profile Design","Hot-Swappable","RGB LED","Multi-Device"]}'><div class="item-image"><span class="available">Tersedia</span><img src="{{ asset('images/keyboard kantor.png') }}" alt="NuPhy Air75" style="width:100%;height:100%;object-fit:contain;background:#fff;padding:6px;box-sizing:border-box;"></div><h3><i class=""></i>Keyboard NuPhy Air75</h3><p>Elektronik<br>Kode: KBD-004</p><a href="{{ url('/peminjaman-user?barang=' . urlencode('Keyboard NuPhy Air75')) }}"><i class="bi bi-clipboard-plus"></i> Ajukan Peminjaman</a></article>
                            <article class="item-card" data-specs='{"name":"Lenovo IdeaPad Slim 3","category":"Elektronik","desc":"Laptop harian yang tipis dengan layar 14 inci, pilihan prosesor Intel atau AMD, RAM hingga 16GB, serta penyimpanan SSD 512GB. Cocok untuk produktivitas dan penggunaan sehari-hari.","specs":[["Layar","14 inci Full HD 1920x1080, Anti-Glare"],["Prosesor","Intel Core i3/i5/i7 atau AMD Ryzen 3/5 (generasi baru)"],["Grafis","Intel UHD/Iris Xe atau AMD Radeon"],["RAM","8GB / 16GB DDR4 / LPDDR5"],["Penyimpanan","256GB / 512GB SSD M.2 NVMe"],["Konektivitas","USB-A, USB-C, HDMI, Card Reader"],["Audio & Kamera","Dolby Audio, Kamera Privasi Penutup Manual"]],"features":["Tipis & Ringan","Layar Anti-Glare","Dolby Audio","Privacy Camera"]}'><div class="item-image"><span class="available">Tersedia</span><img src="{{ asset('images/Lenovo Ideapad Slim 3.jpg') }}" alt="Laptop Lenovo Ideapad Slim 3" style="width:100%;height:100%;object-fit:contain;background:#fff;padding:6px;box-sizing:border-box;"></div><h3>Laptop Lenovo Ideapad Slim 3</h3><p>Elektronik<br>Kode: LAP-003</p><a href="{{ url('/peminjaman-user?barang=' . urlencode('Laptop Lenovo Ideapad Slim 3')) }}"><i class="bi bi-clipboard-plus"></i> Ajukan Peminjaman</a></article>
                        </div>
                    </div>
                </section>
                <aside class="side-section">
                    <div class="panel"><div class="panel-head"><h2>Peminjaman Aktif</h2></div>
                        <div id="dashboard-active-loans"></div>
                        <a class="forgot-link" style="display:block;text-align:center;margin-top:12px;" href="{{ url('/riwayat-pinjam') }}">Lihat Riwayat Lengkap</a>
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

    const dashboardSearch = document.getElementById('dashboardSearch');
    const itemCards = document.querySelectorAll('.item-card');
    dashboardSearch.addEventListener('input', function () {
        const q = dashboardSearch.value.toLowerCase().trim();
        itemCards.forEach(function (card) {
            const text = (card.textContent || '').toLowerCase();
            card.style.display = (!q || text.includes(q)) ? '' : 'none';
        });
    });

    function parseDashboardJson(key, fallback) {
        try {
            const value = JSON.parse(localStorage.getItem(key) || 'null');
            return value || fallback;
        } catch (e) {
            return fallback;
        }
    }

    function normalizeDashboardLoans() {
        const history = parseDashboardJson('sipibsLoanHistory', []);
        const decision = parseDashboardJson('sipibsLoanDecision', null);
        const request = parseDashboardJson('sipibsLoanRequest', null);
        const fallback = decision && decision.request ? decision.request : request;
        const list = Array.isArray(history) ? history.slice() : [];
        if (fallback && fallback.id && !list.some(loan => loan.id === fallback.id)) list.unshift(fallback);
        return list;
    }

    function formatDashboardDate(value) {
        if (!value) return '-';
        if (String(value).includes('/')) return value;
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return value;
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function renderDashboardLoanStats() {
        const loans = normalizeDashboardLoans();
        const totalQty = loans
            .filter(loan => !['rejected', 'ditolak'].includes(String(loan.status || 'pending').toLowerCase()))
            .reduce((sum, loan) => sum + (parseInt(loan.jumlah, 10) || 1), 0);
        const pending = loans.filter(loan => String(loan.status || 'pending').toLowerCase() === 'pending').length;
        const done = loans.filter(loan => ['returned', 'dikembalikan', 'selesai'].includes(String(loan.status || '').toLowerCase())).length;

        const totalEl = document.getElementById('dashboard-total-pinjam');
        const pendingEl = document.getElementById('dashboard-pending-pinjam');
        const doneEl = document.getElementById('dashboard-selesai-pinjam');
        if (totalEl) totalEl.textContent = totalQty;
        if (pendingEl) pendingEl.textContent = pending;
        if (doneEl) doneEl.textContent = done;
    }

    function renderDashboardActiveLoans() {
        const target = document.getElementById('dashboard-active-loans');
        if (!target) return;
        const loans = normalizeDashboardLoans().filter(loan => {
            const status = String(loan.status || 'pending').toLowerCase();
            return !['returned', 'dikembalikan', 'rejected', 'ditolak'].includes(status);
        }).slice(0, 5);

        if (!loans.length) {
            target.innerHTML = '<div class="active-loan"><span>Belum ada peminjaman aktif.</span></div>';
            return;
        }

        const labels = {
            pending: ['MENUNGGU', 'yellow', 'Permintaan Anda sedang ditinjau admin Sarpras.'],
            approved: ['DIPINJAM', 'blue', 'Dipinjam: '],
            dipinjam: ['DIPINJAM', 'blue', 'Dipinjam: ']
        };

        target.innerHTML = loans.map(loan => {
            const status = String(loan.status || 'pending').toLowerCase();
            const label = labels[status] || labels.pending;
            const dateText = status === 'pending'
                ? label[2]
                : label[2] + formatDashboardDate(loan.tanggalPinjam);
            const dueText = status === 'pending'
                ? ''
                : '<br><span class="status red">BATAS KEMBALI ' + formatDashboardDate(loan.tanggalKembali) + '</span>';
            return '<div class="active-loan"><div class="loan-head"><strong>' + (loan.barang || '-') + '</strong><span class="status ' + label[1] + '">' + label[0] + '</span></div><span>' + dateText + '</span>' + dueText + '</div>';
        }).join('');
    }

    function renderDashboardLoans() {
        renderDashboardLoanStats();
        renderDashboardActiveLoans();
    }

    renderDashboardLoans();
    window.addEventListener('storage', function (event) {
        if (['sipibsLoanHistory', 'sipibsLoanDecision', 'sipibsLoanRequest'].includes(event.key)) renderDashboardLoans();
    });
</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>



