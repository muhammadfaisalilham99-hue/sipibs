<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kondisi Barang - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=25">
</head>
<body>
@php
    $userName = Auth::check() ? Auth::user()->name : 'Dina Atalia';
    $userRole = 'SISWA';
    $page = max(1, min(2, (int) request('slide', 1)));
    $defaultRows = [
        ['name' => 'Mouse HP USB-2', 'room' => 'Lab Komputer', 'code' => 'PRJ-001', 'cat' => 'Elektronik', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-mouse'],
        ['name' => 'Keyboard NuPhy Air75', 'room' => 'Lab Komputer', 'code' => 'CAM-002', 'cat' => 'Elektronik', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-keyboard'],
        ['name' => 'Laptop Lenovo Ideapad Slim 3', 'room' => 'Lab Komputer', 'code' => 'LAP-003', 'cat' => 'Elektronik', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-laptop'],
        ['name' => 'Headset Logitech G 432 7.1', 'room' => 'Lab Multimedia', 'code' => 'SPK-004', 'cat' => 'Audio Visual', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-headphones'],
        ['name' => '4K Webcam 1080P 60fps Mini Video Camera', 'room' => 'Lab Multimedia', 'code' => 'TRI-005', 'cat' => 'Elektronik', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-camera-video'],
        ['name' => 'Epson EX3240 SVGA 3LCD Projector 3200', 'room' => 'Ruang Kelas', 'code' => 'MIK-006', 'cat' => 'Praktikum', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-easel'],
        ['name' => 'Kabel Black High Speed 1.4 Version Gold-Plated HDMI', 'room' => 'Gudang Inventaris', 'code' => 'GLO-007', 'cat' => 'Peralatan Kantor', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-usb-plug'],
        ['name' => 'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin', 'room' => 'Gudang Inventaris', 'code' => 'BOL-008', 'cat' => 'Peralatan Kantor', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-plug'],
        ['name' => 'Kabel LAN CAT6 UTP Cable Networking', 'room' => 'Lab Jaringan', 'code' => 'RKT-009', 'cat' => 'Peralatan Kantor', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-ethernet'],
        ['name' => 'Kabel HDMI to VGA Adapter Gold Plated', 'room' => 'Gudang Inventaris', 'code' => 'PPT-010', 'cat' => 'Peralatan Kantor', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-usb-c'],
        ['name' => 'Pen Wireless Remote Controller Laser Pointer', 'room' => 'Ruang Presentasi', 'code' => 'MS-011', 'cat' => 'Elektronik', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-broadcast-pin'],
        ['name' => 'Stop Kontak', 'room' => 'Gudang Inventaris', 'code' => 'PRJ-012', 'cat' => 'Elektronik', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-outlet'],
        ['name' => '2pcs Multifunctional network tester 468 network cable', 'room' => 'Lab Jaringan', 'code' => 'HDM-013', 'cat' => 'Peralatan Kantor', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-router'],
        ['name' => 'Tang Crimping Tool RJ45 RJ11 HT-200R', 'room' => 'Lab Jaringan', 'code' => 'LAN-014', 'cat' => 'Peralatan Kantor', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-tools'],
        ['name' => 'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth', 'room' => 'Aula Sekolah', 'code' => 'ADP-015', 'cat' => 'Elektronik', 'condition' => 'BAIK', 'status' => 'green', 'icon' => 'bi-speaker'],
    ];
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
            <div class="search-box condition-search"><i class="bi bi-search"></i><input id="conditionSearch" type="text" placeholder="Cari barang atau kode inventaris..."></div>
            <div class="top-actions">
                @include('user.partials.notification-bell')

                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar">
                </div>
            </div>
        </header>
        <section class="content catalog-page-content condition-page-content">
            <div class="catalog-page-head condition-page-head">
                <div>
                    <div class="catalog-breadcrumb">Home <i class="bi bi-chevron-right"></i> Inventaris <i class="bi bi-chevron-right"></i> <span>Kondisi Barang</span></div>
                    <h1>Kondisi Barang</h1>
                    <p>Daftar kondisi terkini dari seluruh inventaris barang sekolah.</p>
                </div>
                <div class="catalog-page-actions">
                    <div style="position:relative;display:inline-block;">
                        <button class="filter-btn" id="filterToggle"><i class="bi bi-filter"></i> Filter</button>
                        <div class="filter-dropdown hidden" id="filterDropdown">
                            <label>Kategori
                                <select id="filterKategori">
                                    <option value="">Semua</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Audio Visual">Audio Visual</option>
                                    <option value="Praktikum">Praktikum</option>
                                    <option value="Peralatan Kantor">Peralatan Kantor</option>
                                </select>
                            </label>
                            <label>Kondisi
                                <select id="filterKondisi">
                                    <option value="">Semua</option>
                                    <option value="BAIK">Baik</option>
                                    <option value="RUSAK RINGAN">Rusak Ringan</option>
                                    <option value="RUSAK BERAT">Rusak Berat</option>
                                </select>
                            </label>
                            <div style="display:flex;gap:8px;margin-top:8px;">
                                <button class="filter-apply-btn" id="filterApply">Terapkan</button>
                                <button class="filter-reset-btn" id="filterReset">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="condition-stats">
                <div class="condition-stat"><span class="condition-stat-icon green"><i class="bi bi-check-circle"></i></span><div>Kondisi Baik<strong id="statBaik">0<small>Items</small></strong></div></div>
                <div class="condition-stat"><span class="condition-stat-icon yellow"><i class="bi bi-exclamation-circle"></i></span><div>Rusak Ringan<strong id="statRingan">0<small>Items</small></strong></div></div>
                <div class="condition-stat"><span class="condition-stat-icon red"><i class="bi bi-x-circle"></i></span><div>Rusak Berat<strong id="statBerat">0<small>Items</small></strong></div></div>
            </div>
            <div class="condition-table-card">
                <table class="admin-table condition-table">
                    <thead><tr><th>Nama Barang</th><th>Kode Barang</th><th>Kategori</th><th>Kondisi</th><th>Aksi</th></tr></thead>
                    <tbody id="conditionTableBody">
                    </tbody>
                </table>
                <div class="pager-row condition-pager-row">
                    <span id="conditionPagerInfo">Menampilkan 1-10 dari 15 barang</span>
                    <div class="pager condition-pager" id="conditionPager"></div>
                </div>
            </div>
        </section>
    </main>
</div>
<script>
    const invToggle = document.getElementById('inventaris-toggle');
    const invSub = document.getElementById('inventaris-sub');
    const CONDITION_DEFAULTS = @json($defaultRows);
    const ITEMS_PER_PAGE = 10;
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

    function conditionToState(cond) {
        const c = String(cond || '').toUpperCase();
        if (c.includes('RUSAK') && !c.includes('RINGAN')) return { label: 'RUSAK BERAT', status: 'red' };
        if (c.includes('RUSAK')) return { label: 'RUSAK RINGAN', status: 'yellow' };
        return { label: 'BAIK', status: 'green' };
    }

    function loadConditionRows() {
        let master = [];
        try { master = JSON.parse(localStorage.getItem('sipibsMasterItems') || '[]'); } catch (e) {}
        return CONDITION_DEFAULTS.map(function (def) {
            const item = master.find(function (m) { return String(m.code).toUpperCase() === String(def.code).toUpperCase(); });
            const cond = conditionToState(item ? item.condition : 'BAIK');
            return Object.assign({}, def, { condition: cond.label, status: cond.status });
        });
    }

    const conditionTableBody = document.getElementById('conditionTableBody');
    const conditionPager = document.getElementById('conditionPager');
    const conditionPagerInfo = document.getElementById('conditionPagerInfo');
    let conditionPage = 1;
    let searchQuery = '';
    let filterCat = '';
    let filterCond = '';

    function renderConditionStats(rows) {
        let baik = 0, ringan = 0, berat = 0;
        rows.forEach(function (r) {
            if (r.status === 'red') berat++;
            else if (r.status === 'yellow') ringan++;
            else baik++;
        });
        document.getElementById('statBaik').innerHTML = baik + '<small>Items</small>';
        document.getElementById('statRingan').innerHTML = ringan + '<small>Items</small>';
        document.getElementById('statBerat').innerHTML = berat + ' <small>Items</small>';
    }

    function filteredConditionRows() {
        const master = loadConditionRows();
        return master.filter(function (r) {
            const q = searchQuery.toLowerCase();
            const matchQ = !q || (r.name + ' ' + r.code + ' ' + r.cat).toLowerCase().includes(q);
            const matchCat = !filterCat || r.cat === filterCat;
            const matchCond = !filterCond || r.condition === filterCond;
            return matchQ && matchCat && matchCond;
        });
    }

    function renderConditionTable() {
        const data = filteredConditionRows();
        renderConditionStats(loadConditionRows());
        const totalPages = Math.max(1, Math.ceil(data.length / ITEMS_PER_PAGE));
        if (conditionPage > totalPages) conditionPage = totalPages;
        const start = (conditionPage - 1) * ITEMS_PER_PAGE;
        const pageRows = data.slice(start, start + ITEMS_PER_PAGE);

        conditionTableBody.innerHTML = pageRows.map(function (r) {
            return `<tr>
                <td><div class="condition-name"><span class="condition-item-icon"><i class="bi ${r.icon}"></i></span><div><strong>${r.name}</strong><small>${r.room}</small></div></div></td>
                <td><span class="condition-code">${r.code}</span></td>
                <td>${r.cat}</td>
                <td><span class="condition-badge ${r.status}"><i class="bi bi-circle-fill"></i> ${r.condition}</span></td>
                <td><a class="condition-action" href="{{ url('/detail-kondisi-barang') }}/${r.code}">${r.status === 'red' ? 'Cek Riwayat' : 'Lihat Detail'}</a></td>
            </tr>`;
        }).join('');

        if (data.length === 0) {
            conditionTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:24px;color:#64748b;">Tidak ada barang yang cocok</td></tr>';
        }

        conditionPagerInfo.textContent = data.length
            ? `Menampilkan ${start + 1}-${Math.min(start + ITEMS_PER_PAGE, data.length)} dari ${data.length} barang`
            : 'Tidak ada hasil';

        let pagerHtml = `<a href="#" class="${conditionPage === 1 ? 'disabled' : ''}" data-page="${conditionPage - 1}"><i class="bi bi-chevron-left"></i></a>`;
        for (let p = 1; p <= totalPages; p++) {
            pagerHtml += `<a href="#" class="${p === conditionPage ? 'active' : ''}" data-page="${p}">${p}</a>`;
        }
        pagerHtml += `<a href="#" class="${conditionPage === totalPages ? 'disabled' : ''}" data-page="${conditionPage + 1}"><i class="bi bi-chevron-right"></i></a>`;
        conditionPager.innerHTML = pagerHtml;
    }

    conditionPager.addEventListener('click', function (e) {
        const link = e.target.closest('a[data-page]');
        if (!link || link.classList.contains('disabled')) return;
        e.preventDefault();
        conditionPage = parseInt(link.dataset.page, 10);
        renderConditionTable();
    });

    const filterToggle = document.getElementById('filterToggle');
    const filterDropdown = document.getElementById('filterDropdown');
    const filterApply = document.getElementById('filterApply');
    const filterReset = document.getElementById('filterReset');
    const conditionSearch = document.getElementById('conditionSearch');

    filterToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        filterDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!filterDropdown.contains(e.target) && e.target !== filterToggle) {
            filterDropdown.classList.add('hidden');
        }
    });

    filterApply.addEventListener('click', function() {
        filterCat = document.getElementById('filterKategori').value;
        filterCond = document.getElementById('filterKondisi').value;
        conditionPage = 1;
        renderConditionTable();
        filterDropdown.classList.add('hidden');
    });

    filterReset.addEventListener('click', function() {
        document.getElementById('filterKategori').value = '';
        document.getElementById('filterKondisi').value = '';
        filterCat = '';
        filterCond = '';
        searchQuery = '';
        conditionSearch.value = '';
        conditionPage = 1;
        renderConditionTable();
        filterDropdown.classList.add('hidden');
    });

    conditionSearch.addEventListener('input', function() {
        searchQuery = conditionSearch.value;
        conditionPage = 1;
        renderConditionTable();
    });

    renderConditionTable();
</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>



