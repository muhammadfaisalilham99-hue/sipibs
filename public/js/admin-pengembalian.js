document.addEventListener('DOMContentLoaded', function () {
const STORAGE_KEY = 'sipibsReturnSubmissions';
    const body = document.getElementById('returnItemsBody');
    const toast = document.getElementById('returnToast');
    const archiveBody = document.getElementById('returnArchiveBody');
    const archiveBodyWrap = document.getElementById('archiveBody');
    const archiveCollapseBtn = document.getElementById('archiveCollapseBtn');
    const archiveCountBadge = document.getElementById('archiveCountBadge');

    const defaultPhoto = window.PENGEMBALIAN_DEFAULT_PHOTO || '/sipibs/public/images/PROYEKTOR EPSON.jpg';

const sampleItems = [];

    function getStoredData() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            let parsed = JSON.parse(raw || '[]');
            if (!Array.isArray(parsed)) parsed = [];
            parsed = parsed.filter(item => item && item.id !== 'RET-SAMPLE-1');
            parsed.forEach(item => {
                if (!Array.isArray(item.photos)) item.photos = [];
                if (!item.fineAmount) item.fineAmount = '30.000';
                if (!item.borrower || String(item.borrower).trim() === 'Admin SIPIBS') {
                    try {
                        const history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
                        const hit = history.find(x => x.id === item.loanId || x.id === item.id);
                        if (hit && (hit.nama || hit.borrower || hit.name)) {
                            item.borrower = hit.nama || hit.borrower || hit.name;
                        }
                    } catch (err) {}
                }
            });
            localStorage.setItem(STORAGE_KEY, JSON.stringify(parsed));
            return parsed;
        } catch (e) {
            return [];
        }
    }

    function saveData(data) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }

function showToast(text) {
        toast.textContent = text;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2600);
    }

    function pushUserNotification(notification) {
        const newNotif = {
            id: 'ntf-' + Date.now() + '-' + Math.floor(Math.random() * 99999),
            title: notification.title || 'Notifikasi',
            message: notification.message || '',
            icon: notification.icon || 'bi-bell',
            type: notification.type || 'blue',
            read: false,
            url: notification.url || '',
            time: new Date().toISOString()
        };
        if (typeof window.sipibsUserNotify === 'function') {
            window.sipibsUserNotify(newNotif);
            return;
        }
        try {
            const notifs = JSON.parse(localStorage.getItem('sipibsUserNotifications') || '[]')
                .filter(old => !(old.title === newNotif.title && old.message === newNotif.message));
            notifs.push(newNotif);
            localStorage.setItem('sipibsUserNotifications', JSON.stringify(notifs));
        } catch (err) {}
    }

    function userPageUrl(path) {
        const href = window.location.href || '';
        const idx = href.indexOf('/admin/');
        const base = idx > -1 ? href.substring(0, idx) : href.replace(/\/[^/]*$/, '');
        return base + path;
    }

