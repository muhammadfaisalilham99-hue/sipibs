<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Alat - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=23">
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
        .catalog-card-image img { cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .catalog-card-image img:hover { transform: scale(1.03); box-shadow: 0 4px 16px rgba(0,0,0,0.15); }
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
    $tools = [
        ['name' => 'Mouse HP USB-2', 'cat' => 'Elektronik', 'code' => 'PRJ-001', 'stock' => '5 / 5', 'icon' => 'bi-mouse', 'image' => 'mouse HP.png', 'desc' => 'Mouse optik USB HP berdesain simetris, presisi dan nyaman digunakan untuk kegiatan harian.'],
        ['name' => 'Keyboard NuPhy Air75', 'cat' => 'Elektronik', 'code' => 'CAM-002', 'stock' => '3 / 3', 'icon' => 'bi-keyboard', 'image' => 'keyboard kantor.png', 'desc' => 'Keyboard mekanik nirkabel 75% profil tipis dengan switch responsif dan koneksi ganda.'],
        ['name' => 'Laptop Lenovo Ideapad Slim 3', 'cat' => 'Elektronik', 'code' => 'LAP-003', 'stock' => '12 / 12', 'icon' => 'bi-laptop', 'image' => 'Lenovo Ideapad Slim 3.jpg', 'desc' => 'Laptop 14 inci berkinerja tinggi, tipis dan ringan untuk mendukung kegiatan belajar mengajar.'],
        ['name' => 'Headset Logitech G 432 7.1', 'cat' => 'Audio Visual', 'code' => 'SPK-004', 'stock' => '8 / 8', 'icon' => 'bi-headphones', 'image' => 'HEADSET LOGITECH.png', 'desc' => 'Headset audio 7.1 surround dengan earcup nyaman dan mikrofon penyaring kebisingan.'],
        ['name' => '4K Webcam 1080P 60fps Mini Video Camera', 'cat' => 'Elektronik', 'code' => 'TRI-005', 'stock' => '15 / 15', 'icon' => 'bi-camera-video', 'image' => 'WEBCAM.jpg', 'desc' => 'Kamera webcam Full HD 1080p 60fps dengan fitur autofokus untuk meeting dan kelas online.'],
        ['name' => 'Epson EX3240 SVGA 3LCD Projector 3200', 'cat' => 'Praktikum', 'code' => 'MIK-006', 'stock' => '4 / 4', 'icon' => 'bi-easel', 'image' => 'PROYEKTOR EPSON.jpg', 'desc' => 'Projector 3LCD 3200 lumens dengan warna terang tajam untuk presentasi di ruang kelas.'],
        ['name' => 'Kabel Black High Speed 1.4 Version Gold-Plated HDMI', 'cat' => 'Peralatan Kantor', 'code' => 'GLO-007', 'stock' => '20 / 20', 'icon' => 'bi-usb-plug', 'image' => 'kabel_hdmi.png', 'desc' => 'Kabel HDMI High Speed dengan konektor lapis emas mendukung transmisi sinyal HD stabil.'],
        ['name' => 'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin', 'cat' => 'Peralatan Kantor', 'code' => 'BOL-008', 'stock' => '10 / 10', 'icon' => 'bi-plug', 'image' => 'kabel vga.jpg', 'desc' => 'Kabel VGA 15 pin male to male berkulit tebal untuk koneksi monitor dan projector.'],
        ['name' => 'Kabel LAN CAT6 UTP Cable Networking', 'cat' => 'Peralatan Kantor', 'code' => 'RKT-009', 'stock' => '6 / 6', 'icon' => 'bi-ethernet', 'image' => 'KABEL LAN.jpg', 'desc' => 'Kabel jaringan UTP CAT6 berkecepatan tinggi untuk sambungan internet dan LAN lab.'],
        ['name' => 'Kabel HDMI to VGA Adapter Gold Plated', 'cat' => 'Peralatan Kantor', 'code' => 'PPT-010', 'stock' => '2 / 2', 'icon' => 'bi-usb-c', 'image' => 'KABEL HDMI TO VGA.jpg', 'desc' => 'Konverter HDMI male ke VGA female untuk menghubungkan laptop modern ke projector.'],
        ['name' => 'Pen Wireless Remote Controller Laser Pointer', 'cat' => 'Elektronik', 'code' => 'MS-011', 'stock' => '7 / 7', 'icon' => 'bi-mouse', 'image' => 'pointer.jpg', 'desc' => 'Material: ABS + PC Color black Connect mode: 2.4G wireless connection 2.4 GHZ control distance: about 100M.'],
        ['name' => 'Stop Kontak', 'cat' => 'Elektronik', 'code' => 'PRJ-012', 'stock' => '4 / 4', 'icon' => 'bi-easel', 'image' => 'STOP KONTAK.jpg', 'desc' => 'STOP KONTAK 4 LUBANG 3 METER SAKLAR COLOKAN LISTRIK 4 LUBANG 3 METER Spesifikasi Produk: * Stopkontak : 4 Lubang. * Kabel : 3 Meter..'],
        ['name' => '2pcs Multifunctional network tester 468 network cable', 'cat' => 'Peralatan Kantor', 'code' => 'HDM-013', 'stock' => '14 / 14', 'icon' => 'bi-usb-plug', 'image' => 'LAN tester.jpg', 'desc' => 'Computer network cable tester RJ45 + R11 tester Product parameters Name: multifunctional tester Function: special equipment Size: 110mm * 105mm Material.'],
        ['name' => 'Tang Crimping Tool RJ45 RJ11 HT-200R', 'cat' => 'Peralatan Kantor', 'code' => 'LAN-014', 'stock' => '9 / 9', 'icon' => 'bi-ethernet', 'image' => 'Tang crimping.jpg', 'desc' => 'Tang Crimping Tool RJ45 Rj11 Model : HT-200R Tang Crimping yang Kokoh dan Tahan Lama yang dapat digunakan untuk memasang konektor RJ45 pada kabel LAN / Kabel UTP dan Konektor Rj11 pada Kabel Telpon..'],
        ['name' => 'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth', 'cat' => 'ELEKTRONIK', 'code' => 'ADP-015', 'stock' => '5 / 5', 'icon' => 'bi-usb-c', 'image' => 'speaker portable.jpg', 'desc' => 'JBL Boombox 3 Speaker Massive sound and deepest bass Enjoy your music with massive JBL Original Pro Sound.'],
    ];
    $firstSlideCount = 10;
    $itemsPerSlide = $firstSlideCount;
    $chunks = [array_slice($tools, 0, $firstSlideCount), array_slice($tools, $firstSlideCount)];
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
            <a class="nav-item open" href="#" id="inventaris-toggle"><i class="bi bi-box-seam"></i> Inventaris <span class="nav-arrow">^</span></a>
            <div class="nav-sub-list" id="inventaris-sub">
                <a class="nav-sub-item active" href="{{ url('/katalog-alat') }}">Katalog Barang</a>
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
            <div class="search-box"><i class="bi bi-search"></i><input id="catalogSearch" type="text" placeholder="Cari nama alat, kategori, atau kode..."></div>
            <div class="top-actions">
                @include('user.partials.notification-bell')

                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar">

                </div>
            </div>
        </header>
        <section class="content catalog-page-content">
            <div class="catalog-page-head">
                <div>
                    <div class="catalog-breadcrumb"><i class="bi bi-house"></i> / Inventaris / <span>Katalog Alat</span></div>
                    <h1>Katalog Alat</h1>
                    <p>Daftar semua barang yang tersedia di sekolah</p>
                </div>
            </div>

            <div class="catalog-slides-container">
                @foreach ($chunks as $slideIndex => $slideTools)
                    <div class="catalog-full-grid catalog-slide @if($slideIndex === 0) active @endif" id="slide-{{ $slideIndex + 1 }}" @if($slideIndex > 0) style="display: none;" @endif>
                        @foreach ($slideTools as $tool)
                            <article class="catalog-card" data-item-name="{{ $tool['name'] }}">
                                <div class="catalog-card-image">
                                    @if(!empty($tool['image']))
                                        <img src="{{ asset('images/' . $tool['image']) }}" alt="{{ $tool['name'] }}">
                                    @else
                                        <i class="bi {{ $tool['icon'] }}"></i>
                                    @endif
                                </div>
                                <div class="catalog-card-status">
                                    <span class="available catalog-status-label">TERSEDIA</span>
                                    <span class="stock-label catalog-stock-label" data-initial-stock="{{ $tool['stock'] }}">Tersedia: {{ $tool['stock'] }}</span>
                                </div>
                                <div class="catalog-card-body">
                                    <h3>{{ $tool['name'] }}</h3>
                                    <div class="catalog-card-meta">
                                        <strong>{{ $tool['cat'] }}</strong><br>
                                        Kode: {{ $tool['code'] }}
                                    </div>
                                    <p class="catalog-card-desc">{{ $tool['desc'] }}</p>
                                    <a class="catalog-loan-btn" href="{{ url('/peminjaman-user?barang=' . urlencode($tool['name'])) }}"><i class="bi bi-file-earmark-plus"></i> Ajukan<br>Peminjaman</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="catalog-pagination-bar">
                <div class="pagination-info" id="pagination-info">
                    Menampilkan 1-{{ count($chunks[0]) }} dari {{ count($tools) }} barang
                </div>
                <div class="pagination-controls">
                    <button class="page-btn page-arrow disabled" id="prev-page" type="button"><i class="bi bi-chevron-left"></i></button>
                    @foreach ($chunks as $slideIndex => $slideTools)
                        <button class="page-btn @if($slideIndex === 0) active @endif" type="button" data-slide="{{ $slideIndex + 1 }}">{{ $slideIndex + 1 }}</button>
                    @endforeach
                    <button class="page-btn page-arrow" id="next-page" type="button"><i class="bi bi-chevron-right"></i></button>
                </div>
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
    } else if (localStorage.getItem('invOpen') === '0') {
        invSub.classList.add('collapsed');
        invToggle.classList.remove('open');
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

    function initCatalogStock() {
        const stocks = JSON.parse(localStorage.getItem('sipibsItemStock') || '{}');
        document.querySelectorAll('.catalog-card').forEach(card => {
            const name = card.dataset.itemName;
            const stockLabel = card.querySelector('.catalog-stock-label');
            const statusLabel = card.querySelector('.catalog-status-label');
            if (!stockLabel || !name) return;

            const initial = stockLabel.dataset.initialStock;
            const parts = initial.split('/').map(s => s.trim());
            let total = parseInt(parts[1]) || 0;
            let available = stocks[name] !== undefined ? stocks[name] : parseInt(parts[0]) || total;

            stockLabel.textContent = 'Tersedia: ' + available + ' / ' + total;

            if (available === 0) {
                card.querySelector('.catalog-card-status').className = 'catalog-card-status';
                statusLabel.className = 'available catalog-status-label';
                statusLabel.textContent = 'KOSONG';
                statusLabel.style.background = '#fee2e2';
                statusLabel.style.color = '#dc2626';
                const btn = card.querySelector('.catalog-loan-btn');
                if (btn) { btn.style.opacity = '0.5'; btn.style.pointerEvents = 'none'; }
            } else if (available <= Math.floor(total / 2)) {
                statusLabel.textContent = 'STOK RENDAH';
                statusLabel.style.background = '#fef3c7';
                statusLabel.style.color = '#d97706';
            } else {
                statusLabel.textContent = 'TERSEDIA';
                statusLabel.style.background = '';
                statusLabel.style.color = '';
            }
        });
    }

    initCatalogStock();

    const slides = document.querySelectorAll('.catalog-slide');
    const pageBtns = document.querySelectorAll('.pagination-controls .page-btn[data-slide]');
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    const paginationInfo = document.getElementById('pagination-info');
    const slideItemCounts = @json(array_map('count', $chunks));
    const totalItems = {{ count($tools) }};
    let currentSlide = 1;
    const totalSlides = slides.length;

    function showSlide(index) {
        if (index < 1 || index > totalSlides) return;
        currentSlide = index;
        slides.forEach((slide, i) => {
            slide.style.display = (i + 1 === currentSlide) ? 'grid' : 'none';
        });
        pageBtns.forEach(btn => {
            btn.classList.toggle('active', parseInt(btn.dataset.slide) === currentSlide);
        });
        if (prevBtn) {
            prevBtn.disabled = currentSlide === 1;
            prevBtn.classList.toggle('disabled', currentSlide === 1);
        }
        if (nextBtn) {
            nextBtn.disabled = currentSlide === totalSlides;
            nextBtn.classList.toggle('disabled', currentSlide === totalSlides);
        }
        if (paginationInfo) {
            const startItem = slideItemCounts.slice(0, currentSlide - 1).reduce((total, count) => total + count, 0) + 1;
            const endItem = slideItemCounts.slice(0, currentSlide).reduce((total, count) => total + count, 0);
            paginationInfo.textContent = `Menampilkan ${startItem}-${endItem} dari ${totalItems} barang`;
        }
    }

    pageBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            showSlide(parseInt(this.dataset.slide));
        });
    });
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            showSlide(currentSlide - 1);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            showSlide(currentSlide + 1);
        });
    }
    window.history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);

    const catalogTools = @json($tools);
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxSpec = document.getElementById('lightbox-spec');

    function renderCatalogSpec(tool) {
        let specsHtml = '<table class="spec-table"><tr><td>Nama</td><td>' + tool.name + '</td></tr><tr><td>Kategori</td><td>' + tool.cat + '</td></tr><tr><td>Kode</td><td>' + tool.code + '</td></tr></table>';
        return '<h2>' + tool.name + '</h2>' +
            '<div class="spec-badge">' + tool.cat + '</div>' +
            '<p class="spec-desc">' + tool.desc + '</p>' +
            specsHtml;
    }

    document.querySelectorAll('.catalog-card-image img').forEach(function(img) {
        img.addEventListener('click', function() {
            lightboxImg.src = this.src;
            lightboxImg.alt = this.alt;
            const card = this.closest('.catalog-card');
            const itemName = card?.dataset.itemName;
            const toolData = catalogTools.find(function(t) { return t.name === itemName; });
            if (toolData) {
                lightboxSpec.innerHTML = renderCatalogSpec(toolData);
            } else {
                lightboxSpec.innerHTML = '<h2>' + (this.alt || '') + '</h2>';
            }
            lightbox.classList.add('active');
        });
    });

    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
            lightbox.classList.remove('active');
        }
    });

    const catalogSearch = document.getElementById('catalogSearch');
    const allCards = Array.from(document.querySelectorAll('.catalog-card'));
    catalogSearch.addEventListener('input', function () {
        const q = catalogSearch.value.toLowerCase().trim();
        if (!q) {
            allCards.forEach(function (card) { card.style.display = ''; });
            showSlide(currentSlide);
            return;
        }
        slides.forEach(function (slide) { slide.style.display = 'grid'; });
        let matchCount = 0;
        allCards.forEach(function (card) {
            const text = (card.textContent || '').toLowerCase();
            const match = text.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) matchCount++;
        });
        if (paginationInfo) {
            paginationInfo.textContent = matchCount
                ? 'Menampilkan 1-' + matchCount + ' dari ' + matchCount + ' hasil untuk "' + catalogSearch.value + '"'
                : 'Tidak ada hasil untuk "' + catalogSearch.value + '"';
        }
    });
</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>






