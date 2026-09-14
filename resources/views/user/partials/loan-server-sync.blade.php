<script>
(function () {
    function getJson(key, fb) {
        try { return JSON.parse(localStorage.getItem(key) || 'null') || fb; }
        catch (e) { return fb; }
    }
    function mapStatus(s) {
        return { 'menunggu':'pending', 'disetujui':'approved', 'dipinjam':'approved', 'ditolak':'rejected', 'dikembalikan':'returned', 'terlambat':'returned' }[s] || 'pending';
    }
    function localDate(v) {
        const p = String(v || '').split('/');
        return p.length === 3 ? p[0] + p[1] + p[2] : String(v || '').replace(/-/g, '');
    }
function signature(item) {
            const nis = String(item.nis || item.identity_number || '').replace(/\D/g, '');
            function normDate(v) {
                const s = String(v || '').trim();
                if (!s) return '';
                const m = s.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
                if (m) return m[3] + m[2] + m[1];
                return s.replace(/[^0-9]/g, '');
            }
            return [nis, String(item.barang || item.item || '').toLowerCase(), normDate(item.tanggalPinjam), normDate(item.tanggalKembali)].join('|');
        }
        function trackLoanStatus(x) { return String(x && x.status || '').toLowerCase(); }
        function loanIsEnded(s) { return ['returned', 'dikembalikan', 'diterima', 'selesai', 'bermasalah', 'complete', 'completed'].indexOf(s) !== -1; }
        function sameLoan(a, b) {
            if (!a || !b) return false;
            if (a.borrowingId != null && b.borrowingId != null && Number(a.borrowingId) === Number(b.borrowingId)) return true;
            if (a.id && b.id && String(a.id) === String(b.id)) return true;
            return signature(a) === signature(b);
        }
        function dedupeHistory(list) {
            function strength(x) { return (x.borrowingId != null ? 2 : 0) + (loanIsEnded(trackLoanStatus(x)) ? 0 : 1); }
            const out = [];
            (Array.isArray(list) ? list : []).forEach(function (it) {
                if (!it) return;
                let idx = -1;
                out.forEach(function (x, i) { if (idx === -1 && sameLoan(x, it)) idx = i; });
                if (idx === -1) { out.push(it); return; }
                if (strength(it) > strength(out[idx])) out[idx] = it;
            });
            return out;
        }
    function run(name) {
        if (window[name]) { try { window[name](); } catch (e) {} }
    }
    function synced() {
        run('sipibsAfterLoanSync');
        run('renderDashboardLoans');
        run('renderLatestLoanHistory');
        run('renderLoansFromHistory');
        run('renderLatestReturnLoan');
        run('renderReturnHistory');
    }

    const apiRoot = (window.__apiBase || @json(rtrim(url('/api'), '/'))).replace(/\/$/, '');
    fetch(apiRoot + '/peminjaman/saya', {
        method: 'GET',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (res) { return res.json().catch(function () { return null; }); })
    .then(function (data) {
        const borrowings = data && Array.isArray(data.borrowings) ? data.borrowings : [];
        if (!borrowings.length) { synced(); return; }

        let changed = false;
        const request = getJson('sipibsLoanRequest', null);
        let decision = getJson('sipibsLoanDecision', null);
        let history = getJson('sipibsLoanHistory', []);
        if (!Array.isArray(history)) history = [];

        function findMatch(b) {
            let hit = null;
            history.forEach(function (it) {
                if (!it || hit) return;
                if (Number(it.borrowingId) === Number(b.id)) { hit = it; return; }
                const st = String(it.status || '').toLowerCase();
                const ended = ['returned', 'dikembalikan', 'diterima', 'selesai', 'bermasalah', 'complete', 'completed'].includes(st);
                if (!ended && signature(it) === signature(b)) hit = it;
            });
            if (!hit && request) {
                if (Number(request.borrowingId) === Number(b.id)) hit = request;
                else {
                    const st = String(request.status || '').toLowerCase();
                    const ended = ['returned', 'dikembalikan', 'diterima', 'selesai', 'bermasalah', 'complete', 'completed'].includes(st);
                    if (!ended && signature(request) === signature(b)) hit = request;
                }
            }
            return hit;
        }

        borrowings.forEach(function (b) {
            const s = mapStatus(b.status);
            const hit = findMatch(b);
            if (!hit) {
                if (s === 'pending') return;
                history.unshift({
                    id: 'PMJ-' + b.id,
                    borrowingId: b.id,
                    nama: b.borrower,
                    nis: b.identity_number,
                    barang: b.barang,
                    kategori: 'Inventaris',
                    jumlah: b.quantity,
                    tanggalPinjam: b.tanggalPinjam,
                    tanggalKembali: b.tanggalKembali,
                    keperluan: b.purpose || '',
                    status: s,
                    submittedAt: new Date().toISOString(),
                    decidedAt: new Date().toISOString()
                });
                changed = true;
                return;
            }
            if (String(hit.borrowingId || '') !== String(b.id)) {
                hit.borrowingId = Number(b.id);
                changed = true;
            }
            if (String(hit.status || 'pending').toLowerCase() !== s) {
                hit.status = s;
                hit.decidedAt = new Date().toISOString();
                changed = true;
                if (decision && decision.request && (Number(decision.request.borrowingId) === Number(b.id) || decision.request.id === hit.id)) {
                    decision.status = s;
                    decision.decidedAt = hit.decidedAt;
                }
            }
        });

        const linked = request ? borrowings.find(function (b) { return Number(b.id) === Number(request.borrowingId); }) : null;
        if (request && linked) {
            const s = mapStatus(linked.status);
            if (s === 'approved' || s === 'rejected') {
                request.status = s;
                if (!decision || !decision.request || decision.request.id !== request.id) {
                    decision = { status: s, request: request, decidedAt: new Date().toISOString() };
                } else if (decision.status !== s) {
                    decision.status = s;
                    decision.decidedAt = new Date().toISOString();
                }
                localStorage.setItem('sipibsLoanRequest', JSON.stringify(request));
                localStorage.setItem('sipibsLoanDecision', JSON.stringify(decision));
                const notifications = getJson('sipibsUserNotifications', []);
                const notifId = 'loan-decision-server-' + linked.id;
                if (!notifications.some(function (it) { return it.id === notifId; })) {
                    const approved = s === 'approved';
                    notifications.unshift({
                        id: notifId,
                        title: approved ? 'Peminjaman Disetujui' : 'Peminjaman Ditolak',
                        message: (request.barang || 'Barang') + (approved ? ' disetujui admin. Anda dapat meminjam barang tersebut.' : ' ditolak admin. Anda tidak dapat meminjam barang tersebut.'),
                        icon: approved ? 'bi-check-circle' : 'bi-x-circle',
                        type: approved ? 'green' : 'red',
                        read: false,
                        loanStatus: s,
                        loanId: request.id,
                        url: approved ? (request.downloadUrl || request.detailUrl || '/peminjaman-user') : (request.detailUrl || '/peminjaman-user'),
                        time: new Date().toISOString()
                    });
                    localStorage.setItem('sipibsUserNotifications', JSON.stringify(notifications.slice(0, 20)));
                }
                changed = true;
            }
        }

        const cleaned = dedupeHistory(history);
        if (cleaned.length !== history.length) changed = true;
        history = cleaned;

        if (changed) {
            localStorage.setItem('sipibsLoanHistory', JSON.stringify(history));
            const returnList = history.filter(function (it) {
                const st = String(it.status || '').toLowerCase();
                return st !== 'ditolak' && st !== 'rejected';
            });
            localStorage.setItem('sipibsReturnLoanItems', JSON.stringify(returnList));
        }
        synced();
    })
    .catch(function () { synced(); });
})();
</script>