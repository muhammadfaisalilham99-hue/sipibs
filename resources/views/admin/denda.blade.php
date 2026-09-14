@php
    $active = 'denda';
    $title = 'Denda';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}">
    <style>
        .denda-main{flex:1;background:#f5f8fd;min-height:100vh;font-family:Inter,sans-serif;color:#071735;overflow:auto}.denda-top{height:82px;display:flex;align-items:center;justify-content:space-between;padding:0 36px;background:#fff;border-bottom:1px solid #e3eaf5}.denda-top h1{margin:0;font-size:24px;font-weight:900}.breadcrumb{margin-top:8px;font-size:13px;color:#64748b}.breadcrumb a{color:#0058d4;text-decoration:none}.admin-mini{display:flex;align-items:center;gap:12px;font-weight:800}.admin-mini img{width:42px;height:42px;border-radius:50%;object-fit:cover;background:#eaf2ff}.denda-content{padding:34px 40px}.denda-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:24px}.denda-stat{background:#fff;border:1px solid #e0e8f4;border-radius:14px;padding:24px;display:flex;align-items:center;gap:18px;box-shadow:0 10px 26px rgba(15,23,42,.05)}.stat-icon{width:58px;height:58px;border-radius:16px;display:grid;place-items:center;background:linear-gradient(135deg,#2d7df0,#1457d9);color:#fff;font-size:25px}.stat-icon.green{background:linear-gradient(135deg,#61d98d,#1ea35b)}.stat-icon.yellow{background:linear-gradient(135deg,#ffd264,#f59e0b)}.stat-icon.purple{background:linear-gradient(135deg,#a071f2,#7c3aed)}.denda-stat span{display:block;color:#475569;font-size:13px}.denda-stat strong{display:block;margin:5px 0;font-size:26px;font-weight:900}.denda-stat small{color:#64748b}.denda-card{background:#fff;border:1px solid #e0e8f4;border-radius:14px;box-shadow:0 10px 26px rgba(15,23,42,.05);overflow:hidden}.denda-filter{display:flex;justify-content:space-between;gap:16px;padding:20px;border-bottom:1px solid #e5edf8}.filter-left{display:flex;gap:14px}.denda-input,.denda-select{height:44px;border:1px solid #cfdced;border-radius:9px;background:#fff;padding:0 14px;color:#1e293b;font-weight:600;outline:0}.searchbox{width:390px;height:44px;border:1px solid #cfdced;border-radius:9px;display:flex;align-items:center;gap:10px;padding:0 14px}.searchbox input{width:100%;border:0;outline:0;background:transparent}.denda-table{width:100%;border-collapse:collapse}.denda-table th{padding:16px 14px;background:#f8fbff;border-bottom:1px solid #e0e8f4;color:#14284f;font-size:12px;text-align:left}.denda-table td{padding:18px 14px;border-bottom:1px solid #edf2f7;font-size:13px;vertical-align:middle}.user-cell{display:flex;align-items:center;gap:12px}.avatar-sm{width:36px;height:36px;border-radius:50%;background:#e8f1ff;color:#0058d4;display:grid;place-items:center;font-size:18px}.user-cell strong{display:block}.user-cell small{display:block;color:#64748b;margin-top:3px}.loan-link{color:#0058d4;text-decoration:none;font-weight:800}.badge-paid,.badge-unpaid{display:inline-flex;border-radius:999px;padding:5px 12px;font-size:12px;font-weight:800}.badge-paid{background:#dcfce7;color:#15803d;border:1px solid #a7f3d0}.badge-unpaid{background:#fee2e2;color:#dc2626;border:1px solid #fecaca}.eye-btn{width:38px;height:38px;border-radius:9px;border:1px solid #dbe5f2;background:#fff;color:#0058d4;display:grid;place-items:center;cursor:pointer}.eye-btn:hover{background:#eff6ff}.denda-footer{display:flex;justify-content:space-between;align-items:center;padding:18px 20px;color:#475569;font-size:13px}.pages{display:flex;align-items:center;gap:7px}.page{width:36px;height:36px;border:1px solid #dbe5f2;border-radius:8px;display:grid;place-items:center;color:#0f172a;text-decoration:none}.page.active{background:#145fd8;color:#fff;border-color:#145fd8}@media(max-width:1200px){.denda-stats{grid-template-columns:repeat(2,1fr)}.denda-filter{flex-direction:column}.searchbox{width:100%}}@media(max-width:700px){.denda-content{padding:18px}.denda-stats{grid-template-columns:1fr}.denda-card{overflow:auto}.denda-table{min-width:980px}}
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-logo">
            @include('admin.partials.sipibs-logo')
            <div class="sidebar-title" style="font-size:22px;">SIPIBS</div>
            <div class="sidebar-subtitle">INVENTORY SYSTEM</div>
            <div style="margin-top:12px;"><span class="status blue">ADMIN PANEL</span></div>
        </div>
        <div class="menu-caption">MAIN MENU</div>
        <nav class="nav-list">
            <a class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ url('/dashboard-admin') }}"><i class="bi bi-house-fill"></i> Dashboard</a>
            <div class="menu-caption nav-caption">MASTER DATA</div>
            <a class="nav-item {{ $active === 'master' ? 'active' : '' }}" href="{{ url('/admin/data-master') }}"><i class="bi bi-box-seam-fill"></i> Data Master <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'condition' ? 'active' : '' }}" href="{{ url('/admin/kondisi-barang') }}"><i class="bi bi-clipboard2-pulse-fill"></i> Kondisi Barang <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'user' ? 'active' : '' }}" href="{{ url('/admin/data-user') }}"><i class="bi bi-people-fill"></i> Data User <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'kategori' ? 'active' : '' }}" href="{{ url('/admin/kategori') }}"><i class="bi bi-diagram-3-fill"></i> Kategori <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">TRANSAKSI</div>
            <a class="nav-item {{ $active === 'peminjaman' ? 'active' : '' }}" href="{{ url('/admin/peminjaman') }}"><i class="bi bi-journal-check"></i> Peminjaman <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'pengembalian' ? 'active' : '' }}" href="{{ url('/admin/pengembalian') }}"><i class="bi bi-card-checklist"></i> Pengembalian <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'denda' ? 'active' : '' }}" href="{{ url('/admin/denda') }}"><i class="bi bi-cash-coin"></i> Denda <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">LAPORAN</div>
            <a class="nav-item {{ $active === 'laporan' ? 'active' : '' }}" href="{{ url('/admin/laporan') }}"><i class="bi bi-bar-chart-fill"></i> Laporan <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">AKUN</div>
            <a class="nav-item {{ $active === 'profil' ? 'active' : '' }}" href="{{ url('/admin/profil') }}"><i class="bi bi-person-fill"></i> Profil <span style="margin-left:auto;">›</span></a>
            <a class="nav-item {{ $active === 'logout' ? 'active' : '' }}" href="{{ url('/admin/logout') }}"><i class="bi bi-box-arrow-left"></i> Logout <span style="margin-left:auto;">›</span></a>
        </nav>
        <div class="admin-profile"><img class="avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin"><div><strong>Online</strong><span>Administrator<br>Super Admin</span></div></div>
    </aside>

    <main class="denda-main">
        <header class="topbar">
            <div class="page-label">Denda</div>
            <div class="top-actions">
                @include('admin.partials.notification-bell')
                
                <div class="top-user">
                    <div><strong id="top-user-name">Admin</strong><span>Administrator</span></div>
                    <img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin">
                </div>
            </div>
        </header>
        <section class="denda-content">
            <div class="denda-stats">
                <div class="denda-stat"><div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div><div><span>Total Denda</span><strong id="statTotal">0</strong><small>Total seluruh denda</small></div></div>
                <div class="denda-stat"><div class="stat-icon green"><i class="bi bi-check-lg"></i></div><div><span>Lunas</span><strong id="statPaid">0</strong><small>Denda sudah dibayar</small></div></div>
                <div class="denda-stat"><div class="stat-icon yellow"><i class="bi bi-clock"></i></div><div><span>Belum Lunas</span><strong id="statUnpaid">0</strong><small>Denda belum dibayar</small></div></div>
                <div class="denda-stat"><div class="stat-icon purple"><i class="bi bi-currency-dollar"></i></div><div><span>Total Pendapatan Denda</span><strong id="statIncome">Rp 0</strong><small>Total dana denda masuk</small></div></div>
            </div>
            <div class="denda-card">
                <div class="denda-filter">
                    <div class="filter-left"><input class="denda-input" id="dateFilter" type="text" placeholder="Pilih tanggal awal - akhir"><select class="denda-select" id="typeFilter"><option value="all">Semua Jenis Denda</option><option value="Keterlambatan Pengembalian">Keterlambatan Pengembalian</option><option value="Kerusakan Barang">Kerusakan Barang</option><option value="Kehilangan Barang">Kehilangan Barang</option></select><select class="denda-select" id="statusFilter"><option value="all">Semua Status</option><option value="paid">Lunas</option><option value="unpaid">Belum Lunas</option></select></div>
                    <div class="searchbox"><input id="searchFilter" type="text" placeholder="Cari nama user / jenis denda..."><i class="bi bi-search"></i></div>
                </div>
                <table class="denda-table">
                    <thead><tr><th>No</th><th>Kode Denda</th><th>User</th><th>Jenis Denda</th><th>Peminjaman</th><th>Tanggal Denda</th><th>Jumlah</th><th>Status Pembayaran</th><th>Metode</th><th>Tanggal Bayar</th><th>Aksi</th></tr></thead>
                    <tbody id="dendaBody"></tbody>
                </table>
                <div class="denda-footer"><span id="dendaInfo">Menampilkan 0 data</span><div class="pages"><a class="page" href="#"><i class="bi bi-chevron-left"></i></a><a class="page active" href="#">1</a><a class="page" href="#">2</a><a class="page" href="#">3</a><a class="page" href="#"><i class="bi bi-chevron-right"></i></a><select class="denda-select" style="height:36px"><option>10 / halaman</option></select></div></div>
            </div>
        </section>
    </main>
