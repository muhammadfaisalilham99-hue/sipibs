<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\InventoryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'inventory_item_id' => ['required', 'integer', 'exists:inventory_items,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'borrow_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:borrow_date'],
            'purpose' => ['nullable', 'string', 'max:500'],
        ]);

        $item = InventoryItem::findOrFail($validated['inventory_item_id']);
        $quantity = (int) ($validated['quantity'] ?? 1);

        if ($quantity < 1 || $item->available_quantity < $quantity) {
            return response()->json([
                'message' => 'Stok barang tidak mencukupi untuk dipinjam.',
            ], 422);
        }

        $borrowDate = $validated['borrow_date'] ?? now()->toDateString();
        $dueDate = $validated['due_date'] ?? now()->addDays(2)->toDateString();

        $borrowing = Borrowing::create([
            'user_id' => Auth::id(),
            'inventory_item_id' => $item->id,
            'quantity' => $quantity,
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'status' => 'menunggu',
            'purpose' => $validated['purpose'] ?? null,
        ]);

        return response()->json([
            'message' => 'Permintaan peminjaman berhasil diajukan.',
            'borrowing' => $this->formatBorrowing($borrowing->fresh(['item'])),
        ], 201);
    }

    public function decide(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:approved,rejected'],
        ]);

        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'menunggu') {
            return response()->json([
                'message' => 'Permintaan sudah diproses sebelumnya.',
            ], 422);
        }

        $newStatus = $validated['action'] === 'approved' ? 'dipinjam' : 'ditolak';

        DB::transaction(function () use ($borrowing, $newStatus) {
            $borrowing->status = $newStatus;
            $borrowing->approved_at = now()->toDateString();
            $borrowing->approved_by = Auth::id();
            $borrowing->save();

            if ($newStatus === 'dipinjam' && $borrowing->item) {
                $item = $borrowing->item;
                $item->available_quantity = max(0, $item->available_quantity - $borrowing->quantity);
                $item->borrowed_quantity = $item->borrowed_quantity + $borrowing->quantity;
                $item->save();
            }
        });

        return response()->json([
            'message' => $newStatus === 'dipinjam' ? 'Peminjaman disetujui.' : 'Peminjaman ditolak.',
            'borrowing' => $this->formatBorrowing($borrowing->fresh(['item'])),
        ]);
    }

    public function adminList(Request $request): JsonResponse
    {
        $query = Borrowing::with(['user', 'item'])->orderByDesc('id');

        $status = (string) $request->query('status', 'all');
        if ($status !== 'all' && in_array($status, ['menunggu', 'dipinjam', 'disetujui', 'ditolak', 'dikembalikan', 'terlambat'], true)) {
            $query->where('status', $status);
        }

        return response()->json([
            'borrowings' => $query->get()->map(fn ($b) => $this->formatBorrowing($b)),
        ]);
    }

    protected function formatBorrowing(Borrowing $borrowing): array
    {
        $item = $borrowing->item;
        $user = $borrowing->user;

        return [
            'id' => $borrowing->id,
            'borrowing_id' => $borrowing->id,
            'user_id' => $borrowing->user_id,
            'borrower' => $user ? $user->name : '-',
            'identity_number' => $user ? ($user->identity_number ?: '-') : '-',
            'barang' => $item ? $item->name : '-',
            'item_id' => $item ? $item->id : null,
            'serial' => $item ? $item->code : '-',
            'code' => $item ? $item->code : '-',
            'quantity' => $borrowing->quantity,
            'tanggalPinjam' => optional($borrowing->borrow_date)->format('d/m/Y'),
            'tanggalKembali' => optional($borrowing->due_date)->format('d/m/Y'),
            'status' => $borrowing->status,
            'purpose' => $borrowing->purpose,
            'image' => $item ? $item->photo : null,
        ];
    }
}
