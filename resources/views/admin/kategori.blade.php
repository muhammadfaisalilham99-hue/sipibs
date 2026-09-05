@php
    $active = 'kategori';
    $title = 'Kategori Barang';
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
        <header class="topbar category-topbar">
            <div class="category-breadcrumb"><strong>Kategori Barang</strong><small>Inventaris / <span>Kategori Barang</span></small></div>
            <div class="top-actions">
                <div class="category-search-box"><i class="bi bi-search"></i><input id="categorySearch" placeholder="Cari kategori..."></div>
                @include('admin.partials.notification-bell')
                <div class="top-user"><div><strong id="top-user-name">Admin SIPIBS</strong><span>Administrator</span></div><img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin"><i class="bi bi-chevron-down" style="font-size:11px;color:#52657c;"></i></div>
            </div>
        </header>
        <section class="content">
            <div class="admin-wrap">
                <div class="admin-page-head"><div><h1>Daftar Kategori Barang</h1><p>Kelola data kategori barang pada sistem peminjaman alat.</p></div><div class="admin-btns"><button type="button" class="btn-admin-light" id="categoryExportBtn"><i class="bi bi-download"></i> Export</button><button type="button" class="btn-admin-primary" id="openAddCategory"><i class="bi bi-plus"></i> Tambah Kategori</button></div></div>
                <div class="admin-panel category-panel">
                    <table class="admin-table"><thead><tr><th>NO</th><th>ICON</th><th>NAMA KATEGORI</th><th>KODE KATEGORI</th><th>JUMLAH BARANG</th><th>STATUS</th><th>AKSI</th></tr></thead><tbody id="categoryTableBody"></tbody></table>
                    <div class="pager-row"><span id="categoryPagerInfo">Menampilkan 1 - 3 dari 8 data</span><div class="pager" id="categoryPager"></div></div>
                </div>
            </div>
        </section>
    </main>
</div>

