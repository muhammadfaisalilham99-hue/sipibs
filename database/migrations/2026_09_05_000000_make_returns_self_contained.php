<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasReturns = Schema::hasTable('returns');

        if ($hasReturns) {
            Schema::dropIfExists('returns_old');
            DB::statement('ALTER TABLE returns RENAME TO returns_old');
        }

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrowing_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->date('return_date');
            $table->unsignedInteger('returned_quantity')->default(1);
            $table->string('condition');
            $table->string('borrower_name')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_code')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->text('photos')->nullable();
            $table->timestamps();
        });

        if ($hasReturns && Schema::hasTable('returns_old')) {
            DB::statement(
                'INSERT INTO returns
                    (id, borrowing_id, return_date, returned_quantity, `condition`, received_by, status, notes, photos, created_at, updated_at)
                 SELECT
                    id, borrowing_id, return_date, returned_quantity, `condition`, received_by, status, notes, photos, created_at, updated_at
                 FROM returns_old'
            );
            Schema::drop('returns_old');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('returns_old');

        if (Schema::hasTable('returns')) {
            DB::statement('ALTER TABLE returns RENAME TO returns_old');
        }

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrowing_id');
            $table->date('return_date');
            $table->unsignedInteger('returned_quantity');
            $table->string('condition');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->text('photos')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('returns_old')) {
            DB::statement(
                'INSERT INTO returns
                    (id, borrowing_id, return_date, returned_quantity, `condition`, received_by, status, notes, photos, created_at, updated_at)
                 SELECT
                    id, COALESCE(borrowing_id, 0), return_date, returned_quantity, `condition`, received_by, status, notes, photos, created_at, updated_at
                 FROM returns_old'
            );
            Schema::drop('returns_old');
        }
    }
};
