<div class="admin-notification" data-admin-notification>
    <button type="button" class="admin-notification-btn" aria-label="Buka notifikasi" aria-expanded="false">
        <i class="bi bi-bell"></i>
        <span class="admin-notification-count" style="display:none">0</span>
    </button>
    <div class="admin-notification-dropdown" role="menu">
        <div class="notification-head">
            <strong>Notifikasi</strong>
            <button type="button" class="mark-read-btn">Tandai dibaca</button>
        </div>
        <div class="notification-list">
            <!-- Notifikasi dinamis diisi oleh admin-notification.js -->
        </div>
        <a href="{{ url('/admin/laporan') }}" class="notification-footer">Lihat laporan lengkap</a>
    </div>
</div>