<div class="asset-modal-overlay" id="categoryModal" aria-hidden="true">
    <div class="asset-modal" role="dialog" aria-modal="true" aria-labelledby="categoryModalTitle">
        <button type="button" class="asset-modal-close" id="closeAddCategory" aria-label="Tutup">×</button>
        <h2 id="categoryModalTitle">Tambah Kategori</h2>
        <p>Masukkan data kategori barang baru.</p>
        <form id="categoryForm" class="asset-form">
            <input type="hidden" id="editCategoryIndex" value="-1">
            <div class="form-grid-2">
                <div><label class="plain-label" for="catNameInput">Nama Kategori</label><input class="plain-input" id="catNameInput" placeholder="Contoh: Kamera" required></div>
                <div><label class="plain-label" for="catCodeInput">Kode Kategori</label><input class="plain-input" id="catCodeInput" value="KTG-009" required></div>
                <div><label class="plain-label" for="catDescInput">Keterangan</label><input class="plain-input" id="catDescInput" placeholder="Contoh: Semua jenis kamera" required></div>
                <div><label class="plain-label" for="catQtyInput">Jumlah Barang</label><input class="plain-input" id="catQtyInput" type="number" min="0" value="0" required></div>
                <div><label class="plain-label" for="catIconInput">Icon (Bootstrap Icon)</label><select class="plain-select" id="catIconInput"><option value="bi-camera">bi-camera (Kamera)</option><option value="bi-laptop">bi-laptop (Laptop)</option><option value="bi-projector">bi-projector (Proyektor)</option><option value="bi-mic">bi-mic (Audio)</option><option value="bi-tools">bi-tools (Peralatan)</option></select></div>
                <div><label class="plain-label" for="catStatusInput">Status</label><select class="plain-select" id="catStatusInput"><option>Aktif</option><option>Non-Aktif</option></select></div>
            </div>
            <div class="asset-modal-actions"><button type="button" class="btn-admin-light" id="cancelAddCategory">Batal</button><button type="submit" class="btn-admin-primary"><i class="bi bi-save"></i> Simpan Kategori</button></div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categories = [
            ['Kamera','Semua jenis kamera','KTG-001','15','bi-camera','Aktif'],
            ['Laptop','Laptop untuk praktik','KTG-002','12','bi-laptop','Aktif'],
            ['Proyektor','LCD Projector','KTG-003','8','bi-projector','Aktif'],
            ['Audio','Mic & Speaker','KTG-004','20','bi-mic','Aktif'],
            ['Aksesoris','Kabel & Adapter','KTG-005','45','bi-tools','Aktif'],
            ['Networking','Router & Switch','KTG-006','10','bi-laptop','Aktif'],
            ['Olahraga','Peralatan olahraga','KTG-007','30','bi-tools','Aktif'],
            ['Laboratorium','Alat lab IPA','KTG-008','18','bi-tools','Aktif']
        ];
        const perPage = 3;
        let currentPage = 1;
        const tableBody = document.getElementById('categoryTableBody');
        const pager = document.getElementById('categoryPager');
        const pagerInfo = document.getElementById('categoryPagerInfo');
        const searchInput = document.getElementById('categorySearch');
        const modal = document.getElementById('categoryModal');
        const form = document.getElementById('categoryForm');
        const modalTitle = document.getElementById('categoryModalTitle');
        const editIndexInput = document.getElementById('editCategoryIndex');

        function filteredData() {
            const keyword = searchInput.value.toLowerCase().trim();
            return categories.filter(c => `${c[0]} ${c[1]} ${c[2]}`.toLowerCase().includes(keyword));
        }

        function renderTable() {
            const data = filteredData();
            const totalPages = Math.max(1, Math.ceil(data.length / perPage));
            currentPage = Math.min(currentPage, totalPages);
            const start = (currentPage - 1) * perPage;
            const rows = data.slice(start, start + perPage);

            tableBody.innerHTML = rows.map((c, index) => {
                const globalIndex = categories.indexOf(c);
                return `
                    <tr>
                        <td>${start + index + 1}</td>
                        <td><span class="category-icon-box"><i class="bi ${c[4]}"></i></span></td>
                        <td><strong>${c[0]}</strong><span class="tiny-note">${c[1]}</span></td>
                        <td>${c[2]}</td>
                        <td><div class="qty-pill">${c[3]}<span class="tiny-note">Barang</span></div></td>
                        <td><span class="status green">${c[5]}</span></td>
                        <td>
                            <div class="action-icons">
                                <button type="button" class="icon-btn edit-cat-btn" data-index="${globalIndex}" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                <button type="button" class="icon-btn red delete-cat-btn" data-index="${globalIndex}" title="Hapus"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            pagerInfo.textContent = rows.length ? `Menampilkan ${start + 1} - ${start + rows.length} dari ${data.length} data` : 'Tidak ada data kategori';
            renderPager(totalPages);
        }

        function renderPager(totalPages) {
            let html = `<button type="button" ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">‹</button>`;
            for (let page = 1; page <= totalPages; page++) {
                html += `<button type="button" class="${page === currentPage ? 'active' : ''}" data-page="${page}">${page}</button>`;
            }
            html += `<button type="button" ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">›</button>`;
            html += `<span class="pager-per-page">10 / halaman</span>`;
            pager.innerHTML = html;
        }

        pager.addEventListener('click', function (event) {
            const button = event.target.closest('button[data-page]');
            if (!button || button.disabled) return;
            currentPage = Number(button.dataset.page);
            renderTable();
        });

        searchInput.addEventListener('input', function () {
            currentPage = 1;
            renderTable();
        });

        tableBody.addEventListener('click', function (event) {
            const editBtn = event.target.closest('.edit-cat-btn');
            if (editBtn) {
                const index = Number(editBtn.dataset.index);
                const c = categories[index];
                editIndexInput.value = index;
                modalTitle.textContent = 'Edit Kategori';
                document.getElementById('catNameInput').value = c[0];
                document.getElementById('catDescInput').value = c[1];
                document.getElementById('catCodeInput').value = c[2];
                document.getElementById('catQtyInput').value = c[3];
                document.getElementById('catIconInput').value = c[4];
                document.getElementById('catStatusInput').value = c[5];
                openModal();
                return;
            }

            const deleteBtn = event.target.closest('.delete-cat-btn');
            if (deleteBtn) {
                const index = Number(deleteBtn.dataset.index);
                if (confirm(`Apakah Anda yakin ingin menghapus kategori "${categories[index][0]}"?`)) {
                    categories.splice(index, 1);
                    renderTable();
                }
            }
        });

        document.getElementById('categoryExportBtn').addEventListener('click', function () {
            const header = ['Nama Kategori','Keterangan','Kode Kategori','Jumlah Barang','Icon','Status'];
            const csvRows = [header, ...filteredData()].map(row => row.map(v => `"${String(v).replaceAll('"', '""')}"`).join(','));
            const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'kategori-barang.csv';
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(link.href);
        });

        function openModal() {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            form.reset();
            editIndexInput.value = '-1';
            modalTitle.textContent = 'Tambah Kategori';
            document.getElementById('catCodeInput').value = `KTG-${String(categories.length + 1).padStart(3, '0')}`;
        }

        document.getElementById('openAddCategory').addEventListener('click', function () {
            closeModal();
            openModal();
        });
        document.getElementById('closeAddCategory').addEventListener('click', closeModal);
        document.getElementById('cancelAddCategory').addEventListener('click', closeModal);
        modal.addEventListener('click', event => { if (event.target === modal) closeModal(); });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const index = Number(editIndexInput.value);
            const data = [
                document.getElementById('catNameInput').value,
                document.getElementById('catDescInput').value,
                document.getElementById('catCodeInput').value,
                document.getElementById('catQtyInput').value,
                document.getElementById('catIconInput').value,
                document.getElementById('catStatusInput').value
            ];

            if (index >= 0) {
                categories[index] = data;
            } else {
                categories.unshift(data);
                currentPage = 1;
            }
            closeModal();
            renderTable();
        });

        renderTable();
    });
</script>
@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>
