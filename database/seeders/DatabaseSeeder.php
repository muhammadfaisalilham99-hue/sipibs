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
            ['code' => 'MOUSE-001', 'name' => 'Mouse HP USB-2', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 20, 'available_quantity' => 20, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'KBD-004', 'name' => 'Keyboard NuPhy Air75', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 8, 'available_quantity' => 8, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'LAP-020', 'name' => 'Laptop Lenovo Ideapad Slim 3', 'item_category_id' => $categoryIds['KMP'], 'total_quantity' => 12, 'available_quantity' => 12, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Lab Komputer'],
            ['code' => 'HS-432', 'name' => 'Headset Logitech G 432 7.1', 'item_category_id' => $categoryIds['AUD'], 'total_quantity' => 8, 'available_quantity' => 8, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Lab Multimedia'],
            ['code' => 'CAM-4K', 'name' => '4K Webcam 1080P 60fps Mini Video Camera', 'item_category_id' => $categoryIds['AUD'], 'total_quantity' => 15, 'available_quantity' => 15, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Lab Multimedia'],
            ['code' => 'PRJ-EX3', 'name' => 'Epson EX3240 SVGA 3LCD Projector 3200', 'item_category_id' => $categoryIds['PRK'], 'total_quantity' => 4, 'available_quantity' => 4, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Ruang Multimedia'],
            ['code' => 'HDMI-14', 'name' => 'Kabel Black High Speed 1.4 Version Gold-Plated HDMI', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 20, 'available_quantity' => 20, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'VGA-15', 'name' => 'Kabel 0.3m 1.5M 3m VGA To VGA Cable 15 Pin', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 10, 'available_quantity' => 10, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'LAN-06', 'name' => 'Kabel LAN CAT6 UTP Cable Networking', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 6, 'available_quantity' => 6, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'HDM2VGA', 'name' => 'Kabel HDMI to VGA Adapter Gold Plated', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 2, 'available_quantity' => 2, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'PTR-007', 'name' => 'Pen Wireless Remote Controller Laser Pointer', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 7, 'available_quantity' => 7, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'STK-001', 'name' => 'Stop Kontak', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 4, 'available_quantity' => 4, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'TST-468', 'name' => '2pcs Multifunctional network tester 468 network cable', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 14, 'available_quantity' => 14, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'CRP-200', 'name' => 'Tang Crimping Tool RJ45 RJ11 HT-200R', 'item_category_id' => $categoryIds['ELK'], 'total_quantity' => 9, 'available_quantity' => 9, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Gudang Sarpras'],
            ['code' => 'BMB-003', 'name' => 'JBL Boombox 3 Portable Rechargeable Splashproof Bluetooth', 'item_category_id' => $categoryIds['AUD'], 'total_quantity' => 5, 'available_quantity' => 5, 'borrowed_quantity' => 0, 'condition' => 'baik', 'status' => 'tersedia', 'location' => 'Lab Multimedia'],
        ];

        foreach ($items as $item) {
            DB::table('inventory_items')->updateOrInsert(
                ['code' => $item['code']],
                [...$item, 'description' => null, 'damaged_quantity' => 0, 'photo' => null, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
