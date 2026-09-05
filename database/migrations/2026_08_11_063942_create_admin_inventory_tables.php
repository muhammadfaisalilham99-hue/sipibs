<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_category_id')->nullable()->constrained('item_categories')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('total_quantity')->default(0);
            $table->unsignedInteger('available_quantity')->default(0);
            $table->unsignedInteger('borrowed_quantity')->default(0);
            $table->unsignedInteger('damaged_quantity')->default(0);
            $table->enum('condition', ['baik', 'perlu_servis', 'rusak'])->default('baik');
            $table->enum('status', ['tersedia', 'hampir_habis', 'kosong', 'nonaktif'])->default('tersedia');
            $table->string('location')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['menunggu', 'disetujui', 'dipinjam', 'ditolak', 'dikembalikan', 'terlambat'])->default('menunggu');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained('borrowings')->cascadeOnDelete();
            $table->date('return_date');
            $table->unsignedInteger('returned_quantity')->default(1);
            $table->enum('condition', ['baik', 'perlu_servis', 'rusak'])->default('baik');
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['menunggu', 'diterima', 'bermasalah'])->default('menunggu');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nip')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('position')->default('Administrator System');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_profiles');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('borrowings');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('item_categories');
    }
};
