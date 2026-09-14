document.addEventListener('DOMContentLoaded', function () {
    const defaultLoans = [];
    const dummyIds = ['PMJ-2026-01', 'PMJ-2026-02', 'PMJ-2026-03', 'PMJ-2026-04', 'PMJ-2026-05', 'PMJ-2026-06'];

    let currentTab = 'all';
    window.__dbLoans = window.__dbLoans || [];
    const apiRoot = (window.__apiBase || (window.location.origin + '/api')).replace(/\/$/, '');

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
            borrowingId: userReq.borrowingId || null,
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

    function dbStatusToUi(status) {
        return {
            'menunggu': 'pending',
            'disetujui': 'approved',
            'dipinjam': 'approved',
            'ditolak': 'rejected',
            'dikembalikan': 'returned',
            'terlambat': 'returned'
        }[status] || 'pending';
    }

    function normalizeDbLoan(b) {
        const identity = b.identity_number && b.identity_number !== '-' ? b.identity_number : '-';
        return {
            id: 'db-' + b.id,
            borrowingId: b.id,
            borrower: b.borrower || '-',
            nim: identity !== '-' ? 'NIM. ' + identity : '-',
            prodi: 'Siswa SIPIBS',
            item: b.barang || 'Barang Inventaris',
            code: b.code || b.serial || '-',
            category: 'Inventaris',
            stock: b.quantity || 1,
            startDate: b.tanggalPinjam || '-',
            endDate: b.tanggalKembali || '-',
            purpose: b.purpose || '-',
            status: dbStatusToUi(b.status),
            submittedAt: 'Baru saja',
            image: b.image || null,
            identityDocument: b.identity_document || null
        };
    }

    function loanSignature(l) {
        const normNim = (v) => String(v || '').replace(/^(NIM\.|NIS\.)\s*/i, '').trim().toLowerCase();
        return [normNim(l.nim || l.nis), String(l.item || l.barang || '').toLowerCase(), String(l.stock || l.quantity || l.jumlah || ''), String(l.startDate || l.tanggalPinjam || ''), String(l.endDate || l.tanggalKembali || '')].join('|');
    }

    function getAllLoans() {
        const saved = getStoredJson('sipibsAdminLoanList', []);
        const baseLoans = saved.filter(item => !dummyIds.includes(item.id));
        const merged = mergeUserRequests(baseLoans);

        const dbLoans = (window.__dbLoans || []).map(normalizeDbLoan);
        const dbById = {};
        dbLoans.forEach(dbItem => { if (dbItem.borrowingId) dbById['bid:' + String(dbItem.borrowingId)] = dbItem; });

        const preferred = merged.map(item => {
            if (item.borrowingId && dbById['bid:' + String(item.borrowingId)]) {
                return Object.assign({}, item, dbById['bid:' + String(item.borrowingId)]);
            }
            return item;
        });

        const combined = dbLoans.concat(preferred);
        const seen = {};
        const deduped = combined.filter(item => {
            // Prioritas: borrowingId (DB) > signature (Local/Sync)
            const key = item.borrowingId ? 'bid:' + String(item.borrowingId) : 'sig:' + loanSignature(item);
            
            // Jika kita sudah melihat signature ini tapi item saat ini punya borrowingId (data nyata DB),
            // kita harus mengganti data local tersebut atau mengabaikan duplikat signature jika DB sudah ada.
            if (seen[key]) return false;
            
            // Tambahan: jika item ini punya borrowingId, tandai juga signature-nya sebagai 'seen'
            // agar data local dengan signature yang sama tidak muncul double.
            if (item.borrowingId) {
                seen['sig:' + loanSignature(item)] = true;
            }

            seen[key] = true;
            return true;
        });

        localStorage.setItem('sipibsAdminLoanList', JSON.stringify(deduped.filter(item => !item.borrowingId)));
        return deduped;
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
        const api = String(window.__apiBase || (window.location.origin + '/api')).replace(/\/$/, '');
        return api.replace(/\/api$/, '') + '/images';
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
        if (String(img).indexOf('/') === 0) return window.location.origin + img;
        return getLoanImageBase() + '/' + String(img).split('/').map(encodeURIComponent).join('/') + '?v=3';
    }

    function itemThumb(item) {
        const iconClass = getItemIconClass(item.item);
        const noImg = getLoanImageBase() + '/no-image.svg';
        let src = '';
        const photo = String(item.image || '').trim();
        if (photo) {
            if (photo.indexOf('http://') === 0 || photo.indexOf('https://') === 0 || photo.indexOf('data:') === 0) src = photo;
            else if (photo.indexOf('/') === 0) src = window.location.origin + photo;
            else src = getLoanImageBase() + '/' + photo.split('/').map(encodeURIComponent).join('/') + '?v=3';
        }
        if (!src) src = getLoanItemPhoto(item.item, '');
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
            '<div class="loan-user-col"><div class="loan-user-avatar"><i class="bi bi-person-fill"></i></div><div class="loan-user-text"><strong>' + item.borrower + '</strong><span>' + item.nim + '</span><span>' + item.prodi + '</span>' + (item.identityDocument ? '<a href="' + item.identityDocument + '" target="_blank" rel="noopener">Lihat KTP/Kartu Pelajar</a>' : '') + '</div></div>' +
            '<div class="loan-item-col"><div class="loan-item-thumb">' + itemThumb(item) + '</div><div class="loan-item-text"><strong>' + item.item + '</strong><div class="loan-item-code"><span>' + item.code + '</span><span class="loan-cat-badge">' + item.category + '</span></div><span class="loan-stock">Stok tersedia: ' + item.stock + '</span></div></div>' +
            '<div class="loan-date-col"><div class="loan-date-row"><i class="bi bi-calendar-event"></i><span class="loan-date-label">Tanggal Pinjam</span><span class="loan-date-value">' + item.startDate + '</span></div><div class="loan-date-row"><i class="bi bi-calendar-check"></i><span class="loan-date-label">Tanggal Kembali</span><span class="loan-date-value">' + item.endDate + '</span></div><div class="loan-date-row"><i class="bi bi-journal-text"></i><span class="loan-date-label">Keperluan</span><span class="loan-date-value">' + item.purpose + '</span></div></div>' +
            '<div class="loan-action-col">' + actionHtml(item) + '<span class="loan-submitted">Diajukan: ' + item.submittedAt + '</span></div>' +
            '</div>').join('');
        document.getElementById('paginationInfo').textContent = 'Menampilkan 1 - ' + filtered.length + ' dari ' + filtered.length + ' data';
    }

    function showLoanToast(message, type) {
        let toast = document.getElementById('loanDecideToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'loanDecideToast';
            toast.style.cssText = 'position:fixed;top:22px;right:22px;z-index:99999;padding:14px 20px;border-radius:12px;color:#fff;font-size:13px;font-weight:700;box-shadow:0 10px 30px rgba(15,23,42,.28);display:flex;align-items:center;gap:10px;opacity:0;transform:translateY(-8px);transition:all .25s ease;';
            document.body.appendChild(toast);
        }
        toast.textContent = '';
        const icon = document.createElement('i');
        icon.className = 'bi ' + (type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill');
        toast.appendChild(icon);
        toast.appendChild(document.createTextNode(message));
        toast.style.background = type === 'success' ? '#16a34a' : '#dc2626';
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        clearTimeout(toast._t);
        toast._t = setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
        }, 3000);
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

    function syncDbDecision(borrowingId, action) {
        const status = action;
        const req = getStoredJson('sipibsLoanRequest', null);
        if (req && Number(req.borrowingId) === Number(borrowingId)) {
            req.status = status;
            req.decidedAt = new Date().toISOString();
            localStorage.setItem('sipibsLoanRequest', JSON.stringify(req));
            localStorage.setItem('sipibsLoanDecision', JSON.stringify({ status: status, request: req, decidedAt: req.decidedAt }));
            const notifications = getStoredJson('sipibsUserNotifications', []);
            const notifId = 'loan-decision-' + req.id + '-' + status;
            const approved = status === 'approved';
            if (!notifications.some(item => item.id === notifId)) {
                notifications.unshift({
                    id: notifId,
                    title: approved ? 'Peminjaman Disetujui' : 'Peminjaman Ditolak',
                    message: (req.barang || 'Barang') + (approved ? ' disetujui admin. Klik untuk melihat bukti peminjaman.' : ' ditolak admin. Klik untuk melihat detail.'),
                    icon: approved ? 'bi-check-circle' : 'bi-x-circle',
                    type: approved ? 'green' : 'red',
                    read: false,
                    loanStatus: status,
                    loanId: req.id,
                    url: approved ? (req.downloadUrl || req.detailUrl || '/peminjaman-user') : (req.detailUrl || '/peminjaman-user'),
                    time: req.decidedAt
                });
                localStorage.setItem('sipibsUserNotifications', JSON.stringify(notifications.slice(0, 20)));
            }
        }
        const history = getStoredJson('sipibsLoanHistory', []);
        const changed = history.some(item => item && Number(item.borrowingId) === Number(borrowingId));
        history.forEach(item => { if (item && Number(item.borrowingId) === Number(borrowingId)) item.status = status; });
        if (changed) localStorage.setItem('sipibsLoanHistory', JSON.stringify(history));
    }

    function decideDbLoan(loan, action, loans) {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        fetch(apiRoot + '/peminjaman/' + loan.borrowingId + '/decide', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : ''
            },
            body: JSON.stringify({ action: action })
        })
        .then(function (res) { return res.json().catch(function () { return null; }); })
        .then(function (data) {
            if (data && data.borrowing) {
                const list = window.__dbLoans || [];
                const idx = list.findIndex(function (b) { return String(b.id) === String(data.borrowing.id); });
                if (idx > -1) list[idx] = data.borrowing; else list.unshift(data.borrowing);
                window.__dbLoans = list;
                syncDbDecision(data.borrowing.id, action);
                if (data.message) showLoanToast(data.message, action === 'approved' ? 'success' : 'error');
            }
            renderCards();
        })
        .catch(function () { renderCards(); });
    }

    function loadDbLoans() {
        const csrfMeta = document.querySelector('meta[name=csrf-token]');
        fetch(apiRoot + '/peminjaman/list', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : '' }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            window.__dbLoans = (data && Array.isArray(data.borrowings)) ? data.borrowings : [];
            renderCards();
        })
        .catch(function () { renderCards(); });
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
        if (loan.borrowingId) {
            decideDbLoan(loan, button.dataset.action, loans);
        } else {
            loan.status = button.dataset.action;
            saveAllLoans(loans);
            syncDecision(loan.id, loan.status, loans);
            showLoanToast(loan.status === 'approved' ? 'Peminjaman disetujui.' : 'Peminjaman ditolak.', loan.status === 'approved' ? 'success' : 'error');
            renderCards();
        }
    });

    window.addEventListener('storage', renderCards);
    renderCards();
    loadDbLoans();
    setInterval(loadDbLoans, 5000);
});