</div>
<script>
    window.__apiBase = @json(rtrim(url('/api'), '/'));
    function rupiah(value){ const n=String(value||'0').replace(/\D/g,'')||'0'; return 'Rp '+Number(n).toLocaleString('id-ID'); }
    function initials(name){ return String(name||'U').split(' ').map(x=>x[0]).join('').slice(0,2).toUpperCase(); }
    function escapeHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    let allFines = [];
    function fineRows(){
        return allFines.map(function(row){
            const paid = String(row.status) === 'lunas';
            const date = (row.returnDate && row.returnDate !== '-') ? row.returnDate : (row.createdAt || '-');
            return {
                code: 'DN-' + (row.id || 0),
                user: row.borrower || 'Siswa',
                nis: row.identity_number || '-',
                type: row.fineType || 'Keterlambatan Pengembalian',
                loan: row.loanId || row.return_id || '-',
                date: date,
                amount: rupiah(row.fineAmount),
                status: paid ? 'Lunas' : 'Belum Lunas',
                method: (row.method && row.method !== '-') ? row.method : '-',
                paidAt: (row.paidAt && row.paidAt !== '-') ? row.paidAt : '-',
                raw: row
            };
        });
    }
    function renderDenda(){
        const body=document.getElementById('dendaBody');
        const q=(document.getElementById('searchFilter').value||'').toLowerCase();
        const status=document.getElementById('statusFilter').value;
        const type=document.getElementById('typeFilter').value;
        const rows=fineRows().filter(row =>
            (!q || [row.code,row.user,row.nis,row.type,row.loan].join(' ').toLowerCase().includes(q)) &&
            (status==='all' || (status==='paid' ? row.status==='Lunas' : row.status!=='Lunas')) &&
            (type==='all' || row.type===type));
        body.innerHTML = rows.length ? rows.map((row,i)=>`<tr><td>${i+1}</td><td>${row.code}</td><td><div class="user-cell"><div class="avatar-sm">${initials(row.user)}</div><div><strong>${escapeHtml(row.user)}</strong><small>${escapeHtml(row.nis)}</small></div></div></td><td>${escapeHtml(row.type)}</td><td><a class="loan-link" href="#">${escapeHtml(row.loan)}</a></td><td>${row.date}</td><td>${row.amount}</td><td><span class="${row.status==='Lunas'?'badge-paid':'badge-unpaid'}">${row.status}</span></td><td>${escapeHtml(row.method)}</td><td>${row.paidAt}</td><td><button class="eye-btn" type="button" data-id="${row.raw.id}" title="Lihat detail"><i class="bi bi-eye"></i></button></td></tr>`).join('') : '<tr><td colspan="11" style="text-align:center;padding:35px;color:#64748b;">Belum ada data denda.</td></tr>';
        document.getElementById('dendaInfo').textContent = rows.length ? `Menampilkan 1 - ${rows.length} dari ${rows.length} data` : 'Menampilkan 0 data';
        const paid=rows.filter(r=>r.status==='Lunas').length;
        const total=rows.length;
        const income=rows.filter(r=>r.status==='Lunas').reduce((s,r)=>s+(parseInt(String(r.amount).replace(/\D/g,''))||0),0);
        document.getElementById('statTotal').textContent=total;
        document.getElementById('statPaid').textContent=paid;
        document.getElementById('statUnpaid').textContent=total-paid;
        document.getElementById('statIncome').textContent=rupiah(income);
    }
    function loadFines(){
        fetch((window.__apiBase || '/api') + '/denda/admin', {
            method: 'GET',
            headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' }
        })
        .then(function(res){ if (!res.ok) throw new Error('fail'); return res.json(); })
        .then(function(data){
            allFines = (data && Array.isArray(data.fines)) ? data.fines : [];
            renderDenda();
        })
        .catch(function(){ renderDenda(); });
    }
    function showDetail(id){
        const row = allFines.find(f => f.id === id);
        if (!row) return;
        const overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(7,23,53,.55);z-index:999;display:flex;align-items:center;justify-content:center;padding:20px;';
        overlay.innerHTML = `<div style="background:#fff;border-radius:16px;max-width:540px;width:100%;padding:28px;font-family:Inter,sans-serif;color:#071735;box-shadow:0 24px 60px rgba(0,0,0,.25);position:relative;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <h3 style="margin:0;font-size:20px;font-weight:900;">Detail Denda</h3>
                <button type="button" style="border:none;background:#eef2f9;width:34px;height:34px;border-radius:9px;cursor:pointer;font-size:17px;" class="denda-modal-close">&times;</button>
            </div>
            <div style="font-size:14px;line-height:1.9;">
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Kode Denda</span><strong>DN-${row.id}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Peminjam</span><strong>${escapeHtml(row.borrower)}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">NIS / NIP</span><strong>${escapeHtml(row.identity_number)}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Barang</span><strong>${escapeHtml(row.itemName)}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">ID Peminjaman</span><strong>${escapeHtml(row.loanId)}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Jenis Denda</span><strong>${escapeHtml(row.fineType)}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Tanggal Ganti</span><strong>${row.returnDate}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Jatuh Tempo</span><strong>${row.dueDate}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Nominal</span><strong>${rupiah(row.fineAmount)}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Status</span><strong><span class="${row.status==='lunas'?'badge-paid':'badge-unpaid'}">${row.status==='lunas'?'Lunas':'Belum Lunas'}</span></strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Metode Bayar</span><strong>${escapeHtml(row.method)}</strong></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#64748b;">Tanggal Bayar</span><strong>${row.paidAt}</strong></div>
                <div style="margin-top:12px;padding:12px;background:#f5f8fd;border-radius:10px;color:#475569;">${escapeHtml(row.notes)}</div>
            </div>
        </div>`;
        const closeBtn = overlay.querySelector('.denda-modal-close');
        closeBtn.addEventListener('click', () => overlay.remove());
        overlay.addEventListener('click', function(e){ if (e.target === overlay) overlay.remove(); });
        document.body.appendChild(overlay);
    }
    document.getElementById('dendaBody').addEventListener('click', function(e){
        const btn = e.target.closest('.eye-btn');
        if (btn) { const id = parseInt(btn.dataset.id); if (!isNaN(id)) showDetail(id); }
    });
    document.getElementById('searchFilter').addEventListener('input', renderDenda);
    document.getElementById('statusFilter').addEventListener('change', renderDenda);
    document.getElementById('typeFilter').addEventListener('change', renderDenda);
    loadFines();
    setInterval(loadFines, 8000);
</script>
@include('admin.partials.sidebar-scroll')
@include('admin.partials.profile-sync')
<script src="{{ asset('js/admin-notification.js') }}?v=2"></script>
</body>
</html>

