<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRecord extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'borrowing_id',
        'user_id',
        'return_date',
        'returned_quantity',
        'condition',
        'received_by',
        'status',
        'notes',
        'photos',
        'borrower_name',
        'identity_number',
        'item_name',
        'item_code',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
            'due_date' => 'date',
            'photos' => 'array',
        ];
    }

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'borrowing_id');
    }
    public function fine(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Fine::class, 'return_id');
    }
}
