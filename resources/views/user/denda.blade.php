<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Denda - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=31">
</head>
<body>
@php
    $userName = Auth::check() ? Auth::user()->name : 'Siswa';
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
                    <div><strong id="top-user-name">{{ $userName }}</strong><span>SISWA</span></div>
                    <img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar">
                </div>
            </div>
        </header>

        <section class="content fine-content">
            <div class="fine-page-head">
                <h1>Form Denda</h1>
                <p>Isi informasi denda sesuai dengan pelanggaran.</p>
            </div>

            <div class="fine-layout">
                <form class="fine-form-card" action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="fine-form-grid">
                        <label>Nama Peminjam<input id="fine-name" type="text" value="Muhammad Faisal Ilham"></label>
                        <label>NIS / NIP<input id="fine-nis" type="text" value="12345678"></label>
                        <label>Kelas / Bagian<input id="fine-class" type="text" value="XI RPL 1"></label>
                        <label class="span-2">Nama Barang<input id="fine-item" type="text" value="Laptop Lenovo"></label>
                        <label>Kode Barang<input id="fine-code" type="text" value="INV-00123"></label>
                        <label>Tanggal Peminjaman<input id="fine-loan-date" type="date" value="2026-08-20"></label>
                        <label>Tanggal Seharusnya Dikembalikan<input id="fine-due-date" type="date" value="2026-08-22"></label>
                        <label>Tanggal Pengembalian<input id="fine-return-date" type="date" value="2026-08-25"></label>
                        <label>Jenis Denda<select id="fine-type"><option>Keterlambatan Pengembalian</option><option>Kerusakan Barang</option><option>Kehilangan Barang</option></select></label>
                        <label>Jumlah Denda (Rp)<input id="fine-amount" type="text" value="30.000"></label>
                        <label class="span-3">Keterangan<textarea id="fine-note">Keterlambatan pengembalian selama 3 hari.</textarea></label>
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
                    <div class="fine-actions"><button type="button" onclick="window.location.href='{{ url('/pengembalian-user') }}'">Batal</button><button type="button"><i class="bi bi-check-circle"></i> Simpan Denda</button></div>
                </form>

                <aside class="fine-qris-card hidden">
                    <div class="fine-qris-head"><strong>Pembayaran QRIS</strong><span>Menunggu Pembayaran</span></div>
                    <div class="fine-qris-amount"><small>Scan QRIS di bawah ini untuk membayar denda sebesar:</small><strong id="qris-amount">Rp 30.000</strong></div>
                    <div class="fine-qris-box">
                        <strong>Sipibs</strong><small>NMID: ID1026581585985<br>A01</small>
                        <div class="fine-qris-image" role="img" aria-label="QRIS Sipibs"></div>
                        <div class="fine-timer">QRIS berlaku hingga <b><i class="bi bi-clock"></i> 14:59</b></div>
                    </div>
                    <div class="fine-howto"><strong>Cara Pembayaran:</strong><p><b>1</b> Buka aplikasi e-wallet / mobile banking</p><p><b>2</b> Pilih menu Scan QRIS</p><p><b>3</b> Scan QR code di atas</p><p><b>4</b> Pastikan nominal sesuai, lalu konfirmasi pembayaran</p></div>
                </aside>

                <aside class="fine-bill-card hidden" id="cash-bill-card">
                    <div class="fine-qris-head"><strong>Pembayaran di Tempat</strong><span>Menunggu Pembayaran</span></div>
                    <div class="fine-cash-alert"><i class="bi bi-shop"></i><div><strong>Bayar langsung ke petugas</strong><small>Tunjukkan detail denda ini saat melakukan pembayaran.</small></div></div>
                    <div class="fine-bill-panel" id="fine-bill-print">
                        <h3>RINCIAN DENDA</h3>
                        <dl>
                            <div><dt>Nama Peminjam</dt><dd id="bill-name">Muhammad Faisal Ilham</dd></div>
                            <div><dt>NIS / NIP</dt><dd id="bill-nis">12345678</dd></div>
                            <div><dt>Nama Barang</dt><dd id="bill-item">Laptop Lenovo</dd></div>
                            <div><dt>Kode Barang</dt><dd id="bill-code">INV-00123</dd></div>
                            <div><dt>Jenis Denda</dt><dd id="bill-type">Keterlambatan Pengembalian</dd></div>
                            <div><dt>Jumlah Denda</dt><dd id="bill-amount">Rp 30.000</dd></div>
                            <div><dt>Status Pembayaran</dt><dd><span class="fine-status-pill">Belum Dibayar</span></dd></div>
                            <div><dt>Metode Pembayaran</dt><dd>Bayar di Tempat</dd></div>
                            <div><dt>Dibuat Pada</dt><dd id="bill-created">25 Agustus 2026, 14:30</dd></div>
                        </dl>
                    </div>
                    <div class="fine-instruction-panel"><h3>INSTRUKSI</h3><p><b>1</b> Datang ke laboratorium sesuai jam operasional.</p><p><b>2</b> Sampaikan kepada petugas bahwa Anda akan membayar denda.</p><p><b>3</b> Lakukan pembayaran sebesar <strong id="bill-instruction-amount">Rp 30.000</strong>.</p><p><b>4</b> Minta konfirmasi dan bukti pembayaran dari petugas.</p></div>
                    <div class="fine-hours-panel"><strong><i class="bi bi-clock"></i> Jam Operasional Laboratorium</strong><div><span>Senin - Jumat</span><b>08.00 - 16.00</b></div><div><span>Sabtu</span><b>08.00 - 12.00</b></div></div>
                    <button class="fine-check-btn" type="button" onclick="downloadFineBillPdf()"><i class="bi bi-download"></i> Unduh Bill PDF</button>
                </aside>
            </div>
        </section>
    </main>
