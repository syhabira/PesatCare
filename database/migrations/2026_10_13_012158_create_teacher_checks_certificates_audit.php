<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_health_checks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teacher_id')->constrained()->restrictOnDelete();
            $t->foreignId('recorded_by')->constrained('users')->restrictOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->date('checked_at');
            $t->decimal('uric_acid', 4, 1)->nullable();
            $t->unsignedSmallInteger('cholesterol')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->index(['teacher_id', 'checked_at']);
        });

        Schema::create('medical_certificates', function (Blueprint $t) {
            $t->id();
            $t->foreignId('visit_id')->unique()->constrained()->restrictOnDelete();
            $t->boolean('status_home')->default(false); // true = pulang
            $t->dateTime('departure_time')->nullable();
            $t->text('additional_notes')->nullable();
            $t->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $t->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action', 20);
            $t->string('entity_type', 100);
            $t->unsignedBigInteger('entity_id');
            $t->json('old_values')->nullable();
            $t->json('new_values')->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->timestamp('created_at')->useCurrent();
            $t->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('medical_certificates');
        Schema::dropIfExists('teacher_health_checks');
    }
};