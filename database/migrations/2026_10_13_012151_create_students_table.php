<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $t->string('nis', 30)->unique();
            $t->string('name');
            $t->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $t->enum('gender', ['L', 'P']);
            $t->date('birth_date')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index('name');
        });
    }

    public function down(): void { Schema::dropIfExists('students'); }
};