<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Barang - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=31">
    <style>
        .return-photo-upload.photo-missing { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,.15); }
        .return-selected-item { display:flex; gap:12px; align-items:center; padding:12px; margin:12px 0 16px; border:1px solid #cfe3f7; border-radius:14px; background:#f4f9ff; }
        .return-selected-item[hidden] { display:none !important; }
        .return-selected-photo { width:72px; height:72px; border-radius:12px; overflow:hidden; flex:0 0 72px; display:grid; place-items:center; background:#e8f2ff; color:#0055c8; }
        .return-selected-photo img { width:100%; height:100%; object-fit:cover; }
        .return-selected-photo i { font-size:30px; }
        .return-selected-item strong { display:block; color:#001f4d; line-height:1.25; }
        .return-selected-item small { display:block; color:#64748b; margin-top:3px; }
        .return-list-wrap { overflow-x:auto; }
        .return-page-head { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; margin-bottom:24px; }
        .return-page-head h1 { color:#003f8f; font-size:24px; font-weight:800; line-height:1.2; letter-spacing:-.02em; }
        .return-page-head p { color:#475569; font-size:14px; margin-top:4px; }
        .return-print-btn { display:inline-flex; align-items:center; gap:8px; height:38px; padding:0 16px; border:1px solid #0055c8; border-radius:8px; background:#fff; color:#0055c8; font-size:.82rem; font-weight:700; text-decoration:none; cursor:pointer; font-family:inherit; transition:background .15s ease; }
        .return-print-btn:hover { background:#eef7ff; }
        .return-page-content { background:#f4f8fc; padding:28px 36px 48px; min-height:calc(100vh - 74px); }
        .return-main-grid { display:grid; grid-template-columns:minmax(0, 1fr) 360px; align-items:start; gap:24px; }
        .return-left-col { display:flex; flex-direction:column; gap:22px; min-width:0; }
        .return-card { border:1px solid #e2e8f0; border-radius:16px; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,.04); overflow:hidden; }
        .return-list-card, .return-history-card { padding:22px 24px; }
        .return-card-title { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; color:#0f172a; font-size:16px; font-weight:700; }
        .return-card-title div, .return-card-title.simple { display:flex; align-items:center; gap:10px; color:#0f172a; font-size:16px; font-weight:700; }
        .return-card-title i, .return-card-title.simple i { color:#0055c8; font-size:18px; }
        .return-pending-badge { background:#dbeafe; color:#1d4ed8; border-radius:999px; padding:4px 14px; font-size:12px; font-weight:700; display:inline-block; }
        .return-table-wrap, .return-table.history { width:100%; border-collapse:collapse; }
        .return-table-wrap th, .return-table.history th { padding:12px 14px; border-top:none; border-bottom:1px solid #f1f5f9; color:#64748b; font-size:11px; font-weight:700; text-align:left; text-transform:uppercase; letter-spacing:.05em; }
        .return-table-wrap td, .return-table.history td { padding:14px; border-bottom:1px solid #f1f5f9; color:#334155; font-size:13px; vertical-align:middle; }
        .return-table-wrap tr:last-child td, .return-table.history tr:last-child td { border-bottom:none; }
        .return-table-wrap tr.selected td { background:#e8f0fc !important; color:#1e293b !important; }
        .return-table-wrap tr.selected strong { color:#0f274a !important; }
        .return-table-wrap tr.selected small { color:#475569 !important; }
        .return-table-wrap tr.selected .return-icon { background:#d4e5fb !important; color:#003884 !important; }
        .return-table-wrap tr.selected .return-due-date.safe { color:#334155 !important; }
        .return-table-wrap tr.selected { box-shadow: inset 4px 0 0 #003884; }
        .return-item-meta { display:flex; align-items:center; gap:14px; min-width:220px; }
        .return-item-meta strong { display:block; color:#0f172a; font-size:13.5px; font-weight:700; line-height:1.3; }
        .return-item-meta small { display:block; color:#64748b; font-size:11.5px; margin-top:3px; }
        .return-icon { width:46px; height:46px; min-width:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#edf5ff; color:#0055c8; flex:0 0 46px; font-size:22px; }
        .return-due-date { display:block; color:#dc2626; font-weight:700; font-size:13px; line-height:1.3; }
        .return-due-date.safe { color:#0f172a; }
        .return-due-pill { display:inline-block; margin-top:4px; padding:2px 6px; border-radius:4px; background:#fee2e2; color:#dc2626; font-size:9.5px; font-weight:800; text-transform:uppercase; letter-spacing:.04em; }
        .return-due-pill.safe { background:transparent; color:#64748b; font-weight:700; padding:0; }
        .return-now-btn { background:#003884; color:#fff; border:none; border-radius:8px; padding:9px 16px; font-size:12px; font-weight:700; cursor:pointer; transition:background .15s ease, transform .15s ease; box-shadow:0 2px 5px rgba(0,56,132,.18); font-family:inherit; }
        .return-now-btn:hover { background:#002a63; transform:translateY(-1px); }
        .return-now-btn.clicked { background:#002456 !important; color:#ffffff !important; box-shadow:0 0 0 2px rgba(0,36,86,.25); }
        .return-cond-pill { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:999px; font-size:11px; font-weight:700; }
        .return-cond-pill.good { background:#dcfce7; color:#16a34a; }
        .return-cond-pill.warn { background:#ffedd5; color:#c2410c; }
        .return-cond-pill.bad { background:#fee2e2; color:#dc2626; }
        .return-cond-pill .cond-symbol { font-size:10px; line-height:1; }
        .return-status-pill.done { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border:1px solid #bfdbfe; border-radius:6px; background:#eff6ff; color:#2563eb; font-size:11px; font-weight:700; }
        .return-status-pill.done i { font-size:12px; }
        .return-empty { text-align:center; padding:36px 20px; color:#94a3b8; font-size:13px; }
        .return-form-card { padding:24px 26px; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,.04); }
        .return-form-card.focused, .return-form-card.selected-active { border-color:#003884; box-shadow:0 0 0 4px rgba(0,85,200,.18), 0 12px 30px rgba(0,56,132,.16); }
        .return-form-card.focus-pulse { animation:returnFormPulse 1.2s ease 2; }
        @keyframes returnFormPulse { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-4px); box-shadow:0 0 0 6px rgba(0,85,200,.22), 0 16px 36px rgba(0,56,132,.22); } }
        .return-form-card h2 { color:#003f8f; font-size:20px; font-weight:800; margin-bottom:4px; display:flex; align-items:center; gap:8px; }
        .return-form-card p { color:#64748b; font-size:12.5px; margin-bottom:16px; }
        .return-form-card label { display:block; color:#334155; font-size:12px; font-weight:700; margin-top:14px; margin-bottom:6px; }
        .return-form-card select, .return-form-card textarea { width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:10px 12px; font-size:13px; font-family:inherit; color:#1e293b; background:#fff; outline:none; transition:border-color .15s ease, box-shadow .15s ease; box-sizing:border-box; }
        .return-form-card select:focus, .return-form-card textarea:focus { border-color:#0055c8; box-shadow:0 0 0 3px rgba(0,85,200,.12); }
        .return-form-card textarea { resize:vertical; min-height:80px; }
        .return-photo-upload { border:2px dashed #cbd5e1; border-radius:12px; background:#f8fafc; padding:16px; text-align:center; cursor:pointer; transition:all .2s ease; outline:none; }
        .return-photo-upload:hover, .return-photo-upload.dragover { border-color:#0055c8; background:#f0f7ff; }
        .return-photo-upload.photo-missing { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,.15); }
        .return-upload-empty { display:flex; flex-direction:column; align-items:center; gap:4px; }
        .return-upload-icon { width:42px; height:42px; border-radius:50%; background:#e0f2fe; color:#0284c7; display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:4px; }
        .return-upload-empty strong { color:#0f172a; font-size:13px; font-weight:700; }
        .return-upload-empty span { color:#64748b; font-size:12px; }
        .return-upload-empty small { color:#94a3b8; font-size:11px; margin-top:2px; }
        .return-photo-list { display:flex; flex-direction:column; gap:8px; margin-bottom:12px; text-align:left; }
        .return-photo-item { display:flex; align-items:center; gap:10px; background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; }
        .return-photo-item-thumb, .return-photo-thumb { width:44px; height:44px; border-radius:6px; overflow:hidden; flex-shrink:0; background:#f1f5f9; display:flex; align-items:center; justify-content:center; }
        .return-photo-item-thumb img, .return-photo-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .return-photo-item-info, .return-photo-info { flex:1; min-width:0; text-align:left; }
        .return-photo-item-name, .return-photo-name { font-size:12px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
        .return-photo-item-size, .return-photo-size { font-size:11px; color:#64748b; display:block; margin-top:1px; }
        .return-photo-item-del, .return-photo-remove { background:none; border:none; color:#ef4444; font-size:15px; cursor:pointer; padding:6px 8px; border-radius:6px; line-height:1; transition:background .15s ease; }
        .return-photo-item-del:hover, .return-photo-remove:hover { background:#fee2e2; }
        .return-upload-actions { display:flex; justify-content:space-between; align-items:center; padding-top:6px; border-top:1px dashed #e2e8f0; }
        .return-upload-add { background:#e8f2ff; color:#0055c8; border:1px solid #bfdbfe; border-radius:6px; padding:6px 12px; font-size:11.5px; font-weight:700; cursor:pointer; font-family:inherit; transition:all .15s ease; }
        .return-upload-add:hover { background:#d0e4ff; }
        .return-info-box { display:flex; gap:10px; align-items:flex-start; background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:10px 12px; margin-top:14px; color:#0369a1; font-size:12px; line-height:1.4; }
        .return-info-box i { font-size:16px; flex-shrink:0; margin-top:1px; }
        .return-confirm-btn { width:100%; margin-top:18px; padding:12px; background:#003884; color:#fff; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; font-family:inherit; transition:background .15s ease, transform .15s ease; box-shadow:0 4px 12px rgba(0,56,132,.25); }
        .return-confirm-btn:hover { background:#002a63; transform:translateY(-1px); }
        .return-modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.6); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; z-index:10000; opacity:0; pointer-events:none; transition:opacity .25s ease; }
        .return-modal-overlay.active { opacity:1; pointer-events:auto; }
        .return-modal { background:#fff; border-radius:18px; width:92%; max-width:480px; padding:28px 24px; position:relative; transform:scale(.92); transition:transform .25s ease; box-shadow:0 20px 40px rgba(0,0,0,.2); text-align:center; max-height:90vh; overflow-y:auto; }
        .return-modal-overlay.active .return-modal { transform:scale(1); }
        .modal-close { position:absolute; top:14px; right:16px; background:none; border:none; font-size:24px; color:#94a3b8; cursor:pointer; line-height:1; }
        .modal-close:hover { color:#0f172a; }
        .return-success-icon { width:60px; height:60px; border-radius:50%; background:#dcfce7; color:#16a34a; font-size:32px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:12px; }
        .return-modal h2 { font-size:20px; font-weight:800; color:#0f172a; margin-bottom:6px; }
        .return-modal p { font-size:13px; color:#64748b; margin-bottom:18px; line-height:1.45; }
        .return-proof { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px; text-align:left; margin-bottom:20px; }
        .proof-head { display:flex; justify-content:space-between; align-items:center; border-bottom:1px dashed #cbd5e1; padding-bottom:10px; margin-bottom:10px; }
        .proof-head strong { font-size:13px; color:#003884; display:block; }
        .proof-head span { font-size:11px; color:#64748b; }
        .proof-code { font-family:monospace; font-size:11px; font-weight:700; background:#e0f2fe; color:#0369a1; padding:3px 8px; border-radius:4px; }
        .proof-row { display:flex; justify-content:space-between; font-size:12px; margin-bottom:6px; }
        .proof-row span { color:#64748b; }
        .proof-row strong { color:#0f172a; font-weight:600; text-align:right; max-width:60%; }
        .proof-footer { margin-top:10px; padding-top:8px; border-top:1px dashed #cbd5e1; font-size:11px; color:#c2410c; font-weight:700; text-align:center; }
        .return-modal-actions { display:flex; gap:10px; justify-content:center; }
        .return-modal-actions button, .return-modal-actions a { flex:1; padding:10px 16px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; gap:6px; font-family:inherit; }
    </style>
</head>
<body>
@php
    $userName = Auth::check() ? Auth::user()->name : 'Admin SIPIBS';
    $userRole = 'SISWA';
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
                    if (name) {
                        var topUser = document.getElementById('top-user-name');
                        if (topUser) topUser.textContent = name;
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
            <a class="nav-item" href="{{ url('/dashboard-user') }}"><i class="bi bi-grid"></i> Dashboard</a>
            <a class="nav-item" href="#" id="inventaris-toggle"><i class="bi bi-box-seam"></i> Inventaris <span class="nav-arrow">^</span></a>
            <div class="nav-sub-list collapsed" id="inventaris-sub">
                <a class="nav-sub-item" href="{{ url('/katalog-alat') }}">Katalog Barang</a>
                <a class="nav-sub-item" href="{{ url('/kondisi-barang') }}">Kondisi Barang</a>
            </div>
            <a class="nav-item" href="{{ url('/peminjaman-user') }}"><i class="bi bi-gem"></i> Peminjaman</a>
            <a class="nav-item active" href="{{ url('/pengembalian-user') }}"><i class="bi bi-calendar-check"></i> Pengembalian</a>
            <a class="nav-item" href="{{ url('/denda-user') }}"><i class="bi bi-cash-coin"></i> Denda</a>
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-area">
        <header class="topbar">
            <div class="search-box condition-search"><i class="bi bi-search"></i><input id="returnSearch" type="text" placeholder="Cari inventaris..."></div>
            <div class="top-actions">
                @include('user.partials.notification-bell')
                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>{{ $userRole }}</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar">
                </div>
            </div>
        </header>

        <section class="content return-page-content">
            <div class="return-page-head">
                <div>
                    <h1>Pengembalian Barang</h1>
                    <p>Selesaikan proses peminjaman dengan mendata kondisi barang yang dikembalikan.</p>
                </div>
                <button class="return-print-btn" type="button" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Laporan</button>
            </div>

            <div class="return-main-grid">
                <div class="return-left-col">
                    <div class="return-card return-list-card">
                        <div class="return-card-title">
                            <div><i class="bi bi-box-seam-fill"></i> Barang Perlu Dikembalikan</div>
                            <span id="return-pending-count" class="return-pending-badge">4 Tertunda</span>
                        </div>
                        <div class="return-list-wrap">
                            <table class="return-table-wrap">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Batas Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="return-items-body">
                                    <tr data-id="B802931-B" data-name="Kamera DSLR Canon EOS 80D" data-serial="B802931-B" data-pinjam="24 Oct 2023" data-batas="26 Oct 2023" data-image="" data-icon="bi-camera-fill" data-from-history="1">
                                        <td>
                                            <div class="return-item-meta">
                                                <span class="return-icon"><i class="bi bi-camera-fill"></i></span>
                                                <div>
                                                    <strong>Kamera DSLR Canon EOS 80D</strong>
                                                    <small>SN: B802931-B</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>24 Oct 2023</td>
                                        <td>
                                            <span class="return-due-date">26 Oct 2023</span>
                                            <span class="return-due-pill">TERLAMBAT</span>
                                        </td>
                                        <td>
                                            <button class="return-now-btn" type="button" onclick="return handleReturnNowClick(this);">Kembalikan Sekarang</button>
                                        </td>
                                    </tr>
                                    <tr data-id="OLY-X300" data-name="Mikroskop Binokuler Olympus" data-serial="OLY-X300" data-pinjam="25 Oct 2023" data-batas="28 Oct 2023" data-image="" data-icon="bi-microscope" data-from-history="1">
                                        <td>
                                            <div class="return-item-meta">
                                                <span class="return-icon">
                                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h8"></path><path d="M3 22h18"></path><path d="M14 22a7 7 0 1 0 0-14h-1"></path><path d="M9 14h2"></path><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"></path><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"></path></svg>
                                                </span>
                                                <div>
                                                    <strong>Mikroskop Binokuler Olympus</strong>
                                                    <small>SN: OLY-X300</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>25 Oct 2023</td>
                                        <td>
                                            <span class="return-due-date safe">28 Oct 2023</span>
                                            <span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SISA 1 HARI</span>
                                        </td>
                                        <td>
                                            <button class="return-now-btn" type="button" onclick="return handleReturnNowClick(this);">Kembalikan Sekarang</button>
                                        </td>
                                    </tr>
                                    <tr data-id="LP-001-2023" data-name="Laptop Dell Precision 3561" data-serial="LP-001-2023" data-pinjam="15 Oct 2023" data-batas="22 Oct 2023" data-image="" data-icon="bi-laptop" data-from-history="1">
                                        <td>
                                            <div class="return-item-meta">
                                                <span class="return-icon"><i class="bi bi-laptop"></i></span>
                                                <div>
                                                    <strong>Laptop Dell Precision 3561</strong>
                                                    <small>SN: LP-001-2023</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>15 Oct 2023</td>
                                        <td>
                                            <span class="return-due-date">22 Oct 2023</span>
                                            <span class="return-due-pill">TERLAMBAT</span>
                                        </td>
                                        <td>
                                            <button class="return-now-btn" type="button" onclick="return handleReturnNowClick(this);">Kembalikan Sekarang</button>
                                        </td>
                                    </tr>
                                    <tr data-id="TR-005-2023" data-name="Tripod Excell Promon 500" data-serial="TR-005-2023" data-pinjam="14 Oct 2023" data-batas="20 Oct 2023" data-image="" data-icon="bi-camera-reels" data-from-history="1">
                                        <td>
                                            <div class="return-item-meta">
                                                <span class="return-icon"><i class="bi bi-camera-reels"></i></span>
                                                <div>
                                                    <strong>Tripod Excell Promon 500</strong>
                                                    <small>SN: TR-005-2023</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>14 Oct 2023</td>
                                        <td>
                                            <span class="return-due-date">20 Oct 2023</span>
                                            <span class="return-due-pill">TERLAMBAT</span>
                                        </td>
                                        <td>
                                            <button class="return-now-btn" type="button" onclick="return handleReturnNowClick(this);">Kembalikan Sekarang</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="return-card return-history-card">
                        <div class="return-card-title simple"><i class="bi bi-arrow-counterclockwise"></i> Riwayat Pengembalian Terbaru</div>
                        <table class="return-table history">
                            <thead>
                                <tr>
                                    <th>Barang Kembali</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Kondisi</th>
                                    <th>Catatan Admin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="return-history-body">
                                <tr>
                                    <td>
                                        <strong>Laptop Dell Precision 3561</strong>
                                        <small style="display:block;color:#64748b;font-size:11.5px;margin-top:2px;">Inv: LP-001-2023</small>
                                    </td>
                                    <td>22 Oct 2023</td>
                                    <td><span class="return-cond-pill good"><span class="cond-symbol">●</span> Baik</span></td>
                                    <td style="color:#475569;font-size:12.5px;max-width:240px;line-height:1.35;">"Kondisi mulus, data sudah di-clear."</td>
                                    <td><span class="return-status-pill done"><i class="bi bi-check-circle"></i> Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Tripod Excell Promon 500</strong>
                                        <small style="display:block;color:#64748b;font-size:11.5px;margin-top:2px;">Inv: TR-005-2023</small>
                                    </td>
                                    <td>20 Oct 2023</td>
                                    <td><span class="return-cond-pill warn"><span class="cond-symbol">▪</span> Rusak Ringan</span></td>
                                    <td style="color:#475569;font-size:12.5px;max-width:240px;line-height:1.35;">"Kunci kaki tripod agak longgar."</td>
                        </div>
                    </div>

                    <div class="return-card return-history-card">
                        <div class="return-card-title simple"><i class="bi bi-arrow-counterclockwise"></i> Riwayat Pengembalian Terbaru</div>
                        <table class="return-table history">
                            <thead>
                                <tr>
                                    <th>Barang Kembali</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Kondisi</th>
                                    <th>Catatan Admin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="return-history-body">
                                <tr>
                                    <td>
                                        <strong>Laptop Dell Precision 3561</strong>
                                        <small style="display:block;color:#64748b;font-size:11.5px;margin-top:2px;">Inv: LP-001-2023</small>
                                    </td>
                                    <td>22 Oct 2023</td>
                                    <td><span class="return-cond-pill good"><span class="cond-symbol">●</span> Baik</span></td>
                                    <td style="color:#475569;font-size:12.5px;max-width:240px;line-height:1.35;">"Kondisi mulus, data sudah di-clear."</td>
                                    <td><span class="return-status-pill done"><i class="bi bi-check-circle"></i> Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Tripod Excell Promon 500</strong>
                                        <small style="display:block;color:#64748b;font-size:11.5px;margin-top:2px;">Inv: TR-005-2023</small>
                                    </td>
                                    <td>20 Oct 2023</td>
                                    <td><span class="return-cond-pill warn"><span class="cond-symbol">▪</span> Rusak Ringan</span></td>
                                    <td style="color:#475569;font-size:12.5px;max-width:240px;line-height:1.35;">"Kunci kaki tripod agak longgar."</td>
                                    <td><span class="return-status-pill done"><i class="bi bi-check-circle"></i> Selesai</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <aside class="return-card return-form-card" id="return-form-card" tabindex="-1">
                    <h2><i class="bi bi-pencil-square"></i> Form Pengembalian</h2>
                    <p>Silakan pilih barang yang akan dikembalikan.</p>
                    <div class="return-selected-item" id="return-selected-item" hidden>
                        <div class="return-selected-photo" id="return-selected-photo"><i class="bi bi-box-seam"></i></div>
                        <div>
                            <strong id="return-selected-name"></strong>
                            <small id="return-selected-serial"></small>
                        </div>
                    </div>
                    <form id="return-form">
                        <input type="hidden" id="return-id" value="">
                        <input type="hidden" id="return-name" value="">
                        <label>Nama Barang</label>
                        <input type="text" id="return-name-display" value="Pilih barang dari daftar" readonly style="width:100%;height:42px;padding:0 12px;border:1px solid #b8c7d9;border-radius:3px;background:#eef7ff;color:#003985;font-weight:800;font-size:13px;">
                        <label>Kondisi Barang</label>
                        <select id="return-condition" required>
                            <option>Baik (Fungsional & Bersih)</option>
                            <option>Rusak Ringan</option>
                            <option>Rusak Berat</option>
                        </select>
                        <label>Foto Barang (Wajib)</label>
                        <input type="file" id="return-photo" accept="image/jpeg,image/jpg,image/png,image/webp,image/*" multiple style="display:none;">
                        <div class="return-photo-upload" id="return-photo-upload" tabindex="0" role="button" aria-label="Upload foto barang">
                            <div class="return-upload-empty" id="return-upload-empty">
                                <span class="return-upload-icon"><i class="bi bi-cloud-arrow-up-fill"></i></span>
                                <strong>Klik untuk upload foto barang</strong>
                                <span>atau drag &amp; drop file gambar di sini</span>
                                <small>Format: JPG, PNG, WebP (Maks. 5 Foto, Maks. 15MB)</small>
                            </div>
                            <div class="return-upload-preview" id="return-upload-preview" style="display:none;position:relative;z-index:10;">
                                <div class="return-photo-list" id="return-photo-list"></div>
                                <div class="return-upload-actions">
                                    <button class="return-upload-add" id="return-upload-add" type="button"><i class="bi bi-plus-circle"></i> Tambah Foto Lain</button>
                                    <small id="return-photo-count" style="color:#64748b;font-size:11.5px;font-weight:700;">0 / 5 foto</small>
                                </div>
                            </div>
                            <small id="return-photo-hint" style="display:none;color:#dc2626;font-weight:700;margin-top:8px;text-align:center;"><i class="bi bi-exclamation-circle"></i> Foto barang wajib diupload sebelum konfirmasi pengembalian.</small>
                        </div>
                        <label>Catatan Tambahan</label>
                        <textarea id="return-note" placeholder="Contoh: Lensa sedikit berdebu, baterai penuh..."></textarea>
                        <div class="return-info-box">
                            <i class="bi bi-info-circle"></i>
                            <span>Pastikan semua aksesoris (kabel, tas, charger) ikut disertakan dalam pengembalian untuk verifikasi admin.</span>
                        </div>
                        <button class="return-confirm-btn" id="return-confirm-btn" type="submit"><i class="bi bi-check-circle"></i> Konfirmasi Pengembalian</button>
                    </form>
                </aside>
            </div>

        </section>
    </main>
</div>

<div id="user-toast" style="position:fixed;top:24px;right:24px;background:#16a34a;color:#fff;padding:14px 22px;border-radius:12px;box-shadow:0 8px 24px rgba(22,163,74,0.35);display:flex;align-items:center;gap:10px;font-weight:600;font-size:.95rem;z-index:99999;transition:all .3s cubic-bezier(0.4,0,0.2,1);opacity:0;transform:translateY(-20px);pointer-events:none;">
    <i class="bi bi-check-circle-fill" style="font-size:1.3rem;"></i>
    <span id="user-toast-msg">Pengembalian berhasil! Terima kasih sudah meminjam barang.</span>
</div>

<div class="return-modal-overlay" id="return-success-modal">
    <div class="return-modal">
        <button class="modal-close" type="button" onclick="closeReturnModal()">&times;</button>
        <div class="return-success-icon"><i class="bi bi-check-lg"></i></div>
        <h2>Pengembalian Berhasil!</h2>
        <p>Terima kasih sudah meminjam dan merawat barang inventaris dengan baik! Data pengembalian telah berhasil dikonfirmasi dan masuk ke data admin.</p>
        <div class="return-proof" id="return-proof">
            <div class="proof-head">
                <div>
                    <strong>SIPIBS INVENTORY</strong>
                    <span>Bukti Pengembalian Barang</span>
                </div>
                <span class="proof-code" id="proof-code">RTN-2023-00021</span>
            </div>
            <div class="proof-row"><span>Nama Barang</span><strong id="proof-name">-</strong></div>
            <div class="proof-row"><span>ID Inventaris</span><strong id="proof-id">-</strong></div>
            <div class="proof-row"><span>Kondisi</span><strong id="proof-condition">Baik (Fungsional & Bersih)</strong></div>
            <div class="proof-row"><span>Catatan</span><strong id="proof-note">-</strong></div>
            <div class="proof-row"><span>Tanggal Kembali</span><strong id="proof-date">15 Agustus 2026</strong></div>
            <div class="proof-footer">Status: Menunggu Persetujuan Admin</div>
        </div>
        <div class="return-modal-actions">
            <button class="btn-modal-secondary" type="button" onclick="closeReturnModal()">Tutup</button>
            <button class="btn-modal-primary" type="button" onclick="printReturnProof()"><i class="bi bi-printer"></i> Cetak Bukti</button>
        </div>
    </div>
</div>

<script>
    (function () {
        function parseLoanList(key, fallback) {
            try {
                const value = JSON.parse(localStorage.getItem(key) || fallback);
                return Array.isArray(value) ? value : [];
            } catch (e) {
                return [];
            }
        }

        function readLoanSource() {
            const list = parseLoanList('sipibsLoanHistory', '[]');
            const extra = parseLoanList('sipibsReturnLoanItems', '[]');
            try {
                const request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
                if (request) list.unshift(request);
            } catch (e) {}
            try {
                const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
                if (decision && decision.request) list.unshift(decision.request);
            } catch (e) {}
            return extra.concat(list);
        }

        function parseDate(value) {
            if (!value) return null;
            const text = String(value).trim();
            if (text.includes('/')) {
                const parts = text.split('/');
                return new Date(Number(parts[2]), Number(parts[1]) - 1, Number(parts[0]));
            }
            const months = { jan:0, feb:1, mar:2, apr:3, mei:4, may:4, jun:5, jul:6, agu:7, aug:7, sep:8, okt:9, oct:9, nov:10, des:11, dec:11 };
            const parts = text.split(/\s+/);
            if (parts.length === 3 && months[parts[1].toLowerCase().slice(0,3)] !== undefined) {
                return new Date(Number(parts[2]), months[parts[1].toLowerCase().slice(0,3)], Number(parts[0]));
            }
            const date = new Date(text);
            return Number.isNaN(date.getTime()) ? null : date;
        }

        function formatDate(value) {
            const date = parseDate(value);
            if (!date) return value || '-';
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
        }

        function iconFor(name) {
            const itemName = String(name || '').toLowerCase();
            if (itemName.includes('headset') || itemName.includes('logitech')) return 'bi-headphones';
            if (itemName.includes('keyboard')) return 'bi-keyboard';
            if (itemName.includes('mouse')) return 'bi-mouse';
            if (itemName.includes('lan') || itemName.includes('cat6')) return 'bi-ethernet';
            if (itemName.includes('vga')) return 'bi-plug';
            if (itemName.includes('hdmi')) return 'bi-usb-c';
            if (itemName.includes('crimping') || itemName.includes('tang')) return 'bi-tools';
            if (itemName.includes('pointer') || itemName.includes('remote')) return 'bi-broadcast-pin';
            if (itemName.includes('laptop')) return 'bi-laptop';
            if (itemName.includes('tripod')) return 'bi-camera-reels';
            if (itemName.includes('mikroskop')) return 'bi-microscope';
            if (itemName.includes('kamera')) return 'bi-camera-fill';
            return 'bi-box-seam';
        }

        function dueBadge(dueValue) {
            const due = parseDate(dueValue);
            if (!due) return '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SIAP DIKEMBALIKAN</span>';
            due.setHours(0, 0, 0, 0);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (due.getTime() < today.getTime()) return '<span class="return-due-pill">TERLAMBAT</span>';
            return '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SIAP DIKEMBALIKAN</span>';
        }

        function renderLoansFromHistory() {
            const body = document.getElementById('return-items-body');
            if (!body) return;
            const defaults = Array.from(body.querySelectorAll('[data-id]')).map(row => ({
                id: row.dataset.id,
                barang: row.dataset.name,
                serial: row.dataset.serial,
                tanggalPinjam: row.dataset.pinjam,
                tanggalKembali: row.dataset.batas,
                status: 'Dipinjam'
            }));
            const seen = new Set();
            const items = readLoanSource().concat(defaults).filter(item => {
                const status = String(item.status || item.statusKey || 'Dipinjam').toLowerCase();
                if (status === 'ditolak' || status === 'rejected') return false;
                const key = String(item.id || item.borrowing_id || item.serial || item.barang || item.item || Math.random());
                if (seen.has(key)) return false;
                seen.add(key);
                return true;
            });
            body.innerHTML = '';
            items.forEach(item => {
                const name = item.barang || item.item || item.itemName || item.name || '-';
                const id = item.id || item.borrowing_id || item.serial || name;
                const serial = item.serial || item.kode || item.nis || id;
                const pinjam = formatDate(item.tanggalPinjam || item.pinjam || item.startDate);
                const batas = formatDate(item.tanggalKembali || item.kembali || item.dueDate || item.batas || item.endDate);
                const icon = iconFor(name);
                body.insertAdjacentHTML('beforeend', '<tr data-id="' + id + '" data-name="' + name + '" data-serial="' + serial + '" data-pinjam="' + pinjam + '" data-batas="' + batas + '" data-image="" data-icon="' + icon + '" data-from-history="1"><td><div class="return-item-meta"><span class="return-icon"><i class="bi ' + icon + '"></i></span><div><strong>' + name + '</strong><small>SN: ' + serial + '</small></div></div></td><td>' + pinjam + '</td><td><span class="return-due-date">' + batas + '</span>' + dueBadge(item.tanggalKembali || item.kembali || item.dueDate || item.batas || item.endDate) + '</td><td><button class="return-now-btn" type="button" onclick="return handleReturnNowClick(this);">Kembalikan Sekarang</button></td></tr>');
            });
            const badge = document.getElementById('return-pending-count');
            if (badge) badge.textContent = items.length + ' Tertunda';
        }

        renderLoansFromHistory();
        window.renderLoansFromHistory = renderLoansFromHistory;
    })();

    window.handleReturnNowClick = function(button) {
        const row = button && button.closest ? button.closest('[data-id]') : null;
        const formCard = document.getElementById('return-form-card');
        if (!row || !formCard) return false;

        const itemId = row.dataset.id || '';
        const itemName = row.dataset.name || (row.querySelector('strong') ? row.querySelector('strong').textContent.trim() : '');
        const returnId = document.getElementById('return-id');
        const returnName = document.getElementById('return-name');
        const returnNameDisplay = document.getElementById('return-name-display');

        document.querySelectorAll('#return-items-body [data-id]').forEach(item => item.classList.remove('selected'));
        document.querySelectorAll('.return-now-btn').forEach(itemButton => {
            itemButton.classList.remove('clicked');
            itemButton.textContent = 'Kembalikan Sekarang';
        });

        row.classList.add('selected');
        button.classList.add('clicked');
        button.textContent = 'Terpilih ✓';
        if (returnId) returnId.value = itemId;
        if (returnName) returnName.value = itemName;
        if (returnNameDisplay) {
            returnNameDisplay.value = itemName || 'Pilih barang dari daftar';
            returnNameDisplay.setAttribute('value', itemName || 'Pilih barang dari daftar');
        }

        formCard.classList.add('focused', 'selected-active', 'focus-pulse');
        formCard.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'center' });
        setTimeout(() => {
            const nameField = document.getElementById('return-name-display');
            if (nameField) nameField.focus({ preventScroll: true });
        }, 350);
        return false;
    };
</script>
<script>
    const invToggle = document.getElementById('inventaris-toggle');
    const invSub = document.getElementById('inventaris-sub');
    if (localStorage.getItem('invOpen') === '0') {
        invSub.classList.add('collapsed');
        invToggle.classList.remove('open');
    } else {
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

    const IMG_BASE = "{{ asset('images') }}";

    window.__csrf = "{{ csrf_token() }}";

    function getItemIcon(name) {
        if (!name) return 'bi-box-seam';
        const n = name.toLowerCase();
        if (n.includes('kamera') || n.includes('canon') || n.includes('dslr') || n.includes('eos')) return 'bi-camera-fill';
        if (n.includes('mikroskop') || n.includes('olympus')) return 'bi-microscope';
        if (n.includes('mouse')) return 'bi-mouse';
        if (n.includes('keyboard')) return 'bi-keyboard';
        if (n.includes('laptop') || n.includes('lenovo') || n.includes('notebook') || n.includes('dell')) return 'bi-laptop';
        if (n.includes('headset') || n.includes('headphone') || n.includes('logitech')) return 'bi-headphones';
        if (n.includes('webcam') || n.includes('video camera')) return 'bi-camera-video';
        if (n.includes('projector') || n.includes('proyektor') || n.includes('epson')) return 'bi-easel';
        if (n.includes('lan') || n.includes('cat6') || n.includes('networking') || n.includes('network cable')) return 'bi-ethernet';
        if (n.includes('hdmi')) return 'bi-usb-c';
        if (n.includes('vga')) return 'bi-plug';
        if (n.includes('kabel')) return 'bi-usb-plug';
        if (n.includes('pointer') || n.includes('remote') || n.includes('pen wireless')) return 'bi-broadcast-pin';
        if (n.includes('stop kontak') || n.includes('kontak')) return 'bi-outlet';
        if (n.includes('tester')) return 'bi-router';
        if (n.includes('crimping') || n.includes('tang')) return 'bi-tools';
        if (n.includes('jbl') || n.includes('speaker') || n.includes('boombox')) return 'bi-speaker';
        if (n.includes('tripod') || n.includes('promon')) return 'bi-camera-reels';
        return 'bi-box-seam';
    }

    function getItemImageName(name) {
        try {
            const items = JSON.parse(localStorage.getItem('sipibsMasterItems') || '[]');
            if (Array.isArray(items)) {
                const it = items.find(x => x.name === name);
                if (it && it.image) return it.image;
            }
        } catch (e) {}
        const map = {
            'Laptop Lenovo Ideapad Slim 3': 'Lenovo Ideapad Slim 3.jpg',
            'Mouse HP USB-2': 'mouse HP.png',
            'Keyboard NuPhy Air75': 'keyboard kantor.png',
            'Headset Logitech G 432 7.1': 'HEADSET LOGITECH.png',
            '4K Webcam 1080P 60fps Mini Video Camera': 'WEBCAM.jpg',
            'Kamera DSLR Canon EOS 80D': 'WEBCAM.jpg',
            'Epson EX3240 SVGA 3LCD Projector 3200': 'PROYEKTOR EPSON.jpg',
            'Kabel Black High Speed 1.4 Version Gold-Plated HDMI': 'kabel_hdmi.png',
            'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin': 'kabel vga.jpg',
            'Kabel LAN CAT6 UTP Cable Networking': 'KABEL LAN.jpg',
            'Kabel HDMI to VGA Adapter Gold Plated': 'KABEL HDMI TO VGA.jpg',
            'Pen Wireless Remote Controller Laser Pointer': 'Pointer.jpg',
            'Stop Kontak': 'STOP KONTAK.jpg',
            '2pcs Multifunctional network tester 468 network cable': 'LAN tester.jpg',
            'Tang Crimping Tool RJ45 RJ11 HT-200R': 'Tang Crimping.jpg',
            'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth': 'speaker portable.jpg'
        };
        return map[name] || '';
    }

    function getReturnImageSrc(image) {
        if (!image) return '';
        if (/^(https?:)?\/\//.test(image) || image.indexOf('data:image/') === 0) return image;
        return IMG_BASE + '/' + encodeURIComponent(image) + '?v=3';
    }

    function parseReturnDate(dateValue) {
        if (!dateValue) return null;
        const str = String(dateValue).trim();
        if (!str) return null;
        if (str.includes('/')) {
            const parts = str.split('/');
            if (parts.length === 3) {
                const d = parseInt(parts[0], 10);
                const m = parseInt(parts[1], 10) - 1;
                const y = parseInt(parts[2], 10);
                const dt = new Date(y, m, d);
                if (!Number.isNaN(dt.getTime())) return dt;
            }
        }
        const monthMap = { jan: 0, feb: 1, mar: 2, apr: 3, mei: 4, may: 4, jun: 5, jul: 6, agu: 7, aug: 7, sep: 8, okt: 9, oct: 9, nov: 10, des: 11, dec: 11 };
        const parts = str.split(/\s+/);
        if (parts.length === 3) {
            const d = parseInt(parts[0], 10);
            const mKey = parts[1].toLowerCase().substring(0, 3);
            const m = monthMap[mKey] !== undefined ? monthMap[mKey] : 0;
            const y = parseInt(parts[2], 10);
            const dt = new Date(y, m, d);
            if (!Number.isNaN(dt.getTime())) return dt;
        }
        const date = new Date(str.includes('T') ? str : (str + 'T00:00:00'));
        if (Number.isNaN(date.getTime())) return null;
        date.setHours(0, 0, 0, 0);
        return date;
    }

    function formatReturnDate(dateValue) {
        if (!dateValue) return '-';
        const date = parseReturnDate(dateValue);
        if (!date) return dateValue;
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const d = String(date.getDate());
        const m = months[date.getMonth()];
        const y = date.getFullYear();
        return d + ' ' + m + ' ' + y;
    }

    function getDueStatus(dueDateValue, loanId) {
        if (loanId === 'B802931-B') {
            return {
                late: true,
                pillHtml: '<span class="return-due-pill">TERLAMBAT</span>'
            };
        }
        if (loanId === 'OLY-X300') {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SISA 1 HARI</span>'
            };
        }

        const due = parseReturnDate(dueDateValue);
        if (!due) {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SIAP DIKEMBALIKAN</span>'
            };
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const diffTime = due.getTime() - today.getTime();
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays < 0) {
            return {
                late: true,
                pillHtml: '<span class="return-due-pill">TERLAMBAT</span>'
            };
        } else if (diffDays === 0) {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">HARI INI</span>'
            };
        } else if (diffDays === 1) {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SISA 1 HARI</span>'
            };
        } else {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SISA ' + diffDays + ' HARI</span>'
            };
        }
    }

    function buildReturnRow(loan) {
        const itemName = loan.barang || loan.namaBarang || loan.item || loan.itemName || loan.name || '-';
        const startDate = loan.tanggalPinjam || loan.startDate || loan.pinjam || loan.tglPinjam || '-';
        const dueDate = loan.tanggalKembali || loan.endDate || loan.dueDate || loan.batas || loan.tglKembali || '-';
        const itemImage = loan.image || getItemImageName(itemName);
        const loanId = loan.id || loan.borrowing_id || loan.kode || 'PMJ-' + Math.floor(1000 + Math.random() * 9000);
        const dueStatus = getDueStatus(dueDate, loanId);

        return {
            id: loanId,
            name: itemName,
            serial: loan.serial || loan.kode || loan.id || '-',
            pinjam: formatReturnDate(startDate),
            batas: formatReturnDate(dueDate),
            status: dueStatus.late ? 'TERLAMBAT' : 'SIAP DIKEMBALIKAN',
            late: dueStatus.late,
            duePillHtml: dueStatus.pillHtml,
            icon: getItemIcon(itemName),
            image: itemImage,
            fromHistory: !!loan.fromHistory,
            approved: true
        };
    }

    function getRawLoanHistory() {
        let history = [];
        try { history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]'); } catch (e) {}
        if (!Array.isArray(history)) history = [];
        try {
            const returnItems = JSON.parse(localStorage.getItem('sipibsReturnLoanItems') || '[]');
            if (Array.isArray(returnItems)) {
                returnItems.slice().reverse().forEach(item => {
                    if (item && (item.barang || item.item || item.namaBarang) && !history.some(loan => String(loan.id || loan.barang || loan.item || '') === String(item.id || item.barang || item.item || ''))) {
                        history.unshift(item);
                    }
                });
            }
        } catch (e) {}
        if (history.length === 0) {
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
                    status: 'Dipinjam'
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
                    status: 'Dipinjam'
                }
            ];
        }

        let decision = null;
        try { decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null'); } catch (e) {}
        let request = null;
        try { request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null'); } catch (e) {}
        const fallback = decision && decision.request ? decision.request : request;
        let list = history.slice();
        if (fallback && fallback.id && !list.some(loan => String(loan.id) === String(fallback.id))) {
            list.unshift(fallback);
        }
        return list;
    }

    function getStaticReturnedIds() {
        try {
            const list = JSON.parse(localStorage.getItem('sipibsStaticReturnedIds') || '[]');
            return Array.isArray(list) ? list : [];
        } catch (e) { return []; }
    }

    function getSubmittedReturnLoanIds() {
        try {
            const submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]');
            if (!Array.isArray(submissions)) return [];
            return submissions.map(item => String(item.loanId || item.id || '')).filter(Boolean);
        } catch (e) { return []; }
    }

    function saveStaticReturnedIds(list) {
        try {
            localStorage.setItem('sipibsStaticReturnedIds', JSON.stringify(Array.isArray(list) ? list : []));
        } catch (e) {}
    }

    function getDefaultBorrowedReturnLoans() {
        return [
            {
                id: 'B802931-B',
                nama: 'Ahmad Fauzi',
                nis: 'B802931-B',
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
                nis: 'OLY-X300',
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
                nis: 'LP-001-2023',
                barang: 'Laptop Dell Precision 3561',
                serial: 'LP-001-2023',
                kategori: 'Komputer',
                jumlah: 1,
                tanggalPinjam: '15/10/2023',
                tanggalKembali: '22/10/2023',
                status: 'Dipinjam'
            },
            {
                id: 'TR-005-2023',
                nama: 'Citra Dewi',
                nis: 'TR-005-2023',
                barang: 'Tripod Excell Promon 500',
                serial: 'TR-005-2023',
                kategori: 'Multimedia',
                jumlah: 1,
                tanggalPinjam: '14/10/2023',
                tanggalKembali: '20/10/2023',
                status: 'Dipinjam'
            },
            {
                id: 'PMJ-HS-025',
                nama: 'gashima',
                nis: '1234567890',
                barang: 'Headset Logitech G 432 7.1',
                serial: 'PMJ-HS-025',
                kategori: 'Audio Visual',
                jumlah: 1,
                tanggalPinjam: '01 Sep 2026',
                tanggalKembali: '01 Sep 2026',
                status: 'Dipinjam'
            },
            {
                id: 'PMJ-LAN-026',
                nama: 'kentil',
                nis: '1234567890',
                barang: 'Kabel LAN CAT6 UTP Cable Networking',
                serial: 'PMJ-LAN-026',
                kategori: 'Peralatan Kantor',
                jumlah: 1,
                tanggalPinjam: '01 Sep 2026',
                tanggalKembali: '03 Sep 2026',
                status: 'Dipinjam'
            },
            {
                id: 'PMJ-KBD-027',
                nama: 'pentol',
                nis: '1234567890',
                barang: 'Keyboard NuPhy Air75',
                serial: 'PMJ-KBD-027',
                kategori: 'Elektronik',
                jumlah: 1,
                tanggalPinjam: '01 Sep 2026',
                tanggalKembali: '03 Sep 2026',
                status: 'Dipinjam'
            },
            {
                id: 'PMJ-MOU-028',
                nama: 'gashima',
                nis: '1234567890',
                barang: 'Mouse HP USB-2',
                serial: 'PMJ-MOU-028',
                kategori: 'Elektronik',
                jumlah: 1,
                tanggalPinjam: '01 Sep 2026',
                tanggalKembali: '03 Sep 2026',
                status: 'Dipinjam'
            },
            {
                id: 'PMJ-PTR-029',
                nama: 'isal',
                nis: '1234567890',
                barang: 'Pen Wireless Remote Controller Laser Pointer',
                serial: 'PMJ-PTR-029',
                kategori: 'Elektronik',
                jumlah: 1,
                tanggalPinjam: '31 Agu 2026',
                tanggalKembali: '02 Sep 2026',
                status: 'Dipinjam'
            },
            {
                id: 'PMJ-VGA-030',
                nama: 'vanto',
                nis: '1234567890',
                barang: 'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin',
                serial: 'PMJ-VGA-030',
                kategori: 'Peralatan Kantor',
                jumlah: 1,
                tanggalPinjam: '31 Agu 2026',
                tanggalKembali: '02 Sep 2026',
                status: 'Dipinjam'
            },
            {
                id: 'PMJ-CRP-031',
                nama: 'gashima',
                nis: '1234567890',
                barang: 'Tang Crimping Tool RJ45 RJ11 HT-200R',
                serial: 'PMJ-CRP-031',
                kategori: 'Peralatan Kantor',
                jumlah: 1,
                tanggalPinjam: '31 Agu 2026',
                tanggalKembali: '02 Sep 2026',
                status: 'Dipinjam'
            },
            {
                id: 'PMJ-HDM-032',
                nama: 'gashima',
                nis: '1234567890',
                barang: 'Kabel Black High Speed 1.4 Version Gold-Plated HDMI',
                serial: 'PMJ-HDM-032',
                kategori: 'Peralatan Kantor',
                jumlah: 1,
                tanggalPinjam: '30 Agu 2026',
                tanggalKembali: '01 Sep 2026',
                status: 'Dipinjam'
            }
        ];
    }

    function isBorrowedLoan(loan) {
        if (!loan) return false;
        const status = String(loan.status || loan.statusKey || 'dipinjam').toLowerCase().trim();
        if (status === 'rejected' || status === 'ditolak') return false;
        
        // Cek apakah tanggal pengembalian sudah lewat
        const dueDate = loan.tanggalKembali || loan.endDate || loan.dueDate || loan.batas || loan.kembali || '';
        const parsedDue = parseReturnDate(dueDate);
        if (parsedDue) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (parsedDue.getTime() < today.getTime()) {
                return true; // Tanggal lewat otomatis masuk
            }
        }
        
        // Jika status masih dipinjam / aktif / menunggu
        if (status === 'dipinjam' || status === 'approved' || status === 'pending' || status === 'menunggu' || status === 'aktif') {
            return true;
        }
        return true;
    }

    function getReturnLoanRows(forceLoanId) {
        let apiItems = [];
        try { apiItems = window.__returnApiItems || []; } catch (e) {}
        if (!Array.isArray(apiItems)) apiItems = [];

        const apiIds = apiItems.map(loan => String(loan.id || loan.borrowing_id || ''));

        const rawHistory = getRawLoanHistory();
        const historyItems = rawHistory
            .filter(loan => isBorrowedLoan(loan))
            .filter(loan => !apiIds.includes(String(loan.id || loan.borrowing_id || '')))
            .map(loan => Object.assign({}, loan, { fromHistory: true }));

        const defaultItems = getDefaultBorrowedReturnLoans()
            .filter(loan => !historyItems.some(h => String(h.id || h.serial || '') === String(loan.id)))
            .filter(loan => !apiIds.includes(String(loan.id)))
            .map(loan => Object.assign({}, loan, { fromHistory: true }));

        const allCombined = [];
        const seen = new Set();

        // Urutan: API items -> historyItems (pinjaman riwayat terbaru di atas) -> defaultItems
        historyItems.concat(apiItems, defaultItems).forEach((loan, idx) => {
            const key = String(loan.id || loan.borrowing_id || ('loan-item-' + idx));
            if (!seen.has(key)) {
                seen.add(key);
                allCombined.push(loan);
            }
        });

        let base = allCombined.map(loan => buildReturnRow(loan));

        if (forceLoanId && !base.some(l => l.id === forceLoanId)) {
            const target = apiItems.concat(historyItems, defaultItems).find(loan => String(loan.id || loan.borrowing_id) === String(forceLoanId));
            if (target) base.push(buildReturnRow(target));
        }
        return base;
    }

    function saveLocalReturnSubmission(id, name, selectedRow, conditionLabel, note, photos, status) {
        let borrowerName = {{ json_encode($userName) }};
        try {
            const profile = JSON.parse(localStorage.getItem('sipibs_user_profile') || 'null');
            if (profile && (profile.name || profile.nama)) {
                borrowerName = profile.name || profile.nama;
            }
        } catch (e) {}
        if (!borrowerName || borrowerName === 'Admin SIPIBS') {
            const topUserName = document.getElementById('top-user-name');
            if (topUserName && topUserName.textContent.trim()) {
                borrowerName = topUserName.textContent.trim();
            }
        }

        try {
            const submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]');
            const newEntry = {
                id: id,
                loanId: id,
                borrower: borrowerName || 'Siswa SIPIBS',
                itemName: name,
                serial: (selectedRow && selectedRow.dataset && selectedRow.dataset.serial) || id,
                quantity: 1,
                condition: conditionLabel,
                note: note || '-',
                photos: photos || [],
                status: status || 'Menunggu Verifikasi',
                decision: null,
                submittedAt: new Date().toISOString()
            };
            const existingIdx = submissions.findIndex(s => String(s.id) === String(id) || String(s.loanId) === String(id));
            if (existingIdx > -1) {
                submissions[existingIdx] = newEntry;
            } else {
                submissions.unshift(newEntry);
            }
            localStorage.setItem('sipibsReturnSubmissions', JSON.stringify(submissions));
        } catch (e) {}

        try {
            const adminNotifs = JSON.parse(localStorage.getItem('sipibsAdminNotifications') || '[]');
            adminNotifs.unshift({
                id: 'ntf-admin-ret-' + Date.now(),
                title: 'Pengembalian Baru: ' + name,
                message: (borrowerName || 'Siswa') + ' mengajukan pengembalian untuk ' + name + ' (' + (conditionLabel || 'Baik') + ').',
                icon: 'bi-box-arrow-in-down-left',
                type: 'blue',
                read: false,
                url: '/admin/pengembalian',
                time: new Date().toISOString()
            });
            localStorage.setItem('sipibsAdminNotifications', JSON.stringify(adminNotifs));
        } catch (e) {}
    }

    function finishLocalReturn(id, name, selectedRow, conditionLabel, note, photos) {
        try {
            const returnedIds = getStaticReturnedIds();
            if (!returnedIds.map(x => String(x)).includes(String(id))) {
                returnedIds.push(id);
                saveStaticReturnedIds(returnedIds);
            }
        } catch (e) {}
        if (selectedRow && selectedRow.parentNode) {
            selectedRow.remove();
        }
        saveLocalReturnSubmission(id, name, selectedRow, conditionLabel, note, photos, 'Menunggu Verifikasi');
        updateReturnPendingCount();
        renderReturnHistory();
        renderReturnProofFromApi({
            id: id,
            serial: selectedRow.dataset.serial || id,
            condition: conditionLabel,
            note: note || '-',
            code: 'RTN-' + new Date().getFullYear() + '-' + String(Math.floor(10000 + Math.random() * 90000))
        }, name);
        showUserToast('Pengembalian Selesai! Data telah dikirim ke admin.');
        const modal = document.getElementById('return-success-modal');
        if (modal) modal.classList.add('active');
    }

    function loadReturnItemsFromApi() {
        return fetch('/api/pengembalian/items', {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) {
            if (!res.ok) return null;
            return res.json();
        })
        .then(function (data) {
            let items = data && Array.isArray(data.items) ? data.items : [];
            window.__returnApiLoaded = true;
            window.__returnApiItems = items;
            renderLatestReturnLoan();
            if (window.renderLoansFromHistory) window.renderLoansFromHistory();
            return items;
        })
        .catch(function () {
            window.__returnApiLoaded = true;
            window.__returnApiItems = [];
            renderLatestReturnLoan();
            if (window.renderLoansFromHistory) window.renderLoansFromHistory();
            return [];
        });
    }

    function renderLatestReturnLoan() {
        const body = document.getElementById('return-items-body');
        if (!body) return;
        const urlParams = new URLSearchParams(window.location.search);
        const urlLoanId = urlParams.get('loanId') || '';
        const rows = getReturnLoanRows(urlLoanId);
        body.innerHTML = '';

        if (rows.length === 0) {
            updateReturnPendingCount();
            return;
        }

        rows.forEach(loan => {
            const tr = document.createElement('tr');
            tr.dataset.id = loan.id;
            tr.dataset.name = loan.name;
            tr.dataset.serial = loan.serial;
            tr.dataset.pinjam = loan.pinjam;
            tr.dataset.batas = loan.batas;
            tr.dataset.image = getReturnImageSrc(loan.image);
            tr.dataset.icon = loan.icon;
            tr.dataset.fromHistory = loan.fromHistory ? '1' : '0';

            let iconHtml = '<i class="bi ' + loan.icon + '"></i>';
            if (loan.icon === 'bi-microscope' || loan.id === 'OLY-X300') {
                iconHtml = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h8"></path><path d="M3 22h18"></path><path d="M14 22a7 7 0 1 0 0-14h-1"></path><path d="M9 14h2"></path><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"></path><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"></path></svg>';
            }

            const iconContent = (loan.image && !loan.image.includes('no-image') && loan.id !== 'B802931-B' && loan.id !== 'OLY-X300')
                ? '<img src="' + getReturnImageSrc(loan.image) + '" alt="' + loan.name + '" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">'
                : iconHtml;

            tr.innerHTML = 
                '<td>' +
                    '<div class="return-item-meta">' +
                        '<span class="return-icon">' + iconContent + '</span>' +
                        '<div>' +
                            '<strong>' + loan.name + '</strong>' +
                            '<small>SN: ' + (loan.serial || loan.id) + '</small>' +
                        '</div>' +
                    '</div>' +
                '</td>' +
                '<td>' + loan.pinjam + '</td>' +
                '<td>' +
                    '<span class="return-due-date ' + (loan.late ? '' : 'safe') + '">' + loan.batas + '</span>' +
                    loan.duePillHtml +
                '</td>' +
                '<td>' +
                    '<button class="return-now-btn" type="button" onclick="return handleReturnNowClick(this);">Kembalikan Sekarang</button>' +
                '</td>';
            body.appendChild(tr);
        });
        updateReturnPendingCount();
    }

    function updateReturnPendingCount() {
        const body = document.getElementById('return-items-body');
        const pendingCount = document.getElementById('return-pending-count');
        if (!body || !pendingCount) return;
        const rows = Array.from(body.querySelectorAll('[data-id]'));
        if (rows.length === 0) {
            pendingCount.textContent = '0 Tertunda';
            body.innerHTML = '<tr><td colspan="4" class="return-empty"><i class="bi bi-inbox" style="font-size:2.2rem;display:block;margin-bottom:10px;"></i>Tidak ada barang yang perlu dikembalikan.</td></tr>';
            return;
        }
        pendingCount.textContent = rows.length + ' Tertunda';
    }

    window.__returnApiItems = [];
    window.__returnApiLoaded = false;
    try { localStorage.removeItem('sipibsSelectedReturnId'); } catch (e) {}
    renderLatestReturnLoan();
    if (window.renderLoansFromHistory) window.renderLoansFromHistory();
    try { loadReturnItemsFromApi(); } catch (e) {}
    try { renderReturnHistory(); } catch (e) {}

    function paintHistoryBody(combined) {
        const historyBody = document.getElementById('return-history-body');
        if (!historyBody) return;

        if (combined.length === 0) {
            historyBody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px;color:#888;">Belum ada riwayat pengembalian.</td></tr>';
            return;
        }

        historyBody.innerHTML = '';
        combined.forEach(sub => {
            const cond = sub.condition || 'Baik';
            let condClass = 'good';
            let condSymbol = '●';
            let condLabel = 'Baik';

            if (cond.includes('Rusak Berat')) {
                condClass = 'bad';
                condSymbol = '▪';
                condLabel = 'Rusak Berat';
            } else if (cond.includes('Rusak')) {
                condClass = 'warn';
                condSymbol = '▪';
                condLabel = 'Rusak Ringan';
            }

            const dateStr = sub.dateDisplay || (sub.submittedAt ? formatReturnDate(sub.submittedAt.split('T')[0]) : '-');
            let statusLabel = sub.status || 'Selesai';
            let noteContent = sub.note || '-';
            if (noteContent.startsWith('"') && noteContent.endsWith('"')) {
                noteContent = noteContent.slice(1, -1);
            }
            let noteDisplay = '"' + noteContent + '"';

            historyBody.insertAdjacentHTML('beforeend',
                '<tr>' +
                    '<td>' +
                        '<div class="return-hist-meta">' +
                            '<strong>' + (sub.itemName || sub.name || '-') + '</strong>' +
                            '<small style="display:block;color:#64748b;font-size:11.5px;margin-top:2px;">Inv: ' + (sub.serial || sub.id || sub.loanId || '-') + '</small>' +
                        '</div>' +
                    '</td>' +
                    '<td>' + dateStr + '</td>' +
                    '<td><span class="return-cond-pill ' + condClass + '"><span class="cond-symbol">' + condSymbol + '</span> ' + condLabel + '</span></td>' +
                    '<td style="color:#475569;font-size:12.5px;max-width:240px;line-height:1.35;">' + noteDisplay + '</td>' +
                    '<td><span class="return-status-pill done"><i class="bi bi-check-circle"></i> ' + statusLabel + '</span></td>' +
                '</tr>'
            );
        });
    }

    function renderReturnHistory() {
        const historyBody = document.getElementById('return-history-body');
        if (!historyBody) return;

        fetch('/api/pengembalian/history', {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('fail');
            return res.json();
        })
        .then(function (data) {
            const list = data && Array.isArray(data.history) ? data.history : [];
            paintHistoryBody(list);
        })
        .catch(function () {
            let submissions = [];
            try { submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]'); } catch (e) {}
            if (!Array.isArray(submissions)) submissions = [];
            paintHistoryBody(submissions);
        });
    }

    function applySelectedReturnItem(row, button) {
        if (!row) return;
        const itemId = row.dataset.id || (row.querySelector('small') ? row.querySelector('small').textContent.replace('SN:', '').trim() : '');
        const itemName = row.dataset.name || (row.querySelector('strong') ? row.querySelector('strong').textContent.trim() : '');
        document.querySelectorAll('#return-items-body [data-id]').forEach(item => item.classList.remove('selected'));
        document.querySelectorAll('.return-now-btn').forEach(btn => {
            btn.classList.remove('clicked');
            if (btn.dataset.activeBtn === '1') {
                btn.innerHTML = 'Kembalikan Sekarang';
                delete btn.dataset.activeBtn;
            }
        });
        row.classList.add('selected');
        if (button) {
            button.classList.add('clicked');
            button.dataset.activeBtn = '1';
            button.innerHTML = 'Terpilih ✓';
        }
        const idInput = document.getElementById('return-id');
        if (idInput) idInput.value = itemId;
        const nameInput = document.getElementById('return-name');
        if (nameInput) nameInput.value = itemName;
        const nameDisplay = document.getElementById('return-name-display');
        if (nameDisplay) {
            nameDisplay.value = itemName || 'Pilih barang dari daftar';
            nameDisplay.setAttribute('value', itemName || 'Pilih barang dari daftar');
            nameDisplay.style.background = '#e0edff';
            nameDisplay.style.color = '#003884';
            nameDisplay.style.fontWeight = '800';
        }
        updateSelectedReturnPreview(row);
    }

    function handleReturnNowClick(button) {
        selectReturnItem(button);
        return false;
    }

    function selectReturnItem(button) {
        const row = button && button.closest ? button.closest('[data-id]') : null;
        if (!row) return;
        try { console.log('[SIPIBS] selectReturnItem =>', row.dataset.name || row.dataset.id, 'button=', button); } catch (e) {}
        const formCard = document.getElementById('return-form-card');
        applySelectedReturnItem(row, button);
        localStorage.setItem('sipibsSelectedReturnId', row.dataset.id || '');
        if (formCard) {
            formCard.classList.add('focused');
            formCard.classList.add('selected-active');
            formCard.classList.remove('focus-pulse');
            void formCard.offsetWidth;
            formCard.classList.add('focus-pulse');
            formCard.style.outline = '4px solid rgba(0, 56, 132, .25)';
            try {
                formCard.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
            } catch (e) {
                try { formCard.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' }); } catch (err) {}
            }
            setTimeout(() => {
                formCard.style.outline = '';
                const nameField = document.getElementById('return-name-display');
                if (nameField) {
                    nameField.focus({ preventScroll: false });
                }
                const condSelect = document.getElementById('return-condition');
                if (condSelect) condSelect.focus({ preventScroll: false });
            }, 250);
        }
    }
    window.applySelectedReturnItem = applySelectedReturnItem;
    window.selectReturnItem = selectReturnItem;
    window.handleReturnNowClick = handleReturnNowClick;
    function updateSelectedReturnPreview(row) {
        const selectedItem = document.getElementById('return-selected-item');
        const photo = document.getElementById('return-selected-photo');
        const name = document.getElementById('return-selected-name');
        const serial = document.getElementById('return-selected-serial');
        if (!row || !selectedItem || !photo || !name || !serial) return;
        const itemName = row.dataset.name || (row.querySelector('strong') ? row.querySelector('strong').textContent.trim() : '-');
        const itemSerial = row.dataset.serial || row.dataset.id || '-';
        selectedItem.hidden = false;
        name.textContent = itemName;
        serial.textContent = 'SN: ' + itemSerial;
        if (row.dataset.image) {
            photo.innerHTML = `<img src="${row.dataset.image}" alt="${itemName || 'Foto barang'}" onerror="this.onerror=null;this.parentNode.innerHTML='<i class=&quot;bi ${row.dataset.icon || 'bi-box-seam'}&quot;></i>';">`;
        } else {
            photo.innerHTML = `<i class="bi ${row.dataset.icon || 'bi-box-seam'}"></i>`;
        }
    }

    const returnPhotoUpload = document.getElementById('return-photo-upload');
    const returnPhotoInput = document.getElementById('return-photo');
    const returnUploadEmpty = document.getElementById('return-upload-empty');
    const returnUploadPreview = document.getElementById('return-upload-preview');
    const returnPhotoList = document.getElementById('return-photo-list');
    const returnUploadAdd = document.getElementById('return-upload-add');
    const selectedReturnPhotos = window.sipibsSelectedReturnPhotos || [];
    window.sipibsSelectedReturnPhotos = selectedReturnPhotos;
    const maxReturnPhotos = 5;

    function formatFileSize(bytes) {
        if (!bytes || bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function renderReturnPhotos() {
        const list = document.getElementById('return-photo-list');
        const empty = document.getElementById('return-upload-empty');
        const preview = document.getElementById('return-upload-preview');
        const countEl = document.getElementById('return-photo-count');
        const hint = document.getElementById('return-photo-hint');

        if (!list || !empty || !preview) return;

        list.innerHTML = '';

        selectedReturnPhotos.forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'return-photo-item';
            
            let imgUrl = '';
            try {
                imgUrl = URL.createObjectURL(file);
            } catch (e) {
                imgUrl = '';
            }

            const fileName = file.name || ('Foto-' + (index + 1) + '.jpg');
            const fileSize = formatFileSize(file.size);

            item.innerHTML = `
                <div class="return-photo-item-thumb">
                    ${imgUrl ? `<img src="${imgUrl}" alt="${fileName}">` : `<i class="bi bi-image" style="font-size:20px;color:#0055c8;"></i>`}
                </div>
                <div class="return-photo-item-info">
                    <span class="return-photo-item-name" title="${fileName}">${fileName}</span>
                    <span class="return-photo-item-size">${fileSize}</span>
                </div>
                <button type="button" class="return-photo-item-del" data-index="${index}" title="Hapus foto" aria-label="Hapus foto">
                    <i class="bi bi-trash-fill"></i>
                </button>
            `;
            list.appendChild(item);
        });

        if (selectedReturnPhotos.length > 0) {
            empty.style.display = 'none';
            preview.style.display = 'block';
            preview.removeAttribute('hidden');
            if (countEl) countEl.textContent = selectedReturnPhotos.length + ' / ' + maxReturnPhotos + ' foto';
            if (hint) hint.style.display = 'none';
            if (returnPhotoUpload) returnPhotoUpload.classList.remove('photo-missing');
        } else {
            empty.style.display = 'block';
            preview.style.display = 'none';
            preview.setAttribute('hidden', 'hidden');
        }

        toggleConfirmBtn();
    }

    function toggleConfirmBtn() {
        const selected = document.getElementById('return-id') ? document.getElementById('return-id').value : '';
        const isReady = Boolean(selected) && selectedReturnPhotos.length > 0;
        const formCard = document.getElementById('return-form-card');
        if (!formCard) return;
        if (isReady && !formCard.classList.contains('filled-complete')) {
            formCard.classList.add('filled-complete');
        } else if (!isReady) {
            formCard.classList.remove('filled-complete');
        }
    }

    function addReturnPhotos(files) {
        if (!files || files.length === 0) return;
        const remainingSlots = maxReturnPhotos - selectedReturnPhotos.length;
        if (remainingSlots <= 0) {
            alert('Maksimal 5 foto barang sudah tercapai.');
            return;
        }

        Array.from(files).slice(0, remainingSlots).forEach(file => {
            const isImage = (file.type && file.type.startsWith('image/')) || /\.(jpe?g|png|webp|jfif|gif|bmp|svg|heic|heif)$/i.test(file.name || '');

            if (!isImage) {
                alert('File "' + (file.name || 'file') + '" bukan format gambar yang didukung (Gunakan JPG, PNG, WebP).');
                return;
            }

            if (file.size > 15 * 1024 * 1024) {
                alert('Ukuran foto "' + file.name + '" terlalu besar! Maksimal 15 MB.');
                return;
            }

            selectedReturnPhotos.push(file);
        });

        if (files.length > remainingSlots) {
            alert('Hanya ' + remainingSlots + ' foto pertama yang dapat ditambahkan (maksimal 5 foto).');
        }

        renderReturnPhotos();
    }

    window.handleReturnPhotoFiles = addReturnPhotos;
    window.addReturnPhotos = addReturnPhotos;
    window.renderReturnPhotos = renderReturnPhotos;
    window.addReturnPhotosFromInput = function (input) {
        if (!input || !input.files || input.files.length === 0) return;
        addReturnPhotos(input.files);
        input.value = '';
    };

    function validateReturnPhotos() {
        if (selectedReturnPhotos.length === 0) {
            const hint = document.getElementById('return-photo-hint');
            if (hint) hint.style.display = 'block';
            if (returnPhotoUpload) {
                returnPhotoUpload.classList.add('photo-missing');
                returnPhotoUpload.scrollIntoView({ behavior: 'smooth', block: 'center' });
                returnPhotoUpload.focus();
                setTimeout(() => returnPhotoUpload.classList.remove('photo-missing'), 2500);
            }
            return false;
        }
        const hint = document.getElementById('return-photo-hint');
        if (hint) hint.style.display = 'none';
        return true;
    }

    function compressImageFile(file, maxWidth = 800, maxHeight = 800, quality = 0.75) {
        return new Promise((resolve) => {
            if (!file || !file.type || !file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => resolve({ name: file ? file.name : 'foto.jpg', src: e.target.result });
                reader.onerror = () => resolve({ name: file ? file.name : 'foto.jpg', src: '' });
                reader.readAsDataURL(file);
                return;
            }
            const img = new Image();
            const reader = new FileReader();
            reader.onload = (e) => {
                img.onload = () => {
                    let width = img.width || 800;
                    let height = img.height || 600;
                    if (width > height) {
                        if (width > maxWidth) {
                            height = Math.round((height * maxWidth) / width);
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width = Math.round((width * maxHeight) / height);
                            height = maxHeight;
                        }
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    const dataUrl = canvas.toDataURL('image/jpeg', quality);
                    resolve({ name: file.name, src: dataUrl });
                };
                img.onerror = () => {
                    resolve({ name: file.name, src: e.target.result });
                };
                img.src = e.target.result;
            };
            reader.onerror = () => resolve({ name: file.name, src: '' });
            reader.readAsDataURL(file);
        });
    }

    function readReturnPhotosAsDataUrls() {
        return Promise.all(selectedReturnPhotos.map(file => compressImageFile(file)));
    }

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function (eventName) {
        document.addEventListener(eventName, function (event) {
            event.preventDefault();
        }, false);
    });

    if (returnPhotoInput) {
        returnPhotoInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                addReturnPhotos(this.files);
            }
            this.value = '';
        });
    }

    if (returnPhotoUpload) {
        returnPhotoUpload.addEventListener('click', function (event) {
            const delBtn = event.target.closest('.return-photo-item-del');
            if (delBtn) {
                event.preventDefault();
                event.stopPropagation();
                const idx = parseInt(delBtn.dataset.index, 10);
                if (!isNaN(idx) && idx >= 0 && idx < selectedReturnPhotos.length) {
                    selectedReturnPhotos.splice(idx, 1);
                    renderReturnPhotos();
                }
                return;
            }

            const addBtn = event.target.closest('#return-upload-add');
            if (addBtn) {
                event.preventDefault();
                event.stopPropagation();
                if (selectedReturnPhotos.length < maxReturnPhotos) {
                    if (returnPhotoInput) returnPhotoInput.click();
                } else {
                    alert('Maksimal 5 foto barang sudah tercapai.');
                }
                return;
            }

            if (selectedReturnPhotos.length === 0) {
                if (returnPhotoInput) returnPhotoInput.click();
            }
        });

        returnPhotoUpload.addEventListener('dragover', function (event) {
            event.preventDefault();
            event.stopPropagation();
            returnPhotoUpload.classList.add('dragging');
        });

        returnPhotoUpload.addEventListener('dragleave', function (event) {
            event.preventDefault();
            event.stopPropagation();
            returnPhotoUpload.classList.remove('dragging');
        });

        returnPhotoUpload.addEventListener('drop', function (event) {
            event.preventDefault();
            event.stopPropagation();
            returnPhotoUpload.classList.remove('dragging');
            if (event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                addReturnPhotos(event.dataTransfer.files);
            }
        });
    }

    function mapReturnCondition(label) {
        const s = String(label || '').toLowerCase();
        if (s.includes('rusak berat')) return 'rusak';
        if (s.includes('rusak')) return 'perlu_servis';
        return 'baik';
    }

    function renderReturnProofFromApi(data, name) {
        if (document.getElementById('proof-name')) document.getElementById('proof-name').textContent = name || (data && data.itemName) || '-';
        if (document.getElementById('proof-id')) document.getElementById('proof-id').textContent = (data && (data.borrowing_id || data.serial || data.id)) || '-';
        if (document.getElementById('proof-condition')) document.getElementById('proof-condition').textContent = (data && data.condition) || 'Baik (Fungsional & Bersih)';
        if (document.getElementById('proof-note')) document.getElementById('proof-note').textContent = (data && data.note) || '-';
        const proofDate = document.getElementById('proof-date');
        if (proofDate) {
            proofDate.textContent = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }
        const proofCode = document.getElementById('proof-code');
        if (proofCode) {
            proofCode.textContent = (data && data.code) || ('RTN-' + new Date().getFullYear() + '-' + String(Math.floor(10000 + Math.random() * 90000)));
        }
    }

    async function confirmReturn(event) {
        if (event) {
            if (event.preventDefault) event.preventDefault();
            if (event.stopPropagation) event.stopPropagation();
        }

        if (selectedReturnPhotos.length === 0 && returnPhotoInput && returnPhotoInput.files && returnPhotoInput.files.length > 0) {
            addReturnPhotos(returnPhotoInput.files);
        }

        let id = (document.getElementById('return-id') ? document.getElementById('return-id').value : '') || '';
        let name = (document.getElementById('return-name') ? document.getElementById('return-name').value : '') || '';

        let selectedRow = document.querySelector('#return-items-body [data-id].selected') ||
                          document.querySelector('#return-items-body [data-id="' + id + '"]') ||
                          document.querySelector('#return-items-body [data-id]');

        if ((!id || !name) && selectedRow) {
            id = selectedRow.dataset.id || id;
            name = selectedRow.dataset.name || name;
            if (document.getElementById('return-id')) document.getElementById('return-id').value = id;
            if (document.getElementById('return-name')) document.getElementById('return-name').value = name;
        }

        if (!selectedRow || !id) {
            alert('Silakan pilih barang terlebih dahulu dari daftar.');
            return;
        }

        if (selectedReturnPhotos.length === 0) {
            validateReturnPhotos();
            return;
        }

        if (document.getElementById('return-name-display') && !name) {
            name = document.getElementById('return-name-display').value || name;
        }

        const conditionLabel = (document.getElementById('return-condition') ? document.getElementById('return-condition').value : '') || 'Baik (Fungsional & Bersih)';
        const note = (document.getElementById('return-note') ? document.getElementById('return-note').value.trim() : '') || '';

        let photos = [];
        try {
            if (selectedReturnPhotos && selectedReturnPhotos.length > 0) {
                photos = await readReturnPhotosAsDataUrls();
            }
        } catch (e) {
            photos = [];
        }

        const submitBtn = document.getElementById('return-confirm-btn');
        if (submitBtn) {
            submitBtn.disabled = true;
        }

        try {
            if (selectedRow.dataset.fromHistory === '1') {
                finishLocalReturn(id, name, selectedRow, conditionLabel, note, photos);
                return;
            }

            const res = await fetch('/api/pengembalian', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.__csrf || ''
                },
                body: JSON.stringify({
                    borrowing_id: id,
                    condition: mapReturnCondition(conditionLabel),
                    notes: note,
                    photos: photos
                })
            });
            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                finishLocalReturn(id, name, selectedRow, conditionLabel, note, photos);
                return;
            }

            if (data && data.return) {
                const code = data.return.code;
                if (document.getElementById('proof-code') && code) {
                    document.getElementById('proof-code').textContent = code;
                }
            }

            const returnRow = selectedRow;
            if (returnRow && returnRow.parentNode) {
                returnRow.remove();
            }

            saveLocalReturnSubmission(id, name, selectedRow, conditionLabel, note, photos, 'Menunggu Verifikasi');

            updateReturnPendingCount();
            loadReturnItemsFromApi();
            renderReturnHistory();

            renderReturnProofFromApi(data && data.return, name);

            showUserToast('Pengembalian Selesai! Data telah dikirim ke admin.');

            try {
                const notifs = JSON.parse(localStorage.getItem('sipibsUserNotifications') || '[]');
                notifs.unshift({
                    id: 'ntf-' + Date.now(),
                    title: 'Pengembalian Selesai',
                    message: 'Pengembalian barang ' + name + ' selesai. Terima kasih sudah meminjam!',
                    icon: 'bi-check-circle',
                    type: 'green',
                    read: false,
                    time: new Date().toISOString()
                });
                localStorage.setItem('sipibsUserNotifications', JSON.stringify(notifs));
            } catch (e) {}

            const modal = document.getElementById('return-success-modal');
            if (modal) modal.classList.add('active');
        } catch (err) {
            finishLocalReturn(id, name, selectedRow, conditionLabel, note, photos);
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    function showUserToast(msg) {
        const toast = document.getElementById('user-toast');
        const toastMsg = document.getElementById('user-toast-msg');
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        toast.style.pointerEvents = 'auto';
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            toast.style.pointerEvents = 'none';
        }, 4000);
    }

    function resetReturnForm() {
        document.querySelectorAll('#return-items-body [data-id]').forEach(item => item.classList.remove('selected'));
        document.querySelectorAll('.return-now-btn').forEach(btn => {
            btn.classList.remove('clicked');
            if (btn.dataset.activeBtn === '1') {
                btn.innerHTML = 'Kembalikan Sekarang';
                delete btn.dataset.activeBtn;
            }
        });
        const formCard = document.getElementById('return-form-card');
        if (formCard) { formCard.classList.remove('selected-active'); formCard.classList.remove('filled-complete'); }
        const condSelect = document.getElementById('return-condition');
        if (condSelect) condSelect.selectedIndex = 0;
        const noteEl = document.getElementById('return-note');
        if (noteEl) noteEl.value = '';
        selectedReturnPhotos.length = 0;
        renderReturnPhotos();
        const idInput = document.getElementById('return-id'); if (idInput) idInput.value = '';
        const nameInput = document.getElementById('return-name'); if (nameInput) nameInput.value = '';
        const nameDisplay = document.getElementById('return-name-display');
        if (nameDisplay) { nameDisplay.value = 'Pilih barang dari daftar'; nameDisplay.style.background = '#eef7ff'; nameDisplay.style.color = '#003985'; }
        const selectedItem = document.getElementById('return-selected-item');
        if (selectedItem) selectedItem.hidden = true;
    }

    function closeReturnModal() {
        const modal = document.getElementById('return-success-modal');
        if (modal) modal.classList.remove('active');
        const formCard = document.getElementById('return-form-card');
        if (formCard) formCard.classList.remove('focused');
        updateReturnPendingCount();
        resetReturnForm();
    }

    function printReturnProof() { window.print(); }

    window.applySelectedReturnItem = applySelectedReturnItem;
    window.selectReturnItem = selectReturnItem;
    window.confirmReturn = confirmReturn;
    window.doSubmitReturn = confirmReturn;
    window.closeReturnModal = closeReturnModal;
    window.printReturnProof = printReturnProof;
    window.handleReturnNowClick = handleReturnNowClick;

    const returnForm = document.getElementById('return-form');
    if (returnForm) {
        returnForm.addEventListener('submit', function (e) { e.preventDefault(); confirmReturn(e); });
    }

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.return-now-btn');
        if (!button) return;
        event.preventDefault();
        event.stopPropagation();
        selectReturnItem(button);
    });

    window.history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);

    const returnSearch = document.getElementById('returnSearch');
    if (returnSearch) {
        returnSearch.addEventListener('input', function () {
            const q = returnSearch.value.toLowerCase().trim();
            const countLabel = document.getElementById('return-pending-count');
            const rows = document.querySelectorAll('#return-items-body [data-name]');
            let visible = 0;
            rows.forEach(function (row) {
                const rowName = (row.dataset.name || '').toLowerCase();
                const serial = (row.dataset.serial || '').toLowerCase();
                const match = !q || rowName.includes(q) || serial.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (countLabel) countLabel.textContent = q ? (visible + ' Terlihat') : (rows.length + ' Tertunda');
        });
    }

    window.addEventListener('storage', function (event) {
        const relevantKeys = ['sipibsLoanHistory', 'sipibsLoanDecision', 'sipibsLoanRequest', 'sipibsReturnLoanItems', 'sipibsReturnSubmissions'];
        if (relevantKeys.indexOf(event.key) < 0) return;
        try { loadReturnItemsFromApi(); } catch (e) {}
        if (window.renderLoansFromHistory) window.renderLoansFromHistory();
        try { renderReturnHistory(); } catch (e) {}
    });

    window.addEventListener('load', function () {
        setTimeout(function () {
            try { loadReturnItemsFromApi(); } catch (e) {}
            if (window.renderLoansFromHistory) window.renderLoansFromHistory();
            try { renderReturnHistory(); } catch (e) {}
        }, 150);
    });

    function openRiwayatPinjamDetail(index) {
        const history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
        const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
        const request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
        const fallback = decision && decision.request ? decision.request : request;
        let list = history.slice();
        if (fallback && fallback.id && !list.some(item => item.id === fallback.id)) list.unshift(fallback);
        const items = list.slice().reverse().slice(0, 5);
        const loan = items[index];
        if (!loan) return;
        alert('Detail Peminjaman\n\nID: ' + (loan.id || '-') + '\nBarang: ' + (loan.barang || '-') + '\nKategori: ' + (loan.kategori || '-') + '\nTanggal Pinjam: ' + (loan.tanggalPinjam || '-') + '\nTanggal Kembali: ' + (loan.tanggalKembali || '-') + '\nJumlah: ' + (loan.jumlah || '-') + '\nStatus: ' + (loan.status || 'pending'));
    }
</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>





