<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pinjam - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=19">
    <style>
        .date-range-wrap { display: flex; align-items: center; gap: 8px; }
        .filter-date-input { border: 1px solid #ddd; border-radius: 6px; padding: 6px 10px; font-size: 0.82rem; font-family: inherit; background: #fff; color: #333; outline: none; width: 155px; }
        .filter-date-input:focus { border-color: #4f6ef7; box-shadow: 0 0 0 2px rgba(79,110,247,0.15); }
        .history-filter-row label { min-width: 0; }
        .filter-select { border: 1px solid #ddd; border-radius: 6px; padding: 6px 10px; font-size: 0.82rem; font-family: inherit; background: #fff; color: #333; outline: none; cursor: pointer; width: 100%; appearance: auto; }
        .filter-select:focus { border-color: #4f6ef7; box-shadow: 0 0 0 2px rgba(79,110,247,0.15); }
    </style>
</head>
<body>
@php
    $userName = Auth::check() ? Auth::user()->name : 'Siswa';
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
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item active" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-area">
        <header class="topbar history-topbar">
            <div class="search-box history-search"><i class="bi bi-search"></i> Cari inventaris...</div>
            <div class="top-actions">
                @include('user.partials.notification-bell')

                <div class="top-user"><div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div><img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar"></div>
            </div>
        </header>

        <section class="content history-page-content">
            <div class="history-page-head">
                <h1>Riwayat Pinjam</h1>
                <p>Daftar riwayat semua peminjaman barang inventaris.</p>

            </div>

            <div class="history-table-card">
                <div class="history-filter-row">
                    <label>Rentang Tanggal
                        <div class="date-range-wrap">
                            <input type="date" id="filter-date-start" class="filter-date-input">
                            <span style="color:#999;flex-shrink:0;">-</span>
                            <input type="date" id="filter-date-end" class="filter-date-input">
                        </div>
                    </label>
                    <label>Status
                        <div class="history-filter-field" style="position:relative;">
                            <select id="filter-status" class="filter-select">
                                <option value="all">Semua Status</option>
                                <option value="Dipinjam">Dipinjam</option>
                                <option value="Dikembalikan">Dikembalikan</option>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Ditolak">Ditolak</option>
                                <option value="Terlambat">Terlambat</option>
                            </select>
                        </div>
                    </label>
                    <label>Cari Peminjam / Barang
                        <div class="history-filter-field">
                            <input class="history-search-input" id="filter-search" type="text" placeholder="Cari nama peminjam atau barang...">
                        </div>
                    </label>
                    <div>
                        <span style="display:block;font-size:12px;font-weight:700;color:#10233f;margin-bottom:8px;visibility:hidden;">.</span>
                        <button type="button" class="history-export-btn" id="btn-filter-apply"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </div>

                <table class="history-loan-table">
                    <thead>
                        <tr><th>No</th><th>Peminjam</th><th>Barang</th><th>Tanggal Pinjam</th><th>Tanggal Kembali</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody id="history-loan-body">
                    </tbody>
                </table>
                <div class="history-footer-row">
                    <span id="history-total-label">Menampilkan 0 data</span>
                    <div class="history-pagination" id="history-pagination">
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<div class="history-modal-overlay" id="history-detail-modal">
    <div class="history-modal">
        <button class="modal-close" type="button" onclick="closeHistoryDetail()">&times;</button>
        <div class="history-modal-icon"><i class="bi bi-journal-check"></i></div>
        <h2>Detail Riwayat Peminjaman</h2>
        <p>Informasi lengkap transaksi peminjaman barang inventaris.</p>
        <div class="history-detail-box">
            <div><span>Peminjam</span><strong id="detail-borrower">-</strong></div>
            <div><span>NIM</span><strong id="detail-nim">-</strong></div>
            <div><span>Barang</span><strong id="detail-item">-</strong></div>
            <div><span>Spesifikasi</span><strong id="detail-desc">-</strong></div>
            <div><span>Tanggal Pinjam</span><strong id="detail-pinjam">-</strong></div>
            <div><span>Tanggal Kembali</span><strong id="detail-kembali">-</strong></div>
            <div><span>Status</span><strong id="detail-status">-</strong></div>
        </div>
        <button class="history-modal-ok" type="button" onclick="closeHistoryDetail()">Tutup</button>
    </div>
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

    function openHistoryDetail(row) {
        document.getElementById('detail-borrower').textContent = row.borrower;
        document.getElementById('detail-nim').textContent = row.nim;
        document.getElementById('detail-item').textContent = row.item;
        document.getElementById('detail-desc').textContent = row.desc;
        document.getElementById('detail-pinjam').textContent = row.pinjam + (row.pinjam_time ? ' ' + row.pinjam_time : '');
        document.getElementById('detail-kembali').textContent = row.kembali + (row.kembali_time ? ' ' + row.kembali_time : '');
        document.getElementById('detail-status').textContent = row.status;
        document.getElementById('history-detail-modal').classList.add('active');
    }

    function closeHistoryDetail() {
        document.getElementById('history-detail-modal').classList.remove('active');
    }

    function getRawLoanHistory() {
        let history = [];
        try { history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]'); } catch (e) {}
        if (!Array.isArray(history) || history.length === 0) {
            history = [
                {
                    id: 'B802931-B',
                    nama: 'Ahmad Fauzi',
                    nis: '220401001',
                    barang: 'Kamera DSLR Canon EOS 80D',
                    serial: 'B802931-B',
                    kategori: 'Multimedia',
                    jumlah: 1,
                    tanggalPinjam: '24/10/2023',
                    tanggalKembali: '26/10/2023',
                    status: 'Dipinjam'
                },
                {
                    id: 'OLY-X300',
                    nama: 'Siti Rahma',
                    nis: '220401002',
                    barang: 'Mikroskop Binokuler Olympus',
                    serial: 'OLY-X300',
                    kategori: 'Laboratorium',
                    jumlah: 1,
                    tanggalPinjam: '25/10/2023',
                    tanggalKembali: '28/10/2023',
                    status: 'Dipinjam'
                },
                {
                    id: 'LP-001-2023',
                    nama: 'Budi Santoso',
                    nis: '220401003',
                    barang: 'Laptop Dell Precision 3561',
                    serial: 'LP-001-2023',
                    kategori: 'Komputer',
                    jumlah: 1,
                    tanggalPinjam: '15/10/2023',
                    tanggalKembali: '22/10/2023',
                    status: 'Dikembalikan'
                },
                {
                    id: 'TR-005-2023',
                    nama: 'Citra Dewi',
                    nis: '220401004',
                    barang: 'Tripod Excell Promon 500',
                    serial: 'TR-005-2023',
                    kategori: 'Multimedia',
                    jumlah: 1,
                    tanggalPinjam: '14/10/2023',
                    tanggalKembali: '20/10/2023',
                    status: 'Dikembalikan'
                }
            ];
            try { localStorage.setItem('sipibsLoanHistory', JSON.stringify(history)); } catch (e) {}
        }
        return history;
    }

    function getLoanHistoryRows() {
        let history = getRawLoanHistory();
        let decision = null;
        try { decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null'); } catch (e) {}
        let request = null;
        try { request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null'); } catch (e) {}
        const fallback = decision && decision.request ? decision.request : request;
        let list = history.slice();
        if (fallback && fallback.id && !list.some(loan => loan.id === fallback.id)) list.unshift(fallback);
        try {
            const activeReturns = list.filter(loan => {
                const status = String(loan.status || 'Dipinjam').toLowerCase().trim();
                return status !== 'ditolak' && status !== 'rejected';
            });
            localStorage.setItem('sipibsReturnLoanItems', JSON.stringify(activeReturns));
        } catch (e) {}
        
        return list.map((loan, index) => {
            const rawStatus = String(loan.status || 'pending').toLowerCase();
            let label = 'Menunggu';
            let color = 'yellow';

            if (rawStatus === 'dipinjam' || rawStatus === 'approved') {
                label = 'Dipinjam';
                color = 'blue';
            } else if (rawStatus === 'dikembalikan' || rawStatus === 'returned') {
                label = 'Dikembalikan';
                color = 'green';
            } else if (rawStatus === 'ditolak' || rawStatus === 'rejected') {
                label = 'Ditolak';
                color = 'red';
            }

            return {
                no: index + 1,
                id: loan.id || '',
                statusKey: rawStatus,
                borrower: loan.nama || loan.borrower || 'Siswa',
                nim: loan.nis || loan.nim || '-',
                item: loan.barang || loan.item || '-',
                desc: loan.kategori || loan.desc || '-',
                pinjam: formatHistoryDate(loan.tanggalPinjam || loan.pinjam),
                pinjam_time: '',
                kembali: formatHistoryDate(loan.tanggalKembali || loan.kembali),
                kembali_time: '',
                status: label,
                type: color,
                icon: getItemIcon(loan.barang || loan.item)
            };
        });
    }

    function formatHistoryDate(dateValue) {
        if (!dateValue) return '-';
        if (dateValue.includes('/')) {
            const parts = dateValue.split('/');
            dateValue = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        const date = new Date(dateValue + 'T00:00:00');
        if (Number.isNaN(date.getTime())) return dateValue;
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function renderLatestLoanHistory() {
        const body = document.getElementById('history-loan-body');
        if (!body) return;
        const rows = getLoanHistoryRows();

        const searchVal = (document.getElementById('filter-search').value || '').toLowerCase().trim();
        const statusVal = document.getElementById('filter-status').value;
        const dateStart = document.getElementById('filter-date-start').value;
        const dateEnd = document.getElementById('filter-date-end').value;

        const filtered = rows.filter(row => {
            if (searchVal) {
                const haystack = (row.borrower + ' ' + row.item + ' ' + row.nim).toLowerCase();
                if (!haystack.includes(searchVal)) return false;
            }
            if (statusVal !== 'all' && row.status !== statusVal) return false;
            if (dateStart || dateEnd) {
                const pinjamDate = parseFilterDate(row.pinjam);
                if (dateStart && pinjamDate < parseFilterDate(dateStart)) return false;
                if (dateEnd && pinjamDate > parseFilterDate(dateEnd)) return false;
            }
            return true;
        });

        body.innerHTML = '';

        if (filtered.length === 0) {
            body.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px 20px;color:#888;"><i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:10px;"></i>' + (rows.length === 0 ? 'Belum ada riwayat peminjaman.' : 'Tidak ada data yang cocok dengan filter.') + '</td></tr>';
            document.getElementById('history-total-label').textContent = 'Menampilkan 0 data';
            return;
        }

        filtered.forEach((row, idx) => {
            row.no = idx + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.no}</td>
                <td><strong>${row.borrower}</strong><small>NIM. ${row.nim}</small></td>
                <td><div class="history-item-cell"><span><i class="bi ${row.icon}"></i></span><div><strong>${row.item}</strong><small>${row.desc}</small></div></div></td>
                <td>${row.pinjam}<small>${row.pinjam_time}</small></td>
                <td>${row.kembali}<small>${row.kembali_time}</small></td>
                <td><span class="history-status ${row.type}"><i class="bi bi-circle-fill"></i> ${row.status}</span></td>
                <td><div class="history-actions"><button class="history-detail-btn" type="button"><i class="bi bi-eye"></i> Detail</button></div></td>
            `;
            tr.querySelector('.history-detail-btn').addEventListener('click', function () {
                openHistoryDetail(row);
            });
            body.appendChild(tr);
        });

        const label = document.getElementById('history-total-label');
        if (label) label.textContent = 'Menampilkan 1 - ' + filtered.length + ' dari ' + rows.length + ' data';
    }

    function parseFilterDate(str) {
        if (!str) return new Date(0);
        if (str.includes('/')) {
            const p = str.split('/');
            if (p.length === 3) return new Date(`${p[2]}-${p[1]}-${p[0]}T00:00:00`);
        }
        return new Date(str + 'T00:00:00');
    }

    document.getElementById('btn-filter-apply').addEventListener('click', renderLatestLoanHistory);
    document.getElementById('filter-search').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); renderLatestLoanHistory(); }
    });
    document.getElementById('filter-status').addEventListener('change', renderLatestLoanHistory);

    renderLatestLoanHistory();
    window.history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);

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



