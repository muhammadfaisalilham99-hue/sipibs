<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kondisi Barang - SIPIBS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/sipibs-ui.css') }}?v=19">
</head>
<body>
@include('user.partials.local-storage-cleanup')
@php
    $userName = Auth::check() ? Auth::user()->name : 'Dina Atalia';
    $categoryNames = \Illuminate\Support\Facades\DB::table('item_categories')->pluck('name', 'id');
    $inventoryItem = \App\Models\InventoryItem::where('code', $code)->first();
    $item = [
        'name' => $inventoryItem ? $inventoryItem->name : 'Barang Tidak Ditemukan',
        'cat' => ($inventoryItem && isset($categoryNames[$inventoryItem->item_category_id])) ? $categoryNames[$inventoryItem->item_category_id] : 'Lainnya',
        'photo' => $inventoryItem ? $inventoryItem->photo : null,
        'history' => $inventoryItem ? $inventoryItem->conditionHistories : collect(),
];
    $selectedCode = $code;
    $latestHistory = $inventoryItem?->conditionHistories?->first();
    $badgeState = match ($inventoryItem?->condition) {
        'rusak' => ['RUSAK BERAT', 'red', 'Tidak Layak Pakai', 'Barang rusak, perlu perbaikan'],
        'perlu_servis' => ['RUSAK RINGAN', 'yellow', 'Kurang Layak', 'Barang mengalami kerusakan ringan'],
        default => ['BAIK', 'green', 'Layak Pakai', 'Tidak ada kerusakan'],
    };
@endphp
<div class="app-shell">
    <aside class="sidebar user-sidebar">
        <div class="sidebar-logo">
            @include('user.partials.sipibs-logo')
            <div class="sidebar-title">SIPIBS</div>
            <div class="sidebar-subtitle">INVENTORY</div>
        </div>
        <nav class="nav-list">
            <a class="nav-item" href="{{ url('/dashboard-user') }}"><i class="bi bi-grid"></i> Dashboard</a>
            <a class="nav-item" href="#" id="inventaris-toggle"><i class="bi bi-box-seam"></i> Inventaris <span class="nav-arrow">^</span></a>
            <div class="nav-sub-list collapsed" id="inventaris-sub">
                <a class="nav-sub-item" href="{{ url('/katalog-alat') }}">Katalog Barang</a>
                <a class="nav-sub-item active" href="{{ url('/kondisi-barang') }}">Kondisi Barang</a>
            </div>
            <a class="nav-item" href="{{ url('/peminjaman-user') }}"><i class="bi bi-gem"></i> Peminjaman</a>
            <a class="nav-item" href="{{ url('/pengembalian-user') }}"><i class="bi bi-calendar-check"></i> Pengembalian</a>
            <a class="nav-item" href="{{ url('/denda-user') }}"><i class="bi bi-cash-coin"></i> Denda</a>
            <a class="nav-item" href="{{ url('/laporan-user') }}"><i class="bi bi-bar-chart-fill"></i> Laporan</a>
            <a class="nav-item" href="{{ url('/riwayat-pinjam') }}"><i class="bi bi-clock-history"></i> Riwayat Pinjam</a>
            <a class="nav-item" href="{{ url('/profil-user') }}"><i class="bi bi-person"></i> Profil</a>
            <a class="nav-item" href="{{ url('/logout-user') }}"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </nav>
    </aside>
    <main class="main-area">
        <header class="topbar">
            <div class="search-box condition-search"><i class="bi bi-search"></i> Cari barang atau kode inventaris...</div>
            <div class="top-actions">
                @include('user.partials.notification-bell')

                <div class="top-user"><div><strong id="top-user-name">{{ $userName }}</strong><span>{{ Auth::check() && Auth::user()->role === 'guru' ? 'GURU' : 'SISWA' }}</span></div><img class="top-avatar" id="top-avatar" src="{{ asset('images/PROFIL.png') }}" alt="Avatar"></div>
            </div>
        </header>
        <section class="content catalog-page-content condition-page-content">
            <div class="catalog-page-head">
                <div>
                    <div class="catalog-breadcrumb">Home <i class="bi bi-chevron-right"></i> Inventaris <i class="bi bi-chevron-right"></i> Kondisi Barang <i class="bi bi-chevron-right"></i> <span>Detail</span></div>
                    <h1>Detail Kondisi Barang</h1>
                    <p>Informasi detail kondisi barang inventaris.</p>
                </div>
                <div class="catalog-page-actions"><a class="filter-btn detail-back" href="{{ url('/kondisi-barang') }}"><i class="bi bi-arrow-left"></i> Kembali</a></div>
            </div>
            <div class="detail-condition-grid">
                <div class="detail-condition-card main">
                    <div class="detail-condition-hero">
                        @if($item['photo'])
                            <img src="{{ (str_starts_with($item['photo'], 'http') || str_starts_with($item['photo'], '/') || str_starts_with($item['photo'], 'storage/')) ? $item['photo'] : asset('images/' . $item['photo']) }}" alt="{{ $item['name'] }}">
                        @else
                            <i class="bi bi-box-seam"></i>
                        @endif
                    </div>
                    <h2>{{ $item['name'] }}</h2>
                    <span class="condition-code">{{ $selectedCode }}</span>
                    <p>{{ $item['cat'] }}</p>
