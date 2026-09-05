@php
    $active = 'peminjaman';
    $title = 'Peminjaman - SIPIBS Admin';
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
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/admin-peminjaman.css') }}?v=4">
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
        <header class="topbar">
            <div class="page-label">Peminjaman</div>
            <div class="top-actions">
                @include('admin.partials.notification-bell')
                <i class="bi bi-question-circle"></i>
                <div class="top-user">
                    <div><strong id="top-user-name">Admin</strong><span>Administrator</span></div>
                    <img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin">
                </div>
            </div>
        </header>

        <section class="admin-loan-container">
            <div class="admin-loan-header">
                <h1>Peminjaman</h1>
                <p>Kelola permintaan peminjaman barang dari user</p>
            </div>

            <div class="loan-shell-card">
                <!-- Status Filter Tabs -->
                <div class="loan-tabs" id="loanTabs">
                    <button class="loan-tab-btn active" data-tab="all">
                        Semua <span class="tab-badge" id="countAll">0</span>
                    </button>
                    <button class="loan-tab-btn" data-tab="pending">
                        Menunggu Persetujuan <span class="tab-badge" id="countPending">0</span>
                    </button>
                    <button class="loan-tab-btn" data-tab="approved">
                        Disetujui <span class="tab-badge" id="countApproved">0</span>
                    </button>
                    <button class="loan-tab-btn" data-tab="rejected">
                        Ditolak <span class="tab-badge" id="countRejected">0</span>
                    </button>
                    <button class="loan-tab-btn" data-tab="returned">
                        Selesai <span class="tab-badge" id="countReturned">0</span>
                    </button>
                </div>

                <!-- Search & Date Filter Bar -->
                <div class="loan-controls">
                    <div class="loan-search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="loanSearch" placeholder="Cari nama peminjam atau barang...">
                    </div>
                    <button class="loan-filter-btn" type="button">
                        <i class="bi bi-calendar3"></i> Filter Tanggal <i class="bi bi-chevron-down"></i>
                    </button>
                </div>

                <!-- Cards Container -->
                <div class="loan-cards-list" id="loanCardsContainer"></div>

                <!-- Pagination Footer -->
                <div class="loan-pagination">
                    <div class="pagination-info" id="paginationInfo">Menampilkan 0 - 0 dari 0 data</div>
                    <div class="loan-page-nav">
                        <button class="loan-page-btn" disabled>&lt;</button>
                        <button class="loan-page-btn active">1</button>
                        <button class="loan-page-btn" disabled>&gt;</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    window.PEMINJAMAN_DEFAULT_PHOTO = '{{ asset("images/PROYEKTOR EPSON.jpg") }}';
</script>
<script src="{{ asset('js/admin-peminjaman.js') }}?v=6"></script>
@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>
