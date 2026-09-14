<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

// Public routes
Route::get('/', fn () => view('landing'))->name('landing');

// Auth routes (Siswa / Guru)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login-siswa', [AuthController::class, 'showLogin']);
Route::get('/login-guru', [AuthController::class, 'showTeacherLogin'])->name('teacher.login');
Route::post('/login-guru', [AuthController::class, 'teacherLogin'])->name('teacher.login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Auth routes (Admin)
Route::get('/login-admin', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/login-admin', [AuthController::class, 'adminLogin'])->name('admin.login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Siswa / Guru (User) routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', fn () => view('user.dashboard'))->name('dashboard');
    Route::get('/dashboard-user', fn () => view('user.dashboard'))->name('dashboard.user');
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
    Route::post('/profil-user/photo', function (Illuminate\Http\Request $request) {
        $request->validate([
            'photo' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photo = '/storage/' . $request->file('photo')->store('profile-photos', 'public');
        $request->user()->update(['photo' => $photo]);

        return response()->json(['photo' => $photo]);
    })->name('profil.user.photo');
    Route::post('/profil-user/password', function (Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $request->user()->password)) {
            return response()->json(['message' => 'Password lama tidak sesuai.'], 422);
        }

        $request->user()->update(['password' => Hash::make($validated['password'])]);

        return response()->json(['ok' => true, 'message' => 'Password berhasil diubah.']);
    })->name('profil.user.password');    Route::post('/profil-user/identity-document', function (Illuminate\Http\Request $request) {
        $request->validate(['identity_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']]);
        $document = '/storage/' . $request->file('identity_document')->store('identity-documents', 'public');
        $request->user()->update(['identity_document' => $document]);
        return response()->json(['identity_document' => $document]);
    })->name('profil.user.identity-document');

    Route::post('/profil-user', function (Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'class_name' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->fill($validated)->save();

        return response()->json(['ok' => true]);
    });
    Route::get('/profil', fn () => view('user.profil'))->name('profil');
    Route::get('/laporan-user', fn () => view('user.laporan'))->name('laporan.user');
    Route::get('/riwayat-pinjam', fn () => view('user.riwayat-pinjam'))->name('riwayat.pinjam');
    Route::get('/logout-user', fn () => view('user.profil', ['showLogout' => true]))->name('logout.user');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', function () {
        return view('admin.dashboard', [
            'totalItems' => \App\Models\InventoryItem::count(),
            'borrowedItems' => \App\Models\Borrowing::where('status', 'dipinjam')->count(),
            'pendingLoans' => \App\Models\Borrowing::where('status', 'menunggu')->count(),
            'returnsToday' => \App\Models\ReturnRecord::whereDate('created_at', now()->toDateString())->count(),
            'recentLoans' => \App\Models\Borrowing::with(['user', 'item'])->latest('id')->take(5)->get(),
        ]);
    })->name('dashboard.admin');

    Route::get('/admin/data-master', fn () => view('admin.data-master'));
    Route::get('/admin/kondisi-barang', fn () => view('admin.kondisi-barang'));
    Route::patch('/admin/kondisi-barang/{id}', [App\Http\Controllers\InventarisController::class, 'updateCondition']);

    Route::get('/admin/data-user', function () {
        $dbUsers = \App\Models\User::where('role', '!=', 'admin')->orderBy('name')->get()->map(fn ($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'identity_number' => $u->identity_number ?: '',
            'email' => $u->email,
            'role' => match ($u->role) {
                'guru' => 'Guru',
                default => 'Siswa',
            },
            'status' => 'Aktif',
            'photo' => '',
            'class_name' => $u->class_name ?: '',
            'birth_date' => $u->birth_date ? \Carbon\Carbon::parse($u->birth_date)->format('Y-m-d') : '',
            'gender' => $u->gender ?: 'Laki-laki',
            'identity_document' => $u->identity_document ?: '',
        ]);

        return view('admin.data-user', ['dbUsers' => $dbUsers]);
    });
    Route::patch('/admin/data-user/{user}', function (Illuminate\Http\Request $request, \App\Models\User $user) {
        abort_unless($user->role !== 'admin', 404);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
            'identity_number' => ['nullable', 'string', 'max:100'],
            'class_name' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:Laki-laki,Perempuan'],
            'identity_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);
        if ($request->hasFile('identity_document')) {
            $validated['identity_document'] = '/storage/' . $request->file('identity_document')->store('identity-documents', 'public');
        }
        $user->update($validated);
        return redirect('/admin/data-user')->with('success', 'Data pengguna berhasil diperbarui.');
    });
    Route::get('/admin/kategori', fn () => view('admin.kategori'));
    Route::get('/admin/peminjaman', fn () => view('admin.peminjaman'));
    Route::get('/admin/pengembalian', function () {
        $pendingReturns = \App\Models\ReturnRecord::with(['borrowing.user', 'borrowing.item'])
            ->where('status', 'menunggu')
            ->orderByDesc('id')
            ->get()
            ->map(function ($return) {
                $borrowing = $return->borrowing;
                $item = $borrowing ? $borrowing->item : null;
                $user = $borrowing ? $borrowing->user : null;

                return [
                    'id' => 'DBRET-' . $return->id,
                    'dbReturnId' => (string) $return->id,
                    'loanId' => $return->borrowing_id,
                    'borrower' => $user ? $user->name : ($return->borrower_name ?: '-'),
                    'serial' => $item ? $item->code : ($return->item_code ?: '-'),
                    'itemName' => $item ? $item->name : ($return->item_name ?: '-'),
                    'quantity' => $return->returned_quantity ?: 1,
                    'condition' => match ($return->condition) {
                        'perlu_servis' => 'Rusak Ringan',
                        'rusak' => 'Rusak Berat',
                        default => 'Baik (Fungsional & Bersih)',
                    },
                    'adminNote' => $return->notes ?: '',
                    'photos' => is_array($return->photos) ? $return->photos : [],
                    'submittedAt' => optional($return->return_date)->format('d/m/Y') ?: '-',
                    'status' => 'menunggu',
                    'source' => 'db'
                ];
            })
            ->values();

        return view('admin.pengembalian', ['pendingReturns' => $pendingReturns]);
    });
    Route::get('/admin/denda', fn () => view('admin.denda'));
    Route::get('/admin/laporan', fn () => view('admin.laporan'));
    Route::get('/admin/profil', function () {
        $dbUsers = \App\Models\User::where('role', '!=', 'admin')->orderBy('id', 'desc')->limit(5)->get()->map(fn ($u) => [
            'name' => $u->name,
            'identity_number' => $u->identity_number ?: '-',
            'email' => $u->email,
            'role' => match ($u->role) {
                'guru' => 'Akun Guru',
                default => 'Akun Siswa',
            },
            'status' => 'Aktif',
            'photo' => $u->photo ?: '',
        ]);

        return view('admin.profil', [
            'dbUsers' => $dbUsers,
            'totalUsers' => \App\Models\User::where('role', '!=', 'admin')->count(),
            'activeUsers' => \App\Models\User::where('role', '!=', 'admin')->count(),
            'pendingVerifications' => \App\Models\ReturnRecord::where('status', 'menunggu')->count(),
        ]);
    });
    Route::get('/admin/logout', fn () => view('admin.logout'));
});

