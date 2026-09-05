
    const invToggle = document.getElementById('inventaris-toggle');
    const invSub = document.getElementById('inventaris-sub');
    if (localStorage.getItem('invOpen') === '0') {
        invSub.classList.add('collapsed');
        invToggle.classList.remove('open');
    } else {
        invSub.classList.remove('collapsed');
        invToggle.classList.add('open');
    }
    invToggle.addEventListener('click', function (e) {
        e.preventDefault();
        invSub.classList.toggle('collapsed');
        invToggle.classList.toggle('open', !invSub.classList.contains('collapsed'));
        localStorage.setItem('invOpen', invSub.classList.contains('collapsed') ? '0' : '1');
    });
    invSub.querySelectorAll('.nav-sub-item').forEach(function(link) {
        link.addEventListener('click', function() {
            localStorage.setItem('invOpen', '1');
        });
    });

    const IMG_BASE = ""sample"";

    window.__csrf = ""sample"";

    function getItemIcon(name) {
        if (!name) return 'bi-box-seam';
        const n = name.toLowerCase();
        if (n.includes('kamera') || n.includes('canon') || n.includes('dslr') || n.includes('eos')) return 'bi-camera-fill';
        if (n.includes('mikroskop') || n.includes('olympus')) return 'bi-microscope';
        if (n.includes('mouse')) return 'bi-mouse';
        if (n.includes('keyboard')) return 'bi-keyboard';
        if (n.includes('laptop') || n.includes('lenovo') || n.includes('notebook') || n.includes('dell')) return 'bi-laptop';
        if (n.includes('headset') || n.includes('headphone') || n.includes('logitech')) return 'bi-headphones';
        if (n.includes('webcam') || n.includes('video camera')) return 'bi-camera-video';
        if (n.includes('projector') || n.includes('proyektor') || n.includes('epson')) return 'bi-easel';
        if (n.includes('lan') || n.includes('cat6') || n.includes('networking') || n.includes('network cable')) return 'bi-ethernet';
        if (n.includes('hdmi')) return 'bi-usb-c';
        if (n.includes('vga')) return 'bi-plug';
        if (n.includes('kabel')) return 'bi-usb-plug';
        if (n.includes('pointer') || n.includes('remote') || n.includes('pen wireless')) return 'bi-broadcast-pin';
        if (n.includes('stop kontak') || n.includes('kontak')) return 'bi-outlet';
        if (n.includes('tester')) return 'bi-router';
        if (n.includes('crimping') || n.includes('tang')) return 'bi-tools';
        if (n.includes('jbl') || n.includes('speaker') || n.includes('boombox')) return 'bi-speaker';
        if (n.includes('tripod') || n.includes('promon')) return 'bi-camera-reels';
        return 'bi-box-seam';
    }

    function getItemImageName(name) {
        try {
            const items = JSON.parse(localStorage.getItem('sipibsMasterItems') || '[]');
            if (Array.isArray(items)) {
                const it = items.find(x => x.name === name);
                if (it && it.image) return it.image;
            }
        } catch (e) {}
        const map = {
            'Laptop Lenovo Ideapad Slim 3': 'Lenovo Ideapad Slim 3.jpg',
            'Mouse HP USB-2': 'mouse HP.png',
            'Keyboard NuPhy Air75': 'keyboard kantor.png',
            'Headset Logitech G 432 7.1': 'HEADSET LOGITECH.png',
            '4K Webcam 1080P 60fps Mini Video Camera': 'WEBCAM.jpg',
            'Kamera DSLR Canon EOS 80D': 'WEBCAM.jpg',
            'Epson EX3240 SVGA 3LCD Projector 3200': 'PROYEKTOR EPSON.jpg',
            'Kabel Black High Speed 1.4 Version Gold-Plated HDMI': 'kabel_hdmi.png',
            'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin': 'kabel vga.jpg',
            'Kabel LAN CAT6 UTP Cable Networking': 'KABEL LAN.jpg',
            'Kabel HDMI to VGA Adapter Gold Plated': 'KABEL HDMI TO VGA.jpg',
            'Pen Wireless Remote Controller Laser Pointer': 'Pointer.jpg',
            'Stop Kontak': 'STOP KONTAK.jpg',
            '2pcs Multifunctional network tester 468 network cable': 'LAN tester.jpg',
            'Tang Crimping Tool RJ45 RJ11 HT-200R': 'Tang Crimping.jpg',
            'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth': 'speaker portable.jpg'
        };
        return map[name] || '';
    }

    function getReturnImageSrc(image) {
        if (!image) return '';
        if (/^(https?:)?\/\//.test(image) || image.indexOf('data:image/') === 0) return image;
        return IMG_BASE + '/' + encodeURIComponent(image) + '?v=3';
    }

    function parseReturnDate(dateValue) {
        if (!dateValue) return null;
        if (dateValue.includes('/')) {
            const parts = dateValue.split('/');
            dateValue = parts[2] + '-' + parts[1] + '-' + parts[0];
        }
        const date = new Date(dateValue + 'T00:00:00');
        if (Number.isNaN(date.getTime())) return null;
        date.setHours(0, 0, 0, 0);
        return date;
    }

    function formatReturnDate(dateValue) {
        if (!dateValue) return '-';
        const date = parseReturnDate(dateValue);
        if (!date) return dateValue;
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const d = String(date.getDate());
        const m = months[date.getMonth()];
        const y = date.getFullYear();
        return d + ' ' + m + ' ' + y;
    }

    function getDueStatus(dueDateValue, loanId) {
        if (loanId === 'B802931-B') {
            return {
                late: true,
                pillHtml: '<span class="return-due-pill">TERLAMBAT</span>'
            };
        }
        if (loanId === 'OLY-X300') {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SISA 1 HARI</span>'
            };
        }

        const due = parseReturnDate(dueDateValue);
        if (!due) {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SIAP DIKEMBALIKAN</span>'
            };
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const diffTime = due.getTime() - today.getTime();
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays < 0) {
            return {
                late: true,
                pillHtml: '<span class="return-due-pill">TERLAMBAT</span>'
            };
        } else if (diffDays === 0) {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">HARI INI</span>'
            };
        } else if (diffDays === 1) {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SISA 1 HARI</span>'
            };
        } else {
            return {
                late: false,
                pillHtml: '<span class="return-due-pill safe" style="font-size:10px;font-weight:700;letter-spacing:.04em;display:block;margin-top:3px;color:#64748b;">SISA ' + diffDays + ' HARI</span>'
            };
        }
    }

    function buildReturnRow(loan) {
        const itemName = loan.barang || loan.item || loan.itemName || '-';
        const startDate = loan.tanggalPinjam || loan.startDate || loan.pinjam || '-';
        const dueDate = loan.tanggalKembali || loan.endDate || loan.dueDate || loan.batas || '-';
        const itemImage = loan.image || getItemImageName(itemName);
        const loanId = loan.id || 'PMJ-2025-00067';
        const dueStatus = getDueStatus(dueDate, loanId);

        return {
            id: loanId,
            name: itemName,
            serial: loan.serial || loan.nis || loan.nim || loan.kode || loan.code || loan.id || '-',
            pinjam: formatReturnDate(startDate),
            batas: formatReturnDate(dueDate),
            status: dueStatus.late ? 'TERLAMBAT' : 'SIAP DIKEMBALIKAN',
            late: dueStatus.late,
            duePillHtml: dueStatus.pillHtml,
            icon: getItemIcon(itemName),
            image: itemImage,
            fromHistory: !!loan.fromHistory,
            approved: true
        };
    }

    function getRawLoanHistory() {
        let history = [];
        try { history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]'); } catch (e) {}
        if (!Array.isArray(history)) history = [];

        try {
            const request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
            if (request && request.barang && !history.some(loan => String(loan.id || '') === String(request.id || ''))) {
                history.unshift(request);
            }
        } catch (e) {}

        try {
            const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
            const request = decision && decision.request ? decision.request : null;
            if (request && request.barang && !history.some(loan => String(loan.id || '') === String(request.id || ''))) {
                history.unshift(request);
            }
        } catch (e) {}

        return history;
    }

    function getStaticReturnedIds() {
        try {
            const list = JSON.parse(localStorage.getItem('sipibsStaticReturnedIds') || '[]');
            return Array.isArray(list) ? list : [];
        } catch (e) { return []; }
    }

    function getSubmittedReturnLoanIds() {
        try {
            const submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]');
            if (!Array.isArray(submissions)) return [];
            return submissions.map(item => String(item.loanId || item.id || '')).filter(Boolean);
        } catch (e) { return []; }
    }

    function saveStaticReturnedIds(list) {
        try {
            localStorage.setItem('sipibsStaticReturnedIds', JSON.stringify(Array.isArray(list) ? list : []));
        } catch (e) {}
    }

    function getDefaultBorrowedReturnLoans() {
        return [
            {
                id: 'B802931-B',
                nama: 'Ahmad Fauzi',
                nis: 'B802931-B',
                barang: 'Kamera DSLR Canon EOS 80D',
                serial: 'B802931-B',
                kategori: 'Multimedia',
                jumlah: 1,
                tanggalPinjam: '24/10/2023',
                tanggalKembali: '26/10/2023',
                status: 'Dipinjam'
            },
            {
                id: 'OLY-X300',
                nama: 'Siti Rahma',
                nis: 'OLY-X300',
                barang: 'Mikroskop Binokuler Olympus',
                serial: 'OLY-X300',
                kategori: 'Laboratorium',
                jumlah: 1,
                tanggalPinjam: '25/10/2023',
                tanggalKembali: '28/10/2023',
                status: 'Dipinjam'
            }
        ];
    }

    function isBorrowedLoan(loan) {
        const status = String(loan && loan.status ? loan.status : 'pending').toLowerCase();
        const doneStatuses = ['returned', 'dikembalikan', 'rejected', 'ditolak', 'selesai'];
        const activeStatuses = ['dipinjam', 'approved', 'pending', 'menunggu', 'aktif', 'active'];
        if (doneStatuses.includes(status)) return false;
        return activeStatuses.includes(status);
    }

    function getReturnLoanRows(forceLoanId) {
        let apiItems = [];
        try { apiItems = window.__returnApiItems || []; } catch (e) {}
        if (!Array.isArray(apiItems)) apiItems = [];

        const apiIds = apiItems.map(loan => String(loan.id || loan.borrowing_id || ''));
        const historyItems = getRawLoanHistory()
            .filter(loan => isBorrowedLoan(loan))
            .filter(loan => !apiIds.includes(String(loan.id || loan.borrowing_id || '')))
            .map(loan => Object.assign({}, loan, { fromHistory: true }));

        const defaultItems = apiItems.length === 0 && historyItems.length === 0
            ? getDefaultBorrowedReturnLoans()
                .map(loan => Object.assign({}, loan, { fromHistory: true }))
            : [];

        let base = apiItems.concat(historyItems, defaultItems).map(loan => buildReturnRow(loan));

        if (forceLoanId && !base.some(l => l.id === forceLoanId)) {
            const target = apiItems.concat(historyItems, defaultItems).find(loan => String(loan.id || loan.borrowing_id) === String(forceLoanId));
            if (target) base.push(buildReturnRow(target));
        }
        return base;
    }

    function saveLocalReturnSubmission(id, name, selectedRow, conditionLabel, note, photos, status) {
        try {
            const submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]');
            submissions.unshift({
                id: id,
                loanId: id,
                borrower: "sample",
                itemName: name,
                serial: selectedRow.dataset.serial || id,
                quantity: 1,
                condition: conditionLabel,
                note: note || '-',
                photos: photos,
                status: status || 'Menunggu Verifikasi',
                decision: null,
                submittedAt: new Date().toISOString()
            });
            localStorage.setItem('sipibsReturnSubmissions', JSON.stringify(submissions));
        } catch (e) {}
    }

    function finishLocalReturn(id, name, selectedRow, conditionLabel, note, photos) {
        try {
            const returnedIds = getStaticReturnedIds();
            if (!returnedIds.map(x => String(x)).includes(String(id))) {
                returnedIds.push(id);
                saveStaticReturnedIds(returnedIds);
            }
        } catch (e) {}
        if (selectedRow && selectedRow.parentNode) {
            selectedRow.remove();
        }
        saveLocalReturnSubmission(id, name, selectedRow, conditionLabel, note, photos, 'Menunggu Verifikasi');
        updateReturnPendingCount();
        renderReturnHistory();
        renderReturnProofFromApi({
            id: id,
            serial: selectedRow.dataset.serial || id,
            condition: conditionLabel,
            note: note || '-',
            code: 'RTN-' + new Date().getFullYear() + '-' + String(Math.floor(10000 + Math.random() * 90000))
        }, name);
        showUserToast('Pengembalian Selesai! Data masuk ke riwayat pengembalian.');
        const modal = document.getElementById('return-success-modal');
        if (modal) modal.classList.add('active');
    }

    function loadReturnItemsFromApi() {
        return fetch('/api/pengembalian/items', {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) {
            if (!res.ok) return null;
            return res.json();
        })
        .then(function (data) {
            let items = data && Array.isArray(data.items) ? data.items : [];
            window.__returnApiLoaded = true;
            window.__returnApiItems = items;
            renderLatestReturnLoan();
            return items;
        })
        .catch(function () {
            window.__returnApiLoaded = true;
            window.__returnApiItems = [];
            renderLatestReturnLoan();
            return [];
        });
    }

    function renderLatestReturnLoan() {
        const body = document.getElementById('return-items-body');
        if (!body) return;
        const urlParams = new URLSearchParams(window.location.search);
        const urlLoanId = urlParams.get('loanId') || '';
        const rows = getReturnLoanRows(urlLoanId);
        body.innerHTML = '';

        if (rows.length === 0) {
            updateReturnPendingCount();
            return;
        }

        rows.forEach(loan => {
            const tr = document.createElement('tr');
            tr.dataset.id = loan.id;
            tr.dataset.name = loan.name;
            tr.dataset.serial = loan.serial;
            tr.dataset.pinjam = loan.pinjam;
            tr.dataset.batas = loan.batas;
            tr.dataset.image = getReturnImageSrc(loan.image);
            tr.dataset.icon = loan.icon;
            tr.dataset.fromHistory = loan.fromHistory ? '1' : '0';

            let iconHtml = '<i class="bi ' + loan.icon + '"></i>';
            if (loan.icon === 'bi-microscope' || loan.id === 'OLY-X300') {
                iconHtml = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h8"></path><path d="M3 22h18"></path><path d="M14 22a7 7 0 1 0 0-14h-1"></path><path d="M9 14h2"></path><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"></path><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"></path></svg>';
            }

            const iconContent = (loan.image && !loan.image.includes('no-image') && loan.id !== 'B802931-B' && loan.id !== 'OLY-X300')
                ? '<img src="' + getReturnImageSrc(loan.image) + '" alt="' + loan.name + '" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">'
                : iconHtml;

            tr.innerHTML = 
                '<td>' +
                    '<div class="return-item-meta">' +
                        '<span class="return-icon">' + iconContent + '</span>' +
                        '<div>' +
                            '<strong>' + loan.name + '</strong>' +
                            '<small>SN: ' + (loan.serial || loan.id) + '</small>' +
                        '</div>' +
                    '</div>' +
                '</td>' +
                '<td>' + loan.pinjam + '</td>' +
                '<td>' +
                    '<span class="return-due-date ' + (loan.late ? '' : 'safe') + '">' + loan.batas + '</span>' +
                    loan.duePillHtml +
                '</td>' +
                '<td>' +
                    '<button class="return-now-btn" type="button">Kembalikan Sekarang</button>' +
                '</td>';
            body.appendChild(tr);
        });
        updateReturnPendingCount();
    }

    function updateReturnPendingCount() {
        const body = document.getElementById('return-items-body');
        const pendingCount = document.getElementById('return-pending-count');
        if (!body || !pendingCount) return;
        const rows = Array.from(body.querySelectorAll('[data-id]'));
        if (rows.length === 0) {
            pendingCount.textContent = '0 Tertunda';
            body.innerHTML = '<tr><td colspan="4" class="return-empty"><i class="bi bi-inbox" style="font-size:2.2rem;display:block;margin-bottom:10px;"></i>Tidak ada barang yang perlu dikembalikan.</td></tr>';
            return;
        }
        pendingCount.textContent = rows.length + ' Tertunda';
    }

    window.__returnApiItems = [];
    window.__returnApiLoaded = false;
    try { localStorage.removeItem('sipibsSelectedReturnId'); } catch (e) {}
    try { loadReturnItemsFromApi(); } catch (e) {}
    try { renderReturnHistory(); } catch (e) {}

    function paintHistoryBody(combined) {
        const historyBody = document.getElementById('return-history-body');
        if (!historyBody) return;

        if (combined.length === 0) {
            historyBody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px;color:#888;">Belum ada riwayat pengembalian.</td></tr>';
            return;
        }

        historyBody.innerHTML = '';
        combined.forEach(sub => {
            const cond = sub.condition || 'Baik';
            let condClass = 'good';
            let condSymbol = '●';
            let condLabel = 'Baik';

            if (cond.includes('Rusak Berat')) {
                condClass = 'bad';
                condSymbol = '▪';
                condLabel = 'Rusak Berat';
            } else if (cond.includes('Rusak')) {
                condClass = 'warn';
                condSymbol = '▪';
                condLabel = 'Rusak Ringan';
            }

            const dateStr = sub.dateDisplay || (sub.submittedAt ? formatReturnDate(sub.submittedAt.split('T')[0]) : '-');
            let statusLabel = sub.status || 'Selesai';
            let noteContent = sub.note || '-';
            if (noteContent.startsWith('"') && noteContent.endsWith('"')) {
                noteContent = noteContent.slice(1, -1);
            }
            let noteDisplay = '"' + noteContent + '"';

            historyBody.insertAdjacentHTML('beforeend',
                '<tr>' +
                    '<td>' +
                        '<div class="return-hist-meta">' +
                            '<strong>' + (sub.itemName || sub.name || '-') + '</strong>' +
                            '<small style="display:block;color:#64748b;font-size:11.5px;margin-top:2px;">Inv: ' + (sub.serial || sub.id || sub.loanId || '-') + '</small>' +
                        '</div>' +
                    '</td>' +
                    '<td>' + dateStr + '</td>' +
                    '<td><span class="return-cond-pill ' + condClass + '"><span class="cond-symbol">' + condSymbol + '</span> ' + condLabel + '</span></td>' +
                    '<td style="color:#475569;font-size:12.5px;max-width:240px;line-height:1.35;">' + noteDisplay + '</td>' +
                    '<td><span class="return-status-pill done"><i class="bi bi-check-circle"></i> ' + statusLabel + '</span></td>' +
                '</tr>'
            );
        });
    }

    function renderReturnHistory() {
        const historyBody = document.getElementById('return-history-body');
        if (!historyBody) return;

        fetch('/api/pengembalian/history', {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('fail');
            return res.json();
        })
        .then(function (data) {
            const list = data && Array.isArray(data.history) ? data.history : [];
            paintHistoryBody(list);
        })
        .catch(function () {
            let submissions = [];
            try { submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]'); } catch (e) {}
            if (!Array.isArray(submissions)) submissions = [];
            paintHistoryBody(submissions);
        });
    }

    function applySelectedReturnItem(row, button) {
        if (!row) return;
        const itemId = row.dataset.id || (row.querySelector('small') ? row.querySelector('small').textContent.replace('SN:', '').trim() : '');
        const itemName = row.dataset.name || (row.querySelector('strong') ? row.querySelector('strong').textContent.trim() : '');
        document.querySelectorAll('#return-items-body [data-id]').forEach(item => item.classList.remove('selected'));
        document.querySelectorAll('.return-now-btn').forEach(btn => {
            btn.classList.remove('clicked');
            if (btn.dataset.activeBtn === '1') {
                btn.innerHTML = 'Kembalikan Sekarang';
                delete btn.dataset.activeBtn;
            }
        });
        row.classList.add('selected');
        if (button) {
            button.classList.add('clicked');
            button.dataset.activeBtn = '1';
            button.innerHTML = 'Terpilih ✓';
        }
        const idInput = document.getElementById('return-id');
        if (idInput) idInput.value = itemId;
        const nameInput = document.getElementById('return-name');
        if (nameInput) nameInput.value = itemName;
        const nameDisplay = document.getElementById('return-name-display');
        if (nameDisplay) {
            nameDisplay.value = itemName || 'Pilih barang dari daftar';
            nameDisplay.style.background = '#e0edff';
            nameDisplay.style.color = '#003884';
            nameDisplay.style.fontWeight = '800';
        }
        updateSelectedReturnPreview(row);
    }

    function selectReturnItem(button) {
        const row = button && button.closest ? button.closest('[data-id]') : null;
        if (!row) return;
        try { console.log('[SIPIBS] selectReturnItem =>', row.dataset.name || row.dataset.id, 'button=', button); } catch (e) {}
        const formCard = document.getElementById('return-form-card');
        applySelectedReturnItem(row, button);
        localStorage.setItem('sipibsSelectedReturnId', row.dataset.id || '');
        if (formCard) {
            formCard.classList.add('focused');
            formCard.classList.add('selected-active');
            formCard.classList.remove('focus-pulse');
            void formCard.offsetWidth;
            formCard.classList.add('focus-pulse');
            try {
                formCard.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
            } catch (e) {
                try { formCard.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' }); } catch (err) {}
            }
            setTimeout(() => {
                const nameField = document.getElementById('return-name-display');
                if (nameField) {
                    nameField.focus({ preventScroll: false });
                }
                const condSelect = document.getElementById('return-condition');
                if (condSelect) condSelect.focus({ preventScroll: false });
            }, 250);
        }
    }
    function updateSelectedReturnPreview(row) {
        const selectedItem = document.getElementById('return-selected-item');
        const photo = document.getElementById('return-selected-photo');
        const name = document.getElementById('return-selected-name');
        const serial = document.getElementById('return-selected-serial');
        if (!row || !selectedItem || !photo || !name || !serial) return;
        const itemName = row.dataset.name || (row.querySelector('strong') ? row.querySelector('strong').textContent.trim() : '-');
        const itemSerial = row.dataset.serial || row.dataset.id || '-';
        selectedItem.hidden = false;
        name.textContent = itemName;
        serial.textContent = 'SN: ' + itemSerial;
        if (row.dataset.image) {
            photo.innerHTML = `<img src="${row.dataset.image}" alt="${itemName || 'Foto barang'}" onerror="this.onerror=null;this.parentNode.innerHTML='<i class=&quot;bi ${row.dataset.icon || 'bi-box-seam'}&quot;></i>';">`;
        } else {
            photo.innerHTML = `<i class="bi ${row.dataset.icon || 'bi-box-seam'}"></i>`;
        }
    }

    const returnPhotoUpload = document.getElementById('return-photo-upload');
    const returnPhotoInput = document.getElementById('return-photo');
    const returnUploadEmpty = document.getElementById('return-upload-empty');
    const returnUploadPreview = document.getElementById('return-upload-preview');
    const returnPhotoGrid = document.getElementById('return-photo-grid');
    const returnUploadAdd = document.getElementById('return-upload-add');
    const selectedReturnPhotos = window.sipibsSelectedReturnPhotos || [];
    window.sipibsSelectedReturnPhotos = selectedReturnPhotos;
    const maxReturnPhotos = 5;

    function syncReturnPhotoInput() {
        try {
            if (typeof DataTransfer !== 'undefined') {
                const dataTransfer = new DataTransfer();
                selectedReturnPhotos.forEach(file => dataTransfer.items.add(file));
                returnPhotoInput.files = dataTransfer.files;
            }
        } catch (e) {}
    }

    function renderReturnPhotos() {
        if (!returnPhotoGrid) return;
        returnPhotoGrid.innerHTML = '';
        selectedReturnPhotos.forEach((file, index) => {
            const preview = document.createElement('div');
            preview.className = 'return-photo-preview-item';
            const imageUrl = URL.createObjectURL(file);
            preview.innerHTML = '<img src="' + imageUrl + '" alt="Preview foto barang ' + (index + 1) + '" style="width:100%;height:100%;object-fit:cover;display:block;"><button class="return-photo-remove" type="button" data-index="' + index + '" aria-label="Hapus foto">&times;</button>';
            returnPhotoGrid.appendChild(preview);
        });

        if (returnUploadEmpty) returnUploadEmpty.style.display = selectedReturnPhotos.length > 0 ? 'none' : 'flex';
        if (returnUploadPreview) {
            returnUploadPreview.hidden = selectedReturnPhotos.length === 0;
            returnUploadPreview.style.display = selectedReturnPhotos.length > 0 ? 'flex' : 'none';
        }
        const hint = document.getElementById('return-photo-hint');
        if (hint && selectedReturnPhotos.length > 0) hint.style.display = 'none';
        if (returnPhotoUpload && selectedReturnPhotos.length > 0) returnPhotoUpload.classList.remove('photo-missing');
        toggleConfirmBtn();
    }

    function toggleConfirmBtn() {
        // Form menjadi "lengkap" (efek biru) saat kondisi terpilih & minimal 1 foto sudah diupload
        const selected = document.getElementById('return-id').value;
        const isReady = Boolean(selected) && selectedReturnPhotos.length > 0;
        const formCard = document.getElementById('return-form-card');
        if (!formCard) return;
        if (isReady && !formCard.classList.contains('filled-complete')) {
            formCard.classList.add('filled-complete');
        } else if (!isReady) {
            formCard.classList.remove('filled-complete');
        }
    }

    function addReturnPhotos(files) {
        if (!files || files.length === 0) return;
        const remainingSlots = maxReturnPhotos - selectedReturnPhotos.length;
        if (remainingSlots <= 0) {
            alert('Maksimal 5 foto barang.');
            return;
        }

        Array.from(files).slice(0, remainingSlots).forEach(file => {
            const isImage = (file.type && file.type.startsWith('image/')) || /\.(jpe?g|png|webp|jfif|gif|bmp|svg|heic|heif)$/i.test(file.name || '');

            if (!isImage) {
                alert('Format foto salah! Harap upload file gambar (JPG, PNG, WebP, dll).');
                return;
            }

            if (file.size > 15 * 1024 * 1024) {
                alert('Ukuran foto terlalu besar! Maksimal ukuran foto adalah 15 MB.');
                return;
            }

            selectedReturnPhotos.push(file);
        });

        if (files.length > remainingSlots) {
            alert('Hanya 5 foto pertama yang bisa ditambahkan.');
        }

        syncReturnPhotoInput();
        renderReturnPhotos();
    }

    window.handleReturnPhotoFiles = addReturnPhotos;
    window.addReturnPhotos = addReturnPhotos;
    window.renderReturnPhotos = renderReturnPhotos;
    window.addReturnPhotosFromInput = function (input) {
        if (!input || !input.files || input.files.length === 0) return;
        addReturnPhotos(input.files);
        input.value = '';
    };

    function validateReturnPhotos() {
        if (selectedReturnPhotos.length === 0) {
            const hint = document.getElementById('return-photo-hint');
            if (hint) hint.style.display = 'block';
            if (returnPhotoUpload) {
                returnPhotoUpload.classList.add('photo-missing');
                returnPhotoUpload.scrollIntoView({ behavior: 'smooth', block: 'center' });
                returnPhotoUpload.focus();
                setTimeout(() => returnPhotoUpload.classList.remove('photo-missing'), 2500);
            }
            return false;
        }
        const hint = document.getElementById('return-photo-hint');
        if (hint) hint.style.display = 'none';
        return true;
    }

    function compressImageFile(file, maxWidth = 800, maxHeight = 800, quality = 0.75) {
        return new Promise((resolve) => {
            if (!file || !file.type || !file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => resolve({ name: file ? file.name : 'foto.jpg', src: e.target.result });
                reader.onerror = () => resolve({ name: file ? file.name : 'foto.jpg', src: '' });
                reader.readAsDataURL(file);
                return;
            }
            const img = new Image();
            const reader = new FileReader();
            reader.onload = (e) => {
                img.onload = () => {
                    let width = img.width || 800;
                    let height = img.height || 600;
                    if (width > height) {
                        if (width > maxWidth) {
                            height = Math.round((height * maxWidth) / width);
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width = Math.round((width * maxHeight) / height);
                            height = maxHeight;
                        }
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    const dataUrl = canvas.toDataURL('image/jpeg', quality);
                    resolve({ name: file.name, src: dataUrl });
                };
                img.onerror = () => {
                    resolve({ name: file.name, src: e.target.result });
                };
                img.src = e.target.result;
            };
            reader.onerror = () => resolve({ name: file.name, src: '' });
            reader.readAsDataURL(file);
        });
    }

    function readReturnPhotosAsDataUrls() {
        return Promise.all(selectedReturnPhotos.map(file => compressImageFile(file)));
    }

    returnPhotoInput.addEventListener('change', function () {
        window.addReturnPhotosFromInput(this);
    });
    returnPhotoUpload.addEventListener('click', function (event) {
        if (event.target === returnPhotoInput) return;
        const removeBtn = event.target.closest('.return-photo-remove');
        if (removeBtn) {
            event.preventDefault();
            event.stopPropagation();
            const idx = parseInt(removeBtn.dataset.index, 10);
            if (!isNaN(idx) && idx >= 0 && idx < selectedReturnPhotos.length) {
                selectedReturnPhotos.splice(idx, 1);
                syncReturnPhotoInput();
                renderReturnPhotos();
            }
            return;
        }
        if (event.target.closest('#return-upload-add')) {
            event.preventDefault();
            event.stopPropagation();
            if (selectedReturnPhotos.length >= maxReturnPhotos) {
                alert('Maksimal 5 foto barang.');
                return;
            }
            returnPhotoInput.click();
            return;
        }
        if (selectedReturnPhotos.length >= maxReturnPhotos) {
            alert('Maksimal 5 foto barang.');
            return;
        }
        returnPhotoInput.click();
    });

    returnPhotoUpload.addEventListener('dragover', function (event) {
        event.preventDefault();
        event.stopPropagation();
        returnPhotoUpload.classList.add('dragging');
    });

    returnPhotoUpload.addEventListener('dragleave', function (event) {
        event.preventDefault();
        event.stopPropagation();
        returnPhotoUpload.classList.remove('dragging');
    });

    returnPhotoUpload.addEventListener('drop', function (event) {
        event.preventDefault();
        event.stopPropagation();
        returnPhotoUpload.classList.remove('dragging');
        if (event.dataTransfer && event.dataTransfer.files) {
            addReturnPhotos(event.dataTransfer.files);
        }
    });

    function mapReturnCondition(label) {
        const s = String(label || '').toLowerCase();
        if (s.includes('rusak berat')) return 'rusak';
        if (s.includes('rusak')) return 'perlu_servis';
        return 'baik';
    }

    function renderReturnProofFromApi(data, name) {
        if (document.getElementById('proof-name')) document.getElementById('proof-name').textContent = name || (data && data.itemName) || '-';
        if (document.getElementById('proof-id')) document.getElementById('proof-id').textContent = (data && (data.borrowing_id || data.serial || data.id)) || '-';
        if (document.getElementById('proof-condition')) document.getElementById('proof-condition').textContent = (data && data.condition) || 'Baik (Fungsional & Bersih)';
        if (document.getElementById('proof-note')) document.getElementById('proof-note').textContent = (data && data.note) || '-';
        const proofDate = document.getElementById('proof-date');
        if (proofDate) {
            proofDate.textContent = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }
        const proofCode = document.getElementById('proof-code');
        if (proofCode) {
            proofCode.textContent = (data && data.code) || ('RTN-' + new Date().getFullYear() + '-' + String(Math.floor(10000 + Math.random() * 90000)));
        }
    }

    async function confirmReturn(event) {
        if (event) {
            if (event.preventDefault) event.preventDefault();
            if (event.stopPropagation) event.stopPropagation();
        }

        // Auto-ambil file input jika user memilih file tapi belum masuk ke array
        if (selectedReturnPhotos.length === 0 && returnPhotoInput && returnPhotoInput.files && returnPhotoInput.files.length > 0) {
            addReturnPhotos(returnPhotoInput.files);
        }

        let id = (document.getElementById('return-id') ? document.getElementById('return-id').value : '') || '';
        let name = (document.getElementById('return-name') ? document.getElementById('return-name').value : '') || '';

        let selectedRow = document.querySelector('#return-items-body [data-id].selected') ||
                          document.querySelector('#return-items-body [data-id="' + id + '"]') ||
                          document.querySelector('#return-items-body [data-id]');

        if ((!id || !name) && selectedRow) {
            id = selectedRow.dataset.id || id;
            name = selectedRow.dataset.name || name;
            if (document.getElementById('return-id')) document.getElementById('return-id').value = id;
            if (document.getElementById('return-name')) document.getElementById('return-name').value = name;
        }

        if (!selectedRow || !id) {
            alert('Silakan pilih barang terlebih dahulu dari daftar.');
            return;
        }

        if (document.getElementById('return-name-display') && !name) {
            name = document.getElementById('return-name-display').value || name;
        }

        const conditionLabel = (document.getElementById('return-condition') ? document.getElementById('return-condition').value : '') || 'Baik (Fungsional & Bersih)';
        const note = (document.getElementById('return-note') ? document.getElementById('return-note').value.trim() : '') || '';

        let photos = [];
        try {
            if (selectedReturnPhotos && selectedReturnPhotos.length > 0) {
                photos = await readReturnPhotosAsDataUrls();
            }
        } catch (e) {
            photos = [];
        }

        const submitBtn = document.getElementById('return-confirm-btn');
        if (submitBtn) {
            submitBtn.disabled = true;
        }

        try {
            if (selectedRow.dataset.fromHistory === '1') {
                finishLocalReturn(id, name, selectedRow, conditionLabel, note, photos);
                return;
            }

            const res = await fetch('/api/pengembalian', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.__csrf || ''
                },
                body: JSON.stringify({
                    borrowing_id: id,
                    condition: mapReturnCondition(conditionLabel),
                    notes: note,
                    photos: photos
                })
            });
            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                if (selectedRow.dataset.fromHistory === '1') {
                    finishLocalReturn(id, name, selectedRow, conditionLabel, note, photos);
                    return;
                }
                const msg = (data && data.message) || 'Gagal menyimpan pengembalian. Silakan coba lagi.';
                alert(msg);
                return;
            }

            if (data && data.return) {
                const code = data.return.code;
                if (document.getElementById('proof-code') && code) {
                    document.getElementById('proof-code').textContent = code;
                }
            }

            // Hilangkan baris yang sudah dikembalikan dari daftar tampilan
            const returnRow = selectedRow;
            if (returnRow && returnRow.parentNode) {
                returnRow.remove();
            }

            // Sisipkan ke riwayat (localStorage) untuk kompatibilitas tampilan lain
            try {
                const submissions = JSON.parse(localStorage.getItem('sipibsReturnSubmissions') || '[]');
                submissions.unshift({
                    id: id,
                    loanId: id,
                    borrower: "sample",
                    itemName: name,
                    serial: selectedRow.dataset.serial || id,
                    quantity: 1,
                    condition: conditionLabel,
                    note: note || '-',
                    photos: photos,
                    status: 'Menunggu Verifikasi',
                    decision: null,
                    submittedAt: new Date().toISOString()
                });
                localStorage.setItem('sipibsReturnSubmissions', JSON.stringify(submissions));
            } catch (e) {}

            updateReturnPendingCount();
            loadReturnItemsFromApi();
            renderReturnHistory();

            renderReturnProofFromApi(data && data.return, name);

            showUserToast('Pengembalian Selesai! Terima kasih sudah meminjam barang.');

            try {
                const notifs = JSON.parse(localStorage.getItem('sipibsUserNotifications') || '[]');
                notifs.unshift({
                    id: 'ntf-' + Date.now(),
                    title: 'Pengembalian Selesai',
                    message: 'Pengembalian barang ' + name + ' selesai. Terima kasih sudah meminjam!',
                    icon: 'bi-check-circle',
                    type: 'green',
                    read: false,
                    time: new Date().toISOString()
                });
                localStorage.setItem('sipibsUserNotifications', JSON.stringify(notifs));
            } catch (e) {}

            const modal = document.getElementById('return-success-modal');
            if (modal) modal.classList.add('active');
        } catch (err) {
            alert('Terjadi kesalahan saat mengirim pengembalian. Silakan coba lagi.');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    function showUserToast(msg) {
        const toast = document.getElementById('user-toast');
        const toastMsg = document.getElementById('user-toast-msg');
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        toast.style.pointerEvents = 'auto';
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            toast.style.pointerEvents = 'none';
        }, 4000);
    }

    function resetReturnForm() {
        document.querySelectorAll('#return-items-body [data-id]').forEach(item => item.classList.remove('selected'));
        document.querySelectorAll('.return-now-btn').forEach(btn => {
            btn.classList.remove('clicked');
            if (btn.dataset.activeBtn === '1') {
                btn.innerHTML = 'Kembalikan Sekarang';
                delete btn.dataset.activeBtn;
            }
        });
        const formCard = document.getElementById('return-form-card');
        if (formCard) {
            formCard.classList.remove('selected-active');
            formCard.classList.remove('filled-complete');
        }
        document.getElementById('return-condition').selectedIndex = 0;
        document.getElementById('return-note').value = '';
        selectedReturnPhotos.length = 0;
        syncReturnPhotoInput();
        renderReturnPhotos();
        document.getElementById('return-id').value = '';
        document.getElementById('return-name').value = '';
        const nameDisplay = document.getElementById('return-name-display');
        if (nameDisplay) nameDisplay.value = 'Pilih barang dari daftar';
        const selectedItem = document.getElementById('return-selected-item');
        if (selectedItem) selectedItem.hidden = true;
        const selectedBox = document.getElementById('return-selected-item');
        if (selectedBox) selectedBox.hidden = true;
    }

    function closeReturnModal() {
        const modal = document.getElementById('return-success-modal');
        if (modal) modal.classList.remove('active');
        const formCard = document.getElementById('return-form-card');
        if (formCard) formCard.classList.remove('focused');
        updateReturnPendingCount();
        resetReturnForm();
    }

    function printReturnProof() {
        window.print();
    }

    window.applySelectedReturnItem = applySelectedReturnItem;
    window.selectReturnItem = selectReturnItem;
    window.confirmReturn = confirmReturn;
    window.doSubmitReturn = confirmReturn;
    window.closeReturnModal = closeReturnModal;
    window.printReturnProof = printReturnProof;


    const returnForm = document.getElementById('return-form');
    if (returnForm) {
        returnForm.addEventListener('submit', function(e) {
            e.preventDefault();
            confirmReturn(e);
        });
    }

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.return-now-btn');
        if (!button) return;
        event.preventDefault();
        event.stopPropagation();
        selectReturnItem(button);
    });

    window.history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);

    const returnSearch = document.getElementById('returnSearch');
    returnSearch.addEventListener('input', function () {
        const q = returnSearch.value.toLowerCase().trim();
        const countLabel = document.getElementById('return-pending-count');
        const rows = document.querySelectorAll('#return-items-body [data-name]');
        let visible = 0;
        rows.forEach(function (row) {
            const name = (row.dataset.name || '').toLowerCase();
            const serial = (row.dataset.serial || '').toLowerCase();
            const match = !q || name.includes(q) || serial.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        if (countLabel) {
            countLabel.textContent = q
                ? (visible + ' Terlihat')
                : (rows.length + ' Tertunda');
        }
    });

    window.addEventListener('storage', function (event) {
        const relevantKeys = ['sipibsLoanHistory', 'sipibsLoanDecision', 'sipibsLoanRequest', 'sipibsReturnSubmissions'];
        if (relevantKeys.indexOf(event.key) < 0) return;
        try { loadReturnItemsFromApi(); } catch (e) {}
            try { renderReturnHistory(); } catch (e) {}
    });

    window.addEventListener('load', function () {
        setTimeout(function () {
            try { loadReturnItemsFromApi(); } catch (e) {}
            try { renderReturnHistory(); } catch (e) {}
        }, 150);
    });

    function openRiwayatPinjamDetail(index) {
        const history = JSON.parse(localStorage.getItem('sipibsLoanHistory') || '[]');
        const decision = JSON.parse(localStorage.getItem('sipibsLoanDecision') || 'null');
        const request = JSON.parse(localStorage.getItem('sipibsLoanRequest') || 'null');
        const fallback = decision && decision.request ? decision.request : request;
        let list = history.slice();
        if (fallback && fallback.id && !list.some(item => item.id === fallback.id)) list.unshift(fallback);
        const items = list.slice().reverse().slice(0, 5);
        const loan = items[index];
        if (!loan) return;
        alert('Detail Peminjaman\n\nID: ' + (loan.id || '-') + '\nBarang: ' + (loan.barang || '-') + '\nKategori: ' + (loan.kategori || '-') + '\nTanggal Pinjam: ' + (loan.tanggalPinjam || '-') + '\nTanggal Kembali: ' + (loan.tanggalKembali || '-') + '\nJumlah: ' + (loan.jumlah || '-') + '\nStatus: ' + (loan.status || 'pending'));
    }
