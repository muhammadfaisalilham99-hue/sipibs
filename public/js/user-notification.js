document.addEventListener('DOMContentLoaded', function () {
    const STORAGE_KEY = 'sipibsUserNotifications';
    const SEED_KEY = 'sipibsUserNotificationsSeeded';
    const MAX_NOTIFS = 5;

    function loadNotifications() {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        } catch (e) {
            return [];
        }
    }

    function saveNotifications(list) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
    }

    function syncLoanDecisionNotification() {
        let decision = null;
        try { decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null'); } catch (e) {}
        if (!decision || !decision.request || !['approved', 'rejected'].includes(decision.status)) return;

        const request = decision.request;
        const notifId = 'loan-decision-' + (request.id || 'last') + '-' + decision.status;
        const list = loadNotifications();
        if (list.some(function (item) { return item.id === notifId; })) return;

        const approved = decision.status === 'approved';
        list.unshift({
            id: notifId,
            title: approved ? 'Peminjaman Disetujui' : 'Peminjaman Ditolak',
            message: (request.barang || 'Barang') + (approved ? ' disetujui admin. Klik untuk melihat bukti peminjaman.' : ' ditolak admin. Klik untuk melihat detail.'),
            icon: approved ? 'bi-check-circle' : 'bi-x-circle',
            type: approved ? 'green' : 'red',
            read: false,
            loanStatus: decision.status,
            url: approved ? (request.downloadUrl || request.detailUrl || '/peminjaman-user') : (request.detailUrl || '/peminjaman-user'),
            time: decision.decidedAt || request.decidedAt || new Date().toISOString()
        });
        saveNotifications(list.slice(0, 20));
    }

    function formatLoanDate(dateValue) {
        if (!dateValue) return '-';
        if (String(dateValue).includes('/')) {
            const parts = String(dateValue).split('/');
            dateValue = parts[2] + '-' + parts[1] + '-' + parts[0];
        }
        const date = new Date(dateValue + 'T00:00:00');
        if (Number.isNaN(date.getTime())) return dateValue;
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function getLoanDecisionRequest(item) {
        try {
            const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
            if (decision && decision.request && (!item.loanId || String(decision.request.id) === String(item.loanId))) {
                return decision.request;
            }
        } catch (e) {}
        return item.loan || null;
    }

    function openLoanDecisionModal(item) {
        const modal = document.getElementById('user-loan-decision-modal');
        const request = getLoanDecisionRequest(item);
        if (!modal || !request) return false;
        const approved = (item.loanStatus || '').toLowerCase() === 'approved';
        modal.querySelector('[data-loan-modal-icon]').innerHTML = '<i class="bi ' + (approved ? 'bi-check-lg' : 'bi-x-lg') + '"></i>';
        modal.querySelector('[data-loan-modal-icon]').className = approved ? 'loan-modal-icon success' : 'loan-modal-icon danger';
        modal.querySelector('[data-loan-modal-title]').textContent = approved ? 'Peminjaman Disetujui!' : 'Peminjaman Ditolak!';
        modal.querySelector('[data-loan-modal-sub]').textContent = approved ? 'Selamat! Peminjaman barang Anda telah disetujui oleh admin.' : 'Maaf! Peminjaman barang Anda ditolak oleh admin.';
        modal.querySelector('[data-loan-modal-id]').textContent = request.id || '-';
        modal.querySelector('[data-loan-modal-barang]').textContent = request.barang || '-';
        modal.querySelector('[data-loan-modal-kategori]').textContent = request.kategori || '-';
        modal.querySelector('[data-loan-modal-jumlah]').textContent = request.jumlah || '1';
        modal.querySelector('[data-loan-modal-pinjam]').textContent = formatLoanDate(request.tanggalPinjam);
        modal.querySelector('[data-loan-modal-kembali]').textContent = formatLoanDate(request.tanggalKembali);
        modal.querySelector('[data-loan-modal-keperluan]').textContent = request.keperluan || '-';
        const image = modal.querySelector('[data-loan-modal-image]');
        if (image && request.image) image.src = request.image;
        const proof = modal.querySelector('[data-loan-modal-proof]');
        const reject = modal.querySelector('[data-loan-modal-reject]');
        const download = modal.querySelector('[data-loan-modal-download]');
        if (proof) proof.style.display = approved ? '' : 'none';
        if (reject) reject.style.display = approved ? 'none' : '';
        if (download) {
            download.style.display = approved ? '' : 'none';
            download.href = request.downloadUrl || item.url || '#';
        }
        modal.classList.add('active');
        return true;
    }

    function seedNotifications() {
        if (localStorage.getItem(SEED_KEY) === '1') return;
        const existing = loadNotifications();
        if (!existing.length) {
            const seeded = [
                {
                    id: 'seed-1',
                    title: 'Pengingat Pembayaran Denda',
                    message: 'Segera lakukan pembayaran denda keterlambatan sebelum jatuh tempo.',
                    icon: 'bi-cash-coin',
                    type: 'red',
                    read: false,
                    time: new Date(Date.now() - 86400000).toISOString()
                },
                {
                    id: 'seed-2',
                    title: 'Informasi Laboratorium',
                    message: 'Jam operasional nerubah menjadi 08.00 - 15.00 pada hari Jumat.',
                    icon: 'bi-info-circle',
                    type: 'blue',
                    read: false,
                    time: new Date(Date.now() - 172800000).toISOString()
                }
            ];
            saveNotifications(seeded);
        }
        localStorage.setItem(SEED_KEY, '1');
    }

    function formatTime(iso) {
        const date = new Date(iso);
        return date.toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function refreshCount(notif, list) {
        const count = notif.querySelector('.admin-notification-count');
        if (!count) return;
        const unread = list.filter(function (item) { return !item.read; }).length;
        count.textContent = String(unread);
        count.classList.toggle('hidden', unread === 0);
    }

    function renderNotifications(notif) {
        syncLoanDecisionNotification();
        const listEl = notif.querySelector('.notification-list');
        if (!listEl) return;
        const list = loadNotifications();
        listEl.innerHTML = '';

        renderUnreadCount(notif);

        if (list.length === 0) {
            listEl.innerHTML = '<div class="notification-item empty-notification"><span class="notif-icon blue"><i class="bi bi-check2-circle"></i></span><div><strong>Tidak ada notifikasi</strong><small>Belum ada pesan dari admin.</small></div></div>';
            refreshCount(notif, list);
            renderFooter(notif, list);
            return;
        }

        list.slice()
            .sort(function (a, b) { return new Date(b.time) - new Date(a.time); })
            .slice(0, MAX_NOTIFS)
            .forEach(function (item) {
                const el = document.createElement('div');
                el.className = 'notification-item' + (item.read ? '' : ' unread');
                el.setAttribute('data-notification-id', item.id);
                el.innerHTML =
                    '<span class="notif-icon ' + (item.type || 'blue') + '"><i class="bi ' + (item.icon || 'bi-bell') + '"></i></span>' +
                    '<div><strong>' + item.title + '</strong><small>' + item.message + '</small>' +
                    '<small class="notif-time">' + formatTime(item.time) + '</small></div>';
                el.addEventListener('click', function () {
                    const current = loadNotifications();
                    const target = current.find(function (n) { return n.id === item.id; });
                    if (target) { target.read = true; saveNotifications(current); renderNotifications(notif); }
                    if (item.loanStatus && openLoanDecisionModal(item)) return;
                    if (item.url) { window.location.href = item.url; }
                });
                listEl.appendChild(el);
            });
        refreshCount(notif, list);
        renderFooter(notif, list);
    }

    function renderUnreadCount(notif) {
        const strong = notif.querySelector('.notification-head strong');
        if (!strong) return;
        const unread = loadNotifications().filter(function (n) { return !n.read; }).length;
        let small = strong.querySelector('small');
        if (!small) {
            small = document.createElement('small');
            strong.appendChild(small);
        }
        small.textContent = unread > 0 ? ' • ' + unread + ' belum dibaca' : '';
    }

    function renderFooter(notif, list) {
        const dropdown = notif.querySelector('.admin-notification-dropdown');
        if (!dropdown) return;
        const oldFooter = dropdown.querySelector('.notification-footer');
        if (oldFooter) oldFooter.remove();

        const hasRead = list.some(function (n) { return n.read; });
        if (!hasRead && list.length <= MAX_NOTIFS) return;

        const footer = document.createElement('div');
        footer.className = 'notification-footer';
        let html = '';
        if (hasRead) {
            html += '<button type="button" class="notification-clear-btn">Hapus yang sudah dibaca</button>';
        }
        if (list.length > MAX_NOTIFS) {
            html += '<small>Menampilkan ' + MAX_NOTIFS + ' notifikasi terbaru</small>';
        }
        footer.innerHTML = html;
        const clearBtn = footer.querySelector('.notification-clear-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                const current = loadNotifications().filter(function (n) { return !n.read; });
                saveNotifications(current);
                renderNotifications(notif);
            });
        }
        dropdown.appendChild(footer);
    }

    document.querySelectorAll('[data-user-notification]').forEach(function (notif) {
        seedNotifications();
        const button = notif.querySelector('.admin-notification-btn');
        const markRead = notif.querySelector('.mark-read-btn');
        renderNotifications(notif);

        button.addEventListener('click', function (event) {
            event.stopPropagation();
            document.querySelectorAll('[data-user-notification].open').forEach(function (openNotif) {
                if (openNotif !== notif) openNotif.classList.remove('open');
            });
            notif.classList.toggle('open');
            button.setAttribute('aria-expanded', notif.classList.contains('open') ? 'true' : 'false');
        });

        if (markRead) {
            markRead.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                const current = loadNotifications().map(function (n) { n.read = true; return n; });
                saveNotifications(current);
                renderNotifications(notif);
            });
        }
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('[data-user-notification]')) {
            document.querySelectorAll('[data-user-notification].open').forEach(function (notif) {
                notif.classList.remove('open');
                const btn = notif.querySelector('.admin-notification-btn');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });
        }
    });

    window.addEventListener('storage', function () {
        document.querySelectorAll('[data-user-notification]').forEach(renderNotifications);
    });

    window.sipibsUserNotify = function (notification) {
        const list = loadNotifications();
        list.push({
            id: 'ntf-' + Date.now(),
            title: notification.title || 'Notifikasi',
            message: notification.message || '',
            icon: notification.icon || 'bi-bell',
            type: notification.type || 'blue',
            read: false,
            url: notification.url || '',
            time: new Date().toISOString()
        });
        saveNotifications(list);
        document.querySelectorAll('[data-user-notification]').forEach(renderNotifications);
    };

    setInterval(function () {
        document.querySelectorAll('[data-user-notification]').forEach(renderNotifications);
    }, 5000);
});
