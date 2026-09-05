@php
    $active = 'laporan';
    $title = 'Laporan Inventaris Sekolah';
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
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=25">
    <style>
        .print-report-title { display:none; }
        @media print {
            @page { size: A4 portrait; margin: 14mm; }
            body, html { background:#fff !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            aside.sidebar, .topbar, .report-stats-grid, .report-actions, .notification-bell,
            .report-panel-search, .report-pager-row, .sidebar-scroll, .pager {
                display:none !important;
            }
            .main-area, .content, .admin-wrap { margin:0 !important; padding:0 !important; background:#fff !important; width:100% !important; box-shadow:none !important; }
            .report-header, .report-panel { border:0 !important; box-shadow:none !important; padding:0 !important; margin:0 0 12px !important; background:#fff !important; }
            .report-title { font-size:18px !important; margin:0 0 2px !important; }
            .report-subtitle { font-size:11px !important; margin:0 0 14px !important; }
            .print-report-title {
                display:flex; justify-content:space-between; align-items:baseline;
                border-bottom:2px solid #111; padding-bottom:6px; margin-bottom:10px;
                font-size:13px; color:#111;
            }
            .print-report-title strong { font-size:15px; }
            .report-panel-head { display:none !important; }
            table.report-table { width:100% !important; border-collapse:collapse !important; font-size:10px !important; color:#111 !important; }
            table.report-table th {
                background:#111 !important; color:#fff !important;
                padding:5px 6px !important; text-align:left !important;
                font-size:9px !important; letter-spacing:.4px !important;
            }
            table.report-table td { padding:4px 6px !important; border-bottom:1px solid #ddd !important; vertical-align:middle !important; }
            table.report-table tr { page-break-inside:avoid !important; }
            table.report-table tbody tr:nth-child(even) td { background:#f7f7f7 !important; }
            .report-thumb { width:34px !important; height:34px !important; }
            .report-tiny-cat, .code-link, .status-badge, .condition-dot-text { color:#111 !important; }
            .status-badge.green { background:#d7f5e3 !important; color:#14532d !important; border:1px solid #9adbb6 !important; }
            .status-badge.yellow { background:#fdf3d2 !important; color:#713f12 !important; border:1px solid #f2d98c !important; }
            .status-badge.red { background:#fde0e0 !important; color:#7f1d1d !important; border:1px solid #f5bcbc !important; }
        }
    </style>
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

    <main class="main-area">
        <section class="content">
            <div class="admin-wrap">
                <!-- Header Title & Export Buttons -->
                <div class="report-header">
                    <div>
                        <h1 class="report-title">Laporan Inventaris Sekolah</h1>
                        <p class="report-subtitle">Analisis komprehensif aset dan pergerakan stok barang sekolah.</p>
                    </div>
                    <div class="report-actions">
@include('admin.partials.notification-bell')
                        <button type="button" class="btn-report-pdf" id="exportPdfBtn">
                            <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                        </button>
                    </div>
                </div>

                <!-- 4 Stat Cards Matching Reference Image -->
                <div class="report-stats-grid">
                    <div class="report-stat-card">
                        <div class="stat-icon-box shapes-bg">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 3l4.5 7.5H2.5L7 3z"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <circle cx="17.5" cy="17.5" r="3.5"/>
                            </svg>
                        </div>
                        <div class="stat-text-box">
                            <small>Total Aset</small>
                            <strong id="totalAssetCount">2,840</strong>
                        </div>
                    </div>

                    <div class="report-stat-card">
                        <div class="stat-icon-box mail-bg">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="6" width="13" height="11" rx="2"/>
                                <path d="M2 7.5l6.5 4.5L15 7.5"/>
                                <path d="M14 12h7m-3-3l3 3-3 3"/>
                            </svg>
                        </div>
                        <div class="stat-text-box">
<small>Aset Terpinjam</small>
                            <strong id="asetTerpinjam">0</strong>
                        </div>
                    </div>

                    <div class="report-stat-card">
<div class="stat-icon-box green-bg">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2l8 3v6c0 5-3.5 8.5-8 11-4.5-2.5-8-6-8-11V5l8-3z"/>
                                <path d="M8.5 12l2.5 2.5 4.5-4.5"/>
                            </svg>
                        </div>
                        <div class="stat-text-box">
                            <small>Kondisi Baik</small>
                            <strong id="kondisiBaik">0</strong>
                        </div>
                    </div>

                    <div class="report-stat-card">
<div class="stat-icon-box warning-bg">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2l8 3v6c0 5-3.5 8.5-8 11-4.5-2.5-8-6-8-11V5l8-3z"/>
                                <path d="M9.5 9.5l5 5M14.5 9.5l-5 5"/>
                            </svg>
                        </div>
                        <div class="stat-text-box">
                            <small>Kondisi Rusak</small>
                            <strong id="kondisiRusak">0</strong>
                        </div>
                    </div>
                </div>

                <!-- Laporan Stok Barang Panel -->
                <div class="report-panel">
                    <div class="report-panel-head">
                        <h2>Laporan Stok Barang</h2>
                        <div class="report-panel-search">
                            <div class="report-search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" id="reportSearchInput" placeholder="Cari aset...">
                            </div>
                            <button type="button" class="report-filter-btn" id="reportFilterBtn" title="Filter Status Stok">
                                <i class="bi bi-sliders"></i>
                            </button>
                        </div>
                    </div>

                    <div class="print-report-title">
                        <strong>Laporan Inventaris Sekolah</strong>
                        <span id="printReportDate"></span>
                    </div>
                    <table class="report-table">
<thead>
                            <tr>
                                <th>KODE ASET</th>
                                <th>FOTO</th>
                                <th>NAMA BARANG</th>
                                <th>STATUS STOK</th>
                                <th>TOTAL</th>
                                <th>TERPINJAM</th>
                                <th>KONDISI</th>
                            </tr>
                        </thead>
                        <tbody id="reportTableBody"></tbody>
                    </table>

                    <div class="report-pager-row">
                        <span id="reportPagerInfo">Menampilkan 4 dari 1,240 item</span>
                        <div class="pager" id="reportPager"></div>
                    </div>
                </div>

            </div>
        </section>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
const catalogItems = [
            { code: 'PRJ-001', name: 'Mouse HP USB-2', category: 'Elektronik', total: 5, image: 'mouse HP.png' },
            { code: 'CAM-002', name: 'Keyboard NuPhy Air75', category: 'Elektronik', total: 3, image: 'keyboard kantor.png' },
            { code: 'LAP-003', name: 'Laptop Lenovo Ideapad Slim 3', category: 'Elektronik', total: 12, image: 'Lenovo Ideapad Slim 3.jpg' },
            { code: 'SPK-004', name: 'Headset Logitech G 432 7.1', category: 'Audio Visual', total: 8, image: 'HEADSET LOGITECH.png' },
            { code: 'TRI-005', name: '4K Webcam 1080P 60fps Mini Video Camera', category: 'Elektronik', total: 15, image: 'WEBCAM.jpg' },
            { code: 'MIK-006', name: 'Epson EX3240 SVGA 3LCD Projector 3200', category: 'Praktikum', total: 4, image: 'PROYEKTOR EPSON.jpg' },
            { code: 'GLO-007', name: 'Kabel Black High Speed 1.4 Version Gold-Plated HDMI', category: 'Peralatan Kantor', total: 20, image: 'kabel_hdmi.png' },
            { code: 'BOL-008', name: 'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin', category: 'Peralatan Kantor', total: 10, image: 'kabel vga.jpg' },
            { code: 'RKT-009', name: 'Kabel LAN CAT6 UTP Cable Networking', category: 'Peralatan Kantor', total: 6, image: 'KABEL LAN.jpg' },
            { code: 'PPT-010', name: 'Kabel HDMI to VGA Adapter Gold Plated', category: 'Peralatan Kantor', total: 2, image: 'KABEL HDMI TO VGA.jpg' },
            { code: 'MS-011', name: 'Pen Wireless Remote Controller Laser Pointer', category: 'Elektronik', total: 7, image: 'pointer.jpg' },
            { code: 'PRJ-012', name: 'Stop Kontak', category: 'Elektronik', total: 4, image: 'STOP KONTAK.jpg' },
            { code: 'HDM-013', name: '2pcs Multifunctional network tester 468 network cable', category: 'Peralatan Kantor', total: 14, image: 'LAN tester.jpg' },
            { code: 'LAN-014', name: 'Tang Crimping Tool RJ45 RJ11 HT-200R', category: 'Peralatan Kantor', total: 9, image: 'Tang crimping.jpg' },
            { code: 'ADP-015', name: 'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth', category: 'Elektronik', total: 5, image: 'speaker portable.jpg' }
        ];

        function getLiveStock(name, total) {
            let available = total;
            try {
                const stocks = JSON.parse(localStorage.getItem('sipibsItemStock') || '{}');
                if (stocks[name] !== undefined) available = Math.max(0, parseInt(stocks[name]) || 0);
            } catch (e) {}
            const borrowed = Math.max(0, total - available);
            const status = available <= 0 ? 'Kosong' : (available <= 2 ? 'Hampir Habis' : 'Tersedia');
            return { available: available, borrowed: borrowed, status: status };
        }

        const reportData = catalogItems.map(item => {
            const live = getLiveStock(item.name, item.total);
            return {
                code: item.code,
                name: item.name,
                category: item.category,
                status: live.status,
                total: item.total,
                borrowed: live.borrowed,
                condition: 'Baik (100%)',
                conditionState: 'green',
                image: item.image
            };
        });

        const IMG_BASE = "{{ asset('images') }}";

        const perPage = 4;
        let currentPage = 1;
        let activeStatus = 'Semua';
        const statuses = ['Semua', 'Tersedia', 'Hampir Habis', 'Kosong'];

        const searchInput = document.getElementById('reportSearchInput');
        const filterBtn = document.getElementById('reportFilterBtn');
        const tableBody = document.getElementById('reportTableBody');
        const pager = document.getElementById('reportPager');
        const pagerInfo = document.getElementById('reportPagerInfo');

        function statusBadge(status) {
            if (status === 'Tersedia') return '<span class="status-badge green">Tersedia</span>';
            if (status === 'Hampir Habis') return '<span class="status-badge yellow">Hampir Habis</span>';
            return '<span class="status-badge red">Kosong</span>';
        }

        function conditionBadge(text, state) {
            let dotColor = '#22c55e';
            if (state === 'yellow') dotColor = '#eab308';
            if (state === 'red') dotColor = '#ef4444';
            return `<span class="condition-dot-text"><span class="c-dot" style="background:${dotColor}"></span>${text}</span>`;
        }

        function filteredData() {
            const keyword = searchInput.value.toLowerCase().trim();
            return reportData.filter(item => {
                const matchStatus = activeStatus === 'Semua' || item.status === activeStatus;
                const matchSearch = `${item.code} ${item.name} ${item.category} ${item.status}`.toLowerCase().includes(keyword);
                return matchStatus && matchSearch;
            });
        }

function rowHtml(r) {
            return `
                <tr>
                    <td><strong class="code-link">${r.code}</strong></td>
                    <td><img class="report-thumb" src="${IMG_BASE}/${encodeURIComponent(r.image)}" alt="${r.name}" onerror="this.style.display='none'"></td>
                    <td>
                        <strong>${r.name}</strong>
                        <span class="report-tiny-cat">${r.category}</span>
                    </td>
                    <td>${statusBadge(r.status)}</td>
                    <td>${r.total}</td>
                    <td>${r.borrowed}</td>
                    <td>${conditionBadge(r.condition, r.conditionState)}</td>
                </tr>
            `;
        }

        function renderTable() {
            const data = filteredData();
            const totalPages = Math.max(1, Math.ceil(data.length / perPage));
            currentPage = Math.min(currentPage, totalPages);
            const start = (currentPage - 1) * perPage;
            const rows = data.slice(start, start + perPage);

tableBody.innerHTML = rows.map(rowHtml).join('');

            pagerInfo.textContent = rows.length ? `Menampilkan ${rows.length} dari ${data.length} item` : 'Tidak ada data aset';
            renderPager(totalPages);
        }

        function renderPager(totalPages) {
            let html = `<button type="button" ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">‹</button>`;
            for (let page = 1; page <= totalPages; page++) {
                html += `<button type="button" class="${page === currentPage ? 'active' : ''}" data-page="${page}">${page}</button>`;
            }
            html += `<button type="button" ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">›</button>`;
            pager.innerHTML = html;
        }

        pager.addEventListener('click', function (e) {
            const button = e.target.closest('button[data-page]');
            if (!button || button.disabled) return;
            currentPage = Number(button.dataset.page);
            renderTable();
        });

        searchInput.addEventListener('input', function () {
            currentPage = 1;
            renderTable();
        });

        filterBtn.addEventListener('click', function () {
            const currIdx = statuses.indexOf(activeStatus);
            activeStatus = statuses[(currIdx + 1) % statuses.length];
            currentPage = 1;
            this.classList.toggle('active', activeStatus !== 'Semua');
            this.title = `Filter Status: ${activeStatus}`;
            renderTable();
        });

document.getElementById('exportPdfBtn').addEventListener('click', function () {
            const savedHTML = tableBody.innerHTML;
            const savedPage = currentPage;
            const savedStatus = activeStatus;
            currentPage = 1;
            const dateEl = document.getElementById('printReportDate');
            if (dateEl) dateEl.textContent = 'Dicetak: ' + new Date().toLocaleString('id-ID');
            tableBody.innerHTML = filteredData().map(rowHtml).join('');
            try {
                window.print();
            } catch (err) {}
            tableBody.innerHTML = savedHTML;
            currentPage = savedPage;
            activeStatus = savedStatus;
            renderTable();
        });

renderTable();

        const totalBaik = reportData.length;
        const totalRusak = reportData.filter(d => d.conditionState === 'red').length;
        document.getElementById('totalAssetCount').textContent = reportData.length;
        document.getElementById('asetTerpinjam').textContent = 0;
        document.getElementById('kondisiBaik').textContent = totalBaik;
        document.getElementById('kondisiRusak').textContent = totalRusak;
    });
</script>
@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>
