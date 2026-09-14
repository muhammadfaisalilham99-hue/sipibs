(function () {
    function getStored(key) {
        try { return JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) { return []; }
    }
    function setStored(key, val) {
        try { localStorage.setItem(key, JSON.stringify(val)); } catch (e) {}
    }

    function refreshCount(notif) {
        const count = notif.querySelector('.admin-notification-count');
        const totalUnread = notif.querySelectorAll('.notification-item.unread').length;
        count.textContent = String(totalUnread);
        count.style.display = totalUnread ? 'inline-flex' : 'none';
    }

    function renderNotifications() {
        document.querySelectorAll('[data-admin-notification]').forEach(function (notif) {
            const list = notif.querySelector('.notification-list');
            if (!list) return;
            Promise.all([
                fetch('/api/peminjaman/list?status=all', { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.ok ? r.json() : { borrowings: [] }),
                fetch('/api/pengembalian/admin/pending', { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.ok ? r.json() : { returns: [] })
            ]).then(([loanData, returnData]) => {
                const deletedIds = getStored('sipibsAdminDeletedNotifs');
                const readIds = getStored('sipibsAdminReadNotifs');
                const loans = (Array.isArray(loanData.borrowings) ? loanData.borrowings : []).slice(0, 20);
                const returns = (Array.isArray(returnData.returns) ? returnData.returns : []).slice(0, 20);

                const allItems = [
                    ...loans.map(loan => ({
                        id: 'loan-' + loan.id,
                        title: 'Peminjaman barang',
                        text: `${loan.borrower || '-'} meminjam ${loan.barang || '-'}.`,
                        icon: 'bi-box-arrow-in-down',
                        color: 'blue'
                    })),
                    ...returns.map(item => ({
                        id: 'return-' + item.id,
                        title: 'Pengembalian barang',
                        text: `${item.borrower || '-'} mengembalikan ${item.itemName || '-'}.`,
                        icon: 'bi-box-arrow-in-up',
                        color: 'green'
                    }))
                ].filter(it => !deletedIds.includes(it.id));

                if (!allItems.length) {
                    list.innerHTML = '<div class="notification-item"><div><strong>Tidak ada notifikasi baru</strong><small>Belum ada aktivitas peminjaman atau pengembalian.</small></div></div>';
                    refreshCount(notif);
                    return;
                }

                list.innerHTML = allItems.map(it => {
                    const isUnread = !readIds.includes(it.id);
                    return `<div class="notification-item ${isUnread ? 'unread' : ''}" data-notif-id="${it.id}">
                        <span class="notif-icon ${it.color}"><i class="bi ${it.icon}"></i></span>
                        <div><strong>${it.title}</strong><small>${it.text}</small></div>
                    </div>`;
                }).join('');

                refreshCount(notif);
            }).catch(() => {
                list.innerHTML = '<div class="notification-item"><div><strong>Notifikasi gagal dimuat</strong><small>Periksa koneksi lalu coba lagi.</small></div></div>';
                refreshCount(notif);
            });
        });
    }

    document.querySelectorAll('[data-admin-notification]').forEach(function (notif) {
        const button = notif.querySelector('.admin-notification-btn');
        const markRead = notif.querySelector('.mark-read-btn');
        const deleteRead = notif.querySelector('.delete-read-btn');

        button.addEventListener('click', function (event) {
            event.stopPropagation();
            renderNotifications();
            notif.classList.toggle('open');
            button.setAttribute('aria-expanded', notif.classList.contains('open') ? 'true' : 'false');
        });

        markRead.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            const readIds = getStored('sipibsAdminReadNotifs');
            notif.querySelectorAll('.notification-item[data-notif-id]').forEach(item => {
                const id = item.dataset.notifId;
                if (id && !readIds.includes(id)) readIds.push(id);
                item.classList.remove('unread');
            });
            setStored('sipibsAdminReadNotifs', readIds);
            refreshCount(notif);
        });

        deleteRead.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            const deletedIds = getStored('sipibsAdminDeletedNotifs');
            notif.querySelectorAll('.notification-item:not(.unread)[data-notif-id]').forEach(item => {
                const id = item.dataset.notifId;
                if (id && !deletedIds.includes(id)) deletedIds.push(id);
                item.remove();
            });
            setStored('sipibsAdminDeletedNotifs', deletedIds);
            if (!notif.querySelectorAll('.notification-item').length) {
                const list = notif.querySelector('.notification-list');
                if (list) list.innerHTML = '<div class="notification-item"><div><strong>Tidak ada notifikasi baru</strong><small>Belum ada aktivitas peminjaman atau pengembalian.</small></div></div>';
            }
            refreshCount(notif);
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('[data-admin-notification]')) document.querySelectorAll('[data-admin-notification].open').forEach(item => item.classList.remove('open'));
    });

    renderNotifications();
    setInterval(renderNotifications, 30000);
})();