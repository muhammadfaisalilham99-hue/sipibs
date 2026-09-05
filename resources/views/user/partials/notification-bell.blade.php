<div class="admin-notification user-loan-notification" data-user-notification>
    <button type="button" class="admin-notification-btn" aria-label="Buka notifikasi" aria-expanded="false">
        <i class="bi bi-bell"></i>
        <span class="admin-notification-count hidden">0</span>
    </button>
    <div class="admin-notification-dropdown" role="menu">
        <div class="notification-head">
            <strong>Notifikasi</strong>
            <button type="button" class="mark-read-btn">Tandai dibaca</button>
        </div>
        <div class="notification-list"></div>
    </div>
</div>

<style>
    .user-loan-modal-overlay { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(15,23,42,.35); z-index:99999; padding:20px; }
    .user-loan-modal-overlay.active { display:flex; }
    .user-loan-modal { width:390px; max-width:100%; background:#fff; border-radius:10px; box-shadow:0 18px 45px rgba(15,23,42,.18); padding:24px; position:relative; text-align:center; color:#0f172a; }
    .user-loan-modal-close { position:absolute; right:12px; top:10px; border:0; background:transparent; color:#64748b; font-size:18px; cursor:pointer; }
    .loan-modal-icon { width:54px; height:54px; margin:0 auto 14px; border-radius:999px; display:flex; align-items:center; justify-content:center; font-size:30px; }
    .loan-modal-icon.success { border:3px solid #22c55e; color:#22c55e; }
    .loan-modal-icon.danger { border:3px solid #dc2626; color:#dc2626; border-radius:8px; }
    .user-loan-modal h2 { font-size:17px; margin:0 0 8px; color:#0f172a; }
    .user-loan-modal-sub { font-size:12px; color:#64748b; margin:0 0 14px; }
    .loan-modal-detail { border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; text-align:left; margin-bottom:14px; }
    .loan-modal-detail-head { background:#f8fafc; color:#2563eb; font-weight:800; text-align:center; padding:8px; font-size:12px; }
    .loan-modal-body { display:flex; gap:12px; padding:12px; }
    .loan-modal-body img { width:82px; height:62px; object-fit:cover; border-radius:4px; border:1px solid #e2e8f0; }
    .loan-modal-meta { flex:1; display:grid; gap:6px; font-size:11px; }
    .loan-modal-row { display:grid; grid-template-columns:92px 1fr; gap:6px; }
    .loan-modal-row span { color:#64748b; }
    .loan-modal-row strong { color:#0f172a; font-weight:700; }
    .loan-modal-foot { border-top:1px solid #e2e8f0; padding:10px 12px; display:grid; gap:7px; font-size:11px; }
    .loan-modal-proof, .loan-modal-reject { display:flex; gap:10px; align-items:flex-start; text-align:left; border-radius:8px; padding:12px; margin-bottom:14px; font-size:11px; }
    .loan-modal-proof { background:#ecfdf5; color:#166534; border:1px solid #bbf7d0; }
    .loan-modal-reject { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
    .loan-modal-actions { display:flex; gap:10px; }
    .loan-modal-actions button, .loan-modal-actions a { flex:1; height:36px; border-radius:7px; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; text-decoration:none; cursor:pointer; }
    .loan-modal-actions a { background:#0d6efd; color:#fff; border-color:#0d6efd; }
</style>

<div class="user-loan-modal-overlay" id="user-loan-decision-modal">
    <div class="user-loan-modal">
        <button type="button" class="user-loan-modal-close" onclick="document.getElementById('user-loan-decision-modal').classList.remove('active')">&times;</button>
        <div class="loan-modal-icon success" data-loan-modal-icon><i class="bi bi-check-lg"></i></div>
        <h2 data-loan-modal-title>Peminjaman Disetujui!</h2>
        <p class="user-loan-modal-sub" data-loan-modal-sub>Selamat! Peminjaman barang Anda telah disetujui oleh admin.</p>
        <div class="loan-modal-detail">
            <div class="loan-modal-detail-head">Detail Peminjaman</div>
            <div class="loan-modal-body">
                <img data-loan-modal-image src="{{ asset('images/no-image.svg') }}" alt="Barang">
                <div class="loan-modal-meta">
                    <div class="loan-modal-row"><span>ID Peminjaman</span><strong data-loan-modal-id>-</strong></div>
                    <div class="loan-modal-row"><span>Nama Barang</span><strong data-loan-modal-barang>-</strong></div>
                    <div class="loan-modal-row"><span>Kategori</span><strong data-loan-modal-kategori>-</strong></div>
                    <div class="loan-modal-row"><span>Jumlah</span><strong data-loan-modal-jumlah>1</strong></div>
                    <div class="loan-modal-row"><span>Kondisi</span><strong>Baik</strong></div>
                </div>
            </div>
            <div class="loan-modal-foot">
                <div class="loan-modal-row"><span>Tanggal Pinjam</span><strong data-loan-modal-pinjam>-</strong></div>
                <div class="loan-modal-row"><span>Tanggal Kembali</span><strong data-loan-modal-kembali>-</strong></div>
                <div class="loan-modal-row"><span>Keperluan</span><strong data-loan-modal-keperluan>-</strong></div>
            </div>
        </div>
        <div class="loan-modal-proof" data-loan-modal-proof><i class="bi bi-printer"></i><div><strong>Bukti Peminjaman</strong><br>Download bukti peminjaman untuk ditunjukkan saat mengambil serta mengembalikan barang.</div></div>
        <div class="loan-modal-reject" data-loan-modal-reject style="display:none"><i class="bi bi-x-circle"></i><div><strong>Alasan Penolakan</strong><br>Barang sedang tidak tersedia.</div></div>
        <div class="loan-modal-actions">
            <button type="button" onclick="document.getElementById('user-loan-decision-modal').classList.remove('active')">Nanti Saja</button>
            <a href="#" target="_blank" data-loan-modal-download><i class="bi bi-download"></i>&nbsp;Download</a>
        </div>
    </div>
</div>
