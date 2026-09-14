<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventarisController extends Controller
{
    public function index(): JsonResponse
    {
        $items = InventoryItem::orderBy('name')->get()
            ->map(fn (InventoryItem $item) => $this->formatItem($item))
            ->values();

        return response()->json(['items' => $items]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:inventory_items,code'],
            'category' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'photo' => ['nullable', 'string'],
            'photo_file' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $category = null;
        if (! empty($validated['category'])) {
            $category = DB::table('item_categories')->where('name', $validated['category'])->first();
            if (! $category) {
                $categoryId = DB::table('item_categories')->insertGetId([
                    'code' => strtolower(str_replace(' ', '-', $validated['category'])),
                    'name' => $validated['category'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $category = DB::table('item_categories')->find($categoryId);
            
}
        
}

        $photo = $validated['photo'] ?? null;
        if ($request->hasFile('photo_file')) {
            $photo = '/storage/' . $request->file('photo_file')->store('inventory-items', 'public');
        }

        $stock = (int) $validated['stock'];
        $item = InventoryItem::create([
            'item_category_id' => $category?->id,
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'description' => null,
            'total_quantity' => $stock,
            'available_quantity' => $stock,
            'borrowed_quantity' => 0,
            'damaged_quantity' => 0,
            'condition' => 'baik',
            'status' => $stock > 0 ? 'tersedia' : 'kosong',
            'location' => null,
            'photo' => $photo,
        ]);

        return response()->json(['message' => 'Barang berhasil ditambahkan.', 'item' => $this->formatItem($item->fresh())], 201);
    
}

    public function updatePhoto(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'photo_file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $item = InventoryItem::findOrFail($id);
        $photo = '/storage/' . $request->file('photo_file')->store('inventory-items', 'public');
        $item->update(['photo' => $photo]);

        return response()->json([
            'message' => 'Foto barang berhasil diperbarui.',
            'item' => $this->formatItem($item->fresh()),
        ]);
    }
    public function adjustStock(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:add,sub'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = InventoryItem::findOrFail($id);
        $quantity = (int) $validated['quantity'];

        if ($validated['action'] === 'add') {
            $item->total_quantity += $quantity;
            $item->available_quantity += $quantity;
        } else {
            $reduced = min($quantity, $item->available_quantity);
            $item->available_quantity = max(0, $item->available_quantity - $reduced);
            $item->total_quantity = max(
                $item->available_quantity + $item->borrowed_quantity + $item->damaged_quantity,
                $item->total_quantity - $reduced
            );
        
}

        $item->save();

        return response()->json([
            'message' => 'Stok berhasil diperbarui.',
            'item' => $this->formatItem($item->fresh()),
        ]);
    
}

    protected function formatItem(InventoryItem $item): array
    {
        return [
            'id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,
            'category' => $item->item_category_id ? (string) optional(DB::table('item_categories')->find($item->item_category_id))->name : '-',
            'total_quantity' => $item->total_quantity,
            'available_quantity' => $item->available_quantity,
            'borrowed_quantity' => $item->borrowed_quantity,
            'damaged_quantity' => $item->damaged_quantity,
            'condition' => match ($item->condition) {
                'rusak' => 'Rusak',
                'perlu_servis' => 'Rusak Ringan',
                default => 'Baik',
            },
            'status' => $item->status,
            'location' => $item->location,
            'photo' => $item->photo ? (\Illuminate\Support\Str::startsWith($item->photo, ['http://', 'https://', 'data:']) ? (parse_url($item->photo, PHP_URL_PATH) ?: $item->photo) : (\Illuminate\Support\Str::startsWith($item->photo, '/') ? $item->photo : '/images/' . ltrim($item->photo, '/'))) : null,
        ];
    
}


    public function updateCondition(Request $request, int $id)
    {
        $item = InventoryItem::findOrFail($id);
        $validated = $request->validate([
            'condition' => ['required', 'in:baik,perlu_servis,rusak'],
            'checked_at' => ['required', 'date'],
        ]);

        $item->update(['condition' => $validated['condition']]);

        \App\Models\ItemConditionHistory::create([
            'inventory_item_id' => $item->id,
            'checked_at' => $validated['checked_at'],
            'condition' => $validated['condition'],
            'location' => '',
            'officer' => 'Sarpras SIPIBS',
            'notes' => 'Pemeriksaan kondisi barang.',
        ]);

        return back()->with('success', 'Kondisi barang berhasil diperbarui.');
    }

    public function destroy(string $id): JsonResponse
    {
        $item = InventoryItem::where('id', $id)->orWhere('code', $id)->firstOrFail();
        $item->delete();

        return response()->json(['message' => 'Barang berhasil dihapus permanen.']);
    }

}







