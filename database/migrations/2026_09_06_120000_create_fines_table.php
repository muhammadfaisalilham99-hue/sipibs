<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id')->nullable()->index();
            $table->unsignedBigInteger('borrowing_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('borrower_name')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_code')->nullable();
            $table->string('fine_type');
            $table->unsignedBigInteger('fine_amount')->default(0);
            $table->text('notes')->nullable();
            $table->date('return_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('belum_dibayar');
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->longText('proof_photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};