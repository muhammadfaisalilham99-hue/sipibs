<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $table = 'inventory_items';

    protected $fillable = [
        'item_category_id',
        'code',
        'name',
        'description',
        'total_quantity',
        'available_quantity',
        'borrowed_quantity',
        'damaged_quantity',
        'condition',
        'status',
        'location',
        'photo',
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class, 'inventory_item_id');
    }
}