</div>

<script>
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

    function formatRupiah(value) {
        const digits = String(value || '').replace(/\D/g, '') || '0';
        return 'Rp ' + Number(digits).toLocaleString('id-ID');
    }

    function formatDate(value) {
        if (!value) return '-';
        return new Date(value + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
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
        function parseInputDate(dateStr) {
        if (!dateStr) return '';
        if (dateStr.includes('/')) {
            const p = dateStr.split('/');
            if (p.length === 3) return `${p[2]}-${p[1].padStart(2, '0')}-${p[0].padStart(2, '0')}`;
        }
        if (dateStr.includes(' ')) {
            const months = { 'januari': '01', 'februari': '02', 'maret': '03', 'april': '04', 'mei': '05', 'juni': '06', 'juli': '07', 'agustus': '08', 'september': '09', 'oktober': '10', 'november': '11', 'desember': '12', 'jan': '01', 'feb': '02', 'mar': '03', 'apr': '04', 'may': '05', 'jun': '06', 'jul': '07', 'aug': '08', 'sep': '09', 'oct': '10', 'nov': '11', 'dec': '12' };
            const parts = dateStr.toLowerCase().split(' ');
            if (parts.length >= 3) {
                const day = parts[0].padStart(2, '0');
                const month = months[parts[1]] || '08';
                const year = parts[2];
                return `${year}-${month}-${day}`;
            }
        }
        return dateStr;
    }

    function loadActiveFineData() {
        try {
            let activeFine = JSON.parse(localStorage.getItem('sipibsActiveFine') || 'null');
            if (!activeFine) {
                const submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]');
                const fined = submissions.find(s => s.decision === 'Denda' || s.status === 'Denda');
                if (fined) {
                    activeFine = {
                        borrower: fined.borrower || '{{ $userName }}',
                        nis: fined.serial || '12345678',
                        userClass: 'XI RPL 1',
                        itemName: fined.itemName || fined.name,
                        itemCode: fined.serial || fined.id || fined.loanId,
                        loanDate: fined.startDate || '2026-08-20',
                        dueDate: fined.dueDate || '2026-08-22',
                        returnDate: fined.submittedAt ? fined.submittedAt.split('T')[0] : '2026-08-25',
                        fineType: fined.fineType || 'Keterlambatan Pengembalian',
                        fineAmount: fined.fineAmount || '30.000',
                        note: fined.adminNote || fined.note || 'Pelanggaran pengembalian barang.'
                    };
                }
            }
            if (activeFine) {
                if (document.getElementById('fine-name')) document.getElementById('fine-name').value = activeFine.borrower || '';
                if (document.getElementById('fine-nis')) document.getElementById('fine-nis').value = activeFine.nis || '';
                if (document.getElementById('fine-class')) document.getElementById('fine-class').value = activeFine.userClass || 'XI RPL 1';
                if (document.getElementById('fine-item')) document.getElementById('fine-item').value = activeFine.itemName || '';
                if (document.getElementById('fine-code')) document.getElementById('fine-code').value = activeFine.itemCode || '';
                if (document.getElementById('fine-loan-date') && activeFine.loanDate) {
                    const d = parseInputDate(activeFine.loanDate);
                    if (d) document.getElementById('fine-loan-date').value = d;
                }
                if (document.getElementById('fine-due-date') && activeFine.dueDate) {
                    const d = parseInputDate(activeFine.dueDate);
                    if (d) document.getElementById('fine-due-date').value = d;
                }
                if (document.getElementById('fine-return-date') && activeFine.returnDate) {
                    const d = parseInputDate(activeFine.returnDate);
                    if (d) document.getElementById('fine-return-date').value = d;
                }
                if (document.getElementById('fine-type') && activeFine.fineType) {
                    const select = document.getElementById('fine-type');
                    for (let i = 0; i < select.options.length; i++) {
                        if (select.options[i].value === activeFine.fineType || select.options[i].text === activeFine.fineType) {
                            select.selectedIndex = i;
                            break;
                        }
                    }
                }
                if (document.getElementById('fine-amount') && activeFine.fineAmount) {
                    document.getElementById('fine-amount').value = String(activeFine.fineAmount).replace(/\D/g, '');
                }
                if (document.getElementById('fine-note') && activeFine.note) {
                    document.getElementById('fine-note').value = activeFine.note;
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    loadActiveFineData();
    syncBillData();
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

    function escapePdfText(text) {
        return String(text).replace(/\\/g, '\\\\').replace(/\(/g, '\\(').replace(/\)/g, '\\)');
    }

    function downloadFineBillPdf() {
        function parseInputDate(dateStr) {
        if (!dateStr) return '';
        if (dateStr.includes('/')) {
            const p = dateStr.split('/');
            if (p.length === 3) return `${p[2]}-${p[1].padStart(2, '0')}-${p[0].padStart(2, '0')}`;
        }
        if (dateStr.includes(' ')) {
            const months = { 'januari': '01', 'februari': '02', 'maret': '03', 'april': '04', 'mei': '05', 'juni': '06', 'juli': '07', 'agustus': '08', 'september': '09', 'oktober': '10', 'november': '11', 'desember': '12', 'jan': '01', 'feb': '02', 'mar': '03', 'apr': '04', 'may': '05', 'jun': '06', 'jul': '07', 'aug': '08', 'sep': '09', 'oct': '10', 'nov': '11', 'dec': '12' };
            const parts = dateStr.toLowerCase().split(' ');
            if (parts.length >= 3) {
                const day = parts[0].padStart(2, '0');
                const month = months[parts[1]] || '08';
                const year = parts[2];
                return `${year}-${month}-${day}`;
            }
        }
        return dateStr;
    }

    function loadActiveFineData() {
        try {
            let activeFine = JSON.parse(localStorage.getItem('sipibsActiveFine') || 'null');
            if (!activeFine) {
                const submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]');
                const fined = submissions.find(s => s.decision === 'Denda' || s.status === 'Denda');
                if (fined) {
                    activeFine = {
                        borrower: fined.borrower || '{{ $userName }}',
                        nis: fined.serial || '12345678',
                        userClass: 'XI RPL 1',
                        itemName: fined.itemName || fined.name,
                        itemCode: fined.serial || fined.id || fined.loanId,
                        loanDate: fined.startDate || '2026-08-20',
                        dueDate: fined.dueDate || '2026-08-22',
                        returnDate: fined.submittedAt ? fined.submittedAt.split('T')[0] : '2026-08-25',
                        fineType: fined.fineType || 'Keterlambatan Pengembalian',
                        fineAmount: fined.fineAmount || '30.000',
                        note: fined.adminNote || fined.note || 'Pelanggaran pengembalian barang.'
                    };
                }
            }
            if (activeFine) {
                if (document.getElementById('fine-name')) document.getElementById('fine-name').value = activeFine.borrower || '';
                if (document.getElementById('fine-nis')) document.getElementById('fine-nis').value = activeFine.nis || '';
                if (document.getElementById('fine-class')) document.getElementById('fine-class').value = activeFine.userClass || 'XI RPL 1';
                if (document.getElementById('fine-item')) document.getElementById('fine-item').value = activeFine.itemName || '';
                if (document.getElementById('fine-code')) document.getElementById('fine-code').value = activeFine.itemCode || '';
                if (document.getElementById('fine-loan-date') && activeFine.loanDate) {
                    const d = parseInputDate(activeFine.loanDate);
                    if (d) document.getElementById('fine-loan-date').value = d;
                }
                if (document.getElementById('fine-due-date') && activeFine.dueDate) {
                    const d = parseInputDate(activeFine.dueDate);
                    if (d) document.getElementById('fine-due-date').value = d;
                }
                if (document.getElementById('fine-return-date') && activeFine.returnDate) {
                    const d = parseInputDate(activeFine.returnDate);
                    if (d) document.getElementById('fine-return-date').value = d;
                }
                if (document.getElementById('fine-type') && activeFine.fineType) {
                    const select = document.getElementById('fine-type');
                    for (let i = 0; i < select.options.length; i++) {
                        if (select.options[i].value === activeFine.fineType || select.options[i].text === activeFine.fineType) {
                            select.selectedIndex = i;
                            break;
                        }
                    }
                }
                if (document.getElementById('fine-amount') && activeFine.fineAmount) {
                    document.getElementById('fine-amount').value = String(activeFine.fineAmount).replace(/\D/g, '');
                }
                if (document.getElementById('fine-note') && activeFine.note) {
                    document.getElementById('fine-note').value = activeFine.note;
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    loadActiveFineData();
    syncBillData();
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

    function parseInputDate(dateStr) {
        if (!dateStr) return '';
        if (dateStr.includes('/')) {
            const p = dateStr.split('/');
            if (p.length === 3) return `${p[2]}-${p[1].padStart(2, '0')}-${p[0].padStart(2, '0')}`;
        }
        if (dateStr.includes(' ')) {
            const months = { 'januari': '01', 'februari': '02', 'maret': '03', 'april': '04', 'mei': '05', 'juni': '06', 'juli': '07', 'agustus': '08', 'september': '09', 'oktober': '10', 'november': '11', 'desember': '12', 'jan': '01', 'feb': '02', 'mar': '03', 'apr': '04', 'may': '05', 'jun': '06', 'jul': '07', 'aug': '08', 'sep': '09', 'oct': '10', 'nov': '11', 'dec': '12' };
            const parts = dateStr.toLowerCase().split(' ');
            if (parts.length >= 3) {
                const day = parts[0].padStart(2, '0');
                const month = months[parts[1]] || '08';
                const year = parts[2];
                return `${year}-${month}-${day}`;
            }
        }
        return dateStr;
    }

    function loadActiveFineData() {
        try {
            let activeFine = JSON.parse(localStorage.getItem('sipibsActiveFine') || 'null');
            if (!activeFine) {
                const submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]');
                const fined = submissions.find(s => s.decision === 'Denda' || s.status === 'Denda');
                if (fined) {
                    activeFine = {
                        borrower: fined.borrower || '{{ $userName }}',
                        nis: fined.serial || '12345678',
                        userClass: 'XI RPL 1',
                        itemName: fined.itemName || fined.name,
                        itemCode: fined.serial || fined.id || fined.loanId,
                        loanDate: fined.startDate || '2026-08-20',
                        dueDate: fined.dueDate || '2026-08-22',
                        returnDate: fined.submittedAt ? fined.submittedAt.split('T')[0] : '2026-08-25',
                        fineType: fined.fineType || 'Keterlambatan Pengembalian',
                        fineAmount: fined.fineAmount || '30.000',
                        note: fined.adminNote || fined.note || 'Pelanggaran pengembalian barang.'
                    };
                }
            }
            if (activeFine) {
                if (document.getElementById('fine-name')) document.getElementById('fine-name').value = activeFine.borrower || '';
                if (document.getElementById('fine-nis')) document.getElementById('fine-nis').value = activeFine.nis || '';
                if (document.getElementById('fine-class')) document.getElementById('fine-class').value = activeFine.userClass || 'XI RPL 1';
                if (document.getElementById('fine-item')) document.getElementById('fine-item').value = activeFine.itemName || '';
                if (document.getElementById('fine-code')) document.getElementById('fine-code').value = activeFine.itemCode || '';
                if (document.getElementById('fine-loan-date') && activeFine.loanDate) {
                    const d = parseInputDate(activeFine.loanDate);
                    if (d) document.getElementById('fine-loan-date').value = d;
                }
                if (document.getElementById('fine-due-date') && activeFine.dueDate) {
                    const d = parseInputDate(activeFine.dueDate);
                    if (d) document.getElementById('fine-due-date').value = d;
                }
                if (document.getElementById('fine-return-date') && activeFine.returnDate) {
                    const d = parseInputDate(activeFine.returnDate);
                    if (d) document.getElementById('fine-return-date').value = d;
                }
                if (document.getElementById('fine-type') && activeFine.fineType) {
                    const select = document.getElementById('fine-type');
                    for (let i = 0; i < select.options.length; i++) {
                        if (select.options[i].value === activeFine.fineType || select.options[i].text === activeFine.fineType) {
                            select.selectedIndex = i;
                            break;
                        }
                    }
                }
                if (document.getElementById('fine-amount') && activeFine.fineAmount) {
                    document.getElementById('fine-amount').value = String(activeFine.fineAmount).replace(/\D/g, '');
                }
                if (document.getElementById('fine-note') && activeFine.note) {
                    document.getElementById('fine-note').value = activeFine.note;
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    loadActiveFineData();
    syncBillData();

    const fineUploadBox = document.getElementById('fine-upload-box');
    const fineUploadInput = document.getElementById('fine-upload-input');
    const fineUploadEmpty = document.getElementById('fine-upload-empty');
    const fineUploadPreview = document.getElementById('fine-upload-preview');
    const finePreviewList = document.getElementById('fine-preview-list');
    const fineUploadAdd = document.getElementById('fine-upload-add');
    const selectedFineFiles = [];
    const maxFineFiles = 3;

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
                item.innerHTML = '<div class="fine-preview-pdf"><i class="bi bi-file-earmark-pdf"></i><span>' + file.name + '</span></div><button type="button" class="fine-preview-remove" data-index="' + index + '">&times;</button>';
            }
            finePreviewList.appendChild(item);
        });
        fineUploadEmpty.classList.toggle('hidden', selectedFineFiles.length > 0);
        fineUploadPreview.classList.toggle('hidden', selectedFineFiles.length === 0);
        fineUploadBox.classList.toggle('has-files', selectedFineFiles.length > 0);
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

    fineUploadBox.addEventListener('click', function (e) {
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
        if (e.target.closest('#fine-upload-empty') || e.target === fineUploadBox) {
            fineUploadInput.click();
        }
    });

    fineUploadInput.addEventListener('change', function () {
        addFineFiles(this.files);
        this.value = '';
    });

    fineUploadBox.addEventListener('dragover', function (e) {
        e.preventDefault();
        fineUploadBox.classList.add('dragging');
    });
    fineUploadBox.addEventListener('dragleave', function () {
        fineUploadBox.classList.remove('dragging');
    });
    fineUploadBox.addEventListener('drop', function (e) {
        e.preventDefault();
        fineUploadBox.classList.remove('dragging');
        addFineFiles(e.dataTransfer.files);
    });
</script>
@include('user.partials.profile-sync')
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
</body>
</html>






