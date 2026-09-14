@php
    $active = 'condition';
    $title = 'Kondisi Barang - SIPIBS Admin';
    $categories = \Illuminate\Support\Facades\DB::table('item_categories')->pluck('name', 'id');
    $items = \App\Models\InventoryItem::orderBy('name')->get();
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
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=26">
    <style>
        .condition-admin-wrap { padding: 30px; }
        .condition-admin-head { display:flex; justify-content:space-between; gap:20px; align-items:flex-start; margin-bottom:24px; }
        .condition-admin-head h1 { margin:0 0 7px; color:#003985; font-size:29px; }
        .condition-admin-head p { margin:0; color:#64748b; }
        .condition-layout { display:grid; grid-template-columns:minmax(0,1.7fr) minmax(310px,.9fr); gap:22px; align-items:start; }
        .condition-panel { background:#fff; border:1px solid #bfd1e7; border-radius:12px; overflow:hidden; }
        .condition-panel h2 { margin:0; padding:20px 22px; color:#003985; font-size:19px; border-bottom:1px solid #e2e8f0; }
        .condition-table-admin { width:100%; border-collapse:collapse; }
        .condition-table-admin th { background:#e3f4ff; color:#26415f; text-align:left; padding:14px 16px; font-size:12px; letter-spacing:.04em; }
        .condition-table-admin td { padding:14px 16px; border-top:1px solid #e7edf4; color:#334155; vertical-align:middle; }
        .condition-product { display:flex; gap:11px; align-items:center; min-width:220px; }
        .condition-product img,.condition-image-placeholder { width:42px; height:42px; object-fit:cover; border-radius:8px; background:#e3f4ff; display:grid; place-items:center; color:#003985; }
        .condition-product strong { display:block; color:#123a68; font-size:13px; }
        .condition-product small { color:#64748b; }
        .condition-pill { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-weight:800; font-size:11px; }
        .condition-pill.baik { color:#047857; background:#d9f8e8; }.condition-pill.perlu_servis { color:#b45309; background:#fff0c7; }.condition-pill.rusak { color:#dc2626; background:#fee2e2; }
        .edit-condition { border:0; background:#0f5fc0; color:#fff; border-radius:7px; padding:8px 10px; font-weight:700; cursor:pointer; }
        .condition-form { padding:22px; }.condition-form.empty { color:#64748b; line-height:1.6; }
        .condition-form label { display:block; color:#334155; font-size:13px; font-weight:700; margin:15px 0 7px; }.condition-form input,.condition-form select { width:100%; box-sizing:border-box; border:1px solid #cbd5e1; border-radius:8px; padding:11px; font:inherit; color:#1e293b; background:#fff; }
        .condition-form input[readonly] { background:#f8fafc; }.condition-form button { width:100%; margin-top:22px; border:0; border-radius:8px; padding:12px; background:#003985; color:#fff; font-weight:800; cursor:pointer; }
        .condition-note { padding:0 22px 20px; color:#64748b; font-size:12px; line-height:1.5; }
        @media(max-width:1100px){.condition-layout{grid-template-columns:1fr}.condition-admin-wrap{padding:20px}.condition-table-admin{font-size:13px}}
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-logo">
            @include('admin.partials.sipibs-logo')
            <div class="sidebar-title" style="font-size:22px;">SIPIBS</div><div class="sidebar-subtitle">INVENTORY SYSTEM</div>
            <div style="margin-top:12px;"><span class="status blue">ADMIN PANEL</span></div>
        </div>
        <div class="menu-caption">MAIN MENU</div>
        <nav class="nav-list">
            <a class="nav-item" href="{{ url('/dashboard-admin') }}"><i class="bi bi-house-fill"></i> Dashboard</a>
            <div class="menu-caption nav-caption">MASTER DATA</div>
            <a class="nav-item" href="{{ url('/admin/data-master') }}"><i class="bi bi-box-seam-fill"></i> Data Master <span style="margin-left:auto;">›</span></a>
            <a class="nav-item active" href="{{ url('/admin/kondisi-barang') }}"><i class="bi bi-clipboard2-pulse-fill"></i> Kondisi Barang <span style="margin-left:auto;">›</span></a>
            <a class="nav-item" href="{{ url('/admin/data-user') }}"><i class="bi bi-people-fill"></i> Data User <span style="margin-left:auto;">›</span></a>
            <div class="menu-caption nav-caption">TRANSAKSI</div>
            <a class="nav-item" href="{{ url('/admin/peminjaman') }}"><i class="bi bi-journal-check"></i> Peminjaman</a>
            <a class="nav-item" href="{{ url('/admin/pengembalian') }}"><i class="bi bi-card-checklist"></i> Pengembalian</a>
            <a class="nav-item" href="{{ url('/admin/denda') }}"><i class="bi bi-cash-coin"></i> Denda</a>
            <div class="menu-caption nav-caption">LAPORAN</div><a class="nav-item" href="{{ url('/admin/laporan') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <div class="menu-caption nav-caption">AKUN</div><a class="nav-item" href="{{ url('/admin/profil') }}"><i class="bi bi-person-fill"></i> Profil</a><a class="nav-item" href="{{ url('/admin/logout') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
        <div class="admin-profile"><img class="avatar-target" src="{{ asset("images/PROFIL.png") }}" alt="Admin"><div><strong>Online</strong><span>Administrator<br>Super Admin</span></div></div>
    </aside>
    <main class="main-area">
                <header class="topbar">
            <div class="page-label">Kondisi Barang</div>
            <div class="top-actions">
                @include('admin.partials.notification-bell')
                
                <div class="top-user">
                    <div><strong id="top-user-name">Admin</strong><span>Administrator</span></div>
                    <img class="top-avatar avatar-target" src="{{ asset('images/PROFIL.png') }}" alt="Admin">
                </div>
            </div>
        </header>
        <section class="condition-admin-wrap">
            
            <div class="condition-admin-head"><div><h1>Kondisi Barang</h1><p>Output kondisi inventaris dan input pembaruan pemeriksaan barang.</p></div></div>
            @if(session('success'))<div style="margin:0 0 18px;padding:12px 16px;border-radius:8px;background:#dcfce7;color:#166534;font-weight:700;">{{ session('success') }}</div>@endif
            <div class="condition-layout">
                <div class="condition-panel"><h2>Output Kondisi Inventaris <span style="font-size:13px;color:#64748b;font-weight:500;">({{ $items->count() }} barang)</span></h2>
                    <div style="overflow:auto;"><table class="condition-table-admin"><thead><tr><th>BARANG</th><th>KODE</th><th>KONDISI</th><th>AKSI</th></tr></thead><tbody>
                    @forelse($items as $item)
                        @php $state = match($item->condition) { 'rusak' => ['Rusak Berat','rusak'], 'perlu_servis' => ['Rusak Ringan','perlu_servis'], default => ['Baik','baik'] }; $photo = $item->photo ? ((str_starts_with($item->photo, 'http') || str_starts_with($item->photo, '/') || str_starts_with($item->photo, 'storage/')) ? $item->photo : asset('images/' . $item->photo)) : null; @endphp
                        <tr><td><div class="condition-product">@if($photo)<img src="{{ $photo }}" alt="{{ $item->name }}">@else<div class="condition-image-placeholder"><i class="bi bi-box-seam"></i></div>@endif<div><strong>{{ $item->name }}</strong><small>{{ $categories[$item->item_category_id] ?? 'Lainnya' }}</small></div></div></td><td><code>{{ $item->code }}</code></td><td><span class="condition-pill {{ $state[1] }}"><i class="bi bi-circle-fill"></i>{{ $state[0] }}</span></td><td><button type="button" class="edit-condition" data-id="{{ $item->id }}" data-name="{{ e($item->name) }}" data-code="{{ $item->code }}"  data-condition="{{ $item->condition }}">Ubah</button></td></tr>
                    @empty<tr><td colspan="4" style="text-align:center;padding:28px;color:#64748b;">Belum ada barang inventaris.</td></tr>@endforelse
                    </tbody></table></div>
                </div>
                <aside class="condition-panel"><h2>Input Kondisi Barang</h2><form class="condition-form" method="POST" id="conditionForm" action="{{ url('/admin/kondisi-barang/0') }}">@csrf @method('PATCH')
                    <label>Barang</label><input id="itemName" readonly placeholder="Klik tombol Ubah pada barang">
                    <label>Kode Barang</label><input id="itemCode" readonly placeholder="-">
                    
                    <label for="checked_at">Tanggal Pemeriksaan</label><div class="date-input-wrap"><input id="checked_at" type="date" name="checked_at" required aria-label="Tanggal Pemeriksaan"></div>
                    <label for="condition">Kondisi</label><select id="condition" name="condition" required><option value="baik">Baik</option><option value="perlu_servis">Rusak Ringan / Perlu Servis</option><option value="rusak">Rusak Berat</option></select>
                    <button type="submit"><i class="bi bi-save"></i> Simpan Pembaruan</button></form><div class="condition-note">Pilih barang pada tabel. Pembaruan langsung tersimpan ke database dan tampil pada halaman kondisi pengguna.</div></aside>
            </div>
        </section>
    </main>
</div>
<script>
    document.querySelectorAll('.edit-condition').forEach(function(button) { button.addEventListener('click', function() { const item = button.dataset; document.getElementById('conditionForm').action = '{{ url('/admin/kondisi-barang') }}/' + item.id; document.getElementById('itemName').value = item.name; document.getElementById('itemCode').value = item.code;  document.getElementById('condition').value = item.condition || 'baik'; document.getElementById('checked_at').focus(); }); });
    document.querySelector('.date-input-wrap').addEventListener('click', function () {
        const input = document.getElementById('checked_at');
        input.focus();
        if (input.showPicker) input.showPicker();
    });
</script>
@include("admin.partials.profile-sync")
</body>
</html>


