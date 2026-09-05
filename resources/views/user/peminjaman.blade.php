<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Barang - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=26">
</head>
<body>
@php
    $userName = Auth::check() ? Auth::user()->name : 'Siswa';
    $userRole = 'SISWA';
    $step = request('step', 1);
    $barangData = [
        'Mouse HP USB-2' => ['cat' => 'Elektronik', 'image' => 'mouse HP.png'],
        'Keyboard NuPhy Air75' => ['cat' => 'Elektronik', 'image' => 'keyboard kantor.png'],
        'Laptop Lenovo Ideapad Slim 3' => ['cat' => 'Elektronik', 'image' => 'Lenovo Ideapad Slim 3.jpg'],
        'Headset Logitech G 432 7.1' => ['cat' => 'Audio Visual', 'image' => 'HEADSET LOGITECH.png'],
        '4K Webcam 1080P 60fps Mini Video Camera' => ['cat' => 'Elektronik', 'image' => 'WEBCAM.jpg'],
        'Epson EX3240 SVGA 3LCD Projector 3200' => ['cat' => 'Praktikum', 'image' => 'PROYEKTOR EPSON.jpg'],
        'Kabel Black High Speed 1.4 Version Gold-Plated HDMI' => ['cat' => 'Peralatan Kantor', 'image' => 'kabel_hdmi.png'],
        'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin' => ['cat' => 'Peralatan Kantor', 'image' => 'kabel vga.jpg'],
        'Kabel LAN CAT6 UTP Cable Networking' => ['cat' => 'Peralatan Kantor', 'image' => 'KABEL LAN.jpg'],
        'Kabel HDMI to VGA Adapter Gold Plated' => ['cat' => 'Peralatan Kantor', 'image' => 'KABEL HDMI TO VGA.jpg'],
        'Pen Wireless Remote Controller Laser Pointer' => ['cat' => 'Elektronik', 'image' => 'pointer.jpg'],
        'Stop Kontak' => ['cat' => 'Elektronik', 'image' => 'STOP KONTAK.jpg'],
        '2pcs Multifunctional network tester 468 network cable' => ['cat' => 'Peralatan Kantor', 'image' => 'LAN tester.jpg'],
        'Tang Crimping Tool RJ45 RJ11 HT-200R' => ['cat' => 'Peralatan Kantor', 'image' => 'Tang crimping.jpg'],
        'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth' => ['cat' => 'Elektronik', 'image' => 'speaker portable.jpg'],
    ];
    $barangOptions = array_keys($barangData);
    $selectedBarang = request('barang', '');
    $selectedBarangData = $barangData[$selectedBarang] ?? ['cat' => '-', 'image' => null];
    $namaPeminjam = request('nama', '');
    $nisPeminjam = request('nim', '');
    $jumlahPinjam = request('jumlah', '1');
    $tanggalPinjam = request('tgl_pinjam', '');
    $tanggalKembali = request('tgl_kembali', '');
    $keperluanPinjam = request('keperluan', '');
    $catatanPinjam = request('catatan', '');
    $tanggalPinjamText = $tanggalPinjam ? \Carbon\Carbon::parse($tanggalPinjam)->translatedFormat('d F Y') : '-';
    $tanggalKembaliText = $tanggalKembali ? \Carbon\Carbon::parse($tanggalKembali)->translatedFormat('d F Y') : '-';
    $tanggalPinjamJson = $tanggalPinjam ? \Carbon\Carbon::parse($tanggalPinjam)->format('d/m/Y') : '';
    $tanggalKembaliJson = $tanggalKembali ? \Carbon\Carbon::parse($tanggalKembali)->format('d/m/Y') : '';
    $queryStep1 = request()->except('step');
    $queryStep2 = array_merge($queryStep1, ['step' => 2]);
    $queryStep3 = array_merge($queryStep1, ['step' => 3]);
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
            <a class="nav-item active" href="{{ url('/peminjaman-user') }}"><i class="bi bi-gem"></i> Peminjaman</a>
            <a class="nav-item" href="{{ url('/pengembalian-user') }}"><i class="bi bi-calendar-check"></i> Pengembalian</a>
            <a class="nav-item" href="{{ url('/denda-user') }}"><i class="bi bi-cash-coin"></i> Denda</a>
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-area">
        <header class="topbar">
            @if($step == 2 || $step == 3)
                <div class="back-header-title">
                    <a href="{{ $step == 2 ? url('/peminjaman-user?' . http_build_query($queryStep1 + ['step' => 1])) : url('/dashboard-user') }}" class="back-link-icon"><i class="bi bi-arrow-left"></i></a>
                    <div>
                        <h2>Peminjaman Barang</h2>
                        <span>Ajukan Peminjaman</span>
                    </div>
                </div>
            @else
                <div class="search-box condition-search"><i class="bi bi-search"></i><input id="loanSearch" type="text" placeholder="Cari Inventaris.."></div>
            @endif
            <div class="top-actions">
                @include('user.partials.notification-bell')

                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar">
                </div>
            </div>
        </header>

        <section class="content peminjaman-content">
            @if($step == 1)
                <!-- TAHAP 1: FORM PEMINJAMAN -->
                <div class="peminjaman-page-head">
                    <h1>Peminjaman Barang</h1>
                    <p>Ajukan peminjaman barang inventaris yang tersedia untuk kebutuhan Anda.</p>
                </div>

                <div class="peminjaman-card">
                    <div class="card-head-title">
                        <i class="bi bi-calendar-event"></i> <strong>Form Peminjaman</strong>
                    </div>
                    <form id="form-step-1" action="{{ url('/peminjaman-user') }}" method="GET">
                        <input type="hidden" name="step" value="2">
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label>Nama Peminjam</label>
                                <input type="text" class="form-control" id="input-name-pminjam" name="nama" value="{{ $namaPeminjam }}" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="form-group">
                                <label>NIS / NIP</label>
                                <input type="text" class="form-control" id="input-nis-pminjam" name="nim" value="{{ $nisPeminjam }}" placeholder="Masukkan NIS atau NIP" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Peminjaman</label>
                                <div class="date-input-wrap">
                                    <input type="date" class="form-control date-picker-input" name="tgl_pinjam" value="{{ $tanggalPinjam }}" required>
                                    <i class="bi bi-calendar3 date-picker-icon"></i>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Pilih Barang</label>
                                <select class="form-control" name="barang" required>
                                    <option value="" disabled {{ $selectedBarang === '' ? 'selected' : '' }}>Pilih barang yang akan dipinjam</option>
                                    @foreach ($barangOptions as $barangOption)
                                        <option value="{{ $barangOption }}" {{ $selectedBarang === $barangOption ? 'selected' : '' }}>{{ $barangOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" class="form-control" name="jumlah" value="{{ $jumlahPinjam }}" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pengembalian</label>
                                <div class="date-input-wrap">
                                    <input type="date" class="form-control date-picker-input" name="tgl_kembali" value="{{ $tanggalKembali }}" required>
                                    <i class="bi bi-calendar3 date-picker-icon"></i>
                                </div>
                                <small style="display:flex;align-items:center;gap:6px;margin-top:6px;font-size:0.78rem;color:#e11d48;font-weight:500;"><i class="bi bi-info-circle"></i> Maksimal peminjaman adalah 2 hari.</small>
                            </div>

                            <div class="form-group col-span-1-5">
                                <label>Keperluan Peminjaman</label>
                                <textarea class="form-control textarea-box" name="keperluan" placeholder="Jelaskan keperluan peminjaman barang...">{{ $keperluanPinjam }}</textarea>
                            </div>
                            <div class="form-group col-span-1-5">
                                <label>Catatan Tambahan (Opsional)</label>
                                <textarea class="form-control textarea-box" name="catatan" placeholder="Catatan tambahan jika diperlukan...">{{ $catatanPinjam !== '-' ? $catatanPinjam : '' }}</textarea>
                            </div>
                        </div>

                        <div class="form-bottom-bar">
                            <div class="info-alert">
                                <i class="bi bi-info-circle-fill"></i> Pastikan data yang Anda masukkan sudah benar sebelum mengajukan peminjaman.
                            </div>
                            <button type="submit" class="btn-primary-sipibs"><i class="bi bi-send"></i> Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>

                <!-- RIWAYAT PEMINJAMAN SAYA -->
                <div class="peminjaman-card history-card">
                    <div class="card-head-title">
                        <i class="bi bi-clock-history"></i> <strong>Riwayat Peminjaman Saya</strong>
                    </div>
                    <table class="table-sipibs">
                        <thead>
                            <tr>
                                <th>BARANG</th>
                                <th>TANGGAL PINJAM</th>
                                <th>TANGGAL KEMBALI</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="peminjaman-history-body">
                        </tbody>
                    </table>
                    <div id="peminjaman-history-empty" style="display:none;text-align:center;padding:30px 20px;color:#888;">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>Belum ada riwayat peminjaman.
                    </div>
                    <div id="peminjaman-history-footer" style="display:none;text-align:center;padding:16px 20px;border-top:1px solid #f0f0f0;margin-top:8px;">
                        <a href="{{ url('/riwayat-pinjam') }}" style="display:inline-flex;align-items:center;gap:6px;color:#003985;font-size:13px;font-weight:700;text-decoration:none;">Lihat Semua <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

            @elseif($step == 2)
                <!-- TAHAP 2: SYARAT & KETENTUAN -->
                <div class="stepper-wrap">
                    <div class="step-item done">
                        <div class="step-icon"><i class="bi bi-check-lg"></i></div>
                        <div><strong>1. Form Peminjaman</strong><small>Selesai</small></div>
                    </div>
                    <div class="step-line active"></div>
                    <div class="step-item active">
                        <div class="step-icon">2</div>
                        <div><strong>2. Syarat & Ketentuan</strong><small>Baca dan setujui</small></div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-item">
                        <div class="step-icon">3</div>
                        <div><strong>3. Proses Peminjaman</strong><small>Menunggu persetujuan admin</small></div>
                    </div>
                </div>

                <div class="peminjaman-card terms-card">
                    <div class="terms-header">
                        <span class="blue-icon-box"><i class="bi bi-bookmark"></i></span>
                        <div>
                            <h2>Syarat & Ketentuan Peminjaman</h2>
                            <p>Sebelum peminjaman Anda diproses, harap baca dan setujui syarat & ketentuan berikut.</p>
                        </div>
                    </div>

                    <div class="terms-list">
                        <div class="terms-item">
                            <span class="circle-icon"><i class="bi bi-clock"></i></span>
                            <div>
                                <h3>Batas Waktu Pengembalian</h3>
                                <p>Barang wajib dikembalikan sesuai dengan tanggal dan waktu pengembalian yang telah ditentukan.</p>
                            </div>
                        </div>

                        <div class="terms-item">
                            <span class="circle-icon orange"><i class="bi bi-calendar-x"></i></span>
                            <div>
                                <h3>Keterlambatan Pengembalian</h3>
                                <p>Jika barang dikembalikan terlambat, Anda wajib memberikan alasan kepada admin dan mengikuti ketentuan yang berlaku di sekolah.</p>
                            </div>
                        </div>

                        <div class="terms-item">
                            <span class="circle-icon cyan"><i class="bi bi-wrench"></i></span>
                            <div>
                                <h3>Kerusakan Barang</h3>
                                <p>Jika barang mengalami kerusakan selama masa peminjaman akibat kelalaian pengguna, Anda wajib melaporkan kerusakan kepada admin dan bertanggung jawab sesuai ketentuan sekolah.</p>
                            </div>
                        </div>

                        <div class="terms-item">
                            <span class="circle-icon yellow-warning"><i class="bi bi-exclamation-triangle"></i></span>
                            <div>
                                <h3>Kehilangan Barang</h3>
                                <p>Jika barang hilang selama masa peminjaman, Anda wajib segera melaporkan kepada admin dan bertanggung jawab atas kehilangan tersebut sesuai ketentuan sekolah.</p>
                            </div>
                        </div>

                        <div class="terms-item">
                            <span class="circle-icon blue-shield"><i class="bi bi-shield-check"></i></span>
                            <div>
                                <h3>Tanggung Jawab Barang</h3>
                                <p>Anda wajib menjaga barang yang dipinjam dan mengembalikannya dalam kondisi sebagaimana saat barang diterima, dengan mempertimbangkan kondisi awal barang.</p>
                            </div>
                        </div>

                        <div class="terms-item terms-penalty">
                            <span class="circle-icon red-penalty"><i class="bi bi-exclamation-octagon"></i></span>
                            <div>
                                <h3>Denda Kerusakan & Keterlambatan</h3>
                                <p>Apabila barang mengalami kerusakan atau pengembalian melewati batas waktu yang ditentukan, Anda akan dikenakan denda sesuai ketentuan sekolah. Denda kerusakan dihitung berdasarkan tingkat kerusakan barang, sedangkan denda keterlambatan dikenakan per hari keterlambatan pengembalian.</p>
                            </div>
                        </div>
                    </div>

                    <div class="checkbox-agree-box">
                        <label>
                            <input type="checkbox" id="check-agree" onchange="toggleAgreeBtn()">
                            <span>Saya telah membaca dan menyetujui semua syarat & ketentuan peminjaman di atas.</span>
                        </label>
                    </div>

                    <div class="terms-actions">
                        <a href="{{ url('/peminjaman-user?' . http_build_query($queryStep1 + ['step' => 1])) }}" class="btn-outline-sipibs"><i class="bi bi-arrow-left"></i> Kembali ke Form</a>
                        <a href="{{ url('/peminjaman-user?' . http_build_query($queryStep3)) }}" id="btn-submit-step2" class="btn-primary-sipibs disabled" onclick="return checkAgree(event)">Setuju & Ajukan Peminjaman <i class="bi bi-send"></i></a>
                    </div>
                </div>

                <div class="info-alert bottom-info">
                    <i class="bi bi-info-circle-fill"></i> Setelah Anda menyetujui syarat & ketentuan, pengajuan peminjaman Anda akan diproses dan menunggu persetujuan admin.
                </div>

            @elseif($step == 3)
                <!-- TAHAP 3: PROSES PEMINJAMAN / MENUNGGU PERSETUJUAN -->
                <div class="stepper-wrap">
                    <div class="step-item done">
                        <div class="step-icon"><i class="bi bi-check-lg"></i></div>
                        <div><strong>1. Form Peminjaman</strong><small>Selesai</small></div>
                    </div>
                    <div class="step-line active"></div>
                    <div class="step-item done">
                        <div class="step-icon"><i class="bi bi-check-lg"></i></div>
                        <div><strong>2. Syarat & Ketentuan</strong><small>Selesai</small></div>
                    </div>
                    <div class="step-line active" id="step3-line"></div>
                    <div class="step-item active" id="step3-item">
                        <div class="step-icon" id="step3-icon">3</div>
                        <div><strong>3. Proses Peminjaman</strong><small id="step3-label">Menunggu persetujuan admin</small></div>
                    </div>
                </div>

                <div class="process-banner" id="process-banner">
                    <span class="icon-hour-glass"><i class="bi bi-hourglass-split"></i></span>
                    <div>
                        <h2>Proses Peminjaman</h2>
                        <p>Pengajuan peminjaman Anda telah diterima dan sedang menunggu persetujuan admin.</p>
                    </div>
                </div>

                <div class="peminjaman-card status-header-card">
                    <div class="status-left">
                        <div class="clock-circle" id="status-clock"><i class="bi bi-clock"></i></div>
                        <div>
                            <small>Status Pengajuan</small>
                            <h2 class="text-blue" id="status-title">Menunggu Persetujuan</h2>
                            <p id="status-desc">Pengajuan Anda sedang diperiksa oleh admin.</p>
                        </div>
                    </div>
                    <div class="status-right">
                        <div class="id-meta">
                            <span>ID Peminjaman</span>
                            <span class="badge-code">PMJ-2025-00067</span>
                        </div>
                        <div class="date-meta">
                            <span>Tanggal Pengajuan</span>
                            <strong><i class="bi bi-calendar-event"></i> {{ now()->translatedFormat('d F Y H:i') }} WIB</strong>
                        </div>
                    </div>
                </div>

                <div class="peminjaman-card detail-peminjaman-card">
                    <h3>Detail Peminjaman</h3>
                    <div class="detail-flex">
                        <div class="detail-image-preview">
                                <img data-loan-field="image" src="{{ !empty($selectedBarangData['image']) ? url('images/' . rawurlencode($selectedBarangData['image']) . '?v=3') : url('images/no-image.svg') }}" alt="{{ $selectedBarang }}" onerror="this.onerror=null;this.src='{{ url('images/no-image.svg') }}';">
                            </div>
                        <div class="detail-info-grid">
                            <div class="info-row"><span>Nama Barang</span><strong>: {{ $selectedBarang }}</strong></div>
                            <div class="info-row"><span>Kategori</span><strong>: {{ $selectedBarangData['cat'] }}</strong></div>
                            <div class="info-row"><span>Kondisi Barang</span><strong>: Baik</strong></div>
                            <div class="info-row"><span>Jumlah</span><strong>: {{ $jumlahPinjam }}</strong></div>
                        </div>
                        <div class="detail-info-grid">
                            <div class="info-row"><span>Keperluan</span><strong>: {{ $keperluanPinjam }}</strong></div>
                            <div class="info-row"><span>Tanggal Pinjam</span><strong>: <i class="bi bi-calendar-event"></i> {{ $tanggalPinjamJson ?: '-' }}</strong></div>
                            <div class="info-row"><span>Tanggal Kembali</span><strong>: <i class="bi bi-calendar-event"></i> {{ $tanggalKembaliJson ?: '-' }}</strong></div>
                        </div>
                    </div>
                </div>

                <div class="flow-container">
                    <div class="flow-card">
                        <h3>Alur Proses Peminjaman</h3>
                        <div class="flow-tracker">
                            <div class="flow-step done">
                                <div class="dot"><i class="bi bi-check"></i></div>
                                <strong>Pengajuan Dikirim</strong>
                                <small>{{ now()->translatedFormat('d F Y') }}<br>{{ now()->format('H:i') }} WIB</small>
                            </div>
                            <div class="flow-line active" id="flow-line-1"></div>
                            <div class="flow-step active" id="flow-step-2">
                                <div class="dot" id="flow-dot-2"><i class="bi bi-clock"></i></div>
                                <strong>Menunggu Persetujuan Admin</strong>
                                <small>Sedang diperiksa oleh admin SIPIBS</small>
                            </div>
                            <div class="flow-line" id="flow-line-2"></div>
                            <div class="flow-step" id="flow-step-3">
                                <div class="dot" id="flow-dot-3"></div>
                                <strong>Persetujuan</strong>
                                <small>Akan diberitahukan setelah ada keputusan dari admin</small>
                            </div>
                        </div>
                    </div>

                    <div class="side-info-card">
                        <h3>Yang Perlu Anda Lakukan</h3>
                        <p>Silakan menunggu persetujuan dari admin. Status akan diperbarui dan Anda akan mendapatkan notifikasi.</p>
                        <div class="side-illustration">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="bottom-actions-wrap">
                    <a href="{{ url('/dashboard-user') }}" class="btn-outline-sipibs"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
                </div>

                <div class="info-alert bottom-info">
                    <i class="bi bi-info-circle-fill"></i> Anda akan mendapatkan notifikasi ketika pengajuan peminjaman telah disetujui atau ditolak oleh admin.
                </div>
            @endif
        </section>
    </main>
</div>

<!-- MODAL POPUP SIMULASI SUDAH DISETUJUI (IMAGE #4) -->
<div id="modal-approved" class="sipibs-modal-overlay">
    <div class="sipibs-modal-content">
        <button class="modal-close" onclick="closeModal('approved')">&times;</button>
        <div class="modal-icon-success">
            <i class="bi bi-check-lg"></i>
        </div>
        <h2>Peminjaman Disetujui!</h2>
        <p class="modal-sub">Selamat! Peminjaman barang Anda telah disetujui oleh admin.<br>Silakan cetak bukti peminjaman sebagai tanda terima.</p>

        <div class="modal-detail-box">
            <div class="modal-detail-head">
                <span>Detail Peminjaman</span>
            </div>
            <div class="modal-detail-body">
                <img data-loan-field="image" src="{{ !empty($selectedBarangData['image']) ? url('images/' . rawurlencode($selectedBarangData['image']) . '?v=3') : url('images/no-image.svg') }}" alt="{{ $selectedBarang }}" onerror="this.onerror=null;this.src='{{ url('images/no-image.svg') }}';">
                <div class="modal-meta-grid">
                    <div><span>ID Peminjaman</span><strong class="badge-code-sm" data-loan-field="id">PMJ-2025-00067</strong></div>
                    <div><span>Nama Barang</span><strong data-loan-field="barang">{{ $selectedBarang }}</strong></div>
                    <div><span>Kategori</span><strong data-loan-field="kategori">{{ $selectedBarangData['cat'] }}</strong></div>
                    <div><span>Jumlah</span><strong data-loan-field="jumlah">{{ $jumlahPinjam }}</strong></div>
                    <div><span>Kondisi</span><strong>Baik</strong></div>
                </div>
            </div>
            <div class="modal-detail-foot">
                <div><span>Tanggal Pinjam</span><strong data-loan-field="tanggalPinjamText">{{ $tanggalPinjamText }}</strong></div>
                <div><span>Tanggal Kembali</span><strong data-loan-field="tanggalKembaliText">{{ $tanggalKembaliText }}</strong></div>
                <div><span>Keperluan</span><strong data-loan-field="keperluan">{{ $keperluanPinjam }}</strong></div>
            </div>
        </div>

        <div class="bukti-peminjaman-box">
            <i class="bi bi-printer"></i>
            <div>
                <strong>Bukti Peminjaman</strong>
                <p>Download bukti peminjaman untuk ditunjukkan saat mengambil serta mengembalikan barang.</p>
            </div>
        </div>

        <div class="modal-buttons">
            <button class="btn-modal-secondary" onclick="closeModal('approved')">Nanti Saja</button>
            <a class="btn-modal-primary" data-loan-field="download" href="{{ url('/bukti-peminjaman?' . http_build_query($queryStep1)) }}" target="_blank" onclick="setTimeout(clearUserLoanNotification, 300)"><i class="bi bi-download"></i> Download</a>
        </div>
    </div>
</div>

<!-- MODAL POPUP SIMULASI DITOLAK (IMAGE #5) -->
<div id="modal-rejected" class="sipibs-modal-overlay">
    <div class="sipibs-modal-content">
        <button class="modal-close" onclick="closeModal('rejected')">&times;</button>
        <div class="modal-icon-danger">
            <i class="bi bi-x-lg"></i>
        </div>
        <h2>Peminjaman Ditolak!</h2>
        <p class="modal-sub">Maaf! Peminjaman barang Anda ditolak oleh admin. Silakan periksa alasan penolakan di bawah ini.</p>

        <div class="modal-detail-box">
            <div class="modal-detail-head">
                <span>Detail Peminjaman</span>
            </div>
            <div class="modal-detail-body">
                <img data-loan-field="image" src="{{ !empty($selectedBarangData['image']) ? url('images/' . rawurlencode($selectedBarangData['image']) . '?v=3') : url('images/no-image.svg') }}" alt="{{ $selectedBarang }}" onerror="this.onerror=null;this.src='{{ url('images/no-image.svg') }}';">
                <div class="modal-meta-grid">
                    <div><span>Nama Barang</span><strong>: {{ $selectedBarang }}</strong></div>
                    <div><span>Kategori</span><strong>: {{ $selectedBarangData['cat'] }}</strong></div>
                    <div><span>Jumlah</span><strong>: {{ $jumlahPinjam }}</strong></div>
                    <div><span>Kondisi</span><strong>: Baik</strong></div>
                </div>
            </div>
            <div class="modal-detail-foot">
                <div><span>Tanggal Pinjam</span><strong>: {{ $tanggalPinjamText }}</strong></div>
                <div><span>Tanggal Kembali</span><strong>: {{ $tanggalKembaliText }}</strong></div>
                <div><span>Keperluan</span><strong>: {{ $keperluanPinjam }}</strong></div>
            </div>

            <div class="alasan-penolakan-box">
                <span class="icon-danger-sm"><i class="bi bi-x"></i></span>
                <div>
                    <strong class="text-danger">Alasan Penolakan</strong>
                    <p>Barang sedang tidak tersedia.</p>
                </div>
            </div>
        </div>

        <div class="modal-buttons text-center">
            <button class="btn-modal-secondary w-full" onclick="closeModal('rejected')">Tutup</button>
        </div>
    </div>
</div>

<!-- MODAL POPUP DETAIL PENDING (MENUNGGU PERSETUJUAN) -->
<div id="modal-pending" class="sipibs-modal-overlay">
    <div class="sipibs-modal-content">
        <button class="modal-close" onclick="closeModal('pending')">&times;</button>
        <div class="modal-icon-wait">
            <i class="bi bi-clock"></i>
        </div>
        <h2>Detail Peminjaman</h2>
        <p class="modal-sub">Peminjaman barang Anda sedang menunggu persetujuan admin.</p>

        <div class="modal-detail-box">
            <div class="modal-detail-head">
                <span>Detail Peminjaman</span>
            </div>
            <div class="modal-detail-body">
                <img data-loan-field="image" src="{{ !empty($selectedBarangData['image']) ? url('images/' . rawurlencode($selectedBarangData['image']) . '?v=3') : url('images/no-image.svg') }}" alt="{{ $selectedBarang }}" onerror="this.onerror=null;this.src='{{ url('images/no-image.svg') }}';">
                <div class="modal-meta-grid">
                    <div><span>ID Peminjaman</span><strong class="badge-code-sm" data-loan-field="id">PMJ-2025-00067</strong></div>
                    <div><span>Nama Barang</span><strong data-loan-field="barang">{{ $selectedBarang }}</strong></div>
                    <div><span>Kategori</span><strong data-loan-field="kategori">{{ $selectedBarangData['cat'] }}</strong></div>
                    <div><span>Jumlah</span><strong data-loan-field="jumlah">{{ $jumlahPinjam }}</strong></div>
                    <div><span>Kondisi</span><strong>Baik</strong></div>
                </div>
            </div>
            <div class="modal-detail-foot">
                <div><span>Tanggal Pinjam</span><strong data-loan-field="tanggalPinjamText">{{ $tanggalPinjamText }}</strong></div>
                <div><span>Tanggal Kembali</span><strong data-loan-field="tanggalKembaliText">{{ $tanggalKembaliText }}</strong></div>
                <div><span>Keperluan</span><strong data-loan-field="keperluan">{{ $keperluanPinjam }}</strong></div>
            </div>
        </div>

        <div class="modal-buttons">
            <button class="btn-modal-secondary" onclick="closeModal('pending')">Nanti Saja</button>
            <a class="btn-modal-primary" data-loan-field="download" href="{{ url('/bukti-peminjaman?' . http_build_query($queryStep1)) }}" target="_blank" onclick="setTimeout(clearUserLoanNotification, 300)"><i class="bi bi-download"></i> Cetak</a>
        </div>
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

    const loanSearch = document.getElementById('loanSearch');
    if (loanSearch) {
        const barangSelect = document.querySelector('select[name="barang"]');
        loanSearch.addEventListener('input', function () {
            if (!barangSelect) return;
            const q = loanSearch.value.toLowerCase().trim();
            let visible = 0;
            Array.from(barangSelect.options).forEach(function (opt) {
                const match = !q || opt.textContent.toLowerCase().includes(q);
                opt.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (barangSelect.selectedOptions[0] && barangSelect.selectedOptions[0].style.display === 'none') {
                barangSelect.value = '';
            }
        });
    }

    function toggleAgreeBtn() {
        const checkbox = document.getElementById('check-agree');
        const btn = document.getElementById('btn-submit-step2');
        if (checkbox.checked) {
            btn.classList.remove('disabled');
        } else {
            btn.classList.add('disabled');
        }
    }

    function checkAgree(e) {
        const checkbox = document.getElementById('check-agree');
        if (!checkbox.checked) {
            e.preventDefault();
            alert('Harap centang persetujuan syarat & ketentuan terlebih dahulu.');
            return false;
        }
        return true;
    }

    function formatLoanDate(dateValue) {
        if (!dateValue) return '-';
        if (dateValue.includes('/')) {
            const parts = dateValue.split('/');
            dateValue = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        const date = new Date(dateValue + 'T00:00:00');
        if (Number.isNaN(date.getTime())) return dateValue;
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function getLatestLoanRequest() {
        const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
        const request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
        return (decision && decision.request) ? decision.request : request;
    }

    function syncLoanModalData() {
        const request = getLatestLoanRequest();
        if (!request) return;
        document.querySelectorAll('[data-loan-field="id"]').forEach(el => el.textContent = request.id || 'PMJ-2025-00067');
        document.querySelectorAll('[data-loan-field="barang"]').forEach(el => el.textContent = request.barang || '-');
        document.querySelectorAll('[data-loan-field="kategori"]').forEach(el => el.textContent = request.kategori || '-');
        document.querySelectorAll('[data-loan-field="jumlah"]').forEach(el => el.textContent = request.jumlah || '1');
        document.querySelectorAll('[data-loan-field="keperluan"]').forEach(el => el.textContent = request.keperluan || '-');
        document.querySelectorAll('[data-loan-field="tanggalPinjamText"]').forEach(el => el.textContent = formatLoanDate(request.tanggalPinjam));
        document.querySelectorAll('[data-loan-field="tanggalKembaliText"]').forEach(el => el.textContent = formatLoanDate(request.tanggalKembali));
        document.querySelectorAll('[data-loan-field="image"]').forEach(function (img) {
            if (request.image) {
                img.src = request.image;
                img.alt = request.barang || 'Barang';
            }
        });
        document.querySelectorAll('[data-loan-field="download"]').forEach(function (link) {
            if (request.downloadUrl) link.href = request.downloadUrl;
        });
    }

    function openModal(id) {
        syncLoanModalData();
        document.getElementById('modal-' + id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).classList.remove('active');
    }

    document.querySelectorAll('.date-picker-icon').forEach(function (icon) {
        icon.addEventListener('click', function () {
            var input = this.parentElement.querySelector('.date-picker-input');
            if (!input) return;
            if (typeof input.showPicker === 'function') {
                try {
                    input.showPicker();
                } catch (e) {
                    input.focus();
                }
            } else {
                input.focus();
            }
        });
    });


    const loanRequestData = {
        id: 'PMJ-2025-00067',
        nama: @json($namaPeminjam),
        nis: @json($nisPeminjam),
        barang: @json($selectedBarang),
        kategori: @json($selectedBarangData['cat']),
        jumlah: @json($jumlahPinjam),
        tanggalPinjam: @json($tanggalPinjamJson),
        tanggalKembali: @json($tanggalKembaliJson),
        keperluan: @json($keperluanPinjam),
        image: @json(!empty($selectedBarangData['image']) ? url('images/' . rawurlencode($selectedBarangData['image']) . '?v=3') : url('images/no-image.svg')),
        detailUrl: @json(url('/peminjaman-user?' . http_build_query($queryStep3))),
        downloadUrl: @json(url('/bukti-peminjaman?' . http_build_query($queryStep1)))
    };

    function getLoanHistoryList() {
        return JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
    }

    function saveLoanHistoryItem(item) {
        const list = getLoanHistoryList().filter(loan => loan.id !== item.id);
        list.unshift(item);
        localStorage.setItem('sipibsLoanHistory', JSON.stringify(list));
        const returnList = JSON.parse(localStorage.getItem('sipibsReturnLoanItems') || '[]');
        const returnItems = Array.isArray(returnList) ? returnList.filter(loan => loan.id !== item.id) : [];
        returnItems.unshift(item);
        localStorage.setItem('sipibsReturnLoanItems', JSON.stringify(returnItems));
    }

    function decreaseItemStock(name, qty) {
        const stocks = JSON.parse(localStorage.getItem('sipibsItemStock') || '{}');
        const defaults = {
            'Mouse HP USB-2': 5, 'Keyboard NuPhy Air75': 3, 'Laptop Lenovo Ideapad Slim 3': 12,
            'Headset Logitech G 432 7.1': 8, '4K Webcam 1080P 60fps Mini Video Camera': 15,
            'Epson EX3240 SVGA 3LCD Projector 3200': 4, 'Kabel Black High Speed 1.4 Version Gold-Plated HDMI': 20,
            'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin': 10, 'Kabel LAN CAT6 UTP Cable Networking': 6,
            'Kabel HDMI to VGA Adapter Gold Plated': 2, 'Pen Wireless Remote Controller Laser Pointer': 7,
            'Stop Kontak': 4, '2pcs Multifunctional network tester 468 network cable': 14,
            'Tang Crimping Tool RJ45 RJ11 HT-200R': 9, 'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth': 5
        };
        if (stocks[name] === undefined) stocks[name] = defaults[name] || 10;
        stocks[name] = Math.max(0, stocks[name] - parseInt(qty));
        localStorage.setItem('sipibsItemStock', JSON.stringify(stocks));
    }
    @if($step == 1)
        localStorage.removeItem('sipibsLoanSubmitted');
        localStorage.removeItem('redirectedLoanToReturn');
    @endif
    @if($step == 3)
        const loanSubmitKey = 'sipibsLoanSubmitted:' + [loanRequestData.nama, loanRequestData.nis, loanRequestData.barang, loanRequestData.jumlah, loanRequestData.tanggalPinjam, loanRequestData.tanggalKembali].join('|');
        if (localStorage.getItem(loanSubmitKey) !== '1') {
            loanRequestData.id = 'PMJ-' + Date.now();
            loanRequestData.status = 'Dipinjam';
            loanRequestData.submittedAt = new Date().toISOString();
            localStorage.setItem('sipibsLoanRequest', JSON.stringify(loanRequestData));
            localStorage.setItem('sipibsLoanDecision', JSON.stringify({
                status: 'pending',
                request: loanRequestData,
                submittedAt: loanRequestData.submittedAt
            }));
            saveLoanHistoryItem(loanRequestData);
            decreaseItemStock(loanRequestData.barang, loanRequestData.jumlah);
            localStorage.removeItem('sipibsLoanNotificationDone');
            localStorage.setItem('sipibsLoanSubmitted', '1');
            localStorage.setItem(loanSubmitKey, '1');
        }
    @endif

    function saveLoanDecision(status) {
        localStorage.removeItem('sipibsLoanNotificationDone');
        loanRequestData.status = status;
        loanRequestData.decidedAt = new Date().toISOString();
        saveLoanHistoryItem(loanRequestData);
        localStorage.setItem('sipibsLoanDecision', JSON.stringify({ status: status, request: loanRequestData, decidedAt: loanRequestData.decidedAt }));
        openModal(status === 'approved' ? 'approved' : 'rejected');
        renderUserLoanNotification();
        renderPeminjamanHistory();
    }

    function clearUserLoanNotification() {
        localStorage.setItem('sipibsLoanNotificationDone', '1');
        document.querySelectorAll('.sipibs-modal-overlay.active').forEach(modal => modal.classList.remove('active'));
        renderUserLoanNotification();
    }

    function renderUserLoanNotification() {
        const wrapper = document.querySelector('[data-user-loan-notification]');
        if (!wrapper) return;
        const list = document.getElementById('user-loan-notification-list');
        const count = wrapper.querySelector('.admin-notification-count');
        const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
        const isDone = localStorage.getItem('sipibsLoanNotificationDone') === '1';

        if (isDone || !decision) {
            list.innerHTML = '<div class="notification-item empty-notification"><span class="notif-icon blue"><i class="bi bi-check2-circle"></i></span><div><strong>Tidak ada notifikasi baru</strong><small>Semua notifikasi peminjaman sudah dikonfirmasi.</small></div></div>';
            count.textContent = '0';
            count.classList.add('hidden');
            return;
        }

        if (decision.status === 'pending') {
            list.innerHTML = '<a href="#" class="notification-item unread" onclick="openModal(\'pending\'); return false;"><span class="notif-icon blue"><i class="bi bi-clock"></i></span><div><strong>Peminjaman menunggu persetujuan</strong><small>Pengajuan ' + loanRequestData.barang + ' - klik untuk melihat detail.</small></div></a>';
            count.textContent = '1';
            count.classList.remove('hidden');
            return;
        }

        const approved = decision.status === 'approved';
        list.innerHTML = '<a href="#" class="notification-item unread" onclick="openModal(\'' + (approved ? 'approved' : 'rejected') + '\'); return false;"><span class="notif-icon ' + (approved ? 'blue' : 'red') + '"><i class="bi ' + (approved ? 'bi-check-circle' : 'bi-x-circle') + '"></i></span><div><strong>Peminjaman ' + (approved ? 'disetujui' : 'ditolak') + '</strong><small>' + decision.request.barang + ' - klik untuk melihat detail.</small></div></a>';
        count.textContent = '1';
        count.classList.remove('hidden');
    }

    document.querySelectorAll('[data-user-loan-notification]').forEach(function (notif) {
        const button = notif.querySelector('.admin-notification-btn');
        const markRead = notif.querySelector('.mark-read-btn');
        const count = notif.querySelector('.admin-notification-count');
        button.addEventListener('click', function (event) {
            event.stopPropagation();
            notif.classList.toggle('open');
        });
        markRead.addEventListener('click', function (event) {
            event.preventDefault();
            clearUserLoanNotification();
        });
    });
    renderUserLoanNotification();

    function renderPeminjamanHistory() {
        const body = document.getElementById('peminjaman-history-body');
        const emptyMsg = document.getElementById('peminjaman-history-empty');
        const footer = document.getElementById('peminjaman-history-footer');
        if (!body) return;
        const history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
        const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
        const request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
        const fallback = decision && decision.request ? decision.request : request;
        let list = history.slice();
        if (fallback && fallback.id && !list.some(loan => loan.id === fallback.id)) list.unshift(fallback);
        if (!list.length) list = fallback ? [fallback] : [];

        body.innerHTML = '';
        if (list.length === 0) {
            body.parentElement.style.display = 'none';
            if (emptyMsg) emptyMsg.style.display = 'block';
            if (footer) footer.style.display = 'none';
            return;
        }
        body.parentElement.style.display = '';
        if (emptyMsg) emptyMsg.style.display = 'none';

        const showList = list.slice(0, 3);
        showList.forEach(loan => {
            const statusMap = {
                approved: ['Dipinjam', 'blue'],
                rejected: ['Ditolak', 'red'],
                pending: ['Menunggu', 'yellow'],
                returned: ['Dikembalikan', 'green']
            };
            const s = statusMap[loan.status || 'pending'] || statusMap.pending;
            const pinjam = formatPeminjamanDate(loan.tanggalPinjam);
            const kembali = formatPeminjamanDate(loan.tanggalKembali);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><div class="item-meta"><span class="icon-box"><i class="bi ${getItemIcon(loan.barang)}"></i></span><div><strong>${loan.barang || '-'}</strong><small>${loan.kategori || '-'}</small></div></div></td>
                <td>${pinjam}</td>
                <td>${kembali}</td>
                <td><span class="badge-status ${s[1]}">● ${s[0]}</span></td>
                <td><a href="#" class="btn-action-detail"><i class="bi bi-eye"></i> Detail</a></td>
            `;
            tr.querySelector('.btn-action-detail').addEventListener('click', function(e) {
                e.preventDefault();
            });
            body.appendChild(tr);
        });

        if (footer) footer.style.display = list.length > 3 ? 'block' : 'none';
    }

    function formatPeminjamanDate(dateValue) {
        if (!dateValue) return '-';
        if (dateValue.includes('/')) {
            const parts = dateValue.split('/');
            dateValue = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        const date = new Date(dateValue + 'T00:00:00');
        if (Number.isNaN(date.getTime())) return dateValue;
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    renderPeminjamanHistory();
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

    function applyLoanDecisionStep() {
        const decision = (function () {
            try { return JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null'); }
            catch (e) { return null; }
        })();
        const status = decision && decision.status ? decision.status : 'pending';

        const step3Item = document.getElementById('step3-item');
        const step3Icon = document.getElementById('step3-icon');
        const step3Label = document.getElementById('step3-label');

        if (status === 'approved') {
            if (step3Item) step3Item.className = 'step-item done';
            if (step3Icon) step3Icon.innerHTML = '<i class="bi bi-check-lg"></i>';
            if (step3Label) step3Label.textContent = 'Selesai';

            const statusClock = document.getElementById('status-clock');
            if (statusClock) statusClock.innerHTML = '<i class="bi bi-check-lg"></i>';
            const statusTitle = document.getElementById('status-title');
            if (statusTitle) { statusTitle.textContent = 'Disetujui'; statusTitle.className = 'text-green'; }
            const statusDesc = document.getElementById('status-desc');
            if (statusDesc) statusDesc.textContent = 'Peminjaman Anda telah disetujui oleh admin.';

            setFlowDone('flow-step-2', 'flow-dot-2', 'flow-line-1', 'Menunggu Persetujuan Admin', 'Disetujui oleh admin SIPIBS');
            setFlowDone('flow-step-3', 'flow-dot-3', 'flow-line-2', 'Persetujuan', 'Peminjaman disetujui dan siap diproses');

        } else if (status === 'rejected') {
            const statusTitle = document.getElementById('status-title');
            if (statusTitle) { statusTitle.textContent = 'Ditolak'; statusTitle.className = 'text-red'; }
            const statusDesc = document.getElementById('status-desc');
            if (statusDesc) statusDesc.textContent = 'Pengajuan peminjaman Anda ditolak oleh admin.';
        }
    }

    function setFlowDone(stepId, dotId, lineId, strongText, smallText) {
        const step = document.getElementById(stepId);
        const dot = document.getElementById(dotId);
        const line = document.getElementById(lineId);
        if (step) step.className = 'flow-step done';
        if (dot) dot.innerHTML = '<i class="bi bi-check"></i>';
        if (line) line.className = 'flow-line active';
        if (step) {
            const strong = step.querySelector('strong');
            if (strong && strongText) strong.textContent = strongText;
            const small = step.querySelector('small');
            if (small && smallText) small.textContent = smallText;
        }
    }

    try { applyLoanDecisionStep(); } catch (e) {}
    window.addEventListener('storage', function (event) {
        if (event.key === 'sipibsLoanDecision' || event.key === 'sipibsLoanHistory' || event.key === 'sipibsLoanRequest') {
            try { applyLoanDecisionStep(); } catch (e) {}
        }
    });

</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
<script>
(function () {
    try {
        var profile = JSON.parse(localStorage.getItem('sipibs_user_profile') || 'null');
        var accName = (profile && (profile.name || profile.username || '').trim()) || '';
        var accNis = (profile && (profile.nis || '').trim()) || '';
        var nameInput = document.getElementById('input-name-pminjam');
        var nisInput = document.getElementById('input-nis-pminjam');
        if (nameInput && accName) nameInput.value = accName;
        if (nisInput && accNis) nisInput.value = accNis;
    } catch (e) {}
})();
</script>
</body>
</html>














