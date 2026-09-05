@php
    $active = 'profil';
    $title = 'Profil Admin';
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
        <div class="admin-profile">
            <img class="avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin">
            <div><strong>Online</strong><span id="sidebarAdminRole">Administrator<br>Super Admin</span></div>
        </div>
    </aside>

    <main class="main-area">
        <header class="topbar profile-topbar">
            <div class="profile-search-box">
                <i class="bi bi-search"></i>
                <input placeholder="Cari...">
            </div>
            <div class="top-actions">
                @include('admin.partials.notification-bell')
                <i class="bi bi-question-circle"></i>
                <div class="top-user">
                    <div>
                        <strong id="topAdminName">Admin SIPIBS</strong>
                        <span>SUPER ADMIN</span>
                    </div>
                    <img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin">
                </div>
            </div>
        </header>

        <section class="content">
            <div class="profile-grid-container">
                
                <!-- Main Content Left Column -->
                <div class="profile-main-col">
                    
                    <!-- Top Profile Card -->
                    <div class="profile-header-card">
                        <div class="avatar-upload-wrap">
                            <img id="profilePhotoDisplay" class="profile-avatar-lg avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin SIPIBS">
                            <button type="button" class="btn-avatar-edit" id="btnTriggerPhoto" title="Ganti Foto Profil">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <input type="file" id="photoFileInput" accept="image/*" style="display:none;">
                        </div>

                        <div class="profile-header-info">
                            <h2 id="displayAdminName">Admin SIPIBS</h2>
                            <div class="profile-meta-grid">
                                <div>
                                    <i class="bi bi-person-badge"></i>
                                    <span>NIP: <strong id="displayNip">198501012010011001</strong></span>
                                </div>
                                <div>
                                    <i class="bi bi-envelope"></i>
                                    <span id="displayEmail">admin@sipibs.sch.id</span>
                                </div>
                                <div>
                                    <i class="bi bi-shield-check"></i>
                                    <span>Role: <strong id="displayRole">Administrator System</strong></span>
                                </div>
                                <div>
                                    <i class="bi bi-check-circle-fill green-icon"></i>
                                    <span>Status: <strong class="green-text">Aktif</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs & Form Section -->
                    <div class="profile-tabs-card">
                        <div class="profile-tabs-header">
                            <button type="button" class="tab-btn active" data-tab="data-pribadi">Data Pribadi</button>
                            <button type="button" class="tab-btn" data-tab="keamanan-akun">Keamanan Akun</button>
                            <button type="button" class="tab-btn" data-tab="log-aktivitas">Log Aktivitas</button>
                        </div>

                        <div class="profile-tab-content">
                            
                            <!-- TAB 1: Data Pribadi -->
                            <form id="formPribadi" class="tab-pane active">
                                <div class="form-grid-2">
                                    <div>
                                        <label class="plain-label" for="inputNama">Nama Lengkap</label>
                                        <input class="plain-input" id="inputNama" value="Admin SIPIBS" required>
                                    </div>
                                    <div>
                                        <label class="plain-label" for="inputNip">Nomor Induk Pegawai (NIP)</label>
                                        <input class="plain-input" id="inputNip" value="198501012010011001" required>
                                    </div>
                                    <div>
                                        <label class="plain-label" for="inputEmail">Alamat Email</label>
                                        <input class="plain-input" id="inputEmail" type="email" value="admin@sipibs.sch.id" required>
                                    </div>
                                    <div>
                                        <label class="plain-label" for="inputTelp">Nomor Telepon</label>
                                        <input class="plain-input" id="inputTelp" value="+62 812 3456 7890" required>
                                    </div>
                                </div>
                                <div style="margin-top:16px;">
                                    <label class="plain-label" for="inputAlamat">Alamat Lengkap</label>
                                    <textarea class="plain-textarea" id="inputAlamat" rows="3">Jl. Pendidikan No. 123, Kelurahan Maju Jaya, Kecamatan Cerdas, Kota Pintar, 12345</textarea>
                                </div>
                                <div class="profile-form-btns">
                                    <button type="button" class="btn-admin-light" id="btnResetPribadi">Reset</button>
                                    <button type="submit" class="btn-admin-primary blue-solid">Simpan Perubahan</button>
                                </div>
                            </form>

                            <!-- TAB 2: Keamanan Akun -->
                            <form id="formKeamanan" class="tab-pane">
                                <div class="form-grid-1" style="display:grid;gap:16px;max-width:480px;">
                                    <div>
                                        <label class="plain-label" for="inputPassCurrent">Kata Sandi Saat Ini</label>
                                        <input class="plain-input" id="inputPassCurrent" type="password" placeholder="Masukkan kata sandi lama">
                                    </div>
                                    <div>
                                        <label class="plain-label" for="inputPassNew">Kata Sandi Baru</label>
                                        <input class="plain-input" id="inputPassNew" type="password" placeholder="Minimal 8 karakter">
                                    </div>
                                    <div>
                                        <label class="plain-label" for="inputPassConfirm">Konfirmasi Kata Sandi Baru</label>
                                        <input class="plain-input" id="inputPassConfirm" type="password" placeholder="Ulangi kata sandi baru">
                                    </div>
                                </div>
                                <div class="profile-form-btns">
                                    <button type="button" class="btn-admin-light" id="btnResetKeamanan">Reset</button>
                                    <button type="submit" class="btn-admin-primary blue-solid">Simpan Perubahan</button>
                                </div>
                            </form>

                            <!-- TAB 3: Log Aktivitas -->
                            <div id="paneAktivitas" class="tab-pane">
                                <table class="admin-table log-table">
                                    <thead>
                                        <tr>
                                            <th>WAKTU</th>
                                            <th>AKTIVITAS</th>
                                            <th>DESKRIPSI</th>
                                            <th>IP ADDRESS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="logTableBody">
                                        <tr>
                                            <td>Hari ini, 08:30</td>
                                            <td><span class="status blue">Login System</span></td>
                                            <td>Berhasil masuk ke Dashboard Admin SIPIBS</td>
                                            <td>192.168.1.100</td>
                                        </tr>
                                        <tr>
                                            <td>Kemarin, 14:15</td>
                                            <td><span class="status green">Update Master Data</span></td>
                                            <td>Menambahkan alat baru: Laptop Asus Vivobook</td>
                                            <td>192.168.1.100</td>
                                        </tr>
                                        <tr>
                                            <td>15 Agu 2026, 11:00</td>
                                            <td><span class="status yellow">Persetujuan Transaksi</span></td>
                                            <td>Menyetujui peminjaman alat #PNJ-202505-0001</td>
                                            <td>192.168.1.100</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Right Side Column -->
                <aside class="profile-side-col">
                    
                    <!-- Ringkasan User Card -->
                    <div class="side-card">
                        <div class="side-card-head">
                            <h3>Ringkasan User</h3>
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="user-summary-grid">
                            <!-- Card 1: Total Pengguna -->
                            <div class="user-summary-card total-card">
                                <span class="card-label">Total Pengguna</span>
                                <div class="card-val-row">
                                    <h3 class="card-big-num">1,248</h3>
                                    <span class="pill-badge-blue">+12 bln ini</span>
                                </div>
                                <div class="bar-track">
                                    <div class="bar-progress" style="width: 78%;"></div>
                                </div>
                            </div>

                            <!-- Card 2: Pengguna Aktif -->
                            <div class="user-summary-card flex-stat-card">
                                <div class="stat-icon-blue">
                                    <i class="bi bi-person-check-fill"></i>
                                </div>
                                <div>
                                    <span class="card-label">Pengguna Aktif</span>
                                    <h3 class="card-med-num blue-num">1,150</h3>
                                </div>
                            </div>

                            <!-- Card 3: Menunggu Verifikasi -->
                            <div class="user-summary-card flex-stat-card">
                                <div class="stat-icon-red">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                                <div>
                                    <span class="card-label">Menunggu Verifikasi</span>
                                    <h3 class="card-med-num red-num">98</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pendaftaran Terbaru Card -->
                    <div class="side-card">
                        <div class="side-card-head">
                            <h3>PENDAFTARAN TERBARU</h3>
                        </div>
