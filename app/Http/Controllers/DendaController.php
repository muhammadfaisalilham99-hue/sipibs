<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\ReturnRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DendaController extends Controller
{
    private function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    private function deny(): JsonResponse
    {
        return response()->json(['message' => 'Akses ditolak.'], 403);
    }

    private function formatFine(Fine $fine): array
    {
        return [
            'id' => $fine->id,
            'return_id' => $fine->return_id,
            'borrowing_id' => $fine->borrowing_id,
            'user_id' => $fine->user_id,
            'borrower' => $fine->borrower_name ?: '-',
            'identity_number' => $fine->identity_number ?: '-',
            'itemName' => $fine->item_name ?: '-',
            'serial' => $fine->item_code ?: '-',
            'loanId' => $fine->borrowing_id,
            'fineType' => $fine->fine_type,
            'fineAmount' => (string) $fine->fine_amount,
            'returnDate' => optional($fine->return_date)->format('d/m/Y'),
            'dueDate' => optional($fine->due_date)->format('d/m/Y'),
            'loanDate' => optional($fine->borrowing?->borrow_date)->format('d/m/Y') ?: '-',
            'notes' => $fine->notes ?: '-',
            'status' => $fine->status,
            'method' => $fine->payment_method ?: '-',
            'paidAt' => $fine->paid_at ? $fine->paid_at->format('d/m/Y') : '-',
            'createdAt' => optional($fine->created_at)->format('d/m/Y'),
        ];
    }

    public function createFromReturn(Request $request): JsonResponse
    {
        if (! $this->isAdmin()) {
            return $this->deny();
        }

        $validated = $request->validate([
            'return_id' => ['required', 'integer'],
            'fine_type' => ['required', 'string', 'max:100'],
            'fine_amount' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $record = ReturnRecord::findOrFail($validated['return_id']);

        $fine = Fine::updateOrCreate(
            ['return_id' => $record->id],
            [
                'borrowing_id' => $record->borrowing_id,
                'user_id' => $record->user_id ?? optional($record->borrowing)->user_id,
                'borrower_name' => $record->borrower_name ?: optional($record->borrowing?->user)->name,
                'identity_number' => $record->identity_number ?: optional($record->borrowing?->user)->identity_number,
                'item_name' => $record->item_name ?: optional($record->borrowing?->item)->name,
                'item_code' => $record->item_code ?: optional($record->borrowing?->item)->code,
                'return_date' => $record->return_date,
                'due_date' => $record->due_date ?: optional($record->borrowing)->due_date,
                'fine_type' => $validated['fine_type'],
                'fine_amount' => $validated['fine_amount'],
                'notes' => $validated['notes'] ?? $record->notes,
                'status' => 'belum_dibayar',
            ]
        );

        return response()->json([
            'message' => 'Denda berhasil dibuat.',
            'fine' => $this->formatFine($fine->fresh()),
        ], 201);
    }

    public function adminList(): JsonResponse
    {
        if (! $this->isAdmin()) {
            return $this->deny();
        }

        $fines = Fine::orderByDesc('id')->get();

        return response()->json([
            'fines' => $fines->map(fn ($f) => $this->formatFine($f)),
        ]);
    }

    public function myFines(): JsonResponse
    {
        $user = Auth::user();

        $fines = Fine::where('user_id', $user->id)
            ->orWhere(function ($q) use ($user) {
                $q->whereNull('user_id')
                    ->where('identity_number', $user->identity_number);
            })
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'fines' => $fines->map(fn ($f) => $this->formatFine($f)),
        ]);
    }

    public function pay(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'method' => ['required', 'in:qris,cash'],
            'proof' => ['nullable', 'string', 'max:500000'],
        ]);

        $fine = Fine::findOrFail($id);
        $user = Auth::user();

        if ((int) $fine->user_id !== (int) $user->id
            && $fine->identity_number !== $user->identity_number) {
            return response()->json(['message' => 'Denda bukan milik Anda.'], 403);
        }

        if ($fine->status === 'lunas') {
            return response()->json(['message' => 'Denda sudah lunas.'], 422);
        }

        $fine->status = 'lunas';
        $fine->payment_method = $validated['method'];
        $fine->paid_at = now();
        $fine->proof_photo = $validated['proof'] ?? null;
        $fine->save();

        return response()->json([
            'message' => 'Pembayaran denda berhasil dicatat.',
            'fine' => $this->formatFine($fine->fresh()),
        ]);
    }
}