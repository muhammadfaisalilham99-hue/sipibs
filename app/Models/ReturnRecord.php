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
        'return_date',
        'returned_quantity',
        'condition',
        'received_by',
        'status',
        'notes',
        'photos',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
            'photos' => 'array',
        ];
    }

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class, 'borrowing_id');
    }
}