<span class="condition-badge {{ $badgeState[1] }}" id="detailConditionBadge"><i class="bi bi-circle-fill"></i> {{ $badgeState[0] }}</span>
                </div>
                <div class="detail-condition-card">
                    <h3>Ringkasan Kondisi</h3>
                    <div class="detail-row"><span>Status</span><strong id="detailConditionRingkasan">{{ $badgeState[2] }}</strong></div>
                    <div class="detail-row"><span>Pemeriksaan Terakhir</span><strong>{{ $latestHistory?->checked_at?->format('d F Y') ?? 'Belum ada pemeriksaan' }}</strong></div>
                    <div class="detail-row"><span>Petugas</span><strong>{{ $latestHistory?->officer ?: 'Sarpras SIPIBS' }}</strong></div>
                    <div class="detail-row"><span>Catatan</span><strong id="detailConditionCatatan">{{ $latestHistory?->notes ?: 'Tidak ada kerusakan' }}</strong></div>
                </div>
            </div>
            <div class="condition-table-card detail-history-card">
                <h3>Riwayat Kondisi</h3>
                                <table class="admin-table condition-table">
                    <thead><tr><th>Tanggal</th><th>Kondisi</th><th>Keterangan</th></tr></thead>
                    <tbody>
@foreach($item['history'] as $history)
                        <tr>
                            <td>{{ $history->checked_at?->format('d F Y') ?? '-' }}</td>
                            <td><span class="condition-badge {{ $history->condition === 'rusak' ? 'red' : ($history->condition === 'perlu_servis' ? 'yellow' : 'green') }}"><i class="bi bi-circle-fill"></i> {{ strtoupper(str_replace('_', ' ', $history->condition)) }}</span></td>
                            
                            <td>{{ $history->notes ?: '-' }}{{ $history->officer ? ' (Petugas: ' . $history->officer . ')' : '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<script>
    const invToggle = document.getElementById('inventaris-toggle');
    const invSub = document.getElementById('inventaris-sub');
    if (localStorage.getItem('invOpen') === '1') { invSub.classList.remove('collapsed'); invToggle.classList.add('open'); }
    invToggle.addEventListener('click', function (e) { e.preventDefault(); invSub.classList.toggle('collapsed'); invToggle.classList.toggle('open', !invSub.classList.contains('collapsed')); localStorage.setItem('invOpen', invSub.classList.contains('collapsed') ? '0' : '1'); });
    invSub.querySelectorAll('.nav-sub-item').forEach(function(link) { link.addEventListener('click', function() { localStorage.setItem('invOpen', '1'); }); });

(function () {
        const code = @json($selectedCode);
        const databaseCondition = @json($inventoryItem?->condition ?? 'baik');
        function condState(cond) {
            const c = String(cond || '').toUpperCase().replace(/_/g, ' ');
            if (c.includes('RUSAK BERAT') || c === 'RUSAK') return { label: 'RUSAK BERAT', status: 'red', ringkasan: 'Tidak Layak Pakai', catatan: 'Barang rusak, perlu perbaikan' };
            if (c.includes('RUSAK') || c.includes('SERVIS')) return { label: 'RUSAK RINGAN', status: 'yellow', ringkasan: 'Kurang Layak', catatan: 'Barang mengalami kerusakan ringan' };
            return { label: 'BAIK', status: 'green', ringkasan: 'Layak Pakai', catatan: 'Tidak ada kerusakan' };
        }
        let master = [];
        try { master = JSON.parse(localStorage.getItem('sipibsMasterItems') || '[]'); } catch (e) {}
        const item = code ? master.find(m => String(m.code).toUpperCase() === String(code).toUpperCase()) : null;
        const state = condState(item ? item.condition : databaseCondition);
        const badge = document.getElementById('detailConditionBadge');
        if (badge) {
            badge.className = 'condition-badge ' + state.status;
            badge.innerHTML = '<i class="bi bi-circle-fill"></i> ' + state.label;
        }
        const ringkasan = document.getElementById('detailConditionRingkasan');
        if (ringkasan) ringkasan.textContent = state.ringkasan;
        const catatan = document.getElementById('detailConditionCatatan');
        if (catatan) catatan.textContent = state.catatan;
    })();
</script>
<script src="{{ asset('js/user-notification.js') }}?v=5"></script>
@include('user.partials.profile-sync')
</body>
</html>






