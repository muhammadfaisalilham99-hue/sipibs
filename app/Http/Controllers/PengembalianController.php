<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\ReturnRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function items(): JsonResponse
    {
        $borrowings = Borrowing::with(['item'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'dipinjam', 'disetujui'])
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'items' => $borrowings->map(fn ($b) => $this->formatBorrowing($b)),
        ]);
    }

    public function history(): JsonResponse
    {
        $returns = ReturnRecord::with(['borrowing.item'])
            ->whereHas('borrowing', fn ($q) => $q->where('user_id', Auth::id()))
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'history' => $returns->map(function (ReturnRecord $r) {
                $borrowing = $r->borrowing;
                $item = $borrowing ? $borrowing->item : null;

                return [
                    'id' => $r->id,
                    'loanId' => $borrowing ? $borrowing->id : null,
                    'itemName' => $item ? $item->name : '-',
                    'serial' => $item ? $item->code : '-',
                    'dateDisplay' => optional($r->return_date)->format('d M Y'),
                    'condition' => $this->conditionLabel($r->condition),
                    'note' => $r->notes ?: '-',
                    'status' => $this->returnStatusLabel($r->status),
                ];
            }),
        ]);
    }

    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'borrowing_id' => ['required', 'integer'],
            'condition' => ['nullable', 'in:baik,perlu_servis,rusak'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['nullable', 'string'],
        ]);

        $borrowing = Borrowing::where('id', $validated['borrowing_id'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'dipinjam', 'disetujui'])
            ->first();

        if (! $borrowing) {
            return response()->json([
                'message' => 'Peminjaman tidak ditemukan atau sudah tidak aktif.',
            ], 404);
        }

        $returnRecord = DB::transaction(function () use ($borrowing, $validated) {
            $record = ReturnRecord::create([
                'borrowing_id' => $borrowing->id,
                'return_date' => now()->toDateString(),
                'returned_quantity' => $borrowing->quantity,
                'condition' => $validated['condition'] ?? 'baik',
                'status' => 'menunggu',
                'notes' => $validated['notes'] ?? null,
                'photos' => $validated['photos'] ?? [],
            ]);

            $borrowing->status = 'dikembalikan';
            $borrowing->save();

            if ($borrowing->item) {
                $item = $borrowing->item;
                if ($item->borrowed_quantity >= $borrowing->quantity) {
                    $item->borrowed_quantity = $item->borrowed_quantity - $borrowing->quantity;
                } else {
                    $item->borrowed_quantity = 0;
                }
                $item->available_quantity = min($item->total_quantity, $item->available_quantity + $borrowing->quantity);
                $item->save();
            }

            return $record;
        });

        return response()->json([
            'message' => 'Pengembalian berhasil dikonfirmasi dan menunggu verifikasi admin.',
            'return' => [
                'id' => $returnRecord->id,
                'code' => 'RTN-' . now()->year . '-' . str_pad((string) $returnRecord->id, 5, '0', STR_PAD_LEFT),
                'borrowing_id' => $borrowing->id,
                'itemName' => optional($borrowing->item)->name,
                'serial' => optional($borrowing->item)->code,
                'dateDisplay' => $returnRecord->return_date->format('d M Y'),
                'condition' => $this->conditionLabel($returnRecord->condition),
                'note' => $returnRecord->notes ?: '-',
                'status' => 'Menunggu Verifikasi',
            ],
        ], 201);
    }

    public function verify(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:accepted,problem'],
        ]);

        $record = ReturnRecord::findOrFail($id);

        if ($record->status !== 'menunggu') {
            return response()->json([
                'message' => 'Pengembalian sudah diverifikasi sebelumnya.',
            ], 422);
        }

        $record->status = $validated['action'] === 'accepted' ? 'diterima' : 'bermasalah';
        $record->received_by = Auth::id();
        $record->save();

        return response()->json([
            'message' => 'Pengembalian berhasil diverifikasi.',
        ]);
    }

    public function adminPending(): JsonResponse
    {
        $records = ReturnRecord::with(['borrowing.user', 'borrowing.item'])
            ->where('status', 'menunggu')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'returns' => $records->map(function (ReturnRecord $r) {
                $borrowing = $r->borrowing;
                $item = $borrowing ? $borrowing->item : null;
                $user = $borrowing ? $borrowing->user : null;

                return [
                    'id' => $r->id,
                    'borrowing_id' => $r->borrowing_id,
                    'borrower' => $user ? $user->name : '-',
                    'identity_number' => $user ? ($user->identity_number ?: '-') : '-',
                    'itemName' => $item ? $item->name : '-',
                    'serial' => $item ? $item->code : '-',
                    'quantity' => $r->returned_quantity,
                    'condition' => $this->conditionLabel($r->condition),
                    'notes' => $r->notes ?: '-',
                    'returnedAt' => $r->return_date->format('d/m/Y'),
                    'photos' => $r->photos ?: [],
                    'status' => $r->status,
                    'dueDate' => optional($borrowing)->due_date ? $borrowing->due_date->format('d/m/Y') : '-',
                ];
            }),
        ]);
    }

    protected function formatBorrowing(Borrowing $borrowing): array
    {
        $item = $borrowing->item;

        return [
            'id' => $borrowing->id,
            'borrowing_id' => $borrowing->id,
            'barang' => $item ? $item->name : '-',
            'item_id' => $item ? $item->id : null,
            'serial' => $item ? $item->code : '-',
            'code' => $item ? $item->code : '-',
            'jumlah' => $borrowing->quantity,
            'tanggalPinjam' => optional($borrowing->borrow_date)->format('Y-m-d'),
            'tanggalKembali' => optional($borrowing->due_date)->format('Y-m-d'),
            'status' => $borrowing->status,
            'image' => $item ? $item->photo : null,
        ];
    }

    protected function conditionLabel(string $condition): string
    {
        return match ($condition) {
            'perlu_servis' => 'Rusak Ringan',
            'rusak' => 'Rusak Berat',
            default => 'Baik (Fungsional & Bersih)',
        };
    }

    protected function returnStatusLabel(string $status): string
    {
        return match ($status) {
            'diterima' => 'Diterima Admin',
            'bermasalah' => 'Terdapat Masalah',
            default => 'Menunggu Verifikasi',
        };
    }
}