// Shared API routes
Route::prefix('api')->middleware('auth')->group(function () {
    Route::post('/peminjaman', [PeminjamanController::class, 'submit']);
    Route::post('/peminjaman/list', [PeminjamanController::class, 'adminList'])->middleware('role:admin');
    Route::get('/peminjaman/list', [PeminjamanController::class, 'adminList'])->middleware('role:admin');
    Route::get('/peminjaman/saya', [PeminjamanController::class, 'myLoans']);
    Route::post('/peminjaman/{id}/decide', [PeminjamanController::class, 'decide'])->middleware('role:admin');

    Route::get('/pengembalian/items', [PengembalianController::class, 'items']);
    Route::get('/pengembalian/history', [PengembalianController::class, 'history']);
    Route::post('/pengembalian', [PengembalianController::class, 'submit']);
    Route::get('/pengembalian/admin/pending', [PengembalianController::class, 'adminPending']);
    Route::post('/pengembalian/{id}/verify', [PengembalianController::class, 'verify']);

    Route::get('/denda/admin', [\App\Http\Controllers\DendaController::class, 'adminList']);
    Route::post('/denda/create-from-return', [\App\Http\Controllers\DendaController::class, 'createFromReturn']);
    Route::get('/denda/saya', [\App\Http\Controllers\DendaController::class, 'myFines']);
    Route::post('/denda/{id}/bayar', [\App\Http\Controllers\DendaController::class, 'pay']);

    Route::post('/inventaris', [InventarisController::class, 'store'])->middleware('role:admin');
    Route::get('/inventaris', [InventarisController::class, 'index']);
    Route::post('/inventaris/{id}/photo', [InventarisController::class, 'updatePhoto'])->middleware('role:admin');
    Route::post('/inventaris/{id}/stock', [InventarisController::class, 'adjustStock']);
    Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy'])->middleware('role:admin');
});

