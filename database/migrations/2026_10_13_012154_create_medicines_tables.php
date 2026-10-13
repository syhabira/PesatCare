<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->text('indication')->nullable();
            $t->string('medicine_class', 50)->nullable();
            $t->string('symbol')->nullable();
            $t->string('unit', 30)->default('tablet');
            $t->unsignedInteger('low_stock_threshold')->default(10);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('medicine_batches', function (Blueprint $t) {
            $t->id();
            $t->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $t->string('batch_number', 50);
            $t->unsignedInteger('quantity')->default(0); // unsigned: DB menolak stok negatif
            $t->date('expiry_date');
            $t->timestamps();
            $t->unique(['medicine_id', 'batch_number']);
            $t->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
        Schema::dropIfExists('medicines');
    }
};