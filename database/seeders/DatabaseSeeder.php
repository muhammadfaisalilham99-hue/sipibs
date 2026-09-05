<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@sipibs.test'],
            [
                'name' => 'Admin SIPIBS',
                'identity_number' => 'ADMIN001',
                'password' => 'password123',
                'role' => 'admin',
            ]
        );

        DB::table('admin_profiles')->updateOrInsert(
            ['user_id' => $admin->id],
            [
                'nip' => '198501012010011001',
                'phone' => '+62 812 3456 7890',
                'address' => 'Jl. Pendidikan No. 123, Kelurahan Maju Jaya, Kecamatan Cerdas, Kota Pintar, 12345',
                'position' => 'Administrator System',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@sipibs.test'],
            [
                'name' => 'User SIPIBS',
                'identity_number' => 'USER001',
                'password' => 'password123',
                'role' => 'user',
            ]
        );

        $categories = [
            ['code' => 'ELK', 'name' => 'Elektronik', 'icon' => 'bi-lightning-charge'],
            ['code' => 'KMP', 'name' => 'Komputer', 'icon' => 'bi-pc-display'],
            ['code' => 'AUD', 'name' => 'Audio Visual', 'icon' => 'bi-camera-video'],
            ['code' => 'OLR', 'name' => 'Olahraga', 'icon' => 'bi-trophy'],
            ['code' => 'PRK', 'name' => 'Praktikum', 'icon' => 'bi-flask'],
        ];

        foreach ($categories as $category) {
            DB::table('item_categories')->updateOrInsert(
                ['code' => $category['code']],
                [...$category, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $categoryIds = DB::table('item_categories')->pluck('id', 'code');

        $items = [
            ['code' => 'PRJ-001', 'name' => 'Proyektor Epson XGA', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 15, 'available_quantity' => 14, 'borrowed_quantity' => 1, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Ruang Multimedia'],
            ['code' => 'CAM-002', 'name' => 'Kamera Canon EOS 1300D', 'item_category_id' => $categoryIds['AUD'], 'total_quantity' => 8, 'available_quantity' => 6, 'borrowed_quantity' => 2, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Lab Multimedia'],
            ['code' => 'LAP-003', 'name' => 'Laptop Dell Inspiron 14', 'item_category_id' => $categoryIds['KMP'], 'total_quantity' => 40, 'available_quantity' => 28, 'borrowed_quantity' => 12, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Lab Komputer'],
            ['code' => 'HDM-004', 'name' => 'Kabel HDMI 5M', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 25, 'available_quantity' => 3, 'borrowed_quantity' => 22, 'condition' => 'baik', 'status' => 'hampir_habis', 'location' => 'Gudang Sarpras'],
        ];

        foreach ($items as $item) {
            DB::table('inventory_items')->updateOrInsert(
                ['code' => $item['code']],
                [...$item, 'description' => null, 'damaged_quantity' => 0, 'photo' => null, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
