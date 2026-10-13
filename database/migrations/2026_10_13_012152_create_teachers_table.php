<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $t->string('nip', 30)->unique();
            $t->string('name');
            $t->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $t->string('lp_number', 50)->nullable();
            $t->string('blood_type', 3)->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index('name');
        });
    }

    public function down(): void { Schema::dropIfExists('teachers'); }
};