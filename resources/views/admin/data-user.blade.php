@php
    $active = 'user';
    $title = 'Data Pengguna Sistem';
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
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=28">
</head>
<body>
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
            <input class="admin-search" placeholder="Cari ...">
            <div class="top-actions">@include('admin.partials.notification-bell')<i class="bi bi-question-circle"></i><div class="top-user"><div><strong id="top-user-name">Admin SIPIBS</strong><span>SUPER ADMIN</span></div><img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin"></div></div>
        </header>
        <section class="content">
            <div class="admin-wrap">
                        <div class="admin-page-head"><div><h1>Data Pengguna Sistem</h1><p>Kelola data siswa, guru, dan staf yang terdaftar dalam sistem.</p></div></div>
                        <div class="admin-stats stats-col-3"><div class="admin-stat"><span class="admin-stat-icon"><i class="bi bi-people"></i></span><div><small>Total Pengguna</small><strong id="userTotalStat">0</strong></div></div><div class="admin-stat"><span class="admin-stat-icon"><i class="bi bi-mortarboard"></i></span><div><small>Siswa Aktif</small><strong id="userSiswaStat">0</strong></div></div><div class="admin-stat"><span class="admin-stat-icon gray"><i class="bi bi-person-workspace"></i></span><div><small>Guru / Staf</small><strong id="userGuruStat">0</strong></div></div></div>
                        <div class="admin-panel user-master-panel"><div class="admin-panel-head"><div class="user-search-wrap"><i class="bi bi-search"></i><input class="admin-search" id="userSearch" placeholder="Cari nama, NIS, atau role..."></div><div class="admin-panel-tools"><button type="button" class="btn-admin-light" id="userFilterBtn"><i class="bi bi-funnel"></i> Filter</button></div></div><table class="admin-table"><thead><tr><th>No</th><th>Foto</th><th>Nama Lengkap</th><th>NIS / NIP</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="userTableBody"></tbody></table><div class="pager-row"><span id="userPagerInfo">Menampilkan 1 sampai 4 dari 1,248 entri</span><div class="pager" id="userPager"></div></div></div>
                        <div class="asset-modal-overlay" id="userDetailModal" aria-hidden="true"><div class="asset-modal user-detail-modal" role="dialog" aria-modal="true" aria-labelledby="userDetailTitle"><button type="button" class="asset-modal-close" id="closeUserDetail" aria-label="Tutup">×</button><h2 id="userDetailTitle">Detail User</h2><p>Informasi lengkap pengguna sistem SIPIBS.</p><div id="userDetailContent"></div><div class="asset-modal-actions"><button type="button" class="btn-admin-primary" id="okUserDetail">Tutup</button></div></div></div>                        <script>
