document.addEventListener('DOMContentLoaded', function () {
    let activeItem = null;
    let actionType = 'add';
    let deleteTargetItem = null;

    function readJson(key, fallback) {
        try { return JSON.parse(localStorage.getItem(key) || 'null') || fallback; }
        catch (e) { return fallback; }
    }

    function getItems() {
        return readJson('sipibsMasterItems', []);
    }

    function saveItems(items) {
        localStorage.setItem('sipibsMasterItems', JSON.stringify(items));
    }

    function iconForItem(item) {
        if (item && item.icon) return item.icon;
        const name = item && item.name ? item.name.toLowerCase() : '';
        if (name.includes('mouse')) return 'bi-mouse';
        if (name.includes('keyboard')) return 'bi-keyboard';
        if (name.includes('laptop') || name.includes('lenovo')) return 'bi-laptop';
        if (name.includes('kamera') || name.includes('camera') || name.includes('canon')) return 'bi-camera';
        if (name.includes('proyektor') || name.includes('projector') || name.includes('epson')) return 'bi-easel';
        if (name.includes('headset')) return 'bi-headphones';
        return 'bi-box-seam';
    }

    function itemThumb(item) {
        const icon = iconForItem(item);
        if (item && item.image && item.image.indexOf('http') === 0) {
            return '<img src="' + item.image + '" alt="' + item.name + '">';
        }
        if (item && item.image) {
            return '<img src="/sipibs/public/images/' + item.image + '" alt="' + item.name + '" onerror="this.onerror=null;this.parentNode.innerHTML=\'<i class=&quot;bi ' + icon + '&quot;></i>\';">';
        }
        return '<i class="bi ' + icon + '"></i>';
    }

    function openDeleteModal(code) {
        const item = getItems().find(function (row) { return row.code === code; });
        if (!item) return;
        deleteTargetItem = item;
        document.getElementById('deletePreviewName').textContent = item.name;
        document.getElementById('deletePreviewCode').textContent = item.code;
        document.getElementById('deletePreviewThumb').innerHTML = itemThumb(item);
        document.getElementById('deleteModalOverlay').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModalOverlay').classList.remove('active');
        deleteTargetItem = null;
    }

    function confirmDeleteItem() {
        if (!deleteTargetItem) return;
        let items = getItems();
        items = items.filter(function (item) { return item.code !== deleteTargetItem.code; });
        saveItems(items);
        localStorage.removeItem(historyKey(deleteTargetItem.code));
        closeDeleteModal();
        window.dispatchEvent(new Event('storage'));
    }

    function getStock(name, fallback) {
        const stocks = readJson('sipibsItemStock', {});
        return stocks[name] !== undefined ? Math.max(0, parseInt(stocks[name]) || 0) : fallback;
    }

    function setStock(name, value) {
        const stocks = readJson('sipibsItemStock', {});
        stocks[name] = Math.max(0, parseInt(value) || 0);
        localStorage.setItem('sipibsItemStock', JSON.stringify(stocks));
    }

    function historyKey(code) {
        return 'sipibsStockHistory_' + code;
    }

    function getHistory(code) {
        const stored = readJson(historyKey(code), null);
        if (stored && Array.isArray(stored)) return stored;
        const seed = [
            { type:'add', qty:'+2 unit', reason:'Pembelian barang baru', date:'26 Mei 2026, 10:30', actor:'Admin' },
            { type:'sub', qty:'-1 unit', reason:'Barang dipinjam', date:'25 Mei 2026, 14:20', actor:'System' },
            { type:'add', qty:'+1 unit', reason:'Pengembalian barang', date:'24 Mei 2026, 09:15', actor:'System' }
        ];
        localStorage.setItem(historyKey(code), JSON.stringify(seed));
        return seed;
    }

    function renderHistory(code) {
        const box = document.getElementById('stockHistoryList');
        if (!box) return;
        box.innerHTML = getHistory(code).slice(0, 5).map(function (item) {
            return '<div class="history-item-row">' +
                '<div class="history-icon-circle ' + item.type + '">' + (item.type === 'add' ? '+' : '-') + '</div>' +
                '<div class="history-item-content">' +
                    '<div class="history-item-left"><div class="qty-tag ' + item.type + '">' + item.qty + '</div><div class="reason-text">' + item.reason + '</div></div>' +
                    '<div class="history-item-right"><div>' + item.date + '</div><div>oleh <span class="by-actor">' + item.actor + '</span></div></div>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function openModal(code) {
        const item = getItems().find(function (row) { return row.code === code; });
        if (!item) return;
        activeItem = item;
        actionType = 'add';
        document.getElementById('stockModalItemName').textContent = item.name + ' (' + item.code + ')';
        document.getElementById('stockQtyInput').value = '';
        document.getElementById('stockQtyInput').placeholder = 'Masukkan jumlah stok';
        document.getElementById('stockReasonInput').value = '';
        document.getElementById('tabAddStock').classList.add('active');
        document.getElementById('tabSubtractStock').classList.remove('active');
        document.getElementById('btnStockSubmit').textContent = 'Tambah Stok';
        document.getElementById('stockQtyInput').placeholder = 'Masukkan jumlah stok';
        document.getElementById('btnStockSubmit').classList.remove('subtract');
        renderHistory(item.code);
        document.getElementById('stockModalOverlay').classList.add('active');
    }

    function closeModal() {
        document.getElementById('stockModalOverlay').classList.remove('active');
        activeItem = null;
    }

    document.getElementById('masterTableBody').addEventListener('click', function (event) {
        const edit = event.target.closest('.tbl-btn-action.edit');
        if (edit) {
            event.preventDefault();
            event.stopImmediatePropagation();
            openModal(edit.dataset.code);
        }

        const del = event.target.closest('.tbl-btn-action.delete');
        if (del) {
            event.preventDefault();
            event.stopImmediatePropagation();
            openDeleteModal(del.dataset.code);
        }
    }, true);

    document.getElementById('closeDeleteModal').addEventListener('click', closeDeleteModal);
    document.getElementById('btnCancelDelete').addEventListener('click', closeDeleteModal);
    document.getElementById('btnConfirmDelete').addEventListener('click', confirmDeleteItem);
    document.getElementById('deleteModalOverlay').addEventListener('click', function (event) {
        if (event.target === this) closeDeleteModal();
    });

    document.getElementById('closeStockModal').addEventListener('click', closeModal);
    document.getElementById('stockModalOverlay').addEventListener('click', function (event) {
        if (event.target === this) closeModal();
    });

    document.getElementById('tabAddStock').addEventListener('click', function () {
        actionType = 'add';
        this.classList.add('active');
        document.getElementById('tabSubtractStock').classList.remove('active');
        document.getElementById('btnStockSubmit').textContent = 'Tambah Stok';
        document.getElementById('btnStockSubmit').classList.remove('subtract');
    });

    document.getElementById('tabSubtractStock').addEventListener('click', function () {
        actionType = 'sub';
        this.classList.add('active');
        document.getElementById('tabAddStock').classList.remove('active');
        document.getElementById('btnStockSubmit').textContent = 'Kurangi Stok';
        document.getElementById('stockQtyInput').placeholder = 'Kurangi jumlah stok';
        document.getElementById('btnStockSubmit').classList.add('subtract');
    });

    document.getElementById('btnStockSubmit').addEventListener('click', function () {
        if (!activeItem) return;
        const qty = parseInt(document.getElementById('stockQtyInput').value);
        if (!qty || qty <= 0) {
            alert('Masukkan jumlah stok yang valid.');
            return;
        }
        const current = getStock(activeItem.name, activeItem.stockTotal);
        const next = actionType === 'add' ? current + qty : Math.max(0, current - qty);
        setStock(activeItem.name, next);

        const items = getItems();
        const found = items.find(function (row) { return row.code === activeItem.code; });
        if (found) found.stockTotal = next;
        saveItems(items);

        const now = new Date();
        const reason = document.getElementById('stockReasonInput').value.trim() || (actionType === 'add' ? 'Pembelian barang baru' : 'Pengurangan stok barang');
        const record = {
            type: actionType === 'add' ? 'add' : 'sub',
            qty: (actionType === 'add' ? '+' : '-') + qty + ' unit',
            reason: reason,
            date: now.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' }) + ', ' + now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' }),
            actor: 'Admin'
        };
        const history = getHistory(activeItem.code);
        history.unshift(record);
        localStorage.setItem(historyKey(activeItem.code), JSON.stringify(history));
        document.getElementById('stockQtyInput').value = '';
        document.getElementById('stockReasonInput').value = '';
        renderHistory(activeItem.code);
        window.dispatchEvent(new Event('storage'));
    });
});
