<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=25">
    <style>.user-sidebar + .main-area .user-profile-content .profile-field.password-field { grid-template-columns: 34px minmax(0, 1fr) 42px !important; }.password-toggle { border: 0; background: transparent; color: #52627a; cursor: pointer; font-size: 16px; height: 48px; padding: 0; }.password-toggle i { display: block; }.password-toggle:hover { color: #0d4d99; }</style>
</head>
<body class="{{ ($showLogout ?? false) ? 'user-logout-page' : '' }}">
@include('user.partials.local-storage-cleanup')
@php
    $user = Auth::user();
    $userName = $user ? $user->name : 'Rizky Pratama';
    $userEmail = $user ? $user->email : 'rizky.pratama@email.com';
    $userClass = $user ? $user->class_name : null;
    $userNis = Auth::check() ? $user->identity_number : null;
    $userIdentityDocument = $user ? $user->identity_document : null;
    $userBirthdate = $user ? $user->birth_date : null;
        $userGender = $user ? $user->gender : null;
    $userRole = ($user && $user->role === 'guru') ? 'Guru' : 'Siswa';
    $userPhoto = ($user && $user->photo) ? $user->photo : asset('images/PROFIL.png');
    $userRoleUpper = strtoupper($userRole);
    $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $userCreatedAt = ($user && $user->created_at)
        ? $user->created_at->format('j') . ' ' . ($bulanIndo[(int)$user->created_at->format('n')] ?? '') . ' ' . $user->created_at->format('Y')
        : '15 Januari 2024';
    $userBorrowings = \App\Models\Borrowing::where('user_id', Auth::id())->get();
    $totalBorrowings = $userBorrowings->count();
    $activeBorrowings = $userBorrowings->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])->count();
    $completedBorrowings = $userBorrowings->where('status', 'dikembalikan')->count();
    $lateBorrowings = $userBorrowings->filter(function ($b) {
        if ($b->status === 'terlambat') return true;
        if (in_array($b->status, ['menunggu', 'disetujui', 'dipinjam']) && $b->due_date && $b->due_date->isPast()) return true;
        return false;
    })->count();

    $recentActivities = collect();
    $userReturns = \App\Models\ReturnRecord::with('borrowing.item')
        ->where('user_id', Auth::id())
        ->orWhereHas('borrowing', fn($q) => $q->where('user_id', Auth::id()))
        ->orderByDesc('id')
        ->get();

    foreach ($userReturns as $ret) {
        $retDate = $ret->return_date ?: $ret->created_at;
        $title = $ret->status === 'diterima' ? 'Barang dikembalikan' : ($ret->status === 'bermasalah' ? 'Pengembalian bermasalah' : 'Menunggu verifikasi');
        $badge = $ret->status === 'diterima' ? 'green' : ($ret->status === 'bermasalah' ? 'red' : 'orange');
        $icon = $ret->status === 'diterima' ? 'bi-clipboard-check' : ($ret->status === 'bermasalah' ? 'bi-exclamation-triangle' : 'bi-three-dots');
        $recentActivities->push([
            'title' => $title,
            'item' => $ret->item_name ?: optional(optional($ret->borrowing)->item)->name ?: 'Barang',
            'date' => $retDate ? $retDate->format('j M') . '<br>' . $retDate->format('Y') : '-',
            'sort_date' => $ret->created_at ?: now(),
            'badge' => $badge,
            'icon' => $icon,
        ]);
    }

    foreach ($userBorrowings as $bor) {
        if ($bor->status === 'dikembalikan' && $userReturns->where('borrowing_id', $bor->id)->count() > 0) {
            continue;
        }
        $bDate = $bor->borrow_date ?: $bor->created_at;
        $title = match ($bor->status) {
            'dipinjam' => 'Peminjaman sedang aktif',
            'disetujui' => 'Peminjaman disetujui',
            'dikembalikan' => 'Barang dikembalikan',
            'terlambat' => 'Keterlambatan pengembalian',
            'ditolak' => 'Peminjaman ditolak',
            default => 'Menunggu persetujuan',
        };
        $badge = match ($bor->status) {
            'dipinjam', 'disetujui' => 'blue',
            'dikembalikan' => 'green',
            'terlambat', 'ditolak' => 'red',
            default => 'orange',
        };
        $icon = match ($bor->status) {
            'dipinjam', 'disetujui' => 'bi-check2-circle',
            'dikembalikan' => 'bi-clipboard-check',
            'terlambat', 'ditolak' => 'bi-exclamation-triangle',
            default => 'bi-three-dots',
        };
        $recentActivities->push([
            'title' => $title,
            'item' => optional($bor->item)->name ?: 'Barang',
            'date' => $bDate ? $bDate->format('j M') . '<br>' . $bDate->format('Y') : '-',
            'sort_date' => $bor->created_at ?: now(),
            'badge' => $badge,
            'icon' => $icon,
        ]);
    }
    $recentActivities = $recentActivities->sortByDesc('sort_date')->values();
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
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item active" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-area">
        <header class="topbar">
            <div class="topbar-left"></div>
            <div class="top-actions">
                @include('user.partials.notification-bell')

                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span id="top-user-role">{{ $userRoleUpper }}</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ $userPhoto }}" alt="Avatar">
                </div>
            </div>
        </header>

        <section class="content user-profile-content">
            <div class="profile-page-title">
                <h1>Profil Saya</h1>
                <div class="profile-breadcrumb"><i class="bi bi-house-door-fill"></i> Beranda <span>/</span> <strong>Profil Saya</strong></div>
            </div>

            <div class="profile-hero-card">
                <div class="profile-photo-wrap">
                    <img id="profile-photo" src="{{ $userPhoto }}" alt="Foto Profil">
                    <label class="profile-camera-btn" for="profile-photo-input"><i class="bi bi-camera-fill"></i></label>
                    <input id="profile-photo-input" type="file" accept="image/jpeg,image/png,image/webp" hidden>
                </div>
                <div class="profile-hero-info">
                    <div class="profile-name-row">
                        <h2 id="hero-name">{{ $userName }}</h2>
                        <span>{{ $userRole }}</span>
                    </div>
                    <div class="profile-contact-grid">
                        <div><i class="bi bi-envelope"></i> <span id="hero-email">{{ $userEmail }}</span></div>
                        <div><i class="bi bi-calendar2"></i> Bergabung sejak {{ $userCreatedAt }}</div>
                    </div>
                </div>
                <div class="profile-quote-card">"Gunakan fasilitas sekolah dengan bijak<br>dan bertanggung jawab."</div>
            </div>

            <div class="profile-layout-grid">
                <div>
                    <div class="profile-tabs-user">
                        <button class="active" type="button" data-tab="personal" disabled>Informasi Pribadi</button>
                        <button type="button" data-tab="security">Keamanan Akun</button>
                        <button type="button" data-tab="notifications">Pengaturan Notifikasi</button>
                    </div>

                    <form class="profile-form-card" id="profile-form">
                        @csrf
                        <div class="profile-tab-panel active" id="tab-personal">
                            <div class="profile-form-grid">
                                <label>Nama Lengkap
                                    <div class="profile-field"><i class="bi bi-person"></i><input disabled id="input-name" value="{{ $userName }}" readonly></div>
                                </label>
                                <label>Email
                                    <div class="profile-field"><i class="bi bi-envelope"></i><input disabled id="input-email" value="{{ $userEmail }}" readonly></div>
                                </label>
                                <label>Kelas / Ruangan
                                    <div class="profile-field"><i class="bi bi-mortarboard"></i><input disabled id="input-class" value="{{ $userClass ?? '' }}"></div>
                                </label>
                                <label>NIS / NIP
                                    <div class="profile-field"><i class="bi bi-card-text"></i><input disabled id="input-nis" value="{{ $userNis ?? '' }}"></div>
                                </label>
                                <label>Tanggal Lahir
                                    <div class="profile-field"><i class="bi bi-calendar3"></i><input disabled id="input-birthdate" value="{{ $userBirthdate ?? '' }}"></div>
                                </label>
                                <label>Jenis Kelamin
                                    <div class="profile-field"><i class="bi bi-gender-male"></i><select disabled id="input-gender"><option value="Laki-laki" {{ ($userGender ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option><option value="Perempuan" {{ ($userGender ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option></select></div>
                                </label>
                                <label>KTP / Kartu Pelajar
                                    <div class="profile-field identity-document-field">
                                        <i class="bi bi-file-earmark-person"></i>
                                        <span id="identity-doc-text">{{ $userIdentityDocument ? 'Dokumen terupload' : 'Belum diupload' }}</span>
                                        <a id="identity-document-link" href="{{ $userIdentityDocument ?: '#' }}" style="{{ $userIdentityDocument ? '' : 'display:none;' }}">Lihat</a>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="profile-tab-panel" id="tab-security">
                            <h3>Keamanan Akun</h3>
                            <p>Perbarui password dan opsi keamanan akun Anda.</p>
                            <div class="profile-form-grid">
                                <label>Password Lama
                                    <div class="profile-field password-field"><i class="bi bi-lock"></i><input id="current-password" name="current_password" type="password" placeholder="Masukkan password lama"><button type="button" class="password-toggle" data-password-target="current-password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button></div>
                                </label>
                                <label>Password Baru
                                    <div class="profile-field password-field"><i class="bi bi-shield-lock"></i><input id="new-password" name="password" type="password" placeholder="Masukkan password baru"><button type="button" class="password-toggle" data-password-target="new-password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button></div>
                                </label>
                                <label>Konfirmasi Password Baru
                                    <div class="profile-field password-field"><i class="bi bi-check2-circle"></i><input id="new-password-confirmation" name="password_confirmation" type="password" placeholder="Ulangi password baru"><button type="button" class="password-toggle" data-password-target="new-password-confirmation" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button></div>
                                </label>
                                <label>Verifikasi Login
                                    <div class="profile-field"><i class="bi bi-phone"></i><select><option>Aktif - OTP Email</option><option>Nonaktif</option></select></div>
                                </label>
                            </div>
                            <div class="security-options">
                                <label><input type="checkbox" checked> Kirim notifikasi jika ada login baru.</label>
                                <label><input type="checkbox" checked> Keluar otomatis dari perangkat lama setelah ganti password.</label>
                            </div>
                            <div class="profile-save-row">
                                <button id="password-save" type="button"><i class="bi bi-shield-check"></i> Ubah Password</button>
                            </div>
                        </div>

                        <div class="profile-tab-panel" id="tab-notifications">
                            <h3>Pengaturan Notifikasi</h3>
                            <p>Atur pemberitahuan yang ingin Anda terima dari SIPIBS.</p>
                            <div class="notification-list">
                                <label><span><strong>Persetujuan Peminjaman</strong><small>Info saat pengajuan disetujui atau ditolak admin.</small></span><input type="checkbox" checked></label>
                                <label><span><strong>Pengingat Pengembalian</strong><small>Notifikasi sebelum batas waktu pengembalian barang.</small></span><input type="checkbox" checked></label>
                                <label><span><strong>Barang Terlambat</strong><small>Peringatan jika pengembalian melewati batas waktu.</small></span><input type="checkbox" checked></label>
                                <label><span><strong>Newsletter Sistem</strong><small>Info pembaruan fitur dan pengumuman sekolah.</small></span><input type="checkbox"></label>
                            </div>
                        </div>

                        <div class="profile-save-row">
                            <button type="submit" style="display:none;"><i class="bi bi-floppy"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>

                <aside class="profile-side-stack">
                    <div class="profile-side-card">
                        <h3>Ringkasan Akun</h3>
                        <div class="summary-item"><span class="blue"><i class="bi bi-clipboard-check"></i></span><div><strong>Total Peminjaman</strong><small>Semua waktu</small></div><b>{{ $totalBorrowings }}</b></div>
                        <div class="summary-item"><span class="light-blue"><i class="bi bi-clock"></i></span><div><strong>Peminjaman Aktif</strong><small>Sedang dipinjam</small></div><b>{{ $activeBorrowings }}</b></div>
                        <div class="summary-item"><span class="green"><i class="bi bi-check-circle"></i></span><div><strong>Peminjaman Selesai</strong><small>Telah dikembalikan</small></div><b>{{ $completedBorrowings }}</b></div>
                        <div class="summary-item"><span class="red"><i class="bi bi-exclamation-triangle"></i></span><div><strong>Keterlambatan</strong><small>Peminjaman terlambat</small></div><b class="red-text">{{ $lateBorrowings }}</b></div>
                    </div>
                    <div class="profile-side-card activity-card-user">
                        <div class="activity-title"><h3>Aktivitas Terakhir</h3>@if(count($recentActivities) > 4)<a href="#" id="activity-toggle">Lihat Semua</a>@endif</div>
                        @forelse($recentActivities as $index => $act)
                            <div class="activity-item {{ $index >= 4 ? 'activity-more' : '' }}">
                                <span class="{{ $act['badge'] }}"><i class="bi {{ $act['icon'] }}"></i></span>
                                <div><strong>{{ $act['title'] }}</strong><p>{{ $act['item'] }}</p></div>
                                <small>{!! $act['date'] !!}</small>
                            </div>
                        @empty
                            <div class="return-empty" style="padding:18px 0;font-size:12px;">Belum ada aktivitas tercatat.</div>
                        @endforelse
                    </div>
                </aside>
            </div>
        </section>
    </main>
</div>

<div class="profile-toast" id="profile-toast"><i class="bi bi-check-circle-fill"></i> Perubahan profil berhasil disimpan.</div>

<div class="identity-preview-overlay" id="identity-preview-modal" aria-hidden="true">
    <div class="identity-preview-modal" role="dialog" aria-modal="true" aria-labelledby="identity-preview-title">
        <button class="identity-preview-close" id="identity-preview-close" type="button" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        <h2 id="identity-preview-title">KTP / Kartu Pelajar</h2>
        <div class="identity-preview-body" id="identity-preview-body"></div>
    </div>
</div>
@if($showLogout ?? false)
<div class="user-logout-overlay">
    <div class="user-logout-modal">
        <div class="user-logout-topline"></div>
        <div class="user-logout-icon"><i class="bi bi-box-arrow-right"></i></div>
        <h2>Konfirmasi Logout</h2>
        <p>Apakah Anda yakin ingin keluar dari sistem SIPIBS? Pastikan semua pekerjaan dan perubahan data inventaris Anda telah disimpan.</p>
        <div class="user-logout-actions">
            <a class="user-logout-cancel" href="{{ url('/profil-user') }}">Batal</a>
            <form method="POST" action="{{ route('logout') }}" class="user-logout-form">@csrf<button class="user-logout-confirm" type="submit">Ya, Keluar <i class="bi bi-arrow-right"></i></button></form>
        </div>
    </div>
</div>
@endif

<script>
    const identityLink = document.getElementById('identity-document-link');
    const identityModal = document.getElementById('identity-preview-modal');
    const identityBody = document.getElementById('identity-preview-body');
    function closeIdentityPreview() { identityModal.classList.remove('active'); identityModal.setAttribute('aria-hidden', 'true'); identityBody.innerHTML = ''; }
    identityLink.addEventListener('click', function (event) {
        event.preventDefault();
        const url = this.href;
        const isPdf = /\\.pdf(?:$|[?#])/i.test(url);
        identityBody.innerHTML = isPdf ? '<iframe src="' + url + '" title="Dokumen identitas"></iframe>' : '<img src="' + url + '" alt="KTP atau kartu pelajar">';
        identityModal.classList.add('active');
        identityModal.setAttribute('aria-hidden', 'false');
    });
    document.getElementById('identity-preview-close').addEventListener('click', closeIdentityPreview);
    identityModal.addEventListener('click', function (event) { if (event.target === identityModal) closeIdentityPreview(); });

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

    document.querySelectorAll('.profile-tabs-user button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.profile-tabs-user button').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.profile-tab-panel').forEach(panel => panel.classList.remove('active'));
            button.classList.add('active');
            document.getElementById('tab-' + button.dataset.tab).classList.add('active');
        });
    });

    const activityToggle = document.getElementById('activity-toggle');
    const activityCard = activityToggle.closest('.activity-card-user');
    activityToggle.addEventListener('click', function (e) {
        e.preventDefault();
        const showAll = activityCard.classList.toggle('show-all');
        activityToggle.textContent = showAll ? 'Sembunyikan' : 'Lihat Semua';
    });

    document.getElementById('profile-photo-input').addEventListener('change', async function () {
        const file = this.files[0];
        if (!file) return;
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) {
            alert('Foto harus JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.');
            this.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('photo', file);
        const token = (document.querySelector('input[name="_token"]') || {}).value || '';
        try {
            const response = await fetch('{{ url('/profil-user/photo') }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            if (!response.ok) {
                const error = await response.json().catch(function () { return {}; });
                throw new Error(error.message || 'Gagal menyimpan foto profil.');
            }
            const data = await response.json();
            document.getElementById('profile-photo').src = data.photo;
            document.getElementById('top-avatar').src = data.photo;
            const saved = JSON.parse(localStorage.getItem('sipibs_user_profile') || '{}');
            saved.photo = data.photo;
            localStorage.setItem('sipibs_user_profile', JSON.stringify(saved));
            if (window.applySipibsProfile) window.applySipibsProfile();
        } catch (error) {
            alert(error.message);
        } finally {
            this.value = '';
        }
    });
    function getUserProfileFormData() {
        const saved = JSON.parse(localStorage.getItem('sipibs_user_profile') || '{}');
        return {
            name: document.getElementById('input-name').value,
            email: document.getElementById('input-email').value,
            className: document.getElementById('input-class').value,
            nis: document.getElementById('input-nis').value,
            birthdate: document.getElementById('input-birthdate').value,
            gender: document.getElementById('input-gender').value,
            photo: saved.photo || ''
        };
    }

    function applyUserProfileToPage(profile) {
        document.getElementById('hero-name').textContent = profile.name;
        document.getElementById('top-user-name').textContent = profile.name;
        document.getElementById('hero-email').textContent = profile.email;
        document.getElementById('input-name').value = profile.name;
        document.getElementById('input-email').value = profile.email;
        document.getElementById('input-class').value = profile.className;
        document.getElementById('input-nis').value = profile.nis;
        document.getElementById('input-birthdate').value = profile.birthdate;
        document.getElementById('input-gender').value = profile.gender;
        if (profile.photo) {
            document.getElementById('profile-photo').src = profile.photo;
            document.getElementById('top-avatar').src = profile.photo;
        }
    }

    document.getElementById('profile-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const profile = getUserProfileFormData();
        localStorage.setItem('sipibs_user_profile', JSON.stringify(profile));
        applyUserProfileToPage(profile);
        if (window.applySipibsProfile) window.applySipibsProfile();
        const token = (document.querySelector('input[name="_token"]') || {}).value || '';
        fetch('{{ url('/profil-user') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(profile)
        }).catch(function () {});
        const toast = document.getElementById('profile-toast');
        toast.classList.add('active');
        setTimeout(() => toast.classList.remove('active'), 2500);
    });

    document.querySelectorAll('.password-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.passwordTarget);
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            button.innerHTML = visible ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
            button.setAttribute('aria-label', visible ? 'Tampilkan password' : 'Sembunyikan password');
        });
    });
    document.getElementById('password-save').addEventListener('click', async function () {
        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmation = document.getElementById('new-password-confirmation').value;
        if (!currentPassword || !newPassword || !confirmation) return alert('Semua password wajib diisi.');
        if (newPassword.length < 8) return alert('Password baru minimal 8 karakter.');
        if (newPassword !== confirmation) return alert('Konfirmasi password baru tidak cocok.');
        const token = (document.querySelector('input[name="_token"]') || {}).value || '';
        const response = await fetch('{{ route('profil.user.password') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: JSON.stringify({ current_password: currentPassword, password: newPassword, password_confirmation: confirmation }) });
        const data = await response.json().catch(function () { return {}; });
        if (!response.ok) return alert(data.message || 'Password gagal diubah.');
        document.getElementById('current-password').value = '';
        document.getElementById('new-password').value = '';
        document.getElementById('new-password-confirmation').value = '';
        alert(data.message);
    });
    // const savedUserProfile = null;
    // Data profil selalu menggunakan data database terkini

    window.history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);
</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>