function escapeHtml(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function getImageBase() {
        const def = window.PENGEMBALIAN_DEFAULT_PHOTO || '';
        const idx = def.lastIndexOf('/');
        if (idx > 0) return def.substring(0, idx);
        return '/sipibs/public/images';
    }

    function getItemPhoto(itemName, fallbackSrc) {
        const staticMap = {
            'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin': 'kabel vga.jpg'
        };
        let img = '';
        try {
            const items = JSON.parse(localStorage.getItem('sipibsMasterItems') || '[]');
            if (Array.isArray(items)) {
                const it = items.find(x => x.name === itemName);
                if (it && it.image) img = it.image;
            }
        } catch (e) {}
        if (!img) img = staticMap[itemName] || '';
        if (!img) return fallbackSrc;
        if (String(img).indexOf('http') === 0) return img;
        return getImageBase() + '/' + String(img).split('/').map(encodeURIComponent).join('/') + '?v=3';
    }

function renderTable(filter = '') {
        const search = String(filter || '').toLowerCase().trim();
        const data = getStoredData().filter(item => item.processed !== true);
        const items = data.filter(item => {
            const full = [item.loanId, item.borrower, item.itemName, item.serial].join(' ').toLowerCase();
            return !search || full.includes(search);
        });

        if (!items.length) {
            body.innerHTML = '<tr><td colspan="10" class="return-empty-row"><i class="bi bi-inbox"></i><br>Belum ada data pengembalian yang cocok.</td></tr>';
            return;
        }

        if (!items.length) {
            body.innerHTML = '<tr><td colspan="10" class="return-empty-row"><i class="bi bi-inbox"></i><br>Belum ada data pengembalian yang cocok.</td></tr>';
            return;
        }

        body.innerHTML = items.map((item, index) => {
            const noteVal = item.adminNote || '';
            const decVal = item.decision || '';
            const fineAmt = item.fineAmount || '30.000';
            const condText = item.condition || 'Baik (Fungsional & Bersih)';
            const isGood = condText.toLowerCase().includes('baik') || condText.toLowerCase().includes('normal');
            let photosHtml = '';
            if (Array.isArray(item.photos) && item.photos.length > 0) {
                photosHtml = item.photos.map((p, pIdx) => {
                    const pSrc = typeof p === 'string' ? p : (p.src || p.url || '');
                    const pName = (typeof p === 'object' && p.name) ? p.name : ('Bukti ' + (pIdx + 1));
                    return `
                        <div class="return-photo-item" title="${escapeHtml(pName)}">
                            <img src="${pSrc}" alt="${escapeHtml(pName)}" data-full="${pSrc}">
                            <span class="return-photo-zoom"><i class="bi bi-zoom-in"></i></span>
                        </div>`;
                }).join('');
            } else {
                const productSrc = getItemPhoto(item.itemName, getImageBase() + '/no-image.svg');
                photosHtml = `
                    <div class="return-photo-item return-photo-default" data-default="1" title="Foto produk">
                        <img src="${productSrc}" alt="Foto produk" data-full="${productSrc}">
                        <span class="return-photo-zoom"><i class="bi bi-zoom-in"></i></span>
                    </div>`;
            }

            const showFineAmount = decVal === 'Denda' || Boolean(item.fineType);

            return `
                <tr data-id="${escapeHtml(item.id)}">
                    <td>${index + 1}</td>
                    <td>
                        <div class="return-item-cell">
                            <img class="return-user-icon" src="${getItemPhoto(item.itemName, getImageBase() + '/PROFIL.png')}" alt="${escapeHtml(item.itemName)}" onerror="this.onerror=null; this.src='${getImageBase() + '/PROFIL.png'}';">
                            <div class="return-item-text">
                                <strong>${escapeHtml(item.itemName)}</strong>
                                <small>Kode: ${escapeHtml(item.serial || item.loanId)}</small>
                                <small>ID Peminjaman: ${escapeHtml(item.loanId)}</small>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(item.borrower || '-')}</td>
                    <td>${item.quantity || 1} Unit</td>
<td>
                        <div class="return-photo-cell">
                            <div class="return-photo-gallery">${photosHtml}</div>
                        </div>
                    </td>
                    <td>
                        <select class="return-select item-condition">
                            ${['Baik (Fungsional & Bersih)', 'Rusak Ringan', 'Rusak Berat', 'Hilang'].map(opt => `<option value="${opt}" ${condText === opt ? 'selected' : ''}>${opt}</option>`).join('')}
                        </select>
                        <div class="condition-hint"><i class="bi bi-check-circle-fill"></i><span>${isGood ? 'Kondisi baik' : 'Perlu penanganan'}</span></div>
                    </td>
                    <td>
                        <textarea class="return-textarea item-note" maxlength="255" placeholder="Masukkan catatan kondisi barang...">${escapeHtml(noteVal)}</textarea>
                        <span class="field-help">Berikan catatan terkait kondisi barang (sesuai hasil pemeriksaan).</span>
                        <span class="note-counter">${noteVal.length} / 255</span>
                    </td>
                    <td>
                        <span class="field-label">JENIS DENDA</span>
                        <select class="return-select item-fine">
                            <option value="">(Jenis Denda)</option>
                            ${['Keterlambatan Pengembalian', 'Kerusakan Barang', 'Kehilangan Barang'].map(opt => `<option value="${opt}" ${item.fineType === opt ? 'selected' : ''}>${opt}</option>`).join('')}
                        </select>
                        <div class="fine-amount-wrap" style="margin-top:8px; ${showFineAmount ? '' : 'display:none;'}">
                            <span class="field-label" style="margin-bottom:4px;">NOMINAL DENDA (RP)</span>
                            <input type="text" class="return-select item-fine-amount" placeholder="Contoh: 30.000" value="${escapeHtml(fineAmt)}">
                        </div>
                        <span class="field-help">Pilih jenis denda & nominal jika diperlukan.</span>
                    </td>
<td>
                        <div class="decision-wrap" data-value="${escapeHtml(decVal)}">
                            <button type="button" class="decision-option ${decVal === 'Diterima' ? 'active' : ''}" data-decision="Diterima">
                                <i class="bi bi-check-circle-fill"></i>
                                <div><strong>Diterima</strong><small>Barang diterima tanpa denda</small></div>
                            </button>
                            <button type="button" class="decision-option ${decVal === 'Ditolak' ? 'active' : ''}" data-decision="Ditolak">
                                <i class="bi bi-x-circle-fill"></i>
                                <div><strong>Ditolak</strong><small>Barang ditolak / tidak sesuai</small></div>
                            </button>
                            <button type="button" class="decision-option ${decVal === 'Denda' ? 'active' : ''}" data-decision="Denda">
                                <i class="bi bi-receipt"></i>
                                <div><strong>Denda</strong><small>Kenakan denda kepada peminjam</small></div>
                            </button>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="return-save-row-btn" title="Simpan data pengembalian ini"><i class="bi bi-floppy-fill"></i>Simpan Pengembalian</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function renderArchive() {
        const data = getStoredData().filter(item => item.processed === true)
            .sort(function (a, b) { return new Date(b.processedAt || 0) - new Date(a.processedAt || 0); });

        if (archiveCountBadge) {
            archiveCountBadge.textContent = data.length + ' pengembalian';
        }
        if (!archiveBody) return;

        if (!data.length) {
            archiveBody.innerHTML = '<tr><td colspan="9" class="return-empty-row"><i class="bi bi-archive"></i><br>Belum ada pengembalian yang diproses.</td></tr>';
            return;
        }

        const decisionMeta = {
            Diterima: { label: 'Diterima', icon: 'bi-check-circle-fill', cls: 'ok' },
            Ditolak: { label: 'Ditolak', icon: 'bi-x-circle-fill', cls: 'error' },
            Denda: { label: 'Denda', icon: 'bi-receipt', cls: 'warn' }
        };

        archiveBody.innerHTML = data.map((item, index) => {
            const dec = decisionMeta[item.decision] || decisionMeta.Diterima;
            const condText = item.condition || 'Baik (Fungsional & Bersih)';
            const isGood = condText.toLowerCase().includes('baik') || condText.toLowerCase().includes('normal');
            const fineAmt = item.fineAmount || '30.000';
            const fineCell = item.fineType
                ? ('<strong class="return-readonly-text">' + escapeHtml(item.fineType) + '</strong>' + (item.decision === 'Denda' ? '<div class="field-help">Rp ' + escapeHtml(fineAmt) + '</div>' : ''))
                : '<span class="return-readonly-text dim">—</span>';
            return `
                <tr class="archived" data-id="${escapeHtml(item.id)}">
                    <td>${index + 1}</td>
                    <td>
                        <div class="return-item-cell">
                            <img class="return-user-icon" src="${getItemPhoto(item.itemName, getImageBase() + '/PROFIL.png')}" alt="${escapeHtml(item.itemName)}" onerror="this.onerror=null; this.src='${getImageBase() + '/PROFIL.png'}';">
                            <div class="return-item-text">
                                <strong>${escapeHtml(item.itemName)}</strong>
                                <small>Kode: ${escapeHtml(item.serial || item.loanId)}</small>
                                <small>ID Peminjaman: ${escapeHtml(item.loanId)}</small>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(item.borrower || '-')}</td>
                    <td>${item.quantity || 1} Unit</td>
                    <td>
                        <strong class="return-readonly-text">${escapeHtml(condText)}</strong>
                        <div class="condition-hint"><i class="bi bi-check-circle-fill"></i><span>${isGood ? 'Kondisi baik' : 'Perlu penanganan'}</span></div>
                    </td>
                    <td><strong class="return-readonly-text">${escapeHtml(item.adminNote || '-')}</strong></td>
                    <td>${fineCell}</td>
                    <td><span class="return-decision-badge ${dec.cls}"><i class="bi ${dec.icon}"></i>${dec.label}</span></td>
                    <td>
                        <span class="return-processed-badge"><i class="bi bi-check2-circle"></i> Sudah diproses</span>
                        <div class="field-help" style="margin-top:4px;">${formatDateTime(item.processedAt || item.verifiedAt)}</div>
                    </td>
                </tr>`;
        }).join('');
    }

    function formatDateTime(iso) {
        if (!iso) return '-';
        const d = new Date(iso);
        if (isNaN(d.getTime())) return String(iso);
        return d.toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    body.addEventListener('input', function (e) {
        if (e.target.classList.contains('item-note')) {
            const len = e.target.value.length;
            const counter = e.target.parentElement.querySelector('.note-counter');
            if (counter) counter.textContent = `${len} / 255`;
        }
    });

    body.addEventListener('change', function (e) {
        if (e.target.classList.contains('item-fine')) {
            const row = e.target.closest('tr[data-id]');
            const fineWrap = row.querySelector('.fine-amount-wrap');
            if (fineWrap) {
                const decWrap = row.querySelector('.decision-wrap');
                const decVal = decWrap ? decWrap.dataset.value : '';
                if (e.target.value || decVal === 'Denda') {
                    fineWrap.style.display = '';
                } else {
                    fineWrap.style.display = 'none';
                }
}
        }
});

    body.addEventListener('click', function (e) {
        const img = e.target.closest('.return-photo-item img');
        if (img) {
            const fullSrc = img.dataset.full || img.src;
            const overlay = document.createElement('div');
            overlay.className = 'return-lightbox open';
            overlay.innerHTML = `<button type="button" class="lb-close" aria-label="Tutup" title="Tutup">&times;</button><img src="${fullSrc}" alt="Foto Barang yang Dikembalikan"><span class="lb-caption">Foto barang yang dikembalikan peminjam</span>`;
            overlay.addEventListener('click', () => overlay.remove());
            function closeOnEsc(event) {
                if (event.key === 'Escape') {
                    overlay.remove();
                    document.removeEventListener('keydown', closeOnEsc);
                }
            }
            document.addEventListener('keydown', closeOnEsc);
            overlay.addEventListener('click', function () {
                overlay.remove();
                document.removeEventListener('keydown', closeOnEsc);
            });
            document.body.appendChild(overlay);
            return;
        }

const opt = e.target.closest('.decision-option');
        if (opt) {
            const wrap = opt.closest('.decision-wrap');
            const row = opt.closest('tr[data-id]');
            const val = opt.dataset.decision;
            wrap.dataset.value = val;
            wrap.querySelectorAll('.decision-option').forEach(o => o.classList.toggle('active', o === opt));
            wrap.classList.add('selected');

            const fineWrap = row.querySelector('.fine-amount-wrap');
            const fineSelect = row.querySelector('.item-fine');
            if (fineWrap) {
                if (val === 'Denda' || (fineSelect && fineSelect.value)) {
                    fineWrap.style.display = '';
                    if (fineSelect && !fineSelect.value) fineSelect.value = 'Keterlambatan Pengembalian';
                } else {
                    fineWrap.style.display = 'none';
                }
            }
            return;
        }

        const saveRowBtn = e.target.closest('.return-save-row-btn');
        if (saveRowBtn) {
            saveReturnRows([saveRowBtn.closest('tr[data-id]')]);
            return;
        }
});

function saveReturnRows(rows) {
        rows = Array.from(rows || []);
        if (!rows.length) return;
        const currentData = getStoredData();

        rows.forEach(row => {
            const id = row.dataset.id;
            const item = currentData.find(x => x.id === id);
            if (item && item.processed) return;
            if (item) {
                item.condition = row.querySelector('.item-condition').value;
                item.adminNote = row.querySelector('.item-note').value.trim();
                item.fineType = row.querySelector('.item-fine').value;
item.fineAmount = row.querySelector('.item-fine-amount')?.value.trim() || '30.000';
                item.decision = row.querySelector('.decision-wrap').dataset.value || 'Diterima';
item.status = item.decision;
                item.verifiedAt = new Date().toISOString();
                item.processed = true;
                item.processedAt = new Date().toISOString();
                if (item.decision === 'Diterima') {
                    pushUserNotification({
                        title: 'Pengembalian Diterima',
                        message: 'Barang "' + item.itemName + '" telah diterima admin' + (item.adminNote ? ' (Catatan: ' + item.adminNote + ')' : '') + '. Terima kasih sudah mengembalikan barang.',
                        icon: 'bi-check-circle',
                        type: 'green',
                        url: userPageUrl('/riwayat-pinjam')
                    });
                }
                if (item.decision === 'Ditolak') {
                    pushUserNotification({
                        title: 'Pengembalian Ditolak',
                        message: 'Pengembalian barang "' + item.itemName + '" ditolak.' + (item.adminNote ? ' Catatan: ' + item.adminNote : ' Silakan hubungi admin untuk keterangan lebih lanjut.'),
                        icon: 'bi-x-circle',
                        type: 'red',
                        url: userPageUrl('/pengembalian-user')
                    });
                }
                if (item.decision === 'Diterima' || item.decision === 'Denda') {
                    const history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
                    let returnedQty = parseInt(item.quantity) || 1;
                    history.forEach(loan => {
                        if (loan.id === item.loanId || loan.id === item.id) {
                            loan.status = 'returned';
                            loan.returnedAt = new Date().toISOString();
                            returnedQty = parseInt(loan.jumlah) || returnedQty;
                        }
                    });
                    localStorage.setItem('sipibsLoanHistory', JSON.stringify(history));

                    const stocks = JSON.parse(localStorage.getItem('sipibsItemStock') || '{}');
                    const n = item.itemName;
                    if (n) {
                        stocks[n] = (stocks[n] !== undefined) ? (stocks[n] + returnedQty) : returnedQty;
                        localStorage.setItem('sipibsItemStock', JSON.stringify(stocks));
                    }

                    const decisionObj = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
                    if (decisionObj && decisionObj.request && (decisionObj.request.id === item.loanId || decisionObj.request.id === item.id)) {
                        decisionObj.status = 'returned';
                        localStorage.setItem('sipibsLoanDecision', JSON.stringify(decisionObj));
                    }
                }
                if (item.decision === 'Denda') {
                    const activeFine = {
                        id: item.id,
                        loanId: item.loanId || item.id,
                        borrower: item.borrower || 'Admin SIPIBS',
                        nis: item.serial || '12345678',
                        userClass: 'XI RPL 1',
                        itemName: item.itemName,
                        itemCode: item.serial || item.loanId,
                        loanDate: item.startDate || '2026-08-20',
                        dueDate: item.dueDate || '2026-08-22',
                        returnDate: item.submittedAt ? item.submittedAt.split('T')[0] : '2026-08-25',
                        fineType: item.fineType || 'Keterlambatan Pengembalian',
                        fineAmount: item.fineAmount || '30.000',
                        note: item.adminNote || 'Pelanggaran pengembalian barang.',
                        status: 'Belum Dibayar'
                    };
                    localStorage.setItem('sipibsActiveFine', JSON.stringify(activeFine));

                    const finesList = JSON.parse(localStorage.getItem('sipibsFines') || '[]');
                    const existingIdx = finesList.findIndex(f => f.id === item.id || f.itemCode === activeFine.itemCode);
                    if (existingIdx >= 0) {
                        finesList[existingIdx] = activeFine;
                    } else {
                        finesList.unshift(activeFine);
                    }
                    localStorage.setItem('sipibsFines', JSON.stringify(finesList));

                    pushUserNotification({
                        title: 'Pembayaran Denda',
                        message: 'Anda dikenakan denda ' + activeFine.fineType + ' sebesar Rp ' + String(activeFine.fineAmount).replace(/\D/g, '') + '. Klik untuk membayar.',
                        icon: 'bi-cash-coin',
                        type: 'red',
                        url: userPageUrl('/denda-user')
                    });
                }
            }
        });

saveData(currentData);
        renderTable(document.getElementById('returnSearchInput').value);
        renderArchive();
        showToast(rows.length === 1 ? 'Pengembalian berhasil disimpan! Notifikasi terkirim ke user.' : 'Pengembalian berhasil disimpan! Notifikasi terkirim ke user.');
    }

    document.getElementById('saveReturnBtn').addEventListener('click', function () {
        saveReturnRows(body.querySelectorAll('tr[data-id]'));
    });

    document.getElementById('cancelReturnBtn').addEventListener('click', function () {
        renderTable(document.getElementById('returnSearchInput').value);
        showToast('Perubahan dibatalkan.');
    });

    document.getElementById('helpTopBtn').addEventListener('click', function () {
        showToast('Panduan Pengembalian: Periksa kondisi barang, beri catatan, pilih keputusan lalu tekan Simpan.');
    });

    document.getElementById('returnSearchInput').addEventListener('input', function () {
        renderTable(this.value);
    });

if (archiveCollapseBtn && archiveBodyWrap) {
        archiveCollapseBtn.addEventListener('click', function () {
            const collapsed = archiveBodyWrap.classList.toggle('collapsed');
            archiveCollapseBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            const icon = archiveCollapseBtn.querySelector('i');
            if (icon) icon.className = collapsed ? 'bi bi-chevron-down' : 'bi bi-chevron-up';
        });
    }

    renderTable();
    renderArchive();
});


