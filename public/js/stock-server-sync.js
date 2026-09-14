(function () {
    function apiRoot() {
        return (window.__apiBase || window.SIPIBS_API_BASE || '/api').replace(/\/+$/, '');
    }

    function norm(name) {
        return String(name || '').toLowerCase().replace(/\s+/g, ' ').trim();
    }

    window.SIPIBS_SERVER_ITEMS = {};

    function persistStock(items) {
        const stocks = JSON.parse(localStorage.getItem('sipibsItemStock') || '{}');
        const totals = JSON.parse(localStorage.getItem('sipibsItemTotal') || '{}');
        let changed = false;
        items.forEach(function (item) {
            window.SIPIBS_SERVER_ITEMS[norm(item.name)] = item;
            if (item.code) window.SIPIBS_SERVER_ITEMS[norm(item.code)] = item;
            const avail = Math.max(0, parseInt(item.available_quantity, 10) || 0);
            const total = Math.max(avail, parseInt(item.total_quantity, 10) || avail);
            if (stocks[item.name] !== avail || totals[item.name] !== total) {
                changed = true;
                stocks[item.name] = avail;
                totals[item.name] = total;
            }
        });
        if (changed) {
            localStorage.setItem('sipibsItemStock', JSON.stringify(stocks));
            localStorage.setItem('sipibsItemTotal', JSON.stringify(totals));
        }
        return items;
    }

    function syncStockFromServer(done) {
        const url = apiRoot() + '/inventaris';
        return fetch(url, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                const items = (data && Array.isArray(data.items)) ? data.items : [];
                persistStock(items);
                if (typeof done === 'function') done(items);
                return { ok: true, items: items };
            })
            .catch(function () {
                if (typeof done === 'function') done([]);
                return { ok: false, items: [] };
            });
    }

    function getServerItem(name) {
        return window.SIPIBS_SERVER_ITEMS[norm(name)] || null;
    }

    window.syncStockFromServer = syncStockFromServer;
    window.getServerItem = getServerItem;
    window.sipibsStockNorm = norm;
})();