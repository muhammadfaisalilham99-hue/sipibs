<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    use HasFactory;

    protected $table = 'fines';

    protected $fillable = [
        'return_id',
        'borrowing_id',
        'user_id',
        'borrower_name',
        'identity_number',
        'item_name',
        'item_code',
        'fine_type',
        'fine_amount',
        'notes',
        'return_date',
        'due_date',
        'status',
        'payment_method',
        'paid_at',
        'proof_photo',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'fine_amount' => 'integer',
        ];
    }

    public function returnRecord(): BelongsTo
    {
        return $this->belongsTo(ReturnRecord::class, 'return_id');
    }

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'borrowing_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}