<div class="recent-users-list" id="recentUsersList"></div>

                        <a href="{{ url('/admin/data-user') }}" class="btn-all-users">LIHAT SEMUA USER</a>
                    </div>

                </aside>

            </div>
        </section>
    </main>
</div>

<!-- Modal Notifikasi Berhasil -->
<div class="asset-modal-overlay" id="profileSuccessModal" aria-hidden="true">
    <div class="asset-modal" style="max-width:420px;text-align:center;">
        <div style="width:58px;height:58px;margin:0 auto 14px;border-radius:50%;background:#dcfce7;color:#16a34a;display:grid;place-items:center;font-size:26px;"><i class="bi bi-check-lg"></i></div>
        <h2 style="margin:0 0 8px;font-size:20px;">Perubahan Disimpan!</h2>
        <p style="margin:0 0 18px;color:#52657c;font-size:13px;" id="profileSuccessMsg">Data profil Anda telah berhasil diperbarui.</p>
        <button type="button" class="btn-admin-primary" id="closeSuccessModal" style="width:100%;justify-content:center;">OK</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tab Switching
        const tabBtns = document.querySelectorAll('.profile-tabs-header .tab-btn');
        const tabPanes = document.querySelectorAll('.profile-tab-content .tab-pane');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const targetTab = this.dataset.tab;
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));

                this.classList.add('active');
                if (targetTab === 'data-pribadi') document.getElementById('formPribadi').classList.add('active');
                if (targetTab === 'keamanan-akun') document.getElementById('formKeamanan').classList.add('active');
                if (targetTab === 'log-aktivitas') document.getElementById('paneAktivitas').classList.add('active');
            });
        });

        // Load saved state or default
        const savedData = JSON.parse(localStorage.getItem('adminProfileData') || '{}');
        if (savedData.nama) {
            document.getElementById('inputNama').value = savedData.nama;
            document.getElementById('inputNip').value = savedData.nip;
            document.getElementById('inputEmail').value = savedData.email;
            document.getElementById('inputTelp').value = savedData.telp;
            document.getElementById('inputAlamat').value = savedData.alamat;
            updateUIProfile(savedData);
        }
        if (savedData.photoUrl) {
            document.querySelectorAll('.avatar-target').forEach(img => img.src = savedData.photoUrl);
        }

        function updateUIProfile(data) {
            document.getElementById('displayAdminName').textContent = data.nama;
            document.getElementById('topAdminName').textContent = data.nama;
            document.getElementById('displayNip').textContent = data.nip;
            document.getElementById('displayEmail').textContent = data.email;
        }

        // Form Pribadi Submit & Reset
        const formPribadi = document.getElementById('formPribadi');
        formPribadi.addEventListener('submit', function (e) {
            e.preventDefault();
            const data = {
                nama: document.getElementById('inputNama').value,
                nip: document.getElementById('inputNip').value,
                email: document.getElementById('inputEmail').value,
                telp: document.getElementById('inputTelp').value,
                alamat: document.getElementById('inputAlamat').value,
                photoUrl: document.getElementById('profilePhotoDisplay').src
            };

            localStorage.setItem('adminProfileData', JSON.stringify(data));
            localStorage.setItem('sipibs_profile', JSON.stringify({
                name: data.nama,
                email: data.email,
                phone: data.telp,
                address: data.alamat,
                photo: data.photoUrl
            }));
            updateUIProfile(data);

            document.getElementById('profileSuccessMsg').textContent = 'Data pribadi profil Anda berhasil diperbarui.';
            document.getElementById('profileSuccessModal').classList.add('active');
        });

        document.getElementById('btnResetPribadi').addEventListener('click', function () {
            document.getElementById('inputNama').value = 'Admin SIPIBS';
            document.getElementById('inputNip').value = '198501012010011001';
            document.getElementById('inputEmail').value = 'admin@sipibs.sch.id';
            document.getElementById('inputTelp').value = '+62 812 3456 7890';
            document.getElementById('inputAlamat').value = 'Jl. Pendidikan No. 123, Kelurahan Maju Jaya, Kecamatan Cerdas, Kota Pintar, 12345';
        });

        // Form Keamanan Submit & Reset
        const formKeamanan = document.getElementById('formKeamanan');
        formKeamanan.addEventListener('submit', function (e) {
            e.preventDefault();
            const pNew = document.getElementById('inputPassNew').value;
            const pConf = document.getElementById('inputPassConfirm').value;

            if (pNew && pNew !== pConf) {
                alert('Konfirmasi kata sandi baru tidak cocok!');
                return;
            }

            document.getElementById('profileSuccessMsg').textContent = 'Kata sandi akun Anda berhasil diperbarui.';
            document.getElementById('profileSuccessModal').classList.add('active');
            formKeamanan.reset();
        });

        document.getElementById('btnResetKeamanan').addEventListener('click', function () {
            formKeamanan.reset();
        });

        // Photo Upload Trigger & Preview
        const btnTriggerPhoto = document.getElementById('btnTriggerPhoto');
        const photoFileInput = document.getElementById('photoFileInput');

        btnTriggerPhoto.addEventListener('click', function () {
            photoFileInput.click();
        });

        photoFileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const newPhoto = e.target.result;
                    document.querySelectorAll('.avatar-target').forEach(img => img.src = newPhoto);
                    
                    const saved = JSON.parse(localStorage.getItem('adminProfileData') || '{}');
                    saved.photoUrl = newPhoto;
                    localStorage.setItem('adminProfileData', JSON.stringify(saved));
                    const profileSync = JSON.parse(localStorage.getItem('sipibs_profile') || '{}');
                    profileSync.photo = newPhoto;
                    localStorage.setItem('sipibs_profile', JSON.stringify(profileSync));
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('closeSuccessModal').addEventListener('click', function () {
            document.getElementById('profileSuccessModal').classList.remove('active');
        });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var recentUsers = @json($dbUsers);
        var list = document.getElementById('recentUsersList');
        if (!list) return;
        if (!recentUsers.length) {
            list.innerHTML = '<div class="recent-user-item"><div><strong>Belum ada user baru</strong><small>Tidak ada pendaftaran terbaru</small></div></div>';
            return;
        }
        list.innerHTML = recentUsers.slice(0, 4).map(function (u) {
            var name = u && u.name ? u.name : '?';
            var initials = name.split(' ').slice(0, 2).map(function (x) { return x ? x[0] : ''; }).join('').toUpperCase();
            var avatar = u.photo
                ? '<img src="' + u.photo + '" alt="' + name + '">'
                : '<span style="width:38px;height:38px;border-radius:50%;background:#dfe8ff;border:1px solid #b7c8e9;color:#003985;font-weight:800;font-size:13px;display:inline-grid;place-items:center;flex-shrink:0;">' + initials + '</span>';
            return '<div class="recent-user-item">' + avatar +
                '<div><strong>' + name + '</strong><small>' + (u.role || 'User') + ' • ' + (u.identity_number || '-') + '</small></div>' +
                '<span class="badge-baru">Baru</span></div>';
        }).join('');
    });
</script>
@include('admin.partials.sidebar-scroll')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>
