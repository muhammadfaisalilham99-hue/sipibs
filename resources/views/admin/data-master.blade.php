@php
    $active = 'master';
    $title = 'Data Master - SIPIBS Admin';
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
    <link rel="stylesheet" href="{{ asset('css/admin-datamaster.css') }}?v=7">
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
            <div class="page-label">Data Master</div>
            <div class="top-actions">
                @include('admin.partials.notification-bell')
                <i class="bi bi-question-circle"></i>
                <div class="top-user">
                    <div><strong id="top-user-name">Admin</strong><span>Administrator</span></div>
                    <img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin">
                </div>
            </div>
        </header>

        <section class="master-admin-container">
            <div class="master-admin-header">
                <h1>Data Master</h1>
                <p>Kelola data barang dan ketersediaan stok</p>
            </div>

            <!-- Stats Cards Grid -->
            <div class="master-stats-grid">
                <div class="master-stat-card">
                    <div class="stat-icon-box blue">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-title">Total Item</span>
                        <div class="stat-number"><span id="statTotalItem">0</span> <span>Barang</span></div>
                    </div>
                </div>

                <div class="master-stat-card">
                    <div class="stat-icon-box green">
                        <i class="bi bi-box-arrow-in-down-right"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-title">Tersedia</span>
                        <div class="stat-number"><span id="statAvailable">0</span> <span>Barang</span></div>
                    </div>
                </div>

                <div class="master-stat-card">
                    <div class="stat-icon-box orange">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-title">Stok Rendah</span>
                        <div class="stat-number"><span id="statLowStock">0</span> <span>Barang</span></div>
                    </div>
                </div>

                <div class="master-stat-card">
                    <div class="stat-icon-box red">
                        <i class="bi bi-slash-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-title">Stok Habis</span>
                        <div class="stat-number"><span id="statOutOfStock">0</span> <span>Barang</span></div>
                    </div>
                </div>
            </div>

            <!-- Shell Card Container -->
            <div class="master-shell-card">
                <!-- Controls Bar (Without Lokasi Filter) -->
                <div class="master-controls">
                    <div class="master-search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchMaster" placeholder="Cari nama barang, kode, atau merk...">
                    </div>

                    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <select class="master-filter-select" id="filterCategory">
                            <option value="all">Semua Kategori</option>
                        </select>

                        <button class="btn-add-item" type="button" id="btnOpenAddItem">
                            <i class="bi bi-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <!-- Table Container (Without Lokasi Column) -->
                <div class="table-responsive">
                    <table class="master-table">
                        <thead>
<tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 130px;">Kode Barang</th>
                                <th>Nama Barang</th>
                                <th style="width: 160px;">Kategori</th>
                                <th style="width: 160px;">Stok Tersedia</th>
                                <th style="width: 130px;">Kondisi</th>
                                <th style="width: 210px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="masterTableBody">
                            <!-- Dynamic Table Rows -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="master-pagination">
                    <div class="pagination-info" id="paginationInfo">Menampilkan 0 - 0 dari 0 data</div>
                    <div class="pagination-nav" id="paginationNav">
                        <button class="master-page-btn" disabled>&lt;</button>
                        <button class="master-page-btn active">1</button>
                        <button class="master-page-btn" disabled>&gt;</button>
                    </div>
                </div>
            </div>
        

            <!-- Modal Tambah Barang -->
            <div class="add-item-modal-overlay" id="addItemModalOverlay">
                <div class="add-item-modal-card">
                    <div class="add-item-modal-head">
                        <div>
                            <h2>Tambah Barang Baru</h2>
                            <span>Masukkan data barang untuk Data Master</span>
                        </div>
                        <button type="button" class="add-item-modal-close" id="closeAddItemModal">&times;</button>
                    </div>

                    <form id="addItemForm" onsubmit="return false;">
                        <div class="add-item-grid">
                            <div class="add-item-form-group">
                                <label>Nama Barang</label>
                                <input type="text" id="addItemName" placeholder="Contoh: Kamera Canon EOS 80D" required>
                            </div>
                            <div class="add-item-form-group">
                                <label>Kode Barang</label>
                                <input type="text" id="addItemCode" placeholder="Contoh: CAM-016" required>
                            </div>
                            <div class="add-item-form-group">
                                <label>Kategori</label>
                                <select id="addItemCategory">
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Audio Visual">Audio Visual</option>
                                    <option value="Praktikum">Praktikum</option>
                                    <option value="Peralatan Kantor">Peralatan Kantor</option>
                                </select>
                            </div>
                            <div class="add-item-form-group">
                                <label>Merk</label>
                                <input type="text" id="addItemMerk" placeholder="Contoh: Canon">
                            </div>
                            <div class="add-item-form-group">
                                <label>Stok Awal</label>
                                <input type="number" id="addItemStock" min="0" placeholder="Masukkan stok awal" required>
                            </div>
                            <div class="add-item-form-group">
                                <label>Nama File Foto (opsional)</label>
                                <input type="text" id="addItemImage" placeholder="Contoh: kamera.jpg">
                            </div>
                        </div>

                        <div class="add-item-actions">
                            <button type="button" class="btn-add-cancel" id="btnCancelAddItem">Batal</button>
                            <button type="button" class="btn-add-save" id="btnSaveAddItem"><i class="bi bi-plus-circle"></i> Tambah Barang</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Detail Barang -->
            <div class="detail-item-modal-overlay" id="detailItemModalOverlay">
                <div class="detail-item-modal-card">
                    <button type="button" class="detail-item-modal-close" id="closeDetailItemModal">&times;</button>
                    <div class="detail-item-modal-head">
                        <i class="bi bi-box-seam"></i>
                        <h2>Detail Barang</h2>
                    </div>
                    <div class="detail-item-modal-body">
                        <div id="detailItemPreview" class="detail-item-preview"></div>
                        <div class="detail-item-info">
                            <div class="detail-item-row"><span>Kode Barang</span><strong id="detailCode">-</strong></div>
                            <div class="detail-item-row"><span>Nama Barang</span><strong id="detailName">-</strong></div>
                            <div class="detail-item-row"><span>Kategori</span><strong id="detailCat">-</strong></div>
                            <div class="detail-item-row"><span>Merk</span><strong id="detailMerk">-</strong></div>
                            <div class="detail-item-row"><span>Stok Tersedia</span><strong id="detailStock">-</strong></div>
                            <div class="detail-item-row"><span>Status</span><strong id="detailStatus">-</strong></div>
                            <div class="detail-item-row"><span>Kondisi</span><strong id="detailKondisi">-</strong></div>
                        </div>
                    </div>
                    <div class="detail-item-modal-actions">
                        <button type="button" class="btn-add-cancel" id="closeDetailItemModal2">Tutup</button>
                    </div>
                </div>
            </div>

            <!-- Modal Ubah Kondisi Barang -->
            <div class="detail-item-modal-overlay" id="conditionItemModalOverlay">
                <div class="detail-item-modal-card" style="max-width:520px;">
                    <button type="button" class="detail-item-modal-close" id="closeConditionItemModal">&times;</button>
                    <div class="detail-item-modal-head">
                        <i class="bi bi-clipboard-check"></i>
                        <h2>Ubah Kondisi Barang</h2>
                    </div>
                    <div class="condition-item-info">
                        <strong id="conditionItemName">-</strong>
                        <span>Kode Barang: <strong id="conditionItemCode">-</strong></span>
                    </div>
                    <div class="condition-options" id="conditionOptions">
                        <button type="button" class="cond-option" data-cond="Baik">
                            <i class="bi bi-check-circle-fill"></i>
                            <span><strong>Baik</strong><small>Barang dalam kondisi baik dan layak dipakai.</small></span>
                            <span class="cond-radio"></span>
                        </button>
                        <button type="button" class="cond-option" data-cond="Rusak Ringan">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span><strong>Rusak Ringan</strong><small>Barang mengalami kerusakan kecil, masih bisa dipakai.</small></span>
                            <span class="cond-radio"></span>
                        </button>
                        <button type="button" class="cond-option" data-cond="Rusak">
                            <i class="bi bi-x-circle-fill"></i>
                            <span><strong>Rusak</strong><small>Barang rusak dan tidak layak dipakai.</small></span>
                            <span class="cond-radio"></span>
                        </button>
                    </div>
                    <div class="detail-item-modal-actions">
                        <button type="button" class="btn-add-cancel" id="btnCancelCondition">Batal</button>
                        <button type="button" class="btn-add-save" id="btnSaveCondition"><i class="bi bi-check-lg"></i> Simpan</button>
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus Barang -->
            <div class="delete-modal-overlay" id="deleteModalOverlay">
                <div class="delete-modal-card">
                    <button type="button" class="delete-modal-close" id="closeDeleteModal">&times;</button>
                    <div class="delete-warn-icon">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>
                    <h3>Hapus Barang?</h3>
                    <p class="delete-subtitle">Anda yakin ingin menghapus barang berikut?</p>

                    <div class="delete-item-preview">
                        <div class="preview-img" id="deletePreviewThumb">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <strong id="deletePreviewName">Kamera Canon EOS 200D</strong>
                            <span>Kode Barang: <strong id="deletePreviewCode">CAM-002</strong></span>
                        </div>
                    </div>

                    <div class="delete-warning-text">Tindakan ini tidak dapat dibatalkan.</div>

                    <div class="delete-modal-actions">
                        <button type="button" class="btn-delete-cancel" id="btnCancelDelete">Batal</button>
                        <button type="button" class="btn-delete-confirm" id="btnConfirmDelete">Ya, Hapus</button>
                    </div>
                </div>
            </div>

            <!-- Modal Kelola Stok -->
            <div class="stock-modal-overlay" id="stockModalOverlay">
                <div class="stock-modal-card">
                    <div class="stock-modal-head">
                        <div>
                            <h2>Kelola Stok</h2>
                            <span id="stockModalItemName">Laptop Lenovo ThinkPad X1</span>
                        </div>
                        <button type="button" class="stock-modal-close" id="closeStockModal">&times;</button>
                    </div>

                    <!-- Toggle Tabs: Tambah Stok / Kurangi Stok -->
                    <div class="stock-toggle-tabs">
                        <button type="button" class="stock-toggle-btn active" id="tabAddStock" data-type="add">Tambah Stok</button>
                        <button type="button" class="stock-toggle-btn" id="tabSubtractStock" data-type="subtract">Kurangi Stok</button>
                    </div>

                    <!-- Form Input -->
                    <form id="stockManageForm" onsubmit="return false;">
                        <div class="stock-form-group">
                            <label>Jumlah</label>
                            <div class="unit-input-group">
                                <input type="number" id="stockQtyInput" min="1" placeholder="Masukkan jumlah stok">
                                <span class="unit-addon">unit</span>
                            </div>
                        </div>

                        <div class="stock-form-group">
                            <label>Alasan / Keterangan (opsional)</label>
                            <textarea id="stockReasonInput" rows="3" placeholder="Contoh: Pembelian barang baru"></textarea>
                        </div>

                        <button type="button" class="btn-stock-submit" id="btnStockSubmit">Tambah Stok</button>
                    </form>

                    <!-- Riwayat Stok Section -->
                    <div class="stock-history-section">
                        <div class="stock-history-title">Riwayat Stok</div>
                        <div class="stock-history-list" id="stockHistoryList">
                            <!-- Dynamic Timeline Items -->
                        </div>
                        <a href="#" class="link-all-history" id="linkAllHistory" onclick="event.preventDefault();">Lihat semua riwayat stok</a>
                    </div>

                    <!-- Info Banner -->
                    <div class="stock-info-banner">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Informasi</strong>
                            <p>Stok minimum ditentukan per barang. Pastikan stok tidak kurang dari stok minimum.</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>
</div>

<script>
    window.SIPIBS_IMAGE_BASE = '{{ asset("images") }}/';
</script>
<script src="{{ asset('js/admin-datamaster.js') }}?v=8"></script>
<script src="{{ asset('js/admin-stock-modal.js') }}?v=2"></script>
@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>
