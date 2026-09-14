<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Denda - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=31">
    <style>
        .fine-list-card{background:#fff;border:1px solid #dfe8f4;border-radius:16px;box-shadow:0 10px 26px rgba(15,23,42,.05);margin-bottom:24px;overflow:hidden}
        .fine-list-head{padding:18px 24px;border-bottom:1px solid #e5edf8;display:flex;align-items:center;gap:12px}
        .fine-list-head .fine-list-ico{width:40px;height:40px;border-radius:11px;display:grid;place-items:center;background:linear-gradient(135deg,#ffd264,#f59e0b);color:#fff;font-size:19px}
        .fine-list-head strong{font-size:16px;font-weight:800;color:#071735}
        .fine-list-head small{display:block;color:#64748b;font-size:12px;margin-top:2px}
        .fine-table{width:100%;border-collapse:collapse}
        .fine-table th{background:#f7fafd;text-align:left;font-size:11px;font-weight:800;letter-spacing:.5px;color:#64748b;text-transform:uppercase;padding:12px 16px;border-bottom:1px solid #e5edf8}
        .fine-table td{padding:13px 16px;border-bottom:1px solid #edf2f9;font-size:13px;color:#1e293b;vertical-align:middle}
        .fine-table tr:last-child td{border-bottom:none}
        .fine-card-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:999px;font-size:12px;font-weight:800}
        .fine-card-badge.unpaid{background:#ffe7e0;color:#d33115}
        .fine-card-badge.paid{background:#e1f7ea;color:#12a150}
        .fine-bayar-btn{border:none;background:linear-gradient(135deg,#2d7df0,#1457d9);color:#fff;padding:8px 16px;border-radius:9px;font-weight:800;font-size:12px;cursor:pointer}
        .fine-bayar-btn:disabled{opacity:.45;cursor:not-allowed}
        .fine-list-empty{padding:44px 20px;text-align:center;color:#64748b}
        .fine-list-empty i{font-size:32px;color:#22c55e;display:block;margin-bottom:8px}
        .fine-list-empty strong{font-size:15px;color:#071735}
        .fine-list-empty span{font-size:13px}
        .fine-modal-overlay{position:fixed;inset:0;background:rgba(9,26,54,.55);z-index:1000;display:flex;align-items:flex-start;justify-content:center;padding:36px 18px;overflow-y:auto}
        .fine-modal-panel{background:#f6f9ff;border-radius:16px;border:1px solid #dfe8f4;box-shadow:0 26px 70px rgba(5,20,50,.35);width:100%;max-width:920px;max-height:92vh;overflow-y:auto}
        .fine-modal-head{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding:22px 26px 14px;color:#112b56}
        .fine-modal-head h2{margin:0;font-size:1.4rem;font-weight:900}
        .fine-modal-head p{margin:6px 0 0;color:#607089;font-weight:500;font-size:.88rem}
        .fine-modal-close{flex:none;border:none;width:36px;height:36px;border-radius:10px;background:#eef2fa;color:#44587d;font-size:20px;cursor:pointer;line-height:1}
        .fine-modal-close:hover{background:#e2e9f5}
        .fine-modal-body{padding:4px 26px 26px}
        @media (max-width:600px){.fine-modal-overlay{padding:14px}.fine-modal-body{padding:0 14px 14px}}
    </style>
</head>
<body>
@include('user.partials.local-storage-cleanup')
@php
    $userName = Auth::check() ? Auth::user()->name : 'Siswa';
    $userClass = Auth::check() ? (Auth::user()->class_name ?: '') : '';
    $userIdentity = Auth::check() ? (Auth::user()->identity_number ?: '') : '';
    $userRole = 'SISWA';
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
            <a class="nav-item active" href="{{ url('/denda-user') }}"><i class="bi bi-cash-coin"></i> Denda</a>
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-area">
        <header class="topbar fine-topbar">
            <button type="button" class="sidebar-toggle"><i class="bi bi-list"></i></button>
            <div class="top-actions">
                @include('user.partials.notification-bell')
                <div class="top-user">
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>{{ Auth::check() && Auth::user()->role === 'guru' ? 'GURU' : 'SISWA' }}</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar">
                </div>
            </div>
        </header>

        <section class="content fine-content">
            <div class="fine-page-head">
                <h1>Form Denda</h1>
                <p>Pilih denda dan selesaikan pembayaran sesuai ketentuan.</p>
            </div>

            <div class="fine-list-card" id="denda-list-card">
                <div class="fine-list-head">
                    <div class="fine-list-ico"><i class="bi bi-cash-coin"></i></div>
                    <div><strong>Denda Anda</strong><small id="denda-list-sub">Memuat data...</small></div>
                </div>
                <table class="fine-table">
                    <thead><tr><th>No</th><th>Barang</th><th>Jenis Denda</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody id="denda-list-body"></tbody>
                </table>
                <div class="fine-list-empty hidden" id="denda-list-empty">
                    <i class="bi bi-patch-check"></i>
                    <strong>Tidak ada denda</strong>
                    <span>Anda tidak memiliki denda yang perlu dibayar. Terus jaga jadwal pengembalian ya.</span>
                </div>
            </div>

            <div class="fine-modal-overlay hidden" id="fine-modal">
                <div class="fine-modal-panel" role="dialog" aria-modal="true" aria-labelledby="fine-modal-title">
                    <div class="fine-modal-head">
                        <div><h2 id="fine-modal-title">Form Denda</h2><p id="fine-modal-sub">Rincian denda dan pembayaran</p></div>
                        <button type="button" class="fine-modal-close" id="fine-modal-close" aria-label="Tutup">&times;</button>
                    </div>
                    <div class="fine-modal-body">
                        <div class="fine-layout" id="fine-layout">
                            <form class="fine-form-card" action="#" method="POST" enctype="multipart/form-data" id="fine-form-card">
                                @csrf
                                <div class="fine-form-grid">
                                    <label>Nama Peminjam<input id="fine-name" type="text" readonly value="{{ $userName }}"></label>
                                    <label>NIS / NIP<input id="fine-nis" type="text" readonly value="{{ $userIdentity }}"></label>
                                    <label>Kelas / Bagian<input id="fine-class" type="text" readonly value="{{ $userClass }}"></label>
                                    <label class="span-2">Nama Barang<input id="fine-item" type="text" readonly></label>
                                    <label>Kode Barang<input id="fine-code" type="text" readonly></label>
                                    <label>Tanggal Peminjaman<input id="fine-loan-date" type="date" readonly></label>
                                    <label>Tanggal Seharusnya Dikembalikan<input id="fine-due-date" type="date" readonly></label>
                                    <label>Tanggal Pengembalian<input id="fine-return-date" type="date" readonly></label>
                                    <label>Jenis Denda<select id="fine-type" disabled><option>Keterlambatan Pengembalian</option><option>Kerusakan Barang</option><option>Kehilangan Barang</option></select></label>
                                    <label>Jumlah Denda (Rp)<input id="fine-amount" type="text" readonly></label>
                                    <label class="span-3">Keterangan<textarea id="fine-note" readonly></textarea></label>
                                </div>

                                <div class="fine-section-title">Metode Pembayaran</div>
                                <div class="fine-payment-options">
                                    <label class="fine-payment-option" data-payment-option="qris"><input type="radio" name="metode" value="qris"><i class="bi bi-qr-code"></i><span><strong>QRIS</strong><small>Scan QRIS untuk pembayaran cepat dan mudah.</small></span></label>
                                    <label class="fine-payment-option" data-payment-option="cash"><input type="radio" name="metode" value="cash"><i class="bi bi-person-bounding-box"></i><span><strong>Bayar di Tempat</strong><small>Bayar langsung ke petugas laboratorium.</small></span></label>
                                </div>

                                <div class="fine-section-title hidden" id="fine-upload-title">Bukti Pembayaran (Wajib jika memilih QRIS)</div>
                                <div class="fine-upload-box hidden" id="fine-upload-box" tabindex="0" role="button" aria-label="Upload bukti pembayaran">
                                    <input type="file" id="fine-upload-input" accept="image/png,image/jpeg,application/pdf" hidden>
                                    <div id="fine-upload-empty">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <strong>Klik untuk upload bukti pembayaran</strong>
                                        <span>atau drag & drop file di sini</span>
                                        <small>Format: JPG, PNG, PDF (Max. 5MB)</small>
                                    </div>
                                    <div id="fine-upload-preview" class="hidden">
                                        <div id="fine-preview-list"></div>
                                        <button type="button" id="fine-upload-add" class="fine-upload-add-btn"><i class="bi bi-plus-circle"></i> Tambah File</button>
                                        <small>Maksimal 3 file.</small>
                                    </div>
                                </div>
                                <div class="fine-info" id="fine-payment-info"><i class="bi bi-info-circle"></i> Pilih metode pembayaran untuk melanjutkan.</div>
                                <div class="fine-actions"><button type="button" id="fine-modal-cancel">Batal</button><button type="button" id="fine-save-btn" disabled><i class="bi bi-check-circle"></i> Simpan Denda</button></div>
                            </form>

                            <aside class="fine-qris-card hidden">
                                <div class="fine-qris-head"><strong>Pembayaran QRIS</strong><span>Menunggu Pembayaran</span></div>
                                <div class="fine-qris-amount"><small>Scan QRIS di bawah ini untuk membayar denda sebesar:</small><strong id="qris-amount">Rp 0</strong></div>
                                <div class="fine-qris-box">
                                    <strong>Sipibs</strong><small>NMID: ID1026581585985<br>A01</small>
                                    <div class="fine-qris-image" role="img" aria-label="QRIS Sipibs"></div>

                                </div>
                                <div class="fine-howto"><strong>Cara Pembayaran:</strong><p><b>1</b> Buka aplikasi e-wallet / mobile banking</p><p><b>2</b> Pilih menu Scan QRIS</p><p><b>3</b> Scan QR code di atas</p><p><b>4</b> Pastikan nominal sesuai, lalu konfirmasi pembayaran</p></div>
                            </aside>

                            <aside class="fine-bill-card hidden" id="cash-bill-card">
                                <div class="fine-qris-head"><strong>Pembayaran di Tempat</strong><span>Menunggu Pembayaran</span></div>
                                <div class="fine-cash-alert"><i class="bi bi-shop"></i><div><strong>Bayar langsung ke petugas</strong><small>Tunjukkan detail denda ini saat melakukan pembayaran.</small></div></div>
                                <div class="fine-bill-panel" id="fine-bill-print">
                                    <h3>RINCIAN DENDA</h3>
                                    <dl>
                                        <div><dt>Nama Peminjam</dt><dd id="bill-name">-</dd></div>
                                        <div><dt>NIS / NIP</dt><dd id="bill-nis">-</dd></div>
                                        <div><dt>Nama Barang</dt><dd id="bill-item">-</dd></div>
                                        <div><dt>Kode Barang</dt><dd id="bill-code">-</dd></div>
                                        <div><dt>Jenis Denda</dt><dd id="bill-type">-</dd></div>
                                        <div><dt>Jumlah Denda</dt><dd id="bill-amount">Rp 0</dd></div>
                                        <div><dt>Status Pembayaran</dt><dd><span class="fine-status-pill">Belum Dibayar</span></dd></div>
                                        <div><dt>Metode Pembayaran</dt><dd>Bayar di Tempat</dd></div>
                                        <div><dt>Dibuat Pada</dt><dd id="bill-created">-</dd></div>
                                    </dl>
                                </div>
                                <div class="fine-instruction-panel"><h3>INSTRUKSI</h3><p><b>1</b> Datang ke laboratorium sesuai jam operasional.</p><p><b>2</b> Sampaikan kepada petugas bahwa Anda akan membayar denda.</p><p><b>3</b> Lakukan pembayaran sebesar <strong id="bill-instruction-amount">Rp 0</strong>.</p><p><b>4</b> Minta konfirmasi dan bukti pembayaran dari petugas.</p></div>
                                <div class="fine-hours-panel"><strong><i class="bi bi-clock"></i> Jam Operasional Laboratorium</strong><div><span>Senin - Jumat</span><b>08.00 - 16.00</b></div><div><span>Sabtu</span><b>08.00 - 12.00</b></div></div>
                                <button class="fine-check-btn" type="button" onclick="downloadFineBillPdf()"><i class="bi bi-download"></i> Unduh Bill PDF</button>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    window.__apiBase = @json(rtrim(url('/api'), '/'));
    var currentFine = null;
    var paymentMethod = null;

    const invToggle = document.getElementById('inventaris-toggle');
    const invSub = document.getElementById('inventaris-sub');
    if (invToggle && invSub) {
        if (localStorage.getItem('invOpen') === '1') {
            invSub.classList.remove('collapsed');
            invToggle.classList.add('open');
        }
        invToggle.addEventListener('click', function (event) {
            event.preventDefault();
            invSub.classList.toggle('collapsed');
            invToggle.classList.toggle('open', !invSub.classList.contains('collapsed'));
            localStorage.setItem('invOpen', invSub.classList.contains('collapsed') ? '0' : '1');
        });
        invSub.querySelectorAll('.nav-sub-item').forEach(function(link) {
            link.addEventListener('click', function() {
                localStorage.setItem('invOpen', '1');
            });
        });
    }

    const qrisCard = document.querySelector('.fine-qris-card');
    const cashBillCard = document.getElementById('cash-bill-card');
    const uploadTitle = document.getElementById('fine-upload-title');
    const uploadBox = document.getElementById('fine-upload-box');
    const paymentInfo = document.getElementById('fine-payment-info');
    const paymentOptions = document.querySelectorAll('[data-payment-option]');
    const fineLayout = document.getElementById('fine-layout');
    const fineModal = document.getElementById('fine-modal');
    const fineModalClose = document.getElementById('fine-modal-close');
    const fineModalCancel = document.getElementById('fine-modal-cancel');
    const fineSaveBtn = document.getElementById('fine-save-btn');
    const fineUploadInput = document.getElementById('fine-upload-input');
    const fineUploadEmpty = document.getElementById('fine-upload-empty');
    const fineUploadPreview = document.getElementById('fine-upload-preview');
    const finePreviewList = document.getElementById('fine-preview-list');
    const fineUploadAdd = document.getElementById('fine-upload-add');
    const selectedFineFiles = [];
    const maxFineFiles = 3;

    function escapeHtml(s){ return String(s == null ? '' : s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function formatRupiah(value) {
        const digits = String(value || '').replace(/\D/g, '') || '0';
        return 'Rp ' + Number(digits).toLocaleString('id-ID');
    }
    function formatDate(value) {
        if (!value || value === '-') return '-';
        if (String(value).includes('/')) return value;
        return new Date(value + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }
    function parseInputDate(dateStr) {
        if (!dateStr || dateStr === '-') return '';
        if (dateStr.includes('/')) {
            const p = dateStr.split('/');
            if (p.length === 3) return p[2] + '-' + p[1].padStart(2, '0') + '-' + p[0].padStart(2, '0');
        }
        if (dateStr.includes(' ')) {
            const months = { 'januari': '01', 'februari': '02', 'maret': '03', 'april': '04', 'mei': '05', 'juni': '06', 'juli': '07', 'agustus': '08', 'september': '09', 'oktober': '10', 'november': '11', 'desember': '12', 'jan': '01', 'feb': '02', 'mar': '03', 'apr': '04', 'may': '05', 'jun': '06', 'jul': '07', 'aug': '08', 'sep': '09', 'oct': '10', 'nov': '11', 'dec': '12' };
            const parts = dateStr.toLowerCase().split(' ');
            if (parts.length >= 3) {
                return parts[2] + '-' + (months[parts[1]] || '08') + '-' + parts[0].padStart(2, '0');
            }
        }
        return dateStr;
    }

    function syncBillData() {
        const amount = formatRupiah(document.getElementById('fine-amount').value);
        document.getElementById('bill-name').textContent = document.getElementById('fine-name').value || '-';
        document.getElementById('bill-nis').textContent = document.getElementById('fine-nis').value || '-';
        document.getElementById('bill-item').textContent = document.getElementById('fine-item').value || '-';
        document.getElementById('bill-code').textContent = document.getElementById('fine-code').value || '-';
        document.getElementById('bill-type').textContent = document.getElementById('fine-type').value || '-';
        document.getElementById('bill-amount').textContent = amount;
        document.getElementById('bill-instruction-amount').textContent = amount;
        const qrisAmount = document.getElementById('qris-amount');
        if (qrisAmount) qrisAmount.textContent = amount;
    }

    function setPaymentMethod(method) {
        paymentMethod = method;
        const layout = document.querySelector('.fine-layout');
        paymentOptions.forEach(option => option.classList.toggle('selected', option.dataset.paymentOption === method));
        qrisCard.classList.toggle('hidden', method !== 'qris');
        cashBillCard.classList.toggle('hidden', method !== 'cash');
        uploadTitle.classList.toggle('hidden', method !== 'qris');
        uploadBox.classList.toggle('hidden', method !== 'qris');
        layout.classList.toggle('has-side-card', method === 'qris' || method === 'cash');
        if (method === 'cash') {
            paymentInfo.innerHTML = '<i class="bi bi-info-circle"></i> Silakan datang ke laboratorium dan tunjukkan bill denda kepada petugas.';
        } else if (method === 'qris') {
            paymentInfo.innerHTML = '<i class="bi bi-info-circle"></i> Pastikan denda telah dibayarkan sesuai ketentuan yang berlaku.';
        } else {
            paymentInfo.innerHTML = '<i class="bi bi-info-circle"></i> Pilih metode pembayaran untuk melanjutkan.';
        }
        fineSaveBtn.disabled = !currentFine || !method;
    }

    function fillField(id, value) {
        const el = document.getElementById(id);
        if (el) el.value = value || '';
    }

    function resetForm() {
        currentFine = null;
        fillField('fine-name', '{{ $userName }}');
        fillField('fine-nis', '{{ $userIdentity }}');
        fillField('fine-class', '{{ $userClass }}');
        fillField('fine-item', '');
        fillField('fine-code', '');
        fillField('fine-loan-date', '');
        fillField('fine-due-date', '');
        fillField('fine-return-date', '');
        fillField('fine-amount', '');
        fillField('fine-note', '');
        document.getElementById('bill-created').textContent = '-';
        syncBillData();
        selectedFineFiles.length = 0;
        renderFinePreviews();
        setPaymentMethod(null);
    }

    function selectFine(id) {
        const f = allFines.find(x => x.id === id);
        if (!f || String(f.status) === 'lunas') return;
        currentFine = f;
        fillField('fine-name', f.borrower);
        fillField('fine-nis', f.identity_number);
        fillField('fine-class', '{{ $userClass }}');
        fillField('fine-item', f.itemName);
        fillField('fine-code', f.serial);
        fillField('fine-loan-date', parseInputDate(f.loanDate));
        fillField('fine-due-date', parseInputDate(f.dueDate));
        fillField('fine-return-date', parseInputDate(f.returnDate));
        fillField('fine-amount', String(f.fineAmount || '').replace(/\D/g, ''));
        fillField('fine-note', f.notes && f.notes !== '-' ? f.notes : '');
        const select = document.getElementById('fine-type');
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].text === f.fineType) { select.selectedIndex = i; break; }
        }
        const created = document.getElementById('bill-created');
        if (created) created.textContent = f.createdAt ? formatDate(f.createdAt) : '-';
        syncBillData();
        const listBtns = document.querySelectorAll('.fine-bayar-btn');
        listBtns.forEach(btn => btn.classList.toggle('active-context', String(btn.dataset.id) === String(f.id)));
        setPaymentMethod(null);
        fineSaveBtn.disabled = true;
    }

    function openFineModal(id) {
        selectFine(id);
        if (!currentFine) return;
        document.getElementById('fine-modal-sub').textContent = 'Kode DN-' + currentFine.id + ' · Klik Bayar untuk menyelesaikan pembayaran.';
        fineModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        const panel = fineModal.querySelector('.fine-modal-panel');
        if (panel) panel.scrollTop = 0;
    }

    function closeFineModal() {
        fineModal.classList.add('hidden');
        document.body.style.overflow = '';
        currentFine = null;
        setPaymentMethod(null);
        fineSaveBtn.disabled = true;
    }

    function renderFinePreviews() {
        finePreviewList.innerHTML = '';
        selectedFineFiles.forEach(function (file, index) {
            var item = document.createElement('div');
            item.className = 'fine-preview-item';
            if (file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    item.innerHTML = '<img src="' + e.target.result + '" alt="Preview"><button type="button" class="fine-preview-remove" data-index="' + index + '">&times;</button>';
                };
                reader.readAsDataURL(file);
            } else {
                item.innerHTML = '<div class="fine-preview-pdf"><i class="bi bi-file-earmark-pdf"></i><span>' + escapeHtml(file.name) + '</span></div><button type="button" class="fine-preview-remove" data-index="' + index + '">&times;</button>';
            }
            finePreviewList.appendChild(item);
        });
        fineUploadEmpty.classList.toggle('hidden', selectedFineFiles.length > 0);
        fineUploadPreview.classList.toggle('hidden', selectedFineFiles.length === 0);
        uploadBox.classList.toggle('has-files', selectedFineFiles.length > 0);
    }

    function addFineFiles(files) {
        var remaining = maxFineFiles - selectedFineFiles.length;
        if (remaining <= 0) { alert('Maksimal 3 file.'); return; }
        Array.from(files).slice(0, remaining).forEach(function (file) {
            if (!['image/jpeg', 'image/png', 'application/pdf'].includes(file.type)) {
                alert('Format harus JPG, PNG, atau PDF.'); return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran maksimal 5MB.'); return;
            }
            selectedFineFiles.push(file);
        });
        renderFinePreviews();
    }

    function loadFines() {
        fetch((window.__apiBase || '/api') + '/denda/saya', {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) { if (!res.ok) throw new Error('fail'); return res.json(); })
        .then(function (data) {
            allFines = (data && Array.isArray(data.fines)) ? data.fines : [];
            renderFinesList();
        })
        .catch(function () {
            document.getElementById('denda-list-sub').textContent = 'Gagal memuat data.';
        });
    }

    function renderFinesList() {
        const body = document.getElementById('denda-list-body');
        const sub = document.getElementById('denda-list-sub');
        const empty = document.getElementById('denda-list-empty');
        if (!allFines.length) {
            body.innerHTML = '';
            empty.classList.remove('hidden');
            sub.textContent = 'Tidak ada data denda';
            return;
        }
        const unpaid = allFines.filter(f => String(f.status) !== 'lunas');
        const paid = allFines.length - unpaid.length;
        sub.textContent = unpaid.length + ' belum lunas, ' + paid + ' sudah lunas';
        empty.classList.add('hidden');
        body.innerHTML = allFines.map(function (f, i) {
            const isPaid = String(f.status) === 'lunas';
            return '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td><strong>' + escapeHtml(f.itemName) + '</strong></td>' +
                '<td>' + escapeHtml(f.fineType) + '</td>' +
                '<td>' + formatRupiah(f.fineAmount) + '</td>' +
                '<td><span class="fine-card-badge ' + (isPaid ? 'paid' : 'unpaid') + '">' + (isPaid ? '<i class="bi bi-check-circle"></i> Lunas' : '<i class="bi bi-clock"></i> Belum Lunas') + '</span></td>' +
                '<td>' + (isPaid ? '<span style="color:#64748b;font-size:12px;">Terbayar</span>' : '<button type="button" class="fine-bayar-btn" data-id="' + f.id + '">Bayar</button>') + '</td>' +
                '</tr>';
        }).join('');
    }

    document.getElementById('denda-list-body').addEventListener('click', function (e) {
        const btn = e.target.closest('.fine-bayar-btn');
        if (btn) openFineModal(parseInt(btn.dataset.id));
    });

    function readFileAsDataURL(file) {
        return new Promise(function (resolve, reject) {
            const fr = new FileReader();
            fr.onload = function () { resolve(fr.result); };
            fr.onerror = reject;
            fr.readAsDataURL(file);
        });
    }

    function submitFinePayment() {
        if (!currentFine) { alert('Pilih denda terlebih dahulu.'); return; }
        const method = paymentMethod;
        if (!method) { alert('Pilih metode pembayaran terlebih dahulu.'); return; }
        fineSaveBtn.disabled = true;
        fineSaveBtn.textContent = 'Menyimpan...';
        (async function () {
            try {
                let proof = null;
                if (method === 'qris') {
                    if (!selectedFineFiles.length) {
                        alert('Upload bukti pembayaran QRIS terlebih dahulu.');
                        fineSaveBtn.textContent = 'Simpan Denda';
                        fineSaveBtn.disabled = false;
                        return;
                    }
                    proof = await readFileAsDataURL(selectedFineFiles[0]);
                } else if (selectedFineFiles.length) {
                    proof = await readFileAsDataURL(selectedFineFiles[0]);
                }
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                const res = await fetch((window.__apiBase || '/api') + '/denda/' + currentFine.id + '/bayar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ method: method, proof: proof })
                });
                const data = await res.json();
                if (!res.ok) {
                    alert(data.message || 'Gagal menyimpan pembayaran.');
                    fineSaveBtn.textContent = 'Simpan Denda';
                    fineSaveBtn.disabled = false;
                    return;
                }
                alert('Pembayaran denda berhasil dicatat.');
                selectedFineFiles.length = 0;
                renderFinePreviews();
                closeFineModal();
                loadFines();
            } catch (e) {
                alert('Gagal terhubung ke server. Coba lagi.');
                fineSaveBtn.textContent = 'Simpan Denda';
                fineSaveBtn.disabled = false;
            }
        })();
    }

    paymentOptions.forEach(option => {
        option.addEventListener('click', () => {
            option.querySelector('input').checked = true;
            setPaymentMethod(option.dataset.paymentOption);
        });
    });

    document.querySelectorAll('#fine-name, #fine-nis, #fine-item, #fine-code, #fine-type, #fine-amount').forEach(input => {
        input.addEventListener('input', syncBillData);
        input.addEventListener('change', syncBillData);
    });

    uploadBox.addEventListener('click', function (e) {
        if (e.target.closest('.fine-preview-remove')) {
            e.stopPropagation();
            var idx = parseInt(e.target.closest('.fine-preview-remove').dataset.index);
            selectedFineFiles.splice(idx, 1);
            renderFinePreviews();
            return;
        }
        if (e.target.closest('.fine-preview-item')) {
            e.stopPropagation();
            var previewItem = e.target.closest('.fine-preview-item');
            var img = previewItem.querySelector('img');
            if (img) {
                var overlay = document.createElement('div');
                overlay.className = 'fine-lightbox-overlay';
                overlay.innerHTML = '<div class="fine-lightbox-content"><img src="' + img.src + '" alt="Preview"><button type="button" class="fine-lightbox-close">&times;</button></div>';
                overlay.addEventListener('click', function (ev) {
                    if (ev.target === overlay || ev.target.classList.contains('fine-lightbox-close')) {
                        overlay.remove();
                    }
                });
                document.body.appendChild(overlay);
            }
            return;
        }
        if (e.target.closest('#fine-upload-add')) {
            e.preventDefault();
            if (selectedFineFiles.length >= maxFineFiles) { alert('Maksimal 3 file.'); return; }
            fineUploadInput.click();
            return;
        }
        if (e.target.closest('#fine-upload-empty') || e.target === uploadBox) {
            fineUploadInput.click();
        }
    });

    fineUploadInput.addEventListener('change', function () {
        addFineFiles(this.files);
        this.value = '';
    });

    uploadBox.addEventListener('dragover', function (e) {
        e.preventDefault();
        uploadBox.classList.add('dragging');
    });
    uploadBox.addEventListener('dragleave', function () {
        uploadBox.classList.remove('dragging');
    });
    uploadBox.addEventListener('drop', function (e) {
        e.preventDefault();
        uploadBox.classList.remove('dragging');
        addFineFiles(e.dataTransfer.files);
    });

    function escapePdfText(text) {
        return String(text).replace(/\\/g, '\\\\').replace(/\(/g, '\\(').replace(/\)/g, '\\)');
    }

    function downloadFineBillPdf() {
        const bill = document.getElementById('fine-bill-print');
        const billClone = bill.cloneNode(true);

        const css = `
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body { font-family: Arial, Helvetica, sans-serif; color: #142d57; padding: 28px; }
            .bill-title { text-align: center; margin-bottom: 22px; }
            .bill-title h1 { font-size: 18px; font-weight: 900; color: #142d57; letter-spacing: 1px; }
            .bill-title span { font-size: 11px; color: #5d6f8a; }
            .fine-bill-panel { border: 1px solid #dce6f3; border-radius: 8px; padding: 18px; }
            .fine-bill-panel h3 { margin: 0 0 6px; color: #142d57; font-size: 13px; font-weight: 900; text-align: center; letter-spacing: 1px; padding-bottom: 10px; border-bottom: 2px dashed #c9d7e5; }
            .fine-bill-panel dl { margin: 0; }
            .fine-bill-panel dl div { display: flex; justify-content: space-between; gap: 16px; margin-top: 14px; color: #203a63; font-size: 12px; }
            .fine-bill-panel dt { color: #5d6f8a; font-weight: 600; }
            .fine-bill-panel dd { margin: 0; text-align: right; font-weight: 700; }
            .fine-status-pill { display: inline-block; border-radius: 999px; background: #fff1d8; color: #b47008; padding: 4px 10px; font-size: 11px; font-weight: 800; }
            .fine-instruction-panel { border: 1px solid #dce6f3; border-radius: 8px; padding: 18px; margin-top: 18px; }
            .fine-instruction-panel h3 { margin: 0 0 10px; color: #142d57; font-size: 13px; font-weight: 900; }
            .fine-instruction-panel p { display: flex; gap: 10px; margin: 12px 0 0; color: #536984; line-height: 1.45; font-size: 12px; }
            .fine-instruction-panel b { flex: 0 0 20px; width: 20px; height: 20px; border-radius: 999px; display: grid; place-items: center; background: #2378ff; color: #fff; font-size: 10px; }
            .fine-instruction-panel strong { color: #17325d; }
            .fine-footer { margin-top: 26px; text-align: center; font-size: 10px; color: #9aa8bd; }
        `;

        const printWindow = window.open('', '_blank', 'width=650,height=900');
        printWindow.document.write('<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Bill Denda - SIPIBS</title><style>' + css + '</style></head><body>');
        printWindow.document.write('<div class="bill-title"><h1>BILL PEMBAYARAN DENDA</h1><span>SIPIBS - Sistem Inventaris &amp; Peminjaman Barang Sekolah</span></div>');
        printWindow.document.write(billClone.outerHTML);
        printWindow.document.write('<div class="fine-footer">Bill ini otomatis dibuat oleh sistem SIPIBS. Tunjukkan kepada petugas lab saat pembayaran.</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(function () { printWindow.print(); }, 300);
    }

    document.getElementById('fine-save-btn').addEventListener('click', submitFinePayment);

    fineModalClose.addEventListener('click', closeFineModal);
    fineModalCancel.addEventListener('click', closeFineModal);
    fineModal.addEventListener('click', function (e) {
        if (e.target === fineModal) closeFineModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !fineModal.classList.contains('hidden')) closeFineModal();
    });

    var allFines = [];
    loadFines();
</script>
@include('user.partials.profile-sync')
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
</body>
</html>