document.addEventListener('DOMContentLoaded', function () {
                                const users = @json($dbUsers).map(u => [u.name, u.identity_number, u.email, u.role, u.status, u.photo]);
                                const perPage = 4;
                                let currentPage = 1;
                                let activeRole = 'Semua';
                                const roles = ['Semua', ...new Set(users.map(user => user[3]))];
                                const tableBody = document.getElementById('userTableBody');
                                const pager = document.getElementById('userPager');
                                const pagerInfo = document.getElementById('userPagerInfo');
                                const searchInput = document.getElementById('userSearch');
                                const detailModal = document.getElementById('userDetailModal');
                                const detailContent = document.getElementById('userDetailContent');
                                const totalStat = document.getElementById('userTotalStat');

                                function statusClass(value) { return value === 'Aktif' ? 'green' : 'red'; }
                                function initials(name) { return name.split(' ').map(part => part[0]).join('').slice(0, 2).toUpperCase(); }
                                function roleClass(role) { return role === 'Guru' ? 'gray' : 'blue'; }

                                function filteredUsers() {
                                    const keyword = searchInput.value.toLowerCase();
                                    return users.filter(user => {
                                        const matchRole = activeRole === 'Semua' || user[3] === activeRole;
                                        const searchable = `${user[0]} ${user[1]} ${user[2]} ${user[3]}`.toLowerCase();
                                        return matchRole && searchable.includes(keyword);
                                    });
                                }

                                function renderTable() {
                                    const data = filteredUsers();
                                    const totalPages = Math.max(1, Math.ceil(data.length / perPage));
                                    currentPage = Math.min(currentPage, totalPages);
                                    const start = (currentPage - 1) * perPage;
                                    const rows = data.slice(start, start + perPage);
                                    tableBody.innerHTML = rows.map((user, index) => `
                                        <tr>
                                            <td>${start + index + 1}</td>
                                            <td class="user-photo-cell">${user[5] ? `<img class="user-avatar-mini" src="${user[5]}" alt="${user[0]}">` : `<span class="user-initial">${initials(user[0])}</span>`}</td>
                                            <td><strong>${user[0]}</strong><span class="tiny-note">${user[2]}</span></td>
                                            <td>${user[1]}</td>
                                            <td><span class="status ${roleClass(user[3])}">${user[3]}</span></td>
                                            <td><span class="status ${statusClass(user[4])}">● ${user[4]}</span></td>
                                            <td><button type="button" class="icon-btn user-view-btn" data-user-index="${users.indexOf(user)}" title="Lihat detail"><i class="bi bi-eye"></i></button></td>
                                        </tr>
                                    `).join('');
pagerInfo.textContent = rows.length ? `Menampilkan ${start + 1} sampai ${start + rows.length} dari ${data.length} entri` : 'Tidak ada data user';
                                    totalStat.textContent = users.length.toLocaleString('id-ID');
                                    document.getElementById('userSiswaStat').textContent = users.filter(user => user[3] === 'Siswa').length.toLocaleString('id-ID');
                                    document.getElementById('userGuruStat').textContent = users.filter(user => user[3] === 'Guru').length.toLocaleString('id-ID');
                                    renderPager(totalPages);
                                }

                                function renderPager(totalPages) {
                                    let buttons = `<button type="button" ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">‹</button>`;
                                    for (let page = 1; page <= totalPages; page++) buttons += `<button type="button" class="${page === currentPage ? 'active' : ''}" data-page="${page}">${page}</button>`;
                                    buttons += `<button type="button" ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">›</button>`;
                                    pager.innerHTML = buttons;
                                }

                                tableBody.addEventListener('click', function (event) {
                                    const button = event.target.closest('.user-view-btn');
                                    if (!button) return;
                                    const user = users[Number(button.dataset.userIndex)];
                                    detailContent.innerHTML = `
                                        <div class="user-detail-head">
                                            ${user[5] ? `<img class="user-detail-avatar" src="${user[5]}" alt="${user[0]}">` : `<span class="user-detail-avatar user-initial">${initials(user[0])}</span>`}
                                            <div><strong>${user[0]}</strong><span>${user[2]}</span></div>
                                        </div>
                                        <div class="user-detail-grid">
                                            <div><small>NIS / NIP</small><strong>${user[1]}</strong></div>
                                            <div><small>Role</small><strong>${user[3]}</strong></div>
                                            <div><small>Status</small><strong>${user[4]}</strong></div>
                                            <div><small>Email</small><strong>${user[2]}</strong></div>
                                        </div>
                                    `;
                                    detailModal.classList.add('active');
                                    detailModal.setAttribute('aria-hidden', 'false');
                                });

                                function closeDetailModal() {
                                    detailModal.classList.remove('active');
                                    detailModal.setAttribute('aria-hidden', 'true');
                                }
                                document.getElementById('closeUserDetail').addEventListener('click', closeDetailModal);
                                document.getElementById('okUserDetail').addEventListener('click', closeDetailModal);
                                detailModal.addEventListener('click', event => { if (event.target === detailModal) closeDetailModal(); });
                                pager.addEventListener('click', function (event) {
                                    const button = event.target.closest('button[data-page]');
                                    if (!button || button.disabled) return;
                                    currentPage = Number(button.dataset.page);
                                    renderTable();
                                });
                                searchInput.addEventListener('input', function () { currentPage = 1; renderTable(); });
                                document.getElementById('userFilterBtn').addEventListener('click', function () {
                                    const currentIndex = roles.indexOf(activeRole);
                                    activeRole = roles[(currentIndex + 1) % roles.length];
                                    currentPage = 1;
                                    this.classList.toggle('active', activeRole !== 'Semua');
                                    this.innerHTML = `<i class="bi bi-funnel"></i> ${activeRole === 'Semua' ? 'Filter' : activeRole}`;
                                    renderTable();
                                });
                                renderTable();
                            });
                        </script>
            </div>
        </section>
    </main>
</div>
@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>
