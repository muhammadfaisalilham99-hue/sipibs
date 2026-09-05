document.addEventListener('DOMContentLoaded', function () {
    const defaultLoans = [];
    const dummyIds = ['PMJ-2026-01', 'PMJ-2026-02', 'PMJ-2026-03', 'PMJ-2026-04', 'PMJ-2026-05', 'PMJ-2026-06'];

    let currentTab = 'all';

    function getItemIconClass(itemName) {
        if (!itemName) return 'bi-box-seam';
        const n = itemName.toLowerCase();
        if (n.includes('laptop') || n.includes('lenovo') || n.includes('thinkpad')) return 'bi-laptop';
        if (n.includes('kamera') || n.includes('canon') || n.includes('camera')) return 'bi-camera';
        if (n.includes('proyektor') || n.includes('projector') || n.includes('epson')) return 'bi-easel';
        if (n.includes('mic') || n.includes('microphone') || n.includes('audio')) return 'bi-mic';
        if (n.includes('headset') || n.includes('logitech')) return 'bi-headphones';
        if (n.includes('mouse')) return 'bi-mouse';
        if (n.includes('keyboard')) return 'bi-keyboard';
        return 'bi-box-seam';
    }

    function getStoredJson(key, fallback) {
        try { return JSON.parse(localStorage.getItem(key) || 'null') || fallback; }
        catch (e) { return fallback; }
    }

    function normalizeUserRequest(userReq, status) {
        return {
            id: userReq.id,
            borrower: userReq.nama || 'Siswa / User',
            nim: 'NIM. ' + (userReq.nis || '-'),
            prodi: userReq.jurusan || 'Siswa SIPIBS',
            item: userReq.barang || 'Barang Inventaris',
            code: userReq.kode || (userReq.id ? userReq.id.substring(0, 8) : 'PMJ-USR'),
            category: userReq.kategori || 'Inventaris',
            stock: userReq.jumlah || 1,
            startDate: userReq.tanggalPinjam || '-',
            endDate: userReq.tanggalKembali || '-',
            purpose: userReq.keperluan || '-',
            status: status || userReq.status || 'pending',
            submittedAt: userReq.submittedAt ? new Date(userReq.submittedAt).toLocaleString('id-ID') : 'Baru saja',
            image: userReq.image || null
        };
    }

    function mergeUserRequests(loans) {
        // Remove old dummy items
        let cleanedLoans = loans.filter(item => !dummyIds.includes(item.id));

        const latestRequest = getStoredJson('sipibsLoanRequest', null);
        const decision = getStoredJson('sipibsLoanDecision', null);
        const history = getStoredJson('sipibsLoanHistory', []);
        const candidates = history.slice();
        if (latestRequest) candidates.unshift(latestRequest);

        candidates.forEach(req => {
            if (!req || !req.id) return;
            const status = decision && decision.request && decision.request.id === req.id ? decision.status : req.status;
            const normalized = normalizeUserRequest(req, status || 'pending');
            const found = cleanedLoans.findIndex(item => item.id === normalized.id);
            if (found >= 0) cleanedLoans[found] = Object.assign({}, cleanedLoans[found], normalized);
            else cleanedLoans.unshift(normalized);
        });
        return cleanedLoans;
    }

    function getAllLoans() {
        const saved = getStoredJson('sipibsAdminLoanList', []);
        const baseLoans = saved.filter(item => !dummyIds.includes(item.id));
        const merged = mergeUserRequests(baseLoans);
        localStorage.setItem('sipibsAdminLoanList', JSON.stringify(merged));
        return merged;
    }

    function saveAllLoans(loans) {
        const cleaned = loans.filter(item => !dummyIds.includes(item.id));
        localStorage.setItem('sipibsAdminLoanList', JSON.stringify(cleaned));
    }

    function updateCounts(loans) {
        document.getElementById('countAll').textContent = loans.length;
        document.getElementById('countPending').textContent = loans.filter(item => item.status === 'pending').length;
        document.getElementById('countApproved').textContent = loans.filter(item => item.status === 'approved').length;
        document.getElementById('countRejected').textContent = loans.filter(item => item.status === 'rejected').length;
        document.getElementById('countReturned').textContent = loans.filter(item => item.status === 'returned').length;
    }

    function statusText(status) {
        return { pending:'Menunggu Persetujuan', approved:'Disetujui', rejected:'Ditolak', returned:'Selesai' }[status] || status;
    }

    function actionHtml(item) {
        if (item.status === 'pending') {
            return '<button type="button" class="loan-action-btn accept" data-action="approved" data-id="' + item.id + '"><i class="bi bi-check-lg"></i> Terima</button>' +
                '<button type="button" class="loan-action-btn reject" data-action="rejected" data-id="' + item.id + '"><i class="bi bi-x-lg"></i> Tolak</button>';
        }
        return '<span class="loan-decision-label ' + item.status + '">' + statusText(item.status) + '</span>';
    }

function getLoanImageBase() {
        const def = window.PEMINJAMAN_DEFAULT_PHOTO || '';
        const idx = def.lastIndexOf('/');
        if (idx > 0) return def.substring(0, idx);
        return '/sipibs/public/images';
    }

    function getLoanItemPhoto(itemName, fallbackSrc) {
        const staticMap = {
            'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin': 'kabel vga.jpg',
            'Mouse HP USB-2': 'mouse HP.png',
            'Pen Wireless': 'Pointer.jpg'
        };
        let img = '';
        try {
            const items = JSON.parse(localStorage.getItem('sipibsMasterItems') || '[]');
            if (Array.isArray(items)) {
                const it = items.find(x => (x.name || x.nama) === itemName);
                if (it && (it.image || it.imageName)) img = it.image || it.imageName;
            }
        } catch (e) {}
        if (!img) img = staticMap[itemName] || '';
        if (!img) return fallbackSrc;
        if (String(img).indexOf('http') === 0 || String(img).indexOf('data:') === 0) return img;
        return getLoanImageBase() + '/' + String(img).split('/').map(encodeURIComponent).join('/') + '?v=3';
    }

    function itemThumb(item) {
        const iconClass = getItemIconClass(item.item);
        const noImg = getLoanImageBase() + '/no-image.svg';
        let src = getLoanItemPhoto(item.item, '');
        if (!src && item.image) {
            if (String(item.image).indexOf('http') === 0 || String(item.image).indexOf('data:') === 0) src = item.image;
            else src = getLoanImageBase() + '/' + String(item.image).split('/').map(encodeURIComponent).join('/') + '?v=3';
        }
        if (src) {
            return '<img src="' + src + '" alt="' + (item.item || '') + '" onerror="this.onerror=null;this.src=\'' + noImg + '\';">';
        }
        return '<i class="bi ' + iconClass + '"></i>';
    }

    function renderCards() {
        const loans = getAllLoans();
        updateCounts(loans);
        const key = (document.getElementById('loanSearch').value || '').toLowerCase().trim();
        const filtered = loans.filter(item => {
            const tabOk = currentTab === 'all' || item.status === currentTab;
            const searchText = [item.borrower, item.nim, item.prodi, item.item, item.code, item.category].join(' ').toLowerCase();
            return tabOk && (!key || searchText.includes(key));
        });

        const container = document.getElementById('loanCardsContainer');
        if (!filtered.length) {
            container.innerHTML = '<div class="loan-empty"><i class="bi bi-inbox"></i><strong>Tidak ada permintaan peminjaman</strong><br><small>Belum ada pengajuan peminjaman dari user.</small></div>';
            document.getElementById('paginationInfo').textContent = 'Menampilkan 0 - 0 dari 0 data';
            return;
        }

        container.innerHTML = filtered.map(item => '<div class="loan-card-item" data-id="' + item.id + '">' +
            '<span class="loan-status-pill ' + item.status + '">' + statusText(item.status) + '</span>' +
            '<div class="loan-user-col"><div class="loan-user-avatar"><i class="bi bi-person-fill"></i></div><div class="loan-user-text"><strong>' + item.borrower + '</strong><span>' + item.nim + '</span><span>' + item.prodi + '</span></div></div>' +
            '<div class="loan-item-col"><div class="loan-item-thumb">' + itemThumb(item) + '</div><div class="loan-item-text"><strong>' + item.item + '</strong><div class="loan-item-code"><span>' + item.code + '</span><span class="loan-cat-badge">' + item.category + '</span></div><span class="loan-stock">Stok tersedia: ' + item.stock + '</span></div></div>' +
            '<div class="loan-date-col"><div class="loan-date-row"><i class="bi bi-calendar-event"></i><span class="loan-date-label">Tanggal Pinjam</span><span class="loan-date-value">' + item.startDate + '</span></div><div class="loan-date-row"><i class="bi bi-calendar-check"></i><span class="loan-date-label">Tanggal Kembali</span><span class="loan-date-value">' + item.endDate + '</span></div><div class="loan-date-row"><i class="bi bi-journal-text"></i><span class="loan-date-label">Keperluan</span><span class="loan-date-value">' + item.purpose + '</span></div></div>' +
            '<div class="loan-action-col">' + actionHtml(item) + '<span class="loan-submitted">Diajukan: ' + item.submittedAt + '</span></div>' +
            '</div>').join('');
        document.getElementById('paginationInfo').textContent = 'Menampilkan 1 - ' + filtered.length + ' dari ' + filtered.length + ' data';
    }

    function syncDecision(id, status, loans) {
        const userReq = getStoredJson('sipibsLoanRequest', null);
        if (userReq && userReq.id === id) {
            userReq.status = status;
            userReq.decidedAt = new Date().toISOString();
            localStorage.setItem('sipibsLoanRequest', JSON.stringify(userReq));
            localStorage.setItem('sipibsLoanDecision', JSON.stringify({ status: status, request: userReq, decidedAt: userReq.decidedAt }));
            const notifications = getStoredJson('sipibsUserNotifications', []);
            const notifId = 'loan-decision-' + id + '-' + status;
            const approved = status === 'approved';
            if (!notifications.some(item => item.id === notifId)) {
                notifications.unshift({
                    id: notifId,
                    title: approved ? 'Peminjaman Disetujui' : 'Peminjaman Ditolak',
                    message: (userReq.barang || 'Barang') + (approved ? ' disetujui admin. Klik untuk melihat bukti peminjaman.' : ' ditolak admin. Klik untuk melihat detail.'),
                    icon: approved ? 'bi-check-circle' : 'bi-x-circle',
                    type: approved ? 'green' : 'red',
                    read: false,
                    loanStatus: status,
                    loanId: id,
                    url: approved ? (userReq.downloadUrl || userReq.detailUrl || '/peminjaman-user') : (userReq.detailUrl || '/peminjaman-user'),
                    time: userReq.decidedAt
                });
                localStorage.setItem('sipibsUserNotifications', JSON.stringify(notifications.slice(0, 20)));
            }
        }
        const history = getStoredJson('sipibsLoanHistory', []);
        history.forEach(item => { if (item.id === id) item.status = status; });
        localStorage.setItem('sipibsLoanHistory', JSON.stringify(history));
    }

    document.getElementById('loanTabs').addEventListener('click', function (event) {
        const button = event.target.closest('.loan-tab-btn');
        if (!button) return;
        document.querySelectorAll('.loan-tab-btn').forEach(item => item.classList.remove('active'));
        button.classList.add('active');
        currentTab = button.dataset.tab;
        renderCards();
    });

    document.getElementById('loanSearch').addEventListener('input', renderCards);
    document.getElementById('loanCardsContainer').addEventListener('click', function (event) {
        const button = event.target.closest('[data-action]');
        if (!button) return;
        const loans = getAllLoans();
        const loan = loans.find(item => item.id === button.dataset.id);
        if (!loan) return;
        loan.status = button.dataset.action;
        saveAllLoans(loans);
        syncDecision(loan.id, loan.status, loans);
        renderCards();
    });

    window.addEventListener('storage', renderCards);
    renderCards();
});
