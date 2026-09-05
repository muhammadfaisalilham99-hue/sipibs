<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=19">
</head>
<body>
@php
    $userName = Auth::check() ? Auth::user()->name : 'Rizky Pratama';
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
            <a class="nav-item active" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
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
        <section class="content user-report-content">
            <div class="user-report-head">
                <div>
                    <h1>Laporan Saya</h1>
                    <p>Ringkasan aktivitas peminjaman inventaris Anda.</p>
                </div>
                <div class="user-report-actions">
                    <a href="{{ url('/profil-user') }}" aria-label="Profil"><i class="bi bi-person"></i></a>
                </div>
            </div>

            <div class="user-report-stats">
                <div class="user-report-stat-card">
                    <div class="stat-icon soft-blue"><i class="bi bi-clock-history"></i></div>
                    <p>Total Peminjaman</p>
                    <strong id="statTotalLoans">0</strong>
                </div>
                <div class="user-report-stat-card">
                    <div class="stat-icon soft-blue"><i class="bi bi-gem"></i></div>
                    <span class="stat-tag">Aktif</span>
                    <p>Barang Masih Dipinjam</p>
                    <strong id="statActiveLoans">0</strong>
                </div>
                <div class="user-report-stat-card">
                    <div class="stat-icon soft-red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <span class="stat-tag red">Perhatian</span>
                    <p>Keterlambatan</p>
                    <strong id="statLateLoans">0</strong>
                </div>
            </div>

            <div class="user-report-grid">
                <section class="user-report-card user-history-card">
                    <div class="user-card-head">
                        <h2>Riwayat Peminjaman</h2>
                        <div class="history-tools">
                            <button type="button" id="historyFilterBtn" title="Filter status"><i class="bi bi-filter"></i></button>
                            <button type="button" id="historySearchBtn" title="Cari riwayat"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                    <div class="history-search-row" id="historySearchRow">
                        <input type="text" id="historySearchInput" placeholder="Cari nama barang...">
                    </div>
                    <table class="history-loan-table">
                        <thead>
                            <tr><th>No</th><th>Peminjam</th><th>Barang</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr>
                        </thead>
                        <tbody id="historyTableBody"></tbody>
                    </table>
                    <button type="button" class="user-see-all" id="seeAllHistoryBtn">Lihat Semua Riwayat</button>
                </section>

                <aside class="user-report-side">
                    <section class="user-report-card monthly-stat-card">
                        <h2>Statistik Peminjaman Bulanan</h2>
                        <div id="monthlyStatsBody">
                            <p class="monthly-empty" style="color:#8a94a6;font-size:13px;margin:8px 0 16px;">Belum ada data pinjaman.</p>
                        </div>
                        <div class="trend-month"><span>Trend Bulan Ini</span><strong id="monthlyTrend">-</strong></div>
                    </section>
                </aside>
            </div>
        </section>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let showAll = false;
        let activeStatus = 'Semua';
        const statuses = ['Semua', 'Selesai', 'Dipinjam', 'Terlambat', 'Menunggu'];
        const tableBody = document.getElementById('historyTableBody');
        const searchRow = document.getElementById('historySearchRow');
        const searchInput = document.getElementById('historySearchInput');
        const filterBtn = document.getElementById('historyFilterBtn');
        const seeAllBtn = document.getElementById('seeAllHistoryBtn');

        function getHistoryData() {
            var history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
            var decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
            var request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
            var fallback = decision && decision.request ? decision.request : request;
            var list = history.length ? history : (fallback ? [fallback] : []);
            if (fallback && fallback.id && !list.some(function (x) { return x.id === fallback.id; })) list.unshift(fallback);
            var statusMap = {
                approved: ['Dipinjam', 'blue'],
                rejected: ['Ditolak', 'red'],
                pending: ['Menunggu', 'orange'],
                returned: ['Dikembalikan', 'green'],
                'dikembalikan': ['Dikembalikan', 'green']
            };
            return list.map(function (loan) {
                var raw = String(loan.status || 'approved').toLowerCase();
                var s = statusMap[raw] || (raw === 'dipinjam' ? statusMap.approved : statusMap.pending);
                var late = isLoanLate(loan.tanggalKembali) && (raw !== 'pending' && raw !== 'menunggu' && raw !== 'returned' && raw !== 'dikembalikan' && raw !== 'rejected' && raw !== 'ditolak');
                return {
                    borrower: loan.nama || 'Siswa',
                    nim: loan.nis || '-',
                    item: loan.barang || '-',
                    desc: loan.kategori || '-',
                    icon: getItemIcon(loan.barang),
                    pinjam: formatRptDate(loan.tanggalPinjam),
                    kembali: formatRptDate(loan.tanggalKembali),
                    status: late ? 'Terlambat' : s[0],
                    type: late ? 'red' : s[1]
                };
            });
        }

        function getLoanItems() {
            var history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
            var decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
            var request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
            var fallback = decision && decision.request ? decision.request : request;
            var list = Array.isArray(history) ? history.slice() : [];
            if (fallback && fallback.id && !list.some(function (x) { return x.id === fallback.id; })) list.unshift(fallback);
            return list;
        }

        function isLoanLate(dateValue) {
            if (!dateValue) return false;
            var due = parseLoanDate(dateValue);
            if (!due) return false;
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            return due.getTime() < today.getTime();
        }

        function parseLoanDate(dateValue) {
            if (!dateValue) return null;
            if (dateValue.indexOf('/') !== -1) {
                var parts = dateValue.split('/');
                dateValue = parts[2] + '-' + parts[1] + '-' + parts[0];
            }
            var date = new Date(dateValue + 'T00:00:00');
            if (isNaN(date.getTime())) return null;
            date.setHours(0, 0, 0, 0);
            return date;
        }

        function renderStatCards() {
            var statTotal = document.getElementById('statTotalLoans');
            var statActive = document.getElementById('statActiveLoans');
            var statLate = document.getElementById('statLateLoans');
            if (!statTotal || !statActive || !statLate) return;

            var items = getLoanItems();
            var active = items.filter(function (loan) {
                var s = String(loan.status || 'approved').toLowerCase();
                return s !== 'returned' && s !== 'dikembalikan' && s !== 'rejected' && s !== 'ditolak';
            });
            var late = active.filter(function (loan) {
                var s = String(loan.status || 'approved').toLowerCase();
                return s !== 'pending' && s !== 'menunggu' && isLoanLate(loan.tanggalKembali);
            });

            statTotal.textContent = items.length;
            statActive.textContent = active.length;
            statLate.textContent = late.length;
        }

        function renderMonthlyStats() {
            var body = document.getElementById('monthlyStatsBody');
            var trend = document.getElementById('monthlyTrend');
            if (!body || !trend) return;

            var items = getLoanItems();
            var counts = [];
            items.forEach(function (loan) {
                var d = parseLoanDate(loan.tanggalPinjam || loan.tanggalKembali);
                if (!d) return;
                var key = d.getFullYear() + '-' + d.getMonth();
                var monthName = d.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                var found = counts.find(function (c) { return c.key === key; });
                if (found) found.count++;
                else counts.push({ key: key, name: monthName, count: 1 });
            });
            counts.sort(function (a, b) { return a.key < b.key ? -1 : 1; });

            if (counts.length === 0) {
                body.innerHTML = '<p class="monthly-empty" style="color:#8a94a6;font-size:13px;margin:8px 0 16px;">Belum ada data pinjaman.</p>';
                trend.textContent = '-';
                return;
            }

            var maxCount = Math.max.apply(null, counts.map(function (c) { return c.count; }));
            body.innerHTML = counts.map(function (c) {
                var width = maxCount > 0 ? Math.round((c.count / maxCount) * 100) : 0;
                return '<div class="month-progress"><div><span>' + c.name + '</span><small>' + c.count + ' Alat</small></div><b><i style="width:' + width + '%"></i></b></div>';
            }).join('');

            var latest = counts[counts.length - 1];
            var previous = counts.length > 1 ? counts[counts.length - 2] : null;
            if (!previous) {
                trend.textContent = 'Baru';
            } else {
                var diff = latest.count - previous.count;
                var pct = previous.count > 0 ? Math.round((diff / previous.count) * 100) : 100;
                trend.innerHTML = (diff >= 0 ? '↗' : '↘') + Math.abs(pct) + '%';
            }
        }

        function formatRptDate(d) {
            if (!d) return '-';
            if (d.includes('/')) { var p = d.split('/'); d = p[2]+'-'+p[1]+'-'+p[0]; }
            var dt = new Date(d + 'T00:00:00');
            if (isNaN(dt.getTime())) return d;
            return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        function filteredHistories() {
            var keyword = searchInput.value.toLowerCase().trim();
            return getHistoryData().filter(function (item) {
                var matchStatus = activeStatus === 'Semua' || item.status === activeStatus;
                var matchSearch = (item.item + ' ' + item.borrower + ' ' + item.pinjam + ' ' + item.kembali + ' ' + item.status).toLowerCase().includes(keyword);
                return matchStatus && matchSearch;
            });
        }

        function renderHistories() {
            var data = filteredHistories();
            var rows = showAll ? data : data.slice(0, 4);
            tableBody.innerHTML = rows.map(function (item, idx) {
                return '<tr>' +
                    '<td>' + (idx + 1) + '</td>' +
                    '<td><strong>' + item.borrower + '</strong><small>NIM. ' + item.nim + '</small></td>' +
                    '<td><div class="history-item-cell"><span><i class="bi ' + item.icon + '"></i></span><div><strong>' + item.item + '</strong><small>' + item.desc + '</small></div></div></td>' +
                    '<td>' + item.pinjam + '</td>' +
                    '<td>' + item.kembali + '</td>' +
                    '<td><span class="history-status ' + item.type + '"><i class="bi bi-circle-fill"></i> ' + item.status + '</span></td>' +
                '</tr>';
            }).join('');
            seeAllBtn.textContent = showAll ? 'Tampilkan Ringkasan' : 'Lihat Semua Riwayat';
        }

        document.getElementById('inventaris-toggle').addEventListener('click', function (e) {
            e.preventDefault();
            var sub = document.getElementById('inventaris-sub');
            sub.classList.toggle('collapsed');
            this.classList.toggle('open');
            localStorage.setItem('invOpen', sub.classList.contains('collapsed') ? '0' : '1');
        });
        if (localStorage.getItem('invOpen') === '1') {
            document.getElementById('inventaris-sub').classList.remove('collapsed');
            document.getElementById('inventaris-toggle').classList.add('open');
        }
        document.getElementById('inventaris-sub').querySelectorAll('.nav-sub-item').forEach(function(link) {
            link.addEventListener('click', function() { localStorage.setItem('invOpen', '1'); });
        });

        seeAllBtn.addEventListener('click', function () {
            showAll = !showAll;
            renderHistories();
        });

        document.getElementById('historySearchBtn').addEventListener('click', function () {
            searchRow.classList.toggle('active');
            if (searchRow.classList.contains('active')) searchInput.focus();
        });

        searchInput.addEventListener('input', renderHistories);

        filterBtn.addEventListener('click', function () {
            const currentIndex = statuses.indexOf(activeStatus);
            activeStatus = statuses[(currentIndex + 1) % statuses.length];
            filterBtn.classList.toggle('active', activeStatus !== 'Semua');
            filterBtn.title = `Filter: ${activeStatus}`;
            renderHistories();
        });

        renderHistories();
        renderStatCards();
        renderMonthlyStats();
    });

    function getItemIcon(name) {
        if (!name) return "bi-box-seam";
        const n = name.toLowerCase();
        if (n.includes("mouse")) return "bi-mouse";
        if (n.includes("keyboard")) return "bi-keyboard";
        if (n.includes("laptop") || n.includes("lenovo") || n.includes("notebook")) return "bi-laptop";
        if (n.includes("headset") || n.includes("headphone") || n.includes("logitech")) return "bi-headphones";
        if (n.includes("webcam") || n.includes("video camera")) return "bi-camera-video";
        if (n.includes("kamera") || n.includes("canon") || n.includes("dslr")) return "bi-camera";
        if (n.includes("mikroskop") || n.includes("olympus")) return "bi-microscope";
        if (n.includes("projector") || n.includes("proyektor") || n.includes("epson")) return "bi-easel";
        if (n.includes("lan") || n.includes("cat6") || n.includes("networking") || n.includes("network cable")) return "bi-ethernet";
        if (n.includes("hdmi")) return "bi-usb-c";
        if (n.includes("vga")) return "bi-plug";
        if (n.includes("kabel")) return "bi-usb-plug";
        if (n.includes("pointer") || n.includes("remote") || n.includes("pen wireless")) return "bi-broadcast-pin";
        if (n.includes("stop kontak") || n.includes("kontak")) return "bi-outlet";
        if (n.includes("tester")) return "bi-router";
        if (n.includes("crimping") || n.includes("tang")) return "bi-tools";
        if (n.includes("jbl") || n.includes("speaker") || n.includes("boombox")) return "bi-speaker";
        if (n.includes("tripod") || n.includes("promon")) return "bi-camera-reels";
        return "bi-box-seam";
    }

</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>
