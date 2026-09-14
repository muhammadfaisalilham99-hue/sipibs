<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemConditionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'checked_at',
        'condition',
        'location',
        'officer',
        'notes',
    ];

    protected $casts = [
        'checked_at' => 'date',
    ];
}
