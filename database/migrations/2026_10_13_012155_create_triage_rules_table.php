<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('triage_rules', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->enum('parameter', ['temperature', 'systolic', 'diastolic', 'pulse', 'disease']);
            $t->enum('operator', ['gte', 'gt', 'lte', 'lt', 'between'])->nullable();
            $t->decimal('value', 6, 1)->nullable();
            $t->decimal('value_max', 6, 1)->nullable();
            $t->foreignId('disease_id')->nullable()->constrained('diseases')->restrictOnDelete();
            $t->enum('level', ['hijau', 'kuning', 'merah']);
            $t->string('message')->nullable();
            $t->unsignedSmallInteger('deadline_minutes')->nullable();
            $t->boolean('is_active')->default(true);
            $t->boolean('is_validated')->default(false);
            $t->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('validated_at')->nullable();
            $t->unsignedInteger('version')->default(1);
            $t->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('triage_rules'); }
};