Route::get('/bukti-peminjaman', function () {
    $data = [
        'id' => request('id', request('kode', 'PMJ-' . now()->format('Ymd-His'))),
        'nama' => request('nama', 'Siswa'),
        'nis' => request('nim', request('nis', '-')),
        'barang' => request('barang', 'Barang Inventaris'),
        'jumlah' => request('jumlah', '1'),
        'tgl_pinjam' => request('tgl_pinjam', now()->toDateString()),
        'tgl_kembali' => request('tgl_kembali', now()->addDays(2)->toDateString()),
        'keperluan' => request('keperluan', 'Peminjaman barang sekolah'),
    ];

    $escape = static fn ($text) => str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], (string) $text);
    $date = static fn ($value) => \Carbon\Carbon::parse($value)->format('d/m/Y');
    $text = static fn ($x, $y, $size, $value, $font = 'F1') => "BT /{$font} {$size} Tf {$x} {$y} Td (" . $escape($value) . ") Tj ET\n";

    $stream = "q\n";
    $stream .= "0.04 0.25 0.55 rg\n0 742 595 100 re f\n";
    $stream .= "0.95 0.98 1 rg\n36 686 523 34 re f\n";
    $stream .= "0.88 0.93 0.99 RG\n36 686 523 34 re S\n";
    $stream .= "0.04 0.25 0.55 rg\n";
    $stream .= $text(42, 792, 24, 'SIPIBS', 'F2');
    $stream .= $text(42, 766, 10, 'Sistem Inventaris & Peminjaman Barang Sekolah');
    $stream .= "1 1 1 rg\n";
    $stream .= $text(250, 790, 10, 'BUKTI PEMINJAMAN', 'F2');
    $stream .= $text(276, 770, 10, 'BARANG', 'F2');
    $stream .= "0.04 0.25 0.55 rg\n";
    $stream .= $text(48, 698, 10, 'Nomor Bukti', 'F2') . $text(160, 698, 10, ': ' . $data['id']);
    $stream .= $text(325, 698, 10, 'Status', 'F2');
    $stream .= "0.08 0.55 0.32 rg\n36 648 110 25 re f\n1 1 1 rg\n";
    $stream .= $text(57, 657, 10, 'DISETUJUI', 'F2');
    $stream .= "0.04 0.25 0.55 rg\n";
    $stream .= $text(48, 620, 11, 'DATA PEMINJAM', 'F2');
    $stream .= "0.88 0.93 0.99 RG\n48 600 m 547 600 l S\n";
    $stream .= $text(58, 575, 10, 'Nama Peminjam', 'F2') . $text(205, 575, 10, ': ' . $data['nama']);
    $stream .= $text(58, 550, 10, 'NIS / NIP', 'F2') . $text(205, 550, 10, ': ' . $data['nis']);
    $stream .= $text(48, 505, 11, 'DETAIL PEMINJAMAN', 'F2');
    $stream .= "0.95 0.98 1 rg\n48 470 499 28 re f\n0.04 0.25 0.55 rg\n";
    $stream .= $text(58, 480, 9, 'BARANG', 'F2') . $text(280, 480, 9, 'JUMLAH', 'F2') . $text(390, 480, 9, 'TANGGAL PINJAM', 'F2');
    $stream .= $text(58, 442, 10, $data['barang']) . $text(280, 442, 10, $data['jumlah'] . ' unit') . $text(390, 442, 10, $date($data['tgl_pinjam']));
    $stream .= "0.88 0.93 0.99 RG\n48 425 m 547 425 l S\n";
    $stream .= $text(58, 398, 10, 'Tanggal Kembali', 'F2') . $text(205, 398, 10, ': ' . $date($data['tgl_kembali']));
    $stream .= $text(58, 373, 10, 'Keperluan', 'F2') . $text(205, 373, 10, ': ' . $data['keperluan']);
    $stream .= "0.95 0.98 1 rg\n48 286 499 45 re f\n0.04 0.25 0.55 rg\n";
    $stream .= $text(62, 310, 10, 'Simpan bukti ini dan tunjukkan kepada petugas saat mengambil');
    $stream .= $text(62, 294, 10, 'atau mengembalikan barang inventaris sekolah.');
    $stream .= "0.45 0.52 0.62 rg\n" . $text(48, 70, 9, 'SIPIBS • Sistem Inventaris & Peminjaman Barang Sekolah');
    $stream .= $text(465, 70, 9, 'Dicetak ' . now()->format('d/m/Y')); 
    $stream .= "Q";

    $objects = [
        "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
        "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
        "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>\nendobj\n",
        "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n",
        "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj\n",
        "6 0 obj\n<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream\nendobj\n",
    ];

    $pdf = "%PDF-1.4\n";
    $offsets = [0];
    foreach ($objects as $object) {
        $offsets[] = strlen($pdf);
        $pdf .= $object;
    }
    $xref = strlen($pdf);
    $pdf .= "xref\n0 7\n0000000000 65535 f \n";
    for ($i = 1; $i <= 6; $i++) {
        $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
    }
    $pdf .= "trailer\n<< /Size 7 /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

    return response($pdf, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="bukti-peminjaman-' . $data['id'] . '.pdf"',
    ]);
})->name('bukti.peminjaman');




