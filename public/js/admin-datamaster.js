document.addEventListener('DOMContentLoaded', function () {
    const userCatalogItems = [];

    let currentPage = 1;
    const itemsPerPage = 8;

    function getItemStock(itemName, defaultTotal) {
        try {
            const stocks = JSON.parse(localStorage.getItem('sipibsItemStock') || '{}');
            if (stocks[itemName] !== undefined) {
                return Math.max(0, parseInt(stocks[itemName]) || 0);
            }
        } catch (e) {}
        return defaultTotal;
    }

function getMasterItems() {
        try {
            const saved = JSON.parse(localStorage.getItem('sipibsMasterItems') || 'null');
            if (saved && Array.isArray(saved) && saved.length > 0) {
                const merged = saved.map(item => {
                    const def = userCatalogItems.find(d => String(d.code).toUpperCase() === String(item.code).toUpperCase());
                    return def && def.image ? Object.assign({}, item, { image: def.image }) : item;
                });
                localStorage.setItem('sipibsMasterItems', JSON.stringify(merged));
                return merged;
            }
        } catch (e) {}
        
        
        return userCatalogItems;
    }

    function saveMasterItems(items) {
        localStorage.setItem('sipibsMasterItems', JSON.stringify(items));
    }

    function getStockStatus(currentStock) {
        if (currentStock <= 0) {
            return { label: 'Habis', colorClass: 'red' };
        } else if (currentStock <= 2) {
            return { label: 'Stok Rendah', colorClass: 'orange' };
        } else {
            return { label: 'Tersedia', colorClass: 'green' };
        }
    }

    function updateStats(items) {
        const total = items.length;
        let availableCount = 0;
        let lowStockCount = 0;
        let outOfStockCount = 0;

        items.forEach(item => {
            const currentStock = getItemStock(item.name, item.stockTotal);
            if (currentStock <= 0) {
                outOfStockCount++;
            } else if (currentStock <= 2) {
                lowStockCount++;
            } else {
                availableCount++;
            }
        });

        document.getElementById('statTotalItem').textContent = total;
        document.getElementById('statAvailable').textContent = availableCount;
        document.getElementById('statLowStock').textContent = lowStockCount;
        document.getElementById('statOutOfStock').textContent = outOfStockCount;
    }

    function populateCategoryFilter(items) {
        const select = document.getElementById('filterCategory');
        if (!select) return;
        const categories = Array.from(new Set(items.map(i => i.cat))).filter(Boolean);
        const currentVal = select.value;
        select.innerHTML = '<option value="all">Semua Kategori</option>' + 
            categories.map(c => `<option value="${c}">${c}</option>`).join('');
        select.value = currentVal || 'all';
    }

    function getItemIconClass(name, iconClass) {
        if (iconClass && iconClass.startsWith('bi-')) return iconClass;
        if (!name) return 'bi-box-seam';
        const n = name.toLowerCase();
        if (n.includes('mouse')) return 'bi-mouse';
        if (n.includes('keyboard')) return 'bi-keyboard';
        if (n.includes('laptop') || n.includes('lenovo')) return 'bi-laptop';
        if (n.includes('headset') || n.includes('logitech')) return 'bi-headphones';
        if (n.includes('webcam') || n.includes('camera')) return 'bi-camera-video';
        if (n.includes('proyektor') || n.includes('epson')) return 'bi-easel';
        if (n.includes('hdmi')) return 'bi-usb-c';
        if (n.includes('vga')) return 'bi-plug';
        if (n.includes('lan') || n.includes('cat6')) return 'bi-ethernet';
        if (n.includes('kabel')) return 'bi-usb-plug';
        if (n.includes('pointer')) return 'bi-broadcast-pin';
        if (n.includes('stop kontak')) return 'bi-outlet';
        if (n.includes('tester')) return 'bi-router';
        if (n.includes('crimping') || n.includes('tang')) return 'bi-tools';
        if (n.includes('jbl') || n.includes('speaker')) return 'bi-speaker';
        return 'bi-box-seam';
    }

    function renderTable() {
        const items = getMasterItems();
        updateStats(items);
        populateCategoryFilter(items);

        const searchVal = (document.getElementById('searchMaster').value || '').toLowerCase().trim();
        const selectedCat = document.getElementById('filterCategory').value || 'all';

        const filtered = items.filter(item => {
            const matchCat = (selectedCat === 'all') || (item.cat === selectedCat);
            const matchSearch = !searchVal || 
                (item.name + ' ' + item.code + ' ' + (item.merk || '') + ' ' + item.cat).toLowerCase().includes(searchVal);
            return matchCat && matchSearch;
        });

        const totalItems = filtered.length;
        const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
        if (currentPage > totalPages) currentPage = totalPages;

        const startIndex = (currentPage - 1) * itemsPerPage;
        const pageItems = filtered.slice(startIndex, startIndex + itemsPerPage);

        const tbody = document.getElementById('masterTableBody');

        if (pageItems.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7">
                        <div class="master-empty">
                            <i class="bi bi-inbox"></i>
                            <p style="margin:0; font-size:15px; font-weight:600;">Data tidak ditemukan</p>
                            <small>Tidak ada data barang yang sesuai dengan filter atau pencarian Anda.</small>
                        </div>
                    </td>
                </tr>
            `;
            document.getElementById('paginationInfo').textContent = 'Menampilkan 0 - 0 dari 0 data';
            renderPagination(0, 1);
            return;
        }

tbody.innerHTML = pageItems.map((item, idx) => {
            const realIndex = startIndex + idx + 1;
            const currentStock = getItemStock(item.name, item.stockTotal);
            const status = getStockStatus(currentStock);
            const iconClass = getItemIconClass(item.name, item.icon);
            const condition = item.condition || 'Baik';
            const condClass = condition === 'Rusak' ? 'red' : (condition === 'Rusak Ringan' ? 'yellow' : 'green');

            let thumbHtml = `<i class="bi ${iconClass}"></i>`;
            if (item.image && (item.image.startsWith('http') || item.image.startsWith('data:') || item.image.startsWith('/') || item.image.startsWith('storage/'))) {
                thumbHtml = `<img src="${item.image}" alt="${item.name}">`;
            } else if (item.image) {
                thumbHtml = `<img src="${window.SIPIBS_IMAGE_BASE || '/images/'}${item.image}" alt="${item.name}" onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\\&quot;bi ${iconClass}\\&quot;></i>';">`;
            }

            return `
                <tr>
                    <td style="color:#64748b; font-weight:600;">${realIndex}</td>
                    <td><strong style="color:#2563eb; font-size:13px;">${item.code}</strong></td>
                    <td>
                        <div class="item-name-cell">
                            <div class="item-thumb-box">
                                ${thumbHtml}
                            </div>
                            <div class="item-details-text">
                                <strong>${item.name}</strong>
                                <span>${item.merk || 'SIPIBS'}</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="cat-badge">${item.cat}</span></td>
<td>
                        <div class="stock-badge-box">
                            <span class="stock-count ${status.colorClass}">${currentStock}</span>
                            <span class="stock-status-label ${status.colorClass}">${status.label}</span>
                        </div>
                    </td>
                    <td><span class="cond-badge ${condClass}">${condition}</span></td>
                    <td>
                        <div class="action-btns-wrap">
                            <button type="button" class="tbl-btn-action edit" data-code="${item.code}" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="tbl-btn-action condition" data-code="${item.code}" title="Ubah Kondisi">
                                <i class="bi bi-clipboard-check"></i>
                            </button>
                            <button type="button" class="tbl-btn-action delete" data-code="${item.code}" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button type="button" class="tbl-btn-action more" data-code="${item.code}" title="Detail">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        document.getElementById('paginationInfo').textContent = `Menampilkan ${startIndex + 1} - ${endIndex} dari ${totalItems} data`;
        renderPagination(totalPages, currentPage);
    }

    function renderPagination(totalPages, page) {
        const container = document.getElementById('paginationNav');
        if (!container) return;

        if (totalPages <= 1) {
            container.innerHTML = `
                <button class="master-page-btn" disabled>&lt;</button>
                <button class="master-page-btn active">1</button>
                <button class="master-page-btn" disabled>&gt;</button>
            `;
            return;
        }

        let navHtml = `<button class="master-page-btn" ${page === 1 ? 'disabled' : ''} data-page="${page - 1}">&lt;</button>`;

        for (let p = 1; p <= totalPages; p++) {
            navHtml += `<button class="master-page-btn ${p === page ? 'active' : ''}" data-page="${p}">${p}</button>`;
        }

        navHtml += `<button class="master-page-btn" ${page === totalPages ? 'disabled' : ''} data-page="${page + 1}">&gt;</button>`;
        container.innerHTML = navHtml;
    }

    // Event Listeners
    document.getElementById('searchMaster').addEventListener('input', function () {
        currentPage = 1;
        renderTable();
    });

    document.getElementById('filterCategory').addEventListener('change', function () {
        currentPage = 1;
        renderTable();
    });

    document.getElementById('paginationNav').addEventListener('click', function (e) {
        const btn = e.target.closest('button[data-page]');
        if (!btn || btn.disabled) return;
        currentPage = parseInt(btn.dataset.page);
        renderTable();
    });

    // Delete handling
    document.getElementById('masterTableBody').addEventListener('click', function (e) {
        const deleteBtn = e.target.closest('.tbl-btn-action.delete');
        if (deleteBtn) {
            const code = deleteBtn.dataset.code;
            if (confirm('Apakah Anda yakin ingin menghapus barang dengan kode ' + code + '?')) {
                let items = getMasterItems();
                items = items.filter(i => i.code !== code);
                saveMasterItems(items);
                renderTable();
            }
        }
    });

    // Detail handling (three-dots button)
    function openDetailModal(code) {
        const items = getMasterItems();
        const item = items.find(i => i.code === code);
        if (!item) return;

        const currentStock = getItemStock(item.name, item.stockTotal);
        const status = getStockStatus(currentStock);
        const iconClass = getItemIconClass(item.name, item.icon);

        let previewHtml = `<i class="bi ${iconClass}"></i>`;
        if (item.image) {
            const src = (item.image.startsWith('http') || item.image.startsWith('data:') || item.image.startsWith('/') || item.image.startsWith('storage/')) ? item.image : (window.SIPIBS_IMAGE_BASE || '/images/') + item.image;
            previewHtml = `<img src="${src}" alt="${item.name}" onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\\&quot;bi ${iconClass}\\&quot;></i>';">`;
        }

        document.getElementById('detailItemPreview').innerHTML = previewHtml;
        document.getElementById('detailCode').textContent = item.code;
        document.getElementById('detailName').textContent = item.name;
        document.getElementById('detailCat').textContent = item.cat || '-';
        document.getElementById('detailMerk').textContent = item.merk || 'SIPIBS';
        document.getElementById('detailStock').textContent = currentStock + ' unit';
        document.getElementById('detailStatus').textContent = status.label;
        document.getElementById('detailKondisi').textContent = item.condition || 'Baik';

        const preview = document.querySelector('#detailItemPreview img');
        if (preview) {
            preview.style.cursor = 'zoom-in';
            preview.title = 'Klik untuk membuka foto asli';
            preview.onclick = function () { window.open(this.src, '_blank', 'noopener'); };
        }
        document.getElementById('detailItemModalOverlay').classList.add('active');
    }

    function closeDetailModal() {
        document.getElementById('detailItemModalOverlay').classList.remove('active');
    }

    document.getElementById('masterTableBody').addEventListener('click', function (e) {
        const moreBtn = e.target.closest('.tbl-btn-action.more');
        if (moreBtn) {
            openDetailModal(moreBtn.dataset.code);
        }
    });

    // Ubah Kondisi handling
    function openConditionModal(code) {
        const items = getMasterItems();
        const item = items.find(i => i.code === code);
        if (!item) return;
        const condition = item.condition || 'Baik';
        document.getElementById('conditionItemName').textContent = item.name;
        document.getElementById('conditionItemCode').textContent = item.code;
        document.querySelectorAll('#conditionOptions .cond-option').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.cond === condition);
        });
        document.getElementById('conditionItemModalOverlay').classList.add('active');
    }

    function closeConditionModal() {
        document.getElementById('conditionItemModalOverlay').classList.remove('active');
    }

    document.getElementById('masterTableBody').addEventListener('click', function (e) {
        const conditionBtn = e.target.closest('.tbl-btn-action.condition');
        if (conditionBtn) {
            openConditionModal(conditionBtn.dataset.code);
        }
    });

    document.getElementById('conditionOptions').addEventListener('click', function (e) {
        const opt = e.target.closest('.cond-option');
        if (!opt) return;
        document.querySelectorAll('#conditionOptions .cond-option').forEach(b => b.classList.remove('active'));
        opt.classList.add('active');
    });

    document.getElementById('btnSaveCondition').addEventListener('click', function () {
        const active = document.querySelector('#conditionOptions .cond-option.active');
        if (!active) return;
        const code = document.getElementById('conditionItemCode').textContent;
        const items = getMasterItems();
        const item = items.find(i => i.code === code);
        if (item) {
            item.condition = active.dataset.cond;
            saveMasterItems(items);
        }
        closeConditionModal();
        renderTable();
    });

    document.getElementById('closeConditionItemModal').addEventListener('click', closeConditionModal);
    document.getElementById('btnCancelCondition').addEventListener('click', closeConditionModal);
    document.getElementById('conditionItemModalOverlay').addEventListener('click', function (event) {
        if (event.target === this) closeConditionModal();
    });

    document.getElementById('closeDetailItemModal').addEventListener('click', closeDetailModal);
    document.getElementById('closeDetailItemModal2').addEventListener('click', closeDetailModal);
    document.getElementById('detailItemModalOverlay').addEventListener('click', function (event) {
        if (event.target === this) closeDetailModal();
    });

    document.getElementById('btnChangeDetailPhoto').addEventListener('click', function () {
        document.getElementById('detailItemPhotoInput').click();
    });

    document.getElementById('detailItemPhotoInput').addEventListener('change', async function () {
        const photoFile = this.files[0];
        if (!photoFile) return;
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(photoFile.type) || photoFile.size > 2 * 1024 * 1024) {
            alert('Foto harus JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.');
            this.value = '';
            return;
        }

        const itemCode = document.getElementById('detailCode').textContent;
        const item = getMasterItems().find(function (row) { return row.code === itemCode; });
        if (!item || !item.inventoryId) {
            alert('Data barang belum tersinkron. Refresh halaman lalu coba lagi.');
            this.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('photo_file', photoFile);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        try {
            const response = await fetch((window.__apiBase || '/api').replace(/\/+$/, '') + '/inventaris/' + item.inventoryId + '/photo', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            if (!response.ok) {
                const error = await response.json().catch(function () { return {}; });
                throw new Error(error.message || 'Gagal menyimpan foto barang.');
            }
            const saved = await response.json();
            item.image = saved.item?.photo || '';
            saveMasterItems(getMasterItems());
            renderTable();
            openDetailModal(itemCode);
        } catch (error) {
            alert(error.message);
        } finally {
            this.value = '';
        }
    });


    function openAddItemModal() {
        document.getElementById('addItemForm').reset();
        document.getElementById('addItemImageName').textContent = 'Pilih foto barang dari komputer';
        document.getElementById('addItemModalOverlay').classList.add('active');
        setTimeout(function () { document.getElementById('addItemName').focus(); }, 50);
    }

    function closeAddItemModal() {
        document.getElementById('addItemModalOverlay').classList.remove('active');
    }

    async function saveNewItem() {
        const name = document.getElementById('addItemName').value.trim();
        const code = document.getElementById('addItemCode').value.trim().toUpperCase();
        const cat = document.getElementById('addItemCategory').value;
        const merk = document.getElementById('addItemMerk').value.trim() || 'SIPIBS';
        const stockTotal = Math.max(0, parseInt(document.getElementById('addItemStock').value) || 0);
        const imageInput = document.getElementById('addItemImage');
        const imageFile = imageInput.files[0];
        let image = '';

        if (imageFile) {
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(imageFile.type)) {
                alert('Foto harus berformat JPG, PNG, atau WEBP.');
                return;
            }
            if (imageFile.size > 2 * 1024 * 1024) {
                alert('Ukuran foto maksimal 2 MB.');
                return;
            }
            image = await new Promise(function (resolve, reject) {
                const reader = new FileReader();
                reader.onload = function () { resolve(reader.result); };
                reader.onerror = reject;
                reader.readAsDataURL(imageFile);
            });
        }

        if (!name || !code) {
            alert('Nama barang dan kode barang wajib diisi.');
            return;
        }

        const items = getMasterItems();
        if (items.some(function (item) { return String(item.code).toUpperCase() === code; })) {
            alert('Kode barang sudah digunakan.');
            return;
        }

        const newItem = {
            name: name,
            cat: cat,
            code: code,
            stockTotal: stockTotal,
icon: getItemIconClass(name, ''),
            image: image,
            merk: merk,
            condition: 'Baik'
        };

                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        try {
            const formData = new FormData();
            formData.append('name', name);
            formData.append('code', code);
            formData.append('category', cat);
            formData.append('brand', merk);
            formData.append('stock', stockTotal);
            if (imageFile) formData.append('photo_file', imageFile);

            const response = await fetch((window.__apiBase || '/api') + '/inventaris', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            if (!response.ok) {
                const error = await response.json().catch(() => ({}));
                throw new Error(error.message || 'Gagal menyimpan barang ke database.');
            }
            const saved = await response.json();
            newItem.inventoryId = saved.item?.id;
            newItem.image = saved.item?.photo || '';
        } catch (error) {
            alert(error.message);
            return;
        }
        items.unshift(newItem);
        saveMasterItems(items);

        const stocks = JSON.parse(localStorage.getItem('sipibsItemStock') || '{}');
        stocks[name] = stockTotal;
        localStorage.setItem('sipibsItemStock', JSON.stringify(stocks));

        currentPage = 1;
        closeAddItemModal();
        renderTable();
        window.dispatchEvent(new Event('storage'));
    }

    document.getElementById('btnOpenAddItem').addEventListener('click', openAddItemModal);
    document.getElementById('closeAddItemModal').addEventListener('click', closeAddItemModal);
    document.getElementById('btnCancelAddItem').addEventListener('click', closeAddItemModal);
    document.getElementById('btnSaveAddItem').addEventListener('click', saveNewItem);
    document.getElementById('addItemImage').addEventListener('change', function () {
        const label = document.getElementById('addItemImageName');
        label.textContent = this.files[0] ? this.files[0].name : 'Pilih foto barang dari komputer';
    });
    document.getElementById('addItemModalOverlay').addEventListener('click', function (event) {
        if (event.target === this) closeAddItemModal();
    });

function mergeServerItems(serverItems) {
        if (!serverItems || !serverItems.length) return;
        const items = serverItems.map(function (src) {
            const total = Math.max(parseInt(src.available_quantity, 10) || 0, parseInt(src.total_quantity, 10) || 0);
            return {
                name: src.name,
                cat: src.category || 'Elektronik',
                code: src.code,
                stockTotal: total,
                icon: getItemIconClass(src.name, ''),
                image: src.photo || '',
                merk: src.brand || 'SIPIBS',
                condition: src.condition || 'Baik',
                inventoryId: src.id
            };
        });
        saveMasterItems(items);
    }

    // Initial render
    renderTable();

    // Live stock sync (server = source of truth)
    function refreshServerStock() {
        if (!window.syncStockFromServer) return;
        window.syncStockFromServer(function (serverItems) {
            mergeServerItems(serverItems);
            renderTable();
        });
    }

    refreshServerStock();
    window.addEventListener('focus', refreshServerStock);

    // Listen to storage sync
    window.addEventListener('storage', renderTable);
});








