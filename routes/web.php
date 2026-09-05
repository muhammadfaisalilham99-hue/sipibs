<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('landing'))->name('landing');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login-siswa', [AuthController::class, 'showLogin']);
Route::get('/login-admin', [AuthController::class, 'showAdminLogin']);
Route::post('/login-admin', [AuthController::class, 'adminLogin'])->name('admin.login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', fn () => view('user.dashboard'))->name('dashboard');
Route::get('/dashboard-user', fn () => view('user.dashboard'))->name('dashboard.user');
Route::get('/dashboard-admin', fn () => view('admin.dashboard'))->name('dashboard.admin');
Route::get('/katalog-alat', fn () => view('user.katalog-alat'))->name('katalog.alat');
Route::get('/kondisi-barang', fn () => view('user.kondisi-barang'))->name('kondisi.barang');
Route::get('/detail-kondisi-barang/{code?}', fn ($code = null) => view('user.detail-kondisi-barang', ['code' => $code]))->name('detail.kondisi.barang');
Route::get('/peminjaman-user', fn () => view('user.peminjaman'))->name('peminjaman.user');
Route::get('/peminjaman', fn () => view('user.peminjaman'))->name('peminjaman');
Route::get('/pengembalian-user', fn () => view('user.pengembalian'))->name('pengembalian.user');
Route::get('/pengembalian', fn () => view('user.pengembalian'))->name('pengembalian');
Route::get('/denda-user', fn () => view('user.denda'))->name('denda.user');
Route::get('/denda', fn () => view('user.denda'))->name('denda');
Route::get('/profil-user', fn () => view('user.profil'))->name('profil.user');
Route::get('/profil', fn () => view('user.profil'))->name('profil');
Route::get('/laporan-user', fn () => view('user.laporan'))->name('laporan.user');
Route::get('/riwayat-pinjam', fn () => view('user.riwayat-pinjam'))->name('riwayat.pinjam');
Route::get('/logout-user', fn () => view('user.profil', ['showLogout' => true]))->name('logout.user');

Route::get('/admin/data-master', fn () => view('admin.data-master'));
Route::get('/admin/data-user', function () {
    $dbUsers = \App\Models\User::where('role', '!=', 'admin')->orderBy('name')->get()->map(fn ($u) => [
        'name' => $u->name,
        'identity_number' => $u->identity_number ?: '-',
        'email' => $u->email,
        'role' => match ($u->role) {
            'guru' => 'Guru',
            default => 'Siswa',
        },
        'status' => 'Aktif',
        'photo' => '',
    ]);

    return view('admin.data-user', ['dbUsers' => $dbUsers]);
});
Route::get('/admin/kategori', fn () => view('admin.kategori'));
Route::get('/admin/peminjaman', fn () => view('admin.peminjaman'));
Route::get('/admin/pengembalian', fn () => view('admin.pengembalian'));
Route::get('/admin/denda', fn () => view('admin.denda'));
Route::get('/admin/laporan', fn () => view('admin.laporan'));
Route::get('/admin/profil', function () {
    $dbUsers = \App\Models\User::where('role', '!=', 'admin')->orderBy('id', 'desc')->limit(5)->get()->map(fn ($u) => [
        'name' => $u->name,
        'identity_number' => $u->identity_number ?: '-',
        'email' => $u->email,
        'role' => match ($u->role) {
            'guru' => 'Guru',
            default => 'Siswa',
        },
        'status' => 'Aktif',
        'photo' => $u->photo ?: '',
    ]);

    return view('admin.profil', ['dbUsers' => $dbUsers]);
});
Route::get('/admin/logout', fn () => view('admin.logout'));

Route::prefix('api')->middleware('auth')->group(function () {
    Route::post('/peminjaman', [PeminjamanController::class, 'submit']);
    Route::post('/peminjaman/list', [PeminjamanController::class, 'adminList']);
    Route::post('/peminjaman/{id}/decide', [PeminjamanController::class, 'decide']);

    Route::get('/pengembalian/items', [PengembalianController::class, 'items']);
    Route::get('/pengembalian/history', [PengembalianController::class, 'history']);
    Route::post('/pengembalian', [PengembalianController::class, 'submit']);
    Route::get('/pengembalian/admin/pending', [PengembalianController::class, 'adminPending']);
    Route::post('/pengembalian/{id}/verify', [PengembalianController::class, 'verify']);
});


Route::get('/bukti-peminjaman', function () {
    $data = [
        'id' => 'PMJ-2025-00067',
        'nama' => request('nama', 'Siswa'),
        'nis' => request('nim', '220401001'),
        'barang' => request('barang', 'Laptop Lenovo Ideapad Slim 3'),
        'jumlah' => request('jumlah', '1'),
        'tgl_pinjam' => request('tgl_pinjam', now()->toDateString()),
        'tgl_kembali' => request('tgl_kembali', now()->addDays(2)->toDateString()),
        'keperluan' => request('keperluan', 'Praktikum Pemrograman'),
    ];

    $escape = fn ($text) => str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], (string) $text);
    $lines = [
        'BUKTI PEMINJAMAN BARANG - SIPIBS',
        'ID Peminjaman: ' . $data['id'],
        'Nama Peminjam: ' . $data['nama'],
        'NIS / NIP: ' . $data['nis'],
        'Nama Barang: ' . $data['barang'],
        'Jumlah: ' . $data['jumlah'],
        'Tanggal Pinjam: ' . \Carbon\Carbon::parse($data['tgl_pinjam'])->format('d/m/Y'),
        'Tanggal Kembali: ' . \Carbon\Carbon::parse($data['tgl_kembali'])->format('d/m/Y'),
        'Keperluan: ' . $data['keperluan'],
        '',
        'Status: DISETUJUI',
        'Silakan tunjukkan bukti ini saat mengambil dan mengembalikan barang.',
    ];

    $stream = "BT\n/F1 18 Tf\n50 790 Td\n(" . $escape($lines[0]) . ") Tj\n/F1 11 Tf\n0 -34 Td\n";
    foreach (array_slice($lines, 1) as $line) {
        $stream .= '(' . $escape($line) . ") Tj\n0 -22 Td\n";
    }
    $stream .= 'ET';

    $objects = [];
    $objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
    $objects[] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
    $objects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n";
    $objects[] = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
    $objects[] = "5 0 obj\n<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream\nendobj\n";

    $pdf = "%PDF-1.4\n";
    $offsets = [0];
    foreach ($objects as $object) {
        $offsets[] = strlen($pdf);
        $pdf .= $object;
    }
    $xref = strlen($pdf);
    $pdf .= "xref\n0 6\n0000000000 65535 f \n";
    for ($i = 1; $i <= 5; $i++) {
        $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
    }
    $pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

    return response($pdf, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="bukti-peminjaman-' . $data['id'] . '.pdf"',
    ]);
})->name('bukti.peminjaman');
