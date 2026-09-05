document.addEventListener('DOMContentLoaded', function () {
    function refreshCount(notif) {
        const count = notif.querySelector('.admin-notification-count');
        if (!count) return;
        const totalUnread = notif.querySelectorAll('.notification-item.unread').length;
        count.textContent = String(totalUnread);
        count.style.display = totalUnread > 0 ? 'inline-flex' : 'none';
    }

    function renderSipibsLoanAdminNotification() {
        document.querySelectorAll('[data-admin-notification]').forEach(function (notif) {
            const list = notif.querySelector('.notification-list');
            if (!list) return;
            const request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
            const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
            const oldDynamic = list.querySelector('[data-loan-admin-notification]');
            if (oldDynamic) oldDynamic.remove();

            if (request && (!decision || decision.status === 'pending')) {
                const item = document.createElement('div');
                item.className = 'notification-item unread';
                item.setAttribute('data-loan-admin-notification', '1');
                item.innerHTML = `
                    <span class="notif-icon blue"><i class="bi bi-journal-check"></i></span>
                    <div>
                        <strong>1 peminjaman menunggu</strong>
                        <small>${request.nama} mengajukan ${request.barang}.</small>
                        <div style="display:flex;gap:6px;margin-top:8px;">
                            <button type="button" class="btn-simulasi green" style="padding:6px 10px;font-size:11px;" data-loan-decision="approved">Setujui</button>
                            <button type="button" class="btn-simulasi red" style="padding:6px 10px;font-size:11px;" data-loan-decision="rejected">Tolak</button>
                        </div>
                    </div>`;
                list.prepend(item);
            }
            refreshCount(notif);
        });
    }

    document.querySelectorAll('[data-admin-notification]').forEach(function (notif) {
        const button = notif.querySelector('.admin-notification-btn');
        const markRead = notif.querySelector('.mark-read-btn');

        button.addEventListener('click', function (event) {
            event.stopPropagation();
            document.querySelectorAll('[data-admin-notification].open').forEach(function (openNotif) {
                if (openNotif !== notif) openNotif.classList.remove('open');
            });
            renderSipibsLoanAdminNotification();
            notif.classList.toggle('open');
            button.setAttribute('aria-expanded', notif.classList.contains('open') ? 'true' : 'false');
        });

        markRead.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            notif.querySelectorAll('.notification-item').forEach(function (item) {
                item.classList.remove('unread');
            });
            refreshCount(notif);
        });
    });

    document.addEventListener('click', function (event) {
        const decisionButton = event.target.closest('[data-loan-decision]');
        if (decisionButton) {
            event.preventDefault();
            event.stopPropagation();
            const request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
            if (!request) return;
            const status = decisionButton.getAttribute('data-loan-decision');
            request.status = status;
            request.decidedAt = new Date().toISOString();
            const history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
            const updatedHistory = history.map(item => item.id === request.id ? request : item);
            if (!updatedHistory.some(item => item.id === request.id)) updatedHistory.unshift(request);
            localStorage.setItem('sipibsLoanHistory', JSON.stringify(updatedHistory));
            localStorage.setItem('sipibsLoanDecision', JSON.stringify({
                status: status,
                request: request,
                decidedAt: request.decidedAt
            }));
            const notifications = JSON.parse(localStorage.getItem('sipibsUserNotifications') || '[]');
            const notifId = 'loan-decision-' + request.id + '-' + status;
            const approved = status === 'approved';
            if (!notifications.some(item => item.id === notifId)) {
                notifications.unshift({
                    id: notifId,
                    title: approved ? 'Peminjaman Disetujui' : 'Peminjaman Ditolak',
                    message: (request.barang || 'Barang') + (approved ? ' disetujui admin. Klik untuk melihat bukti peminjaman.' : ' ditolak admin. Klik untuk melihat detail.'),
                    icon: approved ? 'bi-check-circle' : 'bi-x-circle',
                    type: approved ? 'green' : 'red',
                    read: false,
                    loanStatus: status,
                    loanId: request.id,
                    url: approved ? (request.downloadUrl || request.detailUrl || '/peminjaman-user') : (request.detailUrl || '/peminjaman-user'),
                    time: request.decidedAt
                });
                localStorage.setItem('sipibsUserNotifications', JSON.stringify(notifications.slice(0, 20)));
            }
            alert(status === 'approved' ? 'Peminjaman disetujui. Notifikasi dikirim ke user.' : 'Peminjaman ditolak. Notifikasi dikirim ke user.');
            renderSipibsLoanAdminNotification();
            return;
        }

        document.querySelectorAll('[data-admin-notification].open').forEach(function (notif) {
            notif.classList.remove('open');
            const button = notif.querySelector('.admin-notification-btn');
            if (button) button.setAttribute('aria-expanded', 'false');
        });
    });

    window.addEventListener('storage', renderSipibsLoanAdminNotification);
    renderSipibsLoanAdminNotification